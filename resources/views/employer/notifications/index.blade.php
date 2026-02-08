@extends('layouts.Employer.app')

@section('content')
<div class="notifications-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Page Header --}}
                <div class="page-header mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Notifications</h3>
                                <p class="text-muted mb-0">
                                    @if($unreadCount > 0)
                                        You have {{ $unreadCount }} unread {{ Str::plural('notification', $unreadCount) }}
                                    @else
                                        All caught up!
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if($unreadCount > 0)
                                <form action="{{ route('employer.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-mark-read">
                                        <i class="bi bi-check-all me-2"></i>Mark All Read
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('employer.notifications.clear-read') }}" method="POST" 
                                  onsubmit="return confirm('Clear all read notifications?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-clear">
                                    <i class="bi bi-trash me-2"></i>Clear Read
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-custom alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Notifications List --}}
                <div class="notifications-container">
                    @forelse($notifications as $notification)
                        <div class="notification-card {{ $notification->read ? 'read' : 'unread' }}">
                            <div class="notification-content">
                                {{-- Icon --}}
                                <div class="notification-icon {{ $notification->color }}">
                                    <i class="{{ $notification->icon ?? 'bi-bell-fill' }}"></i>
                                </div>

                                {{-- Content --}}
                                <div class="notification-body">
                                    <div class="notification-header">
                                        <h6 class="notification-title">{{ $notification->title }}</h6>
                                        <span class="notification-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="notification-message">{{ $notification->message }}</p>
                                    
                                    {{-- Action Button --}}
                                    @if($notification->action_url)
                                        <a href="{{ route('employer.notifications.mark-read', $notification->id) }}" 
                                           class="btn-view-action">
                                            <i class="bi bi-arrow-right-circle me-1"></i>
                                            View Details
                                        </a>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="notification-actions">
                                    @if(!$notification->read)
                                        <form action="{{ route('employer.notifications.mark-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action btn-mark" title="Mark as read">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('employer.notifications.destroy', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete"
                                                onclick="return confirm('Delete this notification?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Unread Indicator --}}
                            @if(!$notification->read)
                                <div class="unread-indicator"></div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-bell-slash"></i>
                            </div>
                            <h4 class="empty-title">No Notifications</h4>
                            <p class="empty-text">You don't have any notifications yet.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                @endif

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
        --text-dark: #2D3748;
        --text-muted: #718096;
        --border-color: #E2E8F0;
        --background-light: #F7F9FC;
    }

    * { font-family: 'Outfit', sans-serif; }
    body { background: var(--background-light); }
    .notifications-wrapper { min-height: 100vh; }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #FFF 0%, #F7F9FC 100%);
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .header-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, rgba(255,107,53,0.15), rgba(255,107,53,0.25));
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
    }

    /* Action Buttons */
    .btn-mark-read, .btn-clear {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-mark-read {
        background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
        color: white;
        box-shadow: 0 2px 8px rgba(78,205,196,0.3);
    }

    .btn-mark-read:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78,205,196,0.4);
    }

    .btn-clear {
        background: white;
        color: var(--text-muted);
        border: 2px solid var(--border-color);
    }

    .btn-clear:hover {
        background: #FF6B6B;
        color: white;
        border-color: #FF6B6B;
    }

    /* Alert */
    .alert-custom {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        animation: slideDown 0.3s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
        color: #0F6848;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Notifications Container */
    .notifications-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Notification Card */
    .notification-card {
        background: white;
        border-radius: 14px;
        border: 2px solid var(--border-color);
        padding: 1.5rem;
        position: relative;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease;
    }

    .notification-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .notification-card.unread {
        background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
        border-left: 4px solid var(--primary-color);
    }

    .notification-card.read {
        opacity: 0.8;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Notification Content */
    .notification-content {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .notification-icon.primary {
        background: linear-gradient(135deg, rgba(255,107,53,0.15), rgba(255,107,53,0.25));
        color: var(--primary-color);
    }

    .notification-icon.success {
        background: linear-gradient(135deg, rgba(78,205,196,0.15), rgba(78,205,196,0.25));
        color: var(--secondary-color);
    }

    .notification-icon.warning {
        background: linear-gradient(135deg, rgba(255,230,109,0.15), rgba(255,230,109,0.25));
        color: #D97706;
    }

    .notification-icon.danger {
        background: linear-gradient(135deg, rgba(255,107,107,0.15), rgba(255,107,107,0.25));
        color: #FF6B6B;
    }

    .notification-body {
        flex: 1;
    }

    .notification-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .notification-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
    }

    .notification-time {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-message {
        color: var(--text-dark);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }

    .btn-view-action {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-view-action:hover {
        background: var(--primary-color);
        color: white;
    }

    /* Notification Actions */
    .notification-actions {
        display: flex;
        gap: 0.5rem;
        flex-direction: column;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-mark {
        background: linear-gradient(135deg, rgba(78,205,196,0.15), rgba(78,205,196,0.25));
        color: var(--secondary-color);
    }

    .btn-mark:hover {
        background: var(--secondary-color);
        color: white;
        transform: scale(1.1);
    }

    .btn-delete {
        background: linear-gradient(135deg, rgba(255,107,107,0.15), rgba(255,107,107,0.25));
        color: #FF6B6B;
    }

    .btn-delete:hover {
        background: #FF6B6B;
        color: white;
        transform: scale(1.1);
    }

    /* Unread Indicator */
    .unread-indicator {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 10px;
        height: 10px;
        background: var(--primary-color);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .empty-icon {
        font-size: 5rem;
        color: var(--text-muted);
        opacity: 0.5;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: var(--text-muted);
        font-size: 1.05rem;
    }

    /* Pagination */
    .pagination {
        gap: 0.5rem;
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
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(255,107,53,0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header { padding: 1.25rem; }
        .header-icon { width: 56px; height: 56px; font-size: 1.5rem; }
        .page-header h3 { font-size: 1.25rem; }
        
        .notification-content {
            flex-direction: column;
        }
        
        .notification-actions {
            flex-direction: row;
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        
        .unread-indicator {
            top: 1rem;
            right: 6rem;
        }
    }
</style>
@endsection