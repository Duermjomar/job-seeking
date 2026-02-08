@extends('layouts.Employer.app')

@section('content')
    <div class="dashboard-wrapper">
        <div class="container-fluid px-4 py-5">
            <div class="row">
                <div class="col-12">

                    {{-- Header Section --}}
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h1 class="h3 fw-bold mb-1 text-primary-dark">Welcome back, {{ auth()->user()->name }}</h1>
                            <p class="text-muted-custom mb-0">Manage your job postings and track applicants</p>
                        </div>
                        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary-custom px-4 py-2 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Post New Job
                        </a>
                    </div>

                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))
                        <div class="alert alert-custom alert-success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Stats Overview --}}
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="stat-card card-jobs h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="icon-wrapper icon-jobs">
                                                <i class="bi bi-briefcase-fill fs-3"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-4">
                                            <p class="stat-label mb-1">Total Jobs</p>
                                            <h2 class="stat-number mb-0">{{ $totalPostedJobs }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="stat-card card-applicants h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="icon-wrapper icon-applicants">
                                                <i class="bi bi-people-fill fs-3"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-4">
                                            <p class="stat-label mb-1">Total Applicants</p>
                                            <h2 class="stat-number mb-0">{{ $totalApplicants }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Job Posts --}}
                    <div class="job-list-card">
                        <div class="card-header-custom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">Recent Job Posts</h5>
                                <span class="badge badge-count">{{ $totalPostedJobs }} Total</span>
                            </div>
                        </div>
                        <div class="card-body-custom">
                            @php
                                $latestJobs = \App\Models\Job::where('employer_id', auth()->user()->id)
                                    ->withCount('applications')
                                    ->latest()
                                    ->take(10)
                                    ->get();
                            @endphp

                            @forelse($latestJobs as $job)
                                <div class="job-item {{ $loop->last ? 'border-0' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-3 mb-lg-0">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="job-title mb-0">{{ $job->job_title }}</h5>
                                                <span class="status-badge status-{{ $job->status }}">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </div>
                                            <p class="job-description mb-2">
                                                {{ \Illuminate\Support\Str::limit($job->job_description, 100) }}</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="job-badge badge-location">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                                                </span>
                                                <span class="job-badge badge-type">
                                                    <i class="bi bi-clock me-1"></i>{{ ucfirst($job->job_type) }}
                                                </span>
                                                <span class="job-badge badge-time">
                                                    <i
                                                        class="bi bi-calendar me-1"></i>{{ $job->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div
                                                class="d-flex flex-column flex-lg-row gap-2 align-items-lg-center justify-content-lg-end">
                                                <span class="applicant-count me-lg-3">
                                                    <i class="bi bi-person-check me-1"></i>
                                                    {{ $job->applications_count }}
                                                    Applicant{{ $job->applications_count !== 1 ? 's' : '' }}
                                                </span>
                                                <div class="btn-group-actions">
                                                    <a href="{{ route('employer.jobs.applicants', $job->id) }}"
                                                        class="btn btn-action btn-applicants" title="View Applicants">
                                                        <i class="bi bi-people-fill me-1"></i>
                                                        Applicants
                                                    </a>
                                                    <a href="{{ route('employer.jobs.edit', $job->id) }}"
                                                        class="btn btn-action btn-edit" title="Edit Job">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    {{-- <button type="button" class="btn btn-action btn-delete"
                                                        onclick="confirmDelete({{ $job->id }})" title="Delete Job">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Hidden Delete Form --}}
                                    <form id="delete-form-{{ $job->id }}"
                                        action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <p class="empty-text mt-3 mb-4">No jobs posted yet</p>
                                    <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary-custom">
                                        Post Your First Job
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #FF6B35;
            --primary-dark: #E85A2A;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --success-color: #95E1D3;
            --danger-color: #FF6B6B;
            --background-light: #F7F9FC;
            --text-dark: #2D3748;
            --text-muted: #718096;
            --border-color: #E2E8F0;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background-light);
        }

        .dashboard-wrapper {
            min-height: 100vh;
        }

        .text-primary-dark {
            color: var(--text-dark) !important;
        }

        .text-muted-custom {
            color: var(--text-muted);
        }

        /* Alert */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
            animation: slideDown 0.3s ease-out;
            display: flex;
            align-items: center;
        }

        .alert-success {
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

        /* Primary Button */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: white;
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            opacity: 0.1;
            transition: all 0.3s ease;
        }

        .card-jobs {
            background: linear-gradient(135deg, #FFF5F2 0%, #FFFFFF 100%);
            border-left: 4px solid var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .card-jobs::before {
            background: var(--primary-color);
            transform: translate(50%, -50%);
        }

        .card-jobs:hover::before {
            transform: translate(40%, -40%) scale(1.2);
        }

        .card-applicants {
            background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
            border-left: 4px solid var(--secondary-color);
            box-shadow: var(--shadow-sm);
        }

        .card-applicants::before {
            background: var(--secondary-color);
            transform: translate(50%, -50%);
        }

        .card-applicants:hover::before {
            transform: translate(40%, -40%) scale(1.2);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .icon-jobs {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15) 0%, rgba(255, 107, 53, 0.25) 100%);
            color: var(--primary-color);
        }

        .icon-applicants {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15) 0%, rgba(78, 205, 196, 0.25) 100%);
            color: var(--secondary-color);
        }

        .stat-card:hover .icon-wrapper {
            transform: rotate(-5deg) scale(1.05);
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Badge Count */
        .badge-count {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15) 0%, rgba(255, 107, 53, 0.25) 100%);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Job List Card */
        .job-list-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #FFFFFF 0%, #F7F9FC 100%);
            border-bottom: 2px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-header-custom h5 {
            color: var(--text-dark);
            font-weight: 600;
        }

        .card-body-custom {
            padding: 0;
        }

        /* Job Items */
        .job-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .job-item::before {
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

        .job-item:hover {
            background-color: #FAFBFC;
            padding-left: 2rem;
        }

        .job-item:hover::before {
            transform: scaleY(1);
        }

        .job-title {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.125rem;
        }

        .job-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-open {
            background: linear-gradient(135deg, #D5F4E6 0%, #B8EDDA 100%);
            color: #0F6848;
            border: 2px solid #95E1D3;
        }

        .status-closed {
            background: linear-gradient(135deg, #FFE5E5 0%, #FFD0D0 100%);
            color: #C92A2A;
            border: 2px solid #FF6B6B;
        }

        .job-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .badge-location {
            background-color: #FFF5F2;
            color: var(--primary-color);
            border: 1px solid rgba(255, 107, 53, 0.2);
        }

        .badge-type {
            background-color: #FFF4E6;
            color: #D97706;
            border: 1px solid rgba(255, 184, 77, 0.2);
        }

        .badge-time {
            background-color: #F0FFFE;
            color: var(--secondary-color);
            border: 1px solid rgba(78, 205, 196, 0.2);
        }

        .job-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .applicant-count {
            font-size: 0.95rem;
            color: var(--text-dark);
            font-weight: 600;
            background: #F7F9FC;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
        }

        /* Action Buttons */
        .btn-group-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-applicants {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #3DBDB4 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(78, 205, 196, 0.3);
        }

        .btn-applicants:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
            background: linear-gradient(135deg, #3DBDB4 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #FFE66D 0%, #FFD84D 100%);
            color: #8B6914;
            box-shadow: 0 2px 8px rgba(255, 230, 109, 0.3);
            width: 40px;
            padding: 0.5rem;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 230, 109, 0.4);
            background: linear-gradient(135deg, #FFD84D 0%, #FFE66D 100%);
            color: #8B6914;
        }

        .btn-delete {
            background: linear-gradient(135deg, #FFB3B3 0%, #FF9999 100%);
            color: #C92A2A;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
            width: 40px;
            padding: 0.5rem;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
            background: linear-gradient(135deg, #FF9999 0%, #FF6B6B 100%);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-muted);
            opacity: 0.5;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .btn-group-actions {
                width: 100%;
            }

            .btn-applicants {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .stat-number {
                font-size: 2rem;
            }

            .icon-wrapper {
                width: 56px;
                height: 56px;
            }

            .job-item:hover {
                padding-left: 1.5rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.6rem;
            }
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
            animation-delay: 0.1s;
        }

        .job-list-card {
            animation: slideUp 0.5s ease-out 0.2s both;
        }
    </style>

    <script>
        function confirmDelete(jobId) {
            if (confirm(
                    'Are you sure you want to delete this job? This action cannot be undone and will also delete all applications.'
                    )) {
                document.getElementById('delete-form-' + jobId).submit();
            }
        }
    </script>
@endsection
