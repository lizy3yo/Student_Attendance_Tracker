<x-app-layout>
    <x-slot name="title">Take Attendance</x-slot>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="font-size:1.4rem;font-weight:800;">Attendance Sheet</h2>
            <p style="color:var(--text-muted);font-size:.85rem;">Mark each student's status for the selected date.</p>
        </div>
    </div>

    {{-- Date Picker --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.5rem;">
            <form method="GET" action="{{ route('attendance.index') }}" style="display:flex;align-items:flex-end;gap:1rem;flex-wrap:wrap;">
                <div>
                    <label class="form-label" for="att-date">Select Date</label>
                    <input class="form-control" id="att-date" type="date" name="date"
                           value="{{ $date }}" max="{{ today()->toDateString() }}">
                </div>
                <button class="btn btn-primary" type="submit">📅 Load</button>
            </form>
        </div>
    </div>

    @if($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon">🎓</div>
                <h3>No students enrolled</h3>
                <p>Add students before marking attendance.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary" style="margin-top:1rem;">Add Student</a>
            </div>
        </div>
    @else
        {{-- Bulk select toolbar --}}
        <div style="display:flex;gap:.75rem;margin-bottom:1rem;flex-wrap:wrap;align-items:center;">
            <span style="font-size:.8rem;color:var(--text-muted);">Mark all as:</span>
            <button class="btn btn-secondary btn-sm" onclick="markAll('present')">✅ All Present</button>
            <button class="btn btn-secondary btn-sm" onclick="markAll('absent')">❌ All Absent</button>
            <button class="btn btn-secondary btn-sm" onclick="markAll('late')">⏱ All Late</button>
        </div>

        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <span class="card-title">
                        📋 {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </span>
                    <span style="font-size:.8rem;color:var(--text-muted);">{{ $students->count() }} students</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Section</th>
                                <th>Status *</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $i => $student)
                                @php $current = $student->attendanceRecord?->status ?? 'present'; @endphp
                                <tr id="row-{{ $student->id }}">
                                    <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="badge badge-muted" style="font-family:monospace;">
                                            {{ $student->student_id_number }}
                                        </span>
                                    </td>
                                    <td style="font-weight:600;">{{ $student->full_name }}</td>
                                    <td><span class="badge badge-muted">{{ $student->section }}</span></td>
                                    <td>
                                        <div class="att-radio-group">
                                            <input class="att-radio" type="radio"
                                                   name="attendance[{{ $student->id }}][status]"
                                                   id="p{{ $student->id }}" value="present"
                                                   {{ $current === 'present' ? 'checked' : '' }}
                                                   onchange="highlightRow({{ $student->id }}, 'present')">
                                            <label class="att-label att-present" for="p{{ $student->id }}">✅ Present</label>

                                            <input class="att-radio" type="radio"
                                                   name="attendance[{{ $student->id }}][status]"
                                                   id="a{{ $student->id }}" value="absent"
                                                   {{ $current === 'absent' ? 'checked' : '' }}
                                                   onchange="highlightRow({{ $student->id }}, 'absent')">
                                            <label class="att-label att-absent" for="a{{ $student->id }}">❌ Absent</label>

                                            <input class="att-radio" type="radio"
                                                   name="attendance[{{ $student->id }}][status]"
                                                   id="l{{ $student->id }}" value="late"
                                                   {{ $current === 'late' ? 'checked' : '' }}
                                                   onchange="highlightRow({{ $student->id }}, 'late')">
                                            <label class="att-label att-late" for="l{{ $student->id }}">⏱ Late</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text"
                                               name="attendance[{{ $student->id }}][remarks]"
                                               value="{{ $student->attendanceRecord?->remarks }}"
                                               placeholder="Optional note…"
                                               style="max-width:200px;padding:.4rem .75rem;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                <button class="btn btn-primary" type="submit">💾 Save Attendance</button>
                <span style="font-size:.8rem;color:var(--text-muted);">All changes will be saved for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.</span>
            </div>
        </form>

        {{-- Clear date records --}}
        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--sidebar-border);">
            <form method="POST" action="{{ route('attendance.destroy') }}"
                  onsubmit="return confirm('Clear ALL attendance records for this date?')">
                @csrf @method('DELETE')
                <input type="hidden" name="date" value="{{ $date }}">
                <button class="btn btn-danger btn-sm" type="submit">🗑 Clear Records for This Date</button>
            </form>
        </div>
    @endif

    <script>
        function markAll(status) {
            document.querySelectorAll(`input[type=radio][value="${status}"]`).forEach(r => {
                r.checked = true;
                const id = r.id.replace(/[a-z]/,'');
                highlightRow(id, status);
            });
        }
        function highlightRow(id, status) {
            const row = document.getElementById('row-' + id);
            if (!row) return;
            row.style.background = {
                present: 'rgba(16,185,129,.05)',
                absent:  'rgba(239,68,68,.05)',
                late:    'rgba(245,158,11,.05)',
            }[status] || '';
        }
        // Init row colors on load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input.att-radio:checked').forEach(r => {
                const id = r.name.match(/\[(\d+)\]/)?.[1];
                if (id) highlightRow(id, r.value);
            });
        });
    </script>
</x-app-layout>
