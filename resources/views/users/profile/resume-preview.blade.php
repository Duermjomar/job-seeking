<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #1a1a2e; min-height: 100vh; display: flex; flex-direction: column; }

        .preview-toolbar {
            background: #16213e;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0f3460;
            flex-shrink: 0;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }

        .file-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .file-icon.word  { background: rgba(43, 87, 154, 0.3); color: #4a90d9; }
        .file-icon.excel { background: rgba(33, 115, 70, 0.3); color: #4caf7d; }

        .file-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #e2e8f0;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-type {
            font-size: 0.78rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .toolbar-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-toolbar {
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-download {
            background: rgba(255,107,53,0.15);
            color: #FF6B35;
            border: 1px solid rgba(255,107,53,0.3);
        }

        .btn-download:hover { background: #FF6B35; color: white; }

        .btn-close-preview {
            background: rgba(255,255,255,0.08);
            color: #a0aec0;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-close-preview:hover { background: rgba(255,255,255,0.15); color: white; }

        /* Preview Frame Area */
        .preview-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .preview-iframe {
            width: 100%;
            flex: 1;
            border: none;
            min-height: calc(100vh - 70px);
        }

        /* Fallback */
        .fallback-overlay {
            display: none;
            flex: 1;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            text-align: center;
        }

        .fallback-overlay.visible { display: flex; }

        .fallback-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
        }

        .fallback-icon.word  { color: #4a90d9; }
        .fallback-icon.excel { color: #4caf7d; }

        .fallback-title {
            color: #e2e8f0;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .fallback-text {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 420px;
            margin-bottom: 2rem;
        }

        .fallback-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-fallback-primary {
            background: linear-gradient(135deg, #FF6B35, #E85A2A);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(255,107,53,0.3);
            transition: all 0.3s ease;
        }

        .btn-fallback-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255,107,53,0.4);
            color: white;
        }

        .btn-fallback-secondary {
            background: rgba(255,255,255,0.06);
            color: #a0aec0;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(255,255,255,0.1);
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .btn-fallback-secondary:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .loading-indicator {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #1a1a2e;
            gap: 1rem;
            z-index: 10;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255,107,53,0.2);
            border-top-color: #FF6B35;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .loading-text { color: #718096; font-size: 0.9rem; }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    {{-- Toolbar --}}
    <div class="preview-toolbar">
        <div class="file-info">
            <div class="file-icon {{ in_array($extension, ['doc','docx']) ? 'word' : 'excel' }}">
                <i class="bi bi-file-earmark-{{ in_array($extension, ['doc','docx']) ? 'word' : 'spreadsheet' }}-fill"></i>
            </div>
            <div>
                <div class="file-name">{{ $originalName }}</div>
                <div class="file-type">{{ strtoupper($extension) }} Document</div>
            </div>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('users.resume.download') }}" class="btn-toolbar btn-download">
                <i class="bi bi-download"></i> Download
            </a>
            <button onclick="window.close()" class="btn-toolbar btn-close-preview">
                <i class="bi bi-x-lg"></i> Close
            </button>
        </div>
    </div>

    {{-- Preview Area --}}
    <div class="preview-area">

        {{-- Loading spinner --}}
        <div class="loading-indicator" id="loadingIndicator">
            <div class="spinner"></div>
            <div class="loading-text">Loading preview...</div>
        </div>

        {{-- Office Online iframe --}}
        <iframe
            id="previewFrame"
            class="preview-iframe"
            src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($publicUrl) }}"
            style="display:none;"
            onload="handleIframeLoad()"
            onerror="showFallback()">
        </iframe>

        {{-- Fallback UI (shown if iframe fails) --}}
        <div class="fallback-overlay" id="fallbackOverlay">
            <div class="fallback-icon {{ in_array($extension, ['doc','docx']) ? 'word' : 'excel' }}">
                <i class="bi bi-file-earmark-{{ in_array($extension, ['doc','docx']) ? 'word' : 'spreadsheet' }}-fill"></i>
            </div>
            <h3 class="fallback-title">Preview Unavailable</h3>
            <p class="fallback-text">
                This file can't be previewed in the browser right now — this usually happens on
                <strong style="color:#e2e8f0;">localhost</strong> since the viewer needs a public URL.
                You can download the file to view it, or deploy to a live server for full preview support.
            </p>
            <div class="fallback-actions">
                <a href="{{ route('users.resume.download') }}" class="btn-fallback-primary">
                    <i class="bi bi-download"></i> Download & Open
                </a>
                <button onclick="window.close()" class="btn-fallback-secondary">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script>
        const TIMEOUT_MS = 8000; // 8 seconds before showing fallback
        let fallbackTimer;

        function handleIframeLoad() {
            clearTimeout(fallbackTimer);
            // Try to detect if Office viewer returned an error page
            try {
                const frame = document.getElementById('previewFrame');
                // If we can read the iframe (same origin), check for error — but Office viewer
                // is cross-origin so this will throw; that's fine — it means it loaded normally
                frame.contentDocument; 
            } catch (e) {
                // Cross-origin = Office viewer loaded successfully
                showPreview();
                return;
            }
            // Same-origin means something went wrong (rare)
            showFallback();
        }

        function showPreview() {
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('previewFrame').style.display = 'block';
        }

        function showFallback() {
            clearTimeout(fallbackTimer);
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('previewFrame').style.display = 'none';
            document.getElementById('fallbackOverlay').classList.add('visible');
        }

        // Start timeout — if iframe hasn't loaded in 8s, show fallback
        fallbackTimer = setTimeout(showFallback, TIMEOUT_MS);
    </script>
</body>
</html>