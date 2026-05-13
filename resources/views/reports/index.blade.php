<x-app-layout>
    <x-slot name="title">Reports</x-slot>

    <x-app-banner title="Attendance reports">
        <x-slot name="subtitle">Per-student attendance summary for a selected date range.</x-slot>
    </x-app-banner>

    <div class="app-page">
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.5rem;">
            <form method="GET" action="{{ route('reports.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                <div>
                    <label class="form-label" for="date_from">From</label>
                    <input class="form-control" id="date_from" type="date" name="date_from"
                           value="{{ $dateFrom }}" max="{{ today()->toDateString() }}">
                </div>
                <div>
                    <label class="form-label" for="date_to">To</label>
                    <input class="form-control" id="date_to" type="date" name="date_to"
                           value="{{ $dateTo }}" max="{{ today()->toDateString() }}">
                </div>
                <div style="min-width:160px;">
                    <label class="form-label">Section</label>
                    <select class="form-control" name="section">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec }}" {{ $section === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">
                    <i data-lucide="bar-chart-3" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Generate
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i data-lucide="rotate-ccw" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                    Reset
                </a>
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

    {{-- Report Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i data-lucide="clipboard-list" data-size="18" style="margin-right:.35rem;vertical-align:middle;"></i>
                Student Breakdown
            </span>
            <span style="font-size:.75rem;color:var(--text-muted);">
                {{ \Carbon\Carbon::parse($dateFrom)->format('M j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
            </span>
        </div>
        @if($students->isEmpty())
            <div class="empty-state">
                <div class="icon" aria-hidden="true">
                    <i data-lucide="inbox" data-size="34"></i>
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
                            <th>Section</th>
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
                                <td><span class="badge badge-muted">{{ $student->section }}</span></td>
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

    {{-- Legend --}}
    <div style="margin-top:1rem;padding:1rem 1.25rem;background:var(--surface);border-radius:12px;border:1px solid var(--border-color);box-shadow:var(--shadow-card);display:flex;gap:1.5rem;flex-wrap:wrap;font-size:.78rem;color:var(--text-muted);">
        <span>
            <i data-lucide="pin" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
            <strong style="color:var(--text);">Threshold:</strong> ≥75% = Good &nbsp;|&nbsp; 50–74% = At Risk &nbsp;|&nbsp; &lt;50% = Critical
        </span>
        <span>
            <i data-lucide="calendar" data-size="16" style="margin-right:.35rem;vertical-align:middle;"></i>
            <strong style="color:var(--text);">Period:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('F j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('F j, Y') }}
        </span>
    </div>
    </div>
</x-app-layout>
