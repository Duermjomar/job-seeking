@can('user-access')
    @extends('layouts.Users.app')

    @section('content')
        <div class="dashboard-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- FLASH MESSAGES --}}
                @if (session('success'))
                    <div class="alert-custom alert-success-custom">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert-custom alert-warning-custom">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div>
                @endif

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h1 class="h3 fw-bold mb-1 header-title">Welcome back, {{ auth()->user()->name }}</h1>
                        <p class="header-subtitle mb-0">Here's what's happening with your job search</p>
                    </div>
                    <a href="{{ route('users.applications') }}" class="btn btn-track">
                        <i class="bi bi-graph-up-arrow me-2"></i>Track Applications
                    </a>
                </div>

                {{-- STAT CARDS --}}
                <div class="row g-4 mb-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card stat-total">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-total">
                                        <i class="bi bi-clipboard-data-fill fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="stat-label mb-1">Total Applications</p>
                                        <h2 class="stat-number mb-0">{{ $totalApplications }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card stat-pending">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-pending">
                                        <i class="bi bi-clock-fill fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="stat-label mb-1">Pending</p>
                                        <h2 class="stat-number mb-0">{{ $pendingApplications }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card stat-accepted">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-accepted">
                                        <i class="bi bi-check-circle-fill fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="stat-label mb-1">Accepted</p>
                                        <h2 class="stat-number mb-0">{{ $acceptedApplications }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card stat-rejected">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrap icon-rejected">
                                        <i class="bi bi-x-circle-fill fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="stat-label mb-1">Rejected</p>
                                        <h2 class="stat-number mb-0">{{ $rejectedApplications }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TWO COLUMN ROW: Profile + Search --}}
                <div class="row g-4 mb-5">

                    {{-- PROFILE SUMMARY --}}
                    <div class="col-lg-5">
                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="profile-name mb-0">{{ auth()->user()->name }}</h6>
                                            <small class="profile-email">{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-edit-profile" data-bs-toggle="modal"
                                        data-bs-target="#editProfileModal">
                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                    </button>
                                </div>
                            </div>
                            <div class="profile-body">
                                {{-- Profile Info Grid --}}
                                <div class="profile-info-grid mb-3">
                                    @if ($jobSeeker->phone)
                                        <div class="profile-info-item">
                                            <i class="bi bi-telephone-fill"></i>
                                            <span>{{ $jobSeeker->phone }}</span>
                                        </div>
                                    @endif
                                    @if ($jobSeeker->address)
                                        <div class="profile-info-item">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>{{ $jobSeeker->address }}</span>
                                        </div>
                                    @endif
                                    @if ($jobSeeker->birthdate)
                                        <div class="profile-info-item">
                                            <i class="bi bi-calendar-fill"></i>
                                            <span>{{ \Carbon\Carbon::parse($jobSeeker->birthdate)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    @if ($jobSeeker->gender)
                                        <div class="profile-info-item">
                                            <i class="bi bi-gender-ambiguous"></i>
                                            <span>{{ ucfirst($jobSeeker->gender) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <p class="profile-summary-label"><i class="bi bi-file-text me-2"></i>Profile Summary</p>
                                <p class="profile-summary-text">
                                    {{ $jobSeeker->profile_summary ?? 'No profile summary added yet. Click Edit to add one!' }}
                                </p>

                                @if ($jobSeeker->resume)
                                    <a href="{{ asset('storage/' . $jobSeeker->resume) }}" target="_blank"
                                        class="btn-view-resume">
                                        <i class="bi bi-file-earmark-text-fill me-2"></i>View Resume
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SEARCH JOBS --}}
                    <div class="col-lg-7">
                        <div class="search-card">
                            <div class="search-header">
                                <h6 class="mb-0"><i class="bi bi-search me-2"></i>Search Jobs</h6>
                            </div>
                            <div class="search-body">
                                <form method="GET" action="{{ route('users.jobs.index') }}">
                                    <div class="search-input-group">
                                        <i class="bi bi-search search-input-icon"></i>
                                        <input type="text" name="search" class="form-control search-input"
                                            placeholder="Search by job title, skill, or keyword..."
                                            value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-search">
                                            <i class="bi bi-search me-2"></i>Search
                                        </button>
                                    </div>
                                </form>
                                <div class="search-tags mt-3">
                                    <span class="search-tag-label">Popular:</span>
                                    <a href="{{ route('users.jobs.index') }}?search=developer"
                                        class="search-tag">Developer</a>
                                    <a href="{{ route('users.jobs.index') }}?search=designer" class="search-tag">Designer</a>
                                    <a href="{{ route('users.jobs.index') }}?search=intern" class="search-tag">Internship</a>
                                    <a href="{{ route('users.jobs.index') }}?search=remote" class="search-tag">Remote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EDIT PROFILE MODAL --}}
                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form action="{{ route('users.jobseeker.profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content modal-custom">
                                <div class="modal-header modal-header-custom">
                                    <h5 class="modal-title" id="editProfileModalLabel">
                                        <i class="bi bi-person-fill-gear me-2"></i>Edit Profile
                                    </h5>
                                    <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body modal-body-custom">
                                    <div class="row g-3">
                                        {{-- Phone --}}
                                        <div class="col-md-6">
                                            <label class="modal-input-label">
                                                <i class="bi bi-telephone-fill"></i>
                                                Phone Number
                                            </label>
                                            <input type="text" name="phone" class="form-control modal-input"
                                                value="{{ $jobSeeker->phone }}" placeholder="e.g., 09171234567"
                                                maxlength="11" required
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                                        </div>

                                        {{-- Gender --}}
                                        <div class="col-md-6">
                                            <label class="modal-input-label">
                                                <i class="bi bi-gender-ambiguous"></i>
                                                Gender
                                            </label>
                                            <select name="gender" class="form-control modal-input" required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ $jobSeeker->gender == 'male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="female" {{ $jobSeeker->gender == 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="other" {{ $jobSeeker->gender == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                        </div>

                                        {{-- Birthdate --}}
                                        <div class="col-md-6">
                                            <label class="modal-input-label">
                                                <i class="bi bi-calendar-fill"></i>
                                                Birthdate
                                            </label>
                                            <input type="date" name="birthdate" class="form-control modal-input"
                                                value="{{ $jobSeeker->birthdate }}" max="{{ date('Y-m-d') }}" required>
                                        </div>

                                        {{-- Address --}}
                                        <div class="col-md-6">
                                            <label class="modal-input-label">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                Full address
                                            </label>
                                            <input type="text" name="address" class="form-control modal-input"
                                                value="{{ $jobSeeker->address }}" placeholder="City, Province" required>
                                        </div>

                                        {{-- Resume Upload --}}
                                        <div class="col-12">
                                            <label class="modal-input-label">
                                                <i class="bi bi-file-earmark-text-fill"></i>
                                                Resume (PDF, DOC, DOCX)
                                            </label>
                                            @if ($jobSeeker->resume)
                                                <div class="current-file-display">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                    <span>Current: </span>
                                                    <a href="{{ asset('storage/' . $jobSeeker->resume) }}" target="_blank">
                                                        View Resume
                                                    </a>
                                                </div>
                                            @endif
                                            <input type="file" name="resume" class="form-control modal-input"
                                                accept=".pdf,.doc,.docx">
                                            <small class="modal-hint">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Leave blank to keep current resume. Max size: 2MB
                                            </small>
                                        </div>

                                        {{-- Profile Summary --}}
                                        <div class="col-12">
                                            <label class="modal-input-label">
                                                <i class="bi bi-file-text-fill"></i>
                                                Profile Summary
                                            </label>
                                            <textarea name="profile_summary" class="form-control modal-textarea" rows="4" required
                                                placeholder="Write a brief summary about yourself, skills, and career goals...">{{ $jobSeeker->profile_summary }}</textarea>
                                            <small class="modal-hint">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Write a short summary about yourself, your skills, and your goals (min. 50
                                                characters).
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer modal-footer-custom">
                                    <button type="button" class="btn btn-modal-cancel"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-modal-save">
                                        <i class="bi bi-check-circle me-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Latest Jobs Section continues... --}}
                {{-- LATEST JOBS --}}
                <div class="jobs-list-card">
                    <div class="jobs-list-header">
                        <div class="d-flex align-items-center">
                            <div class="jobs-header-icon">
                                <i class="bi bi-fire"></i>
                            </div>
                            <h5 class="mb-0 ms-3">Latest Job Openings</h5>
                        </div>
                        <a href="{{ route('users.jobs.index') }}" class="btn btn-see-all">
                            See All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="jobs-list-body">
                        <div class="row g-4">
                            @forelse($latestJobs as $job)
                                @php
                                    $alreadyApplied = \App\Models\Application::where('job_id', $job->id)
                                        ->where('job_seeker_id', $jobSeeker->id)
                                        ->exists();
                                @endphp

                                <div class="col-md-6">
                                    <div class="job-card">
                                        <div class="job-card-top">
                                            <h6 class="job-card-title">{{ $job->job_title }}</h6>
                                            @if ($alreadyApplied)
                                                <span class="applied-badge">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Applied
                                                </span>
                                            @endif
                                        </div>

                                        <div class="job-card-meta">
                                            <span class="job-meta-item">
                                                <i class="bi bi-geo-alt-fill"></i>{{ $job->location }}
                                            </span>
                                            <span class="job-type-badge">
                                                <i class="bi bi-clock"></i>{{ ucfirst($job->job_type) }}
                                            </span>
                                        </div>

                                        <p class="job-card-desc">
                                            {{ \Illuminate\Support\Str::limit($job->job_description, 100) }}</p>

                                        <a href="{{ route('users.jobs.show', $job->id) }}" class="btn btn-job-details">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                                        <p class="empty-title">No jobs available</p>
                                        <p class="empty-text">Check back soon for new opportunities</p>
                                    </div>
                                </div>
                            @endforelse
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
                --text-dark: #2D3748;
                --text-muted: #718096;
                --border-color: #E2E8F0;
                --background-light: #F7F9FC;
            }

            .dashboard-wrapper {
                min-height: 100vh;
                background: var(--background-light);
            }

            /* Header */
            .header-title {
                color: var(--text-dark);
            }

            .header-subtitle {
                color: var(--text-muted);
                font-weight: 500;
            }

            .btn-track {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 0.7rem 1.5rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 0.95rem;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .btn-track:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 18px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Alerts */
            .alert-custom {
                border-radius: 12px;
                padding: 1rem 1.25rem;
                font-weight: 500;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .alert-success-custom {
                background: linear-gradient(135deg, #E8F8F5, #D5F4E6);
                color: #0F6848;
            }

            .alert-warning-custom {
                background: linear-gradient(135deg, #FFF4E6, #FFE8CC);
                color: #D97706;
            }

            /* Stat Cards */
            .stat-card {
                border: none;
                border-radius: 16px;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 120px;
                height: 120px;
                border-radius: 50%;
                opacity: 0.1;
                transform: translate(40%, -40%);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            }

            .stat-card:hover::before {
                transform: translate(30%, -30%) scale(1.2);
            }

            .stat-total {
                background: linear-gradient(135deg, #F0F4FF, #fff);
                border-left: 4px solid #6366F1;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-total::before {
                background: #6366F1;
            }

            .stat-pending {
                background: linear-gradient(135deg, #FFF4E6, #fff);
                border-left: 4px solid var(--primary-color);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-pending::before {
                background: var(--primary-color);
            }

            .stat-accepted {
                background: linear-gradient(135deg, #F0FFFE, #fff);
                border-left: 4px solid var(--secondary-color);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-accepted::before {
                background: var(--secondary-color);
            }

            .stat-rejected {
                background: linear-gradient(135deg, #FFF0F0, #fff);
                border-left: 4px solid #FF6B6B;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-rejected::before {
                background: #FF6B6B;
            }

            .stat-icon-wrap {
                width: 64px;
                height: 64px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .stat-card:hover .stat-icon-wrap {
                transform: rotate(-5deg) scale(1.05);
            }

            .icon-total {
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.25));
                color: #6366F1;
            }

            .icon-pending {
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
                color: var(--primary-color);
            }

            .icon-accepted {
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
                color: var(--secondary-color);
            }

            .icon-rejected {
                background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
                color: #FF6B6B;
            }

            .stat-label {
                font-size: 0.875rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--text-muted);
            }

            .stat-number {
                font-size: 2.25rem;
                font-weight: 700;
                color: var(--text-dark);
            }

            /* Profile Card */
            .profile-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                height: 100%;
            }

            .profile-header {
                padding: 1.5rem;
                border-bottom: 2px solid var(--border-color);
                background: linear-gradient(135deg, #FAFBFC, #fff);
            }

            .profile-avatar {
                width: 52px;
                height: 52px;
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 1.35rem;
                flex-shrink: 0;
            }

            .profile-name {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.05rem;
            }

            .profile-email {
                color: var(--text-muted);
                font-size: 0.85rem;
            }

            .btn-edit-profile {
                background: transparent;
                color: var(--primary-color);
                border: 2px solid var(--primary-color);
                padding: 0.4rem 1rem;
                border-radius: 8px;
                font-weight: 600;
                font-size: 0.85rem;
                transition: all 0.3s ease;
            }

            .btn-edit-profile:hover {
                background: var(--primary-color);
                color: white;
            }

            .profile-body {
                padding: 1.5rem;
            }

            .profile-info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 0.75rem;
                padding: 1rem;
                background: var(--background-light);
                border-radius: 10px;
            }

            .profile-info-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--text-dark);
                font-size: 0.9rem;
                font-weight: 500;
            }

            .profile-info-item i {
                color: var(--primary-color);
                font-size: 1rem;
            }

            .profile-summary-label {
                font-weight: 600;
                color: var(--text-dark);
                font-size: 0.95rem;
                margin-bottom: 0.75rem;
            }

            .profile-summary-text {
                color: var(--text-muted);
                font-size: 0.95rem;
                line-height: 1.7;
                margin-bottom: 1rem;
            }

            .btn-view-resume {
                display: inline-flex;
                align-items: center;
                padding: 0.6rem 1.25rem;
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                color: white;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(78, 205, 196, 0.3);
            }

            .btn-view-resume:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
                color: white;
            }

            /* Search Card */
            .search-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                height: 100%;
            }

            .search-header {
                padding: 1.5rem 1.5rem 0;
            }

            .search-header h6 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.05rem;
            }

            .search-body {
                padding: 1.25rem 1.5rem 1.5rem;
            }

            .search-input-group {
                display: flex;
                align-items: center;
                position: relative;
                gap: 0.75rem;
            }

            .search-input-icon {
                position: absolute;
                left: 1rem;
                color: var(--text-muted);
                font-size: 1.1rem;
                z-index: 2;
            }

            .search-input {
                padding: 0.85rem 1rem 0.85rem 2.75rem;
                border: 2px solid var(--border-color);
                border-radius: 12px;
                font-size: 0.95rem;
                font-family: 'Outfit', sans-serif;
                transition: all 0.3s ease;
                flex: 1;
            }

            .search-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .btn-search {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 0.85rem 1.5rem;
                border-radius: 12px;
                font-weight: 700;
                white-space: nowrap;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .btn-search:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
                color: white;
            }

            .search-tags {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .search-tag-label {
                color: var(--text-muted);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .search-tag {
                background: var(--background-light);
                color: var(--text-muted);
                padding: 0.3rem 0.75rem;
                border-radius: 20px;
                font-size: 0.82rem;
                font-weight: 600;
                text-decoration: none;
                border: 1px solid var(--border-color);
                transition: all 0.2s ease;
            }

            .search-tag:hover {
                background: rgba(255, 107, 53, 0.1);
                color: var(--primary-color);
                border-color: rgba(255, 107, 53, 0.3);
            }

            /* Modal */
            .modal-custom {
                border-radius: 16px;
                border: none;
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
                overflow: hidden;
            }

            .modal-header-custom {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 1.25rem 1.5rem;
                border: none;
            }

            .modal-header-custom .modal-title {
                font-weight: 700;
            }

            .btn-close-custom {
                filter: invert(1);
                opacity: 0.8;
            }

            .btn-close-custom:hover {
                opacity: 1;
            }

            .modal-body-custom {
                padding: 1.75rem 1.5rem 1rem;
            }

            .modal-input-label {
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .modal-input-label i {
                color: var(--primary-color);
            }

            .modal-input {
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-family: 'Outfit', sans-serif;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .modal-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .modal-textarea {
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.85rem 1rem;
                font-family: 'Outfit', sans-serif;
                font-size: 0.95rem;
                resize: vertical;
                transition: all 0.3s ease;
            }

            .modal-textarea:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .current-file-display {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1rem;
                background: linear-gradient(135deg, #F0FFFE, #FFFFFF);
                border: 2px solid rgba(78, 205, 196, 0.3);
                border-radius: 8px;
                margin-bottom: 0.75rem;
                font-size: 0.9rem;
            }

            .current-file-display i {
                color: var(--secondary-color);
                font-size: 1.1rem;
            }

            .current-file-display span {
                color: var(--text-muted);
            }

            .current-file-display a {
                color: var(--primary-color);
                font-weight: 600;
                text-decoration: none;
            }

            .current-file-display a:hover {
                text-decoration: underline;
            }

            .modal-hint {
                color: var(--text-muted);
                font-size: 0.82rem;
                margin-top: 0.5rem;
                display: block;
            }

            .modal-footer-custom {
                padding: 1rem 1.5rem 1.5rem;
                border: none;
                justify-content: flex-end;
                gap: 0.75rem;
                display: flex;
            }

            .btn-modal-cancel {
                background: transparent;
                border: 2px solid var(--border-color);
                color: var(--text-dark);
                padding: 0.6rem 1.25rem;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .btn-modal-cancel:hover {
                border-color: var(--text-dark);
            }

            .btn-modal-save {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border: none;
                padding: 0.6rem 1.25rem;
                border-radius: 10px;
                font-weight: 700;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .btn-modal-save:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(255, 107, 53, 0.4);
                color: white;
            }

            /* Jobs List Card */
            .jobs-list-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .jobs-list-header {
                padding: 1.25rem 1.5rem;
                border-bottom: 2px solid var(--border-color);
                background: linear-gradient(135deg, #FAFBFC, #fff);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .jobs-header-icon {
                width: 42px;
                height: 42px;
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 1.25rem;
            }

            .jobs-list-header h5 {
                color: var(--text-dark);
                font-weight: 700;
            }

            .btn-see-all {
                color: var(--primary-color);
                font-weight: 700;
                text-decoration: none;
                font-size: 0.9rem;
                transition: all 0.2s ease;
            }

            .btn-see-all:hover {
                color: var(--primary-dark);
            }

            .jobs-list-body {
                padding: 1.5rem;
            }

            /* Job Card */
            .job-card {
                background: white;
                border-radius: 14px;
                border: 2px solid var(--border-color);
                padding: 1.5rem;
                height: 100%;
                display: flex;
                flex-direction: column;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .job-card::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 4px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                transform: scaleY(0);
                transition: transform 0.3s ease;
            }

            .job-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                border-color: transparent;
            }

            .job-card:hover::before {
                transform: scaleY(1);
            }

            .job-card-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .job-card-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.1rem;
                margin-bottom: 0;
            }

            .applied-badge {
                background: linear-gradient(135deg, #D5F4E6, #B8EDDA);
                color: #0F6848;
                padding: 0.3rem 0.75rem;
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 600;
                display: flex;
                align-items: center;
            }

            .job-card-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
                margin-bottom: 1rem;
            }

            .job-meta-item {
                color: var(--text-muted);
                font-size: 0.88rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .job-meta-item i {
                color: var(--primary-color);
            }

            .job-type-badge {
                background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
                color: var(--secondary-color);
                padding: 0.3rem 0.75rem;
                border-radius: 8px;
                font-size: 0.82rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.35rem;
                border: 1px solid rgba(78, 205, 196, 0.3);
            }

            .job-card-desc {
                color: var(--text-muted);
                font-size: 0.9rem;
                line-height: 1.6;
                flex-grow: 1;
                margin-bottom: 1rem;
            }

            .btn-job-details {
                border: 2px solid var(--primary-color);
                color: var(--primary-color);
                padding: 0.4rem 1rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-job-details:hover {
                background: var(--primary-color);
                color: white;
            }


            /* Empty */
            .empty-state {
                text-align: center;
                padding: 3rem 2rem;
            }

            .empty-icon {
                font-size: 3.5rem;
                color: var(--text-muted);
                opacity: 0.4;
                margin-bottom: 1rem;
            }

            .empty-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.15rem;
                margin-bottom: 0.25rem;
            }

            .empty-text {
                color: var(--text-muted);
                font-size: 0.95rem;
            }

            /* Animations */
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .stat-card {
                animation: slideUp 0.5s ease-out;
            }

            .stat-card:nth-child(2) {
                animation-delay: 0.07s;
            }

            .stat-card:nth-child(3) {
                animation-delay: 0.14s;
            }

            .stat-card:nth-child(4) {
                animation-delay: 0.21s;
            }

            .jobs-list-card {
                animation: slideUp 0.5s ease-out 0.25s both;
            }
        </style>
    @endsection
@endcan
