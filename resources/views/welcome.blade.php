<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder | Find Your Dream Job</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Google Fonts - Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #FF6B35;
            --primary-dark: #E85A2A;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --background-light: #F7F9FC;
            --white: #FFFFFF;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand-custom:hover .brand-icon {
            transform: rotate(-5deg) scale(1.05);
        }

        .brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link-custom {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 0 0.25rem;
        }

        .nav-link-custom:hover {
            color: var(--primary-color);
            background: rgba(255, 107, 53, 0.1);
        }

        .btn-nav-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        /* .btn-nav-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
            color: white ;
        } */

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 50%, #4ECDC4 100%);
            padding: 100px 0 120px;
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
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.35rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary-color);
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
            color: white !important;
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
        }

        .feature-card {
            padding: 2.5rem;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            background: white;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }

        .feature-card:nth-child(3n+1):hover {
            background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
            border-color: var(--primary-color);
        }

        .feature-card:nth-child(3n+2):hover {
            background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
            border-color: var(--secondary-color);
        }

        .feature-card:nth-child(3n+3):hover {
            background: linear-gradient(135deg, #FFFBF0 0%, #FFFFFF 100%);
            border-color: var(--accent-color);
        }

        .feature-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:nth-child(3n+1) .feature-icon-wrapper {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
        }

        .feature-card:nth-child(3n+2) .feature-icon-wrapper {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
        }

        .feature-card:nth-child(3n+3) .feature-icon-wrapper {
            background: linear-gradient(135deg, rgba(255, 230, 109, 0.15), rgba(255, 230, 109, 0.25));
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.1) rotate(-5deg);
        }

        .feature-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Jobs Section */
        .jobs-section {
            padding: 80px 0;
            background: var(--background-light);
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .job-card {
            background: white;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transition: height 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }

        .job-card:hover::before {
            height: 100%;
        }

        .job-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .job-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .job-meta {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .job-meta-item {
            display: flex;
            align-items: center;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .job-meta-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .job-type-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
            border: 1px solid rgba(78, 205, 196, 0.3);
        }

        .btn-view-job {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .btn-view-job:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-view-all {
            background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
            color: white;
            padding: 1rem 3rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
        }

        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(78, 205, 196, 0.4);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-muted);
            opacity: 0.5;
            margin-bottom: 1.5rem;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 1.15rem;
        }

        /* Footer */
        .footer-custom {
            background: linear-gradient(135deg, var(--text-dark) 0%, #1A202C 100%);
            color: white;
            padding: 3rem 0;
        }

        .footer-content {
            text-align: center;
        }

        .footer-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content {
            animation: fadeInUp 0.8s ease-out;
        }

        .feature-card,
        .job-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .feature-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .feature-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.15rem;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                padding: 0.85rem 2rem;
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .brand-text {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand-custom" href="/">
                <div class="brand-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <span class="brand-text">JobFinder</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link-custom" href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door me-1"></i>Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link-custom" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-nav-primary" href="{{ route('register') }}">
                                Get Started
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">Find Your Dream Job Today</h1>
                <p class="hero-subtitle">
                    Connecting talented job seekers with amazing employers in one smart platform.
                </p>

                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-hero-primary">
                        <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                    </a>
                    <a href="#jobs" class="btn btn-hero-secondary">
                        <i class="bi bi-search me-2"></i>Browse Jobs
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose JobFinder?</h2>
                <p class="section-subtitle">Everything you need to land your perfect job</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-search"></i>
                        </div>
                        <h5 class="feature-title">Smart Job Search</h5>
                        <p class="feature-description">
                            Easily find jobs that match your skills, experience, and location preferences with our intelligent search system.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h5 class="feature-title">One-Click Apply</h5>
                        <p class="feature-description">
                            Upload your resume once and apply to multiple jobs with just one click. Quick, easy, and efficient.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="feature-title">Track Progress</h5>
                        <p class="feature-description">
                            Monitor your application status in real-time and get instant notifications about updates.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JOB LIST PREVIEW --}}
    <section id="jobs" class="jobs-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Latest Job Openings</h2>
                <p class="section-subtitle">Start your career journey with top companies</p>
            </div>

            <div class="row g-4">
                @forelse($latestJobs as $job)
                    <div class="col-lg-4 col-md-6">
                        <div class="job-card">
                            <h5 class="job-title">{{ $job->job_title }}</h5>
                            
                            <p class="job-description">
                                {{ \Illuminate\Support\Str::limit($job->job_description, 120) }}
                            </p>

                            <div class="job-meta">
                                <div class="job-meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="bi bi-clock-fill"></i>
                                    <span class="job-type-badge">{{ ucfirst($job->job_type) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('jobs.show', $job->id) }}" class="btn-view-job">
                                <i class="bi bi-arrow-right-circle me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <p class="empty-text">No job openings available at the moment. Check back soon!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($latestJobs->count() > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('jobs.index') }}" class="btn btn-view-all">
                        <i class="bi bi-grid-3x3-gap me-2"></i>View All Jobs
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer-custom">
        <div class="container">
            <div class="footer-content">
                <h5 class="footer-title">
                    <i class="bi bi-briefcase-fill me-2"></i>JobFinder
                </h5>
                <p class="footer-text mb-2">&copy; {{ date('Y') }} JobFinder. All rights reserved.</p>
                <small class="footer-text">
                    Built with <i class="bi bi-heart-fill text-danger"></i> for connecting talent with opportunity
                </small>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>