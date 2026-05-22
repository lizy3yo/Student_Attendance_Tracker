<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();

        if (! $teacher) {
            abort(403);
        }

        $query = SchoolClass::where('user_id', $teacher->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('class_name', 'like', "%{$search}%")
                    ->orWhere('class_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('semester') && $request->input('semester') !== 'All') {
            $query->where('semester', $request->input('semester'));
        }

        if ($request->filled('year') && $request->input('year') !== 'All') {
            $query->where('year', $request->input('year'));
        }

        $classes = $query->withCount('students')->orderByDesc('created_at')->get();

        $totalClasses = SchoolClass::where('user_id', $teacher->id)->count();
        $totalStudents = Student::where('user_id', $teacher->id)->whereHas('classes')->count();
        $totalEnrollments = $classes->sum('students_count');
        $avgClassSize = $totalClasses > 0 ? round($totalEnrollments / $totalClasses) : 0;
        $instructorsCount = $totalClasses > 0 ? 1 : 0;

        $semesters = SchoolClass::where('user_id', $teacher->id)
            ->distinct()
            ->pluck('semester')
            ->filter()
            ->sort()
            ->values();

        $academicYears = SchoolClass::where('user_id', $teacher->id)
            ->distinct()
            ->pluck('academic_year')
            ->filter()
            ->sort()
            ->values();

        return view('classes.index', compact(
            'classes',
            'totalClasses',
            'totalStudents',
            'avgClassSize',
            'instructorsCount',
            'semesters',
            'academicYears'
        ));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();

        if (! $teacher) {
            abort(403);
        }

        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:100'],
            'class_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('classes', 'class_code')->where('user_id', $teacher->id),
            ],
            'year' => ['required', 'in:1,2,3,4'],
            'block' => ['required', 'regex:/^[A-Z]$/'],
            'semester' => ['required', 'string', 'max:20'],
            'academic_year' => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        SchoolClass::create([
            'user_id' => $teacher->id,
            'class_name' => $data['class_name'],
            'class_code' => $data['class_code'],
            'year' => (int) $data['year'],
            'block' => strtoupper($data['block']),
            'semester' => $data['semester'],
            'academic_year' => $data['academic_year'],
            'capacity' => (int) $data['capacity'],
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function update(Request $request, SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:100'],
            'class_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('classes', 'class_code')
                    ->where('user_id', Auth::id())
                    ->ignore($class->id),
            ],
            'year' => ['required', 'in:1,2,3,4'],
            'block' => ['required', 'regex:/^[A-Z]$/'],
            'semester' => ['required', 'string', 'max:20'],
            'academic_year' => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $class->update([
            'class_name' => $data['class_name'],
            'class_code' => $data['class_code'],
            'year' => (int) $data['year'],
            'block' => strtoupper($data['block']),
            'semester' => $data['semester'],
            'academic_year' => $data['academic_year'],
            'capacity' => (int) $data['capacity'],
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $teacherId = Auth::id();

        $data = $request->validate([
            'class_ids' => ['required', 'array', 'min:1'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
        ]);

        SchoolClass::where('user_id', $teacherId)
            ->whereIn('id', $data['class_ids'])
            ->delete();

        return redirect()->route('classes.index')->with('success', 'Selected classes deleted successfully.');
    }

    public function show(Request $request, SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $tab = $request->input('tab', 'roster');
        $date = $request->input('date', today()->toDateString());

        $totalEnrolledCount = $class->students()->count();

        $enrolledQuery = $class->students();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $enrolledQuery->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id_number', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");
            });
        }

        $enrolledStudents = $enrolledQuery->orderBy('last_name')->get();

        $allEnrolledIds = $class->students()->pluck('students.id');
        $availableStudents = Student::where('user_id', Auth::id())
            ->whereNotIn('id', $allEnrolledIds)
            ->orderBy('last_name')
            ->get();

        $attendances = Attendance::where('class_id', $class->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        foreach ($enrolledStudents as $student) {
            $student->attendanceRecord = $attendances->get($student->id);
        }

        // Attendance summary for today (for KPI stat cards)
        $todayAttendances = Attendance::where('class_id', $class->id)
            ->where('date', today()->toDateString())
            ->get();
        $presentCount = $todayAttendances->where('status', 'present')->count();
        $lateCount    = $todayAttendances->where('status', 'late')->count();
        $absentCount  = $todayAttendances->where('status', 'absent')->count();

        return view('classes.show', compact(
            'class', 'enrolledStudents', 'availableStudents',
            'tab', 'date', 'totalEnrolledCount',
            'presentCount', 'lateCount', 'absentCount'
        ));
    }

    public function enroll(Request $request, SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $studentIds = $request->input('student_ids');

        $ownedCount = Student::where('user_id', Auth::id())
            ->whereIn('id', $studentIds)
            ->count();

        if ($ownedCount !== count($studentIds)) {
            abort(403, 'Unauthorized student assignment');
        }

        $class->students()->syncWithoutDetaching($studentIds);

        return redirect()->route('classes.show', ['class' => $class->id, 'tab' => 'roster'])
            ->with('success', 'Students assigned to class roster successfully.');
    }

    public function unenroll(SchoolClass $class, Student $student)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        if ($student->user_id !== Auth::id()) {
            abort(403);
        }

        $class->students()->detach($student->id);

        return redirect()->route('classes.show', ['class' => $class->id, 'tab' => 'roster'])
            ->with('success', 'Student removed from class roster successfully.');
    }

    public function bulkUnenroll(Request $request, SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'exists:students,id'],
        ]);

        $ownedCount = Student::where('user_id', Auth::id())
            ->whereIn('id', $data['student_ids'])
            ->count();

        if ($ownedCount !== count($data['student_ids'])) {
            abort(403);
        }

        $class->students()->detach($data['student_ids']);

        return redirect()->route('classes.show', ['class' => $class->id, 'tab' => 'roster'])
            ->with('success', 'Selected students removed from class roster successfully.');
    }

    public function saveAttendance(Request $request, SchoolClass $class)
    {
        if ($class->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->toDateString() !== today()->toDateString()) {
                        $fail('Attendance can only be recorded or modified for the current date (today).');
                    }
                },
            ],
            'attendance' => ['nullable', 'array'],
            'attendance.*.status' => ['nullable', 'in:present,absent,late'],
            'attendance.*.time_in' => ['nullable', 'string'],
        ]);

        $date = $request->input('date');
        $attendanceData = $request->input('attendance', []);

        try {
            DB::transaction(function () use ($class, $date, $attendanceData) {
                $enrolledStudentIds = $class->students()->pluck('students.id')->toArray();
                $records = [];
                $studentIdsToDelete = [];

                foreach ($enrolledStudentIds as $studentId) {
                    $record = $attendanceData[$studentId] ?? [];
                    $status = $record['status'] ?? null;

                    if (empty($status)) {
                        $studentIdsToDelete[] = $studentId;
                    } else {
                        $timeIn = null;
                        if (in_array($status, ['present', 'late']) && !empty($record['time_in'])) {
                            try {
                                $timeIn = Carbon::parse($record['time_in'])->format('H:i:s');
                            } catch (\Exception $e) {
                                // fallback if parsing fails
                            }
                        }

                        $records[] = [
                            'student_id' => $studentId,
                            'class_id' => $class->id,
                            'user_id' => Auth::id(),
                            'date' => $date,
                            'status' => $status,
                            'remarks' => $record['remarks'] ?? null,
                            'time_in' => $timeIn,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (! empty($studentIdsToDelete)) {
                    Attendance::where('class_id', $class->id)
                        ->where('date', $date)
                        ->whereIn('student_id', $studentIdsToDelete)
                        ->delete();
                }

                if (! empty($records)) {
                    foreach ($records as $record) {
                        Attendance::upsert(
                            [$record],
                            ['student_id', 'class_id', 'date'],
                            ['status', 'remarks', 'time_in', 'updated_at']
                        );
                    }
                }
            });

            return redirect()->route('classes.show', ['class' => $class->id, 'tab' => 'attendance', 'date' => $date])
                ->with('success', 'Attendance saved successfully for ' . Carbon::parse($date)->format('F j, Y') . '.');
        } catch (\Exception $e) {
            return redirect()->route('classes.show', ['class' => $class->id, 'tab' => 'attendance', 'date' => $date])
                ->with('error', 'Error saving attendance: ' . $e->getMessage());
        }
    }
}
