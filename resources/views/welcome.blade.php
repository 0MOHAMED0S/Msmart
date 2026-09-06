<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Very Smart File Formatter</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --success: #10b981;
            --success-hover: #059669;
            --error: #ef4444;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top center, #1e293b, var(--bg-color));
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease-out;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-muted);
            max-width: 600px;
            line-height: 1.6;
        }

        .glass-panel {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .main-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            animation: fadeInUp 0.8s ease-out;
        }

        .controls-card {
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .top-settings-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
            width: 100%;
        }

        .settings-panel {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 23, 42, 0.4);
            padding: 12px 20px;
            border-radius: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .setting-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .setting-group.border-left {
            margin-left: 10px;
            padding-left: 10px;
            border-left: 1px solid var(--border-color);
        }

        .setting-group label {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .settings-panel input {
            background: #1e293b;
            color: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 6px 12px;
            font-family: inherit;
        }
        
        .settings-panel input:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        .settings-panel input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
            max-width: 500px;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed #475569;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(15, 23, 42, 0.4);
        }

        .file-upload-label:hover {
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.1);
        }

        .file-upload-label svg {
            width: 48px;
            height: 48px;
            fill: none;
            stroke: var(--text-muted);
            stroke-width: 2;
            margin-bottom: 12px;
            transition: stroke 0.3s ease;
        }

        .file-upload-label:hover svg {
            stroke: var(--accent);
        }

        input[type="file"] {
            display: none;
        }

        #fileName {
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--accent);
            font-weight: 600;
        }

        .button-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }

        button {
            padding: 12px 28px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background: #475569;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-success {
            background: var(--success);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
        }

        .btn-success:hover {
            background: var(--success-hover);
            transform: translateY(-2px);
        }

        .btn-success:disabled {
            background: #475569;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        
        .btn-danger {
            background: transparent;
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        #status {
            font-weight: 600;
            font-size: 1rem;
            height: 24px;
        }

        /* Previews Section */
        .previews-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px; /* Preserve navbar space on mobile */
            }
            .content-wrapper {
                padding: 16px 12px;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .header {
                margin-bottom: 20px;
            }
            .header p {
                font-size: 0.9rem;
            }
            .controls-card {
                padding: 16px 12px;
                gap: 16px;
            }
            .file-upload-label {
                padding: 16px 10px;
                text-align: center;
            }
            .file-upload-label span {
                font-size: 0.9rem;
            }
            .file-upload-label svg {
                width: 36px;
                height: 36px;
                margin-bottom: 8px;
            }
            #fileName {
                font-size: 0.8rem;
            }
            .button-group {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }
            button {
                width: 100%;
                justify-content: center;
                font-size: 0.95rem;
                padding: 10px 20px;
            }
            .previews-container {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .preview-content {
                height: 300px;
            }
            .top-settings-row {
                flex-direction: column;
                gap: 12px;
            }
            .settings-panel {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                padding: 12px;
                gap: 8px;
            }
            .setting-group label {
                font-size: 0.85rem;
            }
            .settings-panel input {
                width: 100% !important; /* Force full width on mobile */
                margin-top: 2px;
                font-size: 0.9rem;
                padding: 6px 10px;
            }
            .setting-group {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
                gap: 4px;
            }
            .setting-group.border-left {
                border-left: none;
                padding-left: 0;
                margin-left: 0;
                margin-top: 8px;
                border-top: 1px solid rgba(255,255,255,0.1);
                padding-top: 12px;
            }
            .preview-title {
                font-size: 1rem;
            }
            .preview-badge {
                font-size: 0.7rem;
            }
        }

        .preview-pane {
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: fadeIn 1s ease-in;
            min-width: 0;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 8px;
        }

        .preview-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-badge {
            font-size: 0.75rem;
            background: #334155;
            padding: 2px 8px;
            border-radius: 12px;
            color: #cbd5e1;
        }

        .preview-content {
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            color: #a7f3d0;
            line-height: 1.5;
            white-space: pre; 
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .preview-content.before {
             color: #94a3b8;
        }
        
        textarea.preview-content {
            resize: vertical;
            outline: none;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        textarea.preview-content:focus {
            border-color: var(--accent);
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3), 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
        
        /* Scrollbar styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-card {
            background: #1e293b;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: translateY(20px) scale(0.95);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .modal-overlay.active .modal-card {
            transform: translateY(0) scale(1);
        }

        .modal-icon {
            color: var(--error);
            margin-bottom: 16px;
            display: inline-flex;
            background: rgba(239, 68, 68, 0.1);
            padding: 16px;
            border-radius: 50%;
        }

        .modal-card h2 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .modal-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-cancel {
            background: #334155;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            flex: 1;
        }

        .btn-cancel:hover {
            background: #475569;
        }
        
        .modal-buttons .btn-primary {
            flex: 1;
            justify-content: center;
        }

        /* Layout Additions */
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top center, #1e293b, var(--bg-color));
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 70px; /* Space for navbar */
        }

        .content-wrapper {
            flex-grow: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .navbar {
            width: 100%;
            height: 70px;
            padding: 0 40px;
            display: flex;
            align-items: center;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .footer {
            width: 100%;
            text-align: center;
            padding: 24px;
            background: rgba(15, 23, 42, 0.6);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: auto;
        }

        /* Loading Screen */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s;
        }

        .loader-overlay.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .loader-text-wrapper {
            overflow: hidden; /* For the slide-up reveal */
            padding-bottom: 5px; /* Prevent clipping of glowing text */
        }

        .loader-logo {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            letter-spacing: 4px;
            text-transform: uppercase;
            transform: translateY(100%);
            animation: slideUpReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .loader-progress-bar {
            width: 200px;
            height: 2px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .loader-progress-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 4px;
            animation: progressFill 1.2s cubic-bezier(0.7, 0, 0.3, 1) forwards;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        @keyframes slideUpReveal {
            to { transform: translateY(0); }
        }

        @keyframes progressFill {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 20px;
            }
            .loader-logo {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Loading Screen -->
    <div id="loader" class="loader-overlay">
        <div class="loader-content">
            <div class="loader-text-wrapper">
                <h1 class="loader-logo">Msmart</h1>
            </div>
            <div class="loader-progress-bar">
                <div class="loader-progress-fill"></div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">Msmart</div>
    </nav>

    <!-- Clear Modal -->
    <div id="clearModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <h2>Clear Workspace?</h2>
            <p>Are you sure you want to delete all cached files, manual edits, and settings? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeClearModal()">Cancel</button>
                <button class="btn-primary" style="background: var(--error);" onclick="confirmClearWorkspace()">Yes, Clear</button>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="header">
            <h1>Very Smart Formatter</h1>
            <p>Advanced data parsing, custom grouping separators, and smart editing tools.</p>
        </div>

    <div class="main-container">
        <!-- Controls -->
        <div class="glass-panel controls-card">
            
            <div class="top-settings-row">
                <!-- Grouping Settings -->
                <div class="settings-panel">
                    <div class="setting-group">
                        <label for="groupSize">Group Size:</label>
                        <input type="number" id="groupSize" value="100" min="1" style="width: 70px;" oninput="debouncedProcessFile()">
                    </div>
                    <div class="setting-group border-left">
                        <label for="customSeparator">Separator:</label>
                        <input type="text" id="customSeparator" value="-----------------------------" style="width: 200px; font-family: monospace;" oninput="debouncedProcessFile()">
                    </div>
                </div>

                <!-- Replace Settings -->
                <div class="settings-panel">
                    <div class="setting-group">
                        <label for="findInput">Find:</label>
                        <input type="text" id="findInput" placeholder="Text to find..." disabled oninput="debouncedProcessFile()" style="width: 140px;">
                    </div>
                    <div class="setting-group border-left">
                        <label for="replaceInput">Replace:</label>
                        <input type="text" id="replaceInput" placeholder="Replacement..." disabled oninput="debouncedProcessFile()" style="width: 140px;">
                    </div>
                </div>
            </div>
            
            <div class="file-upload-wrapper">
                <label for="fileInput" class="file-upload-label">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <span>Click or Drag to Upload .txt file</span>
                    <span id="fileName"></span>
                </label>
                <input type="file" id="fileInput" accept=".txt">
            </div>

            <div class="button-group">
                <button id="formatBtn" class="btn-primary" disabled onclick="processFile()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    Process File
                </button>
                
                <button id="downloadBtn" class="btn-success" disabled onclick="downloadFormattedFile()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download Output
                </button>
                
                <button id="clearBtn" class="btn-danger" onclick="showClearModal()" title="Clear cache and reset workspace">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Clear
                </button>
            </div>
            
            <div id="status"></div>
        </div>

        <!-- Previews -->
        <div class="previews-container">
            <!-- Before -->
            <div class="preview-pane">
                <div class="preview-header">
                    <div class="preview-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Raw Input
                    </div>
                    <span class="preview-badge" id="beforeLinesBadge">0 Lines</span>
                </div>
                <div class="preview-content before" id="beforePreview">Select a file to preview raw contents...</div>
            </div>

            <!-- After -->
            <div class="preview-pane">
                <div class="preview-header">
                    <div class="preview-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Editable Output
                    </div>
                    <span class="preview-badge" id="afterLinesBadge">0 Accounts</span>
                </div>
                
                <textarea class="preview-content" id="afterPreview" wrap="off" placeholder="Awaiting processing... Feel free to edit this text before downloading!" oninput="saveTextareaCache()"></textarea>
            </div>
        </div>
    </div>
    </div> <!-- End content-wrapper -->

    <!-- Footer -->
    <footer class="footer">
        &copy; 2026 Msmart. All rights reserved. Crafted with precision.
    </footer>

    <script>
        // Loader logic
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                if (loader) {
                    loader.classList.add('hidden');
                    // Remove from DOM after transition
                    setTimeout(() => loader.remove(), 800);
                }
            }, 1200); // 1.2 seconds to allow progress bar to fill
        });

        let rawContent = "";
        let processTimeout = null;

        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('fileName');
        const statusDiv = document.getElementById('status');
        
        const formatBtn = document.getElementById('formatBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        
        const beforePreview = document.getElementById('beforePreview');
        const afterPreview = document.getElementById('afterPreview');
        
        const beforeLinesBadge = document.getElementById('beforeLinesBadge');
        const afterLinesBadge = document.getElementById('afterLinesBadge');

        // Modal functions
        function showClearModal() {
            document.getElementById('clearModal').classList.add('active');
        }

        function closeClearModal() {
            document.getElementById('clearModal').classList.remove('active');
        }

        // Restore from Cache on Load
        window.addEventListener('DOMContentLoaded', () => {
            const savedRaw = localStorage.getItem('vsf_raw');
            const savedSettings = JSON.parse(localStorage.getItem('vsf_settings') || '{}');
            const savedAfter = localStorage.getItem('vsf_after');
            
            if (savedSettings.groupSize) document.getElementById('groupSize').value = savedSettings.groupSize;
            if (savedSettings.customSeparator) document.getElementById('customSeparator').value = savedSettings.customSeparator;
            if (savedSettings.findInput) document.getElementById('findInput').value = savedSettings.findInput;
            if (savedSettings.replaceInput) document.getElementById('replaceInput').value = savedSettings.replaceInput;
            
            if (savedRaw) {
                rawContent = savedRaw;
                beforePreview.textContent = rawContent;
                beforeLinesBadge.textContent = `${rawContent.split(/\r?\n/).length} Lines`;
                
                fileNameDisplay.textContent = "Loaded from cache";
                formatBtn.disabled = false;
                toggleSmartTools(true);
                
                // If they had manual edits, restore them. Otherwise re-process.
                if (savedAfter) {
                    afterPreview.value = savedAfter;
                    downloadBtn.disabled = false;
                    statusDiv.innerText = "Restored from cache.";
                    statusDiv.style.color = "var(--text-muted)";
                } else {
                    processFile(true);
                }
            }
        });

        function saveToCache() {
            localStorage.setItem('vsf_raw', rawContent);
            const settings = {
                groupSize: document.getElementById('groupSize').value,
                customSeparator: document.getElementById('customSeparator').value,
                findInput: document.getElementById('findInput').value,
                replaceInput: document.getElementById('replaceInput').value
            };
            localStorage.setItem('vsf_settings', JSON.stringify(settings));
        }

        function saveTextareaCache() {
            localStorage.setItem('vsf_after', afterPreview.value);
        }

        function confirmClearWorkspace() {
            closeClearModal();
            
            localStorage.removeItem('vsf_raw');
            localStorage.removeItem('vsf_settings');
            localStorage.removeItem('vsf_after');
            
            rawContent = "";
            beforePreview.textContent = "Select a file to preview raw contents...";
            afterPreview.value = "";
            beforeLinesBadge.textContent = "0 Lines";
            afterLinesBadge.textContent = "0 Accounts";
            
            document.getElementById('fileInput').value = "";
            fileNameDisplay.textContent = "";
            
            document.getElementById('groupSize').value = "100";
            document.getElementById('customSeparator').value = "-----------------------------";
            document.getElementById('findInput').value = "";
            document.getElementById('replaceInput').value = "";
            
            formatBtn.disabled = true;
            downloadBtn.disabled = true;
            toggleSmartTools(false);
            
            statusDiv.innerText = "Workspace cleared.";
            statusDiv.style.color = "var(--text-muted)";
        }

        function toggleSmartTools(enable) {
            document.getElementById('findInput').disabled = !enable;
            document.getElementById('replaceInput').disabled = !enable;
        }

        // Real-time debounce
        function debouncedProcessFile() {
            saveToCache();
            if (!rawContent) return;
            if (processTimeout) clearTimeout(processTimeout);
            
            statusDiv.innerText = "Processing edits...";
            statusDiv.style.color = "var(--text-main)";
            
            processTimeout = setTimeout(() => {
                processFile(true); // pass true to skip setting the status temporarily
            }, 300);
        }

        // File selection event listener
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                fileNameDisplay.textContent = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    rawContent = e.target.result;
                    beforePreview.textContent = rawContent;
                    
                    const lineCount = rawContent.split(/\r?\n/).length;
                    beforeLinesBadge.textContent = `${lineCount} Lines`;
                    
                    // Reset after state
                    afterPreview.value = "";
                    localStorage.removeItem('vsf_after');
                    afterLinesBadge.textContent = "0 Accounts";
                    downloadBtn.disabled = true;
                    toggleSmartTools(true);
                    
                    // Enable process button
                    formatBtn.disabled = false;
                    
                    // Save and Auto-process
                    saveToCache();
                    processFile();
                };
                reader.readAsText(file);
            } else {
                fileNameDisplay.textContent = "";
                formatBtn.disabled = true;
                toggleSmartTools(false);
            }
        });

        function processFile(isAuto = false) {
            if (!rawContent) {
                if (!isAuto) alert("Please select a file first.");
                return;
            }

            if (!isAuto) {
                statusDiv.innerText = "Processing...";
                statusDiv.style.color = "var(--text-main)";
            }
            
            saveToCache();
            
            // Allow UI to update before heavy processing
            setTimeout(() => {
                const groupSize = parseInt(document.getElementById('groupSize').value) || 1;
                const customSeparator = document.getElementById('customSeparator').value;
                const findStr = document.getElementById('findInput').value;
                const replaceStr = document.getElementById('replaceInput').value;
                
                const lines = rawContent.split(/\r?\n/);
                let currentGroup = [];
                let groups = [];
                let successCount = 0;

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                for (let i = 0; i < lines.length; i++) {
                    let line = lines[i].trim();
                    if (!line) continue;

                    let delimiters = [':', '|', ';', ',', '\t'];
                    let bestDelimiter = ':';
                    let maxSplits = 0;
                    for (let char of delimiters) {
                        let splits = line.split(char).length;
                        if (splits > maxSplits) { maxSplits = splits; bestDelimiter = char; }
                    }

                    let parts = line.split(bestDelimiter);
                    let emailIndex = parts.findIndex(p => emailRegex.test(p.trim()));

                    if (emailIndex !== -1) {
                        let username = (emailIndex >= 2) ? parts[emailIndex - 2].trim() : "UnknownUser";
                        let password = (emailIndex >= 1) ? parts[emailIndex - 1].trim() : "UnknownPass";
                        let email = parts[emailIndex].trim();
                        let emailPass = (emailIndex + 1 < parts.length) ? parts[emailIndex + 1].trim() : "UnknownEmailPass";
                        let twoFa = (emailIndex + 2 < parts.length) ? parts[emailIndex + 2].trim() : "Unknown2FA";

                        let urlIndex = parts.findIndex(p => p.trim().startsWith('http://') || p.trim().startsWith('https://'));
                        let link = "";
                        if (urlIndex !== -1) {
                            link = (bestDelimiter === ':') ? parts.slice(urlIndex).join(':').trim() : parts[urlIndex].trim();
                        } else {
                            link = "http://2fa.fb.rip/" + twoFa;
                        }

                        currentGroup.push(`${username}\n${password}\n${email}\n${emailPass}\n${twoFa}\n${link}`);
                        successCount++;
                        
                        if (currentGroup.length === groupSize) {
                            groups.push(currentGroup.join('\n\n'));
                            currentGroup = [];
                        }
                    }
                }
                
                if (currentGroup.length > 0) {
                    groups.push(currentGroup.join('\n\n'));
                }
                
                if (successCount === 0) {
                    statusDiv.innerText = "Error: Could not detect any valid accounts.";
                    statusDiv.style.color = "var(--error)";
                    afterPreview.value = "";
                    downloadBtn.disabled = true;
                    return;
                }
                
                // 1. Join with separator
                const joinStr = customSeparator ? `\n\n${customSeparator}\n\n` : `\n\n`;
                let finalText = groups.join(joinStr);
                
                // 2. Apply Find/Replace globally if user typed something
                let replacedCount = 0;
                if (findStr) {
                    const escapeRegExp = (string) => string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    const regex = new RegExp(escapeRegExp(findStr), 'g');
                    replacedCount = (finalText.match(regex) || []).length;
                    finalText = finalText.replace(regex, replaceStr);
                }

                afterPreview.value = finalText;
                saveTextareaCache();
                afterLinesBadge.textContent = `${successCount} Accounts`;
                
                downloadBtn.disabled = false;
                
                let msg = `Success! Processed ${successCount} accounts (Grouped by ${groupSize}).`;
                if (replacedCount > 0) {
                    msg += ` Replaced ${replacedCount} occurrence(s).`;
                }
                
                statusDiv.innerText = msg;
                statusDiv.style.color = "var(--success)";
                
            }, 50);
        }

        function downloadFormattedFile() {
            const contentToSave = afterPreview.value;
            if (!contentToSave) return;
            
            const filename = 'Smart_Output.txt';
            const blob = new Blob([contentToSave], { type: 'text/plain' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

</body>
</html>