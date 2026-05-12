<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        $teacher = Auth::user();

        $students = Student::where('user_id', $teacher->id)
            ->orderBy('last_name')
            ->get()
            ->map(function ($student) use ($date) {
                $student->attendanceRecord = $student->attendances()
                    ->where('date', $date)
                    ->first();
                return $student;
            });

        return view('attendance.index', compact('students', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'       => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:present,absent,late'],
        ]);

        $teacher = Auth::user();
        $date    = $request->input('date');

        foreach ($request->input('attendance') as $studentId => $record) {
            // Only process students belonging to this teacher
            $student = Student::where('id', $studentId)
                ->where('user_id', $teacher->id)
                ->first();

            if (!$student) continue;

            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                [
                    'user_id' => $teacher->id,
                    'status'  => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', 'Attendance saved successfully for ' . \Carbon\Carbon::parse($date)->format('F j, Y') . '.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['date' => ['required', 'date']]);

        Attendance::where('user_id', Auth::id())
            ->where('date', $request->date)
            ->delete();

        return redirect()->route('attendance.index', ['date' => $request->date])
            ->with('success', 'Attendance records for this date have been cleared.');
    }
}
