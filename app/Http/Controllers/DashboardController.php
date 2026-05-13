<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $today = today()->toDateString();
        $rangeDays = 30;
        $fromDate = now()->subDays($rangeDays - 1)->toDateString();

        $totalStudents   = Student::where('user_id', $teacher->id)->count();
        $presentToday    = Attendance::where('user_id', $teacher->id)->where('date', $today)->whereIn('status', ['present', 'late'])->count();
        $absentToday     = Attendance::where('user_id', $teacher->id)->where('date', $today)->where('status', 'absent')->count();
        $lateToday       = Attendance::where('user_id', $teacher->id)->where('date', $today)->where('status', 'late')->count();
        $markedToday     = Attendance::where('user_id', $teacher->id)->where('date', $today)->count();
        $attendanceRate  = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100, 1) : 0;

        // Recent attendance logs (last 10)
        $recentLogs = Attendance::with('student')
            ->where('user_id', $teacher->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Weekly overview (last 7 days)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayLabel = now()->subDays($i)->format('D');
            $weeklyData[] = [
                'date'    => $date,
                'day'     => $dayLabel,
                'present' => Attendance::where('user_id', $teacher->id)->where('date', $date)->where('status', 'present')->count(),
                'absent'  => Attendance::where('user_id', $teacher->id)->where('date', $date)->where('status', 'absent')->count(),
                'late'    => Attendance::where('user_id', $teacher->id)->where('date', $date)->where('status', 'late')->count(),
            ];
        }

        // Students by section (top 8)
        $sectionCounts = Student::query()
            ->where('user_id', $teacher->id)
            ->select('section', DB::raw('count(*) as total'))
            ->groupBy('section')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Top attendants over the last N days (students with at least 1 record)
        $topAttendants = Student::query()
            ->where('user_id', $teacher->id)
            ->withCount([
                'attendances as period_total' => fn ($q) => $q->whereBetween('date', [$fromDate, $today]),
                'attendances as period_present' => fn ($q) => $q->whereBetween('date', [$fromDate, $today])->whereIn('status', ['present', 'late']),
            ])
            ->get()
            ->filter(fn (Student $s) => (int) $s->period_total > 0)
            ->map(function (Student $s) {
                $s->period_pct = round(((int) $s->period_present / (int) $s->period_total) * 100, 1);
                return $s;
            })
            ->sortByDesc(fn (Student $s) => $s->period_pct)
            ->take(6)
            ->values();

        // Attendance trend (present+late per day) over last N days
        $trendRows = Attendance::query()
            ->where('user_id', $teacher->id)
            ->whereBetween('date', [$fromDate, $today])
            ->whereIn('status', ['present', 'late'])
            ->select('date', DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($r) => (string) $r->date);

        $trend = collect(range($rangeDays - 1, 0))
            ->reverse()
            ->map(function ($i) use ($trendRows) {
                $d = now()->subDays($i)->toDateString();
                return [
                    'date' => $d,
                    'label' => now()->subDays($i)->format('M j'),
                    'total' => (int) ($trendRows[$d]->total ?? 0),
                ];
            })
            ->values();

        return view('dashboard', compact(
            'totalStudents', 'presentToday', 'absentToday', 'lateToday',
            'markedToday', 'attendanceRate', 'recentLogs', 'weeklyData',
            'sectionCounts', 'topAttendants', 'trend', 'rangeDays'
        ));
    }
}
