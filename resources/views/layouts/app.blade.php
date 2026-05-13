<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Attenad' }} – Student Attendance Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #22c55e;
            --primary-dark: #16a34a;
            --primary-light: #86efac;
            --bg-body: #f3f4f6;
            --surface: #ffffff;
            --text-main: #111827;
            --text: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --header-bg: #111827;
            --page-inline-pad: clamp(1rem, 3vw, 2.5rem);
            --section-gap: clamp(1rem, 2.5vw, 1.5rem);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-main); 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── Top Navigation ──────────────────────────────────── */
        .topbar {
            background: var(--header-bg);
            color: white;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            position: relative;
            z-index: 50;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 3rem;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        .logo-icon {
            background: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 800;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            height: 70px;
        }
        .nav-link {
            display: inline-flex;
            align-items: center;
            color: #9ca3af;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
            position: relative;
            height: 100%;
        }
        .nav-link:hover { color: white; }
        .nav-link.active {
            color: white;
            font-weight: 600;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
            border-radius: 3px 3px 0 0;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 250px;
            transition: background 0.2s;
        }
        .search-container:focus-within {
            background: rgba(255, 255, 255, 0.15);
        }
        .search-input {
            background: transparent;
            border: none;
            color: white;
            outline: none;
            width: 100%;
            font-size: 0.85rem;
            font-family: inherit;
        }
        .search-input::placeholder { color: #9ca3af; }
        .nav-icon-btn {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 1.1rem;
            cursor: pointer;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .nav-icon-btn:hover { color: white; }
        .nav-icon-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
        }
        .user-profile-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #1e293b;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* ── Main Layout ────────────────────────────────────── */
        .main-wrap { flex: 1; display: flex; flex-direction: column; width: 100%; }
        
        /* ── Global Utility Classes ─────────────────────────── */
        .card { background: var(--surface); border-radius: 12px; box-shadow: var(--shadow-card); border: 1px solid var(--border-color); }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
        .card-title { font-size: clamp(0.9375rem, 1.1vw, 1rem); font-weight: 600; color: var(--text-main); letter-spacing: -0.01em; }
        .card-body { padding: 1.5rem; }

        /* Inner pages: full-width title band + constrained main (matches dashboard polish) */
        .app-page-banner {
            background: var(--header-bg);
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            box-sizing: border-box;
            padding: clamp(1rem, 2.5vw, 1.375rem) 0 clamp(1.125rem, 3vw, 1.625rem);
        }
        .app-page-banner-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--page-inline-pad);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .app-page-banner-title {
            color: #fff;
            font-size: clamp(1.125rem, 2.2vw, 1.35rem);
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin: 0;
        }
        .app-page-banner-sub {
            color: #d1d5db;
            font-size: clamp(0.875rem, 1.5vw, 0.9375rem);
            margin: 0.35rem 0 0;
            max-width: 42rem;
            line-height: 1.45;
        }
        .app-page-banner-actions { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
        .app-page {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: var(--section-gap) var(--page-inline-pad) clamp(1rem, 3vw, 2.5rem);
        }

        /* Forms & inputs (authenticated app pages; aligned with dashboard / Inter) */
        .form-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; margin-bottom: 0.25rem; }
        @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.375rem; text-transform: uppercase; letter-spacing: 0.06em; }
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--text-main);
            background: var(--surface);
        }
        .form-control:focus { outline: none; border-color: var(--primary-dark); box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.22); }
        .form-control::placeholder { color: #94a3b8; }
        select.form-control { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E"); background-position: right 0.6rem center; background-repeat: no-repeat; background-size: 1rem; padding-right: 2.25rem; }
        .form-error { color: #b91c1c; font-size: 0.8125rem; margin-top: 0.35rem; }

        /* Reports / KPI row */
        .app-kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: var(--section-gap);
            margin-bottom: var(--section-gap);
        }
        @media (max-width: 992px) { .app-kpi-grid { grid-template-columns: 1fr; } }
        .app-kpi-card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-card);
            padding: clamp(1rem, 2.5vw, 1.25rem) clamp(1rem, 2.5vw, 1.5rem);
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }
        .app-kpi-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .app-kpi-value { font-size: 1.375rem; font-weight: 700; color: var(--text-main); line-height: 1.15; letter-spacing: -0.02em; }
        .app-kpi-label { font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-top: 0.125rem; }

        .empty-state { text-align: center; padding: 2.75rem 1.5rem; color: var(--text-muted); }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 0.75rem; line-height: 1; }
        .empty-state h3 { font-size: 1.05rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.35rem; }
        .empty-state p { font-size: 0.875rem; max-width: 22rem; margin: 0 auto; }

        .progress-bar-wrap { height: 6px; background: #e5e7eb; border-radius: 6px; overflow: hidden; flex: 1; min-width: 40px; }
        .progress-bar { height: 100%; border-radius: 6px; transition: width 0.2s ease; }
        .progress-bar.progress-success { background: linear-gradient(90deg, var(--primary), var(--primary-dark)); }
        .progress-bar.progress-danger { background: #f87171; }

        .btn-danger { background: #ef4444; color: #fff; border: none; }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35); }

        /* Attendance status toggles */
        .att-radio-group { display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center; }
        .att-radio { position: absolute; opacity: 0; width: 0; height: 0; }
        .att-label {
            display: inline-flex; align-items: center; gap: 0.2rem;
            padding: 0.28rem 0.55rem; border-radius: 6px;
            font-size: 0.72rem; font-weight: 600; cursor: pointer;
            border: 1px solid var(--border-color); background: #f9fafb; color: var(--text-muted);
            transition: background 0.15s, border-color 0.15s;
        }
        .att-radio:focus-visible + .att-label { outline: 2px solid var(--primary); outline-offset: 2px; }
        .att-radio:checked + .att-label.att-present { background: rgba(34, 197, 94, 0.12); border-color: #86efac; color: #166534; }
        .att-radio:checked + .att-label.att-absent { background: rgba(239, 68, 68, 0.1); border-color: #fecaca; color: #991b1b; }
        .att-radio:checked + .att-label.att-late { background: rgba(245, 158, 11, 0.12); border-color: #fde68a; color: #92400e; }

        /* Profile shell (Tailwind-powered forms inside cards) */
        .profile-forms { max-width: 36rem; display: flex; flex-direction: column; gap: var(--section-gap); }
        .form-section-heading { font-size: 1rem; font-weight: 600; color: var(--text-main); margin: 0 0 0.25rem; }
        .form-section-lead { font-size: 0.875rem; color: var(--text-muted); margin: 0; line-height: 1.45; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; padding: .6rem 1.2rem; border-radius: 8px; font-size: .85rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(34,197,94,.3); }
        .btn-secondary { background: white; color: var(--text-main); border: 1px solid var(--border-color); }
        .btn-secondary:hover { background: #f9fafb; border-color: #d1d5db; }
        .btn-sm { padding: .4rem .85rem; font-size: .78rem; }
        .btn-icon { padding: 0.38rem 0.5rem; min-width: 2rem; justify-content: center; }

        .text-success { color: var(--primary-dark); }
        .text-danger { color: #ef4444; }
        
        .alert { padding: .9rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: .875rem; display: flex; align-items: center; gap: .6rem; }
        .alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error   { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-muted   { background: #f1f5f9; color: var(--text-muted); }
        
        /* Table */
        .table-wrap { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        thead th { padding: 1rem; text-align: left; font-size: .75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border-color); background: #f8fafc; }
        tbody tr { border-bottom: 1px solid var(--border-color); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }
        tbody td { padding: 1rem; font-size: .875rem; color: var(--text-main); }
        
        /* Mobile Nav */
        .mobile-menu-btn { display: none; background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0.5rem; margin-right: -0.5rem; }
        
        .mobile-sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 280px;
            height: 100vh;
            background: var(--header-bg);
            z-index: 1000;
            transition: right 0.3s ease;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .mobile-sidebar.open {
            right: 0;
        }
        
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-overlay.open {
            display: block;
            opacity: 1;
        }
        
        .mobile-close-btn {
            align-self: flex-end;
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 1.5rem;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }
        
        .mobile-nav-link {
            display: block;
            color: #9ca3af;
            font-size: 1rem;
            font-weight: 500;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
        }
        
        .mobile-nav-link.active {
            color: white;
            font-weight: 600;
        }
        
        @media (max-width: 992px) {
            .nav-links, .search-container { display: none; }
            .mobile-menu-btn { display: block; }
            .topbar { padding: 0 1.5rem; }
        }
        
        @media (max-width: 480px) {
            .nav-icon-btn:not(:first-of-type) { display: none; }
            .topbar { padding: 0 1rem; }
            .logo { font-size: 1.1rem; gap: 0.4rem; }
        }
    </style>
</head>
<body>

    {{-- Top Navigation --}}
    <nav class="topbar">
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="logo">
                <div class="logo-icon">A</div>
                Attenad
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">Manage Attendance</a>
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Student's List</a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
            </div>
        </div>
        
        <div class="nav-right">
            <div class="search-container">
                <i data-lucide="search" data-size="18" style="color:#9ca3af;"></i>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
            <button class="nav-icon-btn">
                <i data-lucide="bell" data-size="18"></i>
                <span class="nav-icon-badge"></span>
            </button>
            <button class="nav-icon-btn">
                <i data-lucide="settings" data-size="18"></i>
            </button>
            <div style="position: relative; display: inline-block;">
                <button class="user-profile-btn" onclick="document.getElementById('user-dropdown').classList.toggle('show')">
                    <div class="user-avatar">
                        @if(Auth::check() && Auth::user()->name)
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @else
                            U
                        @endif
                    </div>
                </button>
                <div id="user-dropdown" style="display: none; position: absolute; right: 0; top: 120%; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid var(--border-color); min-width: 150px; overflow: hidden; z-index: 100;">
                    <a href="{{ route('profile.edit') }}" style="display: block; padding: 0.75rem 1rem; color: var(--text-main); text-decoration: none; font-size: 0.85rem; border-bottom: 1px solid var(--border-color);">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; font-size: 0.85rem; cursor: pointer;">Sign Out</button>
                    </form>
                </div>
            </div>
            <button class="mobile-menu-btn" onclick="openMobileMenu()">
                <i data-lucide="menu" data-size="22"></i>
            </button>
        </div>
    </nav>
    <style>
        #user-dropdown.show { display: block !important; }
    </style>

    {{-- Mobile Sidebar --}}
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <button class="mobile-close-btn" onclick="closeMobileMenu()">
            <i data-lucide="x" data-size="20"></i>
        </button>
        <div style="margin-bottom: 2rem;">
            <div class="search-container" style="display: flex; width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                <i data-lucide="search" data-size="18" style="color:#9ca3af;"></i>
                <input type="text" class="search-input" placeholder="Search..." style="color: white;">
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Overview</a>
        <a href="{{ route('attendance.index') }}" class="mobile-nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">Manage Attendance</a>
        <a href="{{ route('students.index') }}" class="mobile-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Student's List</a>
        <a href="{{ route('reports.index') }}" class="mobile-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
    </div>

    {{-- Main Content --}}
    <div class="main-wrap">
        {{-- Flash Messages --}}
        <div style="padding: 0 5%; position: absolute; top: 80px; left: 0; right: 0; z-index: 100; pointer-events: none;">
            @if(session('success'))
                <div class="alert alert-success" style="pointer-events: auto; max-width: 600px; margin: 0 auto 1rem;">
                    <i data-lucide="check-circle-2" data-size="18" style="color:#166534;"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" style="pointer-events: auto; max-width: 600px; margin: 0 auto 1rem;">
                    <i data-lucide="x-circle" data-size="18" style="color:#991b1b;"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        {{ $slot }}
    </div>

    <script>
        function openMobileMenu() {
            document.getElementById('mobileSidebar').classList.add('open');
            document.getElementById('mobileOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenu() {
            document.getElementById('mobileSidebar').classList.remove('open');
            document.getElementById('mobileOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.user-profile-btn')) {
                var dropdown = document.getElementById('user-dropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // Hydrate progress bars from data attributes
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-progress]').forEach((el) => {
                const raw = el.getAttribute('data-progress');
                const n = Number(raw);
                if (!Number.isFinite(n)) return;
                const pct = Math.max(0, Math.min(100, n));
                el.style.width = `${pct}%`;
            });
        });
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        const hydrateLucide = () => {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', hydrateLucide);
        } else {
            hydrateLucide();
        }
    </script>
</body>
</html>
