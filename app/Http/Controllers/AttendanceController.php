<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        $teacher = Auth::user();

        $students = Student::where('user_id', $teacher->id)
            ->with(['attendances' => function ($query) use ($date) {
                $query->whereDate('date', $date);
            }])
            ->orderBy('last_name')
            ->get()
            ->map(function ($student) {
                $student->attendanceRecord = $student->attendances->first();
                return $student;
            });

        return view('attendance.index', compact('students', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'       => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['nullable', 'in:present,absent,late'],
        ]);

        $teacher = Auth::user();
        $date    = $request->input('date');

        try {
            DB::transaction(function () use ($request, $teacher, $date) {
                foreach ($request->input('attendance') as $studentId => $record) {
                    $studentId = (int)$studentId;
                    $status = $record['status'] ?? null;
                    
                    // Skip if no status (pending)
                    if (empty($status)) {
                        continue;
                    }
                    
                    // Only process students belonging to this teacher
                    $student = Student::where('id', $studentId)
                        ->where('user_id', $teacher->id)
                        ->first();

                    if (!$student) continue;

                    // Use upsert for better atomicity
                    Attendance::upsert(
                        [[
                            'student_id' => $studentId,
                            'user_id' => $teacher->id,
                            'date' => $date,
                            'status' => $status,
                            'remarks' => $record['remarks'] ?? null,
                        ]],
                        ['student_id', 'date'], // unique key columns
                        ['status', 'remarks', 'updated_at'] // columns to update
                    );
                }
            });

            // If AJAX request, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attendance saved successfully for ' . \Carbon\Carbon::parse($date)->format('F j, Y') . '.'
                ]);
            }

            return redirect()->route('attendance.index', ['date' => $date])
                ->with('success', 'Attendance saved successfully for ' . \Carbon\Carbon::parse($date)->format('F j, Y') . '.');
        } catch (\Exception $e) {
            Log::error('Attendance Save Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving attendance: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('attendance.index', ['date' => $date])
                ->with('error', 'Error saving attendance: ' . $e->getMessage());
        }
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
