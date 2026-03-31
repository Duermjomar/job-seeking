@extends('layouts.Admin.app')

@section('content')
<div class="jobs-wrapper">
    <div class="container-fluid px-4 py-5">

        {{-- Header --}}
        <div class="page-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-1">All Job Postings</h3>
                        <p class="header-subtitle mb-0">Manage and monitor all employer job listings</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-pill stat-total">
                    <i class="bi bi-briefcase-fill"></i>
                    <div>
                        <div class="stat-num">{{ $totalJobs }}</div>
                        <div class="stat-lbl">Total Jobs</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-pill stat-open">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>
                        <div class="stat-num">{{ $openJobs }}</div>
                        <div class="stat-lbl">Open</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-pill stat-closed">
                    <i class="bi bi-x-circle-fill"></i>
                    <div>
                        <div class="stat-num">{{ $closedJobs }}</div>
                        <div class="stat-lbl">Closed</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-card mb-4">
            <form method="GET" action="{{ route('admin.jobs.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="filter-label">Search</label>
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="filter-input ps-5"
                            placeholder="Search by title or location..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="open"   {{ request('status') === 'open'   ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Type</label>
                    <select name="type" class="filter-input">
                        <option value="">All Types</option>
                        <option value="full-time"   {{ request('type') === 'full-time'   ? 'selected' : '' }}>Full-time</option>
                        <option value="part-time"   {{ request('type') === 'part-time'   ? 'selected' : '' }}>Part-time</option>
                        <option value="internship"  {{ request('type') === 'internship'  ? 'selected' : '' }}>Internship</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-filter-apply flex-grow-1">
                        <i class="bi bi-funnel-fill me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn-filter-reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="alert-custom alert-success mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Jobs Table --}}
        <div class="jobs-card">
            <div class="jobs-card-header">
                <span>
                    Showing <strong>{{ $jobs->firstItem() }}–{{ $jobs->lastItem() }}</strong>
                    of <strong>{{ $jobs->total() }}</strong> jobs
                </span>
            </div>

            <div class="table-responsive">
                <table class="jobs-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Employer / Company</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Applicants</th>
                            <th>Status</th>
                            <th>Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td class="text-muted small">{{ $job->id }}</td>
                            <td>
                                <div class="job-title-cell">{{ $job->job_title }}</div>
                                @if($job->salary)
                                    <div class="job-salary">
                                        <i class="bi bi-cash-stack me-1"></i>
                                        ₱{{ number_format($job->salary) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="employer-name">
                                    {{ $job->employer->name ?? 'N/A' }}
                                </div>
                                @if($job->employer && $job->employer->employer)
                                    <div class="company-name">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $job->employer->employer->company_name }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="type-badge type-{{ str_replace('-', '', $job->job_type) }}">
                                    {{ ucfirst($job->job_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="location-text">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                                </span>
                            </td>
                            <td>
                                <span class="applicant-pill">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ $job->applications_count }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill status-{{ $job->status }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $job->created_at->format('M d, Y') }}
                                <div style="font-size:0.72rem;">{{ $job->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="action-group">
                                    {{-- Toggle Status --}}
                                    {{-- <form action="{{ route('admin.jobs.toggleStatus', $job->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="act-btn {{ $job->status === 'open' ? 'act-close' : 'act-open' }}"
                                            title="{{ $job->status === 'open' ? 'Close Job' : 'Reopen Job' }}">
                                            <i class="bi bi-{{ $job->status === 'open' ? 'lock-fill' : 'unlock-fill' }}"></i>
                                        </button>
                                    </form> --}}

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.jobs.destroy', $job->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this job? This will also remove all applications.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="act-btn act-delete" title="Delete Job">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-briefcase"></i>
                                    <p>No jobs found</p>
                                    <small>Try adjusting your filters</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($jobs->hasPages())
                <div class="pagination-wrapper">
                    {{ $jobs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
        --bg-light: #F7F9FC;
    }

    * { font-family: 'Outfit', sans-serif; }
    body { background: var(--bg-light); }
    .jobs-wrapper { min-height: 100vh; }

    /* Header */
    .page-header {
        background: white; padding: 1.5rem; border-radius: 16px;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .header-icon {
        width: 64px; height: 64px; border-radius: 14px;
        background: linear-gradient(135deg, rgba(255,107,53,.15), rgba(255,107,53,.25));
        color: var(--primary-color); font-size: 1.75rem;
        display: flex; align-items: center; justify-content: center;
    }
    .page-header h3 { color: var(--text-dark); font-weight: 700; font-size: 1.5rem; margin: 0; }
    .header-subtitle { color: var(--text-muted); font-size: 1rem; }

    .btn-back {
        display: inline-flex; align-items: center;
        padding: 0.5rem 1.25rem; background: white;
        color: var(--text-dark); border: 2px solid var(--border-color);
        border-radius: 10px; font-weight: 600; text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background: var(--primary-color); color: white;
        border-color: var(--primary-color); transform: translateX(-4px);
    }

    /* Stat Pills */
    .stat-pill {
        display: flex; align-items: center; gap: 1rem;
        padding: 1.25rem 1.5rem; border-radius: 14px;
        border: 2px solid var(--border-color); background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease;
    }
    .stat-pill:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .stat-pill i { font-size: 2rem; }
    .stat-total i { color: var(--primary-color); }
    .stat-open  i { color: #0F6848; }
    .stat-closed i { color: #C92A2A; }
    .stat-num { font-size: 2rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    .stat-lbl { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }

    /* Filter Card */
    .filter-card {
        background: white; border-radius: 14px;
        border: 2px solid var(--border-color);
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .filter-label { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 0.4rem; }
    .search-wrapper { position: relative; }
    .search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    .filter-input {
        width: 100%; padding: 0.7rem 1rem; border: 2px solid var(--border-color);
        border-radius: 10px; background: var(--bg-light); color: var(--text-dark);
        font-size: 0.9rem; font-family: 'Outfit', sans-serif;
        transition: all 0.3s ease;
    }
    .filter-input:focus { outline: none; border-color: var(--primary-color); background: white; box-shadow: 0 0 0 4px rgba(255,107,53,.1); }

    .btn-filter-apply {
        padding: 0.7rem 1rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white; border: none; border-radius: 10px; font-weight: 700;
        font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-filter-apply:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,107,53,.3); }

    .btn-filter-reset {
        padding: 0.7rem 0.9rem; background: white; color: var(--text-muted);
        border: 2px solid var(--border-color); border-radius: 10px;
        text-decoration: none; font-size: 1rem; display: flex;
        align-items: center; justify-content: center; transition: all 0.3s ease;
    }
    .btn-filter-reset:hover { border-color: var(--primary-color); color: var(--primary-color); }

    /* Alert */
    .alert-custom { border-radius: 12px; padding: 1rem 1.25rem; font-weight: 500; display: flex; align-items: center; }
    .alert-success { background: linear-gradient(135deg, #E8F8F5, #D5F4E6); color: #0F6848; }

    /* Jobs Card */
    .jobs-card {
        background: white; border-radius: 16px;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;
    }
    .jobs-card-header {
        padding: 1rem 1.5rem; border-bottom: 2px solid var(--border-color);
        background: linear-gradient(135deg, #FAFBFC, white);
        color: var(--text-muted); font-size: 0.9rem;
    }
    .jobs-card-header strong { color: var(--text-dark); }

    /* Table */
    .jobs-table { width: 100%; border-collapse: collapse; }
    .jobs-table thead tr { background: linear-gradient(135deg, #F7F9FC, white); }
    .jobs-table th {
        padding: 0.9rem 1rem; text-align: left; font-size: 0.78rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
        color: var(--text-muted); border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
    }
    .jobs-table td {
        padding: 1rem; font-size: 0.88rem; color: var(--text-dark);
        border-bottom: 1px solid var(--border-color); vertical-align: middle;
    }
    .jobs-table tbody tr { transition: background 0.2s ease; }
    .jobs-table tbody tr:hover { background: #FAFBFC; }
    .jobs-table tbody tr:last-child td { border-bottom: none; }

    .job-title-cell { font-weight: 700; color: var(--text-dark); margin-bottom: 0.2rem; }
    .job-salary { font-size: 0.78rem; color: var(--text-muted); }
    .employer-name { font-weight: 600; color: var(--text-dark); }
    .company-name { font-size: 0.78rem; color: var(--text-muted); }
    .location-text { font-size: 0.83rem; color: var(--text-muted); }

    /* Badges */
    .type-badge {
        display: inline-flex; padding: 0.3rem 0.7rem;
        border-radius: 7px; font-size: 0.75rem; font-weight: 700; white-space: nowrap;
    }
    .type-fulltime  { background: #E0E7FF; color: #4338CA; }
    .type-parttime  { background: #FEF3C7; color: #92400E; }
    .type-internship { background: #D5F4E6; color: #0F6848; }

    .status-pill {
        display: inline-flex; align-items: center;
        padding: 0.3rem 0.8rem; border-radius: 8px;
        font-size: 0.78rem; font-weight: 700;
    }
    .status-open   { background: #D5F4E6; color: #0F6848; border: 1.5px solid #95E1D3; }
    .status-closed { background: #FFE5E5; color: #C92A2A; border: 1.5px solid #FF6B6B; }

    .applicant-pill {
        display: inline-flex; align-items: center;
        background: var(--bg-light); border: 1.5px solid var(--border-color);
        padding: 0.3rem 0.75rem; border-radius: 8px;
        font-size: 0.8rem; font-weight: 700; color: var(--text-dark);
    }

    /* Actions */
    .action-group { display: flex; gap: 0.4rem; align-items: center; }
    .act-btn {
        width: 34px; height: 34px; border: none; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease;
    }
    .act-close  { background: #FFF4E6; color: #D97706; }
    .act-close:hover  { background: #D97706; color: white; transform: translateY(-2px); }
    .act-open   { background: #D5F4E6; color: #0F6848; }
    .act-open:hover   { background: #0F6848; color: white; transform: translateY(-2px); }
    .act-delete { background: #FFE5E5; color: #C92A2A; }
    .act-delete:hover { background: #C92A2A; color: white; transform: translateY(-2px); }

    /* Empty */
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
    .empty-state i { font-size: 3.5rem; opacity: 0.3; display: block; margin-bottom: 1rem; }
    .empty-state p { font-weight: 600; font-size: 1.1rem; margin: 0; }
    .empty-state small { font-size: 0.9rem; }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.25rem 1.5rem; border-top: 2px solid var(--border-color);
        background: linear-gradient(135deg, #F7F9FC, white);
        display: flex; justify-content: center;
    }
    .page-link { border: 2px solid var(--border-color); border-radius: 8px; color: var(--text-dark); font-weight: 600; margin: 0 2px; transition: all 0.2s; }
    .page-link:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; }
    .page-item.active .page-link { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-color: var(--primary-color); }

    @media (max-width: 768px) {
        .stat-num { font-size: 1.5rem; }
        .page-header h3 { font-size: 1.2rem; }
    }
</style>
@endsection