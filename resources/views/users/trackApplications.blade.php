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



                {{-- FILTER TABS --}}
                <div class="filter-tabs mb-4">
                    <button class="filter-tab active" onclick="filterApplications('all')">
                        <i class="bi bi-grid me-1"></i>All
                    </button>
                    <button class="filter-tab" onclick="filterApplications('pending')">
                        <i class="bi bi-clock me-1"></i>Pending
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
                                <div
                                    class="app-status-icon
                                @if ($app->application_status === 'pending') status-icon-pending
                                @elseif($app->application_status === 'accepted') status-icon-accepted
                                @else status-icon-rejected @endif">
                                    @if ($app->application_status === 'pending')
                                        <i class="bi bi-clock-fill"></i>
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
                                        <span
                                            class="app-status-badge
                                        @if ($app->application_status === 'pending') badge-pending
                                        @elseif($app->application_status === 'accepted') badge-accepted
                                        @else badge-rejected @endif">
                                            @if ($app->application_status === 'pending')
                                                <i class="bi bi-clock-fill me-1"></i>
                                            @elseif($app->application_status === 'accepted')
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill me-1"></i>
                                            @endif
                                            {{ ucfirst($app->application_status) }}
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="app-progress-track">
                                        <div
                                            class="progress-step {{ $app->application_status !== '' ? 'done' : '' }} always-done">
                                            <div class="progress-dot"><i class="bi bi-check-lg"></i></div>
                                            <span>Applied</span>
                                        </div>
                                        <div
                                            class="progress-line
                                        @if ($app->application_status === 'accepted' || $app->application_status === 'rejected') done @endif">
                                        </div>
                                        <div
                                            class="progress-step
                                        @if ($app->application_status === 'accepted' || $app->application_status === 'rejected') done
                                        @else active @endif">
                                            <div class="progress-dot">
                                                @if ($app->application_status === 'accepted' || $app->application_status === 'rejected')
                                                    <i class="bi bi-check-lg"></i>
                                                @else
                                                    <i class="bi bi-clock"></i>
                                                @endif
                                            </div>
                                            <span>Under Review</span>
                                        </div>
                                        <div
                                            class="progress-line
                                        @if ($app->application_status === 'accepted' || $app->application_status === 'rejected') done @endif">
                                        </div>
                                        <div
                                            class="progress-step
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
                                                @else
                                                    Decision
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Bottom row: date + attachments --}}
                                    <div class="app-footer">
                                        <span class="app-date">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            Applied {{ $app->created_at->format('M d, Y') }}
                                        </span>
                                        <div class="app-attachments">
                                            @if ($app->resume)
                                                <a href="{{ asset('public/storage/' . $app->resume) }}" target="_blank"
                                                    class="app-attachment-btn">
                                                    <i class="bi bi-file-earmark-text me-1"></i>Resume
                                                </a>
                                            @endif
                                            @if ($app->application_letter)
                                                <a href="{{ asset('public/storage/' . $app->application_letter) }}"
                                                    target="_blank" class="app-attachment-btn btn-letter">
                                                    <i class="bi bi-file-earmark-text me-1"></i>Letter
                                                </a>
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

                0%,
                100% {
                    box-shadow: 0 12px 30px rgba(255, 107, 53, 0.25);
                }

                50% {
                    box-shadow: 0 16px 40px rgba(255, 107, 53, 0.4);
                }
            }

            @keyframes iconBounce {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.1);
                }
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
                max-width: 80px;
            }

            .progress-line.done {
                background: linear-gradient(90deg, var(--secondary-color), #3DBDB4);
            }

            @keyframes pulse {

                0%,
                100% {
                    box-shadow: 0 3px 10px rgba(255, 107, 53, 0.4);
                }

                50% {
                    box-shadow: 0 3px 18px rgba(255, 107, 53, 0.7);
                }
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

            .app-date {
                color: var(--text-muted);
                font-size: 0.85rem;
                font-weight: 500;
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
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
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
                    justify-content: center;
                }

                .stat-bar-divider {
                    display: none;
                }

                .stat-bar {
                    gap: 1.25rem;
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
