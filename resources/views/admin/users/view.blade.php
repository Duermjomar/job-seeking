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
                                @foreach ($user->roles as $role)
                                    <span class="role-badge role-{{ $role->name }}">
                                        @if ($role->name === 'admin')
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
                        </div>
                    </div>
                </div>

                {{-- STATS ROW --}}
                <div class="row g-4 mb-5">
                    @if ($user->jobSeeker)
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-applications">
                                <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $user->jobSeeker->applications->count() }}</h3>
                                    <p class="stat-label">Total Applications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-pending">
                                <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->jobSeeker->applications->where('application_status', 'pending')->count() }}
                                    </h3>
                                    <p class="stat-label">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-accepted">
                                <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->jobSeeker->applications->where('application_status', 'accepted')->count() }}
                                    </h3>
                                    <p class="stat-label">Accepted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-rejected">
                                <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->jobSeeker->applications->where('application_status', 'rejected')->count() }}
                                    </h3>
                                    <p class="stat-label">Rejected</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($user->employedJobs->count() > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-jobs">
                                <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $user->employedJobs->count() }}</h3>
                                    <p class="stat-label">Jobs Posted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-applicants">
                                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->employedJobs->sum(fn($job) => $job->applications->count()) }}
                                    </h3>
                                    <p class="stat-label">Total Applicants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-interviews">
                                <div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->employedJobs->sum(fn($job) => $job->applications->where('application_status', 'interview_scheduled')->count()) }}
                                    </h3>
                                    <p class="stat-label">Interviews Scheduled</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card-small stat-hired">
                                <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
                                <div class="stat-content">
                                    <h3 class="stat-number">
                                        {{ $user->employedJobs->sum(fn($job) => $job->applications->where('application_status', 'accepted')->count()) }}
                                    </h3>
                                    <p class="stat-label">Hired</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- TABBED CONTENT --}}
                <div class="content-tabs-wrapper">
                    <ul class="nav nav-tabs custom-tabs" id="userTabs" role="tablist">
                        @if ($user->jobSeeker)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="applications-tab" data-bs-toggle="tab"
                                    data-bs-target="#applications" type="button" role="tab">
                                    <i class="bi bi-file-earmark-text me-2"></i>Applications
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab">
                                    <i class="bi bi-person me-2"></i>Profile
                                </button>
                            </li>
                        @endif

                        @if ($user->employedJobs->count() > 0)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ !$user->jobSeeker ? 'active' : '' }}" id="jobs-tab"
                                    data-bs-toggle="tab" data-bs-target="#jobs" type="button" role="tab">
                                    <i class="bi bi-briefcase me-2"></i>Posted Jobs
                                </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="employer-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#employer-profile" type="button" role="tab">
                                    <i class="bi bi-building me-2"></i>Company Profile
                                </button>
                            </li> --}}
                        @endif

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity"
                                type="button" role="tab">
                                <i class="bi bi-clock-history me-2"></i>Activity Log
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content custom-tab-content" id="userTabsContent">

                        {{-- ── APPLICATIONS TAB (Job Seeker) ── --}}
                        @if ($user->jobSeeker)
                            <div class="tab-pane fade show active" id="applications" role="tabpanel">
                                @if ($user->jobSeeker->applications->count() > 0)
                                    <div class="applications-list">
                                        @foreach ($user->jobSeeker->applications as $app)
                                            <div class="application-item">
                                                <div class="app-main">
                                                    <div class="app-job-info">
                                                        <h6 class="app-job-title">{{ $app->job->job_title ?? 'N/A' }}</h6>
                                                        <p class="app-job-meta">
                                                            <i class="bi bi-geo-alt-fill"></i>
                                                            {{ $app->job->location ?? 'N/A' }}
                                                            <span class="divider">•</span>
                                                            <i class="bi bi-calendar3"></i>
                                                            {{ $app->created_at->format('M d, Y') }}
                                                        </p>
                                                    </div>

                                                    <span class="status-badge
                                                        @switch($app->application_status)
                                                            @case('pending')      badge-pending       @break
                                                            @case('reviewed')     badge-reviewed      @break
                                                            @case('shortlisted')  badge-shortlisted   @break
                                                            @case('interview_scheduled') badge-interview @break
                                                            @case('interviewed')  badge-interviewed   @break
                                                            @case('accepted')     badge-accepted      @break
                                                            @case('rejected')     badge-rejected      @break
                                                        @endswitch">
                                                        {{ ucfirst(str_replace('_', ' ', $app->application_status)) }}
                                                    </span>
                                                </div>

                                                @if ($app->application_status === 'interview_scheduled' && $app->interview)
                                                    <div class="interview-info-row">
                                                        <i class="bi bi-calendar-event-fill"></i>
                                                        <span>
                                                            Interview scheduled:
                                                            <strong>{{ \Carbon\Carbon::parse($app->interview->scheduled_at)->format('M d, Y \a\t h:i A') }}</strong>
                                                            &nbsp;·&nbsp;
                                                            {{ ucfirst($app->interview->interview_type) }}
                                                            @if ($app->interview->interview_type === 'online' && $app->interview->meeting_link)
                                                                — <a href="{{ $app->interview->meeting_link }}"
                                                                    target="_blank" class="interview-link">Join Link</a>
                                                            @elseif($app->interview->location)
                                                                — {{ $app->interview->location }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif

                                                @if ($app->application_status === 'rejected' && $app->rejection_reason)
                                                    <div class="rejection-reason-row">
                                                        <i class="bi bi-chat-left-text-fill"></i>
                                                        <span>Reason: {{ $app->rejection_reason }}</span>
                                                    </div>
                                                @endif

                                                @if ($app->files && $app->files->count() > 0)
                                                    <div class="app-attachments mt-2">
                                                        @if ($user->jobSeeker->resume)
                                                            <a href="{{ asset('storage/' . $user->jobSeeker->resume) }}"
                                                                target="_blank" class="attachment-link">
                                                                <i class="bi bi-file-earmark-person me-1"></i>
                                                                Resume
                                                            </a>
                                                        @endif
                                                        @foreach ($app->files as $file)
                                                            <a href="{{ asset('storage/' . $file->file_path) }}"
                                                                target="_blank" class="attachment-link"
                                                                download="{{ $file->original_name }}">
                                                                <i class="bi bi-file-earmark-text me-1"></i>
                                                                {{ $file->file_type === 'application_letter' ? 'Application Letter' : Str::limit($file->original_name, 20) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @elseif($user->jobSeeker->resume)
                                                    <div class="app-attachments mt-2">
                                                        <a href="{{ asset('storage/' . $user->jobSeeker->resume) }}"
                                                            target="_blank" class="attachment-link">
                                                            <i class="bi bi-file-earmark-person me-1"></i>
                                                            Resume
                                                        </a>
                                                    </div>
                                                @endif
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

                            {{-- ── PROFILE TAB (Job Seeker) ── --}}
                            <div class="tab-pane fade" id="profile" role="tabpanel">
                                <div class="profile-details">
                                    <div class="detail-section">
                                        <h6 class="section-title">
                                            <i class="bi bi-person-badge me-2"></i>Personal Information
                                        </h6>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <span class="info-label">Phone</span>
                                                <span class="info-value">{{ $user->jobSeeker->phone ?? 'Not provided' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Gender</span>
                                                <span class="info-value">{{ $user->jobSeeker->gender ? ucfirst($user->jobSeeker->gender) : 'Not provided' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Birth Date</span>
                                                <span class="info-value">
                                                    {{ $user->jobSeeker->birthdate ? \Carbon\Carbon::parse($user->jobSeeker->birthdate)->format('M d, Y') : 'Not provided' }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Address</span>
                                                <span class="info-value">{{ $user->jobSeeker->address ?? 'Not provided' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section">
                                        <h6 class="section-title">
                                            <i class="bi bi-file-text me-2"></i>Profile Summary
                                        </h6>
                                        <p class="section-content">
                                            {{ $user->jobSeeker->profile_summary ?? 'No profile summary provided.' }}
                                        </p>
                                    </div>

                                    @if ($user->jobSeeker->resume)
                                        <div class="detail-section">
                                            <h6 class="section-title">
                                                <i class="bi bi-file-earmark-person me-2"></i>Resume
                                            </h6>
                                            <a href="{{ asset('storage/' . $user->jobSeeker->resume) }}" target="_blank"
                                                class="btn-view-resume">
                                                <i class="bi bi-download me-2"></i>Download Resume
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- ── JOBS TAB (Employer) ── --}}
                        @if ($user->employedJobs->count() > 0)
                            <div class="tab-pane fade {{ !$user->jobSeeker ? 'show active' : '' }}" id="jobs"
                                role="tabpanel">
                                <div class="jobs-list">
                                    @foreach ($user->employedJobs as $job)
                                        <div class="job-item">
                                            <div class="job-header-row">
                                                <div class="job-main-info">
                                                    <h6 class="job-title">{{ $job->job_title }}</h6>
                                                    <p class="job-meta">
                                                        <i class="bi bi-geo-alt-fill"></i> {{ $job->location }}
                                                        <span class="divider">•</span>
                                                        <span class="job-type-tag">{{ ucfirst($job->job_type) }}</span>
                                                        @if ($job->salary)
                                                            <span class="divider">•</span>
                                                            <i class="bi bi-cash-stack"></i>
                                                            ₱{{ number_format($job->salary) }}
                                                        @endif
                                                    </p>
                                                    <p class="job-dates">
                                                        <i class="bi bi-calendar3"></i>
                                                        Posted {{ $job->created_at->format('M d, Y') }}
                                                        <span class="divider">•</span>
                                                        <span class="job-status-tag job-status-{{ $job->status }}">
                                                            <i class="bi bi-{{ $job->status === 'open' ? 'door-open' : 'door-closed' }}"></i>
                                                            {{ ucfirst($job->status) }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="job-description-summary">
                                                <p class="description-text">
                                                    {{ Str::limit($job->job_description, 200) }}
                                                </p>
                                            </div>

                                            @if ($job->requirements)
                                                <div class="job-requirements-summary">
                                                    <strong><i class="bi bi-list-check me-1"></i>Requirements:</strong>
                                                    {{ Str::limit($job->requirements, 150) }}
                                                </div>
                                            @endif

                                            <div class="job-stats-section">
                                                <h6 class="stats-title">Application Statistics</h6>
                                                <div class="job-stats-grid">
                                                    <div class="stat-item stat-item-total">
                                                        <i class="bi bi-people-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->count() }}</span>
                                                        <span class="stat-label">Total</span>
                                                    </div>
                                                    <div class="stat-item stat-item-pending">
                                                        <i class="bi bi-clock-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'pending')->count() }}</span>
                                                        <span class="stat-label">Pending</span>
                                                    </div>
                                                    <div class="stat-item stat-item-reviewed">
                                                        <i class="bi bi-eye-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'reviewed')->count() }}</span>
                                                        <span class="stat-label">Reviewed</span>
                                                    </div>
                                                    <div class="stat-item stat-item-shortlisted">
                                                        <i class="bi bi-star-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'shortlisted')->count() }}</span>
                                                        <span class="stat-label">Shortlisted</span>
                                                    </div>
                                                    <div class="stat-item stat-item-interview">
                                                        <i class="bi bi-calendar-check-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'interview_scheduled')->count() }}</span>
                                                        <span class="stat-label">Interview</span>
                                                    </div>
                                                    <div class="stat-item stat-item-interviewed">
                                                        <i class="bi bi-chat-dots-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'interviewed')->count() }}</span>
                                                        <span class="stat-label">Interviewed</span>
                                                    </div>
                                                    <div class="stat-item stat-item-accepted">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'accepted')->count() }}</span>
                                                        <span class="stat-label">Hired</span>
                                                    </div>
                                                    <div class="stat-item stat-item-rejected">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                        <span class="stat-number">{{ $job->applications->where('application_status', 'rejected')->count() }}</span>
                                                        <span class="stat-label">Rejected</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @php
                                                $jobInterviews = $job->applications->filter(
                                                    fn($app) => $app->interview !== null,
                                                );
                                            @endphp
                                            @if ($jobInterviews->count() > 0)
                                                <div class="job-interviews-section">
                                                    <h6 class="interviews-title">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        Scheduled Interviews ({{ $jobInterviews->count() }})
                                                    </h6>
                                                    <div class="interviews-mini-list">
                                                        @foreach ($jobInterviews->take(3) as $app)
                                                            <div class="interview-mini-item">
                                                                <div class="interview-mini-info">
                                                                    <span class="interview-candidate">{{ $app->jobSeeker->user->name }}</span>
                                                                    <span class="interview-datetime">
                                                                        <i class="bi bi-clock"></i>
                                                                        {{ \Carbon\Carbon::parse($app->interview->scheduled_at)->format('M d, Y h:i A') }}
                                                                    </span>
                                                                </div>
                                                                <span class="interview-type-badge badge-{{ $app->interview->interview_type }}">
                                                                    <i class="bi bi-{{ $app->interview->interview_type === 'online' ? 'camera-video' : 'geo-alt' }}"></i>
                                                                    {{ ucfirst($app->interview->interview_type) }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                        @if ($jobInterviews->count() > 3)
                                                            <div class="interview-mini-more">
                                                                +{{ $jobInterviews->count() - 3 }} more interviews
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ── EMPLOYER PROFILE TAB ── --}}
                            <div class="tab-pane fade" id="employer-profile" role="tabpanel">
                                <div class="profile-details">
                                    <div class="detail-section">
                                        <h6 class="section-title">
                                            <i class="bi bi-building me-2"></i>Company Information
                                        </h6>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <span class="info-label">Company Name</span>
                                                <span class="info-value">{{ $user->employer->company_name ?? 'Not provided' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Phone</span>
                                                <span class="info-value">{{ $user->employer->phone ?? 'Not provided' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Total Jobs Posted</span>
                                                <span class="info-value">{{ $user->employedJobs->count() }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Active Jobs</span>
                                                <span class="info-value">{{ $user->employedJobs->where('status', 'open')->count() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($user->employer && $user->employer->company_description)
                                        <div class="detail-section">
                                            <h6 class="section-title">
                                                <i class="bi bi-file-text me-2"></i>Company Description
                                            </h6>
                                            <p class="section-content">{{ $user->employer->company_description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- ── ACTIVITY LOG TAB ── --}}
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="activity-timeline">

                                @php
                                    $events = [];

                                    // Account created
                                    $events[] = [
                                        'time' => $user->created_at,
                                        'text' => 'Account created',
                                        'bold' => null,
                                        'suffix' => null,
                                        'type' => 'account',
                                    ];

                                    // ── Job Seeker events ──
                                    if ($user->jobSeeker) {
                                        foreach ($user->jobSeeker->applications as $app) {
                                            $events[] = [
                                                'time' => $app->created_at,
                                                'text' => 'Applied to ',
                                                'bold' => $app->job->job_title ?? 'N/A',
                                                'suffix' => null,
                                                'type' => 'apply',
                                            ];

                                            if ($app->application_status !== 'pending') {
                                                $statusMap = [
                                                    'reviewed' => ['text' => 'Application reviewed for ', 'type' => 'reviewed'],
                                                    'shortlisted' => ['text' => 'Shortlisted for ', 'type' => 'shortlisted'],
                                                    'interview_scheduled' => ['text' => 'Interview scheduled for ', 'type' => 'interview_scheduled'],
                                                    'interviewed' => ['text' => 'Interview completed for ', 'type' => 'interviewed'],
                                                    'accepted' => ['text' => 'Application accepted for ', 'type' => 'accepted'],
                                                    'rejected' => ['text' => 'Application rejected for ', 'type' => 'rejected'],
                                                ];

                                                $entry = $statusMap[$app->application_status] ?? [
                                                    'text' => 'Status updated for ',
                                                    'type' => 'reviewed',
                                                ];

                                                $events[] = [
                                                    'time' => $app->status_updated_at ?? $app->updated_at,
                                                    'text' => $entry['text'],
                                                    'bold' => $app->job->job_title ?? 'N/A',
                                                    'suffix' => null,
                                                    'type' => $entry['type'],
                                                ];
                                            }

                                            if ($app->interview) {
                                                $events[] = [
                                                    'time' => $app->interview->created_at,
                                                    'text' => 'Interview set for ',
                                                    'bold' => $app->job->job_title ?? 'N/A',
                                                    'suffix' => ' on ' . \Carbon\Carbon::parse($app->interview->scheduled_at)->format('M d, Y h:i A'),
                                                    'type' => 'interview_scheduled',
                                                ];

                                                if ($app->interview->status === 'completed') {
                                                    $events[] = [
                                                        'time' => $app->interview->updated_at,
                                                        'text' => 'Interview completed for ',
                                                        'bold' => $app->job->job_title ?? 'N/A',
                                                        'suffix' => null,
                                                        'type' => 'interviewed',
                                                    ];
                                                }

                                                if ($app->interview->status === 'cancelled') {
                                                    $events[] = [
                                                        'time' => $app->interview->updated_at,
                                                        'text' => 'Interview cancelled for ',
                                                        'bold' => $app->job->job_title ?? 'N/A',
                                                        'suffix' => null,
                                                        'type' => 'cancelled',
                                                    ];
                                                }
                                            }
                                        }
                                    }

                                    // ── Employer events (using employedJobs) ──
                                    if ($user->employedJobs->count() > 0) {
                                        foreach ($user->employedJobs as $job) {
                                            $events[] = [
                                                'time' => $job->created_at,
                                                'text' => 'Posted job ',
                                                'bold' => $job->job_title,
                                                'suffix' => null,
                                                'type' => 'job_post',
                                            ];

                                            if ($job->status === 'closed') {
                                                $events[] = [
                                                    'time' => $job->updated_at,
                                                    'text' => 'Closed job posting ',
                                                    'bold' => $job->job_title,
                                                    'suffix' => null,
                                                    'type' => 'job_closed',
                                                ];
                                            }

                                            foreach ($job->applications as $app) {
                                                $events[] = [
                                                    'time' => $app->created_at,
                                                    'text' => 'Received application for ',
                                                    'bold' => $job->job_title,
                                                    'suffix' => null,
                                                    'type' => 'received',
                                                ];

                                                if ($app->application_status !== 'pending') {
                                                    $employerActionMap = [
                                                        'reviewed' => 'Reviewed application for ',
                                                        'shortlisted' => 'Shortlisted applicant for ',
                                                        'interview_scheduled' => 'Scheduled interview for ',
                                                        'interviewed' => 'Completed interview for ',
                                                        'accepted' => 'Accepted applicant for ',
                                                        'rejected' => 'Rejected applicant for ',
                                                    ];

                                                    $actionText = $employerActionMap[$app->application_status] ?? 'Updated application for ';

                                                    $events[] = [
                                                        'time' => $app->status_updated_at ?? $app->updated_at,
                                                        'text' => $actionText,
                                                        'bold' => $job->job_title,
                                                        'suffix' => null,
                                                        'type' => $app->application_status,
                                                    ];
                                                }

                                                if ($app->interview) {
                                                    $events[] = [
                                                        'time' => $app->interview->created_at,
                                                        'text' => 'Interview scheduled for ',
                                                        'bold' => $job->job_title,
                                                        'suffix' => ' on ' . \Carbon\Carbon::parse($app->interview->scheduled_at)->format('M d, Y h:i A') . ' (' . ucfirst($app->interview->interview_type) . ')',
                                                        'type' => 'interview_scheduled',
                                                    ];

                                                    if ($app->interview->status === 'completed') {
                                                        $events[] = [
                                                            'time' => $app->interview->updated_at,
                                                            'text' => 'Interview marked complete for ',
                                                            'bold' => $job->job_title,
                                                            'suffix' => null,
                                                            'type' => 'interviewed',
                                                        ];
                                                    }

                                                    if ($app->interview->status === 'cancelled') {
                                                        $events[] = [
                                                            'time' => $app->interview->updated_at,
                                                            'text' => 'Interview cancelled for ',
                                                            'bold' => $job->job_title,
                                                            'suffix' => null,
                                                            'type' => 'cancelled',
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    // Sort newest first, cap at 30
                                    usort($events, fn($a, $b) => $b['time'] <=> $a['time']);
                                    $events = array_slice($events, 0, 30);
                                @endphp

                                @forelse($events as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-dot timeline-dot--{{ $event['type'] }}"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                @php
                                                    $iconMap = [
                                                        'account' => ['icon' => 'bi-person-check-fill', 'cls' => 'account'],
                                                        'apply' => ['icon' => 'bi-file-earmark-text-fill', 'cls' => 'apply'],
                                                        'job_post' => ['icon' => 'bi-briefcase-fill', 'cls' => 'job_post'],
                                                        'job_closed' => ['icon' => 'bi-briefcase-x-fill', 'cls' => 'job_closed'],
                                                        'received' => ['icon' => 'bi-inbox-fill', 'cls' => 'received'],
                                                        'reviewed' => ['icon' => 'bi-eye-fill', 'cls' => 'reviewed'],
                                                        'shortlisted' => ['icon' => 'bi-star-fill', 'cls' => 'shortlisted'],
                                                        'interview_scheduled' => ['icon' => 'bi-calendar-plus-fill', 'cls' => 'interview_scheduled'],
                                                        'interviewed' => ['icon' => 'bi-chat-dots-fill', 'cls' => 'interviewed'],
                                                        'cancelled' => ['icon' => 'bi-calendar-x-fill', 'cls' => 'cancelled'],
                                                        'accepted' => ['icon' => 'bi-check-circle-fill', 'cls' => 'accepted'],
                                                        'rejected' => ['icon' => 'bi-x-circle-fill', 'cls' => 'rejected'],
                                                    ];
                                                    $ic = $iconMap[$event['type']] ?? ['icon' => 'bi-circle-fill', 'cls' => 'account'];
                                                @endphp

                                                <span class="timeline-icon timeline-icon--{{ $ic['cls'] }}">
                                                    <i class="bi {{ $ic['icon'] }}"></i>
                                                </span>

                                                <p class="timeline-text">
                                                    {{ $event['text'] }}
                                                    @if (!empty($event['bold']))
                                                        <strong>{{ $event['bold'] }}</strong>
                                                    @endif
                                                    @if (!empty($event['suffix']))
                                                        {{ $event['suffix'] }}
                                                    @endif
                                                </p>
                                            </div>
                                            <span class="timeline-time">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $event['time']->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-tab-state">
                                        <i class="bi bi-clock-history"></i>
                                        <p>No activity recorded yet</p>
                                    </div>
                                @endforelse

                            </div>
                        </div>

                    </div>{{-- end tab-content --}}
                </div>{{-- end content-tabs-wrapper --}}

            </div>
        </div>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --text-dark: #2D3748;
                --text-muted: #718096;
                --border-color: #E2E8F0;
                --background-light: #F7F9FC;
            }

            * { font-family: 'Outfit', sans-serif; }

            .user-view-wrapper {
                min-height: 100vh;
                background: var(--background-light);
            }

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

            .user-profile-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                overflow: hidden;
            }
            .profile-header {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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
                top: -50px; right: -50px;
                width: 200px; height: 200px;
                background: rgba(255,255,255,0.1);
                border-radius: 50%;
            }
            .profile-avatar-large {
                width: 100px; height: 100px;
                background: rgba(255,255,255,0.25);
                border: 4px solid rgba(255,255,255,0.4);
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                color: white;
                font-size: 2.5rem; font-weight: 800;
                flex-shrink: 0;
            }
            .profile-info { flex: 1; min-width: 250px; position: relative; z-index: 1; }
            .profile-name { color: white; font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; }
            .profile-email { color: rgba(255,255,255,0.95); font-size: 1.05rem; margin-bottom: 1rem; }
            .profile-roles { display: flex; gap: 0.5rem; flex-wrap: wrap; }
            .role-badge {
                display: inline-flex; align-items: center;
                padding: 0.4rem 0.85rem; border-radius: 8px;
                font-size: 0.875rem; font-weight: 600;
            }
            .role-admin { background: rgba(99,102,241,0.3); color: white; border: 2px solid rgba(255,255,255,0.4); }
            .role-employer { background: rgba(255,255,255,0.3); color: white; border: 2px solid rgba(255,255,255,0.4); }
            .role-user { background: rgba(78,205,196,0.3); color: white; border: 2px solid rgba(255,255,255,0.4); }
            .profile-meta { display: flex; gap: 2rem; position: relative; z-index: 1; }
            .meta-item { display: flex; flex-direction: column; gap: 0.25rem; }
            .meta-label { color: rgba(255,255,255,0.8); font-size: 0.85rem; font-weight: 600; }
            .meta-value { color: white; font-size: 1rem; font-weight: 700; }

            /* Stat Cards */
            .stat-card-small {
                background: white; border-radius: 16px;
                border: 2px solid var(--border-color);
                padding: 1.5rem;
                display: flex; align-items: center; gap: 1.25rem;
                transition: all 0.3s ease;
            }
            .stat-card-small:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); border-color: transparent; }
            .stat-card-small .stat-icon {
                width: 56px; height: 56px; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.5rem; flex-shrink: 0;
            }
            .stat-applications .stat-icon { background: linear-gradient(135deg,rgba(78,205,196,.15),rgba(78,205,196,.25)); color: var(--secondary-color); }
            .stat-pending .stat-icon { background: linear-gradient(135deg,rgba(255,107,53,.15),rgba(255,107,53,.25)); color: var(--primary-color); }
            .stat-accepted .stat-icon { background: linear-gradient(135deg,rgba(16,185,129,.15),rgba(16,185,129,.25)); color: #10B981; }
            .stat-rejected .stat-icon { background: linear-gradient(135deg,rgba(239,68,68,.15),rgba(239,68,68,.25)); color: #EF4444; }
            .stat-jobs .stat-icon { background: linear-gradient(135deg,rgba(255,107,53,.15),rgba(255,107,53,.25)); color: var(--primary-color); }
            .stat-applicants .stat-icon { background: linear-gradient(135deg,rgba(99,102,241,.15),rgba(99,102,241,.25)); color: #6366F1; }
            .stat-interviews .stat-icon { background: linear-gradient(135deg,rgba(59,130,246,.15),rgba(59,130,246,.25)); color: #3B82F6; }
            .stat-hired .stat-icon { background: linear-gradient(135deg,rgba(16,185,129,.15),rgba(16,185,129,.25)); color: #10B981; }
            .stat-number { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 0.25rem; }
            .stat-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 600; }

            /* Tabs */
            .content-tabs-wrapper { background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
            .custom-tabs { background: linear-gradient(135deg,#FAFBFC,#FFF); border-bottom: 2px solid var(--border-color); padding: 0 1.5rem; }
            .custom-tabs .nav-link { color: var(--text-muted); font-weight: 600; padding: 1rem 1.5rem; border: none; border-bottom: 3px solid transparent; transition: all 0.3s ease; }
            .custom-tabs .nav-link:hover { color: var(--primary-color); background: rgba(255,107,53,0.05); }
            .custom-tabs .nav-link.active { color: var(--primary-color); border-bottom-color: var(--primary-color); background: transparent; }
            .custom-tab-content { padding: 2rem; }

            /* Applications */
            .applications-list { display: flex; flex-direction: column; gap: 1rem; }
            .application-item { padding: 1.25rem; border: 2px solid var(--border-color); border-radius: 12px; transition: all 0.3s ease; }
            .application-item:hover { border-color: var(--primary-color); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
            .app-main { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 1rem; }
            .app-job-title { color: var(--text-dark); font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem; }
            .app-job-meta { color: var(--text-muted); font-size: 0.9rem; margin: 0; }
            .app-job-meta i { color: var(--primary-color); }
            .divider { margin: 0 0.5rem; }

            .status-badge { padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; white-space: nowrap; }
            .badge-pending { background: linear-gradient(135deg,#FFF4E6,#FFE8CC); color: #D97706; }
            .badge-reviewed { background: linear-gradient(135deg,#E0E7FF,#C7D2FE); color: #4338CA; }
            .badge-shortlisted { background: linear-gradient(135deg,#FEF3C7,#FDE68A); color: #92400E; }
            .badge-interview { background: linear-gradient(135deg,#DBEAFE,#BFDBFE); color: #1E40AF; }
            .badge-interviewed { background: linear-gradient(135deg,#E9D5FF,#DDD6FE); color: #6B21A8; }
            .badge-accepted { background: linear-gradient(135deg,#D5F4E6,#B8EDDA); color: #0F6848; }
            .badge-rejected { background: linear-gradient(135deg,#FFE5E5,#FFD0D0); color: #C92A2A; }

            .interview-info-row, .rejection-reason-row {
                display: flex; align-items: flex-start; gap: 0.6rem;
                padding: 0.65rem 0.85rem; border-radius: 8px; font-size: 0.85rem; margin-top: 0.5rem;
            }
            .interview-info-row { background: linear-gradient(135deg,#EFF6FF,#DBEAFE); border: 1px solid #93C5FD; color: #1E40AF; }
            .interview-info-row i { color: #1E40AF; flex-shrink: 0; margin-top: 2px; }
            .interview-link { color: #1E40AF; font-weight: 700; }
            .rejection-reason-row { background: linear-gradient(135deg,#FFE5E5,#FFD0D0); border: 1px solid #FCA5A5; color: #C92A2A; }
            .rejection-reason-row i { color: #C92A2A; flex-shrink: 0; margin-top: 2px; }

            .app-attachments { display: flex; gap: 0.5rem; flex-wrap: wrap; }
            .attachment-link {
                background: linear-gradient(135deg,#FFF5F2,#FFE8E0); color: var(--primary-color);
                padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
                text-decoration: none; border: 1px solid rgba(255,107,53,0.25); transition: all 0.2s ease;
            }
            .attachment-link:hover { background: var(--primary-color); color: white; }

            /* Jobs */
            .jobs-list { display: flex; flex-direction: column; gap: 1.5rem; }
            .job-item { padding: 1.5rem; border: 2px solid var(--border-color); border-radius: 16px; transition: all 0.3s ease; background: white; }
            .job-item:hover { border-color: var(--secondary-color); box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
            .job-header-row { margin-bottom: 1.25rem; }
            .job-title { color: var(--text-dark); font-weight: 800; font-size: 1.35rem; margin-bottom: 0.5rem; }
            .job-meta { color: var(--text-muted); font-size: 0.95rem; margin: 0.25rem 0; display: flex; align-items: center; flex-wrap: wrap; gap: 0.35rem; }
            .job-meta i { color: var(--primary-color); }
            .job-dates { color: var(--text-muted); font-size: 0.85rem; margin: 0.5rem 0 0 0; display: flex; align-items: center; flex-wrap: wrap; gap: 0.35rem; }
            .job-type-tag { background: rgba(78,205,196,0.15); color: var(--secondary-color); padding: 0.25rem 0.7rem; border-radius: 6px; font-weight: 700; font-size: 0.82rem; }
            .job-status-tag { padding: 0.25rem 0.7rem; border-radius: 6px; font-weight: 700; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem; }
            .job-status-open { background: rgba(16,185,129,0.15); color: #10B981; }
            .job-status-closed { background: rgba(239,68,68,0.15); color: #EF4444; }

            .job-description-summary { background: var(--background-light); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
            .description-text { color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0; }
            .job-requirements-summary { background: linear-gradient(135deg,#FFF5F2,#FFE8E0); border-left: 4px solid var(--primary-color); padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; color: var(--text-dark); }
            .job-requirements-summary strong { color: var(--primary-color); }

            .job-stats-section { background: linear-gradient(135deg,#F9FAFB,#F3F4F6); padding: 1.25rem; border-radius: 12px; margin-bottom: 1rem; }
            .stats-title { color: var(--text-dark); font-weight: 700; font-size: 0.95rem; margin-bottom: 1rem; }
            .job-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px,1fr)); gap: 0.75rem; }
            .stat-item { background: white; padding: 0.85rem; border-radius: 10px; border: 2px solid var(--border-color); display: flex; flex-direction: column; align-items: center; text-align: center; transition: all 0.3s ease; }
            .stat-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .stat-item i { font-size: 1.5rem; margin-bottom: 0.5rem; }
            .stat-item .stat-number { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.25rem; }
            .stat-item .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
            .stat-item-total { border-color: #6366F1; } .stat-item-total i, .stat-item-total .stat-number { color: #6366F1; }
            .stat-item-pending { border-color: #D97706; } .stat-item-pending i, .stat-item-pending .stat-number { color: #D97706; }
            .stat-item-reviewed { border-color: #4338CA; } .stat-item-reviewed i, .stat-item-reviewed .stat-number { color: #4338CA; }
            .stat-item-shortlisted { border-color: #F59E0B; } .stat-item-shortlisted i, .stat-item-shortlisted .stat-number { color: #F59E0B; }
            .stat-item-interview { border-color: #3B82F6; } .stat-item-interview i, .stat-item-interview .stat-number { color: #3B82F6; }
            .stat-item-interviewed { border-color: #7C3AED; } .stat-item-interviewed i, .stat-item-interviewed .stat-number { color: #7C3AED; }
            .stat-item-accepted { border-color: #10B981; } .stat-item-accepted i, .stat-item-accepted .stat-number { color: #10B981; }
            .stat-item-rejected { border-color: #EF4444; } .stat-item-rejected i, .stat-item-rejected .stat-number { color: #EF4444; }

            .job-interviews-section { background: linear-gradient(135deg,#EFF6FF,#DBEAFE); padding: 1.25rem; border-radius: 12px; border: 2px solid #93C5FD; }
            .interviews-title { color: #1E40AF; font-weight: 700; font-size: 0.95rem; margin-bottom: 1rem; }
            .interviews-mini-list { display: flex; flex-direction: column; gap: 0.75rem; }
            .interview-mini-item { background: white; padding: 0.85rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; gap: 1rem; border: 1px solid #BFDBFE; }
            .interview-mini-info { flex: 1; display: flex; flex-direction: column; gap: 0.25rem; }
            .interview-candidate { color: var(--text-dark); font-weight: 700; font-size: 0.9rem; }
            .interview-datetime { color: var(--text-muted); font-size: 0.82rem; display: flex; align-items: center; gap: 0.35rem; }
            .interview-type-badge { padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; display: flex; align-items: center; gap: 0.35rem; }
            .badge-online { background: linear-gradient(135deg,#3B82F6,#2563EB); color: white; }
            .badge-onsite { background: linear-gradient(135deg,#8B5CF6,#7C3AED); color: white; }
            .interview-mini-more { text-align: center; color: #1E40AF; font-weight: 600; font-size: 0.85rem; padding: 0.5rem; background: white; border-radius: 6px; border: 1px dashed #93C5FD; }

            /* Profile Details */
            .detail-section { margin-bottom: 2rem; }
            .section-title { color: var(--text-dark); font-weight: 700; font-size: 1.05rem; margin-bottom: 1rem; }
            .section-title i { color: var(--primary-color); }
            .section-content { color: var(--text-muted); font-size: 1rem; line-height: 1.7; word-break: break-word; }
            .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap: 1rem; }
            .info-item { background: var(--background-light); padding: 1rem; border-radius: 10px; border: 2px solid var(--border-color); }
            .info-label { display: block; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.25rem; }
            .info-value { display: block; color: var(--text-dark); font-size: 1rem; font-weight: 600; }
            .btn-view-resume { background: linear-gradient(135deg,var(--secondary-color),#3DBDB4); color: white; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(78,205,196,0.3); }
            .btn-view-resume:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(78,205,196,0.4); color: white; }

            /* Activity Timeline */
            .activity-timeline { position: relative; padding-left: 2rem; }
            .activity-timeline::before { content: ''; position: absolute; left: 8px; top: 8px; bottom: 8px; width: 2px; background: var(--border-color); }
            .timeline-item { position: relative; padding-bottom: 1.5rem; }
            .timeline-dot { position: absolute; left: -2rem; top: 6px; width: 18px; height: 18px; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 0 2px var(--border-color); }
            .timeline-dot--account { background: #6366F1; }
            .timeline-dot--apply { background: var(--secondary-color); }
            .timeline-dot--job_post { background: var(--primary-color); }
            .timeline-dot--job_closed { background: #9CA3AF; }
            .timeline-dot--received { background: #8B5CF6; }
            .timeline-dot--reviewed { background: #F59E0B; }
            .timeline-dot--shortlisted { background: #D97706; }
            .timeline-dot--interview_scheduled { background: #3B82F6; }
            .timeline-dot--interviewed { background: #7C3AED; }
            .timeline-dot--cancelled { background: #EF4444; }
            .timeline-dot--accepted { background: #10B981; }
            .timeline-dot--rejected { background: #EF4444; }
            .timeline-content { background: var(--background-light); padding: 0.85rem 1rem; border-radius: 10px; border: 2px solid var(--border-color); }
            .timeline-header { display: flex; align-items: flex-start; gap: 0.65rem; }
            .timeline-icon { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; font-size: 0.78rem; flex-shrink: 0; margin-top: 1px; }
            .timeline-icon--account { background: rgba(99,102,241,.15); color: #6366F1; }
            .timeline-icon--apply { background: rgba(78,205,196,.15); color: var(--secondary-color); }
            .timeline-icon--job_post { background: rgba(255,107,53,.15); color: var(--primary-color); }
            .timeline-icon--job_closed { background: rgba(156,163,175,.15); color: #6B7280; }
            .timeline-icon--received { background: rgba(139,92,246,.15); color: #8B5CF6; }
            .timeline-icon--reviewed { background: rgba(245,158,11,.15); color: #F59E0B; }
            .timeline-icon--shortlisted { background: rgba(217,119,6,.15); color: #D97706; }
            .timeline-icon--interview_scheduled { background: rgba(59,130,246,.15); color: #3B82F6; }
            .timeline-icon--interviewed { background: rgba(124,58,237,.15); color: #7C3AED; }
            .timeline-icon--cancelled { background: rgba(239,68,68,.15); color: #EF4444; }
            .timeline-icon--accepted { background: rgba(16,185,129,.15); color: #10B981; }
            .timeline-icon--rejected { background: rgba(239,68,68,.15); color: #EF4444; }
            .timeline-text { color: var(--text-dark); font-size: 0.95rem; margin: 0; }
            .timeline-time { color: var(--text-muted); font-size: 0.82rem; display: block; margin-top: 0.25rem; }

            /* Empty State */
            .empty-tab-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
            .empty-tab-state i { font-size: 4rem; opacity: 0.4; margin-bottom: 1rem; display: block; }

            /* Responsive */
            @media (max-width: 768px) {
                .profile-header { flex-direction: column; text-align: center; }
                .profile-meta { justify-content: center; }
                .profile-name { font-size: 1.5rem; }
                .job-stats-grid { grid-template-columns: repeat(2,1fr); }
            }
        </style>
    @endsection
@endcan