<x-app-layout>
    <x-slot name="title">Add Student</x-slot>

    <x-app-banner title="Add new student">
        <x-slot name="subtitle">Fill in the student details below.</x-slot>
    </x-app-banner>

    <div class="app-page">
        <div style="max-width:640px;">
            <p style="margin-bottom:1rem;">
                <a href="{{ route('students.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:500;">
                    <i data-lucide="arrow-left" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Back to students
                </a>
            </p>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('students.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="student_id_number">Student ID Number *</label>
                            <input class="form-control" id="student_id_number" type="text"
                                   name="student_id_number" value="{{ old('student_id_number') }}"
                                   placeholder="e.g. 2024-00123" required>
                            @error('student_id_number')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="section">Course and Block *</label>
                            <input class="form-control" id="section" type="text"
                                   name="section" value="{{ old('section') }}"
                                   placeholder="e.g. BSIT-3A" required>
                            @error('section')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="first_name">Given Name *</label>
                            <input class="form-control" id="first_name" type="text"
                                   name="first_name" value="{{ old('first_name') }}"
                                   placeholder="Juan" required>
                            @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="last_name">Last Name *</label>
                            <input class="form-control" id="last_name" type="text"
                                   name="last_name" value="{{ old('last_name') }}"
                                   placeholder="Dela Cruz" required>
                            @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address (optional)</label>
                        <input class="form-control" id="email" type="email"
                               name="email" value="{{ old('email') }}"
                               placeholder="student@school.edu.ph">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;gap:.75rem;margin-top:1rem;">
                        <button class="btn btn-primary" type="submit">
                            <i data-lucide="plus" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                            Add Student
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
