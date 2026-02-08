<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder | Available Jobs</title>
    
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
            background-color: var(--background-light);
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

        .btn-nav {
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-nav-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .btn-nav-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-nav-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-nav-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
            padding: 4rem 0 5rem;
            margin-bottom: -3rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .page-title {
            font-size: 2.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .page-subtitle {
            font-size: 1.25rem;
            opacity: 0.95;
            font-weight: 500;
        }

        /* Jobs Container */
        .jobs-container {
            padding: 4rem 0 3rem;
        }

        /* Job Card */
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

        .job-card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
        }

        .job-meta-section {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .job-meta-item {
            display: flex;
            align-items: center;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .job-meta-item i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            font-size: 1.1rem;
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

        .job-salary {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(255, 230, 109, 0.15), rgba(255, 230, 109, 0.25));
            color: #D97706;
            border: 1px solid rgba(255, 230, 109, 0.3);
        }

        .job-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .job-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: auto;
        }

        .btn-apply {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.85rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            width: 100%;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-apply:disabled,
        .btn-applied {
            background: linear-gradient(135deg, #95E1D3, #7DD8C8);
            color: #0F6848;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-view-details {
            background: transparent;
            color: var(--text-dark);
            padding: 0.85rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .btn-view-details:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            background: rgba(78, 205, 196, 0.08);
            transform: translateY(-2px);
        }

        .alert-profile-warning {
            background: linear-gradient(135deg, #FFF4E6 0%, #FFE8CC 100%);
            color: #D97706;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 2px solid #FFB84D;
            text-align: center;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--text-muted);
            opacity: 0.5;
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        /* Pagination */
        .pagination {
            gap: 0.5rem;
        }

        .page-link {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-dark);
            font-weight: 600;
            padding: 0.65rem 1rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
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

        .job-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .job-card:nth-child(2n) {
            animation-delay: 0.1s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1.05rem;
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
            <a class="navbar-brand-custom" href="{{ route('home') }}">
                <div class="brand-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <span class="brand-text">JobFinder</span>
            </a>

            <div class="ms-auto d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-nav btn-nav-outline">
                        <i class="bi bi-house-door me-1"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav btn-nav-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- PAGE HEADER --}}
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title">
                    <i class="bi bi-fire me-2"></i>Available Job Openings
                </h1>
                <p class="page-subtitle">
                    Browse and apply to jobs that match your skills and aspirations
                </p>
            </div>
        </div>
    </section>

    {{-- JOBS CONTAINER --}}
    <div class="container jobs-container">
        <div class="row g-4">
            @forelse($jobs as $job)
                <div class="col-lg-6">
                    <div class="job-card">
                        <h5 class="job-card-title">{{ $job->job_title }}</h5>
                        
                        <div class="job-meta-section">
                            <div class="job-meta-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>{{ $job->location }}</span>
                            </div>
                            <div class="job-meta-item">
                                <i class="bi bi-clock-fill"></i>
                                <span class="job-type-badge">{{ ucfirst($job->job_type) }}</span>
                            </div>
                            @if($job->salary)
                                <div class="job-meta-item">
                                    <i class="bi bi-cash-coin"></i>
                                    <span class="job-salary">₱{{ number_format($job->salary) }}</span>
                                </div>
                            @endif
                        </div>

                        <p class="job-description">
                            {{ \Illuminate\Support\Str::limit($job->job_description, 150) }}
                        </p>

                        <div class="job-actions">
                            {{-- APPLY BUTTON --}}
                            @auth
                                @php
                                    $user = auth()->user();
                                    $jobSeeker = $user->jobSeeker;
                                    $alreadyApplied = $jobSeeker
                                        ? $job->applications()
                                            ->where('job_seeker_id', $jobSeeker->id)
                                            ->exists()
                                        : false;
                                @endphp

                                @if(!$jobSeeker)
                                    <div class="alert-profile-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Create Job Seeker profile to apply
                                    </div>
                                @elseif($alreadyApplied)
                                    <button class="btn-apply btn-applied" disabled>
                                        <i class="bi bi-check-circle-fill me-2"></i>Already Applied
                                    </button>
                                @else
                                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-apply">
                                            <i class="bi bi-send-fill me-2"></i>Apply Now
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-apply">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Apply
                                </a>
                            @endauth

                            {{-- VIEW DETAILS --}}
                            <a href="{{ route('jobs.show', $job->id) }}" class="btn-view-details">
                                <i class="bi bi-eye me-2"></i>View Full Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h3 class="empty-title">No Jobs Available</h3>
                        <p class="empty-text">There are no job openings at the moment. Check back soon for new opportunities!</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($jobs->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $jobs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>