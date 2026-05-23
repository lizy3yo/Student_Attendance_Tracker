<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'section'   => ['nullable', 'integer'],
            'student_section' => ['nullable', 'string'],
        ], [], [
            'section' => 'course and block',
            'student_section' => 'program, year and block',
        ]);

        $teacher = Auth::user();
        if (! $teacher) {
            abort(403);
        }

        $section = $request->input('section');
        $studentSection = $request->input('student_section');

        $classFilters = SchoolClass::query()
            ->where('user_id', $teacher->id)
            ->orderBy('class_name')
            ->orderBy('block')
            ->get(['id', 'class_name', 'class_code', 'block']);

        if ($section && ! $classFilters->contains('id', (int) $section)) {
            $section = null;
        }

        // Get unique program, year, and block combinations from student sections
        $studentSections = Student::where('user_id', $teacher->id)
            ->whereNotNull('section')
            ->where('section', '!=', 'N/A')
            ->distinct()
            ->pluck('section')
            ->sort()
            ->values();

        if ($studentSection && ! $studentSections->contains($studentSection)) {
            $studentSection = null;
        }

        $availableDatesQuery = Attendance::query()
            ->where('user_id', $teacher->id);

        if ($section) {
            $availableDatesQuery->where('class_id', (int) $section);
        }

        $availableDates = $availableDatesQuery
            ->select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(fn ($date) => \Carbon\Carbon::parse($date)->toDateString())
            ->values();

        if ($availableDates->isNotEmpty()) {
            $defaultFrom = $availableDates->first();
            $defaultTo = $availableDates->last();

            $dateFrom = $request->input('date_from', $defaultFrom);
            $dateTo = $request->input('date_to', $defaultTo);

            if (! $availableDates->contains($dateFrom)) {
                $dateFrom = $defaultFrom;
            }

            if (! $availableDates->contains($dateTo)) {
                $dateTo = $defaultTo;
            }

            if ($dateFrom > $dateTo) {
                $dateFrom = $defaultFrom;
                $dateTo = $defaultTo;
            }
        } else {
            $dateFrom = now()->toDateString();
            $dateTo = now()->toDateString();
        }

        $query = Student::where('user_id', $teacher->id)
            ->with(['classes:id,class_name,class_code,block']);

        if ($section) {
            $query->whereHas('classes', fn ($q) => $q->where('classes.id', (int) $section));
        }

        if ($studentSection) {
            $query->where('section', $studentSection);
        }

        $students = $query->orderBy('last_name')->get()->map(function (Student $student) use ($dateFrom, $dateTo, $section) {
            $records = $student->attendances()
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->when($section, fn ($q) => $q->where('class_id', (int) $section))
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

            $student->reportCourseBlock = $student->year ? str_replace(' - ', $student->year, $student->section) : ($student->section ?: 'N/A');

            return $student;
        });

        // Class-wide stats
        $classTotal   = $students->sum('reportTotal');
        $classPresent = $students->sum('reportPresent');
        $classRate    = $classTotal > 0 ? round(($classPresent / $classTotal) * 100, 1) : 0;

        return view('reports.index', compact(
            'students', 'dateFrom', 'dateTo', 'section', 'classFilters',
            'classTotal', 'classPresent', 'classRate', 'availableDates',
            'studentSection', 'studentSections'
        ));
    }
}
