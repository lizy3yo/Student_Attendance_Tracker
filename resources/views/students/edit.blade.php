<x-app-layout>
    <x-slot name="title">Edit Student</x-slot>

    <x-app-banner title="Edit student">
        <x-slot name="subtitle">Update the information for {{ $student->full_name }}.</x-slot>
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
                <form method="POST" action="{{ route('students.update', $student) }}">
                    @csrf @method('PUT')
                    @php
                        // Parse first_name
                        $firstNameVal = trim($student->first_name);
                        $lastSpaceIndex = strrpos($firstNameVal, ' ');
                        if ($lastSpaceIndex !== false) {
                            $editFirstName = substr($firstNameVal, 0, $lastSpaceIndex);
                            $editMiddleName = substr($firstNameVal, $lastSpaceIndex + 1);
                        } else {
                            $editFirstName = $firstNameVal;
                            $editMiddleName = '';
                        }

                        // Parse last_name
                        $lastNameVal = trim($student->last_name);
                        $editLastName = $lastNameVal;
                        $editSuffix = '';
                        $suffixes = ['Jr', 'Sr', 'II', 'III', 'IV'];
                        foreach ($suffixes as $s) {
                            if (str_ends_with($lastNameVal, ' ' . $s)) {
                                $editLastName = substr($lastNameVal, 0, -strlen(' ' . $s));
                                $editSuffix = $s;
                                break;
                            }
                        }
                    @endphp
                    <div class="form-row">
                        <div class="form-group" style="flex:1;">
                            <label class="form-label" for="student_id_number">Student ID Number *</label>
                            <input class="form-control" id="student_id_number" type="text"
                                   name="student_id_number" value="{{ old('student_id_number', $student->student_id_number) }}"
                                   placeholder="e.g. 2024-00123" required>
                            @error('student_id_number')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="first_name">Given Name *</label>
                            <input class="form-control" id="first_name" type="text"
                                   name="first_name" value="{{ old('first_name', $editFirstName) }}" required>
                            @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="middle_name">MIDDLE NAME (Optional)</label>
                            <input class="form-control" id="middle_name" type="text"
                                   name="middle_name" value="{{ old('middle_name', $editMiddleName) }}">
                            @error('middle_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="last_name">Last Name *</label>
                            <input class="form-control" id="last_name" type="text"
                                   name="last_name" value="{{ old('last_name', $editLastName) }}" required>
                            @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="suffix">Suffix</label>
                            <select class="form-control" id="suffix" name="suffix">
                                <option value="">None</option>
                                <option value="Jr" {{ old('suffix', $editSuffix) === 'Jr' ? 'selected' : '' }}>Jr.</option>
                                <option value="Sr" {{ old('suffix', $editSuffix) === 'Sr' ? 'selected' : '' }}>Sr.</option>
                                <option value="II" {{ old('suffix', $editSuffix) === 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('suffix', $editSuffix) === 'III' ? 'selected' : '' }}>III</option>
                                <option value="IV" {{ old('suffix', $editSuffix) === 'IV' ? 'selected' : '' }}>IV</option>
                            </select>
                            @error('suffix')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address (optional)</label>
                        <input class="form-control" id="email" type="email"
                               name="email" value="{{ old('email', $student->email) }}"
                               readonly
                               placeholder="studentnumber@gordoncollege.edu.ph">
                        <div style="font-size:0.8125rem;color:var(--text-muted);margin-top:0.35rem;">
                            Email is auto-generated as <strong>{student_number}@gordoncollege.edu.ph</strong>.
                        </div>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;gap:.75rem;margin-top:1rem;">
                        <button class="btn btn-primary" type="submit">
                            <i data-lucide="save" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
    <script>
    (function(){
        const studentId = document.getElementById('student_id_number');
        const emailInput = document.getElementById('email');
        if (studentId && emailInput) {
            studentId.addEventListener('input', function() {
                const id = (studentId.value || '').trim();
                emailInput.value = id ? `${id}@gordoncollege.edu.ph` : '';
            });
        }
    })();
    </script>
</x-app-layout>
