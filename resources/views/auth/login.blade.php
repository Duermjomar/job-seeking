<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | JobFinder</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts - Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: var(--background-light);
        }

        /* Full Page Layout */
        .login-page {
            display: flex;
            min-height: 100vh;
        }

        /* Left Side - Graphic Panel */
        .login-graphic {
            width: 45%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-graphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .graphic-circle-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -80px;
            right: -100px;
        }

        .graphic-circle-2 {
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -60px;
            left: -80px;
        }

        .graphic-circle-3 {
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: 50%;
            left: 15%;
            transform: translateY(-50%);
        }

        .graphic-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .graphic-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            backdrop-filter: blur(10px);
        }

        .graphic-title {
            font-size: 2.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .graphic-subtitle {
            font-size: 1.15rem;
            opacity: 0.9;
            font-weight: 500;
            max-width: 380px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .graphic-features {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 320px;
            margin-left: auto;
            margin-right: auto;
        }

        .graphic-feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.12);
            padding: 1rem 1.25rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .graphic-feature-item i {
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .graphic-feature-item span {
            font-weight: 500;
            font-size: 0.95rem;
            text-align: left;
        }

        /* Right Side - Login Form */
        .login-form-panel {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 3rem;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Logo */
        .login-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
            text-decoration: none;
        }

        .login-logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .login-logo-text {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Title */
        .login-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        /* Session Status Alert */
        .alert-session {
            background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
            color: #0F6848;
            padding: 0.85rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background-color: var(--background-light);
            color: var(--text-dark);
            font-size: 0.95rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }

        /* Input Error */
        .input-error {
            color: #C92A2A;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .form-control-custom.is-invalid {
            border-color: #C92A2A;
            background-color: #FFF5F5;
        }

        /* Remember Row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
        }

        .remember-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            appearance: none;
            -webkit-appearance: none;
            background-color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .remember-checkbox:checked {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-color: var(--primary-color);
        }

        .remember-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2.5px 2.5px 0;
            transform: rotate(45deg);
        }

        .remember-text {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .forgot-link {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-login-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.95rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-prompt {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--border-color);
        }

        .register-prompt p {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .register-link {
            color: var(--primary-color);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .login-graphic {
                display: none;
            }

            .login-form-panel {
                width: 100%;
                min-height: 100vh;
                padding: 3rem 1.5rem;
                background: var(--background-light);
            }

            .login-form-wrapper {
                background: white;
                padding: 2.5rem;
                border-radius: 16px;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            }
        }

        @media (max-width: 480px) {
            .login-form-panel {
                padding: 2rem 1rem;
            }

            .login-form-wrapper {
                padding: 2rem 1.5rem;
            }

            .login-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-page">

        {{-- Left Graphic Panel --}}
        <div class="login-graphic">
            <div class="graphic-circle-1"></div>
            <div class="graphic-circle-2"></div>
            <div class="graphic-circle-3"></div>

            <div class="graphic-content">
                <div class="graphic-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <h2 class="graphic-title">Welcome Back</h2>
                <p class="graphic-subtitle">Sign in to continue your job search journey with JobFinder</p>

                <div class="graphic-features">
                    <div class="graphic-feature-item">
                        <i class="bi bi-search"></i>
                        <span>Search thousands of job openings</span>
                    </div>
                    <div class="graphic-feature-item">
                        <i class="bi bi-send-fill"></i>
                        <span>Apply with one click</span>
                    </div>
                    <div class="graphic-feature-item">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Track your applications live</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="login-form-panel">
            <div class="login-form-wrapper">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="login-logo">
                    <div class="login-logo-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <span class="login-logo-text">JobFinder</span>
                </a>

                {{-- Title --}}
                <h1 class="login-title">Welcome back</h1>
                <p class="login-subtitle">Enter your credentials to sign in</p>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="alert-session">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label-custom" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-fill input-icon"></i>
                            <input type="email" id="email" name="email"
                                class="form-control-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="you@email.com" value="{{ old('email') }}" required autofocus
                                autocomplete="username">
                        </div>
                        @if ($errors->has('email'))
                            <div class="input-error">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="form-label-custom" for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" id="password" name="password"
                                class="form-control-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="••••••••" required autocomplete="current-password">
                        </div>
                        @if ($errors->has('password'))
                            <div class="input-error">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="remember-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" class="remember-checkbox" id="remember_me">
                            <span class="remember-text">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login-submit">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sign In
                    </button>
                </form>

                {{-- Register Prompt --}}
                <div class="register-prompt">
                    <p>Don't have an account?
                        <a href="{{ route('register', session('url.intended') ? ['intended' => session('url.intended')] : []) }}"
                            class="register-link">Sign up free</a>
                    </p>
                </div>

            </div>
        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
