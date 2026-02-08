@can('admin-access')
    @extends('layouts.Admin.app')

    @section('content')
    <div class="admin-users-wrapper">
        <div class="container-fluid px-4 py-5">

            {{-- PAGE HEADER --}}
            <div class="page-header-section mb-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h1 class="h3 fw-bold mb-1 header-title">User Management</h1>
                            <p class="header-subtitle mb-0">Manage all registered users on the platform</p>
                        </div>
                    </div>
                    <div class="header-stats">
                        <div class="stat-badge">
                            <i class="bi bi-person-check-fill me-2"></i>
                            <span class="stat-number">{{ count($allusers) }}</span>
                            <span class="stat-label">Total Users</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- USERS TABLE CARD --}}
            <div class="users-table-card">
                <div class="table-card-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h5 class="mb-0 table-title">
                            <i class="bi bi-table me-2"></i>All Users
                        </h5>
                        <div class="d-flex gap-2">
                            <div class="search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search users...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-card-body">
                    @if(count($allusers) > 0)
                        <div class="table-responsive">
                            <table class="table users-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="th-content">
                                                <i class="bi bi-person-badge me-2"></i>Name
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="bi bi-envelope me-2"></i>Email
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="bi bi-shield-check me-2"></i>Role
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="bi bi-calendar3 me-2"></i>Date Created
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="bi bi-gear me-2"></i>Actions
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allusers as $user)
                                    <tr class="user-row">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="user-name">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="user-email">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $roles = $user->roles()->get()->pluck('name')->toArray();
                                                $roleColors = [
                                                    'admin' => 'role-admin',
                                                    'employer' => 'role-employer',
                                                    'user' => 'role-user',
                                                ];
                                            @endphp
                                            @foreach($roles as $role)
                                                <span class="role-badge {{ $roleColors[$role] ?? 'role-default' }}">
                                                    @if($role === 'admin')
                                                        <i class="bi bi-shield-fill-check me-1"></i>
                                                    @elseif($role === 'employer')
                                                        <i class="bi bi-briefcase-fill me-1"></i>
                                                    @else
                                                        <i class="bi bi-person-fill me-1"></i>
                                                    @endif
                                                    {{ ucfirst($role) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="date-text">
                                                <i class="bi bi-clock-fill me-1"></i>
                                                {{ date('M d, Y', strtotime($user->created_at)) }}
                                            </span>
                                            <small class="time-text d-block">
                                                {{ date('h:i A', strtotime($user->created_at)) }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.view', $user->id) }}" class="btn-action btn-view">
                                                <i class="bi bi-eye-fill me-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="pagination-wrapper">
                            {{ $allusers->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="empty-title">No Users Found</h5>
                            <p class="empty-text">There are no registered users in the system yet.</p>
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
            --accent-color: #FFE66D;
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --background-light: #F7F9FC;
            --white: #FFFFFF;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        .admin-users-wrapper {
            min-height: 100vh;
            background: var(--background-light);
        }

        /* Page Header */
        .page-header-section {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAFBFC 100%);
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

        .header-title {
            color: var(--text-dark);
            font-weight: 700;
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
        }

        .header-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            font-weight: 600;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.95;
        }

        /* Table Card */
        .users-table-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-card-header {
            background: linear-gradient(135deg, #FFFFFF 0%, #F7F9FC 100%);
            border-bottom: 2px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .table-title {
            color: var(--text-dark);
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .table-title i {
            color: var(--primary-color);
        }

        /* Search Box */
        .search-box {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            z-index: 2;
        }

        .search-input {
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            min-width: 250px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        /* Table */
        .table-card-body {
            padding: 0;
        }

        .users-table {
            margin: 0;
        }

        .users-table thead {
            background: linear-gradient(135deg, #FAFBFC 0%, #F7F9FC 100%);
            border-bottom: 2px solid var(--border-color);
        }

        .users-table thead th {
            padding: 1rem 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .th-content {
            display: flex;
            align-items: center;
        }

        .th-content i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        .users-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .users-table tbody tr:hover {
            background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
        }

        .users-table tbody td {
            padding: 1.25rem 1.25rem;
            vertical-align: middle;
            border: none;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .user-name {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .user-email {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Role Badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-right: 0.35rem;
        }

        .role-admin {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
            color: #6366F1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .role-employer {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
            border: 1px solid rgba(255, 107, 53, 0.3);
        }

        .role-user {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
            border: 1px solid rgba(78, 205, 196, 0.3);
        }

        .role-default {
            background: linear-gradient(135deg, rgba(113, 128, 150, 0.15), rgba(113, 128, 150, 0.25));
            color: var(--text-muted);
            border: 1px solid rgba(113, 128, 150, 0.3);
        }

        /* Date Text */
        .date-text {
            color: var(--text-dark);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .date-text i {
            color: var(--primary-color);
            font-size: 0.85rem;
        }

        .time-text {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 0.15rem;
            margin-left: 1.5rem;
        }

        /* Action Button */
        .btn-action {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
            color: white;
            box-shadow: 0 2px 8px rgba(78, 205, 196, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
            color: white;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 1.5rem;
            border-top: 2px solid var(--border-color);
            background: linear-gradient(135deg, #FAFBFC 0%, #FFFFFF 100%);
        }

        .pagination {
            margin: 0;
            gap: 0.5rem;
        }

        .page-link {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-dark);
            font-weight: 600;
            padding: 0.5rem 0.85rem;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--text-muted);
            opacity: 0.4;
            margin-bottom: 1.5rem;
        }

        .empty-title {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Animations */
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

        .user-row {
            animation: fadeIn 0.4s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-input {
                min-width: 200px;
            }

            .header-stats {
                width: 100%;
            }

            .stat-badge {
                flex: 1;
                justify-content: center;
            }

            .table-responsive {
                border-radius: 0;
            }

            .users-table thead {
                display: none;
            }

            .users-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 2px solid var(--border-color);
                border-radius: 12px;
                padding: 1rem;
            }

            .users-table tbody td {
                display: block;
                text-align: left;
                padding: 0.5rem 0;
                border: none;
            }

            .users-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--text-dark);
                display: block;
                margin-bottom: 0.25rem;
                font-size: 0.8rem;
                text-transform: uppercase;
            }
        }
    </style>

    <script>
        // Simple client-side search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('usersTable');
            
            if (searchInput && table) {
                searchInput.addEventListener('keyup', function() {
                    const filter = searchInput.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                        const email = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                        
                        if (name.includes(filter) || email.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
    @endsection
@endcan