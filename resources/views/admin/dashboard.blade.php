@can('admin-access')
    @extends('layouts.Admin.app')

    @section('content')
    <div class="admin-dashboard-wrapper">
        <div class="container-fluid px-4 py-5">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="h3 fw-bold mb-1 header-title">Admin Dashboard</h1>
                    <p class="header-subtitle mb-0">Welcome back! Here's what's happening on JobFinder</p>
                </div>
                <div class="header-date">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ date('F d, Y') }}
                </div>
            </div>

            {{-- MAIN STATS GRID --}}
            <div class="row g-4 mb-5">
                {{-- Total Users --}}
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-users">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon-wrap icon-users">
                                    <i class="bi bi-people-fill fs-3"></i>
                                </div>
                                <div class="stat-trend trend-up">
                                    <i class="bi bi-arrow-up"></i> 12%
                                </div>
                            </div>
                            <h3 class="stat-number mb-1">{{ $totalUsers ?? 0 }}</h3>
                            <p class="stat-label mb-0">Total Users</p>
                        </div>
                    </div>
                </div>

                {{-- Total Jobs --}}
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-jobs">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon-wrap icon-jobs">
                                    <i class="bi bi-briefcase-fill fs-3"></i>
                                </div>
                                <div class="stat-trend trend-up">
                                    <i class="bi bi-arrow-up"></i> 8%
                                </div>
                            </div>
                            <h3 class="stat-number mb-1">{{ $totalJobs ?? 0 }}</h3>
                            <p class="stat-label mb-0">Active Jobs</p>
                        </div>
                    </div>
                </div>

                {{-- Total Applications --}}
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-applications">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon-wrap icon-applications">
                                    <i class="bi bi-file-earmark-text-fill fs-3"></i>
                                </div>
                                <div class="stat-trend trend-up">
                                    <i class="bi bi-arrow-up"></i> 23%
                                </div>
                            </div>
                            <h3 class="stat-number mb-1">{{ $totalApplications ?? 0 }}</h3>
                            <p class="stat-label mb-0">Applications</p>
                        </div>
                    </div>
                </div>

                {{-- Total Employers --}}
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card stat-employers">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon-wrap icon-employers">
                                    <i class="bi bi-building-fill fs-3"></i>
                                </div>
                                <div class="stat-trend trend-up">
                                    <i class="bi bi-arrow-up"></i> 5%
                                </div>
                            </div>
                            <h3 class="stat-number mb-1">{{ $totalEmployers ?? 0 }}</h3>
                            <p class="stat-label mb-0">Employers</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TWO COLUMN LAYOUT --}}
            <div class="row g-4 mb-5">
                
                {{-- QUICK STATS --}}
                <div class="col-lg-4">
                    <div class="quick-stats-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-lightning-fill me-2"></i>Quick Stats
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="quick-stat-item">
                                <div class="quick-stat-icon bg-pending">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="quick-stat-content">
                                    <span class="quick-stat-number">{{ $pendingApplications ?? 0 }}</span>
                                    <span class="quick-stat-label">Pending Applications</span>
                                </div>
                            </div>

                            <div class="quick-stat-item">
                                <div class="quick-stat-icon bg-accepted">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="quick-stat-content">
                                    <span class="quick-stat-number">{{ $acceptedApplications ?? 0 }}</span>
                                    <span class="quick-stat-label">Accepted Applications</span>
                                </div>
                            </div>

                            <div class="quick-stat-item">
                                <div class="quick-stat-icon bg-active">
                                    <i class="bi bi-person-check-fill"></i>
                                </div>
                                <div class="quick-stat-content">
                                    <span class="quick-stat-number">{{ $activeJobSeekers ?? 0 }}</span>
                                    <span class="quick-stat-label">Active Job Seekers</span>
                                </div>
                            </div>

                            <div class="quick-stat-item mb-0">
                                <div class="quick-stat-icon bg-today">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </div>
                                <div class="quick-stat-content">
                                    <span class="quick-stat-number">{{ $todayApplications ?? 0 }}</span>
                                    <span class="quick-stat-label">Applications Today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="col-lg-8">
                    <div class="activity-card">
                        <div class="card-header-custom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">
                                    <i class="bi bi-activity me-2"></i>Recent Activity
                                </h5>
                                <a href="{{ route('admin.users.index') }}" class="btn-header-link">View All</a>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            @if(isset($recentUsers) && count($recentUsers) > 0)
                                <div class="activity-list">
                                    @foreach($recentUsers as $user)
                                        <div class="activity-item">
                                            <div class="activity-avatar">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="activity-content">
                                                <p class="activity-text">
                                                    <strong>{{ $user->name }}</strong> registered as 
                                                    @php
                                                        $role = $user->roles()->first();
                                                    @endphp
                                                    @if($role)
                                                        <span class="activity-role {{ $role->name }}">{{ ucfirst($role->name) }}</span>
                                                    @endif
                                                </p>
                                                <span class="activity-time">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $user->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-activity">
                                    <i class="bi bi-inbox"></i>
                                    <p>No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RECENT JOBS & REPORTS --}}
            <div class="row g-4">
                
                {{-- RECENT JOBS --}}
                <div class="col-lg-6">
                    <div class="jobs-card">
                        <div class="card-header-custom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">
                                    <i class="bi bi-briefcase me-2"></i>Recent Jobs Posted
                                </h5>
                                <a href="#" class="btn-header-link">View All</a>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            @if(isset($recentJobs) && count($recentJobs) > 0)
                                @foreach($recentJobs as $job)
                                    <div class="job-item">
                                        <div class="job-icon">
                                            <i class="bi bi-briefcase-fill"></i>
                                        </div>
                                        <div class="job-content">
                                            <h6 class="job-title">{{ $job->job_title }}</h6>
                                            <p class="job-meta">
                                                <i class="bi bi-geo-alt-fill"></i> {{ $job->location }}
                                                <span class="job-divider">•</span>
                                                <span class="job-type">{{ ucfirst($job->job_type) }}</span>
                                            </p>
                                        </div>
                                        <div class="job-badge">
                                            <i class="bi bi-people-fill"></i>
                                            {{ $job->applications()->count() }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-activity">
                                    <i class="bi bi-inbox"></i>
                                    <p>No jobs posted yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SYSTEM REPORTS --}}
                <div class="col-lg-6">
                    <div class="reports-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>System Reports
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="report-item">
                                <div class="report-icon">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                </div>
                                <div class="report-content">
                                    <h6 class="report-title">User Analytics Report</h6>
                                    <p class="report-desc">Monthly user registration trends</p>
                                </div>
                                <button class="btn btn-report">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>

                            <div class="report-item">
                                <div class="report-icon">
                                    <i class="bi bi-file-earmark-spreadsheet"></i>
                                </div>
                                <div class="report-content">
                                    <h6 class="report-title">Job Posting Report</h6>
                                    <p class="report-desc">Jobs posted by category and location</p>
                                </div>
                                <button class="btn btn-report">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>

                            <div class="report-item">
                                <div class="report-icon">
                                    <i class="bi bi-file-earmark-check"></i>
                                </div>
                                <div class="report-content">
                                    <h6 class="report-title">Application Success Rate</h6>
                                    <p class="report-desc">Acceptance vs rejection statistics</p>
                                </div>
                                <button class="btn btn-report">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>

                            <div class="report-item mb-0">
                                <div class="report-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="report-content">
                                    <h6 class="report-title">Platform Activity Log</h6>
                                    <p class="report-desc">Complete system activity history</p>
                                </div>
                                <button class="btn btn-report">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

        :root {
            --primary-color: #FF6B35;
            --primary-dark: #E85A2A;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --background-light: #F7F9FC;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        .admin-dashboard-wrapper {
            min-height: 100vh;
            background: var(--background-light);
        }

        /* Header */
        .header-title {
            color: var(--text-dark);
        }

        .header-subtitle {
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-date {
            background: white;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--border-color);
        }

        .header-date i {
            color: var(--primary-color);
        }

        /* Main Stat Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-card:hover::before {
            transform: translate(10%, -10%) scale(1.2);
        }

        .stat-users {
            background: linear-gradient(135deg, #F0F4FF 0%, #FFFFFF 100%);
            border-left: 4px solid #6366F1;
        }

        .stat-users::before {
            background: #6366F1;
        }

        .stat-jobs {
            background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
            border-left: 4px solid var(--primary-color);
        }

        .stat-jobs::before {
            background: var(--primary-color);
        }

        .stat-applications {
            background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
            border-left: 4px solid var(--secondary-color);
        }

        .stat-applications::before {
            background: var(--secondary-color);
        }

        .stat-employers {
            background: linear-gradient(135deg, #FFFBF0 0%, #FFFFFF 100%);
            border-left: 4px solid #D97706;
        }

        .stat-employers::before {
            background: #D97706;
        }

        .stat-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon-wrap {
            transform: rotate(-5deg) scale(1.05);
        }

        .icon-users {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
            color: #6366F1;
        }

        .icon-jobs {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .icon-applications {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .icon-employers {
            background: linear-gradient(135deg, rgba(217, 119, 6, 0.15), rgba(217, 119, 6, 0.25));
            color: #D97706;
        }

        .stat-trend {
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .trend-up {
            background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
            color: #0F6848;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Card Common Styles */
        .quick-stats-card,
        .activity-card,
        .jobs-card,
        .reports-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--border-color);
            overflow: hidden;
            height: 100%;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #FAFBFC 0%, #FFFFFF 100%);
            border-bottom: 2px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-header-custom h5 {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.05rem;
        }

        .card-header-custom i {
            color: var(--primary-color);
        }

        .btn-header-link {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-header-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        /* Quick Stats */
        .quick-stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            background: var(--background-light);
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .quick-stat-item:hover {
            transform: translateX(4px);
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .quick-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .bg-pending {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .bg-accepted {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .bg-active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
            color: #6366F1;
        }

        .bg-today {
            background: linear-gradient(135deg, rgba(217, 119, 6, 0.15), rgba(217, 119, 6, 0.25));
            color: #D97706;
        }

        .quick-stat-content {
            display: flex;
            flex-direction: column;
        }

        .quick-stat-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .quick-stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Activity List */
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .activity-role {
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .activity-role.admin {
            background: rgba(99, 102, 241, 0.15);
            color: #6366F1;
        }

        .activity-role.employer {
            background: rgba(255, 107, 53, 0.15);
            color: var(--primary-color);
        }

        .activity-role.user {
            background: rgba(78, 205, 196, 0.15);
            color: var(--secondary-color);
        }

        .activity-time {
            color: var(--text-muted);
            font-size: 0.82rem;
        }

        .activity-time i {
            color: var(--primary-color);
        }

        /* Job Items */
        .job-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .job-item:last-child {
            border-bottom: none;
        }

        .job-item:hover {
            background: var(--background-light);
        }

        .job-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .job-content {
            flex: 1;
        }

        .job-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .job-meta {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin: 0;
        }

        .job-meta i {
            color: var(--primary-color);
        }

        .job-divider {
            margin: 0 0.5rem;
        }

        .job-badge {
            background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
            color: white;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Report Items */
        .report-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .report-item:hover {
            background: var(--background-light);
        }

        .report-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .report-content {
            flex: 1;
        }

        .report-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
        }

        .report-desc {
            color: var(--text-muted);
            font-size: 0.82rem;
            margin: 0;
        }

        .btn-report {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
            cursor: pointer;
        }

        .btn-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }

        /* Empty States */
        .empty-activity {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }

        .empty-activity i {
            font-size: 3rem;
            opacity: 0.4;
            margin-bottom: 0.75rem;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: slideUp 0.5s ease-out;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-date {
                width: 100%;
                text-align: center;
            }

            .stat-number {
                font-size: 1.75rem;
            }
        }
    </style>
    @endsection
@endcan