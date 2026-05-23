<x-app-layout>
    <x-slot name="title">Overview</x-slot>

    <div class="page-content dashboard-page" x-data="dashboardData()" x-init="initDashboard()">
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
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .dashboard-page.loaded {
            opacity: 1;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            inset: 70px 0 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .loading-overlay.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 3px solid #e5e7eb;
            border-top-color: #22c55e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Skeleton Loading States */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .skeleton-text {
            height: 1rem;
            margin-bottom: 0.5rem;
        }

        .skeleton-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }

        .skeleton-number {
            height: 1.75rem;
            width: 60px;
        }

        /* Smooth Transitions */
        .modern-card,
        .modern-stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .modern-card:hover,
        .modern-stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number,
        .attendant-name,
        .attendant-days {
            transition: color 0.2s ease-in-out;
        }

        /* Chart Animation */
        .chart-bar {
            transition: height 0.5s ease-in-out, background-color 0.3s ease-in-out;
        }

        .chart-line-inner svg path,
        .chart-line-inner svg circle {
            transition: d 0.5s ease-in-out, cx 0.5s ease-in-out, cy 0.5s ease-in-out;
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

        [x-cloak] { display: none !important; }

        /* Segmented Control Styles */
        .segmented-btn {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            border-radius: 9999px !important;
            border: none !important;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            outline: none !important;
        }
        .segmented-badge {
            font-size: 0.7rem !important;
            padding: 0.05rem 0.35rem !important;
            border-radius: 9999px !important;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.25rem;
            height: 1.25rem;
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
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

        /* Loading Spinner */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f1f5f9;
            border-top: 3px solid #22c55e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            .hero-summary { width: 100%; min-width: 0; }
            .dashboard-grid { grid-template-columns: 1fr; }
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
                --hero-overlap: clamp(1.75rem, 5vw, 2.25rem);
                --hero-tail: clamp(1.25rem, 5vw, 2.25rem);
                --stat-pad: 0.5rem 0.625rem;
                --dash-gap: 0.625rem;
                --card-pad: 0.625rem;
                --fs-card-title: 0.8125rem;
            }
            .hero-header { padding-top: 0.75rem; }
            .hero-user-info { gap: 0.75rem; }
            .hero-avatar { width: 40px; height: 40px; min-width: 40px; }
            .hero-eyebrow { font-size: 0.6rem; margin-bottom: 0.2rem; }
            .hero-title { font-size: 1.125rem; }
            .hero-subtitle { font-size: 0.8125rem; }
            .hero-summary { gap: 0.5rem; }
            .hero-summary-card { padding: 0.625rem 0.75rem; border-radius: 12px; }
            .hero-summary-label { font-size: 0.6rem; }
            .hero-summary-card strong { font-size: 0.875rem; }

            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.625rem; }
            .modern-stat-card { padding-right: 1.25rem; min-height: 4rem; border-radius: 12px; }
            .stat-icon-circle { width: 32px; height: 32px; min-width: 32px; font-size: 1rem; }
            .stat-card-main { gap: 0.5rem; }
            .stat-number { font-size: 1.125rem; }
            .stat-label { font-size: 0.55rem; }
            .modern-stat-card .dots { font-size: 0.7rem; top: 0.375rem; right: 0.375rem; }

            .dashboard-grid-bottom { grid-template-columns: 1fr; }
            .modern-card { padding: 0.625rem; border-radius: 12px; }
            .modern-card-header { margin-bottom: 0.75rem; gap: 0.375rem; }
            .modern-card-title { font-size: 0.8125rem; }
            .attendance-range-select { padding: 0.45rem 0.65rem; font-size: 0.75rem; }
            
            .attendant-item { padding: 0.5rem 0; min-height: 2.75rem; }
            .attendant-avatar { width: 32px; height: 32px; min-width: 32px; font-size: 0.75rem; }
            .attendant-name { font-size: 0.8125rem; }
            .attendant-meta span:last-child { font-size: 0.75rem !important; }
            .attendant-days { font-size: 0.8125rem; }
            .attendant-days span { font-size: 0.75rem; }
            .attendant-right .badge { font-size: 0.65rem; padding: 0.15rem 0.4rem; }
            .btn-sm { height: 28px !important; padding: 0.2rem 0.5rem !important; font-size: 0.75rem !important; }
            
            .chart-line-inner { height: 180px; }
            .chart-h-scroll-slab { min-width: 450px; }
            .chart-x-labels { font-size: 0.65rem; }
        }

        @media (max-width: 480px) {
            .dashboard-page { --page-inline-pad: 0.75rem; }
            .hero-header { padding-bottom: calc(var(--hero-overlap) + 1.5rem); }
            .hero-subtitle { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
            .hero-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; }
            .hero-summary-card { padding: 0.5rem 0.625rem; }
            .hero-summary-card strong { font-size: 0.8125rem; }
            .hero-summary-label { font-size: 0.55rem; }
            .stat-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; }
            .modern-stat-card { 
                padding: 0.75rem 0.5rem !important; 
                min-height: 5.5rem; 
                flex-direction: column; 
                align-items: center; 
                text-align: center;
                justify-content: center;
            }
            .stat-card-main { 
                flex-direction: column; 
                gap: 0.35rem; 
                align-items: center;
            }
            .stat-icon-circle { width: 28px; height: 28px; min-width: 28px; font-size: 0.875rem; }
            .stat-icon-circle i { width: 16px !important; height: 16px !important; }
            .stat-number { font-size: 1rem; }
            .stat-label { font-size: 0.5rem; letter-spacing: 0.02em; }
            
            /* Modal responsiveness */
            .bulk-modal-container { padding: 0.5rem; }
            .bulk-modal-container > div:last-child { border-radius: 12px; }
            .bulk-modal-container h3 { font-size: 1rem !important; }
            .bulk-modal-container p { font-size: 0.75rem !important; }
            .segmented-btn { padding: 0.25rem 0.5rem !important; font-size: 0.7rem !important; gap: 0.25rem !important; }
            .segmented-badge { min-width: 1rem !important; height: 1rem !important; font-size: 0.65rem !important; }
        }
    </style>

    <!-- Loading Overlay -->
    <div class="loading-overlay" :class="{ 'hidden': !loading }" x-show="loading" x-transition.opacity.duration.300ms>
        <div class="loading-spinner"></div>
    </div>

    <div class="stat-grid-modern">
        <div class="modern-stat-card">
            <div class="stat-card-main">
                <div class="stat-icon-circle white-border" style="color: #22c55e;">
                    <i data-lucide="graduation-cap" data-size="24"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" x-text="stats.totalStudents">{{ sprintf('%02d', $totalStudents) }}</div>
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
                    <div class="stat-number" x-text="stats.presentToday">{{ sprintf('%02d', $presentToday) }}</div>
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
                    <div class="stat-number" x-text="stats.absentToday">{{ sprintf('%02d', $absentToday) }}</div>
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
                    <div class="stat-number" x-text="stats.lateToday">{{ sprintf('%02d', $lateToday) }}</div>
                    <div class="stat-label">Late Students Today</div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2 class="modern-card-title">Total Attendance Report</h2>
                <div class="attendance-range-form">
                    <label for="timeframe" class="sr-only">Select time frame</label>
                    <select id="timeframe" name="timeframe" class="attendance-range-select" x-model="timeframe" @change="updateTimeframe()">
                        <option value="days">Last 7 days</option>
                        <option value="month">Per month</option>
                    </select>
                </div>
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
                        $plotWidth = $viewWidth - $paddingX * 2;
                        $plotHeight = $viewHeight - $paddingTop - $paddingBottom;
                        $trendMax = max(1, (int) $trendSeries->max('total') ?? 0);
                        $trendMin = (int) $trendSeries->min('total') ?? 0;
                        $trendRange = max(1, $trendMax - $trendMin);
                        $trendPoints = [];

                        foreach ($trendSeries as $index => $row) {
                            $x = $trendCount > 1
                                ? $paddingX + $plotWidth * $index / ($trendCount - 1)
                                : $paddingX + $plotWidth / 2;

                            $value = (int) ($row['total'] ?? 0);
                            $normalized = $trendRange === 0 ? 0.5 : ($value - $trendMin) / $trendRange;
                            $y = $paddingTop + $plotHeight * (1 - $normalized);

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
                            $trendLinePath = "M {$trendPoints[0]['x']},{$trendPoints[0]['y']}";
                            foreach (array_slice($trendPoints, 1) as $point) {
                                $trendLinePath .= " L {$point['x']},{$point['y']}";
                            }

                            $trendAreaPath = "{$trendLinePath} L {$trendPoints[array_key_last($trendPoints)]['x']},{$viewHeight} L {$trendPoints[0]['x']},{$viewHeight} Z";
                            $highlightIndex = $trendSeries->search(fn ($row) => (int) ($row['total'] ?? 0) === (int) $trendSeries->max('total'));
                            $highlightPoint = $highlightIndex !== false ? $trendPoints[$highlightIndex] : null;

                            if ($highlightPoint) {
                                $labelWidth = 72;
                                $labelHeight = 28;
                                $tooltipGap = 12;
                                $tooltipX = max(8, min($viewWidth - $labelWidth - 8, $highlightPoint['x'] - $labelWidth / 2));
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
                <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; text-align: center;">
                    <div class="icon" aria-hidden="true" style="margin-bottom: 1rem;">
                        <i data-lucide="file-text" data-size="64"></i>
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
                <h2 class="modern-card-title">Class Overview</h2>
            </div>
            @if(($sectionCounts ?? collect())->isEmpty())
                <div class="empty-state" style="padding:2rem 1.25rem; text-align: center;">
                    <div class="icon" aria-hidden="true" style="display: flex; justify-content: center; margin-bottom: 1rem;">
                        <i data-lucide="tag" data-size="48"></i>
                    </div>
                    <h3>No classes yet</h3>
                    <p>Add students with course and block to see class breakdown.</p>
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
                                    <span class="attendant-name">{{ $row->display_name ?? $row->section ?? '—' }}</span>
                                    <span style="font-size:0.8125rem;color:var(--text-muted);">{{ $row->class_subtitle ?? $row->section ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="attendant-right" style="display:flex;align-items:center;gap:0.5rem;">
                                <span class="attendant-days">{{ $row->total }} <span>students</span></span>
                                <button type="button" class="btn btn-secondary btn-sm" style="border-radius: 6px; font-weight: 500; display: inline-flex; align-items: center; gap: 0.35rem; height: 32px; padding: 0.25rem 0.65rem;" x-on:click="openClassModal(@js($row->id), @js($row->display_name), @js($row->class_subtitle))">
                                    <i data-lucide="users" data-size="14"></i>
                                    View
                                </button>
                            </div>
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
                <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; text-align: center;">
                    <div class="icon" aria-hidden="true" style="margin-bottom: 1rem;">
                        <i data-lucide="award" data-size="64"></i>
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
    </div>

    <!-- Class Students Modal -->
    <div x-show="classModal.show" 
         class="bulk-modal-container"
         x-cloak
         x-on:keydown.escape.window="classModal.show = false"
         style="display: none;">
        <!-- Backdrop -->
        <div x-show="classModal.show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="classModal.show = false"
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); transition: opacity 0.3s ease; z-index: 9999;"></div>
        
        <!-- Modal Content -->
        <div x-show="classModal.show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="position: relative; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; width: 100%; max-width: 600px; border: 1px solid #e2e8f0; z-index: 10000; box-sizing: border-box;">
            
            <!-- Modal Header -->
            <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; font-family: system-ui, -apple-system, sans-serif;" x-text="classModal.displayName + ' - Students'"></h3>
                    <p style="font-size: 0.8125rem; color: #64748b; margin: 0.25rem 0 0 0;" x-text="classModal.subtitle"></p>
                </div>
                <button type="button" @click="classModal.show = false" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.color='#64748b';this.style.background='#f1f5f9'" onmouseout="this.style.color='#94a3b8';this.style.background='none'">
                    <i data-lucide="x" data-size="20"></i>
                </button>
            </div>
            
            <!-- Filters -->
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                <div style="display: flex; background: #f1f5f9; padding: 0.25rem; border-radius: 9999px; gap: 0.25rem; width: fit-content; border: 1px solid #e2e8f0; margin: 0 auto; align-items: center;">
                    <button type="button" @click="classModal.filter = 'all'" class="segmented-btn"
                            :style="classModal.filter === 'all' ? 'background: #ffffff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);' : 'background: transparent; color: #64748b;'">
                        All <span class="segmented-badge" :style="classModal.filter === 'all' ? 'background: #f1f5f9; color: #0f172a;' : 'background: #e2e8f0; color: #64748b;'" x-text="counts.all"></span>
                    </button>
                    <button type="button" @click="classModal.filter = 'present'" class="segmented-btn"
                            :style="classModal.filter === 'present' ? 'background: #dcfce7; color: #15803d; box-shadow: 0 1px 2px rgba(34,197,94,0.15);' : 'background: transparent; color: #64748b;'">
                        Present <span class="segmented-badge" :style="classModal.filter === 'present' ? 'background: #bbf7d0; color: #15803d;' : 'background: #e2e8f0; color: #64748b;'" x-text="counts.present"></span>
                    </button>
                    <button type="button" @click="classModal.filter = 'late'" class="segmented-btn"
                            :style="classModal.filter === 'late' ? 'background: #fef3c7; color: #b45309; box-shadow: 0 1px 2px rgba(245,158,11,0.15);' : 'background: transparent; color: #64748b;'">
                        Late <span class="segmented-badge" :style="classModal.filter === 'late' ? 'background: #fde68a; color: #b45309;' : 'background: #e2e8f0; color: #64748b;'" x-text="counts.late"></span>
                    </button>
                    <button type="button" @click="classModal.filter = 'absent'" class="segmented-btn"
                            :style="classModal.filter === 'absent' ? 'background: #fee2e2; color: #b91c1c; box-shadow: 0 1px 2px rgba(239,68,68,0.15);' : 'background: transparent; color: #64748b;'">
                        Absent <span class="segmented-badge" :style="classModal.filter === 'absent' ? 'background: #fecaca; color: #b91c1c;' : 'background: #e2e8f0; color: #64748b;'" x-text="counts.absent"></span>
                    </button>
                </div>
            </div>
            
            <!-- Student List -->
            <div style="padding: 0; overflow-y: auto; max-height: 400px; background: #ffffff;">
                <template x-if="classModal.loading">
                    <div style="display: flex; align-items: center; justify-content: center; padding: 3rem;">
                        <div class="loading-spinner" style="border-top-color: #22c55e;"></div>
                    </div>
                </template>
                
                <template x-if="!classModal.loading && filteredStudents.length === 0">
                    <div style="text-align: center; padding: 4rem 2rem; color: #94a3b8;">
                        <div style="background: #f8fafc; color: #cbd5e1; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px dashed #e2e8f0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <h4 style="color: #475569; font-weight: 700; margin: 0 0 0.5rem 0; font-size: 1rem;" x-text="classModal.filter === 'all' ? 'No students enrolled' : 'No ' + classModal.filter + ' students'"></h4>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0; font-weight: 500;" x-text="classModal.filter === 'all' ? 'There are no students assigned to this section yet.' : 'There are no students marked as ' + classModal.filter + ' for today in this class.'"></p>
                    </div>
                </template>
                
                <template x-if="!classModal.loading && filteredStudents.length > 0">
                    <div style="padding: 0 1.5rem;">
                        <ul style="list-style: none; padding: 0; margin: 0; border-top: 1px solid #f1f5f9;">
                            <template x-for="student in filteredStudents" :key="student.id">
                                <li style="padding: 1rem 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; color: #0f172a; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; border: 1px solid #e2e8f0;"
                                             x-text="student.first_name ? student.first_name.charAt(0).toUpperCase() : 'S'"></div>
                                        <div>
                                            <p style="font-weight: 600; color: #0f172a; margin: 0; font-size: 0.9375rem;" x-text="student.first_name + ' ' + student.last_name"></p>
                                            <p style="font-size: 0.75rem; color: #64748b; margin: 0.125rem 0 0 0; font-family: monospace;" x-text="student.student_id_number"></p>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <template x-if="student.today_status === 'present'">
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; background: #f0fdf4; color: #16a34a; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: 1px solid #dcfce7;">
                                                <span style="width: 6px; height: 6px; background: #16a34a; border-radius: 50%;"></span>
                                                Present
                                            </span>
                                        </template>
                                        <template x-if="student.today_status === 'absent'">
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; background: #fef2f2; color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fee2e2;">
                                                <span style="width: 6px; height: 6px; background: #dc2626; border-radius: 50%;"></span>
                                                Absent
                                            </span>
                                        </template>
                                        <template x-if="student.today_status === 'late'">
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; background: #fffbeb; color: #ca8a04; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fef3c7;">
                                                <span style="width: 6px; height: 6px; background: #ca8a04; border-radius: 50%;"></span>
                                                Late
                                            </span>
                                        </template>
                                        <template x-if="!student.today_status">
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; background: #f8fafc; color: #64748b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">
                                                Not Marked
                                            </span>
                                        </template>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>
            
            <!-- Modal Footer -->
            <div style="padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: flex-end;">
                <button type="button" @click="classModal.show = false" class="btn btn-secondary" style="border-radius: 10px; padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 600;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div 
        id="dashboard-data" 
        style="display: none;"
        data-timeframe="{{ request('timeframe', $timeframe ?? 'days') }}"
        data-total-students="{{ $totalStudents }}"
        data-present-today="{{ $presentToday }}"
        data-absent-today="{{ $absentToday }}"
        data-late-today="{{ $lateToday }}"
        data-dashboard-route="{{ route('dashboard') }}"
        data-class-students-route="{{ route('dashboard.class-students') }}"
    ></div>

    <script>
        function dashboardData() {
            const dataEl = document.getElementById('dashboard-data');
            const initialTimeframe = dataEl.dataset.timeframe;
            const initialTotalStudents = parseInt(dataEl.dataset.totalStudents);
            const initialPresentToday = parseInt(dataEl.dataset.presentToday);
            const initialAbsentToday = parseInt(dataEl.dataset.absentToday);
            const initialLateToday = parseInt(dataEl.dataset.lateToday);
            const dashboardRoute = dataEl.dataset.dashboardRoute;
            const classStudentsRoute = dataEl.dataset.classStudentsRoute;

            return {
                timeframe: initialTimeframe,
                loading: false,
                stats: {
                    totalStudents: initialTotalStudents,
                    presentToday: initialPresentToday,
                    absentToday: initialAbsentToday,
                    lateToday: initialLateToday
                },
                classModal: {
                    show: false,
                    classId: null,
                    displayName: '',
                    subtitle: '',
                    students: [],
                    filter: 'all',
                    loading: false
                },
                openClassModal(classId, displayName, subtitle) {
                    this.classModal.classId = classId;
                    this.classModal.displayName = displayName;
                    this.classModal.subtitle = subtitle || 'View student attendance status for today';
                    this.classModal.filter = 'all';
                    this.classModal.loading = true;
                    this.classModal.show = true;
                    this.loadClassStudents(classId);
                },
                async loadClassStudents(classId) {
                    try {
                        const response = await axios.get(classStudentsRoute, {
                            params: { class_id: classId },
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        this.classModal.students = response.data.students || [];
                    } catch (error) {
                        console.error('Error loading students:', error);
                        this.classModal.students = [];
                    } finally {
                        this.classModal.loading = false;
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    }
                },
                get filteredStudents() {
                    if (this.classModal.filter === 'all') return this.classModal.students;
                    return this.classModal.students.filter(s => {
                        if (this.classModal.filter === 'present') return s.today_status === 'present';
                        if (this.classModal.filter === 'absent') return s.today_status === 'absent';
                        if (this.classModal.filter === 'late') return s.today_status === 'late';
                        return true;
                    });
                },
                get counts() {
                    return {
                        all: this.classModal.students.length,
                        present: this.classModal.students.filter(s => s.today_status === 'present').length,
                        absent: this.classModal.students.filter(s => s.today_status === 'absent').length,
                        late: this.classModal.students.filter(s => s.today_status === 'late').length,
                    };
                },
                initDashboard() {
                    // Re-initialize Lucide icons when filters change or loading finishes
                    this.$watch('classModal.filter', () => {
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    });
                    
                    this.$watch('classModal.loading', (val) => {
                        if (!val) {
                            this.$nextTick(() => {
                                if (typeof lucide !== 'undefined') lucide.createIcons();
                            });
                        }
                    });

                    // Hide loading overlay and show content after page is ready
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.loading = false;
                            document.querySelector('.dashboard-page').classList.add('loaded');
                        }, 300);
                    });
                },
                async updateTimeframe() {
                    this.loading = true;
                    try {
                        console.log('Updating timeframe to:', this.timeframe);
                        const response = await axios.get(dashboardRoute, {
                            params: { timeframe: this.timeframe },
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        console.log('Response received:', response.data);
                        console.log('Response status:', response.status);
                        
                        if (response.data) {
                            // Update the chart section with new data
                            const chartContainer = document.querySelector('.chart-line-inner');
                            console.log('Chart container found:', !!chartContainer);
                            console.log('Chart data present:', !!response.data.chart);
                            
                            if (chartContainer && response.data.chart) {
                                chartContainer.innerHTML = response.data.chart;
                                console.log('Chart updated successfully');
                            } else {
                                console.error('Chart container or chart data not found', {
                                    hasContainer: !!chartContainer,
                                    hasChartData: !!response.data.chart,
                                    chartDataLength: response.data.chart ? response.data.chart.length : 0
                                });
                            }
                            
                            // Update labels
                            const labelsContainer = document.querySelector('.chart-x-labels--below');
                            console.log('Labels container found:', !!labelsContainer);
                            console.log('Labels data present:', !!response.data.labels);
                            
                            if (labelsContainer && response.data.labels) {
                                labelsContainer.innerHTML = response.data.labels;
                                labelsContainer.style.gridTemplateColumns = `repeat(${response.data.labelCount}, minmax(0, 1fr))`;
                                console.log('Labels updated successfully');
                            } else {
                                console.error('Labels container or labels data not found', {
                                    hasContainer: !!labelsContainer,
                                    hasLabelsData: !!response.data.labels,
                                    labelCount: response.data.labelCount
                                });
                            }
                        } else {
                            console.error('No response data');
                        }
                    } catch (error) {
                        console.error('Error updating timeframe:', error);
                        console.error('Error response:', error.response);
                        console.error('Error status:', error.response?.status);
                        console.error('Error data:', error.response?.data);
                        // Fallback to full page reload on error
                        window.location.href = `{{ route('dashboard') }}?timeframe=${this.timeframe}`;
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>

