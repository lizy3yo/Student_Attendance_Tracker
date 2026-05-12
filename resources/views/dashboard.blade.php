<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,.15);">👥</div>
            <div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.15);">✅</div>
            <div>
                <div class="stat-value" style="color:#6ee7b7;">{{ $presentToday }}</div>
                <div class="stat-label">Present Today</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,.15);">❌</div>
            <div>
                <div class="stat-value" style="color:#fca5a5;">{{ $absentToday }}</div>
                <div class="stat-label">Absent Today</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,.15);">⏱</div>
            <div>
                <div class="stat-value" style="color:#fcd34d;">{{ $lateToday }}</div>
                <div class="stat-label">Late Today</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,.15);">📊</div>
            <div>
                <div class="stat-value">{{ $attendanceRate }}<span style="font-size:1rem;font-weight:500;">%</span></div>
                <div class="stat-label">Attendance Rate</div>
                <div style="margin-top:.5rem;">
                    <div class="progress-bar-wrap">
                        <div class="progress-bar {{ $attendanceRate >= 75 ? 'progress-success' : 'progress-danger' }}"
                             style="width:{{ $attendanceRate }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(139,92,246,.15);">📋</div>
            <div>
                <div class="stat-value" style="color:#c4b5fd;">{{ $markedToday }}/{{ $totalStudents }}</div>
                <div class="stat-label">Marked Today</div>
                @if($totalStudents > 0 && $markedToday < $totalStudents)
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-sm" style="margin-top:.5rem;">Mark Now</a>
                @endif
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

        {{-- Weekly Chart --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">📅 Weekly Overview</span>
                <span style="font-size:.75rem;color:var(--text-muted);">Last 7 days</span>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:flex-end;gap:.75rem;height:140px;">
                    @foreach($weeklyData as $day)
                        @php
                            $total = $day['present'] + $day['absent'] + $day['late'];
                            $height = $total > 0 ? max(8, round(($day['present'] / max($total,1)) * 100)) : 4;
                        @endphp
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:.4rem;">
                            <div style="font-size:.65rem;color:var(--text-muted);">
                                {{ $day['present'] > 0 ? $day['present'] : '' }}
                            </div>
                            <div style="width:100%;background:var(--sidebar-bg);border-radius:6px;height:100px;display:flex;align-items:flex-end;overflow:hidden;">
                                <div style="width:100%;height:{{ $height }}%;background:linear-gradient(180deg,#6366f1,#4f46e5);border-radius:6px;transition:height .5s;"></div>
                            </div>
                            <div style="font-size:.7rem;color:{{ $day['date'] === now()->toDateString() ? '#a5b4fc' : 'var(--text-muted)' }};font-weight:{{ $day['date'] === now()->toDateString() ? '700' : '400' }};">
                                {{ $day['day'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="display:flex;gap:1rem;margin-top:1rem;flex-wrap:wrap;">
                    <span style="font-size:.72rem;color:#6ee7b7;display:flex;align-items:center;gap:.3rem;"><span style="width:8px;height:8px;background:#10b981;border-radius:50%;display:inline-block;"></span>Present</span>
                    <span style="font-size:.72rem;color:#fca5a5;display:flex;align-items:center;gap:.3rem;"><span style="width:8px;height:8px;background:#ef4444;border-radius:50%;display:inline-block;"></span>Absent</span>
                    <span style="font-size:.72rem;color:#fcd34d;display:flex;align-items:center;gap:.3rem;"><span style="width:8px;height:8px;background:#f59e0b;border-radius:50%;display:inline-block;"></span>Late</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">⚡ Quick Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem;">
                <a href="{{ route('attendance.index') }}" class="btn btn-primary" style="justify-content:flex-start;">
                    ✅ &nbsp; Take Today's Attendance
                </a>
                <a href="{{ route('students.create') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                    ➕ &nbsp; Add New Student
                </a>
                <a href="{{ route('students.index') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                    👥 &nbsp; Manage Student List
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                    📈 &nbsp; View Attendance Reports
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🕐 Recent Activity</span>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        @if($recentLogs->isEmpty())
            <div class="empty-state">
                <div class="icon">📭</div>
                <h3>No attendance records yet</h3>
                <p>Start by taking attendance for your class.</p>
                <a href="{{ route('attendance.index') }}" class="btn btn-primary" style="margin-top:1rem;">Take Attendance</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Section</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                            <tr>
                                <td style="font-weight:600;">{{ $log->student->full_name }}</td>
                                <td><span class="badge badge-muted">{{ $log->student->section }}</span></td>
                                <td style="color:var(--text-muted);">{{ $log->date->format('M j, Y') }}</td>
                                <td>
                                    @php $badge = $log->statusBadge(); @endphp
                                    <span class="badge badge-{{ $badge['color'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
