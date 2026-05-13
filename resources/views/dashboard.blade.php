<x-app-layout>
    <x-slot name="title">Overview</x-slot>

    <div class="page-content dashboard-page">
    {{-- Dark Hero Header --}}
    <div class="hero-header">
        <div class="hero-content">
            <div class="hero-user-info">
                <div class="hero-avatar" style="background: url('https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=cbd5e1&color=1e293b') center/cover;"></div>
                <div class="hero-greeting">
                    <h1 class="hero-title">Hello {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                    <p class="hero-subtitle">We hope you're having a great day.</p>
                </div>
            </div>
            <div class="hero-actions">
                <select class="hero-select" aria-label="Filter by class">
                    <option value="all">All Classes</option>
                </select>
                <div class="hero-select-wrap">
                    <select class="hero-select hero-select--calendar" aria-label="Date range">
                        <option value="30">Last 30 days</option>
                    </select>
                    <span class="hero-select-icon" aria-hidden="true">📅</span>
                </div>
                <button type="button" class="btn btn-primary hero-filter-btn">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <style>
        .dashboard-page {
            --dash-gap: var(--section-gap);
            --hero-overlap: clamp(3.25rem, 8vw, 5rem);
            --hero-tail: clamp(2rem, 5vw, 3.25rem);
            --stat-pad: clamp(1rem, 2vw, 1.375rem) clamp(1rem, 2.5vw, 1.5rem);
            --card-pad: clamp(1rem, 2.5vw, 1.5rem);
            --fs-card-title: clamp(0.9375rem, 1.1vw, 1rem);
            --fs-chart-axis: clamp(0.8125rem, 1.5vw, 0.875rem);
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
            background: var(--header-bg);
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            box-sizing: border-box;
            padding: clamp(1rem, 2.5vw, 1.5rem) 0 calc(var(--hero-overlap) + var(--hero-tail));
            position: relative;
            border-radius: 0;
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
        .hero-user-info { display: flex; align-items: center; gap: clamp(0.75rem, 2vw, 1rem); min-width: min(100%, 240px); }
        .hero-greeting { min-width: 0; }
        .hero-avatar { width: clamp(44px, 4vw, 52px); height: clamp(44px, 4vw, 52px); border-radius: 50%; min-width: clamp(44px, 4vw, 52px); flex-shrink: 0; }
        .hero-title {
            color: white;
            font-size: clamp(1.125rem, 2.4vw, 1.375rem);
            font-weight: 600;
            margin: 0 0 0.25rem;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }
        .hero-subtitle { color: #d1d5db; font-size: clamp(0.875rem, 1.6vw, 0.9375rem); line-height: 1.45; margin: 0; }
        .hero-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .hero-select-wrap { position: relative; display: inline-flex; align-items: center; }
        .hero-select-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        .hero-select {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.5625rem 2.25rem 0.5625rem 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem;
            appearance: none;
            outline: none;
            cursor: pointer;
            font-family: inherit;
            min-height: 2.625rem;
        }
        .hero-select--calendar { padding-right: 2.5rem; }
        .hero-filter-btn { padding: 0.5625rem 1.125rem; font-weight: 600; font-size: 0.875rem; white-space: nowrap; min-height: 2.625rem; }

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
            background: white;
            border-radius: 12px;
            padding: var(--stat-pad);
            padding-right: 2.125rem;
            box-shadow: var(--shadow-card);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            border: 1px solid #e5e7eb;
            min-height: 5.5rem;
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
            box-shadow: var(--shadow-card);
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
            background: white;
            border-radius: 12px;
            padding: var(--card-pad);
            box-shadow: var(--shadow-card);
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
            margin-bottom: clamp(1rem, 2vw, 1.25rem);
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
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.25rem 0.5rem;
            font-size: var(--fs-chart-axis);
            color: #64748b;
            font-weight: 500;
            line-height: 1.3;
        }
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
            }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 992px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .dashboard-grid-bottom { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .dashboard-page {
                --hero-overlap: clamp(1.75rem, 5vw, 2.5rem);
                --hero-tail: clamp(1.5rem, 5vw, 2.5rem);
            }
            .hero-content { flex-direction: column; align-items: stretch; }
            .hero-actions { width: 100%; margin-top: 0.75rem; }
            .hero-actions .hero-select-wrap,
            .hero-actions .hero-select:not(.hero-select--calendar) { flex: 1; min-width: 0; }
            .hero-actions .hero-select { width: 100%; }
            .hero-filter-btn { flex: 1; justify-content: center; }
            .stat-grid-modern { grid-template-columns: 1fr; gap: var(--dash-gap); }
            .chart-h-scroll-slab { min-width: 520px; }
        }

        @media (max-width: 480px) {
            .hero-filter-btn { width: 100%; }
            .attendant-meta .attendant-name { font-size: 0.8125rem; }
        }
    </style>

    <div class="stat-grid-modern">
        <div class="modern-stat-card">
            <div class="dots">...</div>
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #22c55e;">
                    👨‍🎓
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $totalStudents) }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card stat-card--accent">
            <div class="dots">...</div>
            <div class="stat-card-main">
                <div class="stat-icon-circle accent-bg">
                    ➜
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $presentToday) }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card">
            <div class="dots">...</div>
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #ef4444;">
                    ☹️
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ sprintf('%02d', $absentToday) }}</div>
                    <div class="stat-label">Absent Today</div>
                    <div class="stat-card-meta">
                        <a href="#" class="stat-card-link">View details</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modern-stat-card">
            <div class="dots">...</div>
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #22c55e;">
                    ⏱
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
                <div class="card-menu" role="button" tabindex="0" aria-label="Chart options">…</div>
            </div>
            <!-- Mock Line Chart -->
            <div class="chart-placeholder-svg chart-line-wrap">
                <div class="chart-h-scroll-slab">
                <div class="chart-line-inner">
                    <!-- Grid Lines -->
                    <div style="position: absolute; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                        <div style="border-top: 1px dashed #e2e8f0;"></div>
                    </div>
                    <!-- Line -->
                    <svg width="100%" height="100%" viewBox="0 0 1000 250" preserveAspectRatio="none" style="position: absolute; top:0; left:0; z-index: 1; overflow: visible;">
                        <defs>
                            <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="rgba(34,197,94,0.4)" />
                                <stop offset="100%" stop-color="rgba(34,197,94,0)" />
                            </linearGradient>
                        </defs>
                        <path d="M 0,150 Q 100,100 200,100 T 400,150 T 600,100 T 800,50 T 1000,120 L 1000,250 L 0,250 Z" fill="url(#lineGrad)" />
                        <path d="M 0,150 Q 100,100 200,100 T 400,150 T 600,100 T 800,50 T 1000,120" fill="none" stroke="#22c55e" stroke-width="3" />
                        <!-- Points -->
                        <circle cx="100" cy="113" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="200" cy="100" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="300" cy="150" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="400" cy="120" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="500" cy="110" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="600" cy="100" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="700" cy="115" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="800" cy="50" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                        <circle cx="900" cy="90" r="6" fill="white" stroke="#22c55e" stroke-width="3" />
                    </svg>
                    
                    <!-- Highlighted Value -->
                    <div style="position: absolute; left: 60%; top: 35%; transform: translate(-50%, -100%); z-index: 2;">
                        <div style="background: #1e293b; color: white; padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; margin-bottom: 8px; position: relative;">
                            45
                            <div style="position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%) rotate(45deg); width: 8px; height: 8px; background: #1e293b;"></div>
                        </div>
                    </div>
                    <!-- Highlight Bar -->
                    <div style="position: absolute; left: 60%; top: 35%; bottom: 0; width: 30px; transform: translateX(-50%); background: linear-gradient(to bottom, rgba(34,197,94,0.3), rgba(34,197,94,0.05)); z-index: 0; border-radius: 4px 4px 0 0;"></div>
                </div>
                <div class="chart-x-labels chart-x-labels--below">
                    <span>Jan 1</span><span>Jan 4</span><span>Jan 7</span><span>Jan 10</span>
                    <span>Jan 13</span><span>Jan 16</span><span>Jan 19</span><span>Jan 22</span>
                    <span>Jan 25</span><span>Jan 28</span>
                </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Students by Class</h2>
                <div class="card-menu" role="button" tabindex="0" aria-label="Chart options">…</div>
            </div>

            <div class="chart-placeholder">
                <div class="chart-bar" style="height: 60%;"><span class="chart-label">I</span></div>
                <div class="chart-bar" style="height: 70%;"><span class="chart-label">II</span></div>
                <div class="chart-bar" style="height: 55%;"><span class="chart-label">III</span></div>
                <div class="chart-bar" style="height: 65%;"><span class="chart-label">IV</span></div>
                <div class="chart-bar" style="height: 75%;"><span class="chart-label">V</span></div>
                
                <div class="chart-bar active" style="height: 85%;">
                    <div style="position: absolute; top: -38px; left: 50%; transform: translateX(-50%); background: #1e293b; color: white; padding: 0.375rem 0.625rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600;">
                        17
                        <div style="position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%) rotate(45deg); width: 8px; height: 8px; background: #1e293b;"></div>
                    </div>
                    <span class="chart-label">VI</span>
                </div>
                
                <div class="chart-bar" style="height: 60%;"><span class="chart-label">VII</span></div>
                <div class="chart-bar" style="height: 80%;"><span class="chart-label">VIII</span></div>
                <div class="chart-bar" style="height: 50%;"><span class="chart-label">IX</span></div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid-bottom">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Students by Gender</h2>
                <div class="card-menu" role="button" tabindex="0" aria-label="Chart options">…</div>
            </div>
            <div class="gender-chart">
                <div class="gender-donut" role="img" aria-label="Student gender split: 55 percent male, 45 percent female"></div>
                <ul class="gender-legend">
                    <li><span class="gender-swatch gender-swatch--m" aria-hidden="true"></span> Male <strong>55%</strong></li>
                    <li><span class="gender-swatch gender-swatch--f" aria-hidden="true"></span> Female <strong>45%</strong></li>
                </ul>
            </div>
        </div>
        
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Top 6 Attendant</h2>
                <div class="card-menu" role="button" tabindex="0" aria-label="List options">…</div>
            </div>
            <ul class="attendant-list">
                @php
                    $mockUsers = [
                        ['name' => 'Brooklyn Simmons', 'pct' => '100%', 'days' => '30'],
                        ['name' => 'Cody Fisher', 'pct' => '100%', 'days' => '30'],
                        ['name' => 'Marvin McKinney', 'pct' => '98.7%', 'days' => '29'],
                        ['name' => 'Esther Howard', 'pct' => '97.2%', 'days' => '28'],
                        ['name' => 'Jenny Wilson', 'pct' => '96.5%', 'days' => '28'],
                        ['name' => 'Robert Fox', 'pct' => '95.8%', 'days' => '27'],
                    ];
                @endphp
                @foreach($mockUsers as $idx => $user)
                    <li class="attendant-item">
                        <div class="attendant-info">
                            <img class="attendant-avatar" alt="" src="https://ui-avatars.com/api/?name={{ urlencode($user['name']) }}&background=random" />
                            <div class="attendant-meta">
                                <span class="attendant-name">{{ $user['name'] }}</span>
                                <span class="attendant-percent">{{ $user['pct'] }}</span>
                            </div>
                        </div>
                        <div class="attendant-right attendant-days">{{ $user['days'] }} <span>days</span></div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Weekly Absent</h2>
                <div class="card-menu" role="button" tabindex="0" aria-label="Chart options">…</div>
            </div>
            <div class="radar-wrap">
                <svg class="radar-svg" viewBox="0 0 120 120" aria-hidden="true">
                    <g transform="translate(60,60)">
                        <polygon points="0,-48 45.6,-14.1 28.2,38.7 -28.2,38.7 -45.6,-14.1" fill="none" stroke="#e2e8f0" stroke-width="1" />
                        <polygon points="0,-36 34.2,-10.6 21.2,29 -21.2,29 -34.2,-10.6" fill="none" stroke="#e2e8f0" stroke-width="1" />
                        <polygon points="0,-24 22.8,-7.1 14.1,19.3 -14.1,19.3 -22.8,-7.1" fill="none" stroke="#e2e8f0" stroke-width="1" />
                        <line x1="0" y1="0" x2="0" y2="-48" stroke="#e2e8f0" stroke-width="1" />
                        <line x1="0" y1="0" x2="45.6" y2="-14.1" stroke="#e2e8f0" stroke-width="1" />
                        <line x1="0" y1="0" x2="28.2" y2="38.7" stroke="#e2e8f0" stroke-width="1" />
                        <line x1="0" y1="0" x2="-28.2" y2="38.7" stroke="#e2e8f0" stroke-width="1" />
                        <line x1="0" y1="0" x2="-45.6" y2="-14.1" stroke="#e2e8f0" stroke-width="1" />
                        <polygon points="0,-28 38,-12 22,36 -26,34 -34,-8" fill="rgba(34,197,94,0.28)" stroke="#22c55e" stroke-width="1.5" />
                    </g>
                </svg>
                <div class="radar-label" style="position: absolute; top: 4%; left: 50%; transform: translateX(-50%); text-align: center;">Mon<br><span style="font-weight:500;color:#64748b;">08</span></div>
                <div class="radar-label" style="position: absolute; top: 32%; right: 4%;">Tue</div>
                <div class="radar-label" style="position: absolute; bottom: 6%; right: 18%;">Wed</div>
                <div class="radar-label" style="position: absolute; bottom: 6%; left: 18%;">Thu</div>
                <div class="radar-label" style="position: absolute; top: 32%; left: 4%;">Fri</div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>

