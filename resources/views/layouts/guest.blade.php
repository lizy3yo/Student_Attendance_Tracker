<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim(($title ?? '') . ' – Attendly') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        :root{
            --primary:#22c55e;
            --primary-dark:#16a34a;
            --bg-body:#f3f4f6;
            --surface:#ffffff;
            --text-main:#111827;
            --text-muted:#6b7280;
            --border-color:#e5e7eb;
            --shadow-card:0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);
            --header-bg:#111827;
        }
        body{
            font-family:'Inter',sans-serif;
            background:var(--bg-body);
            color:var(--text-main);
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
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(34,197,94,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(22,163,74,.12) 0%, transparent 60%);
            pointer-events:none;
            z-index:0;
        }
        .auth-card{
            position:relative;z-index:1;
            width:100%;max-width:440px;
            background:var(--surface);
            border:1px solid var(--border-color);
            border-radius:18px;
            padding:2.5rem 2.25rem;
            box-shadow:var(--shadow-card);
        }
        .auth-logo{
            display:flex;align-items:center;gap:.75rem;
            margin-bottom:2rem;justify-content:center;
        }
        .auth-logo-icon{
            width:44px;height:44px;border-radius:12px;
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            display:flex;align-items:center;justify-content:center;font-size:1.3rem;
            color:#fff;
        }
        .auth-logo-text{font-size:1.2rem;font-weight:800;color:var(--text-main);}
        .auth-logo-sub{font-size:.7rem;color:var(--text-muted);font-weight:500;}
        .auth-title{font-size:1.05rem;font-weight:800;color:var(--text-main);margin-bottom:.3rem;text-align:center;}
        .auth-sub{font-size:.82rem;color:var(--text-muted);margin-bottom:1.75rem;line-height:1.4;text-align:center;}

        .form-group{margin-bottom:1.1rem;}
        .form-label{display:block;font-size:.75rem;font-weight:600;color:var(--text-muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.05em;}
        .form-control{
            width:100%;padding:.72rem 1rem;
            background:var(--surface);
            border:1px solid var(--border-color);
            border-radius:8px;
            color:var(--text-main);
            font-size:.88rem;font-family:inherit;
            transition:border-color .2s,box-shadow .2s, transform .05s ease-in-out;
        }
        .form-control:focus{outline:none;border-color:var(--primary-dark);box-shadow:0 0 0 3px rgba(34,197,94,.22);}
        .form-control::placeholder{color:#94a3b8;}
        .form-error{font-size:.75rem;color:#dc2626;margin-top:.3rem;}

        .btn-auth{
            width:100%;padding:.8rem;
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            color:#fff;font-size:.9rem;font-weight:800;
            border:none;border-radius:10px;cursor:pointer;
            transition:all .2s;
            margin-top:.5rem;
            font-family:inherit;
        }
        .btn-auth:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(34,197,94,.35);}
        .auth-link{color:var(--primary-dark);text-decoration:none;font-size:.82rem;font-weight:700;}
        .auth-link:hover{color:#0f7a34;text-decoration:underline;}
        .auth-footer{text-align:center;margin-top:1.25rem;color:var(--text-muted);font-size:.82rem;}
        .divider{border:none;border-top:1px solid #334155;margin:1.25rem 0;}

        /* Checkbox */
        .checkbox-wrap{display:flex;align-items:center;gap:.6rem;}
        .checkbox-wrap input[type=checkbox]{
            width:16px;height:16px;accent-color:#6366f1;cursor:pointer;
        }
        .checkbox-wrap input[type=checkbox]{ accent-color: var(--primary); }
        .checkbox-wrap label{font-size:.82rem;color:var(--text-muted);cursor:pointer;}

        /* Status / error banner */
        .status-banner{
            padding:.8rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.82rem;
        }
        .status-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#047857;}
        .status-error{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#991b1b;}
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon"><i data-lucide="clipboard-check" data-size="20"></i></div>
            <div>
                <div class="auth-logo-text">Attendly</div>
                <div class="auth-logo-sub">Student Attendance Tracker</div>
            </div>
        </div>
        {{ $slot }}
    </div>
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
