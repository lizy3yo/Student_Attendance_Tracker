<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
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

        // Students by section (top 8)
        $sectionCounts = Student::query()
            ->where('user_id', $teacher->id)
            ->select('section', DB::raw('count(*) as total'))
            ->groupBy('section')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Top attendants over the last N days (students with at least 1 record)
        $topAttendants = $this->getTopAttendants($teacher->id, $fromDate, $today);

        // Attendance trend (present+late per day) over last N days
        $trendRecords = $this->getTrendRecords($teacher->id, $fromDate, $today);
        $trend = $this->buildTrend($timeframe, $trendRecords, $now, $fromDate, $today);

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
            ->select('date')
            ->orderBy('date')
            ->get();
    }

    // Helper: build trend aggregation
    private function buildTrend(string $timeframe, Collection $trendRecords, \Carbon\CarbonInterface $now, string $fromDate, string $today): Collection
    {
        if ($timeframe === 'week') {
            // use numeric 0 for Sunday to avoid analyzer constant warning
            $baseWeekStart = $now->copy()->startOfWeek(0)->subWeeks(7)->startOfDay();

            return collect(range(0, 7))->map(function ($i) use ($trendRecords, $baseWeekStart) {
                $weekStart = $baseWeekStart->copy()->addWeeks($i)->startOfDay();
                $weekEnd = $weekStart->copy()->endOfWeek();

                $weekTotal = $trendRecords
                    ->filter(fn ($record) => $record->date->between($weekStart, $weekEnd))
                    ->count();

                return [
                    'date' => $weekStart->toDateString(),
                    'label' => $weekStart->format('M j'),
                    'total' => $weekTotal,
                ];
            })->values();

        } elseif ($timeframe === 'month') {
            $baseMonthStart = $now->copy()->startOfMonth()->subMonths(5)->startOfMonth();

            return collect(range(0, 5))->map(function ($i) use ($trendRecords, $baseMonthStart) {
                $monthStart = $baseMonthStart->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();

                $monthTotal = $trendRecords->filter(fn ($record) => $record->date->between($monthStart, $monthEnd))->count();

                return [
                    'date' => $monthStart->toDateString(),
                    'label' => $monthStart->format('M Y'),
                    'total' => $monthTotal,
                ];
            })->values();
        }

        return collect(range(0, 6))
            ->map(function ($i) use ($trendRecords, $now) {
                $d = $now->copy()->subDays(6 - $i)->toDateString();
                $dayTotal = $trendRecords->filter(fn ($record) => $record->date->toDateString() === $d)->count();
                return [
                    'date' => $d,
                    'label' => $now->copy()->subDays(6 - $i)->format('M j'),
                    'total' => $dayTotal,
                ];
            })
            ->values();
    }
}
