<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Voting System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-hover: #334155;
            --main-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--main-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(203, 213, 225, 0.1);
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 0.375rem;
            margin: 0 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .content-wrapper {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .page-subtitle {
            color: #64748b;
            margin-top: 0.25rem;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.75rem;
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-check2-square me-2"></i>
                Voting Admin
            </a>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    Analytics
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.elections.index') }}" class="nav-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    Elections
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.parties.index') }}" class="nav-link {{ request()->routeIs('admin.parties.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Parties
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.candidates.index') }}" class="nav-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    Candidates
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>

            <li class="nav-item mt-auto">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary sidebar-toggle me-3" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="me-2 text-end d-none d-sm-block">
                                <small class="text-muted d-block">Welcome back,</small>
                                <strong>{{ auth()->check() ? (auth()->user()->first_name ?? 'Admin') : 'Admin' }}</strong>
                            </div>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @hasSection('page-header')
                <div class="page-header">
                    @yield('page-header')
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>