@extends('layouts.Admin.app')

@can('admin-access')
    @section('content')
        <div class="feedback-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- HEADER — matches dashboard header style --}}
                <div class="d-flex justify-content-start align-items-center mb-5 flex-wrap gap-3">
                    <div>
                        <h1 class="h3 fw-bold mb-1 header-title">
                            <i class="bi bi-chat-left-quote-fill me-2 header-icon-inline"></i>User Feedbacks
                        </h1>
                        <p class="header-subtitle mb-0">Manage and review all user feedback submissions</p>
                    </div>
                    {{-- <div class="header-date">
                        <i class="bi bi-calendar3 me-2"></i>
                        {{ date('F d, Y') }}
                    </div> --}}
                </div>

        

                {{-- HIGHLIGHT NOTICE --}}
                @if (request('highlight'))
                    <div class="highlight-notice mb-4" id="highlightNotice">
                        <i class="bi bi-bell-fill me-2"></i>
                        Showing the feedback that triggered your notification.
                        <button type="button" class="highlight-notice-close" onclick="dismissHighlight()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                {{-- FEEDBACK TABLE CARD — same card shell as dashboard --}}
                <div class="feedback-list-card">

                    {{-- CARD HEADER --}}
                    <div class="card-header-custom">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="bi bi-inbox-fill me-2"></i>All Feedback Submissions
                            </h5>
                            <span class="submissions-count">
                                {{ $allfeedbacks->total() }} {{ Str::plural('record', $allfeedbacks->total()) }}
                            </span>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th width="25%"><i class="bi bi-envelope me-2"></i>User Email</th>
                                        <th width="15%"><i class="bi bi-star-half me-2"></i>Rating</th>
                                        <th width="40%"><i class="bi bi-chat-dots me-2"></i>Comments</th>
                                        <th width="20%"><i class="bi bi-calendar-check me-2"></i>Date Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allfeedbacks as $feedback)
                                        <tr class="{{ request('highlight') == $feedback->id ? 'row-highlighted' : '' }}"
                                            id="feedback-row-{{ $feedback->id }}" data-feedback-id="{{ $feedback->id }}">

                                            {{-- EMAIL --}}
                                            <td>
                                                <div class="user-email">
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    {{ $feedback->email }}
                                                </div>
                                            </td>

                                            {{-- RATING --}}
                                            <td>
                                                @if ($feedback->rate == 1)
                                                    <div class="rating-badge-table rating-poor">
                                                        <i class="bi bi-emoji-frown me-1"></i>
                                                        <span>Poor</span>
                                                        <div class="stars-mini"><i class="bi bi-star-fill"></i></div>
                                                    </div>
                                                @elseif ($feedback->rate == 2)
                                                    <div class="rating-badge-table rating-fair">
                                                        <i class="bi bi-emoji-neutral me-1"></i>
                                                        <span>Fair</span>
                                                        <div class="stars-mini">
                                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                        </div>
                                                    </div>
                                                @elseif ($feedback->rate == 3)
                                                    <div class="rating-badge-table rating-good">
                                                        <i class="bi bi-emoji-smile me-1"></i>
                                                        <span>Good</span>
                                                        <div class="stars-mini">
                                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                                class="bi bi-star-fill"></i>
                                                        </div>
                                                    </div>
                                                @elseif ($feedback->rate == 4)
                                                    <div class="rating-badge-table rating-great">
                                                        <i class="bi bi-emoji-laughing me-1"></i>
                                                        <span>Great</span>
                                                        <div class="stars-mini">
                                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                        </div>
                                                    </div>
                                                @elseif ($feedback->rate == 5)
                                                    <div class="rating-badge-table rating-outstanding">
                                                        <i class="bi bi-emoji-heart-eyes me-1"></i>
                                                        <span>Outstanding</span>
                                                        <div class="stars-mini">
                                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                                class="bi bi-star-fill"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="rating-badge-table rating-unknown">
                                                        <i class="bi bi-question-circle me-1"></i>
                                                        <span>N/A</span>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- COMMENT — expandable --}}
                                            <td>
                                                @php
                                                    $comment = $feedback->comments ?? '';
                                                    $isLong = mb_strlen($comment) > 100;
                                                    $preview = $isLong ? mb_substr($comment, 0, 100) . '…' : $comment;
                                                    $rowId = 'comment-' . $feedback->id;
                                                @endphp
                                                <div class="comment-cell">
                                                    <i class="bi bi-quote text-muted me-2"></i>
                                                    <span>
                                                        <span class="comment-text"
                                                            id="{{ $rowId }}-preview">{{ $preview }}</span>
                                                        @if ($isLong)
                                                            <span class="comment-text d-none"
                                                                id="{{ $rowId }}-full">{{ $comment }}</span>
                                                            <button class="btn-expand"
                                                                onclick="toggleComment('{{ $rowId }}')">
                                                                <span id="{{ $rowId }}-label">Show more</span>
                                                                <i class="bi bi-chevron-down"
                                                                    id="{{ $rowId }}-icon"></i>
                                                            </button>
                                                        @endif
                                                    </span>
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
                                                <div class="empty-activity">
                                                    <i class="bi bi-inbox"></i>
                                                    <p class="mb-0 fw-semibold">No feedback submissions yet</p>
                                                    <p class="small text-muted mt-1">Feedback from users will appear here</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION --}}
                    @if ($allfeedbacks->hasPages())
                        <div class="card-footer-custom">
                            {{ $allfeedbacks->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

            /* ── Design tokens — exact match to dashboard ── */
            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --text-dark: #2D3748;
                --text-muted: #718096;
                --border-color: #E2E8F0;
                --background-light: #F7F9FC;
            }

            * {
                font-family: 'Outfit', sans-serif;
            }

            .feedback-wrapper {
                min-height: 100vh;
                background: var(--background-light);
            }

            /* ── Header ── */
            .header-title {
                color: var(--text-dark);
            }

            .header-icon-inline {
                color: var(--primary-color);
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
                box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
                border: 2px solid var(--border-color);
            }

            .header-date i {
                color: var(--primary-color);
            }

            /* ── Stat Cards — identical to dashboard ── */
            .stat-card {
                border: none;
                border-radius: 16px;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
                animation: slideUp 0.5s ease-out both;
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
                box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            }

            .stat-card:hover::before {
                transform: translate(10%, -10%) scale(1.2);
            }

            .stat-card:hover .stat-icon-wrap {
                transform: rotate(-5deg) scale(1.05);
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

            .stat-total-card {
                background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
                border-left: 4px solid var(--primary-color);
            }

            .stat-total-card::before {
                background: var(--primary-color);
            }

            .stat-avg-card {
                background: linear-gradient(135deg, #FFFBF0 0%, #FFFFFF 100%);
                border-left: 4px solid #D97706;
            }

            .stat-avg-card::before {
                background: #D97706;
            }

            .stat-outstanding-card {
                background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
                border-left: 4px solid var(--secondary-color);
            }

            .stat-outstanding-card::before {
                background: var(--secondary-color);
            }

            .stat-poor-card {
                background: linear-gradient(135deg, #FFF0F0 0%, #FFFFFF 100%);
                border-left: 4px solid #FC8181;
            }

            .stat-poor-card::before {
                background: #FC8181;
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

            .icon-total {
                background: linear-gradient(135deg, rgba(255, 107, 53, .15), rgba(255, 107, 53, .25));
                color: var(--primary-color);
            }

            .icon-avg {
                background: linear-gradient(135deg, rgba(217, 119, 6, .15), rgba(217, 119, 6, .25));
                color: #D97706;
            }

            .icon-outstanding {
                background: linear-gradient(135deg, rgba(78, 205, 196, .15), rgba(78, 205, 196, .25));
                color: var(--secondary-color);
            }

            .icon-poor {
                background: linear-gradient(135deg, rgba(252, 129, 129, .15), rgba(252, 129, 129, .25));
                color: #E53E3E;
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 800;
                color: var(--text-dark);
            }

            .stat-number-sub {
                font-size: 1rem;
                font-weight: 600;
                color: var(--text-muted);
            }

            .stat-label {
                color: var(--text-muted);
                font-size: 0.9rem;
                font-weight: 600;
            }

            /* ── Highlight Notice ── */
            .highlight-notice {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                background: linear-gradient(135deg, #FFF5F2, #FFE8DC);
                border: 2px solid var(--primary-color);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                color: var(--primary-dark);
                font-weight: 600;
                font-size: 0.95rem;
                animation: slideDown 0.4s ease-out;
            }

            .highlight-notice i:first-child {
                color: var(--primary-color);
                flex-shrink: 0;
            }

            .highlight-notice-close {
                margin-left: auto;
                background: none;
                border: none;
                color: var(--primary-color);
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 6px;
                display: flex;
                align-items: center;
                transition: background 0.2s;
            }

            .highlight-notice-close:hover {
                background: rgba(255, 107, 53, .15);
            }

            /* ── Feedback Table Card — same shell as dashboard cards ── */
            .feedback-list-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
                border: 2px solid var(--border-color);
                overflow: hidden;
                animation: slideUp 0.5s ease-out 0.2s both;
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

            .submissions-count {
                background: linear-gradient(135deg, rgba(255, 107, 53, .15), rgba(255, 107, 53, .25));
                color: var(--primary-color);
                border: 2px solid var(--primary-color);
                padding: 0.35rem 0.85rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.85rem;
            }

            /* ── Table ── */
            .table-custom {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .table-custom thead {
                background: linear-gradient(135deg, #FAFBFC 0%, #FFF5F2 100%);
                border-bottom: 2px solid var(--border-color);
            }

            .table-custom thead th {
                padding: 1.25rem 1.5rem;
                text-align: left;
                font-weight: 700;
                color: var(--primary-color);
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .table-custom tbody tr {
                border-bottom: 1px solid var(--border-color);
                transition: background 0.2s ease;
            }

            .table-custom tbody tr:last-child {
                border-bottom: none;
            }

            .table-custom tbody tr:hover:not(.row-highlighted) {
                background: var(--background-light);
            }

            .table-custom tbody td {
                padding: 1.25rem 1.5rem;
                vertical-align: middle;
            }

            /* ── Highlighted Row ── */
            @keyframes rowPulse {
                0% {
                    box-shadow: inset 0 0 0 0 rgba(255, 107, 53, 0.4);
                }

                40% {
                    box-shadow: inset 0 0 0 4px rgba(255, 107, 53, 0.2);
                }

                100% {
                    box-shadow: inset 0 0 0 0 rgba(255, 107, 53, 0.0);
                }
            }

            .row-highlighted {
                background: linear-gradient(135deg, #FFF5F2 0%, #FFE8DC 100%) !important;
                border-left: 4px solid var(--primary-color) !important;
                animation: rowPulse 2s ease-in-out 3;
            }

            /* ── User Email ── */
            .user-email {
                display: flex;
                align-items: center;
                color: var(--text-dark);
                font-weight: 500;
                font-size: 0.9rem;
            }

            .user-email i {
                color: var(--primary-color);
                font-size: 1.1rem;
            }

            /* ── Rating Badges ── */
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
                background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
                color: #C92A2A;
                border: 2px solid #FF6B6B;
            }

            .rating-fair {
                background: linear-gradient(135deg, #FFF0E5, #FFE0CC);
                color: #C05621;
                border: 2px solid #FFA07A;
            }

            .rating-good {
                background: linear-gradient(135deg, #FFF9E6, #FFF4CC);
                color: #D97706;
                border: 2px solid #FFE66D;
            }

            .rating-great {
                background: linear-gradient(135deg, #EEF9EE, #D4EDDA);
                color: #276749;
                border: 2px solid #68D391;
            }

            .rating-outstanding {
                background: linear-gradient(135deg, #F0FFFE, #CCF5F2);
                color: #0E7490;
                border: 2px solid var(--secondary-color);
            }

            .rating-unknown {
                background: #F7F9FC;
                color: var(--text-muted);
                border: 2px solid var(--border-color);
            }

            .stars-mini {
                display: flex;
                gap: 0.15rem;
                color: #FFD700;
                font-size: 0.75rem;
            }

            /* ── Comment Cell ── */
            .comment-cell {
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .comment-text {
                color: var(--text-dark);
                font-size: 0.9rem;
                line-height: 1.5;
                font-style: italic;
                word-break: break-word;
            }

            .btn-expand {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                margin-top: 0.35rem;
                background: none;
                border: none;
                padding: 0;
                color: var(--primary-color);
                font-size: 0.8rem;
                font-weight: 600;
                cursor: pointer;
                text-decoration: underline;
                text-underline-offset: 2px;
                transition: opacity 0.2s;
            }

            .btn-expand:hover {
                opacity: 0.75;
            }

            .btn-expand i {
                transition: transform 0.25s ease;
                font-size: 0.75rem;
            }

            .btn-expand.expanded i {
                transform: rotate(180deg);
            }

            /* ── Date Cell ── */
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

            /* ── Empty State — same as dashboard ── */
            .empty-activity {
                text-align: center;
                padding: 3rem 2rem;
                color: var(--text-muted);
            }

            .empty-activity i {
                font-size: 3rem;
                opacity: 0.4;
                display: block;
                margin-bottom: 0.75rem;
            }

            /* ── Card Footer / Pagination ── */
            .card-footer-custom {
                background: linear-gradient(135deg, #FAFBFC 0%, #FFFFFF 100%);
                border-top: 2px solid var(--border-color);
                padding: 1.5rem;
                display: flex;
                justify-content: center;
            }

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
                box-shadow: 0 4px 12px rgba(255, 107, 53, .3);
            }

            /* ── Animations ── */
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

            /* ── Responsive ── */
            @media (max-width: 768px) {
                .header-date {
                    width: 100%;
                    text-align: center;
                }

                .stat-number {
                    font-size: 1.75rem;
                }

                .table-custom thead th,
                .table-custom tbody td {
                    padding: 1rem;
                    font-size: 0.85rem;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const highlightId = {{ intval(request('highlight')) ?: 'null' }};

                if (highlightId) {
                    const row = document.getElementById('feedback-row-' + highlightId);

                    if (row) {
                        setTimeout(() => row.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        }), 300);
                        setTimeout(() => row.classList.remove('row-highlighted'), 6000);
                    } else {
                        const notice = document.getElementById('highlightNotice');
                        if (notice) {
                            notice.innerHTML =
                                '<i class="bi bi-exclamation-circle-fill me-2"></i>' +
                                'The feedback item may be on a different page. Use pagination to find it.' +
                                '<button type="button" class="highlight-notice-close" onclick="dismissHighlight()">' +
                                '<i class="bi bi-x-lg"></i></button>';
                            notice.style.background = 'linear-gradient(135deg,#FFF4E6,#FFE8CC)';
                            notice.style.borderColor = '#D97706';
                            notice.style.color = '#D97706';
                        }
                    }
                }
            });

            function dismissHighlight() {
                const notice = document.getElementById('highlightNotice');
                if (notice) {
                    notice.style.transition = 'all 0.3s ease';
                    notice.style.opacity = '0';
                    notice.style.transform = 'translateY(-10px)';
                    setTimeout(() => notice.remove(), 300);
                }
            }

            function toggleComment(rowId) {
                const preview = document.getElementById(rowId + '-preview');
                const full = document.getElementById(rowId + '-full');
                const label = document.getElementById(rowId + '-label');
                const btn = preview.closest('.comment-cell').querySelector('.btn-expand');
                const isExpanded = !full.classList.contains('d-none');

                if (isExpanded) {
                    full.classList.add('d-none');
                    preview.classList.remove('d-none');
                    label.textContent = 'Show more';
                    btn.classList.remove('expanded');
                } else {
                    preview.classList.add('d-none');
                    full.classList.remove('d-none');
                    label.textContent = 'Show less';
                    btn.classList.add('expanded');
                }
            }
        </script>
    @endsection
@endcan
