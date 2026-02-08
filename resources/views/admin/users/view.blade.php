@can('admin-access')
    @extends('layouts.Admin.app')

    @section('content')
    <div class="user-view-wrapper">
        <div class="container-fluid px-4 py-5">

            {{-- BACK BUTTON --}}
            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Users
                </a>
            </div>

            {{-- USER PROFILE HEADER --}}
            <div class="user-profile-card mb-4">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $user->name }}</h2>
                        <p class="profile-email">
                            <i class="bi bi-envelope-fill me-2"></i>{{ $user->email }}
                        </p>
                        <div class="profile-roles">
                            @foreach($user->roles as $role)
                                <span class="role-badge role-{{ $role->name }}">
                                    @if($role->name === 'admin')
                                        <i class="bi bi-shield-fill-check me-1"></i>
                                    @elseif($role->name === 'employer')
                                        <i class="bi bi-briefcase-fill me-1"></i>
                                    @else
                                        <i class="bi bi-person-fill me-1"></i>
                                    @endif
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="profile-meta">
                        <div class="meta-item">
                            <span class="meta-label">Registered</span>
                            <span class="meta-value">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Last Active</span>
                            <span class="meta-value">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATS ROW --}}
            <div class="row g-4 mb-5">
                @if($user->jobSeeker)
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card-small stat-applications">
                            <div class="stat-icon">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $user->jobSeeker->applications->count() }}</h3>
                                <p class="stat-label">Total Applications</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card-small stat-pending">
                            <div class="stat-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $user->jobSeeker->applications->where('application_status', 'pending')->count() }}</h3>
                                <p class="stat-label">Pending</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card-small stat-accepted">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $user->jobSeeker->applications->where('application_status', 'accepted')->count() }}</h3>
                                <p class="stat-label">Accepted</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->employer)
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card-small stat-jobs">
                            <div class="stat-icon">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $user->employer->jobs->count() }}</h3>
                                <p class="stat-label">Jobs Posted</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card-small stat-applicants">
                            <div class="stat-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $user->employer->jobs->sum(function($job) { return $job->applications->count(); }) }}</h3>
                                <p class="stat-label">Total Applicants</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- TABBED CONTENT --}}
            <div class="content-tabs-wrapper">
                <ul class="nav nav-tabs custom-tabs" id="userTabs" role="tablist">
                    @if($user->jobSeeker)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab">
                                <i class="bi bi-file-earmark-text me-2"></i>Applications
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                <i class="bi bi-person me-2"></i>Profile
                            </button>
                        </li>
                    @endif

                    @if($user->employer)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !$user->jobSeeker ? 'active' : '' }}" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button" role="tab">
                                <i class="bi bi-briefcase me-2"></i>Posted Jobs
                            </button>
                        </li>
                    @endif

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="bi bi-clock-history me-2"></i>Activity Log
                        </button>
                    </li>
                </ul>

                <div class="tab-content custom-tab-content" id="userTabsContent">
                    
                    {{-- APPLICATIONS TAB --}}
                    @if($user->jobSeeker)
                        <div class="tab-pane fade show active" id="applications" role="tabpanel">
                            @if($user->jobSeeker->applications->count() > 0)
                                <div class="applications-list">
                                    @foreach($user->jobSeeker->applications as $app)
                                        <div class="application-item">
                                            <div class="app-main">
                                                <div class="app-job-info">
                                                    <h6 class="app-job-title">{{ $app->job->job_title ?? 'N/A' }}</h6>
                                                    <p class="app-job-meta">
                                                        <i class="bi bi-geo-alt-fill"></i> {{ $app->job->location ?? 'N/A' }}
                                                        <span class="divider">•</span>
                                                        <i class="bi bi-calendar3"></i> {{ $app->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <span class="status-badge 
                                                    @if($app->application_status === 'pending') badge-pending
                                                    @elseif($app->application_status === 'accepted') badge-accepted
                                                    @else badge-rejected
                                                    @endif">
                                                    {{ ucfirst($app->application_status) }}
                                                </span>
                                            </div>
                                            <div class="app-attachments">
                                                @if($app->resume)
                                                    <a href="{{ asset('public/storage/' . $app->resume) }}" target="_blank" class="attachment-link">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Resume
                                                    </a>
                                                @endif
                                                @if($app->application_letter)
                                                    <a href="{{ asset('public/storage/' . $app->application_letter) }}" target="_blank" class="attachment-link">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Letter
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-tab-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No applications submitted yet</p>
                                </div>
                            @endif
                        </div>

                        {{-- PROFILE TAB --}}
                        <div class="tab-pane fade" id="profile" role="tabpanel">
                            <div class="profile-details">
                                <div class="detail-section">
                                    <h6 class="section-title">
                                        <i class="bi bi-file-text me-2"></i>Profile Summary
                                    </h6>
                                    <p class="section-content">
                                        {{ $user->jobSeeker->profile_summary ?? 'No profile summary provided.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- JOBS TAB --}}
                    @if($user->employer)
                        <div class="tab-pane fade {{ !$user->jobSeeker ? 'show active' : '' }}" id="jobs" role="tabpanel">
                            @if($user->employer->jobs->count() > 0)
                                <div class="jobs-list">
                                    @foreach($user->employer->jobs as $job)
                                        <div class="job-item">
                                            <div class="job-main">
                                                <div class="job-info">
                                                    <h6 class="job-title">{{ $job->job_title }}</h6>
                                                    <p class="job-meta">
                                                        <i class="bi bi-geo-alt-fill"></i> {{ $job->location }}
                                                        <span class="divider">•</span>
                                                        <span class="job-type">{{ ucfirst($job->job_type) }}</span>
                                                        <span class="divider">•</span>
                                                        <i class="bi bi-calendar3"></i> {{ $job->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="job-stats">
                                                    <span class="job-stat">
                                                        <i class="bi bi-people-fill"></i>
                                                        {{ $job->applications->count() }} Applicants
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-tab-state">
                                    <i class="bi bi-briefcase"></i>
                                    <p>No jobs posted yet</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ACTIVITY TAB --}}
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="activity-timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <p class="timeline-text">Account created</p>
                                    <span class="timeline-time">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            @if($user->jobSeeker)
                                @foreach($user->jobSeeker->applications->sortByDesc('created_at')->take(10) as $app)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <p class="timeline-text">Applied to <strong>{{ $app->job->job_title ?? 'N/A' }}</strong></p>
                                            <span class="timeline-time">{{ $app->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($user->employer)
                                @foreach($user->employer->jobs->sortByDesc('created_at')->take(10) as $job)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <p class="timeline-text">Posted job <strong>{{ $job->job_title }}</strong></p>
                                            <span class="timeline-time">{{ $job->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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

        .user-view-wrapper {
            min-height: 100vh;
            background: var(--background-light);
        }

        /* Back Button */
        .btn-back {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateX(-4px);
        }

        /* Profile Card */
        .user-profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 2.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.25);
            border: 4px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
        }

        .profile-info {
            flex: 1;
            min-width: 250px;
            position: relative;
            z-index: 1;
        }

        .profile-name {
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .profile-email {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.05rem;
            margin-bottom: 1rem;
        }

        .profile-roles {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .role-admin {
            background: rgba(99, 102, 241, 0.3);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .role-employer {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .role-user {
            background: rgba(78, 205, 196, 0.3);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        .profile-meta {
            display: flex;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .meta-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .meta-value {
            color: white;
            font-size: 1rem;
            font-weight: 700;
        }

        /* Stat Cards Small */
        .stat-card-small {
            background: white;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s ease;
        }

        .stat-card-small:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .stat-card-small .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-applications .stat-icon {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .stat-pending .stat-icon {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .stat-accepted .stat-icon {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .stat-jobs .stat-icon {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .stat-applicants .stat-icon {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
            color: #6366F1;
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Tabs */
        .content-tabs-wrapper {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .custom-tabs {
            background: linear-gradient(135deg, #FAFBFC 0%, #FFFFFF 100%);
            border-bottom: 2px solid var(--border-color);
            padding: 0 1.5rem;
        }

        .custom-tabs .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .custom-tabs .nav-link:hover {
            color: var(--primary-color);
            background: rgba(255, 107, 53, 0.05);
        }

        .custom-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent;
        }

        .custom-tab-content {
            padding: 2rem;
        }

        /* Applications List */
        .applications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .application-item {
            padding: 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .application-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .app-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .app-job-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0.25rem;
        }

        .app-job-meta {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .app-job-meta i {
            color: var(--primary-color);
        }

        .divider {
            margin: 0 0.5rem;
        }

        .status-badge {
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-pending {
            background: linear-gradient(135deg, #FFF4E6, #FFE8CC);
            color: #D97706;
        }

        .badge-accepted {
            background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
            color: #0F6848;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
            color: #C92A2A;
        }

        .app-attachments {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .attachment-link {
            background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
            color: var(--primary-color);
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255, 107, 53, 0.25);
            transition: all 0.2s ease;
        }

        .attachment-link:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Jobs List */
        .jobs-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .job-item {
            padding: 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .job-item:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .job-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .job-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0.25rem;
        }

        .job-meta {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .job-meta i {
            color: var(--primary-color);
        }

        .job-stat {
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

        /* Profile Details */
        .detail-section {
            margin-bottom: 2rem;
        }

        .section-title {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 1rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .section-content {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Activity Timeline */
        .activity-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-dot {
            position: absolute;
            left: -2rem;
            top: 4px;
            width: 18px;
            height: 18px;
            background: var(--primary-color);
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 2px var(--border-color);
        }

        .timeline-content {
            background: var(--background-light);
            padding: 0.85rem 1rem;
            border-radius: 10px;
            border: 2px solid var(--border-color);
        }

        .timeline-text {
            color: var(--text-dark);
            font-size: 0.95rem;
            margin: 0;
        }

        .timeline-time {
            color: var(--text-muted);
            font-size: 0.82rem;
            display: block;
            margin-top: 0.25rem;
        }

        /* Empty State */
        .empty-tab-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }

        .empty-tab-state i {
            font-size: 4rem;
            opacity: 0.4;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-meta {
                justify-content: center;
            }

            .profile-name {
                font-size: 1.5rem;
            }
        }
    </style>
    @endsection
@endcan