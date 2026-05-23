<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        if (! $teacher) {
            abort(403);
        }

        $now = now();
        $today = $now->toDateString();
        $timeframe = request('timeframe', 'days');
        $allowedTimeframes = ['days', 'week', 'month'];
        $timeframe = in_array($timeframe, $allowedTimeframes, true) ? $timeframe : 'days';

        $trendStart = match ($timeframe) {
            'week' => $now->copy()->startOfWeek(0)->subWeeks(7)->startOfDay(),
            'month' => $now->copy()->startOfMonth()->subMonths(5)->startOfMonth(),
            default => $now->copy()->subDays(6)->startOfDay(),
        };
        $fromDate = $trendStart->toDateString();

        $totalStudents   = Student::where('user_id', $teacher->id)->count();
        $presentToday    = Attendance::where('user_id', $teacher->id)->where('date', $today)->whereIn('status', ['present', 'late'])->count();
        $absentToday     = Attendance::where('user_id', $teacher->id)->where('date', $today)->where('status', 'absent')->count();
        $lateToday       = Attendance::where('user_id', $teacher->id)->where('date', $today)->where('status', 'late')->count();
        $markedToday     = Attendance::where('user_id', $teacher->id)->where('date', $today)->count();
        $attendanceRate  = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100, 1) : 0;

        // Recent attendance logs (last 10)
        $recentLogs = $this->recentLogsForTeacher($teacher->id);

        // Weekly overview (last 7 days)
        $weeklyData = $this->buildWeeklyData($teacher->id);

        // Students by section (top 8) with classcode
        $sectionCounts = Student::query()
            ->where('user_id', $teacher->id)
            ->where('section', '!=', 'N/A')
            ->whereNotNull('section')
            ->select('section', DB::raw('count(*) as total'))
            ->groupBy('section')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(function ($item) {
                // Extract classcode from section (e.g., "BSIT - A" -> "BSIT-A")
                $item->classcode = str_replace(' - ', '-', $item->section);
                return $item;
            });

        // Top attendants over the last N days (students with at least 1 record)
        $topAttendants = $this->getTopAttendants($teacher->id, $fromDate, $today);

        // Attendance trend (present+late per day) over last N days
        $trendRecords = $this->getTrendRecords($teacher->id, $fromDate, $today);
        $trend = $this->buildTrend($timeframe, $trendRecords, $now);

        Log::info('Dashboard data retrieval', [
            'timeframe' => $timeframe,
            'fromDate' => $fromDate,
            'today' => $today,
            'trendRecords_count' => $trendRecords->count(),
            'trend_count' => $trend->count(),
            'trend_data' => $trend->toArray(),
        ]);

        // Handle AJAX requests for dynamic updates
        if ($request->ajax() || $request->wantsJson()) {
            $chartHtml = $this->generateChartHtml($trend);
            $labelsHtml = $this->generateLabelsHtml($trend);
            
            return response()->json([
                'chart' => $chartHtml,
                'labels' => $labelsHtml,
                'labelCount' => $trend->count(),
            ]);
        }

        return view('dashboard', compact(
            'totalStudents', 'presentToday', 'absentToday', 'lateToday',
            'markedToday', 'attendanceRate', 'recentLogs', 'weeklyData',
            'sectionCounts', 'topAttendants', 'trend', 'timeframe'
        ));
    }

    // Helper: recent logs
    private function recentLogsForTeacher(int $userId): Collection
    {
        return Attendance::with('student')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    // Helper: build weekly data array
    private function buildWeeklyData(int $userId): array
    {
        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayLabel = now()->subDays($i)->format('D');
            $result[] = [
                'date' => $date,
                'formatted_date' => now()->subDays($i)->format('M j'),
                'day' => $dayLabel,
                'present' => Attendance::where('user_id', $userId)->where('date', $date)->where('status', 'present')->count(),
                'absent' => Attendance::where('user_id', $userId)->where('date', $date)->where('status', 'absent')->count(),
                'late' => Attendance::where('user_id', $userId)->where('date', $date)->where('status', 'late')->count(),
            ];
        }
        return $result;
    }

    // Helper: top attendants
    private function getTopAttendants(int $userId, string $fromDate, string $today): Collection
    {
        return Student::query()
            ->where('user_id', $userId)
            ->withCount([
                'attendances as period_total' => fn ($q) => $q->whereBetween('date', [$fromDate, $today]),
                'attendances as period_present' => fn ($q) => $q->whereBetween('date', [$fromDate, $today])->whereIn('status', ['present', 'late']),
            ])
            ->get()
            ->filter(fn (Student $s) => (int) ($s->period_total ?? 0) > 0)
            ->map(function (Student $s) {
                $present = (int) ($s->period_present ?? 0);
                $total = (int) ($s->period_total ?? 0);
                $s->period_pct = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                return $s;
            })
            ->sortByDesc(fn (Student $s) => $s->period_pct)
            ->take(6)
            ->values();
    }

    // Helper: trend records
    private function getTrendRecords(int $userId, string $fromDate, string $today): Collection
    {
        return Attendance::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$fromDate, $today])
            ->whereIn('status', ['present', 'late'])
            ->select('date', DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                $date = is_string($item->date) ? $item->date : $item->date->toDateString();
                return [$date => $item->total];
            });
    }

    private function buildTrend(string $timeframe, Collection $trendRecords, \Carbon\CarbonInterface $now): Collection
    {
        if ($timeframe === 'week') {
            $currentWeekEnd = $now->copy()->endOfWeek(0)->endOfDay();
            $baseWeekStart = $currentWeekEnd->copy()->subWeeks(7)->startOfWeek(0)->startOfDay();
            
            return collect(range(0, 7))->map(function ($i) use ($trendRecords, $baseWeekStart) {
                $weekStart = $baseWeekStart->copy()->addWeeks($i)->startOfDay();
                $weekEnd = $weekStart->copy()->endOfWeek(0)->endOfDay();
                $weekStartStr = $weekStart->toDateString();
                $weekEndStr = $weekEnd->toDateString();

                $weekTotal = $trendRecords
                    ->filter(function ($_, $date) use ($weekStartStr, $weekEndStr) {
                        return $date >= $weekStartStr && $date <= $weekEndStr;
                    })
                    ->sum();

                return [
                    'label' => $weekStart->format('M j'),
                    'total' => $weekTotal,
                ];
            });
        }

        if ($timeframe === 'month') {
            $baseMonthStart = $now->copy()->startOfMonth()->subMonths(5)->startOfMonth();
            
            return collect(range(0, 5))->map(function ($i) use ($trendRecords, $baseMonthStart) {
                $monthStart = $baseMonthStart->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();
                $monthStartStr = $monthStart->toDateString();
                $monthEndStr = $monthEnd->toDateString();

                $monthTotal = $trendRecords->filter(function ($_, $date) use ($monthStartStr, $monthEndStr) {
                    return $date >= $monthStartStr && $date <= $monthEndStr;
                })->sum();

                return [
                    'label' => $monthStart->format('M Y'),
                    'total' => $monthTotal,
                ];
            });
        }

        // Default: days (last 7 days)
        return collect(range(0, 6))
            ->map(function ($i) use ($trendRecords, $now) {
                $d = $now->copy()->subDays(6 - $i)->toDateString();
                $dayTotal = $trendRecords->get($d, 0);
                
                return [
                    'date' => $d,
                    'label' => $now->copy()->subDays(6 - $i)->format('M j'),
                    'total' => $dayTotal,
                ];
            })
            ->values();
    }

    // Helper: generate chart HTML for AJAX response
    private function generateChartHtml(Collection $trend): string
    {
        $trendSeries = $trend->values();
        $trendCount = $trendSeries->count();
        $viewWidth = 1000;
        $viewHeight = 250;
        $paddingX = 36;
        $paddingTop = 18;
        $paddingBottom = 34;
        $plotWidth = $viewWidth - ($paddingX * 2);
        $plotHeight = $viewHeight - $paddingTop - $paddingBottom;
        $trendMax = max(1, (int) $trendSeries->max('total') ?? 0);
        $trendMin = (int) $trendSeries->min('total') ?? 0;
        $trendRange = max(1, $trendMax - $trendMin);
        $trendPoints = [];
        $gradientId = 'lineGrad_' . uniqid();

        foreach ($trendSeries as $index => $row) {
            $x = $trendCount > 1
                ? $paddingX + $plotWidth * $index / ($trendCount - 1)
                : $paddingX + $plotWidth / 2;

            $value = (int) ($row['total'] ?? 0);
            $normalized = $trendRange === 0 ? 0.5 : ($value - $trendMin) / $trendRange;
            $y = $paddingTop + $plotHeight * (1 - $normalized);

            $trendPoints[] = [
                'x' => $x,
                'y' => $y,
                'value' => $value,
                'label' => $row['label'] ?? '',
            ];
        }

        $trendLinePath = '';
        $trendAreaPath = '';
        $highlightPoint = null;
        if (! empty($trendPoints)) {
            $trendLinePath = 'M ' . $trendPoints[0]['x'] . ',' . $trendPoints[0]['y'];
            foreach (array_slice($trendPoints, 1) as $point) {
                $trendLinePath .= ' L ' . $point['x'] . ',' . $point['y'];
            }

            $trendAreaPath = $trendLinePath . ' L ' . $trendPoints[array_key_last($trendPoints)]['x'] . ',' . $viewHeight . ' L ' . $trendPoints[0]['x'] . ',' . $viewHeight . ' Z';
            $highlightIndex = $trendSeries->search(fn ($row) => (int) ($row['total'] ?? 0) === (int) $trendSeries->max('total'));
            $highlightPoint = $highlightIndex !== false ? $trendPoints[$highlightIndex] : null;

            if ($highlightPoint) {
                $labelWidth = 72;
                $labelHeight = 28;
                $tooltipGap = 12;
                $tooltipX = max(8, min($viewWidth - $labelWidth - 8, $highlightPoint['x'] - $labelWidth / 2));
                $tooltipY = max(8, $highlightPoint['y'] - $labelHeight - $tooltipGap);
                $highlightPoint['tooltipX'] = $tooltipX;
                $highlightPoint['tooltipY'] = $tooltipY;
                $highlightPoint['labelWidth'] = $labelWidth;
                $highlightPoint['labelHeight'] = $labelHeight;
            }
        }

        ob_start();
        ?>
        <div style="position: absolute; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="border-top: 1px dashed #e2e8f0;"></div>
            <div style="border-top: 1px dashed #e2e8f0;"></div>
            <div style="border-top: 1px dashed #e2e8f0;"></div>
            <div style="border-top: 1px dashed #e2e8f0;"></div>
            <div style="border-top: 1px dashed #e2e8f0;"></div>
            <div style="border-top: 1px dashed #e2e8f0;"></div>
        </div>
        <svg width="100%" height="100%" viewBox="0 0 1000 250" preserveAspectRatio="none" style="position: absolute; top:0; left:0; z-index: 1; overflow: visible;">
            <defs>
                <linearGradient id="<?php echo e($gradientId); ?>" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="rgba(34,197,94,0.4)" />
                    <stop offset="100%" stop-color="rgba(34,197,94,0)" />
                </linearGradient>
            </defs>
            <?php if(!empty($trendPoints)): ?>
                <path d="<?php echo e($trendAreaPath); ?>" fill="url(#<?php echo e($gradientId); ?>)" />
                <path d="<?php echo e($trendLinePath); ?>" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <?php foreach($trendPoints as $point): ?>
                    <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                <?php endforeach; ?>
                <?php if($highlightPoint): ?>
                    <rect x="<?php echo e($highlightPoint['x'] - 15); ?>" y="<?php echo e($highlightPoint['y']); ?>" width="30" height="<?php echo e($viewHeight - $highlightPoint['y']); ?>" rx="4" fill="#22c55e" opacity="0.14" />
                    <rect x="<?php echo e($highlightPoint['tooltipX']); ?>" y="<?php echo e($highlightPoint['tooltipY']); ?>" width="<?php echo e($highlightPoint['labelWidth']); ?>" height="<?php echo e($highlightPoint['labelHeight']); ?>" rx="8" fill="#1e293b" />
                    <polygon points="<?php echo e($highlightPoint['x'] - 5); ?>,<?php echo e($highlightPoint['tooltipY'] + $highlightPoint['labelHeight']); ?> <?php echo e($highlightPoint['x'] + 5); ?>,<?php echo e($highlightPoint['tooltipY'] + $highlightPoint['labelHeight']); ?> <?php echo e($highlightPoint['x']); ?>,<?php echo e($highlightPoint['tooltipY'] + $highlightPoint['labelHeight'] + 8); ?>" fill="#1e293b" />
                    <text x="<?php echo e($highlightPoint['tooltipX'] + ($highlightPoint['labelWidth'] / 2)); ?>" y="<?php echo e($highlightPoint['tooltipY'] + 19); ?>" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="600"><?php echo e($highlightPoint['value']); ?></text>
                <?php endif; ?>
            <?php else: ?>
                <text x="500" y="125" text-anchor="middle" fill="#64748b" font-size="14">No data available</text>
            <?php endif; ?>
        </svg>
        <?php
        return ob_get_clean();
    }

    // Helper: generate labels HTML for AJAX response
    private function generateLabelsHtml(Collection $trend): string
    {
        $trendLabels = $trend->pluck('label')->values();
        $timeframe = request('timeframe', 'days');
        
        ob_start();
        foreach($trendLabels as $label) {
            echo '<span>' . e($label) . '</span>';
        }
        return ob_get_clean();
    }

    // Get students by section with today's attendance status
    public function getClassStudents(Request $request)
    {
        $section = $request->query('section');
        $teacher = Auth::user();

        if (!$section) {
            return response()->json(['error' => 'Section is required'], 400);
        }

        $today = Carbon::today()->toDateString();

        $students = Student::where('user_id', $teacher->id)
            ->where('section', $section)
            ->get()
            ->map(function ($student) use ($today) {
                $attendance = Attendance::where('student_id_number', $student->student_id_number)
                    ->where('date', $today)
                    ->first();

                return [
                    'id' => $student->id,
                    'student_id_number' => $student->student_id_number,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'today_status' => $attendance?->status,
                ];
            });

        return response()->json(['students' => $students]);
    }
}
