<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'School Voting System') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        /* Enhanced Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .navbar-brand {
            color: #667eea !important;
            font-weight: 700;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            color: #764ba2 !important;
        }
        
        .navbar-nav .nav-link {
            color: #64748b !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
            transform: translateY(-1px);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow-hover);
            border-radius: 0.75rem;
        }
        
        /* Main Content Area */
        .main-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            margin: 2rem 0;
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }
        
        /* Enhanced Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 1rem 1rem 0 0 !important;
        }
        
        /* Enhanced Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
        }
        
        /* Enhanced Alerts */
        .alert {
            border: none;
            border-radius: 0.75rem;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease-out;
        }
        
        .alert-success {
            background: rgba(17, 153, 142, 0.1);
            color: #0d9488;
            border-left: 4px solid #10b981;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }
        
        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
            border-left: 4px solid #3b82f6;
        }
        
        /* Badge Enhancements */
        .badge {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }
        
        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.8) !important;
            backdrop-filter: blur(10px);
        }
        
        /* Loading Animation */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        
        /* Custom Animations */
        @keyframes slideInDown {
            from {
                transform: translate3d(0, -100%, 0);
                visibility: visible;
            }
            to {
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .main-content {
                margin: 1rem 0;
                padding: 1rem;
                border-radius: 0.5rem;
            }
            
            .card {
                border-radius: 0.75rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    
    <!-- Enhanced Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" data-aos="fade-down">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->check() ? route('student.dashboard') : route('login')) }}">
                <i class="bi bi-box-ballot me-2"></i>
                {{ config('app.name', 'School Voting System') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    style="border: none; box-shadow: none;">
                <i class="bi bi-list" style="font-size: 1.5rem; color: #667eea;"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.parties.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.parties.index') }}">
                                    <i class="bi bi-people-fill me-1"></i>Parties
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.elections.index') }}">
                                    <i class="bi bi-calendar-event me-1"></i>Elections
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" 
                                   href="{{ route('admin.analytics') }}">
                                    <i class="bi bi-bar-chart me-1"></i>Analytics
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-house me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.vote.*') ? 'active' : '' }}" 
                                   href="{{ route('student.vote.index') }}">
                                    <i class="bi bi-ballot me-1"></i>Vote
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px; color: white; font-weight: bold; font-size: 0.875rem;">
                                    {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                </div>
                                <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text text-muted small">{{ ucfirst(auth()->user()->role) }} • {{ auth()->user()->usn }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container" style="margin-top: 80px; padding-bottom: 100px;">
        <!-- Enhanced Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="slide-down">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="slide-down">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" data-aos="slide-down">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Info:</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="main-content" data-aos="fade-up">
            @yield('content')
        </div>
    </main>
    
    <!-- Enhanced Footer -->
    <footer class="text-white text-center py-4 fixed-bottom" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} School Voting System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <small class="text-muted">Built with ❤️ for democratic participation</small>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Enhanced JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true,
            offset: 50
        });
        
        // Loading spinner functionality
        function showLoading() {
            document.querySelector('.loading-spinner').style.display = 'block';
        }
        
        function hideLoading() {
            document.querySelector('.loading-spinner').style.display = 'none';
        }
        
        // Show loading on form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', showLoading);
            });
            
            // Hide loading after page load
            window.addEventListener('load', hideLoading);
        });
        
        // Enhanced alert auto-dismiss
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });
        
        // Smooth scrolling for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add click effect to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>
    
    <!-- Add ripple effect CSS -->
    <style>
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
    
    @stack('scripts')
</body>
</html>