{{-- ============================================
     JOBFINDER NAVBAR - BOOTSTRAP VERSION
     ============================================ --}}

<nav class="navbar-custom">
    <div class="container-fluid px-4">
        <div class="navbar-content">

            {{-- Left Side - Logo Only --}}
            <div class="navbar-left">
                <div class="navbar-brand-custom">
                    <a href="{{ route('dashboard') }}" class="brand-link">
                        <div class="brand-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <span class="brand-text">JobFinder</span>
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

                        <div class="notification-dropdown-header">
                            <h6 class="mb-0">Notifications</h6>
                            @if (auth()->user()->unreadNotificationsCount() > 0)
                                <form action="{{ route('employer.notifications.mark-all-read') }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-mark-all">Mark all read</button>
                                </form>
                            @endif
                        </div>

                        <div class="notification-dropdown-body">
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <a href="{{ route('employer.notifications.mark-read', $notification->id) }}"
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

                        <div class="notification-dropdown-footer">
                            <a href="{{ route('employer.notifications.index') }}" class="btn-view-all">
                                View All Notifications <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Desktop User Dropdown --}}
                {{-- data-bs-auto-close="outside" keeps dropdown open when clicking inside --}}
                <div class="dropdown user-dropdown">
                    <button class="user-dropdown-btn dropdown-toggle" type="button" id="userDropdown"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end" aria-labelledby="userDropdown">

                        {{-- User Info --}}
                        <li>
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="dropdown-user-details">
                                    <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                                    <div class="dropdown-user-role">
                                        <i class="bi bi-shield-check me-1"></i>{{ Auth::user()->roles[0]->name }}
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider-custom">
                        </li>

                        {{-- Dashboard --}}
                        <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door me-2"></i>Dashboard
                            </a>
                        </li>

                        {{-- <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('employer.feedback.create') ? 'active' : '' }}"
                                href="{{ route('employer.feedback.create') }}">
                                <i class="bi bi-chat-dots me-2"></i>Send Feedback
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item-custom {{ request()->routeIs('employer.myfeedback') ? 'active' : '' }}"
                                href="{{ route('employer.myfeedback') }}">
                                <i class="bi bi-envelope-check me-2"></i>My Feedbacks
                            </a>
                        </li> --}}

                        <li>
                            <hr class="dropdown-divider-custom">
                        </li>

                        {{-- ── Applicants by Status (Collapsible) ── --}}
                        @php
                            use App\Models\Application;

                            $employerJobIds = Auth::user()->jobs()->pluck('id');

                            $statusCounts = Application::whereIn('job_id', $employerJobIds)
                                ->selectRaw('application_status, COUNT(*) as total')
                                ->groupBy('application_status')
                                ->pluck('total', 'application_status');

                            $totalApplicants = $statusCounts->sum();

                            $statuses = [
                                'pending' => ['label' => 'Pending', 'icon' => 'bi-clock', 'badge' => 'b-pending'],
                                'reviewed' => ['label' => 'Reviewed', 'icon' => 'bi-eye', 'badge' => 'b-reviewed'],
                                'shortlisted' => [
                                    'label' => 'Shortlisted',
                                    'icon' => 'bi-star',
                                    'badge' => 'b-shortlisted',
                                ],
                                'interview_scheduled' => [
                                    'label' => 'Interview',
                                    'icon' => 'bi-calendar-check',
                                    'badge' => 'b-interview',
                                ],
                                'interviewed' => [
                                    'label' => 'Interviewed',
                                    'icon' => 'bi-chat-dots',
                                    'badge' => 'b-interviewed',
                                ],
                                'accepted' => [
                                    'label' => 'Accepted',
                                    'icon' => 'bi-check-circle',
                                    'badge' => 'b-accepted',
                                ],
                                'rejected' => ['label' => 'Rejected', 'icon' => 'bi-x-circle', 'badge' => 'b-rejected'],
                            ];
                        @endphp

                        {{-- Collapsible trigger row --}}
                        <li>
                            <button type="button" class="dropdown-item-custom collapsible-trigger"
                                onclick="toggleStatusPanel(event)">
                                <i class="bi bi-people-fill me-2" style="color: var(--primary-color);"></i>
                                <span class="flex-grow-1 text-start">Applicants by Status</span>
                                <span class="total-badge">{{ $totalApplicants }}</span>
                                <i class="bi bi-chevron-down trigger-arrow" id="statusArrow"></i>
                            </button>
                        </li>

                        {{-- Collapsible panel --}}
                        <li class="status-panel-li" id="statusPanel">
                            <div class="status-panel-inner">
                                @foreach ($statuses as $key => $meta)
                                    <a class="status-row"
                                        href="{{ route('employer.applicants.byStatus', ['status' => $key]) }}">
                                        <i class="bi {{ $meta['icon'] }} status-row-icon"></i>
                                        <span class="status-row-label">{{ $meta['label'] }}</span>
                                   
                                    </a>
                                @endforeach
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider-custom">
                        </li>

                        {{-- Account & Logout --}}
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

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">
            <i class="bi bi-briefcase-fill me-2"></i>JobFinder
        </h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="offcanvas-body">

        <div class="mobile-user-info">
            <div class="mobile-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="mobile-user-details">
                <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                <div class="mobile-user-role">
                    <i class="bi bi-shield-check me-1"></i>{{ Auth::user()->roles[0]->name }}
                </div>
            </div>
        </div>

        <div class="mobile-nav-links">
            <a href="{{ route('dashboard') }}"
                class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>

            {{-- <a href="{{ route('employer.feedback.create') }}"
                class="mobile-nav-link {{ request()->routeIs('employer.feedback.create') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i>
                <span>Send Feedback</span>
            </a>
            <a href="{{ route('employer.myfeedback') }}"
                class="mobile-nav-link {{ request()->routeIs('employer.myfeedback') ? 'active' : '' }}">
                <i class="bi bi-envelope-check"></i>
                <span>My Feedbacks</span>
            </a> --}}

            {{-- Mobile: Collapsible Applicants by Status --}}
            <button type="button" class="mobile-nav-link mobile-collapsible-btn"
                onclick="toggleMobileStatusPanel()">
                <i class="bi bi-people-fill"></i>
                <span class="flex-grow-1 text-start">Applicants by Status</span>
                <span class="total-badge-mobile">{{ $totalApplicants }}</span>
                <i class="bi bi-chevron-down" id="mobileStatusArrow"
                    style="font-size:0.8rem; transition: transform 0.25s ease;"></i>
            </button>

            <div class="mobile-status-panel" id="mobileStatusPanel">
                @foreach ($statuses as $key => $meta)
                    <a class="mobile-status-row"
                        href="{{ route('employer.applicants.byStatus', ['status' => $key]) }}">
                        <i class="bi {{ $meta['icon'] }} mobile-status-row-icon"></i>
                        <span class="mobile-status-row-label">{{ $meta['label'] }}</span>
                     
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mobile-actions">
            <a href="{{ route('account.settings') }}" class="mobile-action-btn">
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

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

    :root {
        --primary-color: #FF6B35;
        --primary-dark: #E85A2A;
        --secondary-color: #4ECDC4;
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

    /* ─── Navbar ─────────────────────────────────── */
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
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        transition: all 0.3s ease;
    }

    .brand-link:hover .brand-icon {
        transform: rotate(-5deg) scale(1.05);
        box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
    }

    .brand-text {
        font-size: 1.35rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* ─── Bell ───────────────────────────────────── */
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

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    /* ─── Notification dropdown ──────────────────── */
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
        background: rgba(255, 107, 53, 0.1);
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
        background: linear-gradient(135deg, #FFF5F2, #FFF);
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
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
        color: var(--primary-color);
    }

    .notification-dropdown-icon.success {
        background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
        color: var(--secondary-color);
    }

    .notification-dropdown-icon.warning {
        background: linear-gradient(135deg, rgba(255, 230, 109, 0.15), rgba(255, 230, 109, 0.25));
        color: #D97706;
    }

    .notification-dropdown-icon.danger {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
        color: #FF6B6B;
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

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
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

    /* ─── User Dropdown ──────────────────────────── */
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
        background: linear-gradient(135deg, #FFF5F2 0%, var(--white) 100%);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
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
        min-width: 280px;
        margin-top: 0.5rem;
    }

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
        background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
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
        color: var(--secondary-color);
        font-weight: 600;
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
        font-size: 0.9rem;
    }

    .dropdown-item-custom:hover {
        background: linear-gradient(135deg, #FFF5F2 0%, #FFE8E0 100%);
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
        margin: 0.4rem 0;
        border-top: 1px solid var(--border-color);
    }

    /* ─── Collapsible trigger ────────────────────── */
    .collapsible-trigger {
        gap: 0;
    }

    .collapsible-trigger:hover {
        background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
        color: var(--primary-color);
    }

    .total-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        padding: 0 0.35rem;
        border-radius: 20px;
        margin-left: auto;
        margin-right: 0.4rem;
        flex-shrink: 0;
    }

    .trigger-arrow {
        font-size: 0.75rem;
        color: var(--text-muted);
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .trigger-arrow.open {
        transform: rotate(180deg);
    }

    /* ─── Collapsible panel ──────────────────────── */
    .status-panel-li {
        list-style: none;
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
        padding: 0;
        margin: 0;
    }

    .status-panel-li.open {
        max-height: 500px;
    }

    .status-panel-inner {
        background: var(--background-light);
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        margin: 0.15rem 0.5rem 0.4rem;
        padding: 0.3rem;
    }

    /* each status row inside panel */
    .status-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.48rem 0.75rem;
        border-radius: 7px;
        text-decoration: none;
        color: var(--text-dark);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.18s ease;
    }

    .status-row:hover {
        background: white;
        color: var(--primary-color);
        box-shadow: 0 2px 6px rgba(255, 107, 53, 0.1);
    }

    .status-row:hover .status-row-icon {
        color: var(--primary-color);
    }

    .status-row-icon {
        font-size: 0.88rem;
        color: var(--text-muted);
        width: 16px;
        flex-shrink: 0;
        transition: color 0.18s;
    }

    .status-row-label {
        flex: 1;
    }



    .b-pending {
        background: #FFF4E6;
        color: #D97706;
        border: 1px solid #FDE68A;
    }

    .b-reviewed {
        background: #E0E7FF;
        color: #4338CA;
        border: 1px solid #C7D2FE;
    }

    .b-shortlisted {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .b-interview {
        background: #DBEAFE;
        color: #1E40AF;
        border: 1px solid #BFDBFE;
    }

    .b-interviewed {
        background: #E9D5FF;
        color: #6B21A8;
        border: 1px solid #D8B4FE;
    }

    .b-accepted {
        background: #D5F4E6;
        color: #0F6848;
        border: 1px solid #6EE7B7;
    }

    .b-rejected {
        background: #FFE5E5;
        color: #C92A2A;
        border: 1px solid #FCA5A5;
    }

    /* ─── Mobile toggle btn ──────────────────────── */
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

    /* ─── Offcanvas ──────────────────────────────── */
    .mobile-menu-offcanvas {
        width: 320px !important;
    }

    .mobile-menu-offcanvas .offcanvas-header {
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
        background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
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
        color: var(--secondary-color);
        border: 1px solid var(--secondary-color);
    }

    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
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
        background: linear-gradient(135deg, #FFF5F2 0%, #FFE8E0 100%);
        color: var(--primary-color);
    }

    .mobile-nav-link.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    }

    /* Mobile collapsible trigger */
    .mobile-collapsible-btn {
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        font-size: 1rem;
    }

    .total-badge-mobile {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        padding: 0 0.35rem;
        border-radius: 20px;
        margin-left: auto;
        margin-right: 0.25rem;
        flex-shrink: 0;
    }

    /* Mobile collapsible panel */
    .mobile-status-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: var(--background-light);
        border-radius: 10px;
        margin: 0.25rem 0;
    }

    .mobile-status-panel.open {
        max-height: 500px;
        padding: 0.35rem;
    }

    .mobile-status-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.85rem;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-dark);
        font-size: 0.88rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .mobile-status-row:hover {
        background: white;
        color: var(--primary-color);
    }

    .mobile-status-row:hover .mobile-status-row-icon {
        color: var(--primary-color);
    }

    .mobile-status-row-icon {
        font-size: 1rem;
        color: var(--text-muted);
        width: 20px;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .mobile-status-row-label {
        flex: 1;
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

    .mobile-action-btn:not(.logout) {
        background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
        color: white;
        box-shadow: 0 2px 8px rgba(78, 205, 196, 0.3);
    }

    .mobile-action-btn:not(.logout):hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
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

<script>
    /* ── Desktop: toggle the collapsible status panel ── */
    function toggleStatusPanel(e) {
        e.stopPropagation(); // prevent Bootstrap from closing the dropdown

        const panel = document.getElementById('statusPanel');
        const arrow = document.getElementById('statusArrow');

        panel.classList.toggle('open');
        arrow.classList.toggle('open');
    }

    /* ── Mobile: toggle the collapsible status panel ── */
    function toggleMobileStatusPanel() {
        const panel = document.getElementById('mobileStatusPanel');
        const arrow = document.getElementById('mobileStatusArrow');

        const isOpen = panel.classList.toggle('open');
        arrow.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    /* ── Auto-refresh notification badge ── */
    (function() {
        'use strict';

        const REFRESH_INTERVAL = 30000;
        const NOTIFICATION_COUNT_URL = '{{ route('employer.notifications.unread-count') }}';

        function updateBadge(count) {
            const badge = document.querySelector('.notification-badge');
            const bell = document.querySelector('.notification-bell');
            if (!bell) return;
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    const b = document.createElement('span');
                    b.className = 'notification-badge';
                    b.textContent = count;
                    bell.appendChild(b);
                }
            } else {
                if (badge) badge.remove();
            }
        }

        function fetchCount() {
            fetch(NOTIFICATION_COUNT_URL)
                .then(r => {
                    if (!r.ok) throw new Error('err');
                    return r.json();
                })
                .then(d => {
                    if (typeof d.count === 'number') updateBadge(d.count);
                })
                .catch(err => console.error('Notification fetch:', err));
        }

        function init() {
            setInterval(fetchCount, REFRESH_INTERVAL);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
