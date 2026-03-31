@extends('layouts.guest')

@section('content')
    <div class="forgot-password-wrapper">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-5 col-md-7">
                    <div class="auth-card">
                        
                        {{-- Header --}}
                        <div class="auth-header text-center mb-4">
                            <div class="auth-icon mb-3">
                                <i class="bi bi-envelope-exclamation-fill"></i>
                            </div>
                            <h2 class="auth-title">Forgot Password?</h2>
                            <p class="auth-subtitle">
                                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                            </p>
                        </div>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success-custom mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Form --}}
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            {{-- Email Address --}}
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2"></i>Email Address
                                </label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus
                                    placeholder="Enter your email address">
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-send-fill me-2"></i>
                                    Email Password Reset Link
                                </button>
                            </div>

                            {{-- Back to Login --}}
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="back-link">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Back to Login
                                </a>
                            </div>
                        </form>

                    </div>

                    {{-- Footer --}}
                    <div class="auth-footer text-center mt-4">
                        <p class="footer-text">
                            &copy; {{ date('Y') }} Job Portal. All rights reserved.
                        </p>
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
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --background-light: #F7F9FC;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .forgot-password-wrapper {
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Auth Card */
        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .auth-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        }

        .auth-title {
            color: var(--text-dark);
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Alert */
        .alert-success-custom {
            background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
            border: 2px solid #95E1D3;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            color: #0F6848;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .alert-success-custom i {
            font-size: 1.25rem;
        }

        /* Form */
        .form-label {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: var(--background-light);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        .form-control.is-invalid {
            border-color: #EF4444;
            background: #FEF2F2;
        }

        .form-control.is-invalid:focus {
            border-color: #EF4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            color: #EF4444;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        /* Submit Button */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, var(--primary-dark), #D64D1F);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        /* Back Link */
        .back-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        /* Footer */
        .auth-footer {
            animation: fadeIn 0.7s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .auth-subtitle {
                font-size: 0.875rem;
            }

            .auth-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .forgot-password-wrapper {
                padding: 1rem 0;
            }

            .auth-card {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
        }
    </style>
@endsection