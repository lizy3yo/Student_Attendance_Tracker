<x-app-layout>
    <x-slot name="title">Reports</x-slot>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 style="font-size:1.4rem;font-weight:800;">Attendance Reports</h2>
            <p style="color:var(--text-muted);font-size:.85rem;">Per-student attendance summary for a selected date range.</p>
        </div>
    </div>

    {{-- Filters --}}
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
                <button class="btn btn-primary" type="submit">📊 Generate</button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">↺ Reset</a>
            </form>
        </div>
    </div>

    {{-- Class Summary --}}
    <div class="stat-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,.15);">👥</div>
            <div>
                <div class="stat-value">{{ $students->count() }}</div>
                <div class="stat-label">Students in Report</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.15);">📅</div>
            <div>
                <div class="stat-value">{{ $classTotal }}</div>
                <div class="stat-label">Total Sessions Recorded</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,.15);">📊</div>
            <div>
                <div class="stat-value">{{ $classRate }}<span style="font-size:1rem;">%</span></div>
                <div class="stat-label">Class Attendance Rate</div>
                <div style="margin-top:.5rem;">
                    <div class="progress-bar-wrap">
                        <div class="progress-bar {{ $classRate >= 75 ? 'progress-success' : 'progress-danger' }}"
                             style="width:{{ $classRate }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Student Breakdown</span>
            <span style="font-size:.75rem;color:var(--text-muted);">
                {{ \Carbon\Carbon::parse($dateFrom)->format('M j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
            </span>
        </div>
        @if($students->isEmpty())
            <div class="empty-state">
                <div class="icon">📭</div>
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
                                        <span style="font-weight:700;font-size:.9rem;min-width:42px;{{ $pct >= 75 ? 'color:#6ee7b7' : 'color:#fca5a5' }}">
                                            {{ $pct }}%
                                        </span>
                                        <div class="progress-bar-wrap" style="width:80px;">
                                            <div class="progress-bar {{ $pct >= 75 ? 'progress-success' : 'progress-danger' }}"
                                                 style="width:{{ $pct }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    @if($student->reportTotal === 0)
                                        <span class="badge badge-muted">No Data</span>
                                    @elseif($pct >= 75)
                                        <span class="badge badge-success">✅ Good</span>
                                    @elseif($pct >= 50)
                                        <span class="badge badge-warning">⚠️ At Risk</span>
                                    @else
                                        <span class="badge badge-danger">🚨 Critical</span>
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
    <div style="margin-top:1rem;padding:1rem 1.25rem;background:var(--surface);border-radius:10px;border:1px solid var(--sidebar-border);display:flex;gap:1.5rem;flex-wrap:wrap;font-size:.78rem;color:var(--text-muted);">
        <span>📌 <strong style="color:var(--text);">Threshold:</strong> ≥75% = Good &nbsp;|&nbsp; 50–74% = At Risk &nbsp;|&nbsp; &lt;50% = Critical</span>
        <span>📅 <strong style="color:var(--text);">Period:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('F j') }} – {{ \Carbon\Carbon::parse($dateTo)->format('F j, Y') }}</span>
    </div>
</x-app-layout>
