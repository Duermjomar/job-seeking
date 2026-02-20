@extends('layouts.Admin.app')

@section('content')
    <div class="notifications-wrapper">
        <div class="container-fluid px-4 py-5">

            {{-- Page Header --}}
            <div class="page-header mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1">Notifications</h3>
                            <p class="header-subtitle mb-0">
                                @if ($unreadCount > 0)
                                    You have <strong>{{ $unreadCount }}</strong> unread
                                    notification{{ $unreadCount !== 1 ? 's' : '' }}
                                @else
                                    All caught up!
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if ($unreadCount > 0)
                            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-mark-all">
                                    <i class="bi bi-check-all me-2"></i>Mark All Read
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.notifications.clear-read') }}" method="POST"
                            onsubmit="return confirm('Clear all read notifications?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-clear-read">
                                <i class="bi bi-trash me-2"></i>Clear Read
                            </button>
                        </form>
                        <a href="{{ route('dashboard') }}" class="btn btn-back">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert-custom alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Notifications List --}}
            <div class="notifications-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Notifications</h5>
                        <span class="total-badge">{{ $notifications->total() }} Total</span>
                    </div>
                </div>

                <div class="card-body-custom">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ !$notification->read ? 'unread' : '' }}"
                            data-id="{{ $notification->id }}" id="notification-{{ $notification->id }}">

                            {{-- Icon --}}
                            <div class="notification-icon {{ $notification->color ?? 'primary' }}">
                                <i class="{{ $notification->icon ?? 'bi-bell-fill' }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="notification-content">
                                <div class="notification-title">
                                    {{ $notification->title }}
                                    @if (!$notification->read)
                                        <span class="unread-dot"></span>
                                    @endif
                                </div>
                                <div class="notification-message">{{ $notification->message }}</div>
                                <div class="notification-time">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                    <span class="ms-2 text-muted">·</span>
                                    <span class="ms-2">{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="notification-actions">
                                @if (!$notification->read)
                                    <a href="{{ route('admin.notifications.mark-read', $notification->id) }}"
                                        class="btn-notif-action btn-read" title="Mark as read"
                                        onclick="setHighlight({{ $notification->id }})">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Delete this notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-notif-action btn-delete" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
                            <p class="empty-text">No notifications yet</p>
                            <p class="empty-subtext">You'll see activity and alerts here</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="card-footer-custom">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

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

        * {
            font-family: 'Outfit', sans-serif;
        }

        .notifications-wrapper {
            min-height: 100vh;
            background: var(--background-light);
        }

        .page-header {
            background: linear-gradient(135deg, #fff, #F7F9FC);
            padding: 1.5rem;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .page-header h3 {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .btn-mark-all {
            background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
            color: #0F6848;
            border: 2px solid #95E1D3;
            border-radius: 10px;
            padding: 0.5rem 1.1rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-mark-all:hover {
            background: #95E1D3;
            transform: translateY(-2px);
        }

        .btn-clear-read {
            background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
            color: #C92A2A;
            border: 2px solid #FFB3B3;
            border-radius: 10px;
            padding: 0.5rem 1.1rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-clear-read:hover {
            background: #FFB3B3;
            transform: translateY(-2px);
        }

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

        .alert-custom {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.3s ease;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
            color: #0F6848;
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

        .notifications-card {
            background: white;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #FAFBFC, #fff);
            border-bottom: 2px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-header-custom h5 {
            color: var(--text-dark);
            font-weight: 700;
        }

        .total-badge {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .card-body-custom {
            padding: 0;
        }

        /* Notification Items */
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: #FAFBFC;
        }

        .notification-item.unread {
            background: linear-gradient(135deg, #FFF5F2, #fff);
        }

        .notification-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        /* ── Highlighted (clicked) state ── */
        .notification-item.highlighted {
            background: linear-gradient(135deg, #FFF9E6, #FFFBF0) !important;
            border-left: none;
            box-shadow: 0 0 0 3px rgba(255, 230, 109, 0.6) inset;
            animation: highlightPulse 2s ease-in-out;
        }

        .notification-item.highlighted::before {
            background: linear-gradient(135deg, #FFE66D, #FFD84D) !important;
        }

        @keyframes highlightPulse {
            0% {
                box-shadow: 0 0 0 0px rgba(255, 230, 109, 0.8) inset;
            }

            30% {
                box-shadow: 0 0 0 6px rgba(255, 230, 109, 0.4) inset;
            }

            100% {
                box-shadow: 0 0 0 3px rgba(255, 230, 109, 0.6) inset;
            }
        }

        /* Notification Icon */
        .notification-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .notification-icon.primary {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .notification-icon.success {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .notification-icon.warning {
            background: linear-gradient(135deg, rgba(255, 230, 109, 0.15), rgba(255, 230, 109, 0.25));
            color: #D97706;
        }

        .notification-icon.danger {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
            color: #C92A2A;
        }

        .notification-icon.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.25));
            color: #1E40AF;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .notification-message {
            color: var(--text-muted);
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 0.4rem;
        }

        .notification-time {
            color: var(--text-muted);
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.2rem;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
            align-items: center;
        }

        .btn-notif-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            background: none;
            text-decoration: none;
        }

        .btn-read {
            background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
            color: #0F6848;
        }

        .btn-read:hover {
            background: #95E1D3;
            transform: scale(1.1);
        }

        .btn-delete {
            background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
            color: #C92A2A;
        }

        .btn-delete:hover {
            background: #FFB3B3;
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--text-muted);
            opacity: 0.3;
        }

        .empty-text {
            color: var(--text-dark);
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 1rem;
        }

        .empty-subtext {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .card-footer-custom {
            background: linear-gradient(135deg, #F7F9FC, #fff);
            border-top: 2px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 0.4rem;
            margin: 0;
        }

        .page-link {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-dark);
            font-weight: 600;
            padding: 0.5rem 0.9rem;
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

        @media (max-width: 768px) {
            .notification-item {
                flex-wrap: wrap;
            }

            .notification-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .page-header h3 {
                font-size: 1.25rem;
            }
        }
    </style>

    <script>
        const STORAGE_KEY = 'admin_highlighted_notification';

        // Called when "Mark as read" is clicked — saves the ID before navigating away
        function setHighlight(id) {
            sessionStorage.setItem(STORAGE_KEY, id);
        }

        // Also highlight when clicking anywhere on the notification row
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Don't interfere with button/form clicks
                if (e.target.closest('.notification-actions')) return;

                const id = this.dataset.id;
                sessionStorage.setItem(STORAGE_KEY, id);

                // Immediate visual highlight on click
                clearHighlights();
                this.classList.add('highlighted');
            });
        });

        function clearHighlights() {
            document.querySelectorAll('.notification-item.highlighted')
                .forEach(el => el.classList.remove('highlighted'));
        }

        // On page load: restore highlight from sessionStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedId = sessionStorage.getItem(STORAGE_KEY);
            if (!savedId) return;

            const target = document.getElementById('notification-' + savedId);
            if (!target) return;

            target.classList.add('highlighted');

            // Scroll into view smoothly
            setTimeout(() => {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 150);

            // Remove highlight after 4 seconds
            setTimeout(() => {
                target.classList.remove('highlighted');
                sessionStorage.removeItem(STORAGE_KEY);
            }, 4000);
        });
    </script>
@endsection
