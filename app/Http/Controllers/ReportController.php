<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $teacher    = Auth::user();
        $dateFrom   = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo     = $request->input('date_to',   now()->toDateString());
        $section    = $request->input('section');

        $query = Student::where('user_id', $teacher->id);
        if ($section) {
            $query->where('section', $section);
        }

        $students = $query->orderBy('last_name')->get()->map(function ($student) use ($dateFrom, $dateTo) {
            $records = $student->attendances()
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->get();

            $total   = $records->count();
            $present = $records->whereIn('status', ['present', 'late'])->count();
            $absent  = $records->where('status', 'absent')->count();
            $late    = $records->where('status', 'late')->count();

            $student->reportTotal   = $total;
            $student->reportPresent = $present;
            $student->reportAbsent  = $absent;
            $student->reportLate    = $late;
            $student->reportPercent = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            return $student;
        });

        $sections = Student::where('user_id', $teacher->id)->distinct()->pluck('section')->sort()->values();

        // Class-wide stats
        $classTotal   = $students->sum('reportTotal');
        $classPresent = $students->sum('reportPresent');
        $classRate    = $classTotal > 0 ? round(($classPresent / $classTotal) * 100, 1) : 0;

        return view('reports.index', compact(
            'students', 'dateFrom', 'dateTo', 'section', 'sections',
            'classTotal', 'classPresent', 'classRate'
        ));
    }
}
