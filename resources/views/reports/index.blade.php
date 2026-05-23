<x-app-layout>
    <x-slot name="title">Reports</x-slot>

    <x-app-banner title="Attendance reports">
        <x-slot name="subtitle">Per-student attendance summary for a selected date range.</x-slot>
    </x-app-banner>

    <style>
        @media (max-width: 640px) {
            .app-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.5rem !important;
                margin-bottom: 1rem !important;
            }
            .app-kpi-card {
                padding: 0.75rem 0.5rem !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                justify-content: center !important;
                min-height: 110px !important;
            }
            .app-kpi-value {
                font-size: 1.25rem !important;
            }
            .app-kpi-label {
                font-size: 0.65rem !important;
            }
            .app-kpi-icon {
                width: 32px !important;
                height: 32px !important;
                margin-bottom: 0.35rem !important;
                order: -1 !important;
            }
            .app-kpi-icon i {
                width: 16px !important;
                height: 16px !important;
            }
            .progress-bar-wrap {
                width: 100% !important;
                height: 4px !important;
            }

            .table-wrap table, 
            .table-wrap thead, 
            .table-wrap tbody, 
            .table-wrap tr, 
            .table-wrap td {
                display: block;
                width: 100%;
            }
            .table-wrap thead {
                display: none;
            }
            .table-wrap tbody {
                display: block !important;
                padding: 0.5rem !important;
            }
            .table-wrap tr {
                margin: 0 0 1rem 0 !important;
                width: 100% !important;
                box-sizing: border-box;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
                padding: 1rem !important;
            }
            .table-wrap td {
                padding: 0.4rem 0 !important;
                border: none !important;
                font-size: 0.85rem !important;
            }
            .table-wrap td:nth-child(3) {
                font-size: 1rem !important;
                font-weight: 700 !important;
                border-bottom: 1px solid #f1f5f9 !important;
                margin-bottom: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }
            .table-wrap td::before {
                display: block;
                font-size: 0.6rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 0.15rem;
            }
            .table-wrap td:nth-child(1) { display: none; }
            .table-wrap td:nth-child(2)::before { content: 'Student ID'; }
            .table-wrap td:nth-child(3)::before { content: 'Full Name'; }
            .table-wrap td:nth-child(4)::before { content: 'Program & Block'; }
            .table-wrap td:nth-child(5)::before { content: 'Present'; display: inline; margin-right: 4px; }
            .table-wrap td:nth-child(6)::before { content: 'Absent'; display: inline; margin-right: 4px; }
            .table-wrap td:nth-child(7)::before { content: 'Late'; display: inline; margin-right: 4px; }
            .table-wrap td:nth-child(8) { display: none; }
            .table-wrap td:nth-child(9)::before { content: 'Attendance Rate'; }
            .table-wrap td:nth-child(10)::before { content: 'Status'; }

            .table-wrap td:nth-child(5),
            .table-wrap td:nth-child(6),
            .table-wrap td:nth-child(7) {
                display: inline-block !important;
                width: auto !important;
                margin-right: 1rem !important;
            }
            
            .badge {
                font-size: 0.75rem !important;
                padding: 0.2rem 0.5rem !important;
            }

            .app-page .card:first-child .card-body {
                padding: 1rem !important;
            }
            .app-page .card:first-child form {
                gap: 0.75rem !important;
            }
            .app-page .card:first-child form > div {
                min-width: 0 !important;
                flex: 1 1 100% !important;
            }

            .report-legend {
                flex-direction: column !important;
                gap: 0.75rem !important;
                padding: 0.85rem 1rem !important;
            }
            .report-legend span {
                display: flex !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                font-size: 0.75rem !important;
            }
            .report-legend i {
                margin-top: 2px !important;
            }

            .card-header {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                padding: 1rem !important;
            }
        }
    </style>

    <div class="app-page">
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.5rem;">
            <form method="GET" action="{{ route('reports.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                <div>
                    <label class="form-label" for="date_from">From</label>
                    <select class="form-control" id="date_from" name="date_from"
                            onchange="if (this.form.date_to.value && this.value > this.form.date_to.value) this.form.date_to.value = this.value; this.form.submit();"
                            {{ $availableDates->isEmpty() ? 'disabled' : '' }}>
                        @forelse($availableDates as $availableDate)
                            <option value="{{ $availableDate }}" {{ $dateFrom === $availableDate ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($availableDate)->format('m/d/Y') }}
                            </option>
                        @empty
                            <option value="">No report dates available</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="form-label" for="date_to">To</label>
                    <select class="form-control" id="date_to" name="date_to"
                            onchange="if (this.form.date_from.value && this.value < this.form.date_from.value) this.form.date_from.value = this.value; this.form.submit();"
                            {{ $availableDates->isEmpty() ? 'disabled' : '' }}>
                        @forelse($availableDates as $availableDate)
                            <option value="{{ $availableDate }}" {{ $dateTo === $availableDate ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($availableDate)->format('m/d/Y') }}
                            </option>
                        @empty
                            <option value="">No report dates available</option>
                        @endforelse
                    </select>
                </div>
                <div style="min-width:160px;">
                    <label class="form-label">Classcode</label>
                    <select class="form-control" name="section" onchange="this.form.submit()">
                        <option value="">Class Code</option>
                        @foreach($classFilters as $cls)
                            <option value="{{ $cls->id }}" {{ (string) $section === (string) $cls->id ? 'selected' : '' }}>
                                {{ $cls->class_code ? ($cls->class_code . ' - ' . $cls->class_name) : $cls->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:160px;">
                    <label class="form-label">Program, Year & Block</label>
                    <select class="form-control" name="student_section" onchange="this.form.submit()">
                        <option value="">All Programs</option>
                        @foreach($studentSections as $sec)
                            <option value="{{ $sec }}" {{ $studentSection === $sec ? 'selected' : '' }}>
                                {{ $sec }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Reset button removed per request --}}
            </form>
        </div>
    </div>

    {{-- Class Summary --}}
    <div class="app-kpi-grid">
        <div class="app-kpi-card">
            <div class="app-kpi-icon" style="background:rgba(99,102,241,.12);">
                <i data-lucide="users" data-size="22" style="color:#6366f1;"></i>
            </div>
            <div>
                <div class="app-kpi-value">{{ $students->count() }}</div>
                <div class="app-kpi-label">Students in report</div>
            </div>
        </div>
        <div class="app-kpi-card">
            <div class="app-kpi-icon" style="background:rgba(34,197,94,.15);">
                <i data-lucide="calendar" data-size="22" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="app-kpi-value">{{ $classTotal }}</div>
                <div class="app-kpi-label">Total sessions recorded</div>
            </div>
        </div>
        <div class="app-kpi-card">
            <div class="app-kpi-icon" style="background:rgba(99,102,241,.12);">
                <i data-lucide="chart-line" data-size="22" style="color:#6366f1;"></i>
            </div>
            <div>
                <div class="app-kpi-value">{{ $classRate }}<span style="font-size:1rem;">%</span></div>
                <div class="app-kpi-label">Class attendance rate</div>
                <div style="margin-top:.5rem;">
                    <div class="progress-bar-wrap">
                        <div class="progress-bar {{ $classRate >= 75 ? 'progress-success' : 'progress-danger' }}"
                             data-progress="{{ $classRate }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="report-legend" style="margin:1rem 0 1.5rem;padding:1rem 1.25rem;background:var(--surface);border-radius:12px;border:1px solid var(--border-color);box-shadow:var(--shadow-card);display:flex;gap:1.5rem;flex-wrap:wrap;font-size:.78rem;color:var(--text-muted);">
        <span style="display:inline-flex;align-items:center;gap:.35rem;">
            <i data-lucide="pin" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
            <strong style="color:var(--text);">Threshold:</strong> ≥75% = Good &nbsp;|&nbsp; 50–74% = At Risk &nbsp;|&nbsp; &lt;50% = Critical
        </span>
        <span style="display:inline-flex;align-items:center;gap:.35rem;">
            <i data-lucide="calendar" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
            <strong style="color:var(--text);">Period:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('F j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('F j, Y') }}
        </span>
    </div>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:var(--surface);border:1px solid var(--border-color);box-shadow:var(--shadow-card);">
                    <i data-lucide="clipboard-list" data-size="18" style="color:var(--text);"></i>
                </div>
                <span class="card-title" style="font-weight:600;">Student Breakdown</span>
            </div>
            <span style="font-size:.75rem;color:var(--text-muted);">
                {{ \Carbon\Carbon::parse($dateFrom)->format('M j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
            </span>
        </div>
        @if($students->isEmpty())
            <div class="empty-state" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 2rem;text-align:center;">
                <div class="icon" aria-hidden="true" style="margin-bottom:1rem;">
                    <i data-lucide="inbox" data-size="48"></i>
                </div>
                <h3>No data for this period</h3>
                <p>Adjust the date range or add students and mark attendance first.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Program, Year & Block</th>
                            <th style="text-align:center;">Present</th>
                            <th style="text-align:center;">Absent</th>
                            <th style="text-align:center;">Late</th>
                            <th style="text-align:center;">Total</th>
                            <th>Attendance %</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $i => $student)
                            <tr>
                                <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge badge-muted" style="font-family:monospace;">
                                        {{ $student->student_id_number }}
                                    </span>
                                </td>
                                <td style="font-weight:600;">{{ $student->full_name }}</td>
                                <td><span class="badge badge-muted">{{ $student->reportCourseBlock }}</span></td>
                                <td style="text-align:center;">
                                    <span style="color:#6ee7b7;font-weight:700;">{{ $student->reportPresent }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span style="color:#fca5a5;font-weight:700;">{{ $student->reportAbsent }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span style="color:#fcd34d;font-weight:700;">{{ $student->reportLate }}</span>
                                </td>
                                <td style="text-align:center;color:var(--text-muted);">{{ $student->reportTotal }}</td>
                                <td>
                                    @php $pct = $student->reportPercent; @endphp
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <span class="{{ $pct >= 75 ? 'text-success' : 'text-danger' }}" style="font-weight:700;font-size:.9rem;min-width:42px;">
                                            {{ $pct }}%
                                        </span>
                                        <div class="progress-bar-wrap" style="width:80px;">
                                            <div class="progress-bar {{ $pct >= 75 ? 'progress-success' : 'progress-danger' }}"
                                                 data-progress="{{ $pct }}"></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    @if($student->reportTotal === 0)
                                        <span class="badge badge-muted">No Data</span>
                                    @elseif($pct >= 75)
                                        <span class="badge badge-success">
                                            <i data-lucide="check-circle-2" data-size="16" style="margin-right:.35rem;vertical-align:middle; color:#166534;"></i>
                                            Good
                                        </span>
                                    @elseif($pct >= 50)
                                        <span class="badge badge-warning">
                                            <i data-lucide="alert-triangle" data-size="16" style="margin-right:.35rem;vertical-align:middle; color:#92400e;"></i>
                                            At Risk
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i data-lucide="sirens" data-size="16" style="margin-right:.35rem;vertical-align:middle; color:#991b1b;"></i>
                                            Critical
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    </div>
</x-app-layout>
