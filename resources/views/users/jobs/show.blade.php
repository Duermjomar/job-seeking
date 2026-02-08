@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="container job-details-container">

            {{-- BACK BUTTON --}}
            <div class="mb-4">
                <a href="{{ route('users.jobs.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Jobs
                </a>
            </div>


            <div class="row justify-content-center">
                <div class="col-lg-9">

                    {{-- FLASH MESSAGES --}}
                    @if (session('success'))
                        <div class="alert-custom alert-success-custom mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert-custom alert-warning-custom mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="job-details-card">
                        {{-- JOB HEADER --}}
                        <div class="job-header">
                            <div class="job-header-content">
                                <h1 class="job-title-main">{{ $job->job_title }}</h1>

                                <div class="job-meta-header">
                                    <div class="job-meta-item-header">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $job->location }}</span>
                                    </div>

                                    <div class="job-meta-item-header">
                                        <i class="bi bi-clock-fill"></i>
                                        <span class="job-type-badge-header">{{ ucfirst($job->job_type) }}</span>
                                    </div>

                                    @if ($job->salary)
                                        <div class="job-meta-item-header">
                                            <i class="bi bi-cash-coin"></i>
                                            <span class="job-salary-badge">₱{{ number_format($job->salary) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- JOB BODY --}}
                        <div class="job-body">

                            {{-- Job Description --}}
                            <div class="section-title">
                                <i class="bi bi-file-text-fill"></i>
                                Job Description
                            </div>
                            <div class="section-content">
                                {{ $job->job_description }}
                            </div>

                            <hr class="divider">

                            {{-- Requirements --}}
                            <div class="section-title">
                                <i class="bi bi-list-check"></i>
                                Requirements
                            </div>
                            <div class="section-content">
                                {{ $job->requirements }}
                            </div>

                            {{-- Download Template --}}

                            @if ($job->templates && $job->templates->count() > 0)
                                <div class="template-download-section">
                                    <h6 class="mb-3" style="font-weight: 600; color: var(--secondary-color);">
                                        <i class="bi bi-download me-2"></i>Application Templates Available
                                    </h6>

                                    <div class="template-list">
                                        @foreach ($job->templates as $template)
                                            <a href="{{ asset('storage/' . $template->file_path) }}"
                                                class="btn-download-template mb-2" download>
                                                <i class="bi bi-file-earmark-arrow-down"></i>
                                                {{ $template->file_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <hr class="divider">

                            {{-- APPLICATION SECTION --}}
                            @auth
                                @php
                                    $user = auth()->user();
                                    $jobSeeker = $user->jobSeeker;
                                    $alreadyApplied = $jobSeeker
                                        ? $job->applications()->where('job_seeker_id', $jobSeeker->id)->exists()
                                        : false;
                                @endphp

                                @if (!$jobSeeker)
                                    <div class="alert-custom alert-danger-custom">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                                        You need to complete your Job Seeker profile to apply.
                                    </div>
                                @elseif(!$jobSeeker->resume)
                                    <div class="alert-custom alert-warning-custom">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Please upload your resume in your profile before applying to jobs.
                                        <a href="{{ route('users.profile.edit') }}" class="btn btn-sm btn-primary ms-2">
                                            Go to Profile
                                        </a>
                                    </div>
                                @elseif($alreadyApplied)
                                    <button class="btn-applied-disabled" disabled>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Already Applied to this Job
                                    </button>
                                @else
                                    <div class="application-section">
                                        <div class="application-title">
                                            <i class="bi bi-send-fill"></i>
                                            Submit Your Application
                                        </div>

                                        {{-- Resume Info --}}
                                        <div class="resume-info-box mb-4">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="bi bi-file-earmark-check-fill text-success me-2"></i>
                                                        Resume Ready
                                                    </h6>
                                                    <p class="mb-0 text-muted small">
                                                        Your resume from profile will be automatically attached
                                                    </p>
                                                </div>
                                                <a href="{{ asset('storage/' . $jobSeeker->resume) }}" target="_blank"
                                                    class="btn btn-sm btn-view">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                            </div>
                                        </div>

                                        <form action="{{ route('users.jobs.apply', $job->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            {{-- Application Letter Upload (Optional) --}}
                                            <div class="mb-4">
                                                <label class="form-label-custom">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                    Upload Application Letter
                                                </label>
                                                <input type="file" name="application_letter" class="form-control-custom"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <small class="form-hint">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Accepted formats: PDF, Word, JPG, PNG (Max 2MB)
                                                </small>
                                            </div>

                                            <button type="submit" class="btn-apply-submit">
                                                <i class="bi bi-rocket-takeoff me-2"></i>
                                                Submit Application
                                            </button>
                                        </form>
                                    </div>
                                @endif
                     
                            @endauth
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

            /* Navbar */
            .navbar-custom {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .navbar-brand-custom {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                text-decoration: none;
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

            .navbar-brand-custom:hover .brand-icon {
                transform: rotate(-5deg) scale(1.05);
            }

            .brand-text {
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .btn-nav {
                padding: 0.6rem 1.5rem;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .btn-nav-outline {
                background: transparent;
                color: var(--primary-color);
                border: 2px solid var(--primary-color);
            }

            .btn-nav-outline:hover {
                background: var(--primary-color);
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .btn-nav-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .btn-nav-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Container */
            .job-details-container {
                padding: 3rem 0;
            }

            /* Back Button */
            .btn-back {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                text-decoration: none;
            }

            .btn-back:hover {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
                transform: translateX(-4px);
            }

            .btn-back i {
                transition: transform 0.3s ease;
            }

            .btn-back:hover i {
                transform: translateX(-3px);
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

            .alert-warning-custom {
                background: linear-gradient(135deg, #FFF4E6 0%, #FFE8CC 100%);
                color: #D97706;
            }

            .alert-danger-custom {
                background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
                color: #C92A2A;
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

            /* Job Details Card */
            .job-details-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .job-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                padding: 2.5rem;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .job-header::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 300px;
                height: 300px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: translate(30%, -30%);
            }

            .job-header-content {
                position: relative;
                z-index: 1;
            }

            .job-title-main {
                font-size: 2.25rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                line-height: 1.2;
            }

            .job-meta-header {
                display: flex;
                flex-wrap: wrap;
                gap: 1.25rem;
            }

            .job-meta-item-header {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1.05rem;
                font-weight: 500;
            }

            .job-meta-item-header i {
                font-size: 1.25rem;
            }

            .job-type-badge-header {
                background: rgba(255, 255, 255, 0.25);
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-weight: 600;
                backdrop-filter: blur(10px);
            }

            .job-salary-badge {
                background: rgba(255, 230, 109, 0.3);
                padding: 0.4rem 0.85rem;
                border-radius: 8px;
                font-weight: 700;
                backdrop-filter: blur(10px);
            }

            /* Job Body */
            .job-body {
                padding: 2.5rem;
            }

            .section-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .section-title i {
                color: var(--primary-color);
                font-size: 1.75rem;
            }

            .section-content {
                color: var(--text-dark);
                font-size: 1.05rem;
                line-height: 1.8;
                margin-bottom: 2rem;
            }

            .divider {
                height: 2px;
                background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), transparent);
                border: none;
                margin: 2rem 0;
            }

            /* Template Download */
            .template-download-section {
                background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
                padding: 1.5rem;
                border-radius: 12px;
                border: 2px solid rgba(78, 205, 196, 0.3);
                margin-bottom: 2rem;
            }

            .btn-download-template {
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                color: white;
                padding: 0.85rem 1.75rem;
                border-radius: 10px;
                font-weight: 700;
                border: none;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
            }

            .btn-download-template:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(78, 205, 196, 0.4);
                color: white;
            }

            /* Application Form */
            .application-section {
                background: var(--background-light);
                padding: 2rem;
                border-radius: 12px;
                border: 2px solid var(--border-color);
            }

            .application-title {
                font-size: 1.35rem;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .form-label-custom {
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 0.5rem;
                font-size: 0.95rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .form-label-custom i {
                color: var(--primary-color);
            }

            .form-control-custom {
                padding: 0.75rem 1rem;
                border: 2px solid var(--border-color);
                border-radius: 10px;
                background-color: white;
                color: var(--text-dark);
                font-size: 0.95rem;
                transition: all 0.3s ease;
                font-family: 'Outfit', sans-serif;
            }

            .form-control-custom:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .file-upload-wrapper {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .file-upload-label {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                border: 2px dashed var(--border-color);
                border-radius: 12px;
                background: white;
                cursor: pointer;
                transition: all 0.3s ease;
                color: var(--text-muted);
                font-weight: 500;
            }

            .file-upload-label:hover {
                border-color: var(--primary-color);
                background: linear-gradient(135deg, #FFF5F2 0%, #FFE8E0 100%);
                color: var(--primary-color);
            }

            .file-upload-label i {
                font-size: 1.5rem;
                margin-right: 0.5rem;
            }

            .form-hint {
                display: block;
                color: var(--text-muted);
                font-size: 0.85rem;
                margin-top: 0.5rem;
            }

            /* Buttons */
            .btn-apply-submit {
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

            .btn-apply-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            }

            .btn-applied-disabled {
                background: linear-gradient(135deg, #95E1D3, #7DD8C8);
                color: #0F6848;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.1rem;
                border: none;
                width: 100%;
                cursor: not-allowed;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .btn-login-apply {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.1rem;
                border: none;
                transition: all 0.3s ease;
                width: 100%;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
            }

            .btn-login-apply:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .job-title-main {
                    font-size: 1.75rem;
                }

                .job-header {
                    padding: 2rem;
                }

                .job-body {
                    padding: 1.5rem;
                }

                .job-meta-header {
                    flex-direction: column;
                    gap: 0.75rem;
                }

                .brand-text {
                    font-size: 1.25rem;
                }
            }

            /* Animation */
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

            .job-details-card {
                animation: fadeIn 0.5s ease-out;
            }

            .btn-back {
                animation: fadeIn 0.4s ease-out;
            }


            /* Resume Info Box */
            .resume-info-box {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
                border: 2px solid rgba(15, 104, 72, 0.2);
                border-radius: 12px;
                padding: 1rem 1.25rem;
            }

            .resume-info-box h6 {
                color: #0F6848;
                font-weight: 700;
                font-size: 1rem;
            }

            .btn-view {
                border: 2px solid var(--primary-color);
                color: var(--primary-color);
                padding: 0.4rem 1rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-view:hover {
                background: var(--primary-color);
                color: white;
            }
        </style>
    @endsection
@endcan
