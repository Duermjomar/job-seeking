@can('admin-access')
    @extends('layouts.Admin.app')

    @section('content')
        {{-- PAGE HEADER --}}
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="bi bi-chat-left-quote-fill me-2"></i>User Feedbacks
                    </h1>
                    <p class="page-subtitle">
                        Manage and review all user feedback submissions
                    </p>
                </div>
            </div>
        </section>

        {{-- FEEDBACK LIST CONTAINER --}}
        <div class="container feedback-admin-container">
            
            <div class="row justify-content-center">
                <div class="col-12">

                    {{-- FEEDBACK LIST CARD --}}
                    <div class="feedback-list-card">
                        {{-- CARD HEADER --}}
                        <div class="card-header-custom">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="header-icon me-3">
                                        <i class="bi bi-inbox-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-semibold">All Feedback Submissions</h5>
                                        <p class="text-muted small mb-0">
                                            {{ $allfeedbacks->total() }} total {{ Str::plural('submission', $allfeedbacks->total()) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="stats-badges">
                                    <span class="stat-badge stat-total">
                                        <i class="bi bi-collection me-1"></i>
                                        Total: {{ $allfeedbacks->total() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- CARD BODY - TABLE --}}
                        <div class="card-body-custom">
                            <div class="table-responsive">
                                <table class="table-custom">
                                    <thead>
                                        <tr>
                                            <th width="25%">
                                                <i class="bi bi-envelope me-2"></i>User Email
                                            </th>
                                            <th width="15%">
                                                <i class="bi bi-star-half me-2"></i>Rating
                                            </th>
                                            <th width="40%">
                                                <i class="bi bi-chat-dots me-2"></i>Comments
                                            </th>
                                            <th width="20%">
                                                <i class="bi bi-calendar-check me-2"></i>Date Submitted
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allfeedbacks as $feedback)
                                            <tr class="table-row">
                                                {{-- EMAIL --}}
                                                <td>
                                                    <div class="user-email">
                                                        <i class="bi bi-person-circle me-2"></i>
                                                        {{ $feedback->email }}
                                                    </div>
                                                </td>

                                                {{-- RATING --}}
                                                <td>
                                                    @if($feedback->rate == 1)
                                                        <div class="rating-badge-table rating-poor">
                                                            <i class="bi bi-emoji-frown me-1"></i>
                                                            <span>Poor</span>
                                                            <div class="stars-mini">
                                                                <i class="bi bi-star-fill"></i>
                                                            </div>
                                                        </div>
                                                    @elseif($feedback->rate == 3)
                                                        <div class="rating-badge-table rating-good">
                                                            <i class="bi bi-emoji-smile me-1"></i>
                                                            <span>Good</span>
                                                            <div class="stars-mini">
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="rating-badge-table rating-outstanding">
                                                            <i class="bi bi-emoji-heart-eyes me-1"></i>
                                                            <span>Outstanding</span>
                                                            <div class="stars-mini">
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>

                                                {{-- COMMENT --}}
                                                <td>
                                                    <div class="comment-cell">
                                                        <i class="bi bi-quote text-muted me-2"></i>
                                                        <span class="comment-text">{{ $feedback->comments }}</span>
                                                    </div>
                                                </td>

                                                {{-- DATE --}}
                                                <td>
                                                    <div class="date-cell">
                                                        <div class="date-main">
                                                            {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y') }}
                                                        </div>
                                                        <div class="time-sub">
                                                            {{ \Carbon\Carbon::parse($feedback->created_at)->format('h:i A') }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="empty-state">
                                                        <div class="empty-icon">
                                                            <i class="bi bi-inbox"></i>
                                                        </div>
                                                        <p class="empty-text mt-3 mb-0">No feedback submissions yet</p>
                                                        <p class="empty-subtext">Feedback from users will appear here</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- PAGINATION --}}
                        @if($allfeedbacks->hasPages())
                            <div class="card-footer-custom">
                                {{ $allfeedbacks->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <style>
            :root {
                --admin-primary: #6366F1;
                --admin-dark: #4F46E5;
                --admin-secondary: #8B5CF6;
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
                background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-dark) 50%, var(--admin-secondary) 100%);
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
            .feedback-admin-container {
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
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.25) 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--admin-primary);
                font-size: 1.5rem;
            }

            .card-header-custom h5 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.25rem;
            }

            .stats-badges {
                display: flex;
                gap: 0.75rem;
            }

            .stat-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.875rem;
            }

            .stat-total {
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
                color: var(--admin-primary);
                border: 2px solid var(--admin-primary);
            }

            /* Card Body */
            .card-body-custom {
                padding: 0;
            }

            .table-responsive {
                overflow-x: auto;
            }

            /* Table */
            .table-custom {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .table-custom thead {
                background: linear-gradient(135deg, #F7F9FC 0%, #EEF2FF 100%);
                border-bottom: 2px solid var(--border-color);
            }

            .table-custom thead th {
                padding: 1.25rem 1.5rem;
                text-align: left;
                font-weight: 700;
                color: var(--admin-primary);
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .table-custom tbody tr {
                border-bottom: 1px solid var(--border-color);
                transition: all 0.3s ease;
            }

            .table-custom tbody tr:hover {
                background: linear-gradient(135deg, #FAFBFC 0%, #F7F9FC 100%);
            }

            .table-custom tbody td {
                padding: 1.25rem 1.5rem;
                vertical-align: middle;
            }

            /* User Email */
            .user-email {
                display: flex;
                align-items: center;
                color: var(--text-dark);
                font-weight: 500;
                font-size: 0.9rem;
            }

            .user-email i {
                color: var(--admin-primary);
                font-size: 1.1rem;
            }

            /* Rating Badge in Table */
            .rating-badge-table {
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                padding: 0.5rem 0.75rem;
                border-radius: 10px;
                font-weight: 600;
                gap: 0.35rem;
                font-size: 0.85rem;
            }

            .rating-badge-table i:first-child {
                font-size: 1.5rem;
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

            .stars-mini {
                display: flex;
                gap: 0.15rem;
                color: #FFD700;
                font-size: 0.75rem;
            }

            /* Comment Cell */
            .comment-cell {
                display: flex;
                align-items: start;
                gap: 0.5rem;
            }

            .comment-text {
                color: var(--text-dark);
                font-size: 0.9rem;
                line-height: 1.5;
                font-style: italic;
            }

            /* Date Cell */
            .date-cell {
                text-align: center;
            }

            .date-main {
                font-weight: 600;
                color: var(--text-dark);
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            .time-sub {
                font-size: 0.8rem;
                color: var(--text-muted);
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
                color: var(--text-dark);
                font-size: 1.25rem;
                font-weight: 600;
            }

            .empty-subtext {
                color: var(--text-muted);
                font-size: 1rem;
                margin-top: 0.5rem;
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
                background: var(--admin-primary);
                border-color: var(--admin-primary);
                color: white;
                transform: translateY(-2px);
            }

            .page-item.active .page-link {
                background: linear-gradient(135deg, var(--admin-primary), var(--admin-dark));
                border-color: var(--admin-primary);
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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
                .card-footer-custom {
                    padding: 1.25rem;
                }

                .table-custom thead th,
                .table-custom tbody td {
                    padding: 1rem;
                    font-size: 0.85rem;
                }

                .stats-badges {
                    width: 100%;
                    margin-top: 1rem;
                }

                .stat-badge {
                    flex: 1;
                    justify-content: center;
                }
            }
        </style>
    @endsection
@endcan