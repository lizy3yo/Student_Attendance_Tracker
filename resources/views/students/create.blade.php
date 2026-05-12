<x-app-layout>
    <x-slot name="title">Add Student</x-slot>

    <div style="max-width:640px;">
        <div style="margin-bottom:1.5rem;">
            <a href="{{ route('students.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:.85rem;">
                ← Back to Students
            </a>
            <h2 style="font-size:1.4rem;font-weight:800;margin-top:.5rem;">Add New Student</h2>
            <p style="color:var(--text-muted);font-size:.85rem;">Fill in the student details below.</p>
        </div>

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
                            <label class="form-label" for="section">Section *</label>
                            <input class="form-control" id="section" type="text"
                                   name="section" value="{{ old('section') }}"
                                   placeholder="e.g. BSIT-3A" required>
                            @error('section')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="first_name">First Name *</label>
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
                        <button class="btn btn-primary" type="submit">➕ Add Student</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
