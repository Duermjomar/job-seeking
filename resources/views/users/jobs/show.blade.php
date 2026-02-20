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

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert-custom alert-warning-custom mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-2" style="list-style: none; padding-left: 0;">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-dot"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
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
                                {!! nl2br(e($job->job_description)) !!}
                            </div>

                            <hr class="divider">

                            {{-- Requirements --}}
                            <div class="section-title">
                                <i class="bi bi-list-check"></i>
                                Requirements
                            </div>
                            <div class="section-content">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>

                            {{-- Download Templates --}}
                            @php
                                $hasTemplates = $job->templates && $job->templates->count() > 0;
                            @endphp

                            @if ($hasTemplates)
                                <div class="template-download-section">
                                    <h6 class="mb-3" style="font-weight: 700; color: var(--secondary-color);">
                                        <i class="bi bi-download me-2"></i>Application Templates Available
                                    </h6>
                                    <p class="mb-3" style="font-size: 0.9rem; color: var(--text-muted);">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Download the template(s) below, fill them out, then upload the completed files in the application form.
                                    </p>
                                    <div class="template-list">
                                        @foreach ($job->templates as $template)
                                            <a href="{{ asset('storage/' . $template->file_path) }}"
                                                class="btn-download-template mb-2" 
                                                download="{{ $template->file_name }}">
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
                                    $user      = auth()->user();
                                    $jobSeeker = $user->jobSeeker;
                                    
                                    $existingApplication = null;
                                    if ($jobSeeker) {
                                        $existingApplication = $job->applications()
                                            ->where('job_seeker_id', $jobSeeker->id)
                                            ->first();
                                    }
                                    
                                    $canApply = false;
                                    $isReapply = false;
                                    $statusMessage = '';
                                    $daysSinceRejection = 0;
                                    $cooldownDays = 30;
                                    
                                    if (!$jobSeeker) {
                                        $statusMessage = 'profile_needed';
                                    } elseif (!$jobSeeker->resume) {
                                        $statusMessage = 'resume_needed';
                                    } elseif (!$existingApplication) {
                                        $canApply = true;
                                        $statusMessage = 'can_apply';
                                    } elseif ($existingApplication->application_status === 'rejected') {
                                        $daysSinceRejection = \Carbon\Carbon::parse($existingApplication->status_updated_at)->diffInDays(now());
                                        if ($daysSinceRejection >= $cooldownDays) {
                                            $canApply = true;
                                            $isReapply = true;
                                            $statusMessage = 'can_reapply';
                                        } else {
                                            $statusMessage = 'cooldown';
                                        }
                                    } else {
                                        $statusMessage = 'already_applied';
                                    }
                                @endphp

                                {{-- Profile Needed --}}
                                @if ($statusMessage === 'profile_needed')
                                    <div class="alert-custom alert-danger-custom">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                                        You need to complete your Job Seeker profile to apply.
                                    </div>

                                {{-- Resume Needed --}}
                                @elseif ($statusMessage === 'resume_needed')
                                    <div class="alert-custom alert-warning-custom">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Please upload your resume in your profile before applying to jobs.
                                        <a href="{{ route('users.profile.edit') }}" class="btn btn-sm btn-primary ms-2">
                                            Go to Profile
                                        </a>
                                    </div>

                                {{-- Already Applied (Active) --}}
                                @elseif ($statusMessage === 'already_applied')
                                    <div class="already-applied-box">
                                        <div class="already-applied-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div class="already-applied-content">
                                            <h6 class="already-applied-title">Already Applied to this Job</h6>
                                            <p class="already-applied-text">
                                                Your application is currently <strong>{{ ucfirst($existingApplication->application_status) }}</strong>. 
                                                <a href="{{ route('users.applications', ['highlight' => $job->id]) }}" class="track-link">Track Application →</a>
                                            </p>
                                        </div>
                                    </div>

                                {{-- Cooldown Period --}}
                                @elseif ($statusMessage === 'cooldown')
                                    <div class="cooldown-box">
                                        <div class="cooldown-icon">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div class="cooldown-content">
                                            <h6 class="cooldown-title">Re-apply Cooldown Period</h6>
                                            <p class="cooldown-text">
                                                Your previous application was rejected {{ $daysSinceRejection }} days ago. 
                                                You can re-apply after <strong>{{ $cooldownDays - $daysSinceRejection }} more days</strong>.
                                            </p>
                                            <div class="cooldown-progress">
                                                <div class="cooldown-progress-bar" style="width: {{ ($daysSinceRejection / $cooldownDays) * 100 }}%"></div>
                                            </div>
                                            <p class="cooldown-hint">
                                                <i class="bi bi-lightbulb me-1"></i>
                                                Use this time to improve your resume and qualifications!
                                            </p>
                                        </div>
                                    </div>

                                {{-- Can Apply / Can Re-apply --}}
                                @elseif ($canApply)
                                    <div class="application-section">
                                        {{-- Re-apply Notice --}}
                                        @if ($isReapply)
                                            <div class="reapply-notice mb-4">
                                                <div class="reapply-notice-icon">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </div>
                                                <div class="reapply-notice-content">
                                                    <h6 class="reapply-notice-title">Re-applying to this Position</h6>
                                                    <p class="reapply-notice-text">
                                                        You previously applied to this job and were rejected. This will be your 
                                                        <strong>Attempt #{{ $existingApplication->reapply_count + 2 }}</strong>.
                                                        @if($existingApplication->rejection_reason)
                                                            <br><span class="rejection-reminder">Previous rejection reason: "{{ $existingApplication->rejection_reason }}"</span>
                                                        @endif
                                                    </p>
                                                    <p class="reapply-notice-tip">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Make sure to upload updated/improved documents to increase your chances!
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="application-title">
                                            <i class="bi bi-{{ $isReapply ? 'arrow-repeat' : 'send-fill' }}"></i>
                                            {{ $isReapply ? 'Re-submit Your Application' : 'Submit Your Application' }}
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
                                            enctype="multipart/form-data" id="applicationForm">
                                            @csrf

                                            @if ($hasTemplates)
                                                {{-- ── TEMPLATES EXIST: required upload per template ── --}}
                                                <div class="template-upload-section-form mb-4">
                                                    <div class="template-upload-header">
                                                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                                                        <span>Upload Completed Application Templates</span>
                                                    </div>
                                                    <p class="template-upload-subtext">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        This job requires you to submit the completed template(s) below.
                                                        Download each template above, fill it in, then upload it here.
                                                    </p>

                                                    <div class="template-upload-list">
                                                        @foreach ($job->templates as $index => $template)
                                                            <div class="template-upload-item">
                                                                <div class="template-upload-item-label">
                                                                    <div class="template-number">{{ $index + 1 }}</div>
                                                                    <div>
                                                                        <p class="template-name">{{ $template->file_name }}</p>
                                                                        <p class="template-name-hint">
                                                                            Download above → fill out → upload here
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="file-drop-zone"
                                                                    id="dropZone{{ $index }}"
                                                                    onclick="document.getElementById('templateFile{{ $index }}').click()">

                                                                    <input
                                                                        type="file"
                                                                        id="templateFile{{ $index }}"
                                                                        name="template_files[{{ $template->id }}]"
                                                                        class="file-input-hidden"
                                                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                                        required
                                                                        onchange="handleFileSelect(this, 'dropZone{{ $index }}', 'fileName{{ $index }}')"
                                                                    >

                                                                    <div class="drop-zone-content" id="dropContent{{ $index }}">
                                                                        <i class="bi bi-cloud-upload-fill drop-zone-icon"></i>
                                                                        <span class="drop-zone-text">Click to upload or drag & drop</span>
                                                                        <span class="drop-zone-hint">PDF, Word, JPG, PNG · Max 2MB</span>
                                                                    </div>

                                                                    <div class="file-selected-preview d-none" id="fileName{{ $index }}">
                                                                        <i class="bi bi-file-earmark-check-fill file-selected-icon"></i>
                                                                        <span class="file-selected-name"></span>
                                                                        <button type="button" class="file-clear-btn"
                                                                            onclick="clearFile(event, 'templateFile{{ $index }}', 'dropZone{{ $index }}', 'fileName{{ $index }}')">
                                                                            <i class="bi bi-x-lg"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="required-badge">
                                                                    <i class="bi bi-asterisk me-1"></i>Required
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                {{-- Application Letter: Optional when templates exist 
                                                <div class="mb-4">
                                                    <label class="form-label-custom">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        Upload Application Letter
                                                        <span class="optional-tag">Optional</span>
                                                    </label>
                                                    <div class="file-drop-zone" id="dropZoneLetter"
                                                        onclick="document.getElementById('appLetterFile').click()">

                                                        <input
                                                            type="file"
                                                            id="appLetterFile"
                                                            name="application_letter"
                                                            class="file-input-hidden"
                                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                            onchange="handleFileSelect(this, 'dropZoneLetter', 'fileNameLetter')"
                                                        >

                                                        <div class="drop-zone-content" id="dropContentLetter">
                                                            <i class="bi bi-cloud-upload-fill drop-zone-icon"></i>
                                                            <span class="drop-zone-text">Click to upload or drag & drop</span>
                                                            <span class="drop-zone-hint">PDF, Word, JPG, PNG · Max 2MB</span>
                                                        </div>

                                                        <div class="file-selected-preview d-none" id="fileNameLetter">
                                                            <i class="bi bi-file-earmark-check-fill file-selected-icon"></i>
                                                            <span class="file-selected-name"></span>
                                                            <button type="button" class="file-clear-btn"
                                                                onclick="clearFile(event, 'appLetterFile', 'dropZoneLetter', 'fileNameLetter')">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-hint">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Accepted formats: PDF, Word, JPG, PNG (Max 2MB)
                                                    </small>
                                                </div>

                                            @else
                                                {{-- ── NO TEMPLATES: only application letter, required ── 
                                                <div class="mb-4">
                                                    <label class="form-label-custom">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        Upload Application Letter
                                                    </label>
                                                    <div class="file-drop-zone" id="dropZoneLetter"
                                                        onclick="document.getElementById('appLetterFile').click()">

                                                        <input
                                                            type="file"
                                                            id="appLetterFile"
                                                            name="application_letter"
                                                            class="file-input-hidden"
                                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                            required
                                                            onchange="handleFileSelect(this, 'dropZoneLetter', 'fileNameLetter')"
                                                        >

                                                        <div class="drop-zone-content" id="dropContentLetter">
                                                            <i class="bi bi-cloud-upload-fill drop-zone-icon"></i>
                                                            <span class="drop-zone-text">Click to upload or drag & drop</span>
                                                            <span class="drop-zone-hint">PDF, Word, JPG, PNG · Max 2MB</span>
                                                        </div>

                                                        <div class="file-selected-preview d-none" id="fileNameLetter">
                                                            <i class="bi bi-file-earmark-check-fill file-selected-icon"></i>
                                                            <span class="file-selected-name"></span>
                                                            <button type="button" class="file-clear-btn"
                                                                onclick="clearFile(event, 'appLetterFile', 'dropZoneLetter', 'fileNameLetter')">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-hint">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Accepted formats: PDF, Word, JPG, PNG (Max 2MB)
                                                    </small>
                                                </div>
                                                --}}
                                            @endif
                                            

                                            <button type="submit" class="btn-apply-submit {{ $isReapply ? 'btn-reapply' : '' }}" id="submitBtn">
                                                <i class="bi bi-{{ $isReapply ? 'arrow-repeat' : 'rocket-takeoff' }} me-2"></i>
                                                {{ $isReapply ? 'Re-submit Application' : 'Submit Application' }}
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

            /* ── Container ── */
            .job-details-container {
                padding: 3rem 0;
            }

            /* ── Back Button ── */
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
                animation: fadeIn 0.4s ease-out;
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

            /* ── Alerts ── */
            .alert-custom {
                border-radius: 12px;
                border: none;
                padding: 1rem 1.25rem;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                animation: slideDown 0.3s ease-out;
                display: flex;
                align-items: flex-start;
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
                from { opacity: 0; transform: translateY(-10px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Already Applied Box ── */
            .already-applied-box {
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
                border: 2px solid rgba(15, 104, 72, 0.3);
                border-radius: 14px;
                padding: 1.5rem;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
            }

            .already-applied-icon {
                width: 48px;
                height: 48px;
                background: rgba(15, 104, 72, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #0F6848;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .already-applied-content {
                flex: 1;
            }

            .already-applied-title {
                color: #0F6848;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .already-applied-text {
                color: #0F6848;
                margin: 0;
            }

            .track-link {
                color: #0F6848;
                font-weight: 700;
                text-decoration: underline;
            }

            .track-link:hover {
                color: var(--secondary-color);
            }

            /* ── Cooldown Box ── */
            .cooldown-box {
                background: linear-gradient(135deg, #FFF4E6 0%, #FFE8CC 100%);
                border: 2px solid rgba(217, 119, 6, 0.3);
                border-radius: 14px;
                padding: 1.5rem;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
            }

            .cooldown-icon {
                width: 48px;
                height: 48px;
                background: rgba(217, 119, 6, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #D97706;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .cooldown-content {
                flex: 1;
            }

            .cooldown-title {
                color: #D97706;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .cooldown-text {
                color: #D97706;
                margin-bottom: 1rem;
            }

            .cooldown-progress {
                width: 100%;
                height: 8px;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 0.75rem;
            }

            .cooldown-progress-bar {
                height: 100%;
                background: linear-gradient(90deg, #D97706, #F59E0B);
                border-radius: 10px;
                transition: width 0.3s ease;
            }

            .cooldown-hint {
                color: #D97706;
                font-size: 0.9rem;
                margin: 0;
                font-style: italic;
            }

            /* ── Re-apply Notice ── */
            .reapply-notice {
                background: linear-gradient(135deg, #FFF8F0 0%, #FFEFD5 100%);
                border: 2px solid rgba(255, 107, 53, 0.3);
                border-left: 4px solid var(--primary-color);
                border-radius: 12px;
                padding: 1.25rem;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
            }

            .reapply-notice-icon {
                width: 42px;
                height: 42px;
                background: rgba(255, 107, 53, 0.15);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 1.3rem;
                flex-shrink: 0;
            }

            .reapply-notice-content {
                flex: 1;
            }

            .reapply-notice-title {
                color: var(--primary-color);
                font-weight: 700;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .reapply-notice-text {
                color: var(--text-dark);
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .rejection-reminder {
                color: #C92A2A;
                font-style: italic;
                font-size: 0.85rem;
            }

            .reapply-notice-tip {
                color: var(--text-muted);
                font-size: 0.85rem;
                margin: 0;
                font-weight: 600;
            }

            /* ── Job Card ── */
            .job-details-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                animation: fadeIn 0.5s ease-out;
            }

            /* ── Job Header ── */
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
                top: 0; right: 0;
                width: 300px; height: 300px;
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

            /* ── Job Body ── */
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

            /* ── Template Download ── */
            .template-download-section {
                background: linear-gradient(135deg, #F0FFFE 0%, #FFFFFF 100%);
                padding: 1.5rem;
                border-radius: 12px;
                border: 2px solid rgba(78, 205, 196, 0.3);
                margin-bottom: 2rem;
            }

            .template-list {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
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

            /* ── Application Form ── */
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

            .application-title i {
                color: var(--primary-color);
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

            .form-hint {
                display: block;
                color: var(--text-muted);
                font-size: 0.85rem;
                margin-top: 0.5rem;
            }

            /* ── Resume Info Box ── */
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

            /* ── Template Upload Form Section ── */
            .template-upload-section-form {
                background: linear-gradient(135deg, #FFFAF8 0%, #FFF5F2 100%);
                border: 2px solid rgba(255, 107, 53, 0.25);
                border-radius: 14px;
                padding: 1.5rem;
            }

            .template-upload-header {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                font-size: 1.05rem;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 0.5rem;
            }

            .template-upload-header i {
                color: var(--primary-color);
                font-size: 1.2rem;
            }

            .template-upload-subtext {
                font-size: 0.88rem;
                color: var(--text-muted);
                margin-bottom: 1.25rem;
                line-height: 1.5;
            }

            .template-upload-list {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            .template-upload-item {
                background: white;
                border: 2px solid var(--border-color);
                border-radius: 12px;
                padding: 1.25rem;
                transition: border-color 0.3s ease;
            }

            .template-upload-item-label {
                display: flex;
                align-items: flex-start;
                gap: 0.85rem;
                margin-bottom: 1rem;
            }

            .template-number {
                width: 28px;
                height: 28px;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: 700;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .template-name {
                font-weight: 700;
                color: var(--text-dark);
                font-size: 0.95rem;
                margin: 0 0 0.2rem;
            }

            .template-name-hint {
                font-size: 0.82rem;
                color: var(--text-muted);
                margin: 0;
            }

            .required-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.78rem;
                font-weight: 700;
                color: #C92A2A;
                margin-top: 0.6rem;
            }

            .required-badge i {
                font-size: 0.6rem;
            }

            .optional-tag {
                background: var(--border-color);
                color: var(--text-muted);
                font-size: 0.72rem;
                font-weight: 600;
                padding: 0.15rem 0.5rem;
                border-radius: 6px;
                margin-left: 0.35rem;
                vertical-align: middle;
            }

            /* ── Drag & Drop Zone ── */
            .file-drop-zone {
                border: 2px dashed var(--border-color);
                border-radius: 12px;
                padding: 1.5rem 1rem;
                background: white;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: center;
                position: relative;
            }

            .file-drop-zone:hover,
            .file-drop-zone.drag-over {
                border-color: var(--primary-color);
                background: linear-gradient(135deg, #FFF5F2 0%, #FFE8E0 100%);
            }

            .file-drop-zone.file-attached {
                border-color: #10B981;
                border-style: solid;
                background: linear-gradient(135deg, #E8F8F5 0%, #D5F4E6 100%);
            }

            .file-input-hidden {
                position: absolute;
                width: 0;
                height: 0;
                opacity: 0;
                pointer-events: none;
            }

            .drop-zone-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.35rem;
                pointer-events: none;
            }

            .drop-zone-icon {
                font-size: 2rem;
                color: var(--text-muted);
                transition: color 0.3s;
            }

            .file-drop-zone:hover .drop-zone-icon,
            .file-drop-zone.drag-over .drop-zone-icon {
                color: var(--primary-color);
            }

            .drop-zone-text {
                font-weight: 600;
                font-size: 0.92rem;
                color: var(--text-dark);
            }

            .drop-zone-hint {
                font-size: 0.8rem;
                color: var(--text-muted);
            }

            /* ── File Selected State ── */
            .file-selected-preview {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                padding: 0.25rem 0;
            }

            .file-selected-icon {
                font-size: 1.5rem;
                color: #10B981;
                flex-shrink: 0;
            }

            .file-selected-name {
                font-weight: 600;
                font-size: 0.9rem;
                color: #0F6848;
                word-break: break-all;
            }

            .file-clear-btn {
                background: rgba(201, 42, 42, 0.1);
                border: none;
                color: #C92A2A;
                border-radius: 6px;
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                flex-shrink: 0;
                transition: all 0.2s ease;
                font-size: 0.75rem;
            }

            .file-clear-btn:hover {
                background: #C92A2A;
                color: white;
            }

            /* ── Submit Buttons ── */
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
                cursor: pointer;
            }

            .btn-apply-submit:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            }

            .btn-apply-submit:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .btn-reapply {
                background: linear-gradient(135deg, #F59E0B, #D97706);
                box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
            }

            .btn-reapply:hover:not(:disabled) {
                box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
            }

            /* ── Responsive ── */
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

                .application-section {
                    padding: 1.25rem;
                }

                .template-upload-section-form {
                    padding: 1.25rem;
                }
            }
        </style>

        <script>
            /**
             * Called when a file is selected via the hidden input.
             * Swaps the drop-zone content to show the chosen filename.
             */
            function handleFileSelect(input, dropZoneId, fileNameId) {
                const dropZone   = document.getElementById(dropZoneId);
                const fileNameEl = document.getElementById(fileNameId);

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    // Hide the placeholder, show the file preview
                    dropZone.querySelector('.drop-zone-content').classList.add('d-none');
                    fileNameEl.classList.remove('d-none');
                    fileNameEl.querySelector('.file-selected-name').textContent = file.name;

                    // Apply green "attached" styling
                    dropZone.classList.add('file-attached');
                }
            }

            /**
             * Clears a file input and resets the drop-zone to its default state.
             */
            function clearFile(event, inputId, dropZoneId, fileNameId) {
                event.stopPropagation(); // prevent re-opening the file picker

                const input      = document.getElementById(inputId);
                const dropZone   = document.getElementById(dropZoneId);
                const fileNameEl = document.getElementById(fileNameId);

                input.value = '';
                fileNameEl.classList.add('d-none');
                dropZone.querySelector('.drop-zone-content').classList.remove('d-none');
                dropZone.classList.remove('file-attached');
            }

            /**
             * Enable drag-and-drop on every drop zone.
             */
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.file-drop-zone').forEach(function (zone) {
                    zone.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        zone.classList.add('drag-over');
                    });

                    zone.addEventListener('dragleave', function () {
                        zone.classList.remove('drag-over');
                    });

                    zone.addEventListener('drop', function (e) {
                        e.preventDefault();
                        zone.classList.remove('drag-over');

                        const input = zone.querySelector('input[type="file"]');
                        if (input && e.dataTransfer.files.length) {
                            // Assign dropped files to the hidden input
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(e.dataTransfer.files[0]);
                            input.files = dataTransfer.files;
                            input.dispatchEvent(new Event('change'));
                        }
                    });
                });

                // Form submission handling
                const form = document.getElementById('applicationForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = document.getElementById('submitBtn');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
                    });
                }
            });
        </script>

    @endsection
@endcan