<x-app-layout>
    <x-slot name="title">Students</x-slot>

    <style>
        .bulk-modal-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }
    </style>

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

    @php
        $editFirstName = '';
        $editMiddleName = '';
        $editLastName = '';
        $editSuffix = '';
        if (isset($editStudent) && $editStudent) {
            $firstNameVal = trim($editStudent->first_name);
            $lastSpaceIndex = strrpos($firstNameVal, ' ');
            if ($lastSpaceIndex !== false) {
                $editFirstName = substr($firstNameVal, 0, $lastSpaceIndex);
                $editMiddleName = substr($firstNameVal, $lastSpaceIndex + 1);
            } else {
                $editFirstName = $firstNameVal;
            }

            $lastNameVal = trim($editStudent->last_name);
            $editLastName = $lastNameVal;
            $suffixes = ['Jr', 'Sr', 'II', 'III', 'IV'];
            foreach ($suffixes as $s) {
                if (str_ends_with($lastNameVal, ' ' . $s)) {
                    $editLastName = substr($lastNameVal, 0, -strlen(' ' . $s));
                    $editSuffix = $s;
                    break;
                }
            }
        }
    @endphp

    <div class="app-page"
        x-data="{
            emailDomain: 'gordoncollege.edu.ph',
            showDeleteModal: false,
            deleteStudentName: '',
            deleteFormAction: '',
            
            triggerDelete(name, action) {
                this.deleteStudentName = name;
                this.deleteFormAction = action;
                this.showDeleteModal = true;
            },
            
            confirmDelete() {
                document.getElementById('delete-student-form').action = this.deleteFormAction;
                document.getElementById('delete-student-form').submit();
            },
            selectedStudentName: '',
            selectedStudentClasses: [],
            selectedStudent: {
                id: null,
                student_id_number: '',
                first_name: '',
                last_name: '',
                section: '',
                email: ''
            },
            createStudent: {
                student_id_number: @js(old('student_id_number','')),
                email: @js(old('email',''))
            },
            editStudent: {
                id: @js(old('edit_student_id', $editStudent?->id)),
                student_id_number: @js(old('student_id_number', $editStudent?->student_id_number ?? '')),
                first_name: @js(old('first_name', $editFirstName ?? '')),
                middle_name: @js(old('middle_name', $editMiddleName ?? '')),
                last_name: @js(old('last_name', $editLastName ?? '')),
                suffix: @js(old('suffix', $editSuffix ?? '')),
                section: @js(old('section', $editStudent?->section ?? '')),
                email: @js(old('email', $editStudent?->email ?? ''))
            },
            syncCreateEmail() {
                const id = (this.createStudent.student_id_number || '').trim();
                this.createStudent.email = id ? `${id}@${this.emailDomain}` : '';
            },
            syncEditEmail() {
                const id = (this.editStudent.student_id_number || '').trim();
                this.editStudent.email = id ? `${id}@${this.emailDomain}` : '';
            },
            openEdit(id, studentIdNumber, firstName, lastName, section, email) {
                // Parse firstName (Given Name + Middle Name)
                let firstNameVal = (firstName || '').trim();
                let editFirstName = firstNameVal;
                let editMiddleName = '';
                const lastSpaceIndex = firstNameVal.lastIndexOf(' ');
                if (lastSpaceIndex !== -1) {
                    editFirstName = firstNameVal.substring(0, lastSpaceIndex).trim();
                    editMiddleName = firstNameVal.substring(lastSpaceIndex + 1).trim();
                }

                // Parse lastName (Last Name + Suffix)
                let lastNameVal = (lastName || '').trim();
                let editLastName = lastNameVal;
                let editSuffix = '';
                const suffixes = ['Jr', 'Sr', 'II', 'III', 'IV'];
                for (let s of suffixes) {
                    if (lastNameVal.endsWith(' ' + s)) {
                        editLastName = lastNameVal.substring(0, lastNameVal.length - s.length - 1).trim();
                        editSuffix = s;
                        break;
                    }
                }

                this.editStudent = {
                    id: id,
                    student_id_number: studentIdNumber,
                    first_name: editFirstName,
                    middle_name: editMiddleName,
                    last_name: editLastName,
                    suffix: editSuffix,
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
                        <div style="position:relative;">
                            <input class="form-control" type="text" name="search" value="{{ request('search') }}" placeholder="Name, Student ID, Course and Block…" style="padding-right:2.5rem;">
                            @if(request('search'))
                                <a href="{{ route('students.index', array_filter(['section' => request('section')])) }}" 
                                   style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;text-decoration:none;transition:background-color 0.2s, color 0.2s;"
                                   title="Clear search"
                                   onmouseover="this.style.color='var(--text-main)';this.style.background='rgba(0,0,0,0.05)';"
                                   onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent';"
                                >
                                    <i data-lucide="x" data-size="16"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div style="min-width:160px;">
                        <label class="form-label">Course and Block</label>
                        <select class="form-control" name="section" onchange="this.form.submit()">
                            <option value="">All Courses and Blocks</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title" style="display:flex;align-items:center;gap:.5rem;">
                    <i data-lucide="users" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    All Students
                </span>
                <span style="font-size:.8rem;color:var(--text-muted);">{{ $students->total() }} student(s)</span>
            </div>

            @if($students->isEmpty())
                <div class="empty-state">
                    <div class="icon" aria-hidden="true">
                        <i data-lucide="graduation-cap" data-size="34"></i>
                    </div>
                    <h3>No students found</h3>
                    <p>Add your first student to get started.</p>
                    <button type="button" class="btn btn-primary" style="margin-top:1rem;" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-student')">
                        <i data-lucide="plus" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                        Add Student
                    </button>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course and Block</th>
                                <th>Email</th>
                                <th style="text-align: right; padding-right: 1.5rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr style="cursor: pointer;"
                                    data-id="{{ $student->id }}"
                                    data-student-id-number="{{ $student->student_id_number }}"
                                    data-first-name="{{ $student->first_name }}"
                                    data-last-name="{{ $student->last_name }}"
                                    data-section="{{ $student->section }}"
                                    data-email="{{ $student->email }}"
                                    data-classes="{{ $student->classes->map(fn($c) => ['class_code' => $c->class_code, 'class_name' => $c->class_name])->toJson() }}"
                                    x-on:click="
                                        selectedStudentName = $el.dataset.firstName + ' ' + $el.dataset.lastName;
                                        selectedStudentClasses = JSON.parse($el.dataset.classes);
                                        selectedStudent = {
                                            id: $el.dataset.id,
                                            student_id_number: $el.dataset.studentIdNumber,
                                            first_name: $el.dataset.firstName,
                                            last_name: $el.dataset.lastName,
                                            section: $el.dataset.section,
                                            email: $el.dataset.email
                                        };
                                        $dispatch('open-modal', 'view-classes');
                                    "
                                    title="Click to view enrolled classes"
                                >
                                    <td>{{ $student->student_id_number }}</td>
                                    <td style="color: var(--primary-dark); font-weight: 600;">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </td>
                                    <td>{{ $student->section }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td style="text-align: right; padding-right: 1.5rem; white-space: nowrap;">
                                        <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                            <button type="button" class="btn btn-secondary btn-sm" style="border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 0.35rem; height: 32px; padding: 0.25rem 0.65rem;"
                                                    x-on:click.stop="openEdit($el.closest('tr').dataset.id, $el.closest('tr').dataset.studentIdNumber, $el.closest('tr').dataset.firstName, $el.closest('tr').dataset.lastName, $el.closest('tr').dataset.section, $el.closest('tr').dataset.email)">
                                                <i data-lucide="edit-3" data-size="14"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" style="border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 0.35rem; height: 32px; padding: 0.25rem 0.65rem;"
                                                    x-on:click.stop="triggerDelete($el.closest('tr').dataset.firstName + ' ' + $el.closest('tr').dataset.lastName, `{{ url('/students') }}/${$el.closest('tr').dataset.id}`)">
                                                <i data-lucide="trash-2" data-size="14"></i>
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:1rem 1.5rem;">{{ $students->links() }}</div>
            @endif
        </div>

    <x-modal name="create-student" :show="$errors->any() && old('student_form') === 'create'" focusable maxWidth="2xl">
        <form id="create-student-form" method="POST" action="{{ route('students.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="student_form" value="create">

            <div class="modern-card-header" style="margin-bottom:1rem;">
                <h2 class="modern-card-title" style="font-size:1.05rem;">Add new student</h2>
                {{-- standardized modal close button is rendered by the modal component --}}
            </div>

            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label" for="student_id_number">Student ID Number *</label>
                    <input
                        class="form-control"
                        id="student_id_number"
                        type="text"
                        name="student_id_number"
                        inputmode="numeric"
                        pattern="\d+"
                        oninput="this.value = this.value.replace(/\D/g, '')"
                        x-model="createStudent.student_id_number"
                        x-on:input="syncCreateEmail()"
                        placeholder="e.g. 202400123"
                        required
                    >
                    @error('student_id_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>



            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="first_name">Given Name *</label>
                    <input
                        class="form-control"
                        id="first_name"
                        type="text"
                        name="first_name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        value="{{ old('first_name') }}"
                        placeholder="Juan"
                        required
                    >
                    @error('first_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="middle_name">MIDDLE NAME (Optional)</label>
                    <input
                        class="form-control"
                        id="middle_name"
                        type="text"
                        name="middle_name"
                        placeholder="Macalincag"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        value="{{ old('middle_name') }}"
                    >
                    @error('middle_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="last_name">Last Name *</label>
                    <input
                        class="form-control"
                        id="last_name"
                        type="text"
                        name="last_name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        value="{{ old('last_name') }}"
                        placeholder="Dela Cruz"
                        required
                    >
                    @error('last_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="suffix">Suffix</label>
                    <select class="form-control" id="suffix" name="suffix">
                        <option value="">None</option>
                        <option value="Jr" {{ old('suffix') === 'Jr' ? 'selected' : '' }}>Jr.</option>
                        <option value="Sr" {{ old('suffix') === 'Sr' ? 'selected' : '' }}>Sr.</option>
                        <option value="II" {{ old('suffix') === 'II' ? 'selected' : '' }}>II</option>
                        <option value="III" {{ old('suffix') === 'III' ? 'selected' : '' }}>III</option>
                        <option value="IV" {{ old('suffix') === 'IV' ? 'selected' : '' }}>IV</option>
                    </select>
                    @error('suffix')
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

            <div style="display:flex;gap:.75rem;margin-top:1rem;flex-wrap:wrap;justify-content:flex-end;align-items:center;">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    Cancel
                </button>
                <button class="btn btn-primary" type="submit">
                    <i data-lucide="save" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Add Student
                </button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-student" :show="$errors->any() && old('student_form') === 'edit'" focusable maxWidth="2xl">
        <form id="edit-student-form" method="POST" :action="`{{ url('/students') }}/${editStudent.id}`" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="student_form" value="edit">
            <input type="hidden" name="edit_student_id" :value="editStudent.id">

            <div class="modern-card-header" style="margin-bottom:1rem;">
                <h2 class="modern-card-title" style="font-size:1.05rem;">Edit student</h2>
                {{-- standardized modal close button is rendered by the modal component --}}
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label" for="edit_student_id_number">Student ID Number *</label>
                    <input
                        class="form-control"
                        id="edit_student_id_number"
                        type="text"
                        name="student_id_number"
                        inputmode="numeric"
                        pattern="\d+"
                        oninput="this.value = this.value.replace(/\D/g, '')"
                        x-model="editStudent.student_id_number"
                        x-on:input="syncEditEmail()"
                        placeholder="e.g. 202400123"
                        required
                    >
                    @error('student_id_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="edit_first_name">Given Name *</label>
                    <input
                        class="form-control"
                        id="edit_first_name"
                        type="text"
                        name="first_name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        x-model="editStudent.first_name"
                        placeholder="Juan"
                        required
                    >
                    @error('first_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_middle_name">MIDDLE NAME (Optional)</label>
                    <input
                        class="form-control"
                        id="edit_middle_name"
                        type="text"
                        name="middle_name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        x-model="editStudent.middle_name"
                        placeholder="Macalincag"
                    >
                    @error('middle_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="edit_last_name">Last Name *</label>
                    <input
                        class="form-control"
                        id="edit_last_name"
                        type="text"
                        name="last_name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s'\-]/g, '')"
                        x-model="editStudent.last_name"
                        placeholder="Dela Cruz"
                        required
                    >
                    @error('last_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_suffix">Suffix</label>
                    <select class="form-control" id="edit_suffix" name="suffix" x-model="editStudent.suffix">
                        <option value="">None</option>
                        <option value="Jr">Jr.</option>
                        <option value="Sr">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                    </select>
                    @error('suffix')
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

            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1.5rem;flex-wrap:wrap;gap:.75rem;width:100%;">
                <button type="button" class="btn btn-danger"
                        x-on:click.prevent="$dispatch('close'); triggerDelete(editStudent.first_name + ' ' + editStudent.last_name, `{{ url('/students') }}/${editStudent.id}`)">
                    <i data-lucide="trash-2" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Remove
                </button>
                <div style="display:flex;gap:.75rem;">
                    <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                        Cancel
                    </button>
                    <button class="btn btn-primary" type="submit">
                        <i data-lucide="save" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                        Save changes
                    </button>
                </div>
            </div>
        </form>
    </x-modal>

    <form id="delete-student-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <x-modal name="view-classes" focusable maxWidth="2xl">
        <div class="p-6">
            <div class="modern-card-header" style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <div style="background: rgba(34, 197, 94, 0.1); color: var(--primary-dark); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="book-open" data-size="18"></i>
                </div>
                <div>
                    <h2 class="modern-card-title" style="font-size: 1.1rem; font-weight: 700; margin: 0;" x-text="selectedStudentName + ' - Enrolled Classes'"></h2>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">List of classes this student is currently assigned to.</div>
                </div>
            </div>

            <template x-if="selectedStudentClasses.length > 0">
                <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #fafafa;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                                <th style="padding: 0.65rem 0.85rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left; width: 100px;">Class Code</th>
                                <th style="padding: 0.65rem 0.85rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left;">Class Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in selectedStudentClasses">
                                <tr style="border-bottom: 1px solid var(--border-color); background: #ffffff;">
                                    <td style="padding: 0.75rem 0.85rem; font-size: 0.85rem; font-weight: 700; color: var(--text-main);" x-text="item.class_code"></td>
                                    <td style="padding: 0.75rem 0.85rem; font-size: 0.85rem; color: var(--text-muted);" x-text="item.class_name"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>

            <template x-if="selectedStudentClasses.length === 0">
                <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted);">
                    <div style="background: #f1f5f9; color: #64748b; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                        <i data-lucide="info" data-size="20"></i>
                    </div>
                    <div style="font-weight: 600; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.25rem;">No enrolled classes</div>
                    <p style="font-size: 0.8rem; line-height: 1.4; margin: 0;">This student is not currently enrolled in any classes. Assign them to a class via the Classes tab.</p>
                </div>
            </template>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; gap: 0.75rem; flex-wrap: wrap;">
                <button type="button" class="btn btn-secondary" style="border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem; font-weight: 500;"
                        x-on:click="$dispatch('close'); openEdit(selectedStudent.id, selectedStudent.student_id_number, selectedStudent.first_name, selectedStudent.last_name, selectedStudent.section, selectedStudent.email)">
                    <i data-lucide="edit-3" data-size="16" style="vertical-align: middle;"></i>
                    Edit Student
                </button>
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')" style="border-radius: 8px; font-weight: 500;">
                    Close
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Delete Student Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="bulk-modal-container"
         x-cloak
         x-on:keydown.escape.window="showDeleteModal = false"
         style="display: none;">
        <!-- Backdrop overlay -->
        <div x-show="showDeleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showDeleteModal = false"
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

        <!-- Modal Content -->
        <div x-show="showDeleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 420px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
            
            <!-- Icon & Header -->
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff1f2; border: 1px solid #fecdd3;">
                    <i data-lucide="trash-2" style="width: 20px; height: 20px; color: #ef4444;"></i>
                </div>
                <div style="flex: 1; padding-top: 0.125rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Remove Student</h3>
                    <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0 0 0.75rem 0; font-family: system-ui, -apple-system, sans-serif;">
                        Are you sure you want to remove <span style="font-weight: 600; color: #0f172a;" x-text="deleteStudentName"></span>?
                    </p>
                    <p style="font-size: 0.825rem; color: #ef4444; line-height: 1.4; margin: 0; font-family: system-ui, -apple-system, sans-serif; font-weight: 500;">
                        All attendance records associated with this student will also be deleted. This action cannot be undone.
                    </p>
                </div>
            </div>

            <!-- Actions Footer -->
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" @click="showDeleteModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                    Cancel
                </button>
                <button type="button" @click="confirmDelete(); showDeleteModal = false;" class="btn" 
                        style="background: #ef4444; color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.filter = 'brightness(0.95)';"
                        onmouseout="this.style.filter = 'brightness(1)';">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

<script>
// Client-side validation and sanitization for Student modals
(function(){
    function setupFormValidation(formSelector, isEdit = false) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const studentId = form.querySelector('[name="student_id_number"]');
        const firstName = form.querySelector('[name="first_name"]');
        const middleName = form.querySelector('[name="middle_name"]');
        const lastName = form.querySelector('[name="last_name"]');

        function clearValidity(el){ if(!el) return; el.setCustomValidity(''); }

        [studentId, firstName, middleName, lastName].forEach(el=>{
            if(!el) return;
            el.addEventListener('input', ()=> clearValidity(el));
            el.addEventListener('blur', ()=> clearValidity(el));
        });

        form.addEventListener('submit', function(e){
            // Student ID: required, numbers only
            if (!studentId || !/^\d+$/.test(studentId.value.trim())){
                e.preventDefault();
                studentId.setCustomValidity('Please enter the Student ID number using digits only.');
                studentId.reportValidity();
                studentId.focus();
                return false;
            }

            // Name fields: only letters, spaces, apostrophes, hyphens
            const nameRe = /^[A-Za-z\s'\-]+$/;
            if (!firstName || !nameRe.test(firstName.value.trim())){
                e.preventDefault();
                firstName.setCustomValidity('First name must contain only letters, spaces, hyphens, or apostrophes.');
                firstName.reportValidity();
                firstName.focus();
                return false;
            }

            if (middleName && middleName.value.trim() && !nameRe.test(middleName.value.trim())){
                e.preventDefault();
                middleName.setCustomValidity('Middle name must contain only letters, spaces, hyphens, or apostrophes.');
                middleName.reportValidity();
                middleName.focus();
                return false;
            }

            if (!lastName || !nameRe.test(lastName.value.trim())){
                e.preventDefault();
                lastName.setCustomValidity('Last name must contain only letters, spaces, hyphens, or apostrophes.');
                lastName.reportValidity();
                lastName.focus();
                return false;
            }

            // All clear, allow submission
            return true;
        });
    }

    setupFormValidation('#create-student-form');
    setupFormValidation('#edit-student-form', true);
})();
</script>
