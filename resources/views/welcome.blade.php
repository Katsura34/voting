<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School Voting System') }} - Democratic Digital Elections</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        
        .hero-section {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="%23ffffff" opacity="0.1"/></svg>') repeat;
            background-size: 50px 50px;
            animation: move 20s linear infinite;
        }
        
        @keyframes move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .btn-hero {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            margin: 0.5rem;
        }
        
        .btn-hero-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-hero-primary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-hero-outline:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .features-section {
            padding: 5rem 0;
            background: #f8fafc;
        }
        
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }
        
        .feature-icon-1 { background: var(--primary-gradient); }
        .feature-icon-2 { background: var(--secondary-gradient); }
        .feature-icon-3 { background: var(--accent-gradient); }
        
        .stats-section {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .cta-section {
            background: #1f2937;
            color: white;
            padding: 5rem 0;
            text-align: center;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-hero {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
                display: block;
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-elements">
            <div class="floating-element" style="width: 60px; height: 60px; top: 10%; left: 10%; animation-delay: 0s;"></div>
            <div class="floating-element" style="width: 40px; height: 40px; top: 20%; left: 80%; animation-delay: 2s;"></div>
            <div class="floating-element" style="width: 80px; height: 80px; top: 60%; left: 5%; animation-delay: 4s;"></div>
            <div class="floating-element" style="width: 50px; height: 50px; top: 70%; left: 85%; animation-delay: 1s;"></div>
            <div class="floating-element" style="width: 30px; height: 30px; top: 40%; left: 90%; animation-delay: 3s;"></div>
        </div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content" data-aos="fade-right">
                        <h1 class="hero-title">Democratic Digital Elections</h1>
                        <p class="hero-subtitle">
                            Secure, transparent, and accessible voting platform for educational institutions. 
                            Empowering students to participate in democratic processes with modern technology.
                        </p>
                        <div class="hero-buttons">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-hero-primary">
                                        <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-hero btn-hero-primary">
                                        <i class="bi bi-house me-2"></i>My Dashboard
                                    </a>
                                    <a href="{{ route('student.vote.index') }}" class="btn btn-hero btn-hero-outline">
                                        <i class="bi bi-ballot me-2"></i>Cast Your Vote
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-hero btn-hero-outline">
                                    <i class="bi bi-person-plus me-2"></i>Register
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center">
                        <div style="font-size: 15rem; opacity: 0.3;">
                            <i class="bi bi-box-ballot"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-4 fw-bold text-dark mb-3">Why Choose Our Platform?</h2>
                    <p class="lead text-muted">Built with security, transparency, and user experience in mind</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-1">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Secure & Private</h4>
                        <p class="text-muted mb-0">
                            Advanced security measures ensure your vote remains private and the election integrity is maintained throughout the process.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-2">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Transparent Results</h4>
                        <p class="text-muted mb-0">
                            Real-time vote counting with detailed analytics and comprehensive result reports that everyone can trust and verify.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-3">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Mobile Friendly</h4>
                        <p class="text-muted mb-0">
                            Fully responsive design works seamlessly on all devices - desktop, tablet, or mobile phone. Vote from anywhere.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <span class="stat-number"><i class="bi bi-people"></i></span>
                        <div class="stat-label">Student Participation</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <span class="stat-number"><i class="bi bi-shield-check"></i></span>
                        <div class="stat-label">Secure Elections</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <span class="stat-number"><i class="bi bi-clock"></i></span>
                        <div class="stat-label">Real-time Results</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <span class="stat-number"><i class="bi bi-award"></i></span>
                        <div class="stat-label">Fair & Democratic</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Ready to Start Voting?</h2>
                    <p class="lead mb-5">
                        Join thousands of students participating in democratic elections. 
                        Your voice matters, make it heard!
                    </p>
                    
                    @guest
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-outline">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        </div>
                    @else
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-hero btn-hero-primary">
                                    <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('student.vote.index') }}" class="btn btn-hero btn-hero-primary">
                                    <i class="bi bi-ballot me-2"></i>Cast Your Vote
                                </a>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-hero btn-hero-outline">
                                    <i class="bi bi-house me-2"></i>My Dashboard
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'School Voting System') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small text-muted">Empowering democratic participation through technology</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>