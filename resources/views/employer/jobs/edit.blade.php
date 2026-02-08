@extends('layouts.Employer.app')

@section('content')
    <div class="job-post-wrapper">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- Page Header --}}
                    <div class="page-header mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="header-icon">
                                    <i class="bi bi-pencil-square"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">Edit Job Posting</h3>
                                    <p class="text-muted mb-0">Update the details for "{{ $job->job_title }}"</p>
                                </div>
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn btn-back">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </div>

                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))
                        <div class="alert alert-custom alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ERROR MESSAGE --}}
                    @if ($errors->any())
                        <div class="alert alert-custom alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Card --}}
                    <div class="form-card">
                        <div class="card-body p-4">
                            <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- JOB TITLE --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-briefcase me-2"></i>Job Title
                                    </label>
                                    <input type="text" name="job_title" class="form-control-custom"
                                        placeholder="e.g. Senior Software Engineer"
                                        value="{{ old('job_title', $job->job_title) }}" required>
                                </div>

                                {{-- JOB DESCRIPTION --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-file-text me-2"></i>Job Description
                                    </label>
                                    <textarea name="job_description" class="form-control-custom" rows="5"
                                        placeholder="Describe the role, responsibilities, and what you're looking for..." required>{{ old('job_description', $job->job_description) }}</textarea>
                                </div>

                                {{-- JOB TYPE & SALARY ROW --}}
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-clock me-2"></i>Job Type
                                            </label>
                                            <select name="job_type" class="form-select-custom" required>
                                                <option value="">-- Select Job Type --</option>
                                                <option value="full-time"
                                                    {{ old('job_type', $job->job_type) == 'full-time' ? 'selected' : '' }}>
                                                    Full Time</option>
                                                <option value="part-time"
                                                    {{ old('job_type', $job->job_type) == 'part-time' ? 'selected' : '' }}>
                                                    Part Time</option>
                                                <option value="internship"
                                                    {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>
                                                    Internship</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-cash-coin me-2"></i>Salary <span
                                                    class="optional-badge">Optional</span>
                                            </label>
                                            <input type="number" name="salary" class="form-control-custom"
                                                placeholder="e.g. 50000" value="{{ old('salary', $job->salary) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- REQUIREMENTS --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-list-check me-2"></i>Requirements
                                    </label>
                                    <textarea name="requirements" class="form-control-custom" rows="4"
                                        placeholder="List the qualifications, skills, and experience needed..." required>{{ old('requirements', $job->requirements) }}</textarea>
                                </div>

                                {{-- LOCATION & STATUS ROW --}}
                                <div class="row mb-4">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-geo-alt me-2"></i>Location
                                            </label>
                                            <input type="text" name="location" class="form-control-custom"
                                                placeholder="e.g. New York, NY or Remote"
                                                value="{{ old('location', $job->location) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-toggles me-2"></i>Status
                                            </label>
                                            <select name="status" class="form-select-custom" required>
                                                <option value="open"
                                                    {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open
                                                </option>
                                                <option value="closed"
                                                    {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- EXISTING TEMPLATES --}}
                                @if ($job->templates && $job->templates->count() > 0)
                                    <div class="existing-templates-section mb-4">
                                        <label class="form-label-custom mb-3">
                                            <i class="bi bi-files me-2"></i>Current Application Templates
                                        </label>

                                        <div class="existing-files-container">
                                            @foreach ($job->templates as $template)
                                                <div class="existing-file-item" id="template-{{ $template->id }}">
                                                    <div class="file-item-info">
                                                        <div class="file-item-icon">
                                                            <i
                                                                class="bi bi-{{ $template->file_type == 'pdf'
                                                                    ? 'file-pdf-fill'
                                                                    : (in_array($template->file_type, ['doc', 'docx'])
                                                                        ? 'file-word-fill'
                                                                        : 'file-excel-fill') }}"></i>
                                                        </div>
                                                        <div class="file-item-details">
                                                            <div class="file-item-name">{{ $template->file_name }}</div>
                                                            <div class="file-item-meta">
                                                                <span
                                                                    class="file-type-badge">{{ strtoupper($template->file_type) }}</span>
                                                                <span class="file-upload-date">
                                                                    <i class="bi bi-calendar3"></i>
                                                                    {{ $template->created_at->format('M d, Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="file-actions">
                                                        <a href="{{ route('employer.templates.view', $template->id) }}"
                                                            target="_blank" class="btn-file-action btn-view"
                                                            title="Download file">
                                                            <i class="bi bi-arrow-down"></i>
                                                        </a>
                                                        <button type="button" class="btn-file-action btn-delete"
                                                            onclick="deleteTemplate({{ $template->id }})"
                                                            title="Delete file">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- NEW APPLICATION TEMPLATES --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Add New Templates
                                        <span class="optional-badge">Optional - Multiple files allowed</span>
                                    </label>

                                    <div class="file-upload-wrapper">
                                        <input type="file" name="application_templates[]" class="form-control-file"
                                            id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                                        <label for="fileInput" class="file-upload-label">
                                            <i class="bi bi-cloud-upload me-2"></i>
                                            <span class="file-text">Choose new files or drag here</span>
                                        </label>
                                    </div>

                                    {{-- Selected Files Display --}}
                                    <div id="selectedFiles" class="selected-files-container mt-3"></div>

                                    <small class="form-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Allowed: PDF, Word, Excel (Max 5MB per file). New uploads will be added to existing
                                        templates.
                                    </small>
                                </div>

                                {{-- SUBMIT BUTTONS --}}
                                <div class="row mt-5">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <a href="{{ route('dashboard') }}" class="btn btn-cancel w-100">
                                            <i class="bi bi-x-circle me-2"></i>Cancel Changes
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-submit w-100">
                                            <i class="bi bi-check-circle me-2"></i>Update Job
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
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
            --input-bg: #F7F9FC;
        }

        * {
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--background-light);
        }

        .job-post-wrapper {
            min-height: 100vh;
        }

        /* Header */
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



        .btn-back {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
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

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
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

        /* Form Elements */
        .form-label-custom {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label-custom i {
            color: var(--primary-color);
        }

        .optional-badge {
            background: var(--input-bg);
            color: var(--text-muted);
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            margin-left: 0.5rem;
            font-weight: 500;
        }

        .form-control-custom,
        .form-select-custom {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: var(--input-bg);
            color: var(--text-dark);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        textarea.form-control-custom {
            resize: vertical;
            min-height: 100px;
        }

        /* Existing Templates */
        .existing-files-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .existing-file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #F7F9FC, #FFF);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .existing-file-item:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #FFF5F2, #FFF);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .file-item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .file-item-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .file-item-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }

        .file-item-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .file-type-badge {
            background: rgba(78, 205, 196, 0.15);
            color: var(--secondary-color);
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .file-upload-date {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .file-actions {
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
        }

        .btn-view {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.15), rgba(78, 205, 196, 0.25));
            color: var(--secondary-color);
        }

        .btn-view:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.15), rgba(255, 107, 107, 0.25));
            color: #FF6B6B;
        }

        .btn-delete:hover {
            background: #FF6B6B;
            color: white;
            transform: translateY(-2px);
        }

        /* File Upload (New) */
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .form-control-file {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            background: var(--input-bg);
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-muted);
            font-weight: 500;
        }

        .file-upload-label:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #FFF5F2, #FFE8E0);
            color: var(--primary-color);
        }

        .file-upload-label i {
            font-size: 1.5rem;
        }

        .selected-files-container {
            display: none;
        }

        .selected-files-container.has-files {
            display: block;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #F7F9FC, #FFF);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .file-item:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #FFF5F2, #FFF);
        }

        .file-item-size {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .file-item-remove {
            background: transparent;
            border: none;
            color: #FF6B6B;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .file-item-remove:hover {
            background: rgba(255, 107, 107, 0.1);
        }

        .form-hint {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Buttons */
        .btn-submit {
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

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-cancel {
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

        .btn-cancel:hover {
            background: var(--text-muted);
            color: white;
            border-color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem;
            }

            .header-icon {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

            .page-header h3 {
                font-size: 1.25rem;
            }

            .form-card .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>

    <script>
        // File upload handling for new templates
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const fileText = document.querySelector('.file-text');
            const selectedFilesContainer = document.getElementById('selectedFiles');

            let selectedFiles = [];

            if (fileInput && fileText) {
                fileInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);

                    if (files.length > 0) {
                        selectedFiles = files;
                        displaySelectedFiles();
                        fileText.textContent = `${files.length} new file(s) selected`;
                    } else {
                        selectedFiles = [];
                        selectedFilesContainer.innerHTML = '';
                        selectedFilesContainer.classList.remove('has-files');
                        fileText.textContent = 'Choose new files or drag here';
                    }
                });
            }

            function displaySelectedFiles() {
                selectedFilesContainer.innerHTML = '';
                selectedFilesContainer.classList.add('has-files');

                selectedFiles.forEach((file, index) => {
                    const fileItem = createFileItem(file, index);
                    selectedFilesContainer.appendChild(fileItem);
                });
            }

            function createFileItem(file, index) {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

                const fileExtension = file.name.split('.').pop().toLowerCase();
                const fileIcon = getFileIcon(fileExtension);
                const fileSize = formatFileSize(file.size);

                fileItem.innerHTML = `
                <div class="file-item-info">
                    <div class="file-item-icon">
                        <i class="bi bi-${fileIcon}"></i>
                    </div>
                    <div class="file-item-details">
                        <div class="file-item-name">${file.name}</div>
                        <div class="file-item-size">${fileSize}</div>
                    </div>
                </div>
                <button type="button" class="file-item-remove" onclick="removeFile(${index})">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            `;

                return fileItem;
            }

            function getFileIcon(extension) {
                const icons = {
                    'pdf': 'file-pdf-fill',
                    'doc': 'file-word-fill',
                    'docx': 'file-word-fill',
                    'xls': 'file-excel-fill',
                    'xlsx': 'file-excel-fill'
                };
                return icons[extension] || 'file-earmark-fill';
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);

                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;

                if (selectedFiles.length > 0) {
                    displaySelectedFiles();
                    fileText.textContent = `${selectedFiles.length} new file(s) selected`;
                } else {
                    selectedFilesContainer.innerHTML = '';
                    selectedFilesContainer.classList.remove('has-files');
                    fileText.textContent = 'Choose new files or drag here';
                }
            };
        });

        // Delete existing template
        function deleteTemplate(templateId) {
            if (!confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
                return;
            }

            fetch(`/employer/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`template-${templateId}`).remove();

                        // Check if no templates left
                        const remainingTemplates = document.querySelectorAll('.existing-file-item');
                        if (remainingTemplates.length === 0) {
                            document.querySelector('.existing-templates-section').remove();
                        }

                        alert('Template deleted successfully!');
                    } else {
                        alert('Error deleting template. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting template. Please try again.');
                });
        }
    </script>

    {{-- Add CSRF token meta tag for AJAX --}}
    @if (!isset($__env->getShared()['__data']['csrf_token']))
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif

@endsection
