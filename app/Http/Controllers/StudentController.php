<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    private const STUDENT_EMAIL_DOMAIN = 'gordoncollege.edu.ph';

    public function index(Request $request)
    {
        $editStudent = null;
        if ($request->filled('edit')) {
            $editStudent = Student::where('user_id', Auth::id())
                ->where('id', $request->input('edit'))
                ->first();
        }

        $query = Student::with('classes')->where('user_id', Auth::id());

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

        return view('students.index', compact('students', 'sections', 'editStudent'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {

        if ($request->filled('student_id_number')) {
            $idVal = preg_replace('/\D/', '', $request->input('student_id_number'));
            $request->merge(['student_id_number' => $idVal]);
        }

        if ($request->filled('middle_name')) {
            $request->merge([
                'first_name' => $request->input('first_name') . ' ' . $request->input('middle_name'),
            ]);
        }
        if ($request->filled('suffix')) {
            $request->merge([
                'last_name' => $request->input('last_name') . ' ' . $request->input('suffix'),
            ]);
        }

        $data = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', 'unique:students,student_id_number'],
            'first_name'        => ['required', 'string', 'max:100'],
            'last_name'         => ['required', 'string', 'max:100'],
            'section'           => ['nullable', 'string', 'max:50'],
            'email'             => ['nullable', 'email', 'max:150', 'ends_with:@' . self::STUDENT_EMAIL_DOMAIN],
        ], [], [
            'section' => 'course and block',
        ]);

        $data['user_id'] = Auth::id();
        if (empty($data['section'])) {
            $data['section'] = 'N/A';
        }
        if (empty($data['email'])) {
            $data['email'] = $data['student_id_number'] . '@' . self::STUDENT_EMAIL_DOMAIN;
        }
        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        // Edit is handled as a modal on the index page
        return redirect()->route('students.index', ['edit' => $student->id]);
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        if ($request->filled('student_id_number')) {
            $idVal = preg_replace('/\D/', '', $request->input('student_id_number'));
            $request->merge(['student_id_number' => $idVal]);
        }

        if ($request->filled('middle_name')) {
            $request->merge([
                'first_name' => $request->input('first_name') . ' ' . $request->input('middle_name'),
            ]);
        }
        if ($request->filled('suffix')) {
            $request->merge([
                'last_name' => $request->input('last_name') . ' ' . $request->input('suffix'),
            ]);
        }

        $data = $request->validate([
            'student_id_number' => ['required', 'string', 'max:30', "unique:students,student_id_number,{$student->id}"],
            'first_name'        => ['required', 'string', 'max:100'],
            'last_name'         => ['required', 'string', 'max:100'],
            'section'           => ['nullable', 'string', 'max:50'],
            'email'             => ['nullable', 'email', 'max:150', 'ends_with:@' . self::STUDENT_EMAIL_DOMAIN],
        ], [], [
            'section' => 'course and block',
        ]);

        if ($request->has('section')) {
            if (empty($data['section'])) {
                $data['section'] = 'N/A';
            }
        } else {
            unset($data['section']);
        }

        if (empty($data['email'])) {
            $data['email'] = $data['student_id_number'] . '@' . self::STUDENT_EMAIL_DOMAIN;
        }
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
