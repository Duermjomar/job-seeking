@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
    <div class="account-settings-wrapper">
        <div class="container-fluid px-4 py-5">

            {{-- Page Header --}}
            <div class="page-header mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1">Account Settings</h3>
                            <p class="header-subtitle mb-0">Manage your account security and login information</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div class="alert-custom alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-custom alert-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">

                {{-- Update Email Card --}}
                <div class="col-lg-6">
                    <div class="settings-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-envelope-fill me-2"></i>Email Address
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="current-info mb-3">
                                <label class="info-label">Current Email</label>
                                <div class="info-display">
                                    <i class="bi bi-envelope-check-fill me-2"></i>
                                    {{ auth()->user()->email }}
                                </div>
                            </div>

                            <form action="{{ route('account.update.email') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group-settings mb-3">
                                    <label class="form-label-settings">
                                        <i class="bi bi-envelope-at me-2"></i>New Email Address
                                    </label>
                                    <input type="email"
                                           name="email"
                                           class="form-control-settings"
                                           placeholder="Enter new email address"
                                           required>
                                </div>

                                <div class="form-group-settings mb-4">
                                    <label class="form-label-settings">
                                        <i class="bi bi-key me-2"></i>Confirm Password
                                    </label>
                                    <input type="password"
                                           name="current_password"
                                           class="form-control-settings"
                                           placeholder="Enter your password to confirm"
                                           required>
                                    <small class="form-hint">We need your password to verify this change</small>
                                </div>

                                <button type="submit" class="btn btn-update">
                                    <i class="bi bi-check-circle me-2"></i>Update Email
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Update Password Card --}}
                <div class="col-lg-6">
                    <div class="settings-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-lock-fill me-2"></i>Password
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="security-notice mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <span>Choose a strong password with at least 8 characters</span>
                            </div>

                            <form action="{{ route('account.update.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group-settings mb-3">
                                    <label class="form-label-settings">
                                        <i class="bi bi-lock-fill me-2"></i>Current Password
                                    </label>
                                    <input type="password"
                                           name="current_password"
                                           class="form-control-settings"
                                           placeholder="Enter current password"
                                           required>
                                </div>

                                <div class="form-group-settings mb-3">
                                    <label class="form-label-settings">
                                        <i class="bi bi-key-fill me-2"></i>New Password
                                    </label>
                                    <input type="password"
                                           name="password"
                                           class="form-control-settings"
                                           placeholder="Enter new password (min. 8 characters)"
                                           required>
                                </div>

                                <div class="form-group-settings mb-4">
                                    <label class="form-label-settings">
                                        <i class="bi bi-check2-square me-2"></i>Confirm New Password
                                    </label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control-settings"
                                           placeholder="Re-enter new password"
                                           required>
                                </div>

                                <button type="submit" class="btn btn-update">
                                    <i class="bi bi-shield-check me-2"></i>Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Account Actions --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="danger-zone-card">
                        <div class="card-header-danger">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Danger Zone
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h6 class="danger-title">Delete Account</h6>
                                    <p class="danger-text mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                                </div>
                                <button type="button" 
                                        class="btn btn-danger-action"
                                        onclick="confirm('Are you sure you want to delete your account? This action cannot be undone.') && document.getElementById('deleteForm').submit()">
                                    <i class="bi bi-trash-fill me-2"></i>Delete Account
                                </button>
                            </div>
                            
                            <form id="deleteForm" action="{{ route('account.delete') }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
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
            --danger-color: #FF6B6B;
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --background-light: #F7F9FC;
        }

        * { font-family: 'Outfit', sans-serif; }
        .account-settings-wrapper { min-height: 100vh; background: var(--background-light); }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #FFFFFF 0%, #F7F9FC 100%);
            padding: 1.5rem; border-radius: 16px; border: 2px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .header-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, rgba(255,107,53,0.15), rgba(255,107,53,0.25));
            color: var(--primary-color); border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.75rem;
        }
        .page-header h3 { color: var(--text-dark); font-weight: 700; font-size: 1.5rem; margin: 0; }
        .header-subtitle { color: var(--text-muted); font-size: 1rem; font-weight: 500; }

        .btn-back {
            background: white; color: var(--text-dark); border: 2px solid var(--border-color);
            border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; transition: all 0.3s ease;
        }
        .btn-back:hover { background: var(--secondary-color); color: white; border-color: var(--secondary-color); }

        /* Alerts */
        .alert-custom {
            border-radius: 12px; padding: 1rem 1.25rem; font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); animation: slideDown 0.3s ease;
        }
        .alert-success { background: linear-gradient(135deg, #E8F8F5, #D5F4E6); color: #0F6848; }
        .alert-danger { background: linear-gradient(135deg, #FFE5E5, #FFD0D0); color: #C92A2A; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* Settings Cards */
        .settings-card, .danger-zone-card {
            background: white; border-radius: 16px; border: 2px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;
            transition: all 0.3s ease;
        }
        .settings-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); border-color: var(--primary-color); }

        .card-header-custom {
            background: linear-gradient(135deg, #FAFBFC, #FFFFFF);
            border-bottom: 2px solid var(--border-color); padding: 1.25rem 1.5rem;
        }
        .card-header-custom h5 {
            color: var(--text-dark); font-weight: 700; font-size: 1.05rem;
            display: flex; align-items: center;
        }
        .card-header-custom i { color: var(--primary-color); }

        .card-body-custom { padding: 1.5rem; }

        /* Current Info Display */
        .current-info { }
        .info-label {
            display: block; font-weight: 600; color: var(--text-muted);
            font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-display {
            background: linear-gradient(135deg, #F7F9FC, #FFF);
            border: 2px solid var(--border-color); border-radius: 10px;
            padding: 0.85rem 1rem; color: var(--text-dark); font-weight: 600;
            display: flex; align-items: center;
        }
        .info-display i { color: var(--secondary-color); }

        /* Security Notice */
        .security-notice {
            background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
            border: 2px solid rgba(255,107,53,0.3); border-radius: 10px;
            padding: 0.85rem 1rem; color: var(--primary-color); font-size: 0.9rem;
            font-weight: 600; display: flex; align-items: center;
        }

        /* Form Elements */
        .form-group-settings { position: relative; }
        .form-label-settings {
            display: flex; align-items: center; font-weight: 600; color: var(--text-dark);
            margin-bottom: 0.5rem; font-size: 0.9rem;
        }
        .form-label-settings i { color: var(--primary-color); font-size: 0.95rem; }

        .form-control-settings {
            width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--border-color);
            border-radius: 10px; background: var(--background-light); color: var(--text-dark);
            font-size: 0.95rem; transition: all 0.3s ease; font-family: 'Outfit', sans-serif;
        }
        .form-control-settings:focus {
            outline: none; border-color: var(--primary-color); background: white;
            box-shadow: 0 0 0 4px rgba(255,107,53,0.1);
        }
        .form-control-settings::placeholder { color: var(--text-muted); opacity: 0.6; }

        .form-hint { display: block; color: var(--text-muted); font-size: 0.8rem; margin-top: 0.4rem; }

        /* Update Button */
        .btn-update {
            width: 100%; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white; border: none; padding: 0.85rem 1.5rem; border-radius: 10px;
            font-weight: 700; font-size: 0.95rem; transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255,107,53,0.3);
            display: flex; align-items: center; justify-content: center;
        }
        .btn-update:hover {
            transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255,107,53,0.4);
        }

        /* Danger Zone */
        .danger-zone-card:hover { box-shadow: 0 4px 16px rgba(255,107,107,0.15); border-color: var(--danger-color); }
        .card-header-danger {
            background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
            border-bottom: 2px solid var(--danger-color); padding: 1.25rem 1.5rem;
        }
        .card-header-danger h5 {
            color: var(--danger-color); font-weight: 700; font-size: 1.05rem;
            display: flex; align-items: center;
        }

        .danger-title { color: var(--text-dark); font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem; }
        .danger-text { color: var(--text-muted); font-size: 0.9rem; }

        .btn-danger-action {
            background: linear-gradient(135deg, var(--danger-color), #E85A5A);
            color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 10px;
            font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255,107,107,0.3);
            display: flex; align-items: center; justify-content: center;
        }
        .btn-danger-action:hover {
            transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255,107,107,0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header { padding: 1.25rem; }
            .header-icon { width: 56px; height: 56px; font-size: 1.5rem; }
            .page-header h3 { font-size: 1.25rem; }
        }
    </style>
    @endsection
@endcan