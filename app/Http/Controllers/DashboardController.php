<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $today = today()->toDateString();

        $totalStudents   = Student::where('user_id', $teacher->id)->count();
        $presentToday    = Attendance::where('user_id', $teacher->id)->where('date', $today)->where('status', 'present')->count();
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

        return view('dashboard', compact(
            'totalStudents', 'presentToday', 'absentToday', 'lateToday',
            'markedToday', 'attendanceRate', 'recentLogs', 'weeklyData'
        ));
    }
}
