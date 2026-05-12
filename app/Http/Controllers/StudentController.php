<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name',  'like', "%{$s}%")
                  ->orWhere('student_id_number', 'like', "%{$s}%")
                  ->orWhere('section', 'like', "%{$s}%");
            });
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->orderBy('last_name')->paginate(15)->withQueryString();
        $sections = Student::where('user_id', Auth::id())->distinct()->pluck('section')->sort()->values();

        return view('students.index', compact('students', 'sections'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', 'unique:students,student_id_number'],
            'first_name'        => ['required', 'string', 'max:100'],
            'last_name'         => ['required', 'string', 'max:100'],
            'section'           => ['required', 'string', 'max:50'],
            'email'             => ['nullable', 'email', 'max:150'],
        ]);

        $data['user_id'] = Auth::id();
        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $data = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', "unique:students,student_id_number,{$student->id}"],
            'first_name'        => ['required', 'string', 'max:100'],
            'last_name'         => ['required', 'string', 'max:100'],
            'section'           => ['required', 'string', 'max:50'],
            'email'             => ['nullable', 'email', 'max:150'],
        ]);

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student removed successfully.');
    }
}
