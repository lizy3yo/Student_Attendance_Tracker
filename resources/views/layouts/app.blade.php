<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Attendly' }} – Student Attendance Tracker</title>
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
        .main-wrap { flex: 1; display: flex; flex-direction: column; width: 100%; position: relative; }
        
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

        /* ── Toast Notifications ──────────────────────────────────── */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
            max-width: 420px;
        }

        .toast {
            background: white;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            animation: slideInToast 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: auto;
            border-left: 4px solid;
            font-size: 0.9375rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .toast.hide {
            animation: slideOutToast 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .toast-success {
            border-left-color: #22c55e;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            color: #166534;
        }

        .toast-error {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            color: #991b1b;
        }

        .toast-warning {
            border-left-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
            color: #92400e;
        }

        .toast-info {
            border-left-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            color: #1e40af;
        }

        .toast-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-success .toast-icon { color: #22c55e; }
        .toast-error .toast-icon { color: #ef4444; }
        .toast-warning .toast-icon { color: #f59e0b; }
        .toast-info .toast-icon { color: #3b82f6; }

        .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .toast-message {
            font-size: 0.875rem;
            opacity: 0.85;
        }

        .toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            color: inherit;
            opacity: 0.5;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .toast-close:hover {
            opacity: 1;
        }

        @keyframes slideInToast {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutToast {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .toast-container {
                top: 1rem;
                right: 1rem;
                left: 1rem;
                max-width: none;
            }

            .toast {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }
        }
        
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

        body.nav-loading .logo,
        body.nav-loading .nav-link,
        body.nav-loading .nav-icon-btn,
        body.nav-loading .user-avatar,
        body.nav-loading .mobile-menu-btn,
        body.nav-loading .mobile-nav-link,
        body.nav-loading .mobile-close-btn {
            color: transparent !important;
            background: linear-gradient(90deg, rgba(255,255,255,0.08) 25%, rgba(255,255,255,0.18) 37%, rgba(255,255,255,0.08) 63%);
            background-size: 400% 100%;
            animation: navSkeletonShimmer 1.2s ease-in-out infinite;
            border-radius: 10px;
            pointer-events: none;
        }

        body.nav-loading .logo-icon,
        body.nav-loading .nav-icon-btn i,
        body.nav-loading .mobile-menu-btn i,
        body.nav-loading .mobile-close-btn i {
            opacity: 0;
        }

        body.nav-loading .logo {
            min-width: 120px;
            min-height: 30px;
        }

        body.nav-loading .nav-link {
            min-width: 88px;
            height: 18px;
        }

        body.nav-loading .nav-icon-btn {
            width: 36px;
            height: 36px;
        }

        body.nav-loading .user-avatar {
            width: 36px;
            height: 36px;
        }

        body.nav-loading .mobile-nav-link {
            min-height: 20px;
        }

        @keyframes navSkeletonShimmer {
            0% { background-position: 100% 0; }
            100% { background-position: 0 0; }
        }

        /* ── Content Skeleton Loading ──────────────────────────────────── */
        .main-wrap.content-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .content-skeleton {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-body);
            z-index: 30;
            overflow-y: auto;
        }

        .main-wrap.content-loading .content-skeleton {
            display: block;
        }

        .content-skeleton[data-skeleton-type="hidden"] {
            display: none !important;
        }

        .skeleton-block {
            background: linear-gradient(90deg, rgba(0,0,0,0.08) 25%, rgba(0,0,0,0.15) 37%, rgba(0,0,0,0.08) 63%);
            background-size: 400% 100%;
            animation: skeletonShimmer 1.2s ease-in-out infinite;
            border-radius: 8px;
        }

        /* Dashboard Skeleton */
        .skeleton-dashboard .skeleton-hero {
            height: 100px;
            margin-bottom: 1.5rem;
            border-radius: 12px;
        }

        .skeleton-dashboard .skeleton-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .skeleton-dashboard .skeleton-card {
            height: 100px;
            border-radius: 12px;
        }

        .skeleton-dashboard .skeleton-chart {
            height: 280px;
            margin-bottom: 2rem;
            border-radius: 12px;
        }

        .skeleton-dashboard .skeleton-list-item {
            height: 45px;
            margin-bottom: 0.75rem;
            border-radius: 8px;
        }

        /* Table Skeleton (Attendance & Students) */
        .skeleton-table {
            padding: 1.5rem;
        }

        .skeleton-table-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .skeleton-table-header-cell {
            height: 40px;
            border-radius: 8px;
        }

        .skeleton-table-rows {
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        .skeleton-table-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            align-items: center;
        }

        .skeleton-table-row:last-child {
            border-bottom: none;
        }

        .skeleton-table-cell {
            height: 35px;
            border-radius: 6px;
        }

        .skeleton-table-row .skeleton-table-cell:first-child {
            min-width: 80px;
        }

        /* Reports Skeleton */
        .skeleton-reports .skeleton-hero {
            height: 80px;
            margin-bottom: 1.5rem;
            border-radius: 12px;
        }

        .skeleton-reports .skeleton-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .skeleton-reports .skeleton-chart {
            height: 320px;
            border-radius: 12px;
        }

        @keyframes skeletonShimmer {
            0% { background-position: 100% 0; }
            100% { background-position: 0 0; }
        }

        @media (max-width: 992px) {
            .skeleton-dashboard .skeleton-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .skeleton-table-header,
            .skeleton-table-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .skeleton-reports .skeleton-row {
                grid-template-columns: 1fr;
            }

            .skeleton-reports .skeleton-row .skeleton-chart {
                height: 280px;
            }
        }

        @media (max-width: 768px) {
            .skeleton-dashboard .skeleton-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .skeleton-dashboard .skeleton-chart {
                height: 240px;
            }

            .skeleton-table-header,
            .skeleton-table-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .skeleton-table-cell {
                height: 30px;
            }

            .skeleton-table-header-cell {
                height: 35px;
            }
        }
        
        @media (max-width: 480px) {
            .nav-links, .search-container { display: none; }
            .mobile-menu-btn { display: block; }
            .topbar { padding: 0 1rem; }
            .logo { font-size: 1.1rem; gap: 0.4rem; }
            .nav-icon-btn:not(:first-of-type) { display: none; }

            .skeleton-dashboard .skeleton-hero {
                height: 80px;
            }

            .skeleton-dashboard .skeleton-cards {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .skeleton-dashboard .skeleton-card {
                height: 90px;
            }

            .skeleton-dashboard .skeleton-chart {
                height: 200px;
            }

            .skeleton-table {
                padding: 1rem;
            }

            .skeleton-table-header,
            .skeleton-table-row {
                grid-template-columns: 1fr;
            }

            .skeleton-table-row {
                padding: 0.75rem;
                gap: 0.5rem;
            }

            .skeleton-table-cell,
            .skeleton-table-header-cell {
                height: 28px;
            }

            .skeleton-reports .skeleton-hero {
                height: 70px;
            }

            .skeleton-reports .skeleton-row {
                grid-template-columns: 1fr;
            }

            .skeleton-reports .skeleton-chart {
                height: 240px;
            }
        }
    </style>
</head>
<body class="nav-loading">

    {{-- Top Navigation --}}
    <nav class="topbar">
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="logo">
                <div class="logo-icon"><i data-lucide="clipboard-check" data-size="18"></i></div>
                Attendly
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">Manage Attendance</a>
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Student's List</a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
            </div>
        </div>
        
        <div class="nav-right">
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

    <script>
        window.addEventListener('load', () => {
            document.body.classList.remove('nav-loading');
        });

        // Add content skeleton loading on navigation
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
            const mainWrap = document.querySelector('.main-wrap');
            const skeletons = document.querySelectorAll('.content-skeleton');

            function getSkeletonType(href) {
                if (href.includes('dashboard') || href === '/') {
                    return 'dashboard';
                } else if (href.includes('attendance')) {
                    return 'table';
                } else if (href.includes('students')) {
                    return 'table';
                } else if (href.includes('reports')) {
                    return 'reports';
                }
                return 'dashboard';
            }

            function showSkeleton(skeletonType) {
                skeletons.forEach(skeleton => {
                    const type = skeleton.getAttribute('data-skeleton-type');
                    if (type === skeletonType) {
                        skeleton.style.display = 'block';
                    } else {
                        skeleton.style.display = 'none';
                    }
                });
            }

            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    // Don't trigger loading for the current page
                    if (!link.classList.contains('active')) {
                        const href = link.getAttribute('href');
                        const skeletonType = getSkeletonType(href);
                        
                        if (mainWrap) {
                            mainWrap.classList.add('content-loading');
                            showSkeleton(skeletonType);
                        }
                    }
                });
            });

            // Remove loading state when page fully loads
            window.addEventListener('load', () => {
                if (mainWrap) {
                    mainWrap.classList.remove('content-loading');
                    skeletons.forEach(skeleton => skeleton.style.display = 'none');
                }
            });
        });
    </script>

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
        {{-- Hidden Flash Message Elements (for toast conversion) --}}
        @if(session('success'))
            <div class="alert alert-success" style="display: none;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="display: none;">{{ session('error') }}</div>
        @endif

        {{-- Content Skeleton Loading - Dashboard --}}
        <div class="content-skeleton skeleton-dashboard" data-skeleton-type="dashboard" style="padding: 2rem 5%;">
            <div class="skeleton-block skeleton-hero"></div>
            <div class="skeleton-cards">
                <div class="skeleton-block skeleton-card"></div>
                <div class="skeleton-block skeleton-card"></div>
                <div class="skeleton-block skeleton-card"></div>
                <div class="skeleton-block skeleton-card"></div>
            </div>
            <div class="skeleton-block skeleton-chart"></div>
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                <div class="skeleton-block skeleton-chart"></div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div class="skeleton-block skeleton-list-item"></div>
                    <div class="skeleton-block skeleton-list-item"></div>
                    <div class="skeleton-block skeleton-list-item"></div>
                    <div class="skeleton-block skeleton-list-item"></div>
                </div>
            </div>
        </div>

        {{-- Content Skeleton Loading - Table (Attendance & Students) --}}
        <div class="content-skeleton skeleton-table" data-skeleton-type="table" style="padding: 2rem 5%;">
            <div class="skeleton-table-header">
                <div class="skeleton-block skeleton-table-header-cell"></div>
                <div class="skeleton-block skeleton-table-header-cell"></div>
                <div class="skeleton-block skeleton-table-header-cell"></div>
                <div class="skeleton-block skeleton-table-header-cell"></div>
                <div class="skeleton-block skeleton-table-header-cell"></div>
            </div>
            <div class="skeleton-table-rows">
                <div class="skeleton-table-row">
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                </div>
                <div class="skeleton-table-row">
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                    <div class="skeleton-block skeleton-table-cell"></div>
                </div>
            </div>
        </div>

        {{-- Content Skeleton Loading - Reports --}}
        <div class="content-skeleton skeleton-reports" data-skeleton-type="reports" style="padding: 2rem 5%;">
            <div class="skeleton-block skeleton-hero"></div>
            <div class="skeleton-reports skeleton-row">
                <div class="skeleton-block skeleton-chart"></div>
                <div class="skeleton-block skeleton-chart"></div>
            </div>
            <div class="skeleton-reports skeleton-row">
                <div class="skeleton-block skeleton-chart"></div>
                <div class="skeleton-block skeleton-chart"></div>
            </div>
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

    {{-- Toast Container --}}
    <div id="toast-container" class="toast-container"></div>

    {{-- Auth Status Tracker --}}
    <meta id="auth-status" content="{{ Auth::check() ? 'authenticated' : 'guest' }}">

    {{-- Global Toast System --}}
    <script>
        class ToastNotification {
            static DEFAULT_DURATION = 4000;
            static icons = {
                success: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
                error: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
                warning: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                info: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
            };

            static show(message, type = 'info', title = null, duration = this.DEFAULT_DURATION) {
                const container = document.getElementById('toast-container') || this.createContainer();
                const toast = document.createElement('div');
                const id = 'toast-' + Date.now();
                
                toast.id = id;
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <div class="toast-icon">${this.icons[type] || this.icons.info}</div>
                    <div class="toast-content">
                        ${title ? `<div class="toast-title">${title}</div>` : ''}
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close" aria-label="Close notification" onclick="document.getElementById('${id}').remove()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                `;
                
                container.appendChild(toast);

                if (duration > 0) {
                    setTimeout(() => {
                        if (document.getElementById(id)) {
                            toast.classList.add('hide');
                            setTimeout(() => toast.remove(), 300);
                        }
                    }, duration);
                }

                return toast;
            }

            static success(message, title = 'Success', duration = this.DEFAULT_DURATION) {
                return this.show(message, 'success', title, duration);
            }

            static error(message, title = 'Error', duration = this.DEFAULT_DURATION) {
                return this.show(message, 'error', title, duration);
            }

            static warning(message, title = 'Warning', duration = this.DEFAULT_DURATION) {
                return this.show(message, 'warning', title, duration);
            }

            static info(message, title = 'Info', duration = this.DEFAULT_DURATION) {
                return this.show(message, 'info', title, duration);
            }

            static createContainer() {
                const container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container';
                document.body.appendChild(container);
                return container;
            }
        }

        // Show flash messages as toasts on page load
        document.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.querySelector('.alert-success');
            const errorAlert = document.querySelector('.alert-error');

            if (successAlert) {
                const message = successAlert.textContent.trim();
                if (message) {
                    ToastNotification.success(message, 'Success');
                }
            }

            if (errorAlert) {
                const message = errorAlert.textContent.trim();
                if (message) {
                    ToastNotification.error(message, 'Error');
                }
            }

            // Handle logout form submission
            const logoutForms = document.querySelectorAll('form[action*="logout"]');
            logoutForms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    // Show logout toast
                    ToastNotification.success('You have been signed out successfully', 'Signed Out');
                });
            });

            // Detect login by checking if we're now authenticated
            const authStatus = document.getElementById('auth-status');
            if (authStatus && authStatus.content === 'authenticated') {
                // Check if there's no error alert and no success alert
                // This means fresh login
                const hasAlerts = document.querySelector('.alert-success, .alert-error');
                if (!hasAlerts) {
                    // Don't show on initial page load, only on redirect after login
                    const isLoginPage = window.location.pathname.includes('login') || 
                                       window.location.pathname === '/' ||
                                       window.location.pathname.includes('auth');
                    const isFirstVisit = sessionStorage.getItem('isFirstVisit');
                    
                    if (!isLoginPage && !isFirstVisit) {
                        // Likely redirected here after login
                        // Only show if coming from auth page
                        const referrer = document.referrer;
                        if (referrer && referrer.includes('login')) {
                            ToastNotification.success('Welcome back! You have been signed in successfully', 'Signed In');
                        }
                    }
                    sessionStorage.setItem('isFirstVisit', 'false');
                }
            }

            // Handle form submissions for general success/error feedback
            const forms = document.querySelectorAll('form[method="POST"]');
            forms.forEach(form => {
                form.addEventListener('submit', () => {
                    // Optional: Show a "processing" toast
                    // ToastNotification.info('Processing...', 'Please wait', -1);
                });
            });

            // Intercept fetch requests (for API calls)
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .then(response => {
                        if (!response.ok && response.status !== 401 && response.status !== 403) {
                            // Could show error toast here if needed
                        }
                        return response;
                    })
                    .catch(error => {
                        // Show error on network issues
                        ToastNotification.error(error.message || 'Network error occurred', 'Error');
                        throw error;
                    });
            };
        });

        // Make ToastNotification globally accessible
        window.Toast = ToastNotification;
    </script>
