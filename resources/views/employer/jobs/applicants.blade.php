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
                                    @if ($job)
                                        <p class="job-title-header mb-0">{{ $job->job_title }}</p>
                                    @else
                                        <p class="job-title-header mb-0">
                                            All Applications
                                            @if ($status)
                                                — <span
                                                    style="text-transform: capitalize;">{{ str_replace('_', ' ', $status) }}</span>
                                            @endif
                                        </p>
                                    @endif
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

                @if (session('error'))
                    <div class="alert alert-custom alert-danger">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Filter Tabs --}}
                <div class="filter-section mb-4">
                    <div class="filter-tabs">
                        <button class="filter-tab" onclick="filterApplicants('all', this)">
                            <i class="bi bi-grid me-1"></i>
                            All <span class="tab-count">({{ $applications->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('pending', this)">
                            <i class="bi bi-clock me-1"></i>
                            Pending <span
                                class="tab-count">({{ $applications->where('application_status', 'pending')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('reviewed', this)">
                            <i class="bi bi-eye me-1"></i>
                            Reviewed <span
                                class="tab-count">({{ $applications->where('application_status', 'reviewed')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('shortlisted', this)">
                            <i class="bi bi-star me-1"></i>
                            Shortlisted <span
                                class="tab-count">({{ $applications->where('application_status', 'shortlisted')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('interview_scheduled', this)">
                            <i class="bi bi-calendar-check me-1"></i>
                            Interview <span
                                class="tab-count">({{ $applications->where('application_status', 'interview_scheduled')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('interviewed', this)">
                            <i class="bi bi-chat-dots me-1"></i>
                            Interviewed <span
                                class="tab-count">({{ $applications->where('application_status', 'interviewed')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('accepted', this)">
                            <i class="bi bi-check-circle me-1"></i>
                            Accepted <span
                                class="tab-count">({{ $applications->where('application_status', 'accepted')->count() }})</span>
                        </button>
                        <button class="filter-tab" onclick="filterApplicants('rejected', this)">
                            <i class="bi bi-x-circle me-1"></i>
                            Rejected <span
                                class="tab-count">({{ $applications->where('application_status', 'rejected')->count() }})</span>
                        </button>
                    </div>
                </div>

                {{-- Applicants List --}}
                <div class="applicants-container">
                    @forelse($applications as $index => $app)
                        <div class="applicant-card {{ request('highlight') == $app->id ? 'highlight-card' : '' }}"
                            data-status="{{ $app->application_status }}" data-index="{{ $index }}"
                            data-app-id="{{ $app->id }}" id="applicant-{{ $app->id }}">

                            {{-- COMPACT HEADER (Always Visible) --}}
                            <div class="card-header-compact" onclick="toggleCard({{ $index }})">
                                <div class="header-left">
                                    <div class="applicant-avatar-sm">
                                        {{ strtoupper(substr($app->jobSeeker->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div class="applicant-brief">
                                        <h6 class="applicant-name-sm">{{ $app->jobSeeker->user->name ?? 'N/A' }}</h6>
                                        <p class="applicant-meta-sm">
                                            <i class="bi bi-envelope"></i>
                                            {{ Str::limit($app->jobSeeker->user->email ?? 'N/A', 30) }}
                                            <span class="meta-divider">•</span>
                                            <i class="bi bi-calendar3"></i>
                                            {{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') : $app->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="header-right">
                                    <span
                                        class="status-badge-sm 
                                        @if ($app->application_status === 'accepted') status-accepted
                                        @elseif($app->application_status === 'rejected') status-rejected
                                        @elseif($app->application_status === 'reviewed') status-reviewed
                                        @elseif($app->application_status === 'shortlisted') status-shortlisted
                                        @elseif($app->application_status === 'interview_scheduled') status-interview
                                        @elseif($app->application_status === 'interviewed') status-interviewed
                                        @else status-pending @endif">
                                        {{ ucfirst(str_replace('_', ' ', $app->application_status)) }}
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
                                                    @if ($app->jobSeeker->phone)
                                                        <div class="detail-item">
                                                            <i class="bi bi-telephone-fill"></i>
                                                            <span>{{ $app->jobSeeker->phone }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($app->jobSeeker->address)
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
                                            @if ($app->jobSeeker->profile_summary)
                                                <div class="detail-box mt-3">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-file-text me-2"></i>Summary
                                                    </h6>
                                                    <p class="detail-text summary-clamp" id="summary-{{ $app->id }}">
                                                        {{ $app->jobSeeker->profile_summary }}
                                                    </p>
                                                    <button class="btn-summary-toggle" id="toggleBtn-{{ $app->id }}"
                                                        onclick="toggleAppSummary({{ $app->id }})" style="display:none;">
                                                        <i class="bi bi-chevron-down"
                                                            id="toggleIcon-{{ $app->id }}"></i>
                                                        <span id="toggleLabel-{{ $app->id }}">Show more</span>
                                                    </button>
                                                </div>
                                            @endif

                                            {{-- Interview Information (if scheduled) --}}
                                            @if ($app->application_status === 'interview_scheduled' && $app->interview)
                                                <div class="detail-box mt-3 interview-info-box">
                                                    <h6 class="detail-box-title">
                                                        <i class="bi bi-calendar-event me-2"></i>Interview Details
                                                    </h6>
                                                    <div class="interview-details">
                                                        <div class="interview-detail-item">
                                                            <i class="bi bi-clock-fill"></i>
                                                            <div>
                                                                <span class="interview-label">Scheduled</span>
                                                                <span
                                                                    class="interview-value">{{ \Carbon\Carbon::parse($app->interview->scheduled_at)->format('M d, Y \a\t h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="interview-detail-item">
                                                            <i
                                                                class="bi bi-{{ $app->interview->interview_type === 'online' ? 'camera-video-fill' : 'geo-alt-fill' }}"></i>
                                                            <div>
                                                                <span
                                                                    class="interview-label">{{ ucfirst($app->interview->interview_type) }}</span>
                                                                @if ($app->interview->interview_type === 'online')
                                                                    <a href="{{ $app->interview->meeting_link }}"
                                                                        target="_blank" class="interview-link">
                                                                        {{ Str::limit($app->interview->meeting_link, 35) }}
                                                                    </a>
                                                                @else
                                                                    <span
                                                                        class="interview-value">{{ $app->interview->location }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if ($app->interview->notes)
                                                            <div class="interview-detail-item">
                                                                <i class="bi bi-journal-text"></i>
                                                                <div>
                                                                    <span class="interview-label">Notes</span>
                                                                    <span
                                                                        class="interview-value">{{ $app->interview->notes }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- COLUMN 2: Documents --}}
                                        <div class="col-lg-4">
                                            <div class="detail-box">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-paperclip me-2"></i>Submitted Documents
                                                </h6>

                                                {{-- Resume from job_seekers table --}}
                                                @if ($app->jobSeeker->resume)
                                                    <a href="{{ asset('storage/' . $app->jobSeeker->resume) }}"
                                                        target="_blank" download class="doc-btn doc-btn-primary mb-2">
                                                        <i class="bi bi-file-earmark-person"></i>
                                                        <span>Resume (Profile)</span>
                                                        <i class="bi bi-download ms-auto"></i>
                                                    </a>
                                                @else
                                                    <div class="doc-missing mb-2">
                                                        <i class="bi bi-file-x"></i>
                                                        <span>No resume</span>
                                                    </div>
                                                @endif

                                                {{-- Application Letter from application_files --}}
                                                @php
                                                    $letter = $app->files
                                                        ->where('file_type', 'application_letter')
                                                        ->first();
                                                @endphp
                                                @if ($letter)
                                                    <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank"
                                                        download="{{ $letter->original_name }}"
                                                        class="doc-btn doc-btn-secondary mb-2">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        <span>{{ $letter->original_name }}</span>
                                                        <i class="bi bi-download ms-auto"></i>
                                                    </a>
                                                @endif

                                                {{-- Template Files from application_files --}}
                                                @php
                                                    $templateFiles = $app->files->where('file_type', 'other');
                                                @endphp
                                                @if ($templateFiles->count() > 0)
                                                    <div class="template-files-divider">
                                                        <span>Template Files</span>
                                                    </div>
                                                    @foreach ($templateFiles as $templateFile)
                                                        <a href="{{ asset('storage/' . $templateFile->file_path) }}"
                                                            target="_blank" download="{{ $templateFile->original_name }}"
                                                            class="doc-btn doc-btn-template mb-2">
                                                            <i class="bi bi-file-earmark-check"></i>
                                                            <span>{{ Str::limit($templateFile->original_name, 25) }}</span>
                                                            <i class="bi bi-download ms-auto"></i>
                                                        </a>
                                                    @endforeach
                                                @endif

                                                {{-- Show message if no files at all --}}
                                                @if (!$app->jobSeeker->resume && !$letter && $templateFiles->count() === 0)
                                                    <div class="doc-missing">
                                                        <i class="bi bi-file-x"></i>
                                                        <span>No documents submitted</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Application Info --}}
                                            <div class="detail-box mt-3">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-info-circle me-2"></i>Application Info
                                                </h6>
                                                <div class="info-grid">
                                                    <div class="info-item-grid">
                                                        <span class="info-label-grid">Applied:</span>
                                                        <span
                                                            class="info-value-grid">{{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->format('M d, Y h:i A') : 'N/A' }}</span>
                                                    </div>
                                                    @if ($app->status_updated_at)
                                                        <div class="info-item-grid">
                                                            <span class="info-label-grid">Last Updated:</span>
                                                            <span
                                                                class="info-value-grid">{{ \Carbon\Carbon::parse($app->status_updated_at)->format('M d, Y h:i A') }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($app->reapply_count > 0)
                                                        <div class="info-item-grid">
                                                            <span class="info-label-grid">Reapply Count:</span>
                                                            <span class="info-value-grid">{{ $app->reapply_count }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- COLUMN 3: Actions --}}
                                        <div class="col-lg-4">
                                            <div class="action-box">
                                                <h6 class="detail-box-title">
                                                    <i class="bi bi-gear me-2"></i>Quick Actions
                                                </h6>

                                                @if (in_array($app->application_status, ['pending', 'reviewed', 'shortlisted', 'interviewed']))
                                                    {{-- Mark as Reviewed (from pending) --}}
                                                    @if ($app->application_status === 'pending')
                                                        <form
                                                            action="{{ route('employer.applications.updateStatus', $app->id) }}"
                                                            method="POST" class="mb-2">
                                                            @csrf
                                                            <input type="hidden" name="status" value="reviewed">
                                                            <button type="submit" class="action-btn action-reviewed">
                                                                <i class="bi bi-eye-fill"></i>
                                                                <span>Mark as Reviewed</span>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Shortlist (from reviewed) --}}
                                                    @if ($app->application_status === 'reviewed')
                                                        <form
                                                            action="{{ route('employer.applications.updateStatus', $app->id) }}"
                                                            method="POST" class="mb-2">
                                                            @csrf
                                                            <input type="hidden" name="status" value="shortlisted">
                                                            <button type="submit" class="action-btn action-shortlist">
                                                                <i class="bi bi-star-fill"></i>
                                                                <span>Shortlist Applicant</span>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Schedule Interview (from shortlisted only) --}}
                                                    @if ($app->application_status === 'shortlisted')
                                                        <button type="button" class="action-btn action-interview mb-2"
                                                            onclick="showInterviewModal({{ $app->id }})">
                                                            <i class="bi bi-calendar-plus"></i>
                                                            <span>Schedule Interview</span>
                                                        </button>
                                                    @endif

                                                    {{-- Accept (from reviewed, shortlisted, or interviewed) --}}
                                                    @if (in_array($app->application_status, ['reviewed', 'shortlisted', 'interviewed']))
                                                        <form
                                                            action="{{ route('employer.applications.updateStatus', $app->id) }}"
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
                                                    @endif

                                                    {{-- Reject with Reason --}}
                                                    <button type="button" class="action-btn action-reject"
                                                        onclick="showRejectModal({{ $app->id }})">
                                                        <i class="bi bi-x-circle"></i>
                                                        <span>Reject Applicant</span>
                                                    </button>
                                                @elseif($app->application_status === 'interview_scheduled')
                                                    {{-- Interview Scheduled Actions --}}
                                                    <div class="interview-actions-box">
                                                        <p class="interview-status-text">
                                                            <i class="bi bi-calendar-check-fill"></i>
                                                            Interview Scheduled
                                                        </p>

                                                        <form
                                                            action="{{ route('employer.interviews.updateStatus', $app->interview->id) }}"
                                                            method="POST" class="mb-2">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit"
                                                                class="action-btn action-interview-complete">
                                                                <i class="bi bi-check2-circle"></i>
                                                                <span>Mark as Completed</span>
                                                            </button>
                                                        </form>

                                                        <form
                                                            action="{{ route('employer.interviews.cancel', $app->interview->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Cancel this interview?')">
                                                            @csrf
                                                            <button type="submit" class="action-btn action-interview-cancel">
                                                                <i class="bi bi-x-circle"></i>
                                                                <span>Cancel Interview</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    {{-- Final Status (accepted/rejected) --}}
                                                    <div class="action-completed-box">
                                                        <i class="bi bi-check2-all"></i>
                                                        <p class="mb-0">
                                                            <strong>Decision Made:</strong><br>
                                                            {{ ucfirst($app->application_status) }}
                                                        </p>
                                                        @if ($app->rejection_reason && $app->application_status === 'rejected')
                                                            <div class="rejection-reason-display">
                                                                <i class="bi bi-chat-left-text me-1"></i>
                                                                <small>{{ $app->rejection_reason }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Status Summary --}}
                                                <div class="info-summary mt-3">
                                                    <div class="info-summary-item">
                                                        <span class="info-label">Applied</span>
                                                        <span
                                                            class="info-value">{{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->diffForHumans() : 'N/A' }}</span>
                                                    </div>
                                                    <div class="info-summary-item">
                                                        <span class="info-label">Status</span>
                                                        <span
                                                            class="info-value">{{ ucfirst(str_replace('_', ' ', $app->application_status)) }}</span>
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
                            <p class="empty-text">No applicants found</p>
                            <p class="empty-subtext">
                                {{ $status ? 'No ' . str_replace('_', ' ', $status) . ' applications found.' : 'Applications will appear here once job seekers apply.' }}
                            </p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Interview Scheduling Modal --}}
        <div class="modal fade" id="interviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content interview-modal-content">
                    <div class="modal-header interview-modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-plus-fill me-2"></i>Schedule Interview
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="interviewForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            {{-- Date and Time --}}
                            <div class="mb-3">
                                <label class="form-label-modal">
                                    <i class="bi bi-clock me-1"></i>Interview Date & Time
                                </label>
                                <input type="datetime-local" name="scheduled_at" class="form-control-modal" required
                                    min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}">
                                <small class="form-text-muted">Must be at least 1 hour from now</small>
                            </div>

                            {{-- Interview Type --}}
                            <div class="mb-3">
                                <label class="form-label-modal">
                                    <i class="bi bi-gear me-1"></i>Interview Type
                                </label>
                                <select name="interview_type" id="interviewType" class="form-control-modal" required
                                    onchange="toggleInterviewFields()">
                                    <option value="">Select type...</option>
                                    <option value="online">Online Meeting</option>
                                    <option value="onsite">Onsite</option>
                                </select>
                            </div>

                            {{-- Online Meeting Link --}}
                            <div class="mb-3" id="meetingLinkField" style="display: none;">
                                <label class="form-label-modal">
                                    <i class="bi bi-link-45deg me-1"></i>Meeting Link
                                </label>
                                <input type="url" name="meeting_link" class="form-control-modal"
                                    placeholder="https://zoom.us/j/123456789">
                                <small class="form-text-muted">Zoom, Google Meet, Teams, etc.</small>
                            </div>

                            {{-- Onsite Location --}}
                            <div class="mb-3" id="locationField" style="display: none;">
                                <label class="form-label-modal">
                                    <i class="bi bi-geo-alt me-1"></i>Location
                                </label>
                                <input type="text" name="location" class="form-control-modal"
                                    placeholder="Office address or meeting room">
                            </div>

                            {{-- Notes --}}
                            <div class="mb-3">
                                <label class="form-label-modal">
                                    <i class="bi bi-journal-text me-1"></i>Additional Notes (Optional)
                                </label>
                                <textarea name="notes" class="form-control-modal" rows="3" maxlength="1000"
                                    placeholder="Any additional information for the candidate..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer interview-modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-modal-interview">
                                <i class="bi bi-calendar-check me-1"></i>Schedule Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reject Modal --}}
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content reject-modal-content">
                    <div class="modal-header reject-modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-x-circle-fill me-2"></i>Reject Application
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="status" value="rejected">
                            <p class="reject-modal-text">
                                Provide a brief reason for rejecting this application (optional but recommended):
                            </p>
                            <textarea name="rejection_reason" class="form-control-reject" rows="4" maxlength="500"
                                placeholder="e.g., Qualifications don't match our requirements, Position already filled, etc."></textarea>
                            <small class="char-count">Max 500 characters</small>
                        </div>
                        <div class="modal-footer reject-modal-footer">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-modal-reject">
                                <i class="bi bi-x-circle me-1"></i>Reject Application
                            </button>
                        </div>
                    </form>
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
                background: var(--background-light);
            }

            .applicants-wrapper {
                min-height: 100vh;
            }

            /* Back & Header */
            .btn-back {
                background: white;
                color: var(--text-dark);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.5rem 1.25rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .btn-back:hover {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
                transform: translateX(-4px);
            }

            .page-header {
                background: linear-gradient(135deg, #FFF 0%, #F7F9FC 100%);
                padding: 1.5rem;
                border-radius: 16px;
                border: 2px solid var(--border-color);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .header-icon {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
                color: var(--primary-color);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
            }

            .page-header h3 {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1.5rem;
                margin: 0;
            }

            .job-title-header {
                color: var(--text-muted);
                font-size: 1rem;
                font-weight: 500;
            }

            .stat-badge {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                padding: 0.6rem 1.25rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            /* Alert */
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

            /* Filter Tabs */
            .filter-section {
                margin-top: 1.5rem;
            }

            .filter-tabs {
                display: flex;
                gap: 0.6rem;
                flex-wrap: wrap;
            }

            .filter-tab {
                background: white;
                border: 2px solid var(--border-color);
                color: var(--text-muted);
                padding: 0.65rem 1.15rem;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.25s ease;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .filter-tab:hover {
                border-color: var(--primary-color);
                color: var(--primary-color);
                background: rgba(255, 107, 53, 0.06);
            }

            .filter-tab.active {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border-color: transparent;
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }

            .tab-count {
                font-size: 0.85rem;
                opacity: 0.9;
            }

            /* Compact Card */
            .applicant-card {
                background: white;
                border-radius: 12px;
                border: 2px solid var(--border-color);
                margin-bottom: 0.75rem;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .applicant-card:hover {
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
                border-color: var(--primary-color);
            }

            .applicant-card.hidden {
                display: none;
            }

            /* Highlight Animation */
            .highlight-card {
                animation: highlightPulse 2s ease-in-out;
                border-color: var(--primary-color) !important;
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2), 0 4px 16px rgba(0, 0, 0, 0.08) !important;
            }

            @keyframes highlightPulse {

                0%,
                100% {
                    box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.4), 0 4px 16px rgba(0, 0, 0, 0.08);
                }

                50% {
                    box-shadow: 0 0 0 8px rgba(255, 107, 53, 0.1), 0 4px 16px rgba(0, 0, 0, 0.08);
                }
            }

            /* Compact Header */
            .card-header-compact {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.25rem;
                cursor: pointer;
                gap: 1rem;
                transition: background 0.2s ease;
            }

            .card-header-compact:hover {
                background: rgba(255, 107, 53, 0.03);
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex: 1;
                min-width: 0;
            }

            .applicant-avatar-sm {
                width: 44px;
                height: 44px;
                background: linear-gradient(135deg, var(--secondary-color), #3DBDB4);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 800;
                font-size: 1.15rem;
                flex-shrink: 0;
            }

            .applicant-brief {
                flex: 1;
                min-width: 0;
            }

            .applicant-name-sm {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 1rem;
                margin: 0 0 0.2rem 0;
            }

            .applicant-meta-sm {
                color: var(--text-muted);
                font-size: 0.82rem;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.35rem;
                flex-wrap: wrap;
            }

            .applicant-meta-sm i {
                color: var(--primary-color);
                font-size: 0.85rem;
            }

            .meta-divider {
                color: var(--border-color);
                margin: 0 0.25rem;
            }

            .header-right {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-shrink: 0;
            }

            .status-badge-sm {
                padding: 0.4rem 0.8rem;
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .status-accepted {
                background: #D5F4E6;
                color: #0F6848;
            }

            .status-rejected {
                background: #FFE5E5;
                color: #C92A2A;
            }

            .status-reviewed {
                background: #E0E7FF;
                color: #4338CA;
            }

            .status-pending {
                background: #FFF4E6;
                color: #D97706;
            }

            .status-shortlisted {
                background: #FEF3C7;
                color: #92400E;
            }

            .status-interview {
                background: #DBEAFE;
                color: #1E40AF;
            }

            .status-interviewed {
                background: #E9D5FF;
                color: #6B21A8;
            }

            .btn-toggle {
                width: 32px;
                height: 32px;
                background: var(--background-light);
                border: 2px solid var(--border-color);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-muted);
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-toggle:hover {
                background: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            .applicant-card.expanded .btn-toggle i {
                transform: rotate(180deg);
            }

            .btn-toggle i {
                transition: transform 0.3s ease;
            }

            /* Expandable Details */
            .card-details {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease;
            }

            .applicant-card.expanded .card-details {
                max-height: 3000px;
            }

            .details-inner {
                padding: 0 1.25rem 1.25rem;
                border-top: 2px solid var(--border-color);
            }

            /* Detail Boxes */
            .detail-box,
            .action-box {
                background: var(--background-light);
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 1rem;
            }

            .detail-box-title {
                color: var(--text-dark);
                font-weight: 700;
                font-size: 0.85rem;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
            }

            .detail-box-title i {
                color: var(--primary-color);
            }

            .detail-list {
                display: flex;
                flex-direction: column;
                gap: 0.6rem;
            }

            .detail-item {
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
                font-size: 0.85rem;
                color: var(--text-dark);
            }

            .detail-item i {
                color: var(--primary-color);
                font-size: 0.9rem;
                margin-top: 0.1rem;
                flex-shrink: 0;
            }

            /* Summary Clamp */
            .detail-text.summary-clamp {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
                transition: all 0.3s ease;
                word-break: break-word;
                overflow-wrap: break-word;
                margin-bottom: 0.35rem;
            }

            .detail-text.summary-clamp.expanded {
                display: block;
                -webkit-line-clamp: unset;
            }

            .btn-summary-toggle {
                background: none;
                border: none;
                padding: 0;
                color: var(--primary-color);
                font-size: 0.78rem;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
                transition: color 0.2s ease;
                font-family: 'Outfit', sans-serif;
            }

            .btn-summary-toggle:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            /* Interview Info Box */
            .interview-info-box {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border-color: #93C5FD;
            }

            .interview-details {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .interview-detail-item {
                display: flex;
                align-items: flex-start;
                gap: 0.6rem;
                padding: 0.5rem;
                background: white;
                border-radius: 6px;
            }

            .interview-detail-item i {
                color: #1E40AF;
                font-size: 1rem;
                margin-top: 0.2rem;
            }

            .interview-detail-item>div {
                flex: 1;
            }

            .interview-label {
                display: block;
                font-size: 0.7rem;
                color: var(--text-muted);
                font-weight: 600;
                margin-bottom: 0.2rem;
            }

            .interview-value {
                display: block;
                font-size: 0.85rem;
                color: var(--text-dark);
                font-weight: 500;
            }

            .interview-link {
                display: block;
                font-size: 0.85rem;
                color: #1E40AF;
                text-decoration: none;
                word-break: break-all;
            }

            .interview-link:hover {
                text-decoration: underline;
            }

            /* Info Grid */
            .info-grid {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-item-grid {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 0.4rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .info-item-grid:last-child {
                border-bottom: none;
            }

            .info-label-grid {
                color: var(--text-muted);
                font-size: 0.75rem;
                font-weight: 600;
            }

            .info-value-grid {
                color: var(--text-dark);
                font-size: 0.75rem;
                font-weight: 700;
                text-align: right;
            }

            /* Documents */
            .template-files-divider {
                margin: 0.75rem 0 0.5rem;
                padding: 0.35rem 0;
                border-top: 2px dashed var(--border-color);
                border-bottom: 2px dashed var(--border-color);
                text-align: center;
            }

            .template-files-divider span {
                font-size: 0.75rem;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .doc-btn {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.65rem 0.85rem;
                background: white;
                border: 2px solid var(--border-color);
                border-radius: 8px;
                color: var(--text-dark);
                text-decoration: none;
                font-size: 0.85rem;
                font-weight: 600;
                transition: all 0.2s ease;
                width: 100%;
            }

            .doc-btn:hover {
                background: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            .doc-btn i:first-child {
                font-size: 1.1rem;
                color: var(--primary-color);
            }

            .doc-btn:hover i {
                color: white;
            }

            .doc-btn-template {
                border-color: rgba(78, 205, 196, 0.3);
            }

            .doc-btn-template i:first-child {
                color: var(--secondary-color);
            }

            .doc-btn-template:hover {
                background: var(--secondary-color);
                border-color: var(--secondary-color);
            }

            .doc-missing {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.65rem 0.85rem;
                background: white;
                border: 2px dashed var(--border-color);
                border-radius: 8px;
                color: var(--text-muted);
                font-size: 0.82rem;
                font-style: italic;
            }

            /* Actions */
            .action-btn {
                width: 100%;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.85rem;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .action-reviewed {
                background: linear-gradient(135deg, #E0E7FF, #C7D2FE);
                color: #4338CA;
            }

            .action-reviewed:hover {
                background: linear-gradient(135deg, #C7D2FE, #A5B4FC);
                color: white;
                transform: translateY(-2px);
            }

            .action-shortlist {
                background: linear-gradient(135deg, #FEF3C7, #FDE68A);
                color: #92400E;
            }

            .action-shortlist:hover {
                background: linear-gradient(135deg, #FDE68A, #FCD34D);
                color: white;
                transform: translateY(-2px);
            }

            .action-interview {
                background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
                color: #1E40AF;
            }

            .action-interview:hover {
                background: linear-gradient(135deg, #BFDBFE, #93C5FD);
                color: white;
                transform: translateY(-2px);
            }

            .action-accept {
                background: linear-gradient(135deg, #95E1D3, #7DD8C8);
                color: #0F6848;
            }

            .action-accept:hover {
                background: linear-gradient(135deg, #7DD8C8, #65CFC0);
                color: white;
                transform: translateY(-2px);
            }

            .action-reject {
                background: linear-gradient(135deg, #FFB3B3, #FF9999);
                color: #C92A2A;
            }

            .action-reject:hover {
                background: linear-gradient(135deg, #FF9999, #FF6B6B);
                color: white;
                transform: translateY(-2px);
            }

            .interview-actions-box {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border: 2px solid #93C5FD;
                padding: 1rem;
                border-radius: 8px;
            }

            .interview-status-text {
                color: #1E40AF;
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .action-interview-complete {
                background: linear-gradient(135deg, #95E1D3, #7DD8C8);
                color: #0F6848;
            }

            .action-interview-complete:hover {
                background: linear-gradient(135deg, #7DD8C8, #65CFC0);
                color: white;
                transform: translateY(-2px);
            }

            .action-interview-cancel {
                background: linear-gradient(135deg, #FED7AA, #FDBA74);
                color: #9A3412;
            }

            .action-interview-cancel:hover {
                background: linear-gradient(135deg, #FDBA74, #FB923C);
                color: white;
                transform: translateY(-2px);
            }

            .action-completed-box {
                background: white;
                border: 2px solid var(--border-color);
                padding: 1rem;
                border-radius: 8px;
                text-align: center;
                color: var(--text-dark);
                font-size: 0.85rem;
            }

            .rejection-reason-display {
                margin-top: 0.75rem;
                padding: 0.75rem;
                background: var(--background-light);
                border-left: 3px solid #C92A2A;
                border-radius: 6px;
                text-align: left;
            }

            .rejection-reason-display small {
                color: var(--text-muted);
                font-size: 0.8rem;
                line-height: 1.5;
            }

            .info-summary {
                background: white;
                border: 2px solid var(--border-color);
                border-radius: 8px;
                padding: 0.75rem;
            }

            .info-summary-item {
                display: flex;
                justify-content: space-between;
                padding: 0.35rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .info-summary-item:last-child {
                border-bottom: none;
            }

            .info-label {
                color: var(--text-muted);
                font-size: 0.8rem;
                font-weight: 600;
            }

            .info-value {
                color: var(--text-dark);
                font-size: 0.8rem;
                font-weight: 700;
            }

            /* Interview Modal */
            .interview-modal-content {
                border-radius: 16px;
                border: none;
            }

            .interview-modal-header {
                background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
                border-bottom: 2px solid #93C5FD;
                padding: 1.25rem 1.5rem;
                border-radius: 16px 16px 0 0;
            }

            .interview-modal-header .modal-title {
                color: #1E40AF;
                font-weight: 700;
                font-size: 1.1rem;
            }

            .form-label-modal {
                color: var(--text-dark);
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
                display: block;
            }

            .form-control-modal {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid var(--border-color);
                border-radius: 10px;
                font-family: 'Outfit', sans-serif;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .form-control-modal:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .form-text-muted {
                display: block;
                color: var(--text-muted);
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }

            .interview-modal-footer {
                background: var(--background-light);
                border-top: 2px solid var(--border-color);
                padding: 1rem 1.5rem;
                border-radius: 0 0 16px 16px;
            }

            .btn-modal-interview {
                background: linear-gradient(135deg, #3B82F6, #1E40AF);
                border: none;
                color: white;
                padding: 0.6rem 1.5rem;
                border-radius: 8px;
                font-weight: 700;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .btn-modal-interview:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            }

            /* Reject Modal */
            .reject-modal-content {
                border-radius: 16px;
                border: none;
            }

            .reject-modal-header {
                background: linear-gradient(135deg, #FFE5E5, #FFD0D0);
                border-bottom: 2px solid #FFB3B3;
                padding: 1.25rem 1.5rem;
                border-radius: 16px 16px 0 0;
            }

            .reject-modal-header .modal-title {
                color: #C92A2A;
                font-weight: 700;
                font-size: 1.1rem;
            }

            .reject-modal-text {
                color: var(--text-dark);
                font-size: 0.95rem;
                margin-bottom: 1rem;
            }

            .form-control-reject {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid var(--border-color);
                border-radius: 10px;
                font-family: 'Outfit', sans-serif;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .form-control-reject:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            }

            .char-count {
                display: block;
                color: var(--text-muted);
                font-size: 0.75rem;
                margin-top: 0.5rem;
            }

            .reject-modal-footer {
                background: var(--background-light);
                border-top: 2px solid var(--border-color);
                padding: 1rem 1.5rem;
                border-radius: 0 0 16px 16px;
            }

            .btn-modal-cancel {
                background: white;
                border: 2px solid var(--border-color);
                color: var(--text-dark);
                padding: 0.6rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-modal-cancel:hover {
                background: var(--text-muted);
                border-color: var(--text-muted);
                color: white;
            }

            .btn-modal-reject {
                background: linear-gradient(135deg, #FF6B6B, #C92A2A);
                border: none;
                color: white;
                padding: 0.6rem 1.5rem;
                border-radius: 8px;
                font-weight: 700;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(201, 42, 42, 0.3);
            }

            .btn-modal-reject:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(201, 42, 42, 0.4);
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
                background: white;
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .empty-icon {
                font-size: 4rem;
                color: var(--text-muted);
                opacity: 0.4;
            }

            .empty-text {
                color: var(--text-dark);
                font-size: 1.25rem;
                font-weight: 700;
                margin: 0;
            }

            .empty-subtext {
                color: var(--text-muted);
                font-size: 1rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .applicant-meta-sm {
                    font-size: 0.75rem;
                }

                .stat-badge {
                    width: 100%;
                    justify-content: center;
                }

                .filter-tabs {
                    font-size: 0.8rem;
                }

                .filter-tab {
                    padding: 0.5rem 0.85rem;
                }
            }
        </style>

        <script>
            /* ── Toggle individual applicant card ── */
            function toggleCard(index) {
                const card = document.querySelector(`[data-index="${index}"]`);
                card.classList.toggle('expanded');
            }

            /* ── Filter tabs by status ── */
            function filterApplicants(status, clickedBtn) {
                // Remove active from all tabs
                document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));

                // Set active on clicked button
                if (clickedBtn) {
                    clickedBtn.classList.add('active');
                }

                // Show/hide cards
                document.querySelectorAll('.applicant-card').forEach(card => {
                    card.classList.remove('expanded');
                    if (status === 'all' || card.dataset.status === status) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            /* ── Interview Modal ── */
            function showInterviewModal(appId) {
                const modal = new bootstrap.Modal(document.getElementById('interviewModal'));
                const form = document.getElementById('interviewForm');
                form.action = `/employer/applications/${appId}/schedule-interview`;
                modal.show();
            }

            /* ── Reject Modal ── */
            function showRejectModal(appId) {
                const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
                const form = document.getElementById('rejectForm');
                form.action = `/employer/applications/${appId}/update-status`;
                modal.show();
            }

            /* ── Interview type toggle ── */
            function toggleInterviewFields() {
                const interviewType = document.getElementById('interviewType').value;
                const meetingLinkField = document.getElementById('meetingLinkField');
                const locationField = document.getElementById('locationField');
                const meetingLinkInput = document.querySelector('input[name="meeting_link"]');
                const locationInput = document.querySelector('input[name="location"]');

                if (interviewType === 'online') {
                    meetingLinkField.style.display = 'block';
                    locationField.style.display = 'none';
                    meetingLinkInput.required = true;
                    locationInput.required = false;
                    locationInput.value = '';
                } else if (interviewType === 'onsite') {
                    meetingLinkField.style.display = 'none';
                    locationField.style.display = 'block';
                    meetingLinkInput.required = false;
                    locationInput.required = true;
                    meetingLinkInput.value = '';
                } else {
                    meetingLinkField.style.display = 'none';
                    locationField.style.display = 'none';
                    meetingLinkInput.required = false;
                    locationInput.required = false;
                }
            }

            /* ── On page load ── */
            document.addEventListener('DOMContentLoaded', function() {

                // 1. Auto-expand and scroll to highlighted card
                const highlightCard = document.querySelector('.highlight-card');
                if (highlightCard) {
                    highlightCard.classList.add('expanded');
                    setTimeout(() => {
                        highlightCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                    setTimeout(() => {
                        highlightCard.classList.remove('highlight-card');
                    }, 3000);
                }

                // 2. ── Auto-activate the correct filter tab based on ?status= URL param ──
                //    Laravel injects the current status value here:
                const urlStatus = "{{ request('status') }}";
                const allTabs = document.querySelectorAll('.filter-tab');

                // Clear any existing active states first
                allTabs.forEach(t => t.classList.remove('active'));

                if (urlStatus) {
                    // Find the tab whose onclick contains the matching status string
                    const matchingBtn = [...allTabs].find(btn => {
                        const onclickAttr = btn.getAttribute('onclick') || '';
                        return onclickAttr.includes(`'${urlStatus}'`);
                    });

                    if (matchingBtn) {
                        // Activate matching tab and filter cards
                        filterApplicants(urlStatus, matchingBtn);
                    } else {
                        // Fallback: show all, activate "All" tab
                        if (allTabs[0]) allTabs[0].classList.add('active');
                    }
                } else {
                    // No status param → default to "All" tab active, show all cards
                    if (allTabs[0]) allTabs[0].classList.add('active');
                }

                // 3. Show/hide summary toggle buttons based on overflow
                document.querySelectorAll('.summary-clamp').forEach(function(el) {
                    const btn = document.getElementById('toggleBtn-' + el.id.split('-')[1]);
                    if (btn && el.scrollHeight > el.clientHeight + 2) {
                        btn.style.display = 'inline-flex';
                    }
                });
            });

            /* ── Toggle profile summary expand/collapse ── */
            function toggleAppSummary(appId) {
                const el = document.getElementById('summary-' + appId);
                const icon = document.getElementById('toggleIcon-' + appId);
                const label = document.getElementById('toggleLabel-' + appId);

                el.classList.toggle('expanded');
                const isExpanded = el.classList.contains('expanded');
                icon.className = isExpanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
                label.textContent = isExpanded ? 'Show less' : 'Show more';
            }
        </script>
    @endsection
@endcan