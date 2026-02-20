@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="track-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1 header-title">Track Applications</h1>
                        <p class="header-subtitle mb-0">Monitor the status of all your job applications</p>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-browse">
                            <i class="bi bi-arrow-left me-2"></i> Back
                        </a>
                        <a href="{{ route('users.jobs.index') }}" class="btn btn-browse">
                            <i class="bi bi-search me-2"></i>Browse Jobs
                        </a>
                    </div>
                </div>

                {{-- FLASH MESSAGES --}}
                @if (session('success'))
                    <div class="alert alert-success-custom">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- STATS BAR --}}
                <div class="stat-bar mb-4">
                    <div class="stat-bar-item stat-bar-total">
                        <i class="bi bi-briefcase-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $totalApplications }}</div>
                            <div class="stat-bar-label">Total</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-pending">
                        <i class="bi bi-clock-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $pendingApplications }}</div>
                            <div class="stat-bar-label">Pending</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-interview">
                        <i class="bi bi-calendar-check-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $interviewApplications ?? 0 }}</div>
                            <div class="stat-bar-label">Interview</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-accepted">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $acceptedApplications }}</div>
                            <div class="stat-bar-label">Accepted</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-rejected">
                        <i class="bi bi-x-circle-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $rejectedApplications }}</div>
                            <div class="stat-bar-label">Rejected</div>
                        </div>
                    </div>
                </div>

                {{-- FILTER TABS --}}
                <div class="filter-tabs mb-4">
                    <button class="filter-tab active" onclick="filterApplications('all')">
                        <i class="bi bi-grid me-1"></i>All
                    </button>
                    <button class="filter-tab" onclick="filterApplications('pending')">
                        <i class="bi bi-clock me-1"></i>Pending
                    </button>
                    <button class="filter-tab" onclick="filterApplications('reviewed')">
                        <i class="bi bi-eye me-1"></i>Reviewed
                    </button>
                    <button class="filter-tab" onclick="filterApplications('shortlisted')">
                        <i class="bi bi-star me-1"></i>Shortlisted
                    </button>
                    <button class="filter-tab" onclick="filterApplications('interview_scheduled')">
                        <i class="bi bi-calendar-check me-1"></i>Interview
                    </button>
                    <button class="filter-tab" onclick="filterApplications('interviewed')">
                        <i class="bi bi-chat-dots me-1"></i>Interviewed
                    </button>
                    <button class="filter-tab" onclick="filterApplications('accepted')">
                        <i class="bi bi-check-circle me-1"></i>Accepted
                    </button>
                    <button class="filter-tab" onclick="filterApplications('rejected')">
                        <i class="bi bi-x-circle me-1"></i>Rejected
                    </button>
                </div>

                {{-- APPLICATIONS LIST --}}
                <div class="applications-container" id="applicationsContainer">
                    @forelse($applications as $app)
                        <div class="application-card {{ request('highlight') == $app->job_id ? 'highlighted' : '' }}"
                            data-status="{{ $app->application_status }}" id="app-{{ $app->job_id }}">
                            <div class="application-card-inner">

                                {{-- Status Icon --}}
                                <div class="app-status-icon
                                    @if ($app->application_status === 'pending') status-icon-pending
                                    @elseif($app->application_status === 'reviewed') status-icon-reviewed
                                    @elseif($app->application_status === 'shortlisted') status-icon-shortlisted
                                    @elseif($app->application_status === 'interview_scheduled') status-icon-interview
                                    @elseif($app->application_status === 'interviewed') status-icon-interviewed
                                    @elseif($app->application_status === 'accepted') status-icon-accepted
                                    @else status-icon-rejected @endif">
                                    @if ($app->application_status === 'pending')
                                        <i class="bi bi-clock-fill"></i>
                                    @elseif($app->application_status === 'reviewed')
                                        <i class="bi bi-eye-fill"></i>
                                    @elseif($app->application_status === 'shortlisted')
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($app->application_status === 'interview_scheduled')
                                        <i class="bi bi-calendar-check-fill"></i>
                                    @elseif($app->application_status === 'interviewed')
                                        <i class="bi bi-chat-dots-fill"></i>
                                    @elseif($app->application_status === 'accepted')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill"></i>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="app-content">
                                    <div class="app-header">
                                        <div>
                                            <h5 class="app-job-title">{{ $app->job->job_title ?? 'N/A' }}</h5>
                                            <p class="app-company">
                                                <i class="bi bi-geo-alt-fill me-1"></i>
                                                {{ $app->job->location ?? 'N/A' }}
                                                <span class="app-divider">•</span>
                                                <i class="bi bi-clock me-1"></i>
                                                {{ ucfirst($app->job->job_type ?? 'N/A') }}
                                            </p>
                                        </div>
                                        <span class="app-status-badge
                                            @if ($app->application_status === 'pending') badge-pending
                                            @elseif($app->application_status === 'reviewed') badge-reviewed
                                            @elseif($app->application_status === 'shortlisted') badge-shortlisted
                                            @elseif($app->application_status === 'interview_scheduled') badge-interview
                                            @elseif($app->application_status === 'interviewed') badge-interviewed
                                            @elseif($app->application_status === 'accepted') badge-accepted
                                            @else badge-rejected @endif">
                                            @if ($app->application_status === 'pending')
                                                <i class="bi bi-clock-fill me-1"></i>
                                            @elseif($app->application_status === 'reviewed')
                                                <i class="bi bi-eye-fill me-1"></i>
                                            @elseif($app->application_status === 'shortlisted')
                                                <i class="bi bi-star-fill me-1"></i>
                                            @elseif($app->application_status === 'interview_scheduled')
                                                <i class="bi bi-calendar-check-fill me-1"></i>
                                            @elseif($app->application_status === 'interviewed')
                                                <i class="bi bi-chat-dots-fill me-1"></i>
                                            @elseif($app->application_status === 'accepted')
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill me-1"></i>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $app->application_status)) }}
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="app-progress-track">
                                        {{-- Step 1: Applied (always done) --}}
                                        <div class="progress-step done always-done">
                                            <div class="progress-dot"><i class="bi bi-check-lg"></i></div>
                                            <span>Applied</span>
                                        </div>
                                        
                                        <div class="progress-line
                                            @if (in_array($app->application_status, ['reviewed', 'shortlisted', 'interview_scheduled', 'interviewed', 'accepted', 'rejected'])) done @endif">
                                        </div>
                                        
                                        {{-- Step 2: Reviewed/Shortlisted --}}
                                        <div class="progress-step
                                            @if ($app->application_status === 'reviewed') active
                                            @elseif(in_array($app->application_status, ['shortlisted', 'interview_scheduled', 'interviewed', 'accepted', 'rejected'])) done @endif">
                                            <div class="progress-dot">
                                                @if (in_array($app->application_status, ['shortlisted', 'interview_scheduled', 'interviewed', 'accepted', 'rejected']))
                                                    <i class="bi bi-check-lg"></i>
                                                @elseif($app->application_status === 'reviewed')
                                                    <i class="bi bi-eye"></i>
                                                @else
                                                    <i class="bi bi-clock"></i>
                                                @endif
                                            </div>
                                            <span>Review</span>
                                        </div>
                                        
                                        <div class="progress-line
                                            @if (in_array($app->application_status, ['interview_scheduled', 'interviewed', 'accepted', 'rejected'])) done @endif">
                                        </div>
                                        
                                        {{-- Step 3: Interview --}}
                                        <div class="progress-step
                                            @if ($app->application_status === 'shortlisted') active
                                            @elseif ($app->application_status === 'interview_scheduled') active
                                            @elseif(in_array($app->application_status, ['interviewed', 'accepted', 'rejected'])) done @endif">
                                            <div class="progress-dot">
                                                @if (in_array($app->application_status, ['interviewed', 'accepted', 'rejected']))
                                                    <i class="bi bi-check-lg"></i>
                                                @elseif(in_array($app->application_status, ['shortlisted', 'interview_scheduled']))
                                                    <i class="bi bi-calendar-check"></i>
                                                @else
                                                    <i class="bi bi-calendar"></i>
                                                @endif
                                            </div>
                                            <span>Interview</span>
                                        </div>
                                        
                                        <div class="progress-line
                                            @if (in_array($app->application_status, ['accepted', 'rejected'])) done @endif">
                                        </div>
                                        
                                        {{-- Step 4: Decision --}}
                                        <div class="progress-step
                                            @if ($app->application_status === 'accepted') done step-accepted
                                            @elseif($app->application_status === 'rejected') done step-rejected @endif">
                                            <div class="progress-dot">
                                                @if ($app->application_status === 'accepted')
                                                    <i class="bi bi-check-lg"></i>
                                                @elseif($app->application_status === 'rejected')
                                                    <i class="bi bi-x-lg"></i>
                                                @else
                                                    <i class="bi bi-trophy"></i>
                                                @endif
                                            </div>
                                            <span>
                                                @if ($app->application_status === 'rejected')
                                                    Rejected
                                                @elseif ($app->application_status === 'accepted')
                                                    Hired!
                                                @else
                                                    Decision
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Interview Details (if scheduled) --}}
                                    @if($app->application_status === 'interview_scheduled' && $app->interview)
                                        <div class="interview-details-box">
                                            <div class="interview-details-header">
                                                <i class="bi bi-calendar-event-fill me-2"></i>
                                                <strong>Interview Scheduled</strong>
                                            </div>
                                            <div class="interview-details-content">
                                                <div class="interview-detail-row">
                                                    <div class="interview-detail-icon">
                                                        <i class="bi bi-clock-fill"></i>
                                                    </div>
                                                    <div class="interview-detail-info">
                                                        <span class="interview-detail-label">Date & Time</span>
                                                        <span class="interview-detail-value">
                                                            {{ \Carbon\Carbon::parse($app->interview->scheduled_at)->format('l, F j, Y \a\t g:i A') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="interview-detail-row">
                                                    <div class="interview-detail-icon">
                                                        <i class="bi bi-{{ $app->interview->interview_type === 'online' ? 'camera-video-fill' : 'geo-alt-fill' }}"></i>
                                                    </div>
                                                    <div class="interview-detail-info">
                                                        <span class="interview-detail-label">Type</span>
                                                        <span class="interview-detail-value">{{ ucfirst($app->interview->interview_type) }}</span>
                                                    </div>
                                                </div>

                                                @if($app->interview->interview_type === 'online' && $app->interview->meeting_link)
                                                    <div class="interview-detail-row">
                                                        <div class="interview-detail-icon">
                                                            <i class="bi bi-link-45deg"></i>
                                                        </div>
                                                        <div class="interview-detail-info">
                                                            <span class="interview-detail-label">Meeting Link</span>
                                                            <a href="{{ $app->interview->meeting_link }}" 
                                                               target="_blank" 
                                                               class="interview-meeting-link">
                                                                Join Meeting <i class="bi bi-box-arrow-up-right ms-1"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($app->interview->interview_type === 'onsite' && $app->interview->location)
                                                    <div class="interview-detail-row">
                                                        <div class="interview-detail-icon">
                                                            <i class="bi bi-geo-alt-fill"></i>
                                                        </div>
                                                        <div class="interview-detail-info">
                                                            <span class="interview-detail-label">Location</span>
                                                            <span class="interview-detail-value">{{ $app->interview->location }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($app->interview->notes)
                                                    <div class="interview-detail-row">
                                                        <div class="interview-detail-icon">
                                                            <i class="bi bi-journal-text"></i>
                                                        </div>
                                                        <div class="interview-detail-info">
                                                            <span class="interview-detail-label">Additional Notes</span>
                                                            <span class="interview-detail-value">{{ $app->interview->notes }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="interview-countdown">
                                                    <i class="bi bi-hourglass-split me-2"></i>
                                                    <span>{{ \Carbon\Carbon::parse($app->interview->scheduled_at)->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Shortlisted Notice --}}
                                    @if($app->application_status === 'shortlisted')
                                        <div class="shortlisted-notice-box">
                                            <div class="shortlisted-notice-icon">
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                            <div class="shortlisted-notice-content">
                                                <strong>You've been shortlisted!</strong>
                                                <p>Great news! The employer is interested in your application. An interview may be scheduled soon.</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Interviewed Notice --}}
                                    @if($app->application_status === 'interviewed')
                                        <div class="interviewed-notice-box">
                                            <div class="interviewed-notice-icon">
                                                <i class="bi bi-check2-circle"></i>
                                            </div>
                                            <div class="interviewed-notice-content">
                                                <strong>Interview Completed</strong>
                                                <p>Your interview has been completed. We'll notify you once a decision has been made.</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Rejection Reason --}}
                                    @if($app->rejection_reason && $app->application_status === 'rejected')
                                        <div class="rejection-reason-box">
                                            <div class="rejection-reason-header">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                <strong>Rejection Reason</strong>
                                            </div>
                                            <p class="rejection-reason-text">{{ $app->rejection_reason }}</p>
                                        </div>
                                    @endif

                                    {{-- Bottom row: date + attachments --}}
                                    <div class="app-footer">
                                        <div class="app-date-info">
                                            <span class="app-date">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                Applied {{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') : $app->created_at->format('M d, Y') }}
                                            </span>
                                            @if($app->status_updated_at && $app->application_status !== 'pending')
                                                <span class="app-date">
                                                    <i class="bi bi-arrow-repeat me-1"></i>
                                                    Updated {{ \Carbon\Carbon::parse($app->status_updated_at)->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="app-attachments">
                                            {{-- Resume from job_seekers table --}}
                                            @if (auth()->user()->jobSeeker->resume)
                                                <a href="{{ asset('storage/' . auth()->user()->jobSeeker->resume) }}" 
                                                   target="_blank"
                                                   download
                                                   class="app-attachment-btn">
                                                    <i class="bi bi-file-earmark-person me-1"></i>Resume
                                                </a>
                                            @endif

                                            {{-- Application Letter from application_files --}}
                                            @php
                                                $letter = $app->files->where('file_type', 'application_letter')->first();
                                            @endphp
                                            @if($letter)
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" 
                                                   target="_blank"
                                                   download="{{ $letter->original_name }}"
                                                   class="app-attachment-btn btn-letter">
                                                    <i class="bi bi-file-earmark-text me-1"></i>Letter
                                                </a>
                                            @endif

                                            {{-- Template Files from application_files --}}
                                            @php
                                                $templateFiles = $app->files->where('file_type', 'other');
                                            @endphp
                                            @if($templateFiles->count() > 0)
                                                @foreach($templateFiles as $templateFile)
                                                    <a href="{{ asset('storage/' . $templateFile->file_path) }}" 
                                                       target="_blank"
                                                       download="{{ $templateFile->original_name }}"
                                                       class="app-attachment-btn btn-template"
                                                       title="{{ $templateFile->original_name }}">
                                                        <i class="bi bi-file-earmark-check me-1"></i>
                                                        {{ Str::limit(pathinfo($templateFile->original_name, PATHINFO_FILENAME), 15) }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-briefcase"></i></div>
                            <h5 class="empty-title">No Applications Yet</h5>
                            <p class="empty-text">Start applying to jobs to track your progress here</p>
                            <a href="{{ route('users.jobs.index') }}" class="btn btn-empty-cta">
                                <i class="bi bi-search me-2"></i>Browse Jobs
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>
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
            }

            .track-wrapper {
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

            .btn-browse {
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

            .btn-browse:hover {
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

            /* Alert */
            .alert-success-custom {
                background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
                color: #0F6848;
                border-radius: 12px;
                padding: 1rem 1.25rem;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                animation: slideDown 0.3s ease;
                margin-bottom: 1rem;
            }

            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Stat Bar */
            .stat-bar {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                padding: 1.25rem 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 2rem;
                flex-wrap: wrap;
            }

            .stat-bar-item {
                display: flex;
                align-items: center;
                gap: 0.85rem;
            }

            .stat-bar-item i {
                font-size: 1.65rem;
            }

            .stat-bar-item div {
                display: flex;
                flex-direction: column;
            }

            .stat-bar-number {
                font-size: 1.5rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .stat-bar-label {
                font-size: 0.78rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--text-muted);
            }

            .stat-bar-total i {
                color: #6366F1;
            }

            .stat-bar-total .stat-bar-number {
                color: #6366F1;
            }

            .stat-bar-pending i {
                color: var(--primary-color);
            }

            .stat-bar-pending .stat-bar-number {
                color: var(--primary-color);
            }

            .stat-bar-interview i {
                color: #3B82F6;
            }

            .stat-bar-interview .stat-bar-number {
                color: #3B82F6;
            }

            .stat-bar-accepted i {
                color: var(--secondary-color);
            }

            .stat-bar-accepted .stat-bar-number {
                color: var(--secondary-color);
            }

            .stat-bar-rejected i {
                color: #FF6B6B;
            }

            .stat-bar-rejected .stat-bar-number {
                color: #FF6B6B;
            }

            .stat-bar-divider {
                width: 1px;
                height: 40px;
                background: var(--border-color);
            }

            /* Filters */
            .filter-tabs {
                display: flex;
                gap: 0.6rem;
                flex-wrap: wrap;
            }

            .filter-tab {
                background: white;
                border: 2px solid var(--border-color);
                color: var(--text-muted);
                padding: 0.55rem 1.15rem;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.25s ease;
                display: flex;
                align-items: center;
            }

            .filter-tab:hover {
                border-color: var(--primary-color);
                color: var(--primary-color);
                background: rgba(255, 107, 53, 0.06);
            }

            .filter-tab.active {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border-color: transparent;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            /* Application Card */
            .application-card {
                background: white;
                border-radius: 16px;
                border: 2px solid var(--border-color);
                margin-bottom: 1.25rem;
                transition: all 0.3s ease;
                overflow: hidden;
                animation: slideUp 0.45s ease-out;
            }

            .application-card:hover {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                border-color: transparent;
                transform: translateY(-2px);
            }

            /* Highlighted Application Card */
            .application-card.highlighted {
                border: 3px solid var(--primary-color);
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 230, 109, 0.05));
                box-shadow: 0 12px 30px rgba(255, 107, 53, 0.25);
                animation: highlightPulse 2s ease-in-out 3;
                scroll-margin-top: 100px;
            }

            .application-card.highlighted .app-status-icon {
                animation: iconBounce 1s ease-in-out 2;
            }

            @keyframes highlightPulse {
                0%, 100% { box-shadow: 0 12px 30px rgba(255, 107, 53, 0.25); }
                50% { box-shadow: 0 16px 40px rgba(255, 107, 53, 0.4); }
            }

            @keyframes iconBounce {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            .application-card-inner {
                display: flex;
                gap: 1.5rem;
                padding: 1.75rem;
                align-items: flex-start;
            }

            /* Status Icon */
            .app-status-icon {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.6rem;
                flex-shrink: 0;
            }

            .status-icon-pending {
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
                color: var(--primary-color);
            }

            .status-icon-reviewed {
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
                color: #6366F1;
            }

            .status-icon-shortlisted {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.25));
                color: #F59E0B;
            }

            .status-icon-interview {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.25));
                color: #3B82F6;
            }

            .status-icon-interviewed {
                background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(168, 85, 247, 0.25));
                color: #A855F7;
            }

            .status-icon-accepted {
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
                color: var(--secondary-color);
            }

            .status-icon-rejected {
                background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
                color: #FF6B6B;
            }

            /* Content */
            .app-content {
                flex: 1;
                min-width: 0;
            }

            .app-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                margin-bottom: 1rem;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .app-job-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.15rem;
                margin-bottom: 0.25rem;
            }

            .app-company {
                color: var(--text-muted);
                font-size: 0.9rem;
                margin-bottom: 0;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.35rem;
            }

            .app-company i {
                color: var(--primary-color);
            }

            .app-divider {
                color: var(--border-color);
                margin: 0 0.25rem;
            }

            /* Status Badge */
            .app-status-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.4rem 0.9rem;
                border-radius: 8px;
                font-size: 0.85rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .badge-pending {
                background: linear-gradient(135deg, #FFF4E6, #FFE8CC);
                color: #D97706;
                border: 1px solid #FFB84D;
            }

            .badge-reviewed {
                background: linear-gradient(135deg, #E0E7FF, #C7D2FE);
                color: #4338CA;
                border: 1px solid #A5B4FC;
            }

            .badge-shortlisted {
                background: linear-gradient(135deg, #FEF3C7, #FDE68A);
                color: #92400E;
                border: 1px solid #F59E0B;
            }

            .badge-interview {
                background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
                color: #1E40AF;
                border: 1px solid #3B82F6;
            }

            .badge-interviewed {
                background: linear-gradient(135deg, #EDE9FE, #DDD6FE);
                color: #6B21A8;
                border: 1px solid #A855F7;
            }

            .badge-accepted {
                background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
                color: #0F6848;
                border: 1px solid #95E1D3;
            }

            .badge-rejected {
                background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
                color: #C92A2A;
                border: 1px solid #FF6B6B;
            }

            /* Progress Track */
            .app-progress-track {
                display: flex;
                align-items: center;
                margin: 1.25rem 0;
                gap: 0;
            }

            .progress-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.4rem;
                position: relative;
                z-index: 1;
            }

            .progress-step span {
                font-size: 0.78rem;
                font-weight: 600;
                color: var(--text-muted);
                white-space: nowrap;
            }

            .progress-dot {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                border: 3px solid var(--border-color);
                background: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                color: var(--text-muted);
                transition: all 0.3s ease;
            }

            .progress-step.always-done .progress-dot {
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                border-color: transparent;
                color: white;
                box-shadow: 0 3px 10px rgba(78, 205, 196, 0.4);
            }

            .progress-step.always-done span {
                color: var(--secondary-color);
                font-weight: 700;
            }

            .progress-step.active .progress-dot {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border-color: transparent;
                color: white;
                box-shadow: 0 3px 10px rgba(255, 107, 53, 0.4);
                animation: pulse 2s infinite;
            }

            .progress-step.active span {
                color: var(--primary-color);
                font-weight: 700;
            }

            .progress-step.done .progress-dot {
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                border-color: transparent;
                color: white;
                box-shadow: 0 3px 10px rgba(78, 205, 196, 0.4);
            }

            .progress-step.done span {
                color: var(--secondary-color);
                font-weight: 700;
            }

            .progress-step.step-rejected .progress-dot {
                background: linear-gradient(135deg, #FF6B6B, #E85A5A);
                box-shadow: 0 3px 10px rgba(255, 107, 107, 0.4);
            }

            .progress-step.step-rejected span {
                color: #FF6B6B;
            }

            .progress-line {
                flex: 1;
                height: 3px;
                background: var(--border-color);
                margin: 0 0.25rem;
                margin-bottom: 1.35rem;
                border-radius: 3px;
                transition: background 0.3s ease;
                max-width: 60px;
            }

            .progress-line.done {
                background: linear-gradient(90deg, var(--secondary-color), #3DBDB4);
            }

            @keyframes pulse {
                0%, 100% { box-shadow: 0 3px 10px rgba(255, 107, 53, 0.4); }
                50% { box-shadow: 0 3px 18px rgba(255, 107, 53, 0.7); }
            }

            /* Interview Details Box */
            .interview-details-box {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border: 2px solid #93C5FD;
                border-radius: 12px;
                padding: 1.25rem;
                margin: 1rem 0;
            }

            .interview-details-header {
                color: #1E40AF;
                font-weight: 700;
                font-size: 1rem;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
            }

            .interview-details-content {
                display: flex;
                flex-direction: column;
                gap: 0.85rem;
            }

            .interview-detail-row {
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
                background: white;
                padding: 0.75rem;
                border-radius: 8px;
            }

            .interview-detail-icon {
                width: 36px;
                height: 36px;
                background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #1E40AF;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .interview-detail-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .interview-detail-label {
                font-size: 0.75rem;
                color: var(--text-muted);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .interview-detail-value {
                font-size: 0.9rem;
                color: var(--text-dark);
                font-weight: 600;
            }

            .interview-meeting-link {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.85rem;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            }

            .interview-meeting-link:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
                color: white;
            }

            .interview-countdown {
                background: white;
                padding: 0.65rem 0.85rem;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #1E40AF;
                font-weight: 700;
                font-size: 0.9rem;
                border: 2px dashed #93C5FD;
            }

            /* Shortlisted Notice */
            .shortlisted-notice-box {
                background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
                border: 2px solid #FDE68A;
                border-radius: 12px;
                padding: 1rem;
                margin: 1rem 0;
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
            }

            .shortlisted-notice-icon {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #FBBF24, #F59E0B);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            .shortlisted-notice-content strong {
                color: #92400E;
                font-size: 0.95rem;
                display: block;
                margin-bottom: 0.35rem;
            }

            .shortlisted-notice-content p {
                color: var(--text-dark);
                font-size: 0.85rem;
                margin: 0;
                line-height: 1.5;
            }

            /* Interviewed Notice */
            .interviewed-notice-box {
                background: linear-gradient(135deg, #FAF5FF, #EDE9FE);
                border: 2px solid #DDD6FE;
                border-radius: 12px;
                padding: 1rem;
                margin: 1rem 0;
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
            }

            .interviewed-notice-icon {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #A855F7, #9333EA);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            .interviewed-notice-content strong {
                color: #6B21A8;
                font-size: 0.95rem;
                display: block;
                margin-bottom: 0.35rem;
            }

            .interviewed-notice-content p {
                color: var(--text-dark);
                font-size: 0.85rem;
                margin: 0;
                line-height: 1.5;
            }

            /* Rejection Reason Box */
            .rejection-reason-box {
                background: linear-gradient(135deg, #FFF5F5, #FFE8E8);
                border-left: 4px solid #FF6B6B;
                border-radius: 8px;
                padding: 1rem;
                margin: 1rem 0;
            }

            .rejection-reason-header {
                color: #C92A2A;
                font-weight: 700;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
            }

            .rejection-reason-text {
                color: var(--text-dark);
                font-size: 0.88rem;
                line-height: 1.6;
                margin: 0;
            }

            /* Footer */
            .app-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 1rem;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .app-date-info {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .app-date {
                color: var(--text-muted);
                font-size: 0.85rem;
                font-weight: 500;
                display: block;
            }

            .app-date i {
                color: var(--primary-color);
            }

            .app-attachments {
                display: flex;
                gap: 0.6rem;
                flex-wrap: wrap;
            }

            .app-attachment-btn {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
                color: var(--primary-color);
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-size: 0.82rem;
                font-weight: 600;
                text-decoration: none;
                border: 1px solid rgba(255, 107, 53, 0.25);
                transition: all 0.2s ease;
            }

            .app-attachment-btn:hover {
                background: var(--primary-color);
                color: white;
            }

            .app-attachment-btn.btn-letter {
                background: linear-gradient(135deg, #F0FFFE, #DDFAF8);
                color: var(--secondary-color);
                border-color: rgba(78, 205, 196, 0.25);
            }

            .app-attachment-btn.btn-letter:hover {
                background: var(--secondary-color);
                color: white;
            }

            .app-attachment-btn.btn-template {
                background: linear-gradient(135deg, #F0F4FF, #E0E7FF);
                color: #4338CA;
                border-color: rgba(99, 102, 241, 0.25);
            }

            .app-attachment-btn.btn-template:hover {
                background: #6366F1;
                color: white;
            }

            /* Empty */
            .empty-state {
                text-align: center;
                padding: 5rem 2rem;
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .empty-icon {
                font-size: 4rem;
                color: var(--text-muted);
                opacity: 0.4;
                margin-bottom: 1rem;
            }

            .empty-title {
                color: var(--text-dark);
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .empty-text {
                color: var(--text-muted);
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            .btn-empty-cta {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 0.75rem 1.75rem;
                border-radius: 12px;
                font-weight: 700;
                text-decoration: none;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
                transition: all 0.3s ease;
            }

            .btn-empty-cta:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 18px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Hidden for filter */
            .application-card.hidden {
                display: none;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(18px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @media (max-width:767px) {
                .application-card-inner {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }

                .app-header {
                    flex-direction: column;
                    align-items: center;
                }

                .app-footer {
                    flex-direction: column;
                    align-items: center;
                }

                .stat-bar-divider {
                    display: none;
                }

                .stat-bar {
                    gap: 1.25rem;
                }

                .app-progress-track {
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .progress-line {
                    max-width: 30px;
                }

                .interview-detail-row {
                    flex-direction: column;
                    text-align: center;
                }

                .interview-detail-icon {
                    margin: 0 auto;
                }
            }
        </style>

        <script>
            function filterApplications(status) {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
                event.target.classList.add('active');

                // Filter cards
                document.querySelectorAll('.application-card').forEach(card => {
                    if (status === 'all' || card.dataset.status === status) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            // Auto-scroll to highlighted application on page load
            document.addEventListener('DOMContentLoaded', function() {
                const highlightedCard = document.querySelector('.application-card.highlighted');
                if (highlightedCard) {
                    // Small delay to ensure page is fully loaded
                    setTimeout(function() {
                        highlightedCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 300);

                    // Remove highlight after 6 seconds
                    setTimeout(function() {
                        highlightedCard.classList.remove('highlighted');
                    }, 6000);
                }
            });
        </script>
    @endsection
@endcan