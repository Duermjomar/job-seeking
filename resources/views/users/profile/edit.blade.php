@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="profile-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- Page Header --}}
                <div class="page-header mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="profile-avatar-large">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-1">My Profile</h3>
                                <p class="profile-subtitle mb-0">{{ auth()->user()->email }}</p>
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

                {{-- Profile Form --}}
                <form action="{{ route('users.jobseeker.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- LEFT COLUMN: Personal & Contact Info --}}
                        <div class="col-lg-6">

                            {{-- Personal Information Card --}}
                            <div class="profile-card mb-4">
                                <div class="card-header-custom">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-fill me-2"></i>Personal Information
                                    </h5>
                                </div>
                                <div class="card-body-custom">
                                    {{-- Full Name --}}
                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">
                                            <i class="bi bi-person-badge me-2"></i>Full Name
                                        </label>
                                        <input type="text" name="name" class="form-control-profile"
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>

                                    {{-- Email (Read-only) --}}
                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">
                                            <i class="bi bi-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" class="form-control-profile" value="{{ auth()->user()->email }}"
                                            readonly style="background: #F7F9FC; cursor: not-allowed;">
                                        <small class="form-hint">Email cannot be changed</small>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">
                                            <i class="bi bi-telephone me-2"></i>Phone Number
                                        </label>
                                        <input type="text" name="phone" class="form-control-profile"
                                            placeholder="e.g. +1 234 567 8900"
                                            value="{{ old('phone', $jobSeeker->phone ?? '') }}" required>
                                    </div>

                                    {{-- Address --}}
                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">
                                            <i class="bi bi-geo-alt me-2"></i>Address
                                        </label>
                                        <textarea name="address" class="form-control-profile" rows="2" placeholder="Enter your full address" required>{{ old('address', $jobSeeker->address ?? '') }}</textarea>
                                    </div>

                                    {{-- Birthdate --}}
                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">
                                            <i class="bi bi-calendar-heart me-2"></i>Date of Birth
                                        </label>
                                        <input type="date" name="birthdate" class="form-control-profile"
                                            value="{{ old('birthdate', $jobSeeker->birthdate ?? '') }}" required>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="form-group-profile">
                                        <label class="form-label-profile">
                                            <i class="bi bi-gender-ambiguous me-2"></i>Gender
                                        </label>
                                        <select name="gender" class="form-control-profile" required>
                                            <option value="">-- Select Gender --</option>
                                            <option value="male"
                                                {{ old('gender', $jobSeeker->gender ?? '') == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $jobSeeker->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            <option value="other"
                                                {{ old('gender', $jobSeeker->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- RIGHT COLUMN: Profile Summary & Resume --}}
                        <div class="col-lg-6">

                            {{-- Profile Summary Card --}}
                            <div class="profile-card mb-4">
                                <div class="card-header-custom">
                                    <h5 class="mb-0">
                                        <i class="bi bi-file-text me-2"></i>Profile Summary
                                    </h5>
                                </div>
                                <div class="card-body-custom">
                                    <div class="form-group-profile">
                                        <label class="form-label-profile">
                                            <i class="bi bi-quote me-2"></i>About You
                                        </label>
                                        <textarea name="profile_summary" class="form-control-profile" rows="12"
                                            placeholder="Write a brief introduction about yourself, your career goals, and what you're looking for..." required>{{ old('profile_summary', $jobSeeker->profile_summary ?? '') }}</textarea>
                                        <small class="form-hint">This will be visible to employers</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Resume Upload Card --}}
                            <div class="profile-card">
                                <div class="card-header-custom">
                                    <h5 class="mb-0">
                                        <i class="bi bi-file-earmark-person me-2"></i>Resume
                                    </h5>
                                </div>
                                <div class="card-body-custom">
                                    @if ($jobSeeker && $jobSeeker->resume)
                                        <div class="current-file-display mb-3" id="currentResumeDisplay">
                                            <div class="file-preview">
                                                <div class="file-icon-large">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                                </div>
                                                <div class="file-info-large">
                                                    {{-- NEW --}}
                                                    <p class="file-name-large">
                                                        {{ $jobSeeker->resume_original ?? basename($jobSeeker->resume) }}
                                                    </p>
                                                    <small class="file-meta">
                                                        Uploaded
                                                        {{ \Carbon\Carbon::parse($jobSeeker->updated_at)->format('M d, Y') }}
                                                    </small>
                                                </div>
                                                <div class="file-actions-large">
                                                    <a href="{{ route('users.resume.download') }}"
                                                        class="btn-file-action btn-download" title="Download resume">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <button type="button" class="btn-file-action btn-delete-resume"
                                                        onclick="deleteResume()" title="Delete resume">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="file-upload-zone">
                                        <input type="file" name="resume" id="resumeInput" class="file-input-hidden"
                                            accept=".pdf,.doc,.docx">
                                        <label for="resumeInput" class="file-upload-label">
                                            <i class="bi bi-cloud-upload fs-2 mb-2"></i>
                                            <span class="upload-text">
                                                {{ $jobSeeker && $jobSeeker->resume ? 'Upload New Resume' : 'Upload Resume' }}
                                            </span>
                                            <small class="upload-hint">PDF, DOC, DOCX (Max 5MB)</small>
                                        </label>
                                    </div>
                                    <div id="resumeFileName" class="selected-file-name"></div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Submit Buttons --}}
                    <div class="row mt-4">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <a href="{{ route('dashboard') }}" class="btn btn-cancel-profile">
                                <i class="bi bi-x-circle me-2"></i>Cancel Changes
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-save-profile">
                                <i class="bi bi-check-circle me-2"></i>Save Profile
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        {{-- Add CSRF token meta tag for AJAX --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

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

            .profile-wrapper {
                min-height: 100vh;
                background: var(--background-light);
            }

            /* Page Header */
            .page-header {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                padding: 2rem;
                border-radius: 16px;
                box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
                position: relative;
                overflow: hidden;
            }

            .page-header::before {
                content: '';
                position: absolute;
                top: -50px;
                right: -50px;
                width: 200px;
                height: 200px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
            }

            .profile-avatar-large {
                width: 80px;
                height: 80px;
                background: rgba(255, 255, 255, 0.25);
                border: 4px solid rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 2rem;
                font-weight: 800;
                flex-shrink: 0;
                backdrop-filter: blur(10px);
            }

            .page-header h3 {
                color: white;
                font-weight: 800;
                font-size: 1.75rem;
            }

            .profile-subtitle {
                color: rgba(255, 255, 255, 0.95);
                font-size: 1rem;
                font-weight: 500;
            }

            .btn-back {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.4);
                border-radius: 10px;
                padding: 0.6rem 1.25rem;
                font-weight: 700;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
            }

            .btn-back:hover {
                background: white;
                color: var(--primary-color);
                border-color: white;
            }

            /* Alerts */
            .alert-custom {
                border-radius: 12px;
                padding: 1rem 1.25rem;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                animation: slideDown 0.3s ease;
            }

            .alert-success {
                background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
                color: #0F6848;
            }

            .alert-danger {
                background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
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

            /* Profile Cards */
            .profile-card {
                background: white;
                border-radius: 16px;
                border: 2px solid var(--border-color);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .profile-card:hover {
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                border-color: var(--primary-color);
            }

            .card-header-custom {
                background: linear-gradient(135deg, #FAFBFC, #FFFFFF);
                border-bottom: 2px solid var(--border-color);
                padding: 1.25rem 1.5rem;
            }

            .card-header-custom h5 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.05rem;
                display: flex;
                align-items: center;
            }

            .card-header-custom i {
                color: var(--primary-color);
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            /* Form Elements */
            .form-group-profile {
                position: relative;
            }

            .form-label-profile {
                display: flex;
                align-items: center;
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .form-label-profile i {
                color: var(--primary-color);
                font-size: 0.95rem;
            }

            .form-control-profile {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 2px solid var(--border-color);
                border-radius: 10px;
                background: var(--background-light);
                color: var(--text-dark);
                font-size: 0.95rem;
                transition: all 0.3s ease;
                font-family: 'Outfit', sans-serif;
            }

            .form-control-profile:focus {
                outline: none;
                border-color: var(--primary-color);
                background: white;
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .form-control-profile::placeholder {
                color: var(--text-muted);
                opacity: 0.6;
            }

            textarea.form-control-profile {
                resize: vertical;
            }

            .form-hint {
                display: block;
                color: var(--text-muted);
                font-size: 0.8rem;
                margin-top: 0.4rem;
            }

            /* Current File Display */
            .current-file-display {
                background: linear-gradient(135deg, #F7F9FC, #FFF);
                border: 2px solid var(--border-color);
                border-radius: 12px;
                padding: 1rem;
            }

            .file-preview {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .file-icon-large {
                width: 56px;
                height: 56px;
                border-radius: 12px;
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .file-info-large {
                flex: 1;
            }

            .file-name-large {
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 0.2rem;
            }

            .file-meta {
                color: var(--text-muted);
                font-size: 0.8rem;
            }

            .file-actions-large {
                display: flex;
                gap: 0.5rem;
            }

            .btn-file-action {
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
                text-decoration: none;
            }

            .btn-download {
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
                color: var(--secondary-color);
            }

            .btn-download:hover {
                background: var(--secondary-color);
                color: white;
                transform: translateY(-2px);
            }

            .btn-delete-resume {
                background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
                color: #FF6B6B;
            }

            .btn-delete-resume:hover {
                background: #FF6B6B;
                color: white;
                transform: translateY(-2px);
            }

            /* File Upload Zone */
            .file-upload-zone {
                position: relative;
            }

            .file-input-hidden {
                position: absolute;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
                z-index: 2;
            }

            .file-upload-label {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                border: 2px dashed var(--border-color);
                border-radius: 12px;
                background: var(--background-light);
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: center;
            }

            .file-upload-label:hover {
                border-color: var(--primary-color);
                background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
            }

            .file-upload-label i {
                color: var(--primary-color);
            }

            .upload-text {
                color: var(--text-dark);
                font-weight: 600;
                font-size: 0.95rem;
            }

            .upload-hint {
                color: var(--text-muted);
                font-size: 0.8rem;
                margin-top: 0.35rem;
            }

            .selected-file-name {
                margin-top: 0.75rem;
                padding: 0.75rem 1rem;
                background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
                border: 2px solid rgba(255, 107, 53, 0.3);
                border-radius: 10px;
                color: var(--primary-color);
                font-weight: 600;
                font-size: 0.9rem;
                display: none;
            }

            .selected-file-name.show {
                display: block;
            }

            /* Submit Buttons */
            .btn-save-profile {
                width: 100%;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.05rem;
                transition: all 0.3s ease;
                box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-save-profile:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            }

            .btn-cancel-profile {
                width: 100%;
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                padding: 1rem 2rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.05rem;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .btn-cancel-profile:hover {
                background: var(--text-muted);
                color: white;
                border-color: var(--text-muted);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .page-header {
                    padding: 1.5rem;
                }

                .profile-avatar-large {
                    width: 64px;
                    height: 64px;
                    font-size: 1.5rem;
                }

                .page-header h3 {
                    font-size: 1.5rem;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resumeInput = document.getElementById('resumeInput');
                const resumeFileName = document.getElementById('resumeFileName');

                if (resumeInput) {
                    resumeInput.addEventListener('change', function(e) {
                        if (e.target.files.length > 0) {
                            const fileName = e.target.files[0].name;
                            resumeFileName.textContent = `Selected: ${fileName}`;
                            resumeFileName.classList.add('show');
                        } else {
                            resumeFileName.textContent = '';
                            resumeFileName.classList.remove('show');
                        }
                    });
                }
            });

            // Delete resume function
            function deleteResume() {
                if (!confirm('Are you sure you want to delete your resume? This action cannot be undone.')) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('users.resume.delete') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the current resume display
                            const currentResumeDisplay = document.getElementById('currentResumeDisplay');
                            if (currentResumeDisplay) {
                                currentResumeDisplay.style.transition = 'all 0.3s ease';
                                currentResumeDisplay.style.opacity = '0';
                                currentResumeDisplay.style.transform = 'translateY(-10px)';

                                setTimeout(() => {
                                    currentResumeDisplay.remove();
                                }, 300);
                            }

                            // Update upload label text
                            const uploadText = document.querySelector('.upload-text');
                            if (uploadText) {
                                uploadText.textContent = 'Upload Resume';
                            }

                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert-custom alert-success mb-4';
                            alertDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>' + data.message;

                            const pageHeader = document.querySelector('.page-header');
                            pageHeader.parentNode.insertBefore(alertDiv, pageHeader.nextSibling);

                            // Auto-remove alert after 3 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 3000);
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the resume. Please try again.');
                    });
            }
        </script>
    @endsection
@endcan
