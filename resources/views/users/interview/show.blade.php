@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="interview-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('users.applications') }}" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Back to Applications
                    </a>
                </div>

                {{-- Header --}}
                <div class="page-header mb-4">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div class="header-text">
                            <h2 class="header-title">Interview Details</h2>
                            <p class="header-subtitle">{{ $interview->application->job->job_title }}</p>
                        </div>
                    </div>
                    <div class="header-badge">
                        <span class="status-badge status-{{ $interview->status }}">
                            @if($interview->status === 'scheduled')
                                <i class="bi bi-calendar-check-fill me-1"></i>Scheduled
                            @elseif($interview->status === 'completed')
                                <i class="bi bi-check-circle-fill me-1"></i>Completed
                            @else
                                <i class="bi bi-x-circle-fill me-1"></i>Cancelled
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="row g-4">
                    
                    {{-- Left Column: Interview Info --}}
                    <div class="col-lg-8">
                        
                        {{-- Interview Schedule Card --}}
                        <div class="info-card">
                            <div class="card-header-custom">
                                <i class="bi bi-clock-fill me-2"></i>
                                <h5>Schedule</h5>
                            </div>
                            <div class="card-body-custom">
                                <div class="schedule-info">
                                    <div class="schedule-icon">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div class="schedule-details">
                                        <div class="schedule-label">Date & Time</div>
                                        <div class="schedule-value">
                                            {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('l, F j, Y') }}
                                        </div>
                                        <div class="schedule-time">
                                            {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('g:i A') }}
                                        </div>
                                    </div>
                                </div>

                                @if($interview->status === 'scheduled')
                                    <div class="countdown-box">
                                        <div class="countdown-icon">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div class="countdown-text">
                                            <strong>{{ \Carbon\Carbon::parse($interview->scheduled_at)->diffForHumans() }}</strong>
                                            @if(\Carbon\Carbon::parse($interview->scheduled_at)->isFuture())
                                                <span class="countdown-subtext">Time until interview</span>
                                            @else
                                                <span class="countdown-subtext">Interview time has passed</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Interview Type & Location --}}
                        <div class="info-card">
                            <div class="card-header-custom">
                                <i class="bi bi-{{ $interview->interview_type === 'online' ? 'camera-video-fill' : 'geo-alt-fill' }} me-2"></i>
                                <h5>{{ ucfirst($interview->interview_type) }} Interview</h5>
                            </div>
                            <div class="card-body-custom">
                                @if($interview->interview_type === 'online')
                                    <div class="meeting-info">
                                        <div class="meeting-icon">
                                            <i class="bi bi-link-45deg"></i>
                                        </div>
                                        <div class="meeting-details">
                                            <div class="meeting-label">Meeting Link</div>
                                            <a href="{{ $interview->meeting_link }}" 
                                               target="_blank" 
                                               class="meeting-link-btn">
                                                <i class="bi bi-box-arrow-up-right me-2"></i>
                                                Join Meeting
                                            </a>
                                            <div class="meeting-url">{{ $interview->meeting_link }}</div>
                                        </div>
                                    </div>

                                    <div class="alert-info-box">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Tips for Online Interview:</strong>
                                            <ul class="tips-list">
                                                <li>Test your camera and microphone beforehand</li>
                                                <li>Find a quiet, well-lit location</li>
                                                <li>Join 5-10 minutes early</li>
                                                <li>Keep the meeting link handy</li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="location-info">
                                        <div class="location-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div class="location-details">
                                            <div class="location-label">Interview Location</div>
                                            <div class="location-address">{{ $interview->location }}</div>
                                        </div>
                                    </div>

                                    <div class="alert-info-box">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <div>
                                            <strong>Tips for Onsite Interview:</strong>
                                            <ul class="tips-list">
                                                <li>Plan your route and arrive 10-15 minutes early</li>
                                                <li>Dress professionally</li>
                                                <li>Bring copies of your resume</li>
                                                <li>Bring a valid ID</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Additional Notes --}}
                        @if($interview->notes)
                            <div class="info-card">
                                <div class="card-header-custom">
                                    <i class="bi bi-journal-text me-2"></i>
                                    <h5>Additional Information</h5>
                                </div>
                                <div class="card-body-custom">
                                    <div class="notes-content">
                                        {{ $interview->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Right Column: Job & Application Info --}}
                    <div class="col-lg-4">
                        
                        {{-- Job Details Card --}}
                        <div class="info-card">
                            <div class="card-header-custom">
                                <i class="bi bi-briefcase-fill me-2"></i>
                                <h5>Job Details</h5>
                            </div>
                            <div class="card-body-custom">
                                <div class="job-info-item">
                                    <div class="job-info-label">Position</div>
                                    <div class="job-info-value">{{ $interview->application->job->job_title }}</div>
                                </div>
                                <div class="job-info-item">
                                    <div class="job-info-label">Company</div>
                                    <div class="job-info-value">
                                        {{ $interview->application->job->employer->company_name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="job-info-item">
                                    <div class="job-info-label">Location</div>
                                    <div class="job-info-value">{{ $interview->application->job->location }}</div>
                                </div>
                                <div class="job-info-item">
                                    <div class="job-info-label">Job Type</div>
                                    <div class="job-info-value">{{ ucfirst($interview->application->job->job_type) }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Application Status Card --}}
                        <div class="info-card">
                            <div class="card-header-custom">
                                <i class="bi bi-file-earmark-check-fill me-2"></i>
                                <h5>Application Status</h5>
                            </div>
                            <div class="card-body-custom">
                                <div class="status-timeline">
                                    <div class="timeline-item completed">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-label">Applied</div>
                                            <div class="timeline-date">
                                                {{ \Carbon\Carbon::parse($interview->application->applied_at)->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="timeline-item completed">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-label">Shortlisted</div>
                                            <div class="timeline-date">
                                                {{ \Carbon\Carbon::parse($interview->created_at)->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="timeline-item {{ $interview->status === 'scheduled' ? 'active' : 'completed' }}">
                                        <div class="timeline-dot"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-label">
                                                @if($interview->status === 'completed')
                                                    Interview Completed
                                                @elseif($interview->status === 'cancelled')
                                                    Interview Cancelled
                                                @else
                                                    Interview Scheduled
                                                @endif
                                            </div>
                                            <div class="timeline-date">
                                                {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="info-card">
                            <div class="card-header-custom">
                                <i class="bi bi-lightning-charge-fill me-2"></i>
                                <h5>Quick Actions</h5>
                            </div>
                            <div class="card-body-custom">
                                <a href="{{ route('users.applications') }}" class="action-btn action-primary">
                                    <i class="bi bi-arrow-left-circle me-2"></i>
                                    View All Applications
                                </a>
                                
                                @if($interview->status === 'scheduled' && $interview->interview_type === 'online')
                                    <a href="{{ $interview->meeting_link }}" 
                                       target="_blank" 
                                       class="action-btn action-success">
                                        <i class="bi bi-camera-video me-2"></i>
                                        Join Interview
                                    </a>
                                @endif

                                <a href="{{ route('users.jobs.show', $interview->application->job->id) }}" 
                                   class="action-btn action-secondary">
                                    <i class="bi bi-eye me-2"></i>
                                    View Job Details
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <style>
            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --success-color: #10B981;
                --warning-color: #F59E0B;
                --danger-color: #EF4444;
                --text-dark: #2D3748;
                --text-muted: #718096;
                --border-color: #E2E8F0;
                --background-light: #F7F9FC;
            }

            .interview-wrapper {
                min-height: 100vh;
                background: var(--background-light);
            }

            /* Back Button */
            .btn-back {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.5rem 1.25rem;
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

            /* Page Header */
            .page-header {
                background: white;
                border-radius: 16px;
                padding: 2rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 1.5rem;
            }

            .header-content {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }

            .header-icon {
                width: 70px;
                height: 70px;
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.25));
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: #3B82F6;
            }

            .header-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.75rem;
                margin: 0;
            }

            .header-subtitle {
                color: var(--text-muted);
                font-size: 1rem;
                margin: 0.25rem 0 0 0;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.6rem 1.25rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.95rem;
            }

            .status-scheduled {
                background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
                color: #1E40AF;
                border: 2px solid #93C5FD;
            }

            .status-completed {
                background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
                color: #065F46;
                border: 2px solid #6EE7B7;
            }

            .status-cancelled {
                background: linear-gradient(135deg, #FEE2E2, #FECACA);
                color: #991B1B;
                border: 2px solid #FCA5A5;
            }

            /* Info Card */
            .info-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                margin-bottom: 1.5rem;
                overflow: hidden;
            }

            .card-header-custom {
                background: linear-gradient(135deg, var(--background-light), #E5E7EB);
                padding: 1.25rem 1.5rem;
                border-bottom: 2px solid var(--border-color);
                display: flex;
                align-items: center;
            }

            .card-header-custom i {
                color: var(--primary-color);
                font-size: 1.25rem;
            }

            .card-header-custom h5 {
                margin: 0;
                font-weight: 700;
                color: var(--text-dark);
                font-size: 1.1rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            /* Schedule Info */
            .schedule-info {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                padding: 1.5rem;
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border-radius: 12px;
                margin-bottom: 1.5rem;
            }

            .schedule-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .schedule-details {
                flex: 1;
            }

            .schedule-label {
                font-size: 0.8rem;
                color: var(--text-muted);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.35rem;
            }

            .schedule-value {
                font-size: 1.25rem;
                color: var(--text-dark);
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .schedule-time {
                font-size: 1.5rem;
                color: #1E40AF;
                font-weight: 800;
            }

            /* Countdown Box */
            .countdown-box {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1.25rem;
                background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
                border: 2px solid #FDE68A;
                border-radius: 12px;
            }

            .countdown-icon {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #FBBF24, #F59E0B);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                animation: rotate 3s linear infinite;
            }

            @keyframes rotate {
                0%, 100% { transform: rotate(0deg); }
                50% { transform: rotate(15deg); }
            }

            .countdown-text {
                flex: 1;
            }

            .countdown-text strong {
                display: block;
                font-size: 1.15rem;
                color: #92400E;
                margin-bottom: 0.25rem;
            }

            .countdown-subtext {
                font-size: 0.85rem;
                color: var(--text-muted);
            }

            /* Meeting Info */
            .meeting-info, .location-info {
                display: flex;
                align-items: flex-start;
                gap: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .meeting-icon, .location-icon {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #1E40AF;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .meeting-details, .location-details {
                flex: 1;
            }

            .meeting-label, .location-label {
                font-size: 0.8rem;
                color: var(--text-muted);
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 0.75rem;
            }

            .meeting-link-btn {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
                margin-bottom: 0.75rem;
            }

            .meeting-link-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
                color: white;
            }

            .meeting-url {
                font-size: 0.85rem;
                color: var(--text-muted);
                word-break: break-all;
                background: var(--background-light);
                padding: 0.5rem;
                border-radius: 6px;
            }

            .location-address {
                font-size: 1rem;
                color: var(--text-dark);
                font-weight: 600;
                line-height: 1.6;
            }

            /* Alert Info Box */
            .alert-info-box {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border-left: 4px solid #3B82F6;
                border-radius: 8px;
                padding: 1.25rem;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .alert-info-box i {
                color: #1E40AF;
                font-size: 1.25rem;
                margin-top: 0.15rem;
            }

            .alert-info-box strong {
                color: #1E40AF;
                display: block;
                margin-bottom: 0.5rem;
            }

            .tips-list {
                margin: 0.5rem 0 0 0;
                padding-left: 1.25rem;
                color: var(--text-dark);
            }

            .tips-list li {
                margin-bottom: 0.35rem;
                font-size: 0.9rem;
            }

            /* Notes Content */
            .notes-content {
                background: var(--background-light);
                padding: 1.25rem;
                border-radius: 10px;
                color: var(--text-dark);
                line-height: 1.7;
                font-size: 0.95rem;
            }

            /* Job Info */
            .job-info-item {
                padding: 1rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .job-info-item:last-child {
                border-bottom: none;
            }

            .job-info-label {
                font-size: 0.8rem;
                color: var(--text-muted);
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 0.35rem;
            }

            .job-info-value {
                font-size: 1rem;
                color: var(--text-dark);
                font-weight: 600;
            }

            /* Status Timeline */
            .status-timeline {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .timeline-item {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                position: relative;
            }

            .timeline-item:not(:last-child)::after {
                content: '';
                position: absolute;
                left: 12px;
                top: 30px;
                width: 2px;
                height: calc(100% + 1.25rem);
                background: var(--border-color);
            }

            .timeline-item.completed::after {
                background: var(--secondary-color);
            }

            .timeline-dot {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                border: 3px solid var(--border-color);
                background: white;
                flex-shrink: 0;
                position: relative;
                z-index: 1;
            }

            .timeline-item.completed .timeline-dot {
                background: var(--secondary-color);
                border-color: var(--secondary-color);
            }

            .timeline-item.active .timeline-dot {
                background: var(--primary-color);
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2);
            }

            .timeline-content {
                flex: 1;
            }

            .timeline-label {
                font-weight: 700;
                color: var(--text-dark);
                font-size: 0.95rem;
                margin-bottom: 0.25rem;
            }

            .timeline-date {
                font-size: 0.85rem;
                color: var(--text-muted);
            }

            /* Action Buttons */
            .action-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.85rem 1.25rem;
                border-radius: 10px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.3s ease;
                margin-bottom: 0.75rem;
                width: 100%;
            }

            .action-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .action-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
                color: white;
            }

            .action-success {
                background: linear-gradient(135deg, #10B981, #059669);
                color: white;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            }

            .action-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
                color: white;
            }

            .action-secondary {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
            }

            .action-secondary:hover {
                background: var(--background-light);
                border-color: var(--primary-color);
                color: var(--primary-color);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .page-header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-content {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .schedule-info, .meeting-info, .location-info {
                    flex-direction: column;
                    text-align: center;
                }

                .schedule-icon, .meeting-icon, .location-icon {
                    margin: 0 auto;
                }
            }
        </style>
    @endsection
@endcan