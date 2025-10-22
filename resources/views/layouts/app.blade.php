<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'School Voting System') }} - @yield('title', 'Dashboard')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @include('components.button-styles')

    <style>
        /* ACLC brand palette (blue/gold) */
        :root{
            --aclc-blue:#0033A0; /* primary */
            --aclc-gold:#FFC72C; /* accent */
            --aclc-blue-600:#0a2a73;
            --aclc-blue-500:#1746b5;
        }
        /* Fix vertical input text issue */
        form input, form textarea, form select{
            writing-mode: horizontal-tb !important;
            text-orientation: mixed !important;
            transform:none !important; rotate:0deg !important; white-space:normal !important;
        }
        /* Theming */
        body{background: linear-gradient(135deg,var(--aclc-blue) 0%, var(--aclc-blue-500) 100%);} 
        .navbar-brand{color:var(--aclc-gold)!important}
        .nav-link.active{background:var(--aclc-gold)!important;color:#111!important}
        .btn-primary{background:linear-gradient(135deg,var(--aclc-blue) 0%, var(--aclc-blue-600) 100%)!important}
        .btn-outline-primary{border-color:var(--aclc-blue)!important;color:var(--aclc-blue)!important}
        .btn-outline-primary:hover{background:var(--aclc-blue)!important;color:#fff!important}
        .badge.bg-warning{background-color:var(--aclc-gold)!important;color:#111!important}
    </style>
    
    @stack('styles')
</head>
<body>
    @php($home = auth()->check() ? (auth()->user()->role==='admin'?route('admin.dashboard'):route('student.dashboard')) : route('login'))
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" data-aos="fade-down" style="background:#ffffffee;backdrop-filter:blur(10px)">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ $home }}"><i class="bi bi-box-ballot me-2"></i>{{ config('app.name','School Voting System') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><i class="bi bi-list" style="font-size:1.5rem;color:var(--aclc-blue)"></i></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.parties.*')?'active':'' }}" href="{{ route('admin.parties.index') }}">Parties</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.elections.*')?'active':'' }}" href="{{ route('admin.elections.index') }}">Elections</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.candidates.*')?'active':'' }}" href="{{ route('admin.candidates.index') }}">Candidates</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.analytics')?'active':'' }}" href="{{ route('admin.analytics') }}">Analytics</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('student.dashboard')?'active':'' }}" href="{{ route('student.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('student.vote.*')?'active':'' }}" href="{{ route('student.vote.index') }}">Vote</a></li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;background:var(--aclc-gold);color:#111;font-weight:700">
                                    {{ strtoupper(substr(auth()->user()->first_name,0,1).substr(auth()->user()->last_name,0,1)) }}
                                </div>
                                <span>{{ auth()->user()->full_name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text text-muted small">{{ ucfirst(auth()->user()->role) }} • {{ auth()->user()->usn }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container" style="margin-top:80px; padding-bottom:100px;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert"><i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        <div class="main-content" data-aos="fade-up" style="background:#fff;border-radius:1rem;padding:2rem;box-shadow:0 4px 16px rgba(0,0,0,.08)">@yield('content')</div>
    </main>

    <footer class="text-white text-center py-4" style="background:var(--aclc-blue)">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">&copy; {{ date('Y') }} School Voting System • ACLC</p>
            <small class="text-warning">Empowering student democracy</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({duration:600,once:true});</script>
    @stack('scripts')
</body>
</html>