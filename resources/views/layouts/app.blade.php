<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AttendTrack' }} – Student Attendance Tracker</title>
    <meta name="description" content="Professional student attendance management for modern educators.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --sidebar-bg: #0f172a;
            --sidebar-border: #1e293b;
            --surface: #1e293b;
            --surface-2: #334155;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: var(--text); min-height: 100vh; display: flex; }

        /* ── Sidebar ──────────────────────────────────── */
        .sidebar {
            width: 260px; min-height: 100vh; background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
            transition: transform .3s ease;
        }
        .sidebar-logo {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex; align-items: center; gap: .75rem;
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .sidebar-logo-text { font-size: 1.05rem; font-weight: 700; color: var(--text); }
        .sidebar-logo-sub  { font-size: .68rem; color: var(--text-muted); font-weight: 400; }

        .sidebar-nav { padding: 1rem .75rem; flex: 1; }
        .nav-section-label {
            font-size: .6rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .12em; color: var(--text-muted);
            padding: 1rem .5rem .4rem;
        }
        .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem .75rem; border-radius: 8px;
            color: var(--text-muted); font-size: .875rem; font-weight: 500;
            text-decoration: none; transition: all .2s; margin-bottom: 2px;
        }
        .nav-link:hover { background: var(--surface); color: var(--text); }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(79,70,229,.15));
            color: var(--primary-light); border-left: 3px solid var(--primary);
        }
        .nav-link .icon { width: 18px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 1rem; border-top: 1px solid var(--sidebar-border);
        }
        .user-card {
            display: flex; align-items: center; gap: .75rem;
            padding: .75rem; border-radius: 10px; background: var(--surface);
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem; flex-shrink: 0;
        }
        .user-name  { font-size: .8rem; font-weight: 600; color: var(--text); }
        .user-email { font-size: .7rem; color: var(--text-muted); }
        .logout-btn {
            display: flex; align-items: center; gap: .5rem;
            margin-top: .5rem; padding: .5rem .75rem; border-radius: 8px;
            color: var(--text-muted); font-size: .8rem; font-weight: 500;
            text-decoration: none; transition: all .2s; width: 100%;
            background: none; border: none; cursor: pointer;
        }
        .logout-btn:hover { background: rgba(239,68,68,.1); color: #ef4444; }

        /* ── Main ─────────────────────────────────────── */
        .main-wrap { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            height: 64px; background: rgba(15,23,42,.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sidebar-border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; position: sticky; top: 0; z-index: 40;
        }
        .page-title { font-size: 1.15rem; font-weight: 700; color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .date-badge {
            font-size: .78rem; color: var(--text-muted);
            background: var(--surface); padding: .35rem .8rem; border-radius: 20px;
        }

        .page-content { padding: 2rem; flex: 1; }

        /* ── Alert Flashes ────────────────────────────── */
        .alert { padding: .9rem 1.2rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: .875rem; display: flex; align-items: center; gap: .6rem; }
        .alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
        .alert-error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }

        /* ── Cards ────────────────────────────────────── */
        .card { background: var(--surface); border: 1px solid var(--sidebar-border); border-radius: 14px; }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--sidebar-border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: .95rem; font-weight: 700; color: var(--text); }
        .card-body { padding: 1.5rem; }

        /* ── Stat Cards ───────────────────────────────── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--sidebar-border);
            border-radius: 14px; padding: 1.4rem 1.5rem;
            display: flex; align-items: flex-start; gap: 1rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.3); }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-label { font-size: .78rem; color: var(--text-muted); margin-top: .2rem; }
        .stat-change { font-size: .75rem; margin-top: .4rem; font-weight: 500; }

        /* ── Table ────────────────────────────────────── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: .75rem 1rem; text-align: left; font-size: .72rem;
            font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            color: var(--text-muted); border-bottom: 1px solid var(--sidebar-border);
            background: rgba(15,23,42,.5);
        }
        tbody tr { border-bottom: 1px solid var(--sidebar-border); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(99,102,241,.05); }
        tbody td { padding: .85rem 1rem; font-size: .875rem; color: var(--text); }

        /* ── Badges ───────────────────────────────────── */
        .badge { display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 600; }
        .badge-success { background: rgba(16,185,129,.15); color: #6ee7b7; }
        .badge-danger  { background: rgba(239,68,68,.15);  color: #fca5a5; }
        .badge-warning { background: rgba(245,158,11,.15); color: #fcd34d; }
        .badge-muted   { background: rgba(148,163,184,.1); color: var(--text-muted); }

        /* ── Buttons ──────────────────────────────────── */
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .6rem 1.2rem; border-radius: 8px; font-size: .85rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(99,102,241,.4); }
        .btn-secondary { background: var(--surface-2); color: var(--text); }
        .btn-secondary:hover { background: #475569; }
        .btn-danger { background: rgba(239,68,68,.15); color: #ef4444; border: 1px solid rgba(239,68,68,.3); }
        .btn-danger:hover { background: rgba(239,68,68,.25); }
        .btn-sm { padding: .4rem .85rem; font-size: .78rem; }
        .btn-icon { width: 34px; height: 34px; padding: 0; justify-content: center; }

        /* ── Form ─────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: .8rem; font-weight: 600; color: var(--text-muted); margin-bottom: .45rem; text-transform: uppercase; letter-spacing: .05em; }
        .form-control {
            width: 100%; padding: .7rem 1rem; background: var(--sidebar-bg);
            border: 1px solid var(--surface-2); border-radius: 8px;
            color: var(--text); font-size: .875rem; font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,.2); }
        .form-control::placeholder { color: var(--text-muted); }
        .form-error { font-size: .75rem; color: #fca5a5; margin-top: .35rem; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 1rem; }

        /* ── Progress Bar ─────────────────────────────── */
        .progress-bar-wrap { background: var(--sidebar-bg); border-radius: 99px; height: 7px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 99px; transition: width .5s ease; }
        .progress-success { background: linear-gradient(90deg, var(--success), #34d399); }
        .progress-danger  { background: linear-gradient(90deg, var(--danger), #f87171); }
        .progress-warning { background: linear-gradient(90deg, var(--warning), #fcd34d); }

        /* ── Hamburger (mobile) ───────────────────────── */
        .hamburger { display: none; background: none; border: none; color: var(--text); font-size: 1.4rem; cursor: pointer; padding: .25rem; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .hamburger { display: block; }
            .page-content { padding: 1.25rem; }
        }

        /* ── Attendance Radio Buttons ─────────────────── */
        .att-radio-group { display: flex; gap: .5rem; }
        .att-radio { display: none; }
        .att-label {
            padding: .35rem .8rem; border-radius: 20px; font-size: .75rem;
            font-weight: 600; cursor: pointer; border: 1px solid transparent;
            transition: all .2s;
        }
        .att-present { border-color: rgba(16,185,129,.3); color: #6ee7b7; }
        .att-absent  { border-color: rgba(239,68,68,.3);  color: #fca5a5; }
        .att-late    { border-color: rgba(245,158,11,.3); color: #fcd34d; }
        .att-radio:checked + .att-label.att-present { background: rgba(16,185,129,.2); }
        .att-radio:checked + .att-label.att-absent  { background: rgba(239,68,68,.2); }
        .att-radio:checked + .att-label.att-late    { background: rgba(245,158,11,.2); }

        /* ── Empty State ──────────────────────────────── */
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
        .empty-state .icon { font-size: 3.5rem; margin-bottom: 1rem; opacity: .4; }
        .empty-state h3 { font-size: 1.1rem; font-weight: 600; color: var(--text); margin-bottom: .5rem; }
        .empty-state p { font-size: .875rem; }

        /* ── Overlay ──────────────────────────────────── */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 49; }
        .sidebar-overlay.open { display: block; }

        /* ── Tooltip ──────────────────────────────────── */
        [data-tooltip] { position: relative; }
        [data-tooltip]::after { content: attr(data-tooltip); position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%); background: #1e293b; color: var(--text); font-size: .7rem; padding: .3rem .6rem; border-radius: 6px; white-space: nowrap; pointer-events: none; opacity: 0; transition: opacity .2s; border: 1px solid var(--sidebar-border); }
        [data-tooltip]:hover::after { opacity: 1; }
    </style>
</head>
<body>

{{-- Sidebar overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ── Sidebar ──────────────────────────────────────────── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">📋</div>
        <div>
            <div class="sidebar-logo-text">AttendTrack</div>
            <div class="sidebar-logo-sub">Student Attendance System</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <span class="icon">✅</span> Take Attendance
        </a>

        <div class="nav-section-label">Management</div>

        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <span class="icon">👥</span> Students
        </a>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="icon">📈</span> Reports
        </a>

        <div class="nav-section-label">Account</div>

        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="icon">👤</span> Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <span>🚪</span> Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ─────────────────────────────────────────────── --}}
<div class="main-wrap">
    {{-- Top Bar --}}
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:1rem;">
            <button class="hamburger" onclick="openSidebar()">☰</button>
            <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
        </div>
        <div class="topbar-right">
            <span class="date-badge">📅 {{ now()->format('l, F j, Y') }}</span>
        </div>
    </header>

    {{-- Flash Messages --}}
    <div style="padding: 0 2rem;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-top:1.5rem;">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="margin-top:1.5rem;">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="page-content">
        {{ $slot }}
    </main>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebar-overlay').classList.add('open'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebar-overlay').classList.remove('open'); }
</script>
</body>
</html>
