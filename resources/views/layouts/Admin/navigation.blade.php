{{-- ============================================
     ADMIN NAVBAR - BOOTSTRAP VERSION
     ============================================ --}}

<nav class="navbar-custom">
    <div class="container-fluid px-4">
        <div class="navbar-content">

            {{-- Left Side - Logo Only --}}
            <div class="navbar-left">
                {{-- Logo --}}
                <div class="navbar-brand-custom">
                    <a href="{{ route('dashboard') }}" class="brand-link">
                        <div class="brand-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <span class="brand-text">Admin Panel</span>
                    </a>
                </div>
            </div>

            {{-- Right Side - Notifications & User Dropdown --}}
            <div class="navbar-right">

                {{-- Notifications Dropdown --}}
                <div class="notification-dropdown">
                    <button class="notification-bell" id="notificationBell" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-bell-fill"></i>
                        @if (auth()->user()->unreadNotificationsCount() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu"
                        aria-labelledby="notificationBell">

                        {{-- Header --}}
                        <div class="notification-dropdown-header">
                            <h6 class="mb-0">Notifications</h6>
                            @if (auth()->user()->unreadNotificationsCount() > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-mark-all">Mark all read</button>
                                </form>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="notification-dropdown-body">
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <a href="{{ route('admin.notifications.mark-read', $notification->id) }}"
                                    class="notification-dropdown-item {{ $notification->read ? 'read' : 'unread' }}">
                                    <div class="notification-dropdown-icon {{ $notification->color }}">
                                        <i class="{{ $notification->icon ?? 'bi-bell-fill' }}"></i>
                                    </div>
                                    <div class="notification-dropdown-content">
                                        <div class="notification-dropdown-title">{{ $notification->title }}</div>
                                        <div class="notification-dropdown-message">
                                            {{ Str::limit($notification->message, 60) }}</div>
                                        <div class="notification-dropdown-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    @if (!$notification->read)
                                        <span class="notification-dropdown-dot"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="notification-dropdown-empty">
                                    <i class="bi bi-bell-slash"></i>
                                    <p>No notifications</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        <div class="notification-dropdown-footer">
                            <a href="{{ route('admin.notifications.index') }}" class="btn-view-all">
                                View All Notifications <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Desktop User Dropdown with Navigation Links --}}
                <div class="dropdown user-dropdown">
                    <button class="user-dropdown-btn dropdown-toggle" type="button" id="userDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar admin-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end" aria-labelledby="userDropdown">
                        {{-- User Info Section --}}
                        <li>
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-avatar admin-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="dropdown-user-details">
                                    <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                                    <div class="dropdown-user-role admin-role">
                                        <i class="bi bi-shield-fill-check me-1"></i>{{ Auth::user()->roles[0]->name }}
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider-custom"></li>

                        {{-- Navigation Links --}}
                        <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" 
                               href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill me-2"></i>Users
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('admin.userfeedback') ? 'active' : '' }}" 
                               href="{{ route('admin.userfeedback') }}">
                                <i class="bi bi-chat-dots-fill me-2"></i>Feedbacks
                            </a>
                        </li>
                        <li><hr class="dropdown-divider-custom"></li>

                        {{-- Profile & Logout --}}
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('account.settings') }}">
                                <i class="bi bi-gear-fill me-2"></i>Account Settings
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item-custom logout-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                {{-- Mobile Hamburger --}}
                <button class="mobile-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
                    aria-controls="mobileMenu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

        </div>
    </div>
</nav>

{{-- Mobile Offcanvas Menu --}}
<div class="offcanvas offcanvas-end mobile-menu-offcanvas" tabindex="-1" id="mobileMenu"
    aria-labelledby="mobileMenuLabel">

    {{-- Header --}}
    <div class="offcanvas-header admin-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">
            <i class="bi bi-shield-fill-check me-2"></i>Admin Panel
        </h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="offcanvas-body">

        {{-- User Info --}}
        <div class="mobile-user-info">
            <div class="mobile-user-avatar admin-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="mobile-user-details">
                <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                <div class="mobile-user-role admin-role">
                    <i class="bi bi-shield-fill-check me-1"></i>{{ Auth::user()->roles[0]->name }}
                </div>
            </div>
        </div>

        {{-- Navigation Links --}}
        <div class="mobile-nav-links">
            <a href="{{ route('dashboard') }}"
                class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="mobile-nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.userfeedback') }}"
                class="mobile-nav-link {{ request()->routeIs('admin.userfeedback') ? 'active' : '' }}">
                <i class="bi bi-chat-dots-fill"></i>
                <span>Feedbacks</span>
            </a>
        </div>

        {{-- Actions --}}
        <div class="mobile-actions">
            <a href="{{ route('account.settings') }}" class="mobile-action-btn admin-btn">
                <i class="bi bi-gear-fill me-2"></i>Account Settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-action-btn logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ============================================
     STYLES - BOOTSTRAP ONLY
     ============================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

    /* ============================================
       CSS VARIABLES
       ============================================ */
    :root {
        --primary-color: #6366F1;
        --primary-dark: #4F46E5;
        --secondary-color: #8B5CF6;
        --accent-color: #A78BFA;
        --text-dark: #2D3748;
        --text-muted: #718096;
        --border-color: #E2E8F0;
        --background-light: #F7F9FC;
        --white: #FFFFFF;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    * {
        font-family: 'Outfit', sans-serif;
    }

    /* ============================================
       MAIN NAVBAR
       ============================================ */
    .navbar-custom {
        background: linear-gradient(135deg, var(--white) 0%, #FAFBFC 100%);
        border-bottom: 2px solid var(--border-color);
        padding: 0.75rem 0;
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ============================================
       LEFT SIDE - BRAND
       ============================================ */
    .navbar-left {
        display: flex;
        align-items: center;
    }

    .navbar-brand-custom {
        margin-right: 1rem;
    }

    .brand-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .brand-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        transition: all 0.3s ease;
    }

    .brand-link:hover .brand-icon {
        transform: rotate(-5deg) scale(1.05);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
    }

    .brand-text {
        font-size: 1.35rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ============================================
       RIGHT SIDE - USER & ACTIONS
       ============================================ */
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* ============================================
       NOTIFICATION BELL & DROPDOWN
       ============================================ */
    .notification-bell {
        position: relative;
        background: transparent;
        border: none;
        color: var(--text-dark);
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .notification-bell:hover {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--primary-color);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.4rem;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
        animation: bounce 0.5s ease;
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .notification-dropdown-menu {
        width: 380px;
        max-height: 500px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        padding: 0;
        margin-top: 0.5rem;
    }

    .notification-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--border-color);
        background: linear-gradient(135deg, #F7F9FC, #FFF);
    }

    .notification-dropdown-header h6 {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1rem;
    }

    .btn-mark-all {
        background: transparent;
        border: none;
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-mark-all:hover {
        background: rgba(99, 102, 241, 0.1);
    }

    .notification-dropdown-body {
        max-height: 350px;
        overflow-y: auto;
    }

    .notification-dropdown-body::-webkit-scrollbar {
        width: 6px;
    }

    .notification-dropdown-body::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 10px;
    }

    .notification-dropdown-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-dropdown-item:hover {
        background: var(--background-light);
    }

    .notification-dropdown-item.unread {
        background: linear-gradient(135deg, #F5F3FF, #FFF);
    }

    .notification-dropdown-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .notification-dropdown-icon.primary {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
        color: var(--primary-color);
    }

    .notification-dropdown-icon.success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.25));
        color: #22C55E;
    }

    .notification-dropdown-icon.warning {
        background: linear-gradient(135deg, rgba(255, 230, 109, 0.15), rgba(255, 230, 109, 0.25));
        color: #D97706;
    }

    .notification-dropdown-icon.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.25));
        color: #EF4444;
    }

    .notification-dropdown-content {
        flex: 1;
    }

    .notification-dropdown-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .notification-dropdown-message {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 0.25rem;
    }

    .notification-dropdown-time {
        color: var(--text-muted);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-dropdown-dot {
        width: 8px;
        height: 8px;
        background: var(--primary-color);
        border-radius: 50%;
        flex-shrink: 0;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .notification-dropdown-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .notification-dropdown-empty i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 0.5rem;
    }

    .notification-dropdown-empty p {
        margin: 0;
        font-weight: 500;
    }

    .notification-dropdown-footer {
        padding: 0.75rem 1.25rem;
        border-top: 2px solid var(--border-color);
        background: linear-gradient(135deg, #FFF, #F7F9FC);
    }

    .btn-view-all {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-view-all:hover {
        color: var(--primary-dark);
    }

    /* ============================================
       USER DROPDOWN (DESKTOP)
       ============================================ */
    .user-dropdown {
        display: none;
    }

    @media (min-width: 768px) {
        .user-dropdown {
            display: block;
        }
    }

    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: var(--white);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-dark);
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .user-dropdown-btn:hover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), var(--white));
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .admin-avatar {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    }

    .user-name {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .dropdown-menu-custom {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        padding: 0.5rem;
        min-width: 260px;
        margin-top: 0.5rem;
    }

    /* User Info in Dropdown */
    .dropdown-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: var(--background-light);
        border-radius: 8px;
        margin-bottom: 0.25rem;
    }

    .dropdown-user-avatar {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .dropdown-user-details {
        flex: 1;
        min-width: 0;
    }

    .dropdown-user-name {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dropdown-user-role {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        color: var(--primary-color);
        font-weight: 600;
    }

    .admin-role {
        background: rgba(99, 102, 241, 0.1);
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        border: 1px solid var(--primary-color);
    }

    .dropdown-item-custom {
        display: flex;
        align-items: center;
        padding: 0.65rem 1rem;
        color: var(--text-dark);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-item-custom:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05));
        color: var(--primary-color);
    }

    .dropdown-item-custom.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-weight: 600;
    }

    .dropdown-item-custom.active i {
        color: white;
    }

    .dropdown-item-custom.logout-btn:hover {
        background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
        color: #C92A2A;
    }

    .dropdown-divider-custom {
        margin: 0.5rem 0;
        border-top: 1px solid var(--border-color);
    }

    /* ============================================
       MOBILE TOGGLE BUTTON
       ============================================ */
    .mobile-toggle {
        display: flex;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .hamburger-line {
        width: 24px;
        height: 3px;
        background: var(--text-dark);
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .mobile-toggle:hover .hamburger-line {
        background: var(--primary-color);
    }

    @media (min-width: 768px) {
        .mobile-toggle {
            display: none;
        }
    }

    /* ============================================
       MOBILE MENU OFFCANVAS
       ============================================ */
    .mobile-menu-offcanvas {
        width: 320px !important;
    }

    .mobile-menu-offcanvas .offcanvas-header.admin-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }

    .mobile-menu-offcanvas .offcanvas-title {
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .btn-close-custom {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close-custom:hover {
        transform: rotate(90deg);
    }

    .mobile-user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--background-light);
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .mobile-user-avatar {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .mobile-user-details {
        flex: 1;
    }

    .mobile-user-name {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.05rem;
        margin-bottom: 0.25rem;
    }

    .mobile-user-email {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .mobile-user-role {
        display: inline-flex;
        align-items: center;
        background: var(--white);
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .mobile-nav-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        color: var(--text-dark);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .mobile-nav-link i {
        font-size: 1.25rem;
        width: 24px;
        text-align: center;
    }

    .mobile-nav-link:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05));
        color: var(--primary-color);
    }

    .mobile-nav-link.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .mobile-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--border-color);
    }

    .mobile-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.85rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        width: 100%;
        cursor: pointer;
    }

    .mobile-action-btn.admin-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .mobile-action-btn.admin-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .mobile-action-btn.logout {
        background: linear-gradient(135deg, #FFB3B3, #FF9999);
        color: #C92A2A;
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
    }

    .mobile-action-btn.logout:hover {
        background: linear-gradient(135deg, #FF9999, #FF6B6B);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
    }

    /* ============================================
       RESPONSIVE ADJUSTMENTS
       ============================================ */
    @media (max-width: 768px) {
        .notification-dropdown-menu {
            width: 320px;
        }
    }

    @media (max-width: 576px) {
        .brand-text {
            display: none;
        }

        .user-name {
            display: none;
        }
    }
</style>

{{-- ============================================
     JAVASCRIPT - AUTO-REFRESH NOTIFICATIONS
     ============================================ --}}
<script>
    (function() {
        'use strict';

        // Configuration
        const REFRESH_INTERVAL = 30000; // 30 seconds
        const NOTIFICATION_COUNT_URL = '{{ route('admin.notifications.unread-count') }}';

        /**
         * Updates the notification badge with the latest count
         */
        function updateNotificationBadge(count) {
            const badge = document.querySelector('.notification-badge');
            const bell = document.querySelector('.notification-bell');

            if (!bell) return;

            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'notification-badge';
                    newBadge.textContent = count;
                    bell.appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        }

        /**
         * Fetches the current unread notification count
         */
        function fetchNotificationCount() {
            fetch(NOTIFICATION_COUNT_URL)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (typeof data.count === 'number') {
                        updateNotificationBadge(data.count);
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }

        /**
         * Initialize auto-refresh
         */
        function init() {
            setInterval(fetchNotificationCount, REFRESH_INTERVAL);
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

    })();
</script>