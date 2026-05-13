<x-app-layout>
    <x-slot name="title">Students</x-slot>

    <x-app-banner title="Student records">
        <x-slot name="subtitle">Manage all enrolled students in your class.</x-slot>
        <x-slot name="actions">
            <a href="{{ route('students.create') }}" class="btn btn-primary">➕ Add student</a>
        </x-slot>
    </x-app-banner>

    <div class="app-page">
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
                    <select class="form-control" name="section">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;">
                    <button class="btn btn-primary" type="submit">🔍 Search</button>
                    @if(request()->hasAny(['search','section']))
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">✕ Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">👥 All Students</span>
            <span style="font-size:.8rem;color:var(--text-muted);">{{ $students->total() }} record(s)</span>
        </div>

        @if($students->isEmpty())
            <div class="empty-state">
                <div class="icon">🎓</div>
                <h3>No students found</h3>
                <p>Add your first student to get started.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary" style="margin-top:1rem;">Add Student</a>
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
                                        <a href="{{ route('students.edit', $student) }}"
                                           class="btn btn-secondary btn-sm btn-icon"
                                           data-tooltip="Edit">✏️</a>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}"
                                              onsubmit="return confirm('Remove {{ addslashes($student->full_name) }}? This will also delete their attendance records.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-icon" data-tooltip="Delete">🗑</button>
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
    </div>
</x-app-layout>
