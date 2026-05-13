<x-app-layout>
    <x-slot name="title">Students</x-slot>

    <x-app-banner title="Student records">
        <x-slot name="subtitle">Manage all enrolled students in your class.</x-slot>
        <x-slot name="actions">
            <button
                type="button"
                class="btn btn-primary"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'create-student')"
            >
                <i data-lucide="plus" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                Add student
            </button>
        </x-slot>
    </x-app-banner>

    <div class="app-page"
        x-data="{
            emailDomain: 'gordoncollege.edu.ph',
            createStudent: {
                student_id_number: @js(old('student_id_number','')),
                email: @js(old('email',''))
            },
            editStudent: {
                id: @js(old('edit_student_id', $editStudent?->id)),
                student_id_number: @js(old('student_id_number', $editStudent?->student_id_number ?? '')),
                first_name: @js(old('first_name', $editStudent?->first_name ?? '')),
                last_name: @js(old('last_name', $editStudent?->last_name ?? '')),
                section: @js(old('section', $editStudent?->section ?? '')),
                email: @js(old('email', $editStudent?->email ?? ''))
            },
            syncCreateEmail() {
                const id = (this.createStudent.student_id_number || '').trim();
                this.createStudent.email = id ? `${id}@${this.emailDomain}` : '';
            },
            openEdit(id, studentIdNumber, firstName, lastName, section, email) {
                this.editStudent = {
                    id: id,
                    student_id_number: studentIdNumber,
                    first_name: firstName,
                    last_name: lastName,
                    section: section,
                    email: email
                }
                $dispatch('open-modal', 'edit-student')
            }
        }"
        x-init="
            if (createStudent && createStudent.student_id_number && (!createStudent.email || createStudent.email.endsWith('@' + emailDomain))) {
                syncCreateEmail()
            }
            if (editStudent && editStudent.id && !(@js($errors->any()) && @js(old('student_form')) === 'create')) {
                $dispatch('open-modal', 'edit-student')
            }
        "
    >
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.5rem;">
            <form method="GET" action="{{ route('students.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                <div style="flex:1;min-width:200px;">
                    <label class="form-label">Search</label>
                    <input class="form-control" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Name, Student ID, Section…">
                </div>
                <div style="min-width:160px;">
                    <label class="form-label">Section</label>
                    <select class="form-control" name="section" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;">
                    @if(request()->hasAny(['search','section']))
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i data-lucide="x" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title" style="display:flex;align-items:center;gap:.5rem;">
                <i data-lucide="users" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                All Students
            </span>
            <span style="font-size:.8rem;color:var(--text-muted);">{{ $students->total() }} record(s)</span>
        </div>

        @if($students->isEmpty())
            <div class="empty-state">
                <div class="icon" aria-hidden="true">
                    <i data-lucide="graduation-cap" data-size="34"></i>
                </div>
                <h3>No students found</h3>
                <p>Add your first student to get started.</p>
                <button
                    type="button"
                    class="btn btn-primary"
                    style="margin-top:1rem;"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-student')"
                >
                    <i data-lucide="plus" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Add Student
                </button>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Section</th>
                            <th>Email</th>
                            <th>Attendance %</th>
                            <th>Today</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $i => $student)
                            @php
                                $pct = $student->attendancePercentage();
                                $today = $student->todayStatus();
                            @endphp
                            <tr>
                                <td style="color:var(--text-muted);">{{ $students->firstItem() + $i }}</td>
                                <td>
                                    <span class="badge badge-muted" style="font-family:monospace;">
                                        {{ $student->student_id_number }}
                                    </span>
                                </td>
                                <td style="font-weight:600;">{{ $student->full_name }}</td>
                                <td><span class="badge badge-muted">{{ $student->section }}</span></td>
                                <td style="color:var(--text-muted);font-size:.8rem;">{{ $student->email ?? '—' }}</td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <span class="{{ $pct >= 75 ? 'text-success' : 'text-danger' }}" style="font-weight:700;font-size:.85rem;">{{ $pct }}%</span>
                                        <div class="progress-bar-wrap" style="width:60px;">
                                            <div class="progress-bar {{ $pct >= 75 ? 'progress-success' : 'progress-danger' }}"
                                                 data-progress="{{ $pct }}"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($today)
                                        <span class="badge badge-{{ $today === 'present' ? 'success' : ($today === 'absent' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($today) }}
                                        </span>
                                    @else
                                        <span class="badge badge-muted">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex;gap:.4rem;justify-content:center;">
                                        <button
                                            type="button"
                                            class="btn btn-secondary btn-sm btn-icon"
                                            data-tooltip="Edit"
                                            x-on:click.prevent="openEdit(
                                                @js($student->id),
                                                @js($student->student_id_number),
                                                @js($student->first_name),
                                                @js($student->last_name),
                                                @js($student->section),
                                                @js($student->email)
                                            )"
                                        >
                                            <i data-lucide="edit-3" data-size="18"></i>
                                        </button>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}"
                                              onsubmit="return confirm('Remove {{ addslashes($student->full_name) }}? This will also delete their attendance records.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-icon" data-tooltip="Delete">
                                                <i data-lucide="trash-2" data-size="18"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem 1.5rem;">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <x-modal name="create-student" :show="$errors->any() && old('student_form') === 'create'" focusable maxWidth="2xl">
        <form method="POST" action="{{ route('students.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="student_form" value="create">

            <div class="modern-card-header" style="margin-bottom:1rem;">
                <h2 class="modern-card-title" style="font-size:1.05rem;">Add new student</h2>
                <button type="button" class="btn btn-secondary btn-sm btn-icon" x-on:click="$dispatch('close')" aria-label="Close">
                    <i data-lucide="x" data-size="18"></i>
                </button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="student_id_number">Student ID Number *</label>
                    <input
                        class="form-control"
                        id="student_id_number"
                        type="text"
                        name="student_id_number"
                        x-model="createStudent.student_id_number"
                        x-on:input="syncCreateEmail()"
                        placeholder="e.g. 2024-00123"
                        required
                    >
                    @error('student_id_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="section">Section *</label>
                    <input
                        class="form-control"
                        id="section"
                        type="text"
                        name="section"
                        value="{{ old('section') }}"
                        placeholder="e.g. BSIT-3A"
                        required
                    >
                    @error('section')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="first_name">First Name *</label>
                    <input
                        class="form-control"
                        id="first_name"
                        type="text"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        placeholder="Juan"
                        required
                    >
                    @error('first_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="last_name">Last Name *</label>
                    <input
                        class="form-control"
                        id="last_name"
                        type="text"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        placeholder="Dela Cruz"
                        required
                    >
                    @error('last_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address (optional)</label>
                <input
                    class="form-control"
                    id="email"
                    type="email"
                    name="email"
                    x-model="createStudent.email"
                    readonly
                    placeholder="studentnumber@gordoncollege.edu.ph"
                >
                <div style="font-size:0.8125rem;color:var(--text-muted);margin-top:0.35rem;">
                    Email is auto-generated as <strong>{student_number}@gordoncollege.edu.ph</strong>.
                </div>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1rem;flex-wrap:wrap;">
                <button class="btn btn-primary" type="submit">
                    <i data-lucide="save" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Add Student
                </button>
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    Cancel
                </button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-student" :show="$errors->any() && old('student_form') === 'edit'" focusable maxWidth="2xl">
        <form method="POST" :action="`{{ url('/students') }}/${editStudent.id}`" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="student_form" value="edit">
            <input type="hidden" name="edit_student_id" :value="editStudent.id">

            <div class="modern-card-header" style="margin-bottom:1rem;">
                <h2 class="modern-card-title" style="font-size:1.05rem;">Edit student</h2>
                <button type="button" class="btn btn-secondary btn-sm btn-icon" x-on:click="$dispatch('close')" aria-label="Close">
                    <i data-lucide="x" data-size="18"></i>
                </button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="edit_student_id_number">Student ID Number *</label>
                    <input
                        class="form-control"
                        id="edit_student_id_number"
                        type="text"
                        name="student_id_number"
                        x-model="editStudent.student_id_number"
                        placeholder="e.g. 2024-00123"
                        required
                    >
                    @error('student_id_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_section">Section *</label>
                    <input
                        class="form-control"
                        id="edit_section"
                        type="text"
                        name="section"
                        x-model="editStudent.section"
                        placeholder="e.g. BSIT-3A"
                        required
                    >
                    @error('section')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="edit_first_name">First Name *</label>
                    <input
                        class="form-control"
                        id="edit_first_name"
                        type="text"
                        name="first_name"
                        x-model="editStudent.first_name"
                        placeholder="Juan"
                        required
                    >
                    @error('first_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_last_name">Last Name *</label>
                    <input
                        class="form-control"
                        id="edit_last_name"
                        type="text"
                        name="last_name"
                        x-model="editStudent.last_name"
                        placeholder="Dela Cruz"
                        required
                    >
                    @error('last_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_email">Email Address (optional)</label>
                <input
                    class="form-control"
                    id="edit_email"
                    type="email"
                    name="email"
                    x-model="editStudent.email"
                    placeholder="student@school.edu.ph"
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1rem;flex-wrap:wrap;">
                <button class="btn btn-primary" type="submit">
                    <i data-lucide="save" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Save changes
                </button>
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    Cancel
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
