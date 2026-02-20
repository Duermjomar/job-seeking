@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="interviews-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1 header-title">My Interviews</h1>
                        <p class="header-subtitle mb-0">View and manage all your scheduled interviews</p>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-browse">
                            <i class="bi bi-arrow-left me-2"></i> Back
                        </a>
                        <a href="{{ route('users.applications') }}" class="btn btn-browse">
                            <i class="bi bi-file-earmark-text me-2"></i>Applications
                        </a>
                    </div>
                </div>

                {{-- Stats Bar --}}
                <div class="stat-bar mb-4">
                    <div class="stat-bar-item stat-bar-scheduled">
                        <i class="bi bi-calendar-check-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $scheduledCount }}</div>
                            <div class="stat-bar-label">Upcoming</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-completed">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $completedCount }}</div>
                            <div class="stat-bar-label">Completed</div>
                        </div>
                    </div>
                    <div class="stat-bar-divider"></div>
                    <div class="stat-bar-item stat-bar-cancelled">
                        <i class="bi bi-x-circle-fill"></i>
                        <div>
                            <div class="stat-bar-number">{{ $cancelledCount }}</div>
                            <div class="stat-bar-label">Cancelled</div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Interviews --}}
                @if($upcomingInterviews->count() > 0)
                    <div class="section-header">
                        <h4 class="section-title">
                            <i class="bi bi-calendar-event me-2"></i>
                            Upcoming Interviews
                        </h4>
                    </div>

                    <div class="interviews-grid mb-4">
                        @foreach($upcomingInterviews as $interview)
                            <div class="interview-card upcoming-card">
                                <div class="interview-card-header">
                                    <div class="interview-type-badge badge-{{ $interview->interview_type }}">
                                        <i class="bi bi-{{ $interview->interview_type === 'online' ? 'camera-video' : 'geo-alt' }}"></i>
                                        {{ ucfirst($interview->interview_type) }}
                                    </div>
                                    <span class="interview-status-badge status-scheduled">
                                        <i class="bi bi-calendar-check"></i>
                                        Scheduled
                                    </span>
                                </div>

                                <div class="interview-card-body">
                                    <h5 class="interview-job-title">{{ $interview->application->job->job_title }}</h5>
                                    <p class="interview-company">
                                        <i class="bi bi-building"></i>
                                        {{ $interview->application->job->employer->company_name ?? 'N/A' }}
                                    </p>

                                    <div class="interview-datetime">
                                        <div class="datetime-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <div class="datetime-info">
                                            <div class="datetime-date">
                                                {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('l, F j, Y') }}
                                            </div>
                                            <div class="datetime-time">
                                                {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('g:i A') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="interview-countdown">
                                        <i class="bi bi-hourglass-split"></i>
                                        {{ \Carbon\Carbon::parse($interview->scheduled_at)->diffForHumans() }}
                                    </div>

                                    @if($interview->interview_type === 'online' && $interview->meeting_link)
                                        <a href="{{ $interview->meeting_link }}" 
                                           target="_blank" 
                                           class="interview-action-btn btn-join">
                                            <i class="bi bi-camera-video-fill me-2"></i>
                                            Join Meeting
                                        </a>
                                    @endif

                                    <a href="{{ route('users.interviews.show', $interview->id) }}" 
                                       class="interview-action-btn btn-view">
                                        <i class="bi bi-eye-fill me-2"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Past Interviews --}}
                @if($pastInterviews->count() > 0)
                    <div class="section-header">
                        <h4 class="section-title">
                            <i class="bi bi-clock-history me-2"></i>
                            Past Interviews
                        </h4>
                    </div>

                    <div class="interviews-list">
                        @foreach($pastInterviews as $interview)
                            <div class="interview-list-item">
                                <div class="interview-list-icon status-{{ $interview->status }}">
                                    @if($interview->status === 'completed')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill"></i>
                                    @endif
                                </div>
                                
                                <div class="interview-list-content">
                                    <h6 class="interview-list-title">{{ $interview->application->job->job_title }}</h6>
                                    <p class="interview-list-meta">
                                        <span>
                                            <i class="bi bi-building"></i>
                                            {{ $interview->application->job->employer->company_name ?? 'N/A' }}
                                        </span>
                                        <span class="meta-divider">•</span>
                                        <span>
                                            <i class="bi bi-calendar3"></i>
                                            {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('M d, Y') }}
                                        </span>
                                        <span class="meta-divider">•</span>
                                        <span>
                                            <i class="bi bi-{{ $interview->interview_type === 'online' ? 'camera-video' : 'geo-alt' }}"></i>
                                            {{ ucfirst($interview->interview_type) }}
                                        </span>
                                    </p>
                                </div>

                                <div class="interview-list-actions">
                                    <span class="interview-list-status status-{{ $interview->status }}">
                                        {{ ucfirst($interview->status) }}
                                    </span>
                                    <a href="{{ route('users.interviews.show', $interview->id) }}" 
                                       class="interview-list-btn">
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Empty State --}}
                @if($upcomingInterviews->count() === 0 && $pastInterviews->count() === 0)
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                        <h5 class="empty-title">No Interviews Yet</h5>
                        <p class="empty-text">You don't have any scheduled interviews at the moment</p>
                        <a href="{{ route('users.applications') }}" class="btn btn-empty-cta">
                            <i class="bi bi-file-earmark-text me-2"></i>View Applications
                        </a>
                    </div>
                @endif

            </div>
        </div>

        <style>
            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --text-dark: #2D3748;
                --text-muted: #718096;
                --border-color: #E2E8F0;
                --background-light: #F7F9FC;
            }

            .interviews-wrapper {
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

            .stat-bar-scheduled i, .stat-bar-scheduled .stat-bar-number {
                color: #3B82F6;
            }

            .stat-bar-completed i, .stat-bar-completed .stat-bar-number {
                color: #10B981;
            }

            .stat-bar-cancelled i, .stat-bar-cancelled .stat-bar-number {
                color: #EF4444;
            }

            .stat-bar-divider {
                width: 1px;
                height: 40px;
                background: var(--border-color);
            }

            /* Section Header */
            .section-header {
                margin: 2rem 0 1rem;
            }

            .section-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.25rem;
                display: flex;
                align-items: center;
            }

            .section-title i {
                color: var(--primary-color);
            }

            /* Interviews Grid */
            .interviews-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 1.5rem;
            }

            .interview-card {
                background: white;
                border-radius: 16px;
                border: 2px solid var(--border-color);
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .interview-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                border-color: var(--primary-color);
            }

            .upcoming-card {
                border-color: #3B82F6;
                background: linear-gradient(135deg, #FFFFFF, #EFF6FF);
            }

            .interview-card-header {
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                padding: 1.25rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .interview-type-badge {
                background: white;
                color: #1E40AF;
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .interview-status-badge {
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.8rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .status-scheduled {
                background: rgba(255, 255, 255, 0.3);
                color: white;
            }

            .interview-card-body {
                padding: 1.5rem;
            }

            .interview-job-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.15rem;
                margin-bottom: 0.5rem;
            }

            .interview-company {
                color: var(--text-muted);
                font-size: 0.9rem;
                margin-bottom: 1.25rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .interview-datetime {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border-radius: 10px;
                padding: 1rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .datetime-icon {
                width: 45px;
                height: 45px;
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.35rem;
            }

            .datetime-date {
                font-size: 0.95rem;
                color: var(--text-dark);
                font-weight: 600;
                margin-bottom: 0.15rem;
            }

            .datetime-time {
                font-size: 1.1rem;
                color: #1E40AF;
                font-weight: 800;
            }

            .interview-countdown {
                background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
                border: 2px solid #FDE68A;
                border-radius: 8px;
                padding: 0.75rem 1rem;
                text-align: center;
                color: #92400E;
                font-weight: 700;
                font-size: 0.9rem;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .interview-action-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.25rem;
                border-radius: 10px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.3s ease;
                margin-bottom: 0.75rem;
                width: 100%;
            }

            .btn-join {
                background: linear-gradient(135deg, #10B981, #059669);
                color: white;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            }

            .btn-join:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
                color: white;
            }

            .btn-view {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
            }

            .btn-view:hover {
                background: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            /* Interviews List */
            .interviews-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .interview-list-item {
                background: white;
                border-radius: 12px;
                border: 2px solid var(--border-color);
                padding: 1.25rem;
                display: flex;
                align-items: center;
                gap: 1.25rem;
                transition: all 0.3s ease;
            }

            .interview-list-item:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                border-color: var(--primary-color);
            }

            .interview-list-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .interview-list-icon.status-completed {
                background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
                color: #065F46;
            }

            .interview-list-icon.status-cancelled {
                background: linear-gradient(135deg, #FEE2E2, #FECACA);
                color: #991B1B;
            }

            .interview-list-content {
                flex: 1;
                min-width: 0;
            }

            .interview-list-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1rem;
                margin-bottom: 0.35rem;
            }

            .interview-list-meta {
                color: var(--text-muted);
                font-size: 0.85rem;
                margin: 0;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.35rem;
            }

            .meta-divider {
                color: var(--border-color);
                margin: 0 0.25rem;
            }

            .interview-list-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .interview-list-status {
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.8rem;
            }

            .interview-list-status.status-completed {
                background: #D1FAE5;
                color: #065F46;
            }

            .interview-list-status.status-cancelled {
                background: #FEE2E2;
                color: #991B1B;
            }

            .interview-list-btn {
                background: var(--background-light);
                color: var(--text-dark);
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .interview-list-btn:hover {
                background: var(--primary-color);
                color: white;
            }

            /* Empty State */
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

            /* Responsive */
            @media (max-width: 768px) {
                .interviews-grid {
                    grid-template-columns: 1fr;
                }

                .interview-list-item {
                    flex-direction: column;
                    text-align: center;
                }

                .interview-list-actions {
                    width: 100%;
                    justify-content: center;
                }

                .stat-bar-divider {
                    display: none;
                }
            }
        </style>
    @endsection
@endcan