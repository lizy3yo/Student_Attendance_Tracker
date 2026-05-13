<x-app-layout>
    <x-slot name="title">Overview</x-slot>

    <div class="page-content dashboard-page">
        {{-- Dashboard Header --}}
        <div class="hero-header">
            <div class="hero-content">
                <div class="hero-user-info">
                    <div class="hero-avatar" style="background: url('https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=cbd5e1&color=1e293b') center/cover;"></div>
                    <div class="hero-greeting">
                        <div class="hero-eyebrow">Attendance control center</div>
                        <h1 class="hero-title">Hello {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                        <p class="hero-subtitle">Track today's attendance, review trends, and keep class activity organized from one place.</p>
                    </div>
                </div>

                <div class="hero-summary">
                    <div class="hero-summary-card">
                        <span class="hero-summary-label">Today marked</span>
                        <strong>{{ $markedToday }}/{{ $totalStudents }}</strong>
                    </div>
                    <div class="hero-summary-card">
                        <span class="hero-summary-label">Attendance rate</span>
                        <strong>{{ $attendanceRate }}%</strong>
                    </div>
                    <!-- Live status card removed -->
                </div>
            </div>
        </div>

    <style>
        .dashboard-page {
            --dash-gap: clamp(1rem, 2.25vw, 1.5rem);
            --hero-overlap: clamp(3.25rem, 8vw, 5rem);
            --hero-tail: clamp(2rem, 5vw, 3.25rem);
            --stat-pad: clamp(1rem, 2vw, 1.375rem) clamp(1rem, 2.5vw, 1.5rem);
            --card-pad: clamp(1rem, 2.5vw, 1.5rem);
            --fs-card-title: clamp(0.9375rem, 1.1vw, 1rem);
            --fs-chart-axis: clamp(0.8125rem, 1.5vw, 0.875rem);
            position: relative;
            isolation: isolate;
        }

        .dashboard-page::before {
            content: '';
            position: fixed;
            inset: 70px 0 0;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.08), transparent 34%),
                radial-gradient(circle at 90% 10%, rgba(15, 23, 42, 0.04), transparent 28%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.42), rgba(255, 255, 255, 0));
            pointer-events: none;
            z-index: -1;
        }

        .page-content.dashboard-page {
            padding-top: 0;
            padding-left: var(--page-inline-pad);
            padding-right: var(--page-inline-pad);
            padding-bottom: clamp(1rem, 3vw, 2.5rem);
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Hero: full-viewport-width band (break out of max-width parent), content capped at 1400px */
        .hero-header {
            background:
                linear-gradient(135deg, #0f172a 0%, #111827 44%, #0b1220 100%);
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.06);
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            box-sizing: border-box;
            padding: clamp(1rem, 2.5vw, 1.5rem) 0 calc(var(--hero-overlap) + var(--hero-tail));
            position: relative;
            border-radius: 0;
            overflow: hidden;
        }
        .hero-header::before,
        .hero-header::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }
        .hero-header::before {
            inset: auto auto -3.5rem -2rem;
            width: 14rem;
            height: 14rem;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.24), transparent 68%);
        }
        .hero-header::after {
            top: -2rem;
            right: 6%;
            width: 12rem;
            height: 12rem;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent 72%);
        }
        .hero-content {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding-left: var(--page-inline-pad);
            padding-right: var(--page-inline-pad);
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--dash-gap);
            flex-wrap: wrap;
        }
        .hero-user-info { display: flex; align-items: center; gap: clamp(0.875rem, 2vw, 1rem); min-width: min(100%, 240px); }
        .hero-greeting { min-width: 0; }
        .hero-avatar { width: clamp(44px, 4vw, 52px); height: clamp(44px, 4vw, 52px); border-radius: 50%; min-width: clamp(44px, 4vw, 52px); flex-shrink: 0; }
        .hero-eyebrow {
            color: #86efac;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }
        .hero-title {
            color: white;
            font-size: clamp(1.25rem, 2.4vw, 1.5rem);
            font-weight: 700;
            margin: 0 0 0.25rem;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }
        .hero-subtitle { color: #d1d5db; font-size: clamp(0.875rem, 1.6vw, 0.9375rem); line-height: 1.55; margin: 0; max-width: 44rem; }
        .hero-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            min-width: min(100%, 380px);
        }

        @media (min-width: 993px) {
            /* Ensure user info grows so summary aligns with page content edge */
            .hero-user-info { flex: 1 1 auto; }
            .hero-summary { margin-left: 1rem; }
        }
        .hero-summary-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 0.875rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            backdrop-filter: blur(10px);
        }
        .hero-summary-label {
            color: #cbd5e1;
            font-size: 0.6875rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .hero-summary-card strong {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Layout Grids — consistent gutters */
        .stat-grid-modern {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: var(--dash-gap);
            margin-top: calc(-1 * var(--hero-overlap));
            position: relative;
            z-index: 10;
            margin-bottom: var(--dash-gap);
        }

        .modern-stat-card {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-radius: 16px;
            padding: var(--stat-pad);
            padding-right: 2.125rem;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            border: 1px solid #e5e7eb;
            min-height: clamp(5rem, 12vw, 5.5rem);
        }

        .stat-card-main {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            flex: 1;
            min-width: 0;
            justify-content: flex-start;
        }

        .modern-stat-card.stat-card--accent {
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 55%);
            border-color: #bbf7d0;
            box-shadow: 0 10px 28px rgba(22, 163, 74, 0.08);
        }

        .modern-stat-card .dots {
            position: absolute;
            top: 0.75rem;
            right: 0.875rem;
            z-index: 2;
            color: #9ca3af;
            cursor: pointer;
            font-weight: 700;
            letter-spacing: 0.125em;
            font-size: 0.875rem;
            line-height: 1;
            padding: 0.25rem;
        }
        .modern-stat-card.stat-card--accent .dots { color: #16a34a; }

        .stat-icon-circle {
            width: clamp(44px, 4vw, 50px);
            height: clamp(44px, 4vw, 50px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            min-width: clamp(44px, 4vw, 50px);
            flex-shrink: 0;
        }

        .stat-icon-circle.white-border {
            border: 1px dashed #cbd5e1;
            color: #22c55e;
            background: #f8fafc;
        }

        .stat-icon-circle.accent-bg {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .stat-content {
            flex: 1;
            min-width: 0;
            text-align: left;
            align-self: stretch;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-number { font-size: clamp(1.5rem, 2.5vw, 1.75rem); font-weight: 700; line-height: 1.15; color: #111827; }
        .modern-stat-card.stat-card--accent .stat-number { color: #14532d; }

        .stat-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.06em;
            margin-top: 0.125rem;
        }
        .modern-stat-card.stat-card--accent .stat-label { color: #15803d; }

        .stat-card-meta {
            margin-top: 0.5rem;
        }
        .stat-card-link {
            display: inline-flex;
            align-items: center;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--primary-dark);
            text-decoration: none;
            padding: 0.25rem 0;
        }
        .stat-card-link:hover { text-decoration: underline; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: var(--dash-gap);
            margin-bottom: var(--dash-gap);
        }

        .dashboard-grid-bottom {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: var(--dash-gap);
        }

        .modern-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            padding: var(--card-pad);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--border-color);
            position: relative;
            width: 100%;
            min-width: 0;
            overflow: visible;
        }

        .modern-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: clamp(0.875rem, 1.8vw, 1.125rem);
            padding-right: 0;
        }

        .modern-card-title {
            font-size: var(--fs-card-title);
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.35;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .card-menu {
            color: #9ca3af;
            cursor: pointer;
            font-weight: 700;
            letter-spacing: 0.125em;
            font-size: 0.875rem;
            line-height: 1;
            padding: 0.25rem;
            flex-shrink: 0;
            margin: -0.25rem -0.25rem 0 0;
        }

        .attendance-range-form {
            display: flex;
            align-items: center;
        }

        .attendance-range-select {
            appearance: none;
            border: 1px solid #dbe4f0;
            background: #f8fafc;
            color: #0f172a;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.55rem 0.85rem;
            min-width: 140px;
            outline: none;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .attendance-range-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
        }

        /* Charts & Lists */
        .chart-placeholder {
            height: clamp(220px, 28vw, 260px);
            display: flex;
            align-items: flex-end;
            gap: 1.5%;
            position: relative;
            padding-bottom: clamp(1.75rem, 3vw, 2.125rem);
        }

        .chart-bar {
            background: #f1f5f9;
            flex: 1;
            min-width: 0;
            border-radius: 6px 6px 0 0;
            position: relative;
        }

        .chart-bar.active {
            background: #22c55e;
            background: repeating-linear-gradient(45deg, #22c55e, #22c55e 10px, #16a34a 10px, #16a34a 20px);
        }

        .chart-label {
            position: absolute;
            bottom: -2rem;
            width: 100%;
            text-align: center;
            font-size: var(--fs-chart-axis);
            color: #64748b;
            font-weight: 600;
        }

        .chart-line-wrap {
            position: relative;
            margin-bottom: 0.25rem;
        }
        .chart-line-inner {
            width: 100%;
            height: clamp(220px, 28vw, 270px);
            position: relative;
        }
        .chart-h-scroll-slab {
            min-width: 600px;
        }
        .chart-x-labels {
            display: grid;
            gap: 0.25rem 0.5rem;
            font-size: var(--fs-chart-axis);
            color: #64748b;
            font-weight: 500;
            line-height: 1.3;
            text-align: center;
        }
        .chart-x-labels span { white-space: nowrap; justify-self: center; }
        .chart-x-labels--below {
            margin-top: 0.75rem;
            padding: 0 0.125rem;
        }

        .attendant-list { list-style: none; padding: 0; margin: 0; }
        .attendant-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.625rem 0;
            border-bottom: 1px solid #f1f5f9;
            min-height: 3.25rem;
        }
        .attendant-item:last-child { border-bottom: none; }
        .attendant-info { display: flex; align-items: center; gap: 0.75rem; min-width: 0; flex: 1; }
        .attendant-meta { display: flex; flex-direction: column; gap: 0.125rem; min-width: 0; }
        .attendant-avatar { width: 36px; height: 36px; border-radius: 50%; background: #cbd5e1; min-width: 36px; flex-shrink: 0; }
        .attendant-name { font-size: 0.875rem; font-weight: 600; color: #111827; line-height: 1.3; }
        .attendant-percent {
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            background: #f1f5f9;
            border-radius: 6px;
            font-weight: 600;
            color: #64748b;
            width: fit-content;
        }
        .attendant-right { text-align: right; flex-shrink: 0; }
        .attendant-days { font-size: 0.875rem; font-weight: 600; color: #111827; line-height: 1.2; }
        .attendant-days span { color: #6b7280; font-weight: 500; font-size: 0.8125rem; }

        /* Gender donut */
        .gender-chart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            padding: 0.5rem 0 0;
            min-height: 220px;
        }
        .gender-donut {
            width: min(200px, 55vw);
            height: min(200px, 55vw);
            border-radius: 50%;
            background: conic-gradient(#22c55e 0% 55%, #111827 55% 100%);
            display: grid;
            place-items: center;
            flex-shrink: 0;
            position: relative;
        }
        .gender-donut::after {
            content: '';
            width: 58%;
            height: 58%;
            border-radius: 50%;
            background: white;
            grid-area: 1 / 1;
        }
        .gender-legend {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem 1.5rem;
            width: 100%;
            max-width: 280px;
        }
        .gender-legend li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }
        .gender-swatch { width: 0.75rem; height: 0.75rem; border-radius: 2px; flex-shrink: 0; }
        .gender-swatch--m { background: #22c55e; }
        .gender-swatch--f { background: #111827; }

        /* Radar */
        .radar-wrap {
            min-height: clamp(240px, 32vw, 280px);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.5rem;
            padding: 0.5rem;
        }
        .radar-svg {
            width: min(100%, 280px);
            height: auto;
            max-height: 260px;
        }
        .radar-label {
            font-size: clamp(0.75rem, 2vw, 0.8125rem);
            font-weight: 600;
            color: #475569;
            line-height: 1.2;
        }

        .chart-placeholder-svg {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -0.25rem;
            padding: 0 0.25rem;
        }

        @media (max-width: 1200px) {
            .dashboard-page {
                --hero-overlap: clamp(2.5rem, 6.5vw, 3.75rem);
                --hero-tail: clamp(1.75rem, 4.5vw, 2.75rem);
                --card-pad: clamp(0.875rem, 2vw, 1.25rem);
            }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero-content { flex-direction: column; align-items: stretch; }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero-summary { width: 100%; grid-template-columns: repeat(2, minmax(0, 1fr)); min-width: 0; }
            .dashboard-grid { grid-template-columns: 1fr; }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .modern-stat-card { padding: 0.75rem; padding-right: 1.75rem; min-height: 4.75rem; }
            .stat-icon-circle { width: 40px; height: 40px; min-width: 40px; }
            .stat-number { font-size: 1.375rem; }
            .modern-card { padding: 1rem; }
            .modern-card-header { margin-bottom: 1rem; }
            .modern-card-title { font-size: 0.9375rem; }
            .attendance-range-select { min-width: 128px; }
            .card-menu { font-size: 0.75rem; }
            .chart-line-inner { height: clamp(200px, 25vw, 240px); }
            .chart-h-scroll-slab { min-width: 550px; }
            .chart-x-labels { font-size: 0.75rem; }
        }

        @media (max-width: 768px) {
            .dashboard-page {
                --hero-overlap: clamp(1.75rem, 5vw, 2.5rem);
                --hero-tail: clamp(1.5rem, 5vw, 2.5rem);
                --stat-pad: 0.625rem 0.75rem;
                --dash-gap: 0.75rem;
                --card-pad: 0.75rem;
                --fs-card-title: 0.875rem;
            }
            .hero-content { flex-direction: column; align-items: stretch; }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero-summary { width: 100%; grid-template-columns: repeat(2, minmax(0, 1fr)); min-width: 0; }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem; }
            .modern-stat-card { padding-right: 1.5rem; min-height: 4.5rem; }
            .stat-icon-circle { width: 36px; height: 36px; min-width: 36px; font-size: 1.125rem; }
            .stat-card-main { gap: 0.625rem; }
            .stat-number { font-size: 1.25rem; }
            .stat-label { font-size: 0.6rem; }
            .modern-stat-card .dots { font-size: 0.75rem; top: 0.5rem; right: 0.5rem; }
            .modern-card { padding: 0.75rem; }
            .modern-card-header { margin-bottom: 0.875rem; gap: 0.5rem; }
            .modern-card-title { font-size: 0.875rem; }
            .attendance-range-form { width: 100%; }
            .attendance-range-select { width: 100%; min-width: 0; }
            .card-menu { font-size: 0.7rem; }
            .chart-line-inner { height: clamp(180px, 50vw, 220px); }
            .chart-h-scroll-slab { min-width: 480px; }
            .chart-x-labels { font-size: 0.7rem; gap: 0.25rem 0.375rem; }
            .chart-x-labels--below { margin-top: 0.5rem; }
        }

        @media (max-width: 480px) {
            .modern-card { padding: 0.625rem; }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .modern-card-header { margin-bottom: 0.75rem; }
            .modern-card-title { font-size: 0.8125rem; }
            .attendance-range-form { width: 100%; }
            .attendance-range-select { width: 100%; min-width: 0; }
            .card-menu { font-size: 0.65rem; }
            .chart-line-inner { height: clamp(150px, 45vw, 200px); }
            .chart-h-scroll-slab { min-width: 420px; }
            .chart-x-labels { font-size: 0.625rem; }
            .attendant-meta .attendant-name { font-size: 0.8125rem; }
            .hero-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>

    <div class="stat-grid-modern">
        <div class="modern-stat-card">
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #22c55e;">
                    <i data-lucide="graduation-cap" data-size="24"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $totalStudents) }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card stat-card--accent">
            <div class="stat-card-main">
                <div class="stat-icon-circle accent-bg">
                    <i data-lucide="arrow-right" data-size="22"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $presentToday) }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card">
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #ef4444;">
                    <i data-lucide="frown" data-size="22"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $absentToday) }}</div>
                    <div class="stat-label">Absent Today</div>
                    <!-- details link intentionally removed -->
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card">
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #22c55e;">
                    <i data-lucide="clock" data-size="22"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $lateToday) }}</div>
                    <div class="stat-label">Late Students Today</div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Total Attendance Report</h2>
                <form method="GET" action="{{ url()->current() }}" class="attendance-range-form">
                    <label for="timeframe" class="sr-only">Select time frame</label>
                    <select id="timeframe" name="timeframe" class="attendance-range-select" onchange="this.form.submit()">
                        <option value="days" @selected(request('timeframe', $timeframe ?? 'days') === 'days')>Last 7 days</option>
                        <option value="week" @selected(request('timeframe', $timeframe ?? 'days') === 'week')>Per week</option>
                        <option value="month" @selected(request('timeframe', $timeframe ?? 'days') === 'month')>Per month</option>
                    </select>
                </form>
            </div>
            <!-- Attendance trend for the selected time frame -->
            <div class="chart-placeholder-svg chart-line-wrap">
                <div class="chart-h-scroll-slab">
                <div class="chart-line-inner">
                    @php
                        $trendSeries = collect($trend ?? [])->values();
                        $trendCount = $trendSeries->count();
                        $viewWidth = 1000;
                        $viewHeight = 250;
                        $paddingX = 36;
                        $paddingTop = 18;
                        $paddingBottom = 34;
                        $plotWidth = $viewWidth - ($paddingX * 2);
                        $plotHeight = $viewHeight - $paddingTop - $paddingBottom;
                        $trendMax = max(1, (int) ($trendSeries->max('total') ?? 0));
                        $trendMin = (int) ($trendSeries->min('total') ?? 0);
                        $trendRange = max(1, $trendMax - $trendMin);
                        $trendPoints = [];

                        foreach ($trendSeries as $index => $row) {
                            $x = $trendCount > 1
                                ? $paddingX + ($plotWidth * $index / ($trendCount - 1))
                                : $paddingX + ($plotWidth / 2);

                            $value = (int) ($row['total'] ?? 0);
                            $normalized = $trendRange === 0 ? 0.5 : (($value - $trendMin) / $trendRange);
                            $y = $paddingTop + ($plotHeight * (1 - $normalized));

                            $trendPoints[] = [
                                'x' => $x,
                                'y' => $y,
                                'value' => $value,
                                'label' => $row['label'] ?? '',
                            ];
                        }

                        $trendLinePath = '';
                        $trendAreaPath = '';
                        $highlightPoint = null;
                        if (! empty($trendPoints)) {
                            $trendLinePath = 'M ' . $trendPoints[0]['x'] . ',' . $trendPoints[0]['y'];
                            foreach (array_slice($trendPoints, 1) as $point) {
                                $trendLinePath .= ' L ' . $point['x'] . ',' . $point['y'];
                            }

                            $trendAreaPath = $trendLinePath . ' L ' . $trendPoints[array_key_last($trendPoints)]['x'] . ',' . $viewHeight . ' L ' . $trendPoints[0]['x'] . ',' . $viewHeight . ' Z';
                            $highlightIndex = $trendSeries->search(fn ($row) => (int) ($row['total'] ?? 0) === (int) $trendSeries->max('total'));
                            $highlightPoint = $highlightIndex !== false ? $trendPoints[$highlightIndex] : null;

                            if ($highlightPoint) {
                                $labelWidth = 72;
                                $labelHeight = 28;
                                $tooltipGap = 12;
                                $tooltipX = max(8, min($viewWidth - $labelWidth - 8, $highlightPoint['x'] - ($labelWidth / 2)));
                                $tooltipY = max(8, $highlightPoint['y'] - $labelHeight - $tooltipGap);
                                $highlightPoint['tooltipX'] = $tooltipX;
                                $highlightPoint['tooltipY'] = $tooltipY;
                                $highlightPoint['labelWidth'] = $labelWidth;
                                $highlightPoint['labelHeight'] = $labelHeight;
                            }
                        }
                    @endphp

                    <!-- Grid Lines -->
                    <div style="position: absolute; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                    </div>
                    <!-- Line chart generated from real attendance data -->
                    <svg width="100%" height="100%" viewBox="0 0 1000 250" preserveAspectRatio="none" style="position: absolute; top:0; left:0; z-index: 1; overflow: visible;">
                        <defs>
                            <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="rgba(34,197,94,0.4)" />
                                <stop offset="100%" stop-color="rgba(34,197,94,0)" />
                            </linearGradient>
                        </defs>
                        @if(!empty($trendPoints))
                            <path d="{{ $trendAreaPath }}" fill="url(#lineGrad)" />
                            <path d="{{ $trendLinePath }}" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            @foreach($trendPoints as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                            @endforeach
                            @if($highlightPoint)
                                <rect x="{{ $highlightPoint['x'] - 15 }}" y="{{ $highlightPoint['y'] }}" width="30" height="{{ $viewHeight - $highlightPoint['y'] }}" rx="4" fill="#22c55e" opacity="0.14" />
                                <rect x="{{ $highlightPoint['tooltipX'] }}" y="{{ $highlightPoint['tooltipY'] }}" width="{{ $highlightPoint['labelWidth'] }}" height="{{ $highlightPoint['labelHeight'] }}" rx="8" fill="#1e293b" />
                                <polygon points="{{ $highlightPoint['x'] - 5 }},{{ $highlightPoint['tooltipY'] + $highlightPoint['labelHeight'] }} {{ $highlightPoint['x'] + 5 }},{{ $highlightPoint['tooltipY'] + $highlightPoint['labelHeight'] }} {{ $highlightPoint['x'] }},{{ $highlightPoint['tooltipY'] + $highlightPoint['labelHeight'] + 8 }}" fill="#1e293b" />
                                <text x="{{ $highlightPoint['tooltipX'] + ($highlightPoint['labelWidth'] / 2) }}" y="{{ $highlightPoint['tooltipY'] + 19 }}" text-anchor="middle" fill="#ffffff" font-size="14" font-weight="600">{{ $highlightPoint['value'] }}</text>
                            @endif
                        @endif
                    </svg>
                    
                </div>
                <div
                    class="chart-x-labels chart-x-labels--below"
                    @php
                        $trendLabels = collect($trend ?? [])->pluck('label')->values();
                        $trendCount = $trendLabels->count();
                        $timeframe = request('timeframe', 'days');
                    @endphp
                    style="grid-template-columns: repeat({{ max(1, $trendCount) }}, minmax(0, 1fr));"
                >
                    @if($timeframe === 'month')
                        @foreach($trendLabels as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    @else
                        @foreach($trendLabels as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    @endif
                </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Recent activity</h2>
            </div>

            @if(($recentLogs ?? collect())->isEmpty())
                <div class="empty-state" style="padding:2rem 1.25rem;">
                    <div class="icon" aria-hidden="true">
                        <i data-lucide="file-text" data-size="34"></i>
                    </div>
                    <h3>No recent attendance yet</h3>
                    <p>Once you start marking attendance, activity will show up here.</p>
                </div>
            @else
                <ul class="attendant-list">
                    @foreach($recentLogs as $log)
                        @php
                            $badge = $log->statusBadge();
                        @endphp
                        <li class="attendant-item" style="padding:0.75rem 0;">
                            <div class="attendant-info">
                                <div class="attendant-avatar" style="display:flex;align-items:center;justify-content:center;background:#e2e8f0;color:#0f172a;font-weight:700;">
                                    {{ strtoupper(substr($log->student?->first_name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="attendant-meta">
                                    <span class="attendant-name">{{ $log->student?->full_name ?? 'Student' }}</span>
                                    <span style="font-size:0.8125rem;color:var(--text-muted);">
                                        {{ $log->date->format('M j, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="attendant-right" style="display:flex;align-items:center;gap:0.5rem;">
                                <span class="badge badge-{{ $badge['color'] }}">{{ $badge['label'] }}</span>
                                <span style="font-size:0.75rem;color:var(--text-muted);white-space:nowrap;">
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    
    <div class="dashboard-grid-bottom">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Students by section</h2>
            </div>
            @if(($sectionCounts ?? collect())->isEmpty())
                <div class="empty-state" style="padding:2rem 1.25rem;">
                    <div class="icon" aria-hidden="true">
                        <i data-lucide="tag" data-size="34"></i>
                    </div>
                    <h3>No students yet</h3>
                    <p>Add students to see section breakdown.</p>
                </div>
            @else
                <ul class="attendant-list">
                    @foreach($sectionCounts as $row)
                        <li class="attendant-item" style="padding:0.65rem 0;">
                            <div class="attendant-info">
                                <div class="attendant-avatar" style="display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#0f172a;font-weight:700;">
                                    {{ strtoupper(substr($row->section ?? 'S', 0, 1)) }}
                                </div>
                                <div class="attendant-meta">
                                    <span class="attendant-name">{{ $row->section ?? '—' }}</span>
                                    <span style="font-size:0.8125rem;color:var(--text-muted);">Section</span>
                                </div>
                            </div>
                            <div class="attendant-right attendant-days">{{ $row->total }} <span>students</span></div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Top attendants</h2>
            </div>
            @if(($topAttendants ?? collect())->isEmpty())
                <div class="empty-state" style="padding:2rem 1.25rem;">
                    <div class="icon" aria-hidden="true">
                        <i data-lucide="award" data-size="34"></i>
                    </div>
                    <h3>No attendance records yet</h3>
                    <p>Mark attendance to calculate top attendants.</p>
                </div>
            @else
                <ul class="attendant-list">
                    @foreach($topAttendants as $student)
                        <li class="attendant-item">
                            <div class="attendant-info">
                                <div class="attendant-avatar" style="display:flex;align-items:center;justify-content:center;background:#e2e8f0;color:#0f172a;font-weight:700;">
                                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="attendant-meta">
                                    <span class="attendant-name">{{ $student->full_name }}</span>
                                    <span class="attendant-percent">{{ number_format($student->period_pct ?? 0, 1) }}%</span>
                                </div>
                            </div>
                            <div class="attendant-right attendant-days">{{ (int) ($student->period_present ?? 0) }} <span>/ {{ (int) ($student->period_total ?? 0) }}</span></div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Weekly Absent</h2>
            </div>
            @if(empty($weeklyData))
                <div class="empty-state" style="padding:2rem 1.25rem;">
                    <div class="icon" aria-hidden="true">
                        <i data-lucide="calendar" data-size="34"></i>
                    </div>
                    <h3>No weekly data</h3>
                    <p>Mark attendance to populate the weekly summary.</p>
                </div>
            @else
                <ul class="attendant-list">
                    @foreach($weeklyData as $day)
                        <li class="attendant-item" style="padding:0.65rem 0;">
                            <div class="attendant-info">
                                <div class="attendant-avatar" style="display:flex;align-items:center;justify-content:center;background:#fef2f2;color:#991b1b;font-weight:800;">
                                    {{ $day['day'] }}
                                </div>
                                <div class="attendant-meta">
                                    <span class="attendant-name">{{ $day['formatted_date'] ?? \Carbon\Carbon::parse($day['date'])->format('M j') }}</span>
                                    <span style="font-size:0.8125rem;color:var(--text-muted);">Absent</span>
                                </div>
                            </div>
                            <div class="attendant-right attendant-days">{{ (int) $day['absent'] }} <span>students</span></div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    </div>
</x-app-layout>

