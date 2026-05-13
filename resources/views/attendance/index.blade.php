<x-app-layout>
    <x-slot name="title">Take Attendance</x-slot>

    <x-app-banner title="Attendance Sheet">
        <x-slot name="subtitle">Mark each student's status for the selected date.</x-slot>
    </x-app-banner>

    <div class="app-page">
    <style>
        .attendance-table-wrap {
            overflow-x: auto;
            overflow-y: visible;
            width: 100%;
        }
        .card-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }
        .card-header .card-title{
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 600;
        }
        .attendance-row-actions {
            position: relative;
            display: inline-block;
        }
        .attendance-row-actions.is-open {
            z-index: 120;
        }
        [data-status-menu-btn] {
            padding: 0.25rem 0.35rem !important;
            height: auto;
            min-height: auto;
        }
        .attendance-status-menu {
            display: none;
            position: fixed;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            padding: .35rem;
            min-width: 145px;
            z-index: 9999;
        }
        [data-set-status="present"]:hover {
            background-color: #dcfce7 !important;
        }
        [data-set-status="absent"]:hover {
            background-color: #fee2e2 !important;
        }
        [data-set-status="late"]:hover {
            background-color: #fef3c7 !important;
        }
        .badge-pending {
            background-color: #f3f4f6;
            color: #6b7280;
        }
    </style>
    {{-- Date Picker --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.5rem;">
            <form id="date-load-form" method="GET" action="{{ route('attendance.index') }}" style="display:flex;align-items:flex-end;gap:1rem;flex-wrap:wrap;">
                <div>
                    <label class="form-label" for="att-date">Select Date</label>
                    <input class="form-control" id="att-date" type="date" name="date"
                           value="{{ $date }}" max="{{ today()->toDateString() }}">
                </div>
                <!-- Load button removed: date now auto-submits on change -->
            </form>
        </div>
    </div>

    @if($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="icon" aria-hidden="true">
                    <i data-lucide="graduation-cap" data-size="34"></i>
                </div>
                <h3>No students enrolled</h3>
                <p>Add students before marking attendance.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary" style="margin-top:1rem;">Add Student</a>
            </div>
        </div>
    @else
        {{-- Bulk select toolbar --}}
        <div style="display:flex;gap:.75rem;margin-bottom:1rem;flex-wrap:wrap;align-items:center;">
            <span style="font-size:.8rem;color:var(--text-muted);">Mark all as:</span>
            <button class="btn btn-secondary btn-sm" onclick="markAll('present')">
                <i data-lucide="check-circle-2" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
                All Present
            </button>
            <button class="btn btn-secondary btn-sm" onclick="markAll('absent')">
                <i data-lucide="x-circle" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
                All Absent
            </button>
            <button class="btn btn-secondary btn-sm" onclick="markAll('late')">
                <i data-lucide="clock" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
                All Late
            </button>
        </div>

        <form method="POST" action="{{ route('attendance.store') }}" id="attendance-form">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <span class="card-title">
                        <i data-lucide="clipboard" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </span>
                    <span style="font-size:.8rem;color:var(--text-muted);">{{ $students->count() }} students</span>
                </div>
                <div class="attendance-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Section</th>
                                <th>Status *</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $i => $student)
                                @php $current = $student->attendanceRecord?->status ?? 'pending'; @endphp
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
                                        <input
                                            type="hidden"
                                            name="attendance[{{ $student->id }}][status]"
                                            value="{{ $current !== 'pending' ? $current : '' }}"
                                            data-student-id="{{ $student->id }}"
                                            data-status-input
                                        >
                                        <span
                                            class="badge {{ $current === 'pending' ? 'badge-pending' : '' }}"
                                            data-student-id="{{ $student->id }}"
                                            data-status-badge
                                        >
                                            {{ $current === 'pending' ? 'Pending' : ucfirst($current) }}
                                        </span>
                                    </td>
                                    <td style="text-align:right;">
                                        <div class="attendance-row-actions">
                                            <button
                                                type="button"
                                                class="btn btn-secondary btn-sm btn-icon"
                                                aria-haspopup="menu"
                                                aria-label="Change status"
                                                data-status-menu-btn
                                                data-student-id="{{ $student->id }}"
                                            >
                                                <i data-lucide="more-horizontal" data-size="14"></i>
                                            </button>
                                            <div
                                                class="attendance-status-menu"
                                                data-status-menu
                                                data-student-id="{{ $student->id }}"
                                                role="menu"
                                            >
                                                <button type="button" role="menuitem" class="btn btn-secondary btn-sm" style="width:100%; justify-content:flex-start;" data-set-status="present" data-student-id="{{ $student->id }}">
                                                    <i data-lucide="check-circle-2" data-size="16" style="margin-right:.4rem;"></i> Present
                                                </button>
                                                <button type="button" role="menuitem" class="btn btn-secondary btn-sm" style="width:100%; justify-content:flex-start; margin-top:.25rem;" data-set-status="absent" data-student-id="{{ $student->id }}">
                                                    <i data-lucide="x-circle" data-size="16" style="margin-right:.4rem;"></i> Absent
                                                </button>
                                                <button type="button" role="menuitem" class="btn btn-secondary btn-sm" style="width:100%; justify-content:flex-start; margin-top:.25rem;" data-set-status="late" data-student-id="{{ $student->id }}">
                                                    <i data-lucide="clock" data-size="16" style="margin-right:.4rem;"></i> Late
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                <!-- Save button removed: attendance auto-saves. -->
                <span style="font-size:.8rem;color:var(--text-muted);" id="save-status">All changes will be saved for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.</span>
            </div>
        </form>

        {{-- Clear date records --}}
        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border-color);">
            <form method="POST" action="{{ route('attendance.destroy') }}"
                  onsubmit="return confirm('Clear ALL attendance records for this date?')">
                @csrf @method('DELETE')
                <input type="hidden" name="date" value="{{ $date }}">
                <button class="btn btn-danger btn-sm" type="submit">
                    <i data-lucide="trash-2" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Clear Records for This Date
                </button>
            </form>
        </div>
    @endif

    </div>

    <script>
        function setStatus(id, status) {
            const input = document.querySelector(`[data-status-input][data-student-id="${id}"]`);
            const badge = document.querySelector(`[data-status-badge][data-student-id="${id}"]`);
            if (!input || !badge) return;

            // Set the input value (empty for pending to not save)
            input.value = status === 'pending' ? '' : status;
            
            // Update badge display
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            
            // Update badge styling
            if (status === 'pending') {
                badge.className = 'badge badge-pending';
            } else {
                badge.className = 'badge';
                badge.style.background = { present: '#dcfce7', absent: '#fee2e2', late: '#fef3c7' }[status] || '#f1f5f9';
                badge.style.color = { present: '#166534', absent: '#991b1b', late: '#92400e' }[status] || '#6b7280';
            }

            highlightRow(id, status);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Auto-submit date picker when changed (debounced)
            (function(){
                const dateInput = document.getElementById('att-date');
                const dateForm = document.getElementById('date-load-form');
                if (!dateInput || !dateForm) return;
                let _timer = null;
                dateInput.addEventListener('change', () => {
                    clearTimeout(_timer);
                    _timer = setTimeout(() => {
                        dateForm.submit();
                    }, 300);
                });
            })();
            // Function to position menu relative to button
            function positionMenu(btn, menu) {
                const rect = btn.getBoundingClientRect();
                menu.style.left = (rect.left + rect.width / 2 - menu.offsetWidth / 2) + 'px';
                menu.style.top = (rect.bottom + 8) + 'px';
            }

            // Auto-save function
            function autoSaveAttendance() {
                const form = document.getElementById('attendance-form');
                const formData = new FormData(form);
                const saveBtn = document.getElementById('save-btn');
                const saveStatus = document.getElementById('save-status');
                const originalText = saveStatus.textContent;

                if (saveBtn) saveBtn.disabled = true;
                if (saveStatus) saveStatus.textContent = 'Saving...';

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || `HTTP ${response.status}`);
                        }).catch(e => {
                            if (e instanceof Error && e.message.startsWith('Error')) {
                                throw e;
                            }
                            throw new Error(`HTTP ${response.status}`);
                        });
                    }
                })
                .then(data => {
                    if (saveStatus) saveStatus.textContent = '✓ Saved';
                    // Show success toast
                    if (window.Toast && typeof window.Toast.success === 'function') {
                        window.Toast.success(data.message || 'Attendance saved successfully.', 'Success');
                    }
                    setTimeout(() => {
                        if (saveStatus) saveStatus.textContent = originalText;
                        if (saveBtn) saveBtn.disabled = false;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (saveStatus) saveStatus.textContent = '✗ ' + error.message;
                    // Show error toast
                    if (window.Toast && typeof window.Toast.error === 'function') {
                        window.Toast.error(error.message || 'Error saving attendance.', 'Error');
                    }
                    if (saveBtn) saveBtn.disabled = false;
                });
            }

            window.autoSaveAttendance = autoSaveAttendance;

            // Toggle per-row status menu
            document.querySelectorAll('[data-status-menu-btn]').forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const id = btn.getAttribute('data-student-id');
                    const menu = document.querySelector(`[data-status-menu][data-student-id="${id}"]`);
                    const wrapper = btn.closest('.attendance-row-actions');
                    if (!menu) return;

                    // close others
                    document.querySelectorAll('.attendance-row-actions').forEach((w) => w.classList.remove('is-open'));
                    document.querySelectorAll('[data-status-menu]').forEach((m) => {
                        if (m !== menu) m.style.display = 'none';
                    });
                    const opening = (menu.style.display === 'none' || !menu.style.display);
                    if (opening) {
                        menu.style.display = 'block';
                        // Position menu after display to get correct dimensions
                        setTimeout(() => positionMenu(btn, menu), 0);
                        if (wrapper) wrapper.classList.add('is-open');
                    } else {
                        menu.style.display = 'none';
                        if (wrapper) wrapper.classList.remove('is-open');
                    }
                });
            });

            // Menu actions
            document.querySelectorAll('[data-set-status]').forEach((item) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const id = item.getAttribute('data-student-id');
                    const status = item.getAttribute('data-set-status');
                    if (id && status) {
                        setStatus(id, status);
                        // Trigger auto-save
                        autoSaveAttendance();
                    }
                    // close menu
                    const menu = document.querySelector(`[data-status-menu][data-student-id="${id}"]`);
                    const wrapper = item.closest('.attendance-row-actions');
                    if (menu) menu.style.display = 'none';
                    if (wrapper) wrapper.classList.remove('is-open');
                });
            });

            // Close menus on outside click
            document.addEventListener('click', () => {
                document.querySelectorAll('[data-status-menu]').forEach((m) => (m.style.display = 'none'));
                document.querySelectorAll('.attendance-row-actions').forEach((w) => w.classList.remove('is-open'));
            });

            // Init row colors on load based on hidden inputs
            document.querySelectorAll('[data-status-input]').forEach((input) => {
                const id = input.getAttribute('data-student-id');
                if (id) {
                    const status = input.value || 'pending';
                    setStatus(id, status);
                }
            });

            // Reposition menus on window scroll/resize
            window.addEventListener('scroll', () => {
                document.querySelectorAll('[data-status-menu][style*="display: block"]').forEach((menu) => {
                    const id = menu.getAttribute('data-student-id');
                    const btn = document.querySelector(`[data-status-menu-btn][data-student-id="${id}"]`);
                    if (btn) positionMenu(btn, menu);
                });
            });

            window.addEventListener('resize', () => {
                document.querySelectorAll('[data-status-menu][style*="display: block"]').forEach((menu) => {
                    const id = menu.getAttribute('data-student-id');
                    const btn = document.querySelector(`[data-status-menu-btn][data-student-id="${id}"]`);
                    if (btn) positionMenu(btn, menu);
                });
            });

            // Form submission fallback for manual save
            document.getElementById('attendance-form').addEventListener('submit', (e) => {
                e.preventDefault();
                autoSaveAttendance();
            });
        });

        function markAll(status) {
            document.querySelectorAll('[data-status-input]').forEach((input) => {
                const id = input.getAttribute('data-student-id');
                if (id) setStatus(id, status);
            });
            // Auto-save after applying bulk change (debounced)
            if (typeof window._bulkSaveTimer !== 'undefined') clearTimeout(window._bulkSaveTimer);
            window._bulkSaveTimer = setTimeout(() => {
                if (typeof window.autoSaveAttendance === 'function') {
                    window.autoSaveAttendance();
                }
            }, 200);
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
    </script>
</x-app-layout>
