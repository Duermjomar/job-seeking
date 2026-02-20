@can('employer-access')

    @extends('layouts.Employer.app')
   @section('content')
        {{-- PAGE HEADER --}}
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="bi bi-list-stars me-2"></i>My Feedback History
                    </h1>
                    <p class="page-subtitle">
                        View all your submitted feedback and ratings
                    </p>
                </div>
            </div>
        </section>

        {{-- FEEDBACK LIST CONTAINER --}}
        <div class="container feedback-list-container">
            
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    {{-- FEEDBACK LIST CARD --}}
                    <div class="feedback-list-card">
                        {{-- CARD HEADER --}}
                        <div class="card-header-custom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="header-icon me-3">
                                        <i class="bi bi-chat-left-text-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Feedback List</h5>
                                        <p class="text-muted small mb-0">{{ $myfeedbacks->total() }} total {{ Str::plural('feedback', $myfeedbacks->total()) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('dashboard') }}" class="btn-back-small">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>

                        {{-- CARD BODY --}}
                        <div class="card-body-custom">
                            @forelse($myfeedbacks as $feedback)
                                <div class="feedback-item {{ $loop->last ? 'border-0' : '' }}">
                                    <div class="row align-items-center">
                                        {{-- RATING --}}
                                        <div class="col-lg-2 col-md-3 mb-3 mb-lg-0">
                                            <div class="rating-display">
                                                @if($feedback->rate == 1)
                                                    <div class="rating-badge rating-poor">
                                                        <i class="bi bi-emoji-frown"></i>
                                                        <span>Poor</span>
                                                    </div>
                                                    <div class="rating-stars-display">
                                                        <i class="bi bi-star-fill"></i>
                                                    </div>
                                                @elseif($feedback->rate == 3)
                                                    <div class="rating-badge rating-good">
                                                        <i class="bi bi-emoji-smile"></i>
                                                        <span>Good</span>
                                                    </div>
                                                    <div class="rating-stars-display">
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                    </div>
                                                @else
                                                    <div class="rating-badge rating-outstanding">
                                                        <i class="bi bi-emoji-heart-eyes"></i>
                                                        <span>Outstanding</span>
                                                    </div>
                                                    <div class="rating-stars-display">
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- COMMENT --}}
                                        <div class="col-lg-7 col-md-6 mb-3 mb-lg-0">
                                            <div class="comment-section">
                                                <p class="comment-text mb-0">
                                                    <i class="bi bi-quote text-muted me-2"></i>
                                                    {{ $feedback->comments }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- DATE --}}
                                        <div class="col-lg-3 col-md-3 text-lg-end">
                                            <div class="date-info">
                                                <i class="bi bi-calendar-check me-2"></i>
                                                <span class="date-text">{{ $feedback->created_at->format('M d, Y') }}</span>
                                                <div class="time-text">{{ $feedback->created_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <p class="empty-text mt-3 mb-4">No feedback submitted yet</p>
                                    <a href="{{ route('employer.feedback.create') }}" class="btn btn-primary-custom">
                                        <i class="bi bi-plus-circle me-2"></i>Submit Your First Feedback
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        {{-- PAGINATION --}}
                        @if($myfeedbacks->hasPages())
                            <div class="card-footer-custom">
                                {{ $myfeedbacks->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <style>
            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --accent-color: #FFE66D;
                --success-color: #95E1D3;
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

            /* Container */
            .feedback-list-container {
                padding: 4rem 0 3rem;
            }

            /* Feedback List Card */
            .feedback-list-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                animation: fadeIn 0.5s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Card Header */
            .card-header-custom {
                background: linear-gradient(135deg, #FFFFFF 0%, #F7F9FC 100%);
                border-bottom: 2px solid var(--border-color);
                padding: 1.5rem 2rem;
            }

            .header-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15) 0%, rgba(255, 107, 53, 0.25) 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 1.5rem;
            }

            .card-header-custom h5 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.25rem;
            }

            /* Back Button Small */
            .btn-back-small {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.9rem;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            }

            .btn-back-small:hover {
                border-color: var(--primary-color);
                color: var(--primary-color);
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 107, 53, 0.1));
                transform: translateX(-4px);
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
            }

            /* Card Body */
            .card-body-custom {
                padding: 0;
            }

            /* Feedback Items */
            .feedback-item {
                padding: 1.75rem 2rem;
                border-bottom: 1px solid var(--border-color);
                transition: all 0.3s ease;
                position: relative;
            }

            .feedback-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 4px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                transform: scaleY(0);
                transition: transform 0.3s ease;
            }

            .feedback-item:hover {
                background-color: #FAFBFC;
                padding-left: 2.5rem;
            }

            .feedback-item:hover::before {
                transform: scaleY(1);
            }

            /* Rating Display */
            .rating-display {
                text-align: center;
            }

            .rating-badge {
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                padding: 0.75rem 1rem;
                border-radius: 12px;
                font-weight: 600;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .rating-badge i {
                font-size: 2rem;
            }

            .rating-badge span {
                font-size: 0.875rem;
            }

            .rating-poor {
                background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
                color: #C92A2A;
                border: 2px solid #FF6B6B;
            }

            .rating-good {
                background: linear-gradient(135deg, #FFF9E6 0%, #FFF4CC 100%);
                color: #D97706;
                border: 2px solid #FFE66D;
            }

            .rating-outstanding {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
                color: #0F6848;
                border: 2px solid #95E1D3;
            }

            .rating-stars-display {
                display: flex;
                gap: 0.25rem;
                justify-content: center;
                color: #FFD700;
                font-size: 1rem;
            }

            /* Comment Section */
            .comment-section {
                padding-left: 1rem;
                border-left: 3px solid var(--border-color);
            }

            .comment-text {
                color: var(--text-dark);
                font-size: 0.95rem;
                line-height: 1.6;
                font-style: italic;
            }

            /* Date Info */
            .date-info {
                background: var(--background-light);
                padding: 0.75rem 1rem;
                border-radius: 10px;
                text-align: center;
            }

            .date-info i {
                color: var(--secondary-color);
            }

            .date-text {
                font-weight: 600;
                color: var(--text-dark);
                font-size: 0.95rem;
            }

            .time-text {
                font-size: 0.8rem;
                color: var(--text-muted);
                margin-top: 0.25rem;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 5rem 2rem;
            }

            .empty-icon {
                font-size: 5rem;
                color: var(--text-muted);
                opacity: 0.5;
            }

            .empty-text {
                color: var(--text-muted);
                font-size: 1.25rem;
                font-weight: 500;
            }

            .btn-primary-custom {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border: none;
                color: white;
                font-weight: 600;
                padding: 0.75rem 1.5rem;
                border-radius: 12px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
            }

            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Card Footer (Pagination) */
            .card-footer-custom {
                background: linear-gradient(135deg, #F7F9FC 0%, #FFFFFF 100%);
                border-top: 2px solid var(--border-color);
                padding: 1.5rem 2rem;
                display: flex;
                justify-content: center;
            }

            /* Pagination */
            .pagination {
                gap: 0.5rem;
                margin: 0;
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

            /* Responsive */
            @media (max-width: 768px) {
                .page-title {
                    font-size: 2rem;
                }

                .page-subtitle {
                    font-size: 1.05rem;
                }

                .card-header-custom,
                .card-footer-custom,
                .feedback-item {
                    padding-left: 1.25rem;
                    padding-right: 1.25rem;
                }

                .comment-section {
                    padding-left: 0;
                    border-left: none;
                    border-top: 2px solid var(--border-color);
                    padding-top: 1rem;
                    margin-top: 1rem;
                }

                .feedback-item:hover {
                    padding-left: 1.25rem;
                }

                .btn-back-small {
                    font-size: 0.85rem;
                    padding: 0.4rem 0.85rem;
                }
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

            .feedback-item {
                animation: slideUp 0.5s ease-out;
            }

            .feedback-item:nth-child(2) {
                animation-delay: 0.1s;
            }

            .feedback-item:nth-child(3) {
                animation-delay: 0.2s;
            }
        </style>
    @endsection

@endcan