<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim(($title ?? '') . ' – AttendTrack') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{
            font-family:'Inter',sans-serif;
            background:#0f172a;
            color:#f1f5f9;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2rem 1rem;
        }
        /* Animated gradient blob */
        body::before{
            content:'';
            position:fixed;inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(99,102,241,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(139,92,246,.14) 0%, transparent 60%);
            pointer-events:none;z-index:0;
        }
        .auth-card{
            position:relative;z-index:1;
            width:100%;max-width:440px;
            background:#1e293b;
            border:1px solid #334155;
            border-radius:18px;
            padding:2.5rem 2.25rem;
            box-shadow:0 25px 60px rgba(0,0,0,.4);
        }
        .auth-logo{
            display:flex;align-items:center;gap:.75rem;
            margin-bottom:2rem;justify-content:center;
        }
        .auth-logo-icon{
            width:44px;height:44px;border-radius:12px;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            display:flex;align-items:center;justify-content:center;font-size:1.3rem;
        }
        .auth-logo-text{font-size:1.2rem;font-weight:800;color:#f1f5f9;}
        .auth-logo-sub{font-size:.7rem;color:#94a3b8;font-weight:400;}
        .auth-title{font-size:1.05rem;font-weight:700;color:#f1f5f9;margin-bottom:.3rem;}
        .auth-sub{font-size:.82rem;color:#94a3b8;margin-bottom:1.75rem;}

        .form-group{margin-bottom:1.1rem;}
        .form-label{display:block;font-size:.75rem;font-weight:600;color:#94a3b8;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em;}
        .form-control{
            width:100%;padding:.72rem 1rem;
            background:#0f172a;border:1px solid #334155;border-radius:8px;
            color:#f1f5f9;font-size:.88rem;font-family:inherit;
            transition:border-color .2s,box-shadow .2s;
        }
        .form-control:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.2);}
        .form-control::placeholder{color:#475569;}
        .form-error{font-size:.75rem;color:#fca5a5;margin-top:.3rem;}

        .btn-auth{
            width:100%;padding:.8rem;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            color:#fff;font-size:.9rem;font-weight:700;
            border:none;border-radius:8px;cursor:pointer;
            transition:all .2s;margin-top:.5rem;
            font-family:inherit;
        }
        .btn-auth:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(99,102,241,.45);}
        .auth-link{color:#a5b4fc;text-decoration:none;font-size:.82rem;font-weight:500;}
        .auth-link:hover{color:#c7d2fe;text-decoration:underline;}
        .auth-footer{text-align:center;margin-top:1.25rem;color:#94a3b8;font-size:.82rem;}
        .divider{border:none;border-top:1px solid #334155;margin:1.25rem 0;}

        /* Checkbox */
        .checkbox-wrap{display:flex;align-items:center;gap:.6rem;}
        .checkbox-wrap input[type=checkbox]{
            width:16px;height:16px;accent-color:#6366f1;cursor:pointer;
        }
        .checkbox-wrap label{font-size:.82rem;color:#94a3b8;cursor:pointer;}

        /* Status / error banner */
        .status-banner{
            padding:.8rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.82rem;
        }
        .status-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;}
        .status-error{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#fca5a5;}
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">📋</div>
            <div>
                <div class="auth-logo-text">AttendTrack</div>
                <div class="auth-logo-sub">Student Attendance System</div>
            </div>
        </div>
        {{ $slot }}
    </div>
</body>
</html>
