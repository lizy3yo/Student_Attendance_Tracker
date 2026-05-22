<x-app-layout>
    <x-slot name="title">Class details: {{ $class->class_code }}</x-slot>

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
        /* Tab link styles to avoid inline Blade expressions inside style attributes */
        .tab-link {
            padding: 0.75rem 0.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            color: var(--text-muted);
            border-bottom: 3px solid transparent;
        }
        .tab-link-active {
            color: var(--primary-dark);
            border-bottom-color: var(--primary);
        }
        .tab-badge {
            padding: 0.1rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            background: #e5e7eb;
            color: var(--text-muted);
        }
        .tab-badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: var(--primary-dark);
        }
        .show-tabs-nav {
            display: flex;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 1.5rem;
            gap: 1.5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .show-main-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        .show-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .show-roster-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }
        .show-attendance-card {
            overflow: hidden;
        }
        .show-attendance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem 1.5rem;
            padding: 1rem 1.5rem;
        }
        .show-attendance-controls {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .show-attendance-filter-group {
            display: inline-flex;
            background: #f1f5f9;
            padding: 0.25rem;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            gap: 0.25rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .show-attendance-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin: 0;
            flex-wrap: wrap;
        }
        .show-attendance-search {
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: #fafafa;
        }
        .show-attendance-search-inner {
            position: relative;
            max-width: 400px;
        }
        .show-attendance-table-wrap {
            max-height: 340px;
            overflow: auto;
            background: white;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }
        .show-attendance-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }
        .show-action-footer {
            background: #f8fafc;
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        /* KPI stat cards grid */
        .show-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 1024px) {
            .show-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 560px) {
            .show-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 1024px) {
            .show-main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .show-tabs-nav {
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .tab-link {
                font-size: 0.85rem;
                white-space: nowrap;
            }

            .show-attendance-header {
                padding: 0.85rem 1rem;
                align-items: stretch;
            }

            .show-attendance-controls {
                width: 100%;
                gap: 0.75rem;
            }

            .show-attendance-filter-group {
                width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
            }

            .show-attendance-form {
                width: 100%;
            }

            .show-attendance-form .form-control,
            .show-attendance-form input[type="date"] {
                width: 100% !important;
                min-width: 0;
            }

            .show-attendance-search {
                padding: 0.75rem 1rem;
            }

            .show-attendance-search-inner {
                max-width: none;
            }

            .show-action-footer {
                padding: 1rem;
                flex-wrap: wrap;
                justify-content: stretch;
            }

            .show-action-footer .btn {
                flex: 1 1 100%;
            }
        }

        @media (max-width: 640px) {
            .show-main-grid {
                gap: 1rem;
            }

            .show-table-scroll,
            .show-attendance-table-wrap {
                overflow-x: hidden;
            }

            .show-roster-table,
            .show-attendance-table {
                min-width: 0;
            }

            .show-roster-table thead,
            .show-attendance-table thead {
                display: none;
            }

            .show-roster-table,
            .show-roster-table tbody,
            .show-roster-table tr,
            .show-roster-table td,
            .show-attendance-table,
            .show-attendance-table tbody,
            .show-attendance-table tr,
            .show-attendance-table td {
                display: block;
                width: 100%;
            }

            .show-roster-table tr,
            .show-attendance-table tr {
                margin: 0.75rem;
                width: calc(100% - 1.5rem);
                box-sizing: border-box;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
                height: auto !important;
                min-height: 0 !important;
            }

            .show-roster-table td,
            .show-attendance-table td {
                border: none;
                padding: 0.6rem 0.85rem;
            }

            .show-roster-table td:nth-child(1),
            .show-attendance-table td:nth-child(1) {
                padding-top: 0.85rem;
                padding-bottom: 0.35rem;
            }

            .show-roster-table td:nth-child(n+2)::before,
            .show-attendance-table td:nth-child(n+2)::before {
                display: block;
                font-size: 0.68rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 0.2rem;
            }

            .show-roster-table td:nth-child(2)::before { content: 'ID Number'; }
            .show-roster-table td:nth-child(3)::before { content: 'Student Name'; }
            .show-roster-table td:nth-child(4)::before { content: 'Course and Block'; }
            .show-roster-table td:nth-child(5)::before { content: 'Actions'; }

            .show-attendance-table td:nth-child(2)::before { content: 'ID Number'; }
            .show-attendance-table td:nth-child(3)::before { content: 'Student Name'; }
            .show-attendance-table td:nth-child(4)::before { content: 'Status'; }
            .show-attendance-table td:nth-child(5)::before { content: 'Time In'; }
            .show-attendance-table td:nth-child(6)::before { content: 'Remarks'; }

            .show-roster-table td:nth-child(5),
            .show-attendance-table td:nth-child(4),
            .show-attendance-table td:nth-child(5),
            .show-attendance-table td:nth-child(6) {
                padding-bottom: 0.85rem;
            }

            .show-roster-table td:nth-child(5) {
                text-align: left !important;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.35rem;
            }

            .show-roster-table td:nth-child(5) .btn {
                width: 100%;
                justify-content: center;
                white-space: normal;
                min-width: 0;
            }

            .show-roster-table td:nth-child(1),
            .show-roster-table td:nth-child(5) {
                display: flex;
                align-items: center;
            }

            .show-roster-table td:nth-child(1) input,
            .show-attendance-table td:nth-child(1) input {
                margin-top: 0.1rem;
            }

            .show-attendance-table td:nth-child(4) .att-radio-group {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.4rem;
                width: 100%;
            }

            .show-attendance-table td:nth-child(4) .att-label {
                flex: none;
                width: 100%;
                min-width: 0;
                justify-content: center;
                padding: 0.45rem 0.5rem;
                font-size: 0.76rem;
                text-align: center;
            }

            .show-attendance-table td:nth-child(4) {
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            .show-attendance-table td:nth-child(5) div[x-show],
            .show-attendance-table td:nth-child(5) span[x-show] {
                padding-left: 0;
            }

            .show-attendance-table td:nth-child(5) {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                min-height: 0;
            }

            .show-attendance-table td:nth-child(6) input.form-control {
                height: 36px;
                font-size: 0.82rem;
            }

            .show-attendance-table td:nth-child(6) {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                min-height: 0;
            }

            .show-attendance-table tbody tr {
                height: auto !important;
            }

            .show-attendance-header {
                gap: 0.75rem;
            }
        }
        .show-kpi-card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-card);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
            cursor: pointer;
        }
        .show-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1), 0 4px 10px -5px rgba(0,0,0,0.06);
            border-color: #d1d5db;
        }
        .show-kpi-card:hover .show-kpi-label { color: var(--text-main); }
        .show-kpi-label { font-size: 0.82rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0.3rem; transition: color 0.15s; }
        .show-kpi-value { font-size: 2rem; font-weight: 800; color: var(--text-main); line-height: 1; }
        .show-kpi-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem; }
        .show-kpi-icon {
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
    </style>

    {{-- Subheader / Banner --}}
    <x-app-banner title="{{ $class->class_name }} ({{ $class->class_code }})">
        <x-slot name="subtitle">
            Year {{ $class->year }} &bull; Block {{ $class->block }} &bull; {{ $class->semester }} Semester &bull; {{ $class->academic_year }}
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                <i data-lucide="arrow-left" data-size="16" style="vertical-align:middle;margin-right:.3rem;"></i>
                Back to Classes
            </a>
        </x-slot>
    </x-app-banner>

    <div class="app-page">
        {{-- KPI Stat Cards --}}
        @php
            $rosterUrl     = route('classes.show', ['class' => $class->id, 'tab' => 'roster']);
            $attUrl        = route('classes.show', ['class' => $class->id, 'tab' => 'attendance']);
            $attPresentUrl = route('classes.show', ['class' => $class->id, 'tab' => 'attendance', 'date' => today()->toDateString()]);
            $enrollPct     = $class->capacity > 0 ? round(($totalEnrolledCount / $class->capacity) * 100) : 0;
        @endphp
        <div class="show-kpi-grid">

            {{-- Enrolled Students → Roster tab --}}
            <a href="{{ $rosterUrl }}" class="show-kpi-card">
                <div>
                    <div class="show-kpi-label">Enrolled Students</div>
                    <div class="show-kpi-value">{{ $totalEnrolledCount }}</div>
                    <div class="show-kpi-sub">{{ $enrollPct }}% of {{ $class->capacity }} capacity</div>
                </div>
                <div class="show-kpi-icon" style="background: rgba(34,197,94,0.1); color: #16a34a;">
                    <i data-lucide="users" data-size="22"></i>
                </div>
            </a>

            {{-- Present Today → Attendance tab (status filter: present) --}}
            <a href="{{ $attPresentUrl }}" class="show-kpi-card">
                <div>
                    <div class="show-kpi-label">Present Today</div>
                    <div class="show-kpi-value" style="color: #16a34a;">{{ $presentCount }}</div>
                    <div class="show-kpi-sub">Marked present this session</div>
                </div>
                <div class="show-kpi-icon" style="background: rgba(34,197,94,0.1); color: #16a34a;">
                    <i data-lucide="check-circle-2" data-size="22"></i>
                </div>
            </a>

            {{-- Late Today → Attendance tab --}}
            <a href="{{ $attPresentUrl }}" class="show-kpi-card">
                <div>
                    <div class="show-kpi-label">Late Today</div>
                    <div class="show-kpi-value" style="color: #b45309;">{{ $lateCount }}</div>
                    <div class="show-kpi-sub">Arrived late this session</div>
                </div>
                <div class="show-kpi-icon" style="background: rgba(245,158,11,0.1); color: #d97706;">
                    <i data-lucide="clock" data-size="22"></i>
                </div>
            </a>

            {{-- Absent Today → Attendance tab --}}
            <a href="{{ $attPresentUrl }}" class="show-kpi-card">
                <div>
                    <div class="show-kpi-label">Absent Today</div>
                    <div class="show-kpi-value" style="color: #b91c1c;">{{ $absentCount }}</div>
                    <div class="show-kpi-sub">Absent from this session</div>
                </div>
                <div class="show-kpi-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                    <i data-lucide="user-x" data-size="22"></i>
                </div>
            </a>

        </div>

        {{-- Tabs Navigation --}}
        <div class="show-tabs-nav">
            <a href="{{ route('classes.show', ['class' => $class->id, 'tab' => 'roster']) }}"
               class="tab-link {{ $tab === 'roster' ? 'tab-link-active' : '' }}">
                <i data-lucide="users" data-size="18"></i>
                Class Roster
                <span class="tab-badge {{ $tab === 'roster' ? 'tab-badge-active' : '' }}">
                    {{ $totalEnrolledCount }}
                </span>
            </a>
            <a href="{{ route('classes.show', ['class' => $class->id, 'tab' => 'attendance']) }}"
               class="tab-link {{ $tab === 'attendance' ? 'tab-link-active' : '' }}">
                <i data-lucide="calendar-days" data-size="18"></i>
                Take Attendance
            </a>
        </div>

        {{-- ── TAB 1: ROSTER MANAGEMENT ────────────────────────────────────── --}}
        @if($tab === 'roster')
            <div x-data="{
                showRemoveModal: false,
                removeStudentName: '',
                removeFormAction: '',
                showAssignModal: false,
                assignSearch: '',
                selectedRosterIds: [],
                showBulkRemoveModal: false,
                availableStudents: [
                    @foreach($availableStudents as $student)
                    {
                        id: {{ $student->id }},
                        full_name: @js($student->full_name),
                        student_id_number: @js($student->student_id_number),
                        section: @js($student->section)
                    },
                    @endforeach
                ],
                get hasVisibleAvailable() {
                    let q = this.assignSearch.toLowerCase().trim();
                    if (!q) return true;
                    return this.availableStudents.some(s => 
                        s.full_name.toLowerCase().includes(q) || 
                        s.student_id_number.toLowerCase().includes(q) || 
                        s.section.toLowerCase().includes(q)
                    );
                },
                
                triggerRemove(name, action) {
                    this.removeStudentName = name;
                    this.removeFormAction = action;
                    this.showRemoveModal = true;
                },
                
                confirmRemove() {
                    let form = document.getElementById('unenroll-form');
                    form.action = this.removeFormAction;
                    form.submit();
                },
                
                triggerAssign() {
                    let checked = document.querySelectorAll('#assign-form input[type=\'checkbox\']:checked');
                    if (checked.length === 0) {
                        return;
                    }
                    this.showAssignModal = true;
                },
                
                confirmAssign() {
                    document.getElementById('assign-form').submit();
                },

                toggleAllRoster() {
                    if (this.selectedRosterIds.length === {{ count($enrolledStudents) }}) {
                        this.selectedRosterIds = [];
                    } else {
                        this.selectedRosterIds = [
                            @foreach($enrolledStudents as $student)
                            {{ $student->id }},
                            @endforeach
                        ];
                    }
                },

                confirmBulkRemove() {
                    document.getElementById('bulk-unenroll-form').submit();
                }
            }">
                <div class="show-main-grid">
                    {{-- Enrolled Students List --}}
                    <div class="card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem;">
                            <div>
                                <h3 class="card-title" style="margin: 0; font-weight: 700;">Enrolled Students</h3>
                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">Middle, given, last name, ID, or block…</div>
                            </div>
                            <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">
                                Capacity: {{ $totalEnrolledCount }}/{{ $class->capacity }}
                            </span>
                        </div>

                        {{-- Search bar for Roster --}}
                        @if($totalEnrolledCount > 0)
                            <div style="padding: 0.75rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #fafafa;">
                                <form method="GET" action="{{ route('classes.show', $class->id) }}" style="display: flex; gap: 0.75rem; align-items: center; margin: 0;">
                                    <input type="hidden" name="tab" value="roster">
                                    <div style="flex: 1; position: relative;">
                                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center;">
                                            <i data-lucide="search" data-size="16"></i>
                                        </span>
                                        <input class="form-control" type="text" name="search" value="{{ request('tab') === 'roster' ? request('search') : '' }}"
                                               placeholder="Search enrolled students..." style="padding-left: 2.25rem; padding-right: 2.5rem; height: 38px; border-radius: 8px;">
                                        @if(request('tab') === 'roster' && request('search'))
                                            <a href="{{ route('classes.show', ['class' => $class->id, 'tab' => 'roster']) }}" 
                                               style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;text-decoration:none;transition:background-color 0.2s, color 0.2s;"
                                               title="Clear search"
                                               onmouseover="this.style.color='var(--text-main)';this.style.background='rgba(0,0,0,0.05)';"
                                               onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent';"
                                            >
                                                <i data-lucide="x" data-size="16"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div class="card-body" style="padding: 0;">
                            @if($totalEnrolledCount > 0)
                                @if(count($enrolledStudents) > 0)
                                    <!-- Bulk Action Bar -->
                                    <div x-show="selectedRosterIds.length > 0" x-transition x-cloak
                                         style="background: #fff1f2; border-bottom: 1px solid #fecdd3; padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                        <div style="font-size: 0.875rem; color: #e11d48; font-weight: 600;">
                                            <span x-text="selectedRosterIds.length"></span> student(s) selected
                                        </div>
                                        <button type="button" @click="showBulkRemoveModal = true" class="btn btn-sm" 
                                                style="background: #ef4444; color: white; border: none; font-weight: 600; font-size: 0.8rem; padding: 0.35rem 0.75rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.35rem; cursor: pointer;">
                                            <i data-lucide="user-minus" data-size="14"></i>
                                            Remove Selected
                                        </button>
                                    </div>
                                    <div class="show-table-scroll">
                                        <table class="show-roster-table">
                                            <thead>
                                                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                                                    <th style="padding: 0.75rem 1rem; width: 48px; text-align: center; vertical-align: middle;">
                                                        <input type="checkbox" :checked="selectedRosterIds.length === {{ count($enrolledStudents) }} && {{ count($enrolledStudents) }} > 0" 
                                                               @change="toggleAllRoster()" 
                                                               style="border-radius: 4px; border: 1px solid #cbd5e1; color: var(--primary); cursor: pointer;">
                                                    </th>
                                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left;">ID Number</th>
                                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left;">Student Name</th>
                                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left;">Course and Block</th>
                                                    <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: right;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($enrolledStudents as $student)
                                                    <tr style="border-bottom: 1px solid var(--border-color); vertical-align: middle;">
                                                        <td style="padding: 0.85rem 1rem; text-align: center; vertical-align: middle; width: 48px;">
                                                            <input type="checkbox" value="{{ $student->id }}" x-model.number="selectedRosterIds"
                                                                   style="border-radius: 4px; border: 1px solid #cbd5e1; color: var(--primary); cursor: pointer;">
                                                        </td>
                                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ $student->student_id_number }}</td>
                                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 500; color: var(--text-main);">
                                                            {{ $student->full_name }}
                                                        </td>
                                                        <td style="padding: 0.85rem 1rem; font-size: 0.85rem; color: var(--text-muted);">{{ $student->section }}</td>
                                                        <td style="padding: 0.85rem 1rem; text-align: right; vertical-align: middle;">
                                                            <button type="button" class="btn btn-secondary btn-sm" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.2); font-weight: 500; padding: 0.35rem 0.65rem;"
                                                                    @click="triggerRemove('{{ addslashes($student->full_name) }}', '{{ route('classes.unenroll', ['class' => $class->id, 'student' => $student->id]) }}')">
                                                                <i data-lucide="user-minus" data-size="14" style="vertical-align: middle; margin-right: 0.25rem;"></i>
                                                                Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                        <i data-lucide="search" data-size="40" style="margin: 0 auto 0.75rem; color: #cbd5e1;"></i>
                                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">No students found</h4>
                                        <p style="font-size: 0.85rem; max-width: 20rem; margin: 0 auto;">No enrolled students match "{{ request('search') }}".</p>
                                    </div>
                                @endif
                            @else
                                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                    <i data-lucide="users-2" data-size="40" style="margin: 0 auto 0.75rem; color: #cbd5e1;"></i>
                                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">Class is empty</h4>
                                    <p style="font-size: 0.85rem; max-width: 20rem; margin: 0 auto;">Assign students from your general student database to this class.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Assign/Enroll Students --}}
                    <div class="card">
                        <div class="card-header" style="padding: 1rem 1.25rem;">
                            <h3 class="card-title" style="margin: 0; font-weight: 700;">Assign Students</h3>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">Enroll students in this class</div>
                        </div>

                        {{-- Search bar for Available Students --}}
                        @if(count($availableStudents) > 0)
                            <div style="padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-color); background: #fafafa;">
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center;">
                                        <i data-lucide="search" data-size="16"></i>
                                    </span>
                                    <input class="form-control" type="text" x-model="assignSearch"
                                           placeholder="Search available students..." 
                                           style="padding-left: 2.25rem; padding-right: 2.5rem; height: 38px; border-radius: 8px; width: 100%; border: 1px solid var(--border-color); background: #ffffff;">
                                    <button type="button" x-show="assignSearch" @click="assignSearch = ''"
                                            style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border:none;background:transparent;cursor:pointer;border-radius:50%;text-decoration:none;transition:background-color 0.2s, color 0.2s;"
                                            title="Clear search"
                                            onmouseover="this.style.color='var(--text-main)';this.style.background='rgba(0,0,0,0.05)';"
                                            onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent';"
                                    >
                                        <i data-lucide="x" data-size="16"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="card-body" style="padding: 1.25rem;">
                            @if(count($availableStudents) > 0)
                                <form id="assign-form" method="POST" action="{{ route('classes.enroll', $class->id) }}" @submit.prevent="triggerAssign">
                                    @csrf
                                    <div style="max-height: 320px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.5rem; margin-bottom: 1rem; background: #fafafa;">
                                        @foreach($availableStudents as $student)
                                            <div x-show="!assignSearch || '{{ addslashes(strtolower($student->full_name)) }}'.includes(assignSearch.toLowerCase()) || '{{ addslashes(strtolower($student->student_id_number)) }}'.includes(assignSearch.toLowerCase()) || '{{ addslashes(strtolower($student->section)) }}'.includes(assignSearch.toLowerCase())"
                                                 class="hover:bg-gray-100" style="padding: 0.5rem; border-radius: 6px; transition: background 0.15s; text-align: left !important;">
                                                <table style="width: 100%; border-collapse: collapse; border: none; margin: 0; padding: 0; background: transparent;">
                                                    <tr style="border: none; background: transparent;">
                                                        <td style="width: 24px; vertical-align: middle; padding: 0; margin: 0; border: none; background: transparent;">
                                                            <input id="available-student-{{ $student->id }}" type="checkbox" name="student_ids[]" value="{{ $student->id }}" style="display: inline-block !important; width: 1.125rem !important; height: 1.125rem !important; border-radius: 4px !important; border: 1px solid #cbd5e1 !important; color: var(--primary) !important; margin: 0 !important; cursor: pointer !important; vertical-align: middle !important;">
                                                        </td>
                                                        <td style="padding: 0 0 0 0.5rem; margin: 0; border: none; background: transparent; vertical-align: middle; text-align: left;">
                                                            <label for="available-student-{{ $student->id }}" style="display: block !important; width: 100% !important; margin: 0 !important; padding: 0 !important; cursor: pointer !important; font-size: 0.85rem !important;">
                                                                <span style="font-weight: 600; color: var(--text-main); display: block !important; line-height: 1.25 !important; font-family: inherit !important;">{{ $student->full_name }}</span>
                                                                <span style="font-size: 0.75rem !important; color: var(--text-muted); display: block !important; line-height: 1.25 !important; font-family: inherit !important;">{{ $student->student_id_number }} &bull; {{ $student->section }}</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @endforeach

                                        {{-- Client-side empty search result for available students --}}
                                        <div x-show="!hasVisibleAvailable" style="text-align: center; color: var(--text-muted); padding: 2rem 1rem;">
                                            <i data-lucide="search" data-size="28" style="margin: 0 auto 0.5rem; display: block; color: #cbd5e1;"></i>
                                            <div style="font-weight: 600; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.25rem;">No students found</div>
                                            <p style="font-size: 0.8rem; margin: 0; line-height: 1.4;">No available students match your search query.</p>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" x-show="hasVisibleAvailable" style="width: 100%; font-weight: 600; background: var(--primary); display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem; border: none; height: 38px;">
                                        <i data-lucide="user-plus" data-size="16"></i>
                                        Assign Selected
                                    </button>
                                </form>
                            @else
                                <div style="text-align: center; color: var(--text-muted); padding: 1.5rem 0.5rem;">
                                    <i data-lucide="check-circle-2" data-size="32" style="color: var(--primary); margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto;"></i>
                                    <div style="font-weight: 600; color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.15rem;">All students assigned</div>
                                    <p style="font-size: 0.8rem; line-height: 1.4;">There are no other students in your database to enroll. Add them in the "Student's List" tab first.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Static Unenroll Form -->
                <form id="unenroll-form" method="POST" style="display: none;">
                    @csrf
                </form>

                <!-- Static Bulk Unenroll Form -->
                <form id="bulk-unenroll-form" method="POST" action="{{ route('classes.bulkUnenroll', $class->id) }}" style="display: none;">
                    @csrf
                    <template x-for="id in selectedRosterIds" :key="id">
                        <input type="hidden" name="student_ids[]" :value="id">
                    </template>
                </form>

                <!-- Remove Student Confirmation Modal -->
                <div x-show="showRemoveModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showRemoveModal = false"
                     style="display: none;">
                    <div x-show="showRemoveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showRemoveModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <div x-show="showRemoveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff1f2; border: 1px solid #fecdd3;">
                                <i data-lucide="user-minus" style="width: 20px; height: 20px; color: #ef4444;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Remove Student</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to remove <span style="font-weight: 600; color: #0f172a;" x-text="removeStudentName"></span> from this class roster?
                                </p>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showRemoveModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="confirmRemove(); showRemoveModal = false;" class="btn" 
                                    style="background: #ef4444; color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Remove Students Confirmation Modal -->
                <div x-show="showBulkRemoveModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showBulkRemoveModal = false"
                     style="display: none;">
                    <!-- Backdrop overlay -->
                    <div x-show="showBulkRemoveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showBulkRemoveModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <!-- Modal Content -->
                    <div x-show="showBulkRemoveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff1f2; border: 1px solid #fecdd3;">
                                <i data-lucide="user-minus" style="width: 20px; height: 20px; color: #ef4444;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Remove Selected Students</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to remove the <span style="font-weight: 600; color: #0f172a;" x-text="selectedRosterIds.length"></span> selected student(s) from this class roster?
                                </p>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showBulkRemoveModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="confirmBulkRemove(); showBulkRemoveModal = false;" class="btn" 
                                    style="background: #ef4444; color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Assign Students Confirmation Modal -->
                <div x-show="showAssignModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showAssignModal = false"
                     style="display: none;">
                    <div x-show="showAssignModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showAssignModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <div x-show="showAssignModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #ecfdf5; border: 1px solid #a7f3d0;">
                                <i data-lucide="user-plus" style="width: 20px; height: 20px; color: #10b981;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Assign Students</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to enroll the selected student(s) into this class?
                                </p>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showAssignModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="confirmAssign(); showAssignModal = false;" class="btn" 
                                    style="background: var(--primary); color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        {{-- ── TAB 2: ATTENDANCE SHEET ────────────────────────────────────── --}}
        @elseif($tab === 'attendance')
            <style>
                /* Custom scrollbar styling for the attendance sheet table container */
                .attendance-scroll-container::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }
                .attendance-scroll-container::-webkit-scrollbar-track {
                    background: #f1f5f9;
                }
                .attendance-scroll-container::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 3px;
                }
                .attendance-scroll-container::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }

                /* Premium Segmented Control Button Styles */
                .segmented-btn {
                    padding: 0.35rem 0.75rem !important;
                    font-size: 0.8rem !important;
                    font-weight: 600 !important;
                    border-radius: 9999px !important; /* Perfect oval shape */
                    border: none !important;
                    cursor: pointer;
                    transition: all 0.2s ease-in-out;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                }
                .segmented-badge {
                    font-size: 0.7rem !important;
                    padding: 0.05rem 0.35rem !important;
                    border-radius: 9999px !important; /* Perfect oval badge shape */
                    transition: all 0.2s ease-in-out;
                }
            </style>
            <div class="card show-attendance-card" x-data="{
                students: {
                    @foreach($enrolledStudents as $student)
                    @php
                        $record = $student->attendanceRecord;
                        $status = old('attendance.' . $student->id . '.status', $record?->status ?? '');
                        $remarks = old('attendance.' . $student->id . '.remarks', $record?->remarks ?? '');
                        $timeIn = old('attendance.' . $student->id . '.time_in', $record?->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '');
                    @endphp
                    '{{ $student->id }}': {
                        id: {{ $student->id }},
                        student_id_number: @js($student->student_id_number),
                        full_name: @js($student->full_name),
                        status: @js($status),
                        remarks: @js($remarks),
                        time_in: @js($timeIn),
                        selected: false
                    },
                    @endforeach
                },
                searchQuery: '',
                statusFilter: 'all',
                isToday: @js($date === today()->toDateString()),
                showBulkModal: false,
                pendingBulkStatus: '',
                showResetModal: false,
                showSaveModal: false,

                getCurrentTime() {
                    const now = new Date();
                    let hours = now.getHours();
                    let minutes = now.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    const strHours = hours < 10 ? '0' + hours : hours;
                    return `${strHours}:${minutes} ${ampm}`;
                },

                confirmSave() {
                    document.getElementById('attendance-form').submit();
                },

                get studentList() {
                    return Object.values(this.students);
                },

                get filteredStudents() {
                    let q = this.searchQuery.toLowerCase().trim();
                    return this.studentList.filter(s => {
                        let matchesSearch = !q || s.full_name.toLowerCase().includes(q) || s.student_id_number.toLowerCase().includes(q);
                        let matchesStatus = this.statusFilter === 'all' || s.status === this.statusFilter;
                        return matchesSearch && matchesStatus;
                    });
                },

                isRowVisible(studentId) {
                    return this.filteredStudents.some(s => s.id === studentId);
                },

                get isAllSelected() {
                    let currentFiltered = this.filteredStudents;
                    if (currentFiltered.length === 0) return false;
                    return currentFiltered.every(s => s.selected);
                },

                toggleAll() {
                    let selectState = !this.isAllSelected;
                    this.filteredStudents.forEach(s => s.selected = selectState);
                },

                bulkAssign(status) {
                    if (!this.isToday) return;
                    const currentTime = this.getCurrentTime();
                    this.studentList.forEach(s => {
                        if (s.selected) {
                            s.status = status;
                            if (status === 'present') {
                                s.remarks = '';
                            }
                            if (status === 'present' || status === 'late') {
                                s.time_in = currentTime;
                            } else {
                                s.time_in = '';
                            }
                            s.selected = false;
                        }
                    });
                },

                get anySelected() {
                    return this.studentList.some(s => s.selected);
                }
            }">
                <div class="show-attendance-header">
                    <div>
                        <h3 class="card-title" style="margin: 0; font-weight: 700;">Class Attendance Sheet</h3>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">Select a date to take or review attendance records</div>
                    </div>
                    <div class="show-attendance-controls">
                        <!-- Segmented control status filter on the left of the date picker -->
                        <div class="show-attendance-filter-group">
                            <button type="button" @click="statusFilter = 'all'" class="segmented-btn"
                                    :style="statusFilter === 'all' ? 'background: #ffffff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);' : 'background: transparent; color: #64748b;'">
                                All <span class="segmented-badge" :style="statusFilter === 'all' ? 'background: #f1f5f9; color: #0f172a;' : 'background: #e2e8f0; color: #64748b;'" x-text="studentList.length"></span>
                            </button>
                            <button type="button" @click="statusFilter = 'present'" class="segmented-btn"
                                    :style="statusFilter === 'present' ? 'background: #dcfce7; color: #15803d; box-shadow: 0 1px 2px rgba(34,197,94,0.15);' : 'background: transparent; color: #64748b;'">
                                Present <span class="segmented-badge" :style="statusFilter === 'present' ? 'background: #bbf7d0; color: #15803d;' : 'background: #e2e8f0; color: #64748b;'" x-text="studentList.filter(s => s.status === 'present').length"></span>
                            </button>
                            <button type="button" @click="statusFilter = 'late'" class="segmented-btn"
                                    :style="statusFilter === 'late' ? 'background: #fef3c7; color: #b45309; box-shadow: 0 1px 2px rgba(245,158,11,0.15);' : 'background: transparent; color: #64748b;'">
                                Late <span class="segmented-badge" :style="statusFilter === 'late' ? 'background: #fde68a; color: #b45309;' : 'background: #e2e8f0; color: #64748b;'" x-text="studentList.filter(s => s.status === 'late').length"></span>
                            </button>
                            <button type="button" @click="statusFilter = 'absent'" class="segmented-btn"
                                    :style="statusFilter === 'absent' ? 'background: #fee2e2; color: #b91c1c; box-shadow: 0 1px 2px rgba(239,68,68,0.15);' : 'background: transparent; color: #64748b;'">
                                Absent <span class="segmented-badge" :style="statusFilter === 'absent' ? 'background: #fecaca; color: #b91c1c;' : 'background: #e2e8f0; color: #64748b;'" x-text="studentList.filter(s => s.status === 'absent').length"></span>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('classes.show', $class->id) }}" class="show-attendance-form">
                            <input type="hidden" name="tab" value="attendance">
                            @if(request('tab') === 'attendance' && request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <span x-show="!isToday" style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; padding: 0.35rem 0.65rem; border-radius: 8px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-lucide="eye" data-size="14"></i> Read Only
                            </span>
                            <label class="form-label" for="att_date" style="margin: 0; font-weight: 600; text-transform: uppercase; font-size: 0.72rem; color: var(--text-muted);">Date:</label>
                            <input type="date" id="att_date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()" style="width: auto; height: 38px; border-radius: 8px;">
                        </form>
                    </div>
                </div>

                {{-- Search bar for Attendance --}}
                @if($totalEnrolledCount > 0)
                    <div class="show-attendance-search">
                        <div class="show-attendance-search-inner">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center;">
                                <i data-lucide="search" data-size="16"></i>
                            </span>
                            <input class="form-control" type="text" x-model="searchQuery"
                                   placeholder="Search students in attendance sheet..." style="padding-left: 2.25rem; padding-right: 2.5rem; height: 38px; border-radius: 8px;">
                            <button type="button" x-show="searchQuery" @click="searchQuery = ''"
                                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border:none;background:transparent;cursor:pointer;border-radius:50%;text-decoration:none;transition:background-color 0.2s, color 0.2s;"
                                    title="Clear search"
                                    onmouseover="this.style.color='var(--text-main)';this.style.background='rgba(0,0,0,0.05)';"
                                    onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent';"
                            >
                                <i data-lucide="x" data-size="16"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card-body" style="padding: 0;">
                    @if($totalEnrolledCount > 0)
                        <!-- Lockdown Warning Banner -->
                        <div x-show="!isToday" style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 0.85rem 1.25rem; margin: 1rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; color: #92400e; font-size: 0.85rem;">
                            <i data-lucide="alert-triangle" data-size="18" style="color: #d97706; flex-shrink: 0;"></i>
                            <span style="font-weight: 500;">You are viewing attendance records for a past/future date. Attendance changes can only be recorded or edited for today's date.</span>
                        </div>

                        <!-- Bulk Actions Block -->
                        <div x-show="anySelected && isToday" x-transition
                             style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 0.75rem 1.25rem; margin: 1rem 1.5rem 0.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: #166534;">
                                <i data-lucide="check-square" data-size="18"></i>
                                <span>Selected <span x-text="studentList.filter(s => s.selected).length"></span> student(s)</span>
                            </div>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <span style="font-size: 0.8rem; color: #166534; font-weight: 600; margin-right: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Bulk Action:</span>
                                <button type="button" @click="pendingBulkStatus = 'present'; showBulkModal = true;" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 0.35rem 0.65rem; background: #ffffff; border-color: #86efac; color: #166534; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span> Present
                                </button>
                                <button type="button" @click="pendingBulkStatus = 'late'; showBulkModal = true;" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 0.35rem 0.65rem; background: #ffffff; border-color: #fde68a; color: #92400e; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></span> Late
                                </button>
                                <button type="button" @click="pendingBulkStatus = 'absent'; showBulkModal = true;" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 0.35rem 0.65rem; background: #ffffff; border-color: #fecaca; color: #991b1b; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span> Absent
                                </button>
                                <button type="button" @click="studentList.forEach(s => s.selected = false)" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 0.35rem 0.65rem; margin-left: 0.5rem;">
                                    Cancel
                                </button>
                            </div>
                        </div>

                        @if(count($enrolledStudents) > 0)
                        <form id="attendance-form" method="POST" action="{{ route('classes.attendance.store', $class->id) }}" class="p-0" @submit.prevent="showSaveModal = true">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            
                            <!-- Scrollable Container with lesser height -->
                            <div class="attendance-scroll-container show-attendance-table-wrap">
                                <table x-show="filteredStudents.length > 0" class="show-attendance-table">
                                    <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc; box-shadow: 0 1px 0 var(--border-color);">
                                        <tr style="height: 40px; vertical-align: middle;">
                                            <!-- Checkbox Header -->
                                            <th style="padding: 0.75rem 1rem; width: 50px; text-align: center;">
                                                <input type="checkbox" :disabled="!isToday" :checked="isAllSelected" @change="toggleAll()" style="border-radius: 4px; border: 1px solid #cbd5e1; color: var(--primary);">
                                            </th>
                                            <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left; width: 140px;">ID Number</th>
                                            <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left;">Student Name</th>
                                            <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left; width: 240px;">Status</th>
                                            <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left; width: 120px;">Time In</th>
                                            <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); text-align: left; min-width: 200px;">Remarks/Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrolledStudents as $student)
                                            <tr x-show="isRowVisible({{ $student->id }})" 
                                                style="border-bottom: 1px solid var(--border-color); vertical-align: middle; height: 48px;">
                                                <!-- Row checkbox -->
                                                <td style="padding: 0.85rem 1rem; text-align: center; vertical-align: middle; width: 50px;">
                                                    <input type="checkbox" :disabled="!isToday" x-model="students[{{ $student->id }}].selected" style="border-radius: 4px; border: 1px solid #cbd5e1; color: var(--primary);">
                                                </td>
                                                <td style="padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ $student->student_id_number }}</td>
                                                <td style="padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 500; color: var(--text-main);">
                                                    {{ $student->full_name }}
                                                </td>
                                                <td style="padding: 0.85rem 1rem;">
                                                    <div class="att-radio-group">
                                                        <input type="radio" :id="'p-' + {{ $student->id }}" class="att-radio" 
                                                               :name="'attendance[' + {{ $student->id }} + '][status]'" value="present" 
                                                               x-model="students[{{ $student->id }}].status" :disabled="!isToday"
                                                               @change="students[{{ $student->id }}].time_in = getCurrentTime()">
                                                        <label :for="'p-' + {{ $student->id }}" class="att-label att-present">Present</label>
 
                                                        <input type="radio" :id="'l-' + {{ $student->id }}" class="att-radio" 
                                                               :name="'attendance[' + {{ $student->id }} + '][status]'" value="late" 
                                                               x-model="students[{{ $student->id }}].status" :disabled="!isToday"
                                                               @change="students[{{ $student->id }}].time_in = getCurrentTime()">
                                                        <label :for="'l-' + {{ $student->id }}" class="att-label att-late">Late</label>
 
                                                        <input type="radio" :id="'a-' + {{ $student->id }}" class="att-radio" 
                                                               :name="'attendance[' + {{ $student->id }} + '][status]'" value="absent" 
                                                               x-model="students[{{ $student->id }}].status" :disabled="!isToday"
                                                               @change="students[{{ $student->id }}].time_in = ''">
                                                        <label :for="'a-' + {{ $student->id }}" class="att-label att-absent">Absent</label>
                                                    </div>
                                                </td>
                                                <td style="padding: 0.85rem 1rem; vertical-align: middle;">
                                                    <div x-show="students[{{ $student->id }}].time_in" style="display: inline-block !important; vertical-align: middle;">
                                                        <div style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 0.35rem; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; color: #334155; white-space: nowrap; vertical-align: middle;">
                                                            <i data-lucide="clock" style="width: 12px; height: 12px; color: #64748b; flex-shrink: 0; display: inline-block !important; vertical-align: middle;"></i>
                                                            <span x-text="students[{{ $student->id }}].time_in" style="display: inline-block !important; vertical-align: middle;"></span>
                                                        </div>
                                                    </div>
                                                    <span x-show="!students[{{ $student->id }}].time_in" style="color: var(--text-muted); font-size: 0.85rem; font-style: italic; padding-left: 0.25rem; vertical-align: middle;">
                                                        --
                                                    </span>
                                                    <input type="hidden" :name="'attendance[' + {{ $student->id }} + '][time_in]'" :value="students[{{ $student->id }}].time_in">
                                                </td>
                                                <td style="padding: 0.85rem 1rem; vertical-align: middle;">
                                                    <div x-show="students[{{ $student->id }}].status === 'late' || students[{{ $student->id }}].status === 'absent'" x-transition style="display: flex; align-items: center;">
                                                        <input type="text" class="form-control" :name="'attendance[' + {{ $student->id }} + '][remarks]'" 
                                                               x-model="students[{{ $student->id }}].remarks" 
                                                               :disabled="!isToday || (students[{{ $student->id }}].status !== 'late' && students[{{ $student->id }}].status !== 'absent')"
                                                               placeholder="e.g. excused, medical, etc." style="height: 34px; border-radius: 6px; font-size: 0.85rem; width: 100%;">
                                                    </div>
                                                    <div x-show="students[{{ $student->id }}].status !== 'late' && students[{{ $student->id }}].status !== 'absent'" style="color: var(--text-muted); font-size: 0.85rem; font-style: italic; padding-left: 0.5rem;">
                                                        --
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Client-side empty search/filter results -->
                                <template x-if="filteredStudents.length === 0">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--text-muted); text-align: center; padding: 2rem;">
                                        <div style="background: #f1f5f9; color: #64748b; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem;">
                                            <i data-lucide="search" data-size="20"></i>
                                        </div>
                                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">No students found</h4>
                                        <p style="font-size: 0.85rem; max-width: 20rem; margin: 0;">No enrolled students match your search or filter criteria.</p>
                                    </div>
                                </template>
                            </div>
 
                            <div class="show-action-footer">
                                <button type="button" @click="showResetModal = true" class="btn btn-secondary" style="border-radius: 8px;">Reset Changes</button>
                                <button type="submit" class="btn btn-primary" :disabled="!isToday" style="display: inline-flex; align-items: center; gap: 0.35rem; border: none; border-radius: 8px;"
                                        :style="!isToday ? 'background: #94a3b8; cursor: not-allowed; opacity: 0.85;' : 'background: var(--primary);'">
                                    <i data-lucide="save" data-size="16" style="vertical-align: middle;"></i>
                                    Save Attendance
                                </button>
                            </div>
                        </form>
                        @else
                            <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                                <i data-lucide="search" data-size="44" style="margin: 0 auto 1rem; color: #cbd5e1;"></i>
                                <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">No students found</h4>
                                <p style="font-size: 0.9rem; max-width: 22rem; margin: 0 auto 1.5rem;">No enrolled students match "{{ request('search') }}".</p>
                            </div>
                        @endif
                    @else
                        <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="calendar" data-size="44" style="margin: 0 auto 1rem; color: #cbd5e1;"></i>
                            <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">Class is empty</h4>
                            <p style="font-size: 0.9rem; max-width: 22rem; margin: 0 auto 1.5rem;">To take attendance, you must first enroll students in this class.</p>
                            <a href="{{ route('classes.show', ['class' => $class->id, 'tab' => 'roster']) }}" class="btn btn-primary" style="background: var(--primary); border: none; border-radius: 8px;">
                                Go to Class Roster
                            </a>
                        </div>
                    @endif
                </div>


                <!-- Bulk Action Confirmation Modal -->
                <div x-show="showBulkModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showBulkModal = false"
                     style="display: none;">
                    <!-- Backdrop overlay -->
                    <div x-show="showBulkModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showBulkModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <!-- Modal Content -->
                    <div x-show="showBulkModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <!-- Icon & Header -->
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"
                                 :style="{
                                     background: pendingBulkStatus === 'present' ? '#ecfdf5' : (pendingBulkStatus === 'late' ? '#fffbeb' : '#fff1f2'),
                                     border: pendingBulkStatus === 'present' ? '1px solid #a7f3d0' : (pendingBulkStatus === 'late' ? '1px solid #fde68a' : '1px solid #fecdd3')
                                 }">
                                <i x-show="pendingBulkStatus === 'present'" data-lucide="check-circle-2" style="width: 20px; height: 20px; color: #10b981;"></i>
                                <i x-show="pendingBulkStatus === 'late'" data-lucide="clock" style="width: 20px; height: 20px; color: #d97706;"></i>
                                <i x-show="pendingBulkStatus === 'absent'" data-lucide="alert-circle" style="width: 20px; height: 20px; color: #e11d48;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Confirm Bulk Action</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to mark <span style="font-weight: 600; color: #0f172a;" x-text="studentList.filter(s => s.selected).length"></span> student(s) as <span style="font-weight: 600; text-transform: capitalize;" :style="{ color: pendingBulkStatus === 'present' ? '#10b981' : (pendingBulkStatus === 'late' ? '#d97706' : '#e11d48') }" x-text="pendingBulkStatus"></span>?
                                </p>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showBulkModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="bulkAssign(pendingBulkStatus); showBulkModal = false;" class="btn" 
                                    :style="{
                                        background: pendingBulkStatus === 'present' ? '#10b981' : (pendingBulkStatus === 'late' ? '#d97706' : '#e11d48'),
                                        color: '#ffffff',
                                        border: 'none',
                                        borderRadius: '8px',
                                        padding: '0.5rem 1rem',
                                        fontSize: '0.875rem',
                                        fontWeight: '500',
                                        cursor: 'pointer',
                                        transition: 'all 0.2s'
                                    }"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reset Changes Confirmation Modal -->
                <div x-show="showResetModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showResetModal = false"
                     style="display: none;">
                    <!-- Backdrop overlay -->
                    <div x-show="showResetModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showResetModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <!-- Modal Content -->
                    <div x-show="showResetModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <!-- Icon & Header -->
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fffbeb; border: 1px solid #fde68a;">
                                <i data-lucide="rotate-ccw" style="width: 20px; height: 20px; color: #d97706;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Reset Changes</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to reset all attendance changes? This will clear all status selections and remarks on the sheet.
                                </p>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showResetModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="studentList.forEach(s => { s.status = ''; s.remarks = ''; s.selected = false; }); showResetModal = false; $nextTick(() => { document.getElementById('attendance-form').submit(); });" class="btn" 
                                    style="background: #d97706; color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Attendance Confirmation Modal -->
                <div x-show="showSaveModal" 
                     class="bulk-modal-container"
                     x-cloak
                     x-on:keydown.escape.window="showSaveModal = false"
                     style="display: none;">
                    <!-- Backdrop overlay -->
                    <div x-show="showSaveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showSaveModal = false"
                         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>

                    <!-- Modal Content -->
                    <div x-show="showSaveModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 400px; border: 1px solid #e2e8f0; padding: 1.5rem; z-index: 10000; box-sizing: border-box;">
                        
                        <!-- Icon & Header -->
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #ecfdf5; border: 1px solid #a7f3d0;">
                                <i data-lucide="save" style="width: 20px; height: 20px; color: #10b981;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 0.125rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #0f172a; margin: 0 0 0.5rem 0; font-family: system-ui, -apple-system, sans-serif;">Save Attendance</h3>
                                <p style="font-size: 0.875rem; color: #475569; line-height: 1.5; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
                                    Are you sure you want to save the attendance records for today?
                                </p>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <button type="button" @click="showSaveModal = false" class="btn btn-secondary" style="border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                                Cancel
                            </button>
                            <button type="button" @click="confirmSave(); showSaveModal = false;" class="btn" 
                                    style="background: var(--primary); color: #ffffff; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.filter = 'brightness(0.95)';"
                                    onmouseout="this.style.filter = 'brightness(1)';">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
