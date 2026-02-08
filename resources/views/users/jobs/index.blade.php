@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
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
            
            {{-- BACK BUTTON --}}
            <div class="mb-4">
                <a href="{{ route('dashboard')}}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            {{-- SUCCESS/ERROR MESSAGES --}}
            @if(session('success'))
                <div class="alert alert-custom alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-custom alert-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- SEARCH BAR --}}
            <div class="search-section mb-4">
                <form method="GET" action="{{ route('users.jobs.index') }}" class="search-form">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input 
                            type="text" 
                            name="search" 
                            class="search-input" 
                            placeholder="Search by job title, location, or description..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        @if(request('search'))
                            <a href="{{ route('users.jobs.index') }}" class="clear-search" title="Clear search">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn-search-submit">
                            <i class="bi bi-search me-2"></i>Search Jobs
                        </button>
                    </div>
                </form>

                {{-- SEARCH RESULTS INFO --}}
                @if(request('search'))
                    <div class="search-results-info">
                        <i class="bi bi-funnel-fill me-2"></i>
                        <span>Showing results for: <strong>"{{ request('search') }}"</strong></span>
                        <span class="results-count">({{ $jobs->total() }} {{ Str::plural('result', $jobs->total()) }})</span>
                    </div>
                @endif
            </div>

            <div class="row g-4">
                @forelse($jobs as $job)
                    <div class="col-lg-6">
                        <div class="job-card">
                            {{-- Job Title --}}
                            <h5 class="job-card-title">{{ $job->job_title }}</h5>

                            {{-- Job Meta --}}
                            <div class="job-meta-section">
                                <div class="job-meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $job->location }}</span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="bi bi-clock-fill"></i>
                                    <span class="job-type-badge">{{ ucfirst($job->job_type) }}</span>
                                </div>
                                @if ($job->salary)
                                    <div class="job-meta-item">
                                        <i class="bi bi-cash-coin"></i>
                                        <span class="job-salary">₱{{ number_format($job->salary) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Job Description --}}
                            <p class="job-description">
                                {{ \Illuminate\Support\Str::limit($job->job_description, 150) }}
                            </p>

                            {{-- Application Status Badge --}}
                            @auth
                                @php
                                    $user = auth()->user();
                                    $jobSeeker = $user->jobSeeker;
                                    $alreadyApplied = $jobSeeker
                                        ? $job->applications()->where('job_seeker_id', $jobSeeker->id)->exists()
                                        : false;
                                @endphp

                                @if($alreadyApplied)
                                    <div class="application-status-badge mb-3">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        You've already applied to this job
                                    </div>
                                @endif
                            @endauth

                            {{-- Job Actions --}}
                            <div class="job-actions">
                                {{-- VIEW DETAILS BUTTON (Primary Action) --}}
                                <a href="{{ route('users.jobs.show', $job->id) }}" class="btn-view-full-details">
                                    <i class="bi bi-eye-fill me-2"></i>View Details & Apply
                                </a>

                                {{-- Quick Info Link --}}
                                @if($job->templates->count() > 0)
                                    <div class="quick-info-badge">
                                        <i class="bi bi-file-earmark-text me-1"></i>
                                        {{ $job->templates->count() }} application {{ Str::plural('template', $job->templates->count()) }} available
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            @if(request('search'))
                                <h3 class="empty-title">No Jobs Found</h3>
                                <p class="empty-text">
                                    No jobs match your search for "<strong>{{ request('search') }}</strong>".
                                    <br>Try different keywords or <a href="{{ route('users.jobs.index') }}" class="text-primary">browse all jobs</a>.
                                </p>
                            @else
                                <h3 class="empty-title">No Jobs Available</h3>
                                <p class="empty-text">There are no job openings at the moment. Check back soon for new opportunities!</p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if ($jobs->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $jobs->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        
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

            /* Alerts */
            .alert-custom {
                border-radius: 12px;
                border: none;
                padding: 1rem 1.25rem;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                animation: slideDown 0.3s ease-out;
                display: flex;
                align-items: center;
            }

            .alert-success {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
                color: #0F6848;
            }

            .alert-danger {
                background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
                color: #C92A2A;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
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

            /* Back Button */
          .btn-back {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                text-decoration: none;
            }

            .btn-back:hover {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
                transform: translateX(-4px);
            }

            .btn-back i {
                transition: transform 0.3s ease;
            }

            .btn-back:hover i {
                transform: translateX(-3px);
            }

            /* Search Section */
            .search-section {
                background: white;
                padding: 1.5rem;
                border-radius: 16px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
                margin-bottom: 2rem;
            }

            .search-form {
                margin-bottom: 0;
            }

            .search-input-wrapper {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                position: relative;
            }

            .search-icon {
                position: absolute;
                left: 1.25rem;
                color: var(--text-muted);
                font-size: 1.15rem;
                z-index: 2;
                pointer-events: none;
            }

            .search-input {
                flex: 1;
                padding: 0.95rem 3rem 0.95rem 3rem;
                border: 2px solid var(--border-color);
                border-radius: 12px;
                font-size: 0.95rem;
                font-family: 'Outfit', sans-serif;
                transition: all 0.3s ease;
            }

            .search-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .clear-search {
                position: absolute;
                right: 200px;
                color: var(--text-muted);
                font-size: 1.25rem;
                cursor: pointer;
                transition: all 0.2s ease;
                z-index: 2;
                text-decoration: none;
            }

            .clear-search:hover {
                color: var(--primary-color);
                transform: scale(1.1);
            }

            .btn-search-submit {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 0.95rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                white-space: nowrap;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .btn-search-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
            }

            .search-results-info {
                margin-top: 1rem;
                padding: 0.75rem 1rem;
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.08), rgba(78, 205, 196, 0.15));
                border-left: 4px solid var(--secondary-color);
                border-radius: 8px;
                color: var(--text-dark);
                font-size: 0.95rem;
                display: flex;
                align-items: center;
            }

            .search-results-info i {
                color: var(--secondary-color);
            }

            .search-results-info strong {
                color: var(--primary-color);
            }

            .results-count {
                margin-left: 0.5rem;
                color: var(--text-muted);
                font-weight: 600;
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

            /* Application Status Badge */
            .application-status-badge {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
                color: #0F6848;
                padding: 0.75rem 1rem;
                border-radius: 10px;
                font-size: 0.875rem;
                font-weight: 600;
                border: 2px solid rgba(15, 104, 72, 0.2);
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .job-actions {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                margin-top: auto;
            }

            .btn-view-full-details {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                font-weight: 700;
                border: none;
                transition: all 0.3s ease;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
                width: 100%;
                font-size: 1.05rem;
            }

            .btn-view-full-details:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
                color: white;
            }

            .quick-info-badge {
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.08), rgba(78, 205, 196, 0.15));
                color: var(--secondary-color);
                padding: 0.65rem 1rem;
                border-radius: 8px;
                font-size: 0.85rem;
                font-weight: 600;
                text-align: center;
                border: 2px solid rgba(78, 205, 196, 0.2);
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

            .empty-text a {
                font-weight: 600;
                text-decoration: none;
            }

            .empty-text a:hover {
                text-decoration: underline;
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

            .search-section {
                animation: fadeInUp 0.5s ease-out;
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

                .search-input-wrapper {
                    flex-direction: column;
                }

                .search-input {
                    width: 100%;
                    padding-right: 2.5rem;
                }

                .clear-search {
                    right: 1rem;
                    top: 1rem;
                }

                .btn-search-submit {
                    width: 100%;
                }
            }
        </style>
    @endsection
@endcan