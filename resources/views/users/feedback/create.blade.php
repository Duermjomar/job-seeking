@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        {{-- PAGE HEADER --}}
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <i class="bi bi-chat-heart-fill me-2"></i>Send Feedback
                    </h1>
                    <p class="page-subtitle">
                        Help us improve by sharing your experience
                    </p>
                </div>
            </div>
        </section>

        {{-- FEEDBACK CONTAINER --}}
        <div class="container feedback-container">
            
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- SUCCESS MESSAGE --}}
                    @if (session('status'))
                        <div class="alert-custom alert-success-custom mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- FEEDBACK FORM CARD --}}
                    <div class="feedback-card">
                        <div class="card-body p-4">
                            {{-- HEADER WITH BACK BUTTON --}}
                            <div class="form-header mb-4">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="header-icon">
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-1">We Value Your Feedback</h4>
                                        <p class="text-muted mb-0">Your input helps us provide better service</p>
                                    </div>
                                </div>
                                <a href="{{ route('dashboard') }}" class="btn-back">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                            </div>

                            <form action="{{ route('users.feedback.store') }}" method="POST" class="feedback-form">
                                @csrf

                                {{-- EMAIL --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email"
                                           name="email"
                                           class="form-control-custom"
                                           value="{{ Auth::user()->email }}"
                                           readonly
                                           required>
                                </div>

                                {{-- RATING --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-star-half me-2"></i>How would you rate your experience?
                                    </label>
                                    <div class="rating-options">
                                        <label class="rating-card">
                                            <input type="radio" name="rate" value="1" required>
                                            <div class="rating-content rating-poor">
                                                <div class="rating-icon">
                                                    <i class="bi bi-emoji-frown"></i>
                                                </div>
                                                <span class="rating-label">Poor</span>
                                                <div class="rating-stars">
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="rating-card">
                                            <input type="radio" name="rate" value="3" required>
                                            <div class="rating-content rating-good">
                                                <div class="rating-icon">
                                                    <i class="bi bi-emoji-smile"></i>
                                                </div>
                                                <span class="rating-label">Good</span>
                                                <div class="rating-stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="rating-card">
                                            <input type="radio" name="rate" value="5" required>
                                            <div class="rating-content rating-outstanding">
                                                <div class="rating-icon">
                                                    <i class="bi bi-emoji-heart-eyes"></i>
                                                </div>
                                                <span class="rating-label">Outstanding</span>
                                                <div class="rating-stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- COMMENTS --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-chat-dots me-2"></i>Your Comments
                                    </label>
                                    <textarea name="comm"
                                              class="form-control-custom"
                                              rows="6"
                                              placeholder="Share your thoughts, suggestions, or experiences with us..."
                                              required></textarea>
                                    <small class="form-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Please provide detailed feedback to help us improve
                                    </small>
                                </div>

                                {{-- SUBMIT BUTTON --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn-submit">
                                        <i class="bi bi-send-fill me-2"></i>
                                        Submit Feedback
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <style>
            :root {
                --primary-color: #FF6B35;
                --primary-dark: #E85A2A;
                --secondary-color: #4ECDC4;
                --accent-color: #FFE66D;
                --success-color: #95E1D3;
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
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
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
            .feedback-container {
                padding: 4rem 0 3rem;
            }

            /* Alerts */
            .alert-custom {
                border-radius: 12px;
                border: none;
                padding: 1rem 1.25rem;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                animation: slideDown 0.3s ease-out;
                display: flex;
                align-items: center;
            }

            .alert-success-custom {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
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

            /* Feedback Card */
            .feedback-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
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

            /* Form Header */
            .form-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 1.5rem;
                border-bottom: 2px solid var(--border-color);
                gap: 1rem;
            }

            .header-icon {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15) 0%, rgba(255, 107, 53, 0.25) 100%);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .form-header h4 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.5rem;
            }

            /* Back Button */
            .btn-back {
                display: inline-flex;
                align-items: center;
                padding: 0.65rem 1.25rem;
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                white-space: nowrap;
                flex-shrink: 0;
            }

            .btn-back:hover {
                border-color: var(--primary-color);
                color: var(--primary-color);
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 107, 53, 0.1));
                transform: translateX(-4px);
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
            }

            .btn-back i {
                transition: transform 0.3s ease;
            }

            .btn-back:hover i {
                transform: translateX(-3px);
            }

            /* Form Groups */
            .form-group-custom {
                position: relative;
            }

            .form-label-custom {
                display: flex;
                align-items: center;
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 0.75rem;
                font-size: 1rem;
            }

            .form-label-custom i {
                color: var(--primary-color);
            }

            .form-control-custom {
                width: 100%;
                padding: 0.85rem 1.25rem;
                border: 2px solid var(--border-color);
                border-radius: 10px;
                background-color: var(--background-light);
                color: var(--text-dark);
                font-size: 0.95rem;
                transition: all 0.3s ease;
                font-family: 'Outfit', sans-serif;
            }

            .form-control-custom:focus {
                outline: none;
                border-color: var(--primary-color);
                background-color: white;
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .form-control-custom:read-only {
                background-color: #E8EDF2;
                cursor: not-allowed;
            }

            textarea.form-control-custom {
                resize: vertical;
                min-height: 140px;
            }

            .form-hint {
                display: block;
                color: var(--text-muted);
                font-size: 0.85rem;
                margin-top: 0.5rem;
            }

            /* Rating Options */
            .rating-options {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }

            .rating-card {
                position: relative;
                cursor: pointer;
            }

            .rating-card input[type="radio"] {
                position: absolute;
                opacity: 0;
                width: 0;
                height: 0;
            }

            .rating-content {
                background: var(--background-light);
                border: 2px solid var(--border-color);
                border-radius: 12px;
                padding: 1.5rem 1rem;
                text-align: center;
                transition: all 0.3s ease;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .rating-card:hover .rating-content {
                transform: translateY(-4px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .rating-card input[type="radio"]:checked + .rating-content {
                border-width: 3px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
                transform: translateY(-4px);
            }

            .rating-icon {
                font-size: 2.5rem;
            }

            .rating-poor .rating-icon {
                color: #FF6B6B;
            }

            .rating-good .rating-icon {
                color: #FFE66D;
            }

            .rating-outstanding .rating-icon {
                color: #95E1D3;
            }

            .rating-card input[type="radio"]:checked + .rating-poor {
                border-color: #FF6B6B;
                background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
            }

            .rating-card input[type="radio"]:checked + .rating-good {
                border-color: #FFE66D;
                background: linear-gradient(135deg, #FFF9E6 0%, #FFF4CC 100%);
            }

            .rating-card input[type="radio"]:checked + .rating-outstanding {
                border-color: #95E1D3;
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
            }

            .rating-label {
                font-weight: 600;
                color: var(--text-dark);
                font-size: 1rem;
            }

            .rating-stars {
                display: flex;
                gap: 0.25rem;
                color: #FFD700;
                font-size: 0.9rem;
            }

            /* Submit Button */
            .btn-submit {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.1rem;
                border: none;
                transition: all 0.3s ease;
                width: 100%;
                box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
            }

            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .page-title {
                    font-size: 2rem;
                }

                .page-subtitle {
                    font-size: 1.05rem;
                }

                .rating-options {
                    grid-template-columns: 1fr;
                }

                .form-header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .btn-back {
                    width: 100%;
                    justify-content: center;
                    order: -1;
                    margin-bottom: 1rem;
                }

                .header-icon {
                    width: 56px;
                    height: 56px;
                    font-size: 1.5rem;
                }

                .form-header h4 {
                    font-size: 1.25rem;
                }
            }
        </style>
    @endsection
@endcan