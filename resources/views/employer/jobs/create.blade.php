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
                                    <i class="bi bi-plus-circle-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">Post a New Job</h3>
                                    <p class="text-muted mb-0">Fill in the details to create a job posting</p>
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
                            <form action="{{ route('employer.jobs.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- JOB TITLE --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-briefcase me-2"></i>Job Title
                                    </label>
                                    <input type="text" name="job_title" class="form-control-custom"
                                        placeholder="e.g. Senior Software Engineer" value="{{ old('job_title') }}" required>
                                </div>

                                {{-- JOB DESCRIPTION --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-file-text me-2"></i>Job Description
                                    </label>
                                    <textarea name="job_description" class="form-control-custom" rows="5"
                                        placeholder="Describe the role, responsibilities, and what you're looking for..." required>{{ old('job_description') }}</textarea>
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
                                                    {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full Time
                                                </option>
                                                <option value="part-time"
                                                    {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part Time
                                                </option>
                                                <option value="internship"
                                                    {{ old('job_type') == 'internship' ? 'selected' : '' }}>Internship
                                                </option>
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
                                                placeholder="e.g. 50000" value="{{ old('salary') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- REQUIREMENTS --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-list-check me-2"></i>Requirements
                                    </label>
                                    <textarea name="requirements" class="form-control-custom" rows="4"
                                        placeholder="List the qualifications, skills, and experience needed..." required>{{ old('requirements') }}</textarea>
                                </div>

                                {{-- LOCATION & STATUS ROW --}}
                                <div class="row mb-4">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-geo-alt me-2"></i>Location
                                            </label>
                                            <input type="text" name="location" class="form-control-custom"
                                                placeholder="e.g. New York, NY or Remote" value="{{ old('location') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="bi bi-toggles me-2"></i>Status
                                            </label>
                                            <select name="status" class="form-select-custom" required>
                                                <option value="open"
                                                    {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>
                                                    Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- APPLICATION TEMPLATES (MULTIPLE) --}}
                                <div class="form-group-custom mb-4">
                                    <label class="form-label-custom">
                                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Application Templates
                                        <span class="optional-badge">Optional - Multiple files allowed</span>
                                    </label>

                                    <div class="file-upload-wrapper">
                                        <input type="file" name="application_templates[]" class="form-control-file"
                                            id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                                        <label for="fileInput" class="file-upload-label">
                                            <i class="bi bi-cloud-upload me-2"></i>
                                            <span class="file-text">Choose files or drag here</span>
                                        </label>
                                    </div>

                                    {{-- Selected Files Display --}}
                                    <div id="selectedFiles" class="selected-files-container mt-3"></div>

                                    <small class="form-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Allowed: PDF, Word, Excel (Max 5MB per file). You can upload multiple templates.
                                    </small>
                                </div>

                                {{-- SUBMIT BUTTONS --}}
                                <div class="row mt-5">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <a href="{{ route('dashboard') }}" class="btn btn-cancel w-100">
                                            <i class="bi bi-x-circle me-2"></i>Cancel
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-submit w-100">
                                            <i class="bi bi-check-circle me-2"></i>Post Job
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

        /* File Upload */
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

        .file-item-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .file-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.25));
            color: var(--primary-color);
        }

        .file-item-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
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

        /* Custom Scrollbar */
        textarea.form-control-custom::-webkit-scrollbar {
            width: 8px;
        }

        textarea.form-control-custom::-webkit-scrollbar-track {
            background: var(--input-bg);
            border-radius: 10px;
        }

        textarea.form-control-custom::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }

        textarea.form-control-custom::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>

    <script>
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
                        fileText.textContent = `${files.length} file(s) selected`;
                    } else {
                        selectedFiles = [];
                        selectedFilesContainer.innerHTML = '';
                        selectedFilesContainer.classList.remove('has-files');
                        fileText.textContent = 'Choose files or drag here';
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

                // Create new FileList
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;

                if (selectedFiles.length > 0) {
                    displaySelectedFiles();
                    fileText.textContent = `${selectedFiles.length} file(s) selected`;
                } else {
                    selectedFilesContainer.innerHTML = '';
                    selectedFilesContainer.classList.remove('has-files');
                    fileText.textContent = 'Choose files or drag here';
                }
            };
        });
    </script>
@endsection
