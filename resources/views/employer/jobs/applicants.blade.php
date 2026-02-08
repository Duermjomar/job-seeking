@can('employer-access')
    @extends('layouts.Employer.app')

    @section('content')
        <div class="applicants-wrapper">
            <div class="container-fluid px-4 py-5">

                {{-- Back Button & Header --}}
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-back mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>

                    <div class="page-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-1">Applicants</h3>
                                    <p class="job-title-header mb-0">{{ $job->job_title }}</p>
                                </div>
                            </div>
                            <div class="header-stats">
                                <span class="stat-badge">
                                    <i class="bi bi-person-check-fill me-2"></i>
                                    {{ $applications->count() }} Total
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FLASH MESSAGE --}}
                @if (session('success'))
                    <div class="alert alert-custom alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter Tabs --}}
                <div class="filter-section mb-4">
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterApplicants('all')">
                            <i class="bi bi-grid me-1"></i>
                            All <span class="tab-count">({{ $applications->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('pending')">
                            <i class="bi bi-clock me-1"></i>
                            Pending <span class="tab-count">({{ $applications->where('application_status', 'pending')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('accepted')">
                            <i class="bi bi-check-circle me-1"></i>
                            Accepted <span class="tab-count">({{ $applications->where('application_status', 'accepted')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('rejected')">
                            <i class="bi bi-x-circle me-1"></i>
                            Rejected <span class="tab-count">({{ $applications->where('application_status', 'rejected')->count() }})</span>
                        </button>
                    </div>
                </div>

                {{-- Applicants List --}}
                <div class="applicants-container">
                    @forelse($applications as $index => $app)
                        <div class="applicant-card {{ request('highlight') == $app->id ? 'highlight-card' : '' }}" 
                             data-status="{{ $app->application_status }}" 
                             data-index="{{ $index }}"
                             data-app-id="{{ $app->id }}"
                             id="applicant-{{ $app->id }}">
                            
                            {{-- COMPACT HEADER (Always Visible) --}}
                            <div class="card-header-compact" onclick="toggleCard({{ $index }})">
                                <div class="header-left">
                                    <div class="applicant-avatar-sm">
                                        {{ strtoupper(substr($app->jobSeeker->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div class="applicant-brief">
                                        <h6 class="applicant-name-sm">{{ $app->jobSeeker->user->name ?? 'N/A' }}</h6>
                                        <p class="applicant-meta-sm">
                                            <i class="bi bi-envelope"></i> {{ Str::limit($app->jobSeeker->user->email ?? 'N/A', 30) }}
                                            <span class="meta-divider">•</span>
                                            <i class="bi bi-calendar3"></i> {{ $app->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="header-right">
                                    @if($app->jobSeeker->skills)
                                        <div class="quick-skills">
                                            @foreach(array_slice(explode(',', $app->jobSeeker->skills), 0, 2) as $skill)
                                                <span class="skill-tag-mini">{{ trim($skill) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <span class="status-badge-sm 
                                        @if ($app->application_status === 'accepted') status-accepted
                                        @elseif($app->application_status === 'rejected') status-rejected
                                        @else status-pending @endif">
                                        {{ ucfirst($app->application_status) }}
                                    </span>
                                    
                                    <button class="btn-toggle" type="button">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- EXPANDABLE DETAILS --}}
                            <div class="card-details">
                                <div class="details-inner">
                                    <div class="row g-3">
                                        
                                        {{-- COLUMN 1: Contact & Profile --}}
                                        <div class="col-lg-4">
                                            {{-- Contact Info --}}
                                            <div class="detail-box">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-person-vcard me-2"></i>Contact
                                                </h6>
                                                <div class="detail-list">
                                                    @if($app->jobSeeker->phone)
                                                        <div class="detail-item">
                                                            <i class="bi bi-telephone-fill"></i>
                                                            <span>{{ $app->jobSeeker->phone }}</span>
                                                        </div>
                                                    @endif
                                                    @if($app->jobSeeker->address)
                                                        <div class="detail-item">
                                                            <i class="bi bi-geo-alt-fill"></i>
                                                            <span>{{ Str::limit($app->jobSeeker->address, 50) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="detail-item">
                                                        <i class="bi bi-envelope-fill"></i>
                                                        <span>{{ $app->jobSeeker->user->email ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Profile Summary --}}
                                            @if($app->jobSeeker->profile_summary)
                                                <div class="detail-box mt-3">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-file-text me-2"></i>Summary
                                                    </h6>
                                                    <p class="detail-text">{{ Str::limit($app->jobSeeker->profile_summary, 200) }}</p>
                                                </div>
                                            @endif

                                            {{-- Skills --}}
                                            @if($app->jobSeeker->skills)
                                                <div class="detail-box mt-3">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-stars me-2"></i>Skills
                                                    </h6>
                                                    <div class="skills-wrap">
                                                        @foreach(explode(',', $app->jobSeeker->skills) as $skill)
                                                            <span class="skill-tag">{{ trim($skill) }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- COLUMN 2: Education & Experience --}}
                                        <div class="col-lg-4">
                                            @if($app->jobSeeker->education)
                                                <div class="detail-box">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-mortarboard me-2"></i>Education
                                                    </h6>
                                                    <p class="detail-text">{{ $app->jobSeeker->education }}</p>
                                                </div>
                                            @endif

                                            @if($app->jobSeeker->experience)
                                                <div class="detail-box {{ $app->jobSeeker->education ? 'mt-3' : '' }}">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-briefcase me-2"></i>Experience
                                                    </h6>
                                                    <p class="detail-text">{{ $app->jobSeeker->experience }}</p>
                                                </div>
                                            @endif

                                            {{-- Documents --}}
                                            <div class="detail-box {{ ($app->jobSeeker->education || $app->jobSeeker->experience) ? 'mt-3' : '' }}">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-paperclip me-2"></i>Documents
                                                </h6>
                                                
                                                {{-- Resume --}}
                                                @if($app->jobSeeker->resume || $app->resume)
                                                    <a href="{{ asset('storage/' . ($app->resume ?? $app->jobSeeker->resume)) }}" 
                                                       target="_blank" 
                                                       class="doc-btn doc-btn-primary mb-2">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                        <span>Resume</span>
                                                        <i class="bi bi-download ms-auto"></i>
                                                    </a>
                                                @else
                                                    <div class="doc-missing mb-2">
                                                        <i class="bi bi-file-x"></i>
                                                        <span>No resume</span>
                                                    </div>
                                                @endif

                                                {{-- Application Letter --}}
                                                @php
                                                    $letter = $app->files->where('file_type', 'application_letter')->first();
                                                @endphp
                                                @if($letter)
                                                    <a href="{{ asset('storage/' . $letter->file_path) }}"
                                                       target="_blank" 
                                                       class="doc-btn doc-btn-secondary">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        <span>Cover Letter</span>
                                                        <i class="bi bi-download ms-auto"></i>
                                                    </a>
                                                @else
                                                    <div class="doc-missing">
                                                        <i class="bi bi-file-x"></i>
                                                        <span>No cover letter</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- COLUMN 3: Actions --}}
                                        <div class="col-lg-4">
                                            <div class="action-box">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-gear me-2"></i>Quick Actions
                                                </h6>
                                                
                                                @if($app->application_status === 'pending')
                                                    <form action="{{ route('employer.applications.updateStatus', $app->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Accept this application?')"
                                                          class="mb-2">
                                                        @csrf
                                                        <input type="hidden" name="status" value="accepted">
                                                        <button type="submit" class="action-btn action-accept">
                                                            <i class="bi bi-check-circle"></i>
                                                            <span>Accept Applicant</span>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('employer.applications.updateStatus', $app->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Reject this application?')">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="action-btn action-reject">
                                                            <i class="bi bi-x-circle"></i>
                                                            <span>Reject Applicant</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="action-completed-box">
                                                        <i class="bi bi-check2-all"></i>
                                                        <p class="mb-0">
                                                            <strong>Decision Made:</strong><br>
                                                            {{ ucfirst($app->application_status) }}
                                                        </p>
                                                    </div>
                                                @endif

                                                {{-- Additional Info --}}
                                                <div class="info-summary mt-3">
                                                    <div class="info-summary-item">
                                                        <span class="info-label">Applied</span>
                                                        <span class="info-value">{{ $app->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="info-summary-item">
                                                        <span class="info-label">Status</span>
                                                        <span class="info-value">{{ ucfirst($app->application_status) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                            <p class="empty-text">No applicants yet for this job</p>
                            <p class="empty-subtext">Applications will appear here once job seekers apply</p>
                        </div>
                    @endforelse
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

            * { font-family: 'Outfit', sans-serif; }
            body { background: var(--background-light); }
            
            .applicants-wrapper { min-height: 100vh; }

            /* Back & Header */
            .btn-back {
                background: white; color: var(--text-dark); border: 2px solid var(--border-color);
                border-radius: 10px; padding: 0.5rem 1.25rem; font-weight: 600;
                text-decoration: none; display: inline-flex; align-items: center;
                transition: all 0.3s ease;
            }
            .btn-back:hover {
                background: var(--primary-color); color: white;
                border-color: var(--primary-color); transform: translateX(-4px);
            }

            .page-header {
                background: linear-gradient(135deg, #FFF 0%, #F7F9FC 100%);
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
            .job-title-header { color: var(--text-muted); font-size: 1rem; font-weight: 500; }
            .stat-badge {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white; padding: 0.6rem 1.25rem; border-radius: 10px;
                font-weight: 700; font-size: 0.9rem; display: flex; align-items: center;
                box-shadow: 0 4px 12px rgba(255,107,53,0.3);
            }

            /* Alert */
            .alert-custom {
                border-radius: 12px; padding: 1rem 1.25rem; font-weight: 500;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08); animation: slideDown 0.3s ease;
            }
            .alert-success { background: linear-gradient(135deg, #E8F8F5, #D5F4E6); color: #0F6848; }
            @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

            /* Filter Tabs */
            .filter-section { margin-top: 1.5rem; }
            .filter-tabs { display: flex; gap: 0.6rem; flex-wrap: wrap; }
            .filter-tab {
                background: white; border: 2px solid var(--border-color); color: var(--text-muted);
                padding: 0.65rem 1.15rem; border-radius: 10px; font-weight: 600; font-size: 0.9rem;
                cursor: pointer; transition: all 0.25s ease; display: flex; align-items: center; gap: 0.35rem;
            }
            .filter-tab:hover { border-color: var(--primary-color); color: var(--primary-color); background: rgba(255,107,53,0.06); }
            .filter-tab.active {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white; border-color: transparent; box-shadow: 0 4px 12px rgba(255,107,53,0.3);
            }
            .tab-count { font-size: 0.85rem; opacity: 0.9; }

            /* Compact Card */
            .applicant-card {
                background: white; border-radius: 12px; border: 2px solid var(--border-color);
                margin-bottom: 0.75rem; overflow: hidden; transition: all 0.3s ease;
            }
            .applicant-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); border-color: var(--primary-color); }
            .applicant-card.hidden { display: none; }

            /* Highlight Animation */
            .highlight-card {
                animation: highlightPulse 2s ease-in-out;
                border-color: var(--primary-color) !important;
                box-shadow: 0 0 0 4px rgba(255,107,53,0.2), 0 4px 16px rgba(0,0,0,0.08) !important;
            }

            @keyframes highlightPulse {
                0%, 100% { 
                    box-shadow: 0 0 0 0 rgba(255,107,53,0.4), 0 4px 16px rgba(0,0,0,0.08);
                }
                50% { 
                    box-shadow: 0 0 0 8px rgba(255,107,53,0.1), 0 4px 16px rgba(0,0,0,0.08);
                }
            }

            /* Compact Header */
            .card-header-compact {
                display: flex; align-items: center; justify-content: space-between;
                padding: 1rem 1.25rem; cursor: pointer; gap: 1rem; transition: background 0.2s ease;
            }
            .card-header-compact:hover { background: rgba(255,107,53,0.03); }

            .header-left { display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0; }
            .applicant-avatar-sm {
                width: 44px; height: 44px; background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                color: white; font-weight: 800; font-size: 1.15rem; flex-shrink: 0;
            }
            .applicant-brief { flex: 1; min-width: 0; }
            .applicant-name-sm { color: var(--text-dark); font-weight: 700; font-size: 1rem; margin: 0 0 0.2rem 0; }
            .applicant-meta-sm {
                color: var(--text-muted); font-size: 0.82rem; margin: 0;
                display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;
            }
            .applicant-meta-sm i { color: var(--primary-color); font-size: 0.85rem; }
            .meta-divider { color: var(--border-color); margin: 0 0.25rem; }

            .header-right { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }
            .quick-skills { display: flex; gap: 0.35rem; }
            .skill-tag-mini {
                background: rgba(78,205,196,0.15); color: var(--secondary-color);
                padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;
            }

            .status-badge-sm {
                padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; white-space: nowrap;
            }
            .status-accepted { background: #D5F4E6; color: #0F6848; }
            .status-rejected { background: #FFE5E5; color: #C92A2A; }
            .status-pending { background: #FFF4E6; color: #D97706; }

            .btn-toggle {
                width: 32px; height: 32px; background: var(--background-light); border: 2px solid var(--border-color);
                border-radius: 8px; display: flex; align-items: center; justify-content: center;
                color: var(--text-muted); cursor: pointer; transition: all 0.3s ease;
            }
            .btn-toggle:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; }
            .applicant-card.expanded .btn-toggle i { transform: rotate(180deg); }
            .btn-toggle i { transition: transform 0.3s ease; }

            /* Expandable Details */
            .card-details { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; }
            .applicant-card.expanded .card-details { max-height: 1000px; }
            .details-inner { padding: 0 1.25rem 1.25rem; border-top: 2px solid var(--border-color); }

            /* Detail Boxes */
            .detail-box, .action-box {
                background: var(--background-light); border: 2px solid var(--border-color);
                border-radius: 10px; padding: 1rem;
            }
            .detail-box-title {
                color: var(--text-dark); font-weight: 700; font-size: 0.85rem; margin-bottom: 0.75rem;
                display: flex; align-items: center;
            }
            .detail-box-title i { color: var(--primary-color); }

            .detail-list { display: flex; flex-direction: column; gap: 0.6rem; }
            .detail-item {
                display: flex; align-items: flex-start; gap: 0.5rem;
                font-size: 0.85rem; color: var(--text-dark);
            }
            .detail-item i { color: var(--primary-color); font-size: 0.9rem; margin-top: 0.1rem; flex-shrink: 0; }

            .detail-text { color: var(--text-dark); font-size: 0.85rem; line-height: 1.6; margin: 0; }

            .skills-wrap { display: flex; flex-wrap: wrap; gap: 0.4rem; }
            .skill-tag {
                background: rgba(78,205,196,0.15); color: var(--secondary-color);
                padding: 0.3rem 0.65rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600;
            }

            /* Documents */
            .doc-btn {
                display: flex; align-items: center; gap: 0.6rem; padding: 0.65rem 0.85rem;
                background: white; border: 2px solid var(--border-color); border-radius: 8px;
                color: var(--text-dark); text-decoration: none; font-size: 0.85rem; font-weight: 600;
                transition: all 0.2s ease; width: 100%;
            }
            .doc-btn:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; }
            .doc-btn i:first-child { font-size: 1.1rem; color: var(--primary-color); }
            .doc-btn:hover i { color: white; }
            .doc-missing {
                display: flex; align-items: center; gap: 0.6rem; padding: 0.65rem 0.85rem;
                background: white; border: 2px dashed var(--border-color); border-radius: 8px;
                color: var(--text-muted); font-size: 0.82rem; font-style: italic;
            }

            /* Actions */
            .action-btn {
                width: 100%; padding: 0.75rem 1rem; border-radius: 8px;
                font-weight: 700; font-size: 0.85rem; border: none;
                display: flex; align-items: center; justify-content: center; gap: 0.5rem;
                cursor: pointer; transition: all 0.3s ease;
            }
            .action-accept { background: linear-gradient(135deg, #95E1D3, #7DD8C8); color: #0F6848; }
            .action-accept:hover { background: linear-gradient(135deg, #7DD8C8, #65CFC0); color: white; transform: translateY(-2px); }
            .action-reject { background: linear-gradient(135deg, #FFB3B3, #FF9999); color: #C92A2A; }
            .action-reject:hover { background: linear-gradient(135deg, #FF9999, #FF6B6B); color: white; transform: translateY(-2px); }

            .action-completed-box {
                background: white; border: 2px solid var(--border-color); padding: 1rem;
                border-radius: 8px; text-align: center; color: var(--text-dark); font-size: 0.85rem;
            }

            .info-summary { background: white; border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem; }
            .info-summary-item {
                display: flex; justify-content: space-between; padding: 0.35rem 0;
                border-bottom: 1px solid var(--border-color);
            }
            .info-summary-item:last-child { border-bottom: none; }
            .info-label { color: var(--text-muted); font-size: 0.8rem; font-weight: 600; }
            .info-value { color: var(--text-dark); font-size: 0.8rem; font-weight: 700; }

            /* Empty State */
            .empty-state {
                text-align: center; padding: 4rem 2rem; background: white;
                border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            .empty-icon { font-size: 4rem; color: var(--text-muted); opacity: 0.4; }
            .empty-text { color: var(--text-dark); font-size: 1.25rem; font-weight: 700; margin: 0; }
            .empty-subtext { color: var(--text-muted); font-size: 1rem; }

            /* Responsive */
            @media (max-width: 768px) {
                .quick-skills { display: none; }
                .applicant-meta-sm { font-size: 0.75rem; }
                .stat-badge { width: 100%; justify-content: center; }
            }
        </style>

        <script>
            function toggleCard(index) {
                const card = document.querySelector(`[data-index="${index}"]`);
                card.classList.toggle('expanded');
            }

            function filterApplicants(status) {
                document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
                event.target.classList.add('active');

                document.querySelectorAll('.applicant-card').forEach(card => {
                    card.classList.remove('expanded');
                    if (status === 'all' || card.dataset.status === status) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            // Auto-expand and scroll to highlighted card
            document.addEventListener('DOMContentLoaded', function() {
                const highlightCard = document.querySelector('.highlight-card');
                if (highlightCard) {
                    // Expand the card
                    highlightCard.classList.add('expanded');
                    
                    // Scroll to the card with smooth animation
                    setTimeout(() => {
                        highlightCard.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    }, 100);

                    // Remove highlight after 3 seconds
                    setTimeout(() => {
                        highlightCard.classList.remove('highlight-card');
                    }, 3000);
                }
            });
        </script>
    @endsection
@endcan