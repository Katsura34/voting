<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'School Voting System') }} - @yield('title', 'Authentication')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* ACLC colors */
        :root{ --aclc-blue:#0033A0; --aclc-gold:#FFC72C; }
        body{ background: radial-gradient(1200px 600px at 50% -200px, #6a7bdc 0%, #5a6bd0 20%, var(--aclc-blue) 60%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
        .auth-container{ width:100%; max-width:420px; padding:24px; }
        .auth-card{ background:#ffffffee; backdrop-filter:blur(12px); border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.15); padding:32px 28px; }
        .auth-logo{ width:84px; height:84px; margin:-70px auto 12px; border-radius:50%; background:linear-gradient(135deg,var(--aclc-gold), #ffd95a); display:flex; align-items:center; justify-content:center; color:#111; font-size:36px; font-weight:800; box-shadow:0 12px 30px rgba(255,199,44,.45); }
        .auth-title{ font-weight:800; color:#0f172a; text-align:center; }
        .auth-subtitle{ color:#64748b; text-align:center; margin-bottom:22px; }
        /* Fix vertical text on inputs */
        input, select, textarea{ writing-mode: horizontal-tb !important; transform:none !important; rotate:0deg !important; white-space:normal !important; }
        .form-control, .form-select{ padding:.8rem 1rem; border-radius:12px; border:2px solid #e5e7eb; }
        .form-control:focus, .form-select:focus{ border-color: var(--aclc-blue); box-shadow:0 0 0 .2rem rgba(0,51,160,.15); }
        .btn-primary{ background:linear-gradient(135deg,var(--aclc-blue), #0a2a73); border:none; }
        .btn-primary:hover{ background:linear-gradient(135deg,#0a2a73, #001a66); box-shadow:0 10px 24px rgba(0,51,160,.35); }
        .auth-links a{ color:var(--aclc-blue); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up">
            <div class="auth-logo"><i class="bi bi-box-ballot"></i></div>
            <h1 class="auth-title">@yield('title','Login')</h1>
            <p class="auth-subtitle">@yield('subtitle','Please sign in to continue')</p>

            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>There were some problems with your input.</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
        <div class="text-center mt-4 text-white-50">
            <small>&copy; {{ date('Y') }} School Voting System • ACLC</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({duration:600,once:true});</script>
    @stack('scripts')
</body>
</html>
