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
            margin-bottom: 36px;
            animation: fadeInDown 0.8s ease-out;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 14px;
            border-radius: 9999px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 12px;
            box-shadow: 0 0 16px rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }

        .header-badge:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(96, 165, 250, 0.5);
            box-shadow: 0 0 24px rgba(59, 130, 246, 0.35);
            transform: translateY(-1px);
        }

        .header-badge .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #38bdf8;
            box-shadow: 0 0 8px #38bdf8;
            animation: statusPulse 2s infinite ease-in-out;
        }

        .header h1 {
            font-size: 2.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 50%, #38bdf8 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            animation: shimmerTitle 8s linear infinite alternate;
        }

        @keyframes shimmerTitle {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .header p {
            color: var(--text-muted);
            max-width: 620px;
            line-height: 1.6;
            font-size: 0.95rem;
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
            padding: 34px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 22px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .controls-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 12%;
            right: 12%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.7), rgba(168, 85, 247, 0.7), transparent);
            border-radius: 2px;
        }

        .top-settings-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            width: 100%;
            animation: fadeInUp 0.7s ease-out;
        }

        .settings-panel {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(15, 23, 42, 0.65);
            padding: 7px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            width: 100%;
            min-height: 38px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.35);
        }

        .settings-panel:hover {
            background: rgba(15, 23, 42, 0.75);
            border-color: rgba(99, 102, 241, 0.35);
            box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.35);
            transform: translateY(-1px);
        }

        .settings-panel:focus-within {
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 16px rgba(59, 130, 246, 0.18);
        }
        
        .setting-group {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
        }

        .setting-group.group-size-item {
            flex: 0 0 auto;
        }

        .setting-group.flex-fill {
            flex: 1 1 0%;
        }
        
        .setting-group.border-left {
            margin-left: 4px;
            padding-left: 10px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .setting-group label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            user-select: none;
            letter-spacing: 0.1px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .setting-icon {
            width: 14px;
            height: 14px;
            stroke: #94a3b8;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .settings-panel:hover .setting-icon {
            stroke: #60a5fa;
        }

        .setting-group:focus-within .setting-icon {
            stroke: #38bdf8;
            transform: scale(1.15);
        }

        .settings-panel input {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 6px;
            padding: 4px 8px;
            font-family: inherit;
            font-size: 0.82rem;
            transition: all 0.25s ease;
            outline: none;
            width: 100%;
            min-width: 0;
            height: 28px;
        }

        #groupSize {
            width: 58px;
            text-align: center;
        }

        #customSeparator {
            font-family: monospace;
        }

        .settings-panel input:hover:not(:disabled) {
            border-color: rgba(255, 255, 255, 0.25);
            background: #243247;
        }
        
        .settings-panel input:focus {
            border-color: var(--accent);
            background: #1e293b;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        .settings-panel input:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            background: rgba(15, 23, 42, 0.4);
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
            max-width: 520px;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 26px 24px;
            border: 2px dashed rgba(99, 102, 241, 0.35);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(15, 23, 42, 0.5);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .file-upload-label:hover {
            border-color: #60a5fa;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.12) 0%, rgba(15, 23, 42, 0.65) 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -8px rgba(59, 130, 246, 0.25);
        }

        .file-upload-label.drag-over {
            border-color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.35);
        }

        .upload-icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(168, 85, 247, 0.15));
            border: 1px solid rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .file-upload-label:hover .upload-icon-circle {
            transform: translateY(-4px) scale(1.08);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.28), rgba(168, 85, 247, 0.28));
            border-color: #60a5fa;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.35);
        }

        .upload-icon-svg {
            width: 26px;
            height: 26px;
            stroke: #93c5fd;
            transition: all 0.35s ease;
        }

        .file-upload-label:hover .upload-icon-svg {
            stroke: #ffffff;
            transform: translateY(-2px);
        }

        .upload-title {
            font-size: 0.98rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 4px;
            transition: color 0.2s;
        }

        .file-upload-label:hover .upload-title {
            color: #ffffff;
        }

        .upload-subtitle {
            font-size: 0.78rem;
            color: #64748b;
            transition: color 0.2s;
        }

        .file-upload-label:hover .upload-subtitle {
            color: #94a3b8;
        }

        input[type="file"] {
            display: none;
        }

        #fileName {
            margin-top: 10px;
            font-size: 0.82rem;
            color: #38bdf8;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 9999px;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.3);
            animation: pop 0.3s ease-out;
        }

        #fileName:not(:empty) {
            display: inline-flex;
        }

        #fileName:not(:empty)::before {
            content: '📄';
            font-size: 0.85rem;
        }

        .button-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        button {
            padding: 12px 26px;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 9px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }

        button svg {
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        button:active:not(:disabled) {
            transform: scale(0.97) translateY(1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #60a5fa 0%, #1d4ed8 100%);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary:hover:not(:disabled) svg {
            transform: scale(1.15) translateX(2px);
        }

        .btn-primary:disabled {
            background: #334155;
            color: #64748b;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
            border-color: transparent;
            opacity: 0.6;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #34d399 0%, #047857 100%);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-success:hover:not(:disabled) svg {
            animation: bounceDown 0.6s infinite alternate;
        }

        @keyframes bounceDown {
            from { transform: translateY(0); }
            to { transform: translateY(3px); }
        }

        .btn-success:disabled {
            background: #334155;
            color: #64748b;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
            border-color: transparent;
            opacity: 0.6;
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.08);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-danger:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.6);
            color: #fca5a5;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover:not(:disabled) svg {
            animation: trashShake 0.4s ease-in-out infinite alternate;
        }

        @keyframes trashShake {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(-8deg); }
            100% { transform: rotate(8deg); }
        }

        #status {
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #status:not(:empty) {
            padding: 6px 18px;
            border-radius: 9999px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            animation: statusPopIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #status:not(:empty)::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            box-shadow: 0 0 8px currentColor;
            animation: statusPulse 2s infinite ease-in-out;
            flex-shrink: 0;
        }

        @keyframes statusPopIn {
            from { opacity: 0; transform: translateY(6px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .btn-icon {
            background: transparent;
            color: var(--text-muted);
            padding: 6px;
            border-radius: 6px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-icon:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-icon:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-icon.copied {
            color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            animation: pop 0.3s ease-out;
        }
        
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }

        /* Previews Section */
        .previews-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 900px) {
            .top-settings-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            .settings-panel {
                padding: 6px 12px;
                min-height: 36px;
            }
            .stats-bar {
                gap: 6px;
                padding: 6px 12px;
            }
            .stat-chip {
                padding: 4px 10px;
                font-size: 0.72rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            .content-wrapper {
                padding: 14px 10px;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .header {
                margin-bottom: 16px;
            }
            .header p {
                font-size: 0.85rem;
            }
            .controls-card {
                padding: 18px 12px;
                gap: 14px;
            }
            .file-upload-label {
                padding: 16px 12px;
                text-align: center;
            }
            .upload-icon-circle {
                width: 40px;
                height: 40px;
                margin-bottom: 6px;
            }
            .upload-icon-svg {
                width: 20px;
                height: 20px;
            }
            .upload-title {
                font-size: 0.88rem;
            }
            .upload-subtitle {
                font-size: 0.7rem;
            }
            #fileName {
                font-size: 0.74rem;
                padding: 3px 10px;
            }
            .button-group {
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }
            button {
                width: 100%;
                justify-content: center;
                font-size: 0.9rem;
                padding: 9px 16px;
            }

            /* Responsive Settings Row - Always ONE line per panel (2 lines total) */
            .top-settings-row {
                display: flex;
                flex-direction: column;
                gap: 6px;
                width: 100%;
            }
            .settings-panel {
                display: flex;
                flex-direction: row;
                align-items: center;
                width: 100%;
                padding: 5px 10px;
                gap: 6px;
                min-height: 34px;
                border-radius: 8px;
            }
            .setting-group {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 4px;
                min-width: 0;
            }
            .setting-group.border-left {
                border-left: 1px solid rgba(255, 255, 255, 0.1);
                border-top: none;
                margin-left: 2px;
                padding-left: 6px;
                padding-top: 0;
                margin-top: 0;
            }
            .setting-group label {
                font-size: 0.74rem;
                white-space: nowrap;
                flex-shrink: 0;
                gap: 4px;
            }
            .setting-icon {
                width: 12px;
                height: 12px;
            }
            .settings-panel input {
                height: 26px;
                font-size: 0.78rem;
                padding: 2px 6px;
                border-radius: 5px;
            }
            #groupSize {
                width: 48px;
                text-align: center;
            }

            /* Responsive Stats Bar - Small chips fitting in ONE or TWO lines */
            .stats-bar {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 4px 6px;
                padding: 6px 8px;
                border-radius: 10px;
                width: 100%;
            }
            .stat-chip {
                flex: 0 0 auto;
                width: auto;
                min-width: 0;
                padding: 3px 8px;
                font-size: 0.68rem;
                border-radius: 6px;
                gap: 4px;
                height: 25px;
            }
            .stat-chip svg {
                width: 11px;
                height: 11px;
            }
            .stat-divider {
                display: none !important;
            }

            /* Responsive Previews */
            .previews-container {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .preview-content, .editor-wrapper {
                height: 300px;
            }
            .line-numbers {
                width: 32px;
            }
            .line-numbers .line-num {
                font-size: 0.65rem;
                padding-right: 4px;
            }
            .preview-title {
                font-size: 0.92rem;
            }
            .preview-badge {
                font-size: 0.66rem;
            }
            .row-hover-copy {
                display: none !important;
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

        .stats-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 8px 16px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.35);
        }

        .stats-bar:hover {
            border-color: rgba(96, 165, 250, 0.25);
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.5), 0 0 16px rgba(59, 130, 246, 0.12);
        }

        .stat-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.76rem;
            font-weight: 600;
            background: rgba(51, 65, 85, 0.45);
            padding: 5px 12px;
            border-radius: 8px;
            color: #94a3b8;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid transparent;
            cursor: pointer;
            user-select: none;
            position: relative;
            flex: 0 0 auto;
        }

        .stat-chip svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            opacity: 0.85;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stat-chip:hover {
            transform: translateY(-2px) scale(1.05);
        }

        .stat-chip:hover svg {
            transform: scale(1.22) rotate(-6deg);
        }

        .stat-chip .stat-value {
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            display: inline-block;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        /* Color Variants with Vibrant Hover & Glow */
        .stat-chip.lines { 
            color: #60a5fa; 
            background: rgba(59, 130, 246, 0.08);  
            border-color: rgba(59, 130, 246, 0.2); 
        }
        .stat-chip.lines:hover { 
            background: rgba(59, 130, 246, 0.2); 
            border-color: rgba(96, 165, 250, 0.55); 
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35); 
            color: #93c5fd; 
        }

        .stat-chip.accounts { 
            color: #a78bfa; 
            background: rgba(167, 139, 250, 0.08); 
            border-color: rgba(167, 139, 250, 0.2); 
        }
        .stat-chip.accounts:hover { 
            background: rgba(167, 139, 250, 0.2); 
            border-color: rgba(192, 132, 252, 0.55); 
            box-shadow: 0 6px 20px rgba(167, 139, 250, 0.35); 
            color: #d8b4fe; 
        }

        .stat-chip.emails { 
            color: #34d399; 
            background: rgba(52, 211, 153, 0.08);  
            border-color: rgba(52, 211, 153, 0.2); 
        }
        .stat-chip.emails:hover { 
            background: rgba(52, 211, 153, 0.2); 
            border-color: rgba(110, 231, 183, 0.55); 
            box-shadow: 0 6px 20px rgba(52, 211, 153, 0.35); 
            color: #6ee7b7; 
        }

        .stat-chip.users { 
            color: #fbbf24; 
            background: rgba(251, 191, 36, 0.08);  
            border-color: rgba(251, 191, 36, 0.2); 
        }
        .stat-chip.users:hover { 
            background: rgba(251, 191, 36, 0.2); 
            border-color: rgba(252, 211, 77, 0.55); 
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.35); 
            color: #fcd34d; 
        }

        .stat-chip.links { 
            color: #f472b6; 
            background: rgba(244, 114, 182, 0.08); 
            border-color: rgba(244, 114, 182, 0.2); 
        }
        .stat-chip.links:hover { 
            background: rgba(244, 114, 182, 0.2); 
            border-color: rgba(249, 168, 212, 0.55); 
            box-shadow: 0 6px 20px rgba(244, 114, 182, 0.35); 
            color: #fbcfe8; 
        }

        .stat-chip.dupes { 
            color: #fb923c; 
            background: rgba(251, 146, 60, 0.08);  
            border-color: rgba(251, 146, 60, 0.2); 
        }
        .stat-chip.dupes:hover { 
            background: rgba(251, 146, 60, 0.2); 
            border-color: rgba(253, 186, 116, 0.55); 
            box-shadow: 0 6px 20px rgba(251, 146, 60, 0.35); 
            color: #fed7aa; 
        }

        @keyframes numberPop {
            0% { transform: scale(1); }
            40% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        .stat-value.number-pop {
            animation: numberPop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stat-divider {
            display: none;
        }

        .preview-content {
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 16px 16px 0;
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

        /* Editor with line numbers (VS Code style) */
        .editor-wrapper {
            position: relative;
            display: flex;
            height: 400px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3);
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .editor-wrapper:focus-within {
            border-color: var(--accent);
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3), 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        .line-numbers {
            position: relative;
            z-index: 3;
            min-width: 48px;
            width: auto;
            flex-shrink: 0;
            padding: 16px 8px 16px 0;
            background: rgba(15, 23, 42, 0.75);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            user-select: none;
            text-align: right;
            box-sizing: border-box;
        }

        .line-numbers .line-num {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 10px 0 4px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.8rem;
            line-height: calc(0.9rem * 1.5);
            height: calc(0.9rem * 1.5);
            color: #64748b;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            z-index: 6;
        }

        .line-numbers .line-num:hover,
        .line-numbers .line-num.row-hovered {
            color: #93c5fd;
            font-weight: 700;
        }

        .line-numbers .line-num.copied {
            color: var(--success) !important;
            font-weight: 700;
        }

        .line-numbers .line-num.group-num {
            color: #c084fc;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .line-numbers .line-num.group-num:hover,
        .line-numbers .line-num.group-num.row-hovered {
            color: #f5d0fe;
            text-shadow: 0 0 8px rgba(192, 132, 252, 0.5);
        }

        /* Professional Full-Row Active Line Highlight */
        .row-hover-highlight {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: calc(0.9rem * 1.5);
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.22) 0%, rgba(59, 130, 246, 0.12) 40%, rgba(59, 130, 246, 0.04) 100%);
            border-top: 1px solid rgba(96, 165, 250, 0.45);
            border-bottom: 1px solid rgba(96, 165, 250, 0.45);
            border-left: 3px solid #3b82f6;
            pointer-events: none;
            z-index: 1;
            display: none;
            box-sizing: border-box;
            transition: top 0.04s ease-out;
        }

        #afterWrapper .row-hover-highlight {
            background: linear-gradient(90deg, rgba(167, 139, 250, 0.22) 0%, rgba(167, 139, 250, 0.12) 40%, rgba(167, 139, 250, 0.04) 100%);
            border-top: 1px solid rgba(192, 132, 252, 0.45);
            border-bottom: 1px solid rgba(192, 132, 252, 0.45);
            border-left: 3px solid #a855f7;
        }

        /* Floating Row Hover Copy Button */
        .row-hover-copy {
            position: absolute;
            right: 12px;
            top: 0;
            display: none;
            align-items: center;
            gap: 5px;
            padding: 2px 10px;
            height: calc(0.9rem * 1.5 - 4px);
            margin-top: 2px;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(96, 165, 250, 0.35);
            border-radius: 6px;
            color: #e2e8f0;
            font-size: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5), 0 0 8px rgba(59, 130, 246, 0.15);
            transition: all 0.15s ease;
            pointer-events: auto;
        }

        .row-hover-copy:hover {
            background: var(--accent);
            border-color: #93c5fd;
            color: white;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.45);
            transform: translateY(-1px);
        }

        .row-hover-copy.copied {
            background: var(--success) !important;
            border-color: #6ee7b7 !important;
            color: white !important;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.45) !important;
        }

        /* ================= Flash Notification Toast System ================= */
        .flash-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 999999;
            pointer-events: none;
            max-width: 380px;
            width: calc(100vw - 32px);
        }

        @media (max-width: 640px) {
            .flash-toast-container {
                top: 14px;
                right: 14px;
                left: 14px;
                width: auto;
                max-width: none;
            }
        }

        .flash-toast {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px 12px 12px;
            background: rgba(15, 23, 42, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.05);
            pointer-events: auto;
            overflow: hidden;
            user-select: none;
            cursor: pointer;
            animation: flashToastIn 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: transform 0.2s ease, opacity 0.25s ease, max-height 0.3s ease, margin 0.3s ease, padding 0.3s ease;
        }

        .flash-toast.dismissing {
            animation: flashToastOut 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes flashToastIn {
            0% {
                opacity: 0;
                transform: translateX(40px) scale(0.92);
            }
            70% {
                transform: translateX(-4px) scale(1.01);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes flashToastOut {
            0% {
                opacity: 1;
                transform: translateX(0) scale(1);
                max-height: 120px;
                margin-bottom: 0;
            }
            100% {
                opacity: 0;
                transform: translateX(40px) scale(0.9);
                max-height: 0;
                margin-bottom: -10px;
                padding-top: 0;
                padding-bottom: 0;
            }
        }

        @media (max-width: 640px) {
            @keyframes flashToastIn {
                0% {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.94);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
            @keyframes flashToastOut {
                0% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                    max-height: 120px;
                }
                100% {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.9);
                    max-height: 0;
                    margin-bottom: -10px;
                    padding-top: 0;
                    padding-bottom: 0;
                }
            }
        }

        .flash-toast-icon {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.2s ease;
        }

        .flash-toast:hover .flash-toast-icon {
            transform: scale(1.08);
        }

        .flash-toast-icon svg {
            width: 18px;
            height: 18px;
        }

        .flash-toast-body {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .flash-toast-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: 0.2px;
            line-height: 1.25;
        }

        .flash-toast-msg {
            font-size: 0.77rem;
            color: #94a3b8;
            line-height: 1.35;
            word-break: break-word;
        }

        .flash-toast-close {
            flex-shrink: 0;
            background: transparent;
            border: none;
            color: #64748b;
            padding: 2px;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            margin-top: -2px;
            margin-right: -2px;
        }

        .flash-toast-close:hover {
            color: #f1f5f9;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Progress Bar */
        .flash-toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: flashProgress linear forwards;
        }

        .flash-toast:hover .flash-toast-progress {
            animation-play-state: paused;
        }

        @keyframes flashProgress {
            0% { transform: scaleX(1); }
            100% { transform: scaleX(0); }
        }

        /* Variant: Upload */
        .flash-toast.flash-upload {
            border-color: rgba(56, 189, 248, 0.35);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(56, 189, 248, 0.25);
        }
        .flash-toast.flash-upload .flash-toast-icon {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.35);
            color: #38bdf8;
        }
        .flash-toast.flash-upload .flash-toast-title { color: #7dd3fc; }
        .flash-toast.flash-upload .flash-toast-progress { background: #38bdf8; }

        /* Variant: Download */
        .flash-toast.flash-download {
            border-color: rgba(16, 185, 129, 0.35);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(16, 185, 129, 0.25);
        }
        .flash-toast.flash-download .flash-toast-icon {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .flash-toast.flash-download .flash-toast-title { color: #6ee7b7; }
        .flash-toast.flash-download .flash-toast-progress { background: #10b981; }

        /* Variant: Success */
        .flash-toast.flash-success {
            border-color: rgba(16, 185, 129, 0.35);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(16, 185, 129, 0.25);
        }
        .flash-toast.flash-success .flash-toast-icon {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .flash-toast.flash-success .flash-toast-title { color: #6ee7b7; }
        .flash-toast.flash-success .flash-toast-progress { background: #10b981; }

        /* Variant: Copy */
        .flash-toast.flash-copy {
            border-color: rgba(168, 85, 247, 0.35);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(168, 85, 247, 0.25);
        }
        .flash-toast.flash-copy .flash-toast-icon {
            background: rgba(168, 85, 247, 0.15);
            border-color: rgba(168, 85, 247, 0.35);
            color: #c084fc;
        }
        .flash-toast.flash-copy .flash-toast-title { color: #d8b4fe; }
        .flash-toast.flash-copy .flash-toast-progress { background: #a855f7; }

        /* Variant: Warning */
        .flash-toast.flash-warning {
            border-color: rgba(245, 158, 11, 0.38);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(245, 158, 11, 0.25);
        }
        .flash-toast.flash-warning .flash-toast-icon {
            background: rgba(245, 158, 11, 0.15);
            border-color: rgba(245, 158, 11, 0.35);
            color: #fbbf24;
        }
        .flash-toast.flash-warning .flash-toast-title { color: #fde68a; }
        .flash-toast.flash-warning .flash-toast-progress { background: #f59e0b; }

        /* Variant: Error */
        .flash-toast.flash-error {
            border-color: rgba(244, 63, 94, 0.4);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(244, 63, 94, 0.25);
        }
        .flash-toast.flash-error .flash-toast-icon {
            background: rgba(244, 63, 94, 0.15);
            border-color: rgba(244, 63, 94, 0.35);
            color: #fb7185;
        }
        .flash-toast.flash-error .flash-toast-title { color: #fecdd3; }
        .flash-toast.flash-error .flash-toast-progress { background: #f43f5e; }

        /* Variant: Info */
        .flash-toast.flash-info {
            border-color: rgba(99, 102, 241, 0.35);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.65), 0 0 22px -2px rgba(99, 102, 241, 0.25);
        }
        .flash-toast.flash-info .flash-toast-icon {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.35);
            color: #818cf8;
        }
        .flash-toast.flash-info .flash-toast-title { color: #a5b4fc; }
        .flash-toast.flash-info .flash-toast-progress { background: #6366f1; }

        .mode-toggle-btn {
            background: rgba(30, 41, 59, 0.6);
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mode-toggle-btn:hover {
            color: var(--text-main);
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(51, 65, 85, 0.7);
        }

        .editor-wrapper textarea.preview-content {
            background: transparent;
            position: relative;
            z-index: 2;
            border: none;
            border-radius: 0;
            box-shadow: none;
            height: 100%;
            flex: 1;
            padding: 16px 16px 16px 12px;
            resize: none;
        }

        .editor-wrapper textarea.preview-content:focus {
            border: none;
            box-shadow: none;
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
            <div class="header-badge">
                <span class="badge-dot"></span>
                <span>Smart Formatter Suite</span>
            </div>
            <h1>Very Smart Formatter</h1>
            <p>Advanced data parsing, custom grouping separators, and smart editing tools.</p>
        </div>

    <div class="main-container">
        <!-- Controls -->
        <div class="glass-panel controls-card">
            <div class="file-upload-wrapper">
                <label for="fileInput" class="file-upload-label" id="fileDropZone">
                    <div class="upload-icon-circle">
                        <svg class="upload-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <span class="upload-title">Click or Drag & Drop .txt file</span>
                    <span class="upload-subtitle">Auto-formats accounts, cleans tokens & caches locally</span>
                    <span id="fileName"></span>
                </label>
                <input type="file" id="fileInput" accept=".txt">
            </div>

            <div class="button-group">
                <button id="formatBtn" class="btn-primary" disabled onclick="processFile()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span>Process File</span>
                </button>
                
                <button id="downloadBtn" class="btn-success" disabled onclick="downloadFormattedFile()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Download Output</span>
                </button>
                
                <button id="clearBtn" class="btn-danger" onclick="showClearModal()" title="Clear cache and reset workspace">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    <span>Clear</span>
                </button>
            </div>
            
            <div id="status"></div>
        </div>

        <!-- Settings Row (Above the states) -->
        <div class="top-settings-row">
            <!-- Grouping Settings -->
            <div class="settings-panel">
                <div class="setting-group group-size-item">
                    <label for="groupSize">
                        <svg class="setting-icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                        Group Size:
                    </label>
                    <input type="number" id="groupSize" value="100" min="1" oninput="debouncedProcessFile()">
                </div>
                <div class="setting-group border-left flex-fill">
                    <label for="customSeparator">
                        <svg class="setting-icon" viewBox="0 0 24 24"><line x1="3" y1="12" x2="7" y2="12"/><line x1="10" y1="12" x2="14" y2="12"/><line x1="17" y1="12" x2="21" y2="12"/></svg>
                        Separator:
                    </label>
                    <input type="text" id="customSeparator" value="-----------------------------" oninput="debouncedProcessFile()">
                </div>
            </div>

            <!-- Replace Settings -->
            <div class="settings-panel">
                <div class="setting-group flex-fill">
                    <label for="findInput">
                        <svg class="setting-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Find:
                    </label>
                    <input type="text" id="findInput" placeholder="Text to find..." disabled oninput="debouncedProcessFile()">
                </div>
                <div class="setting-group border-left flex-fill">
                    <label for="replaceInput">
                        <svg class="setting-icon" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                        Replace:
                    </label>
                    <input type="text" id="replaceInput" placeholder="Replacement..." disabled oninput="debouncedProcessFile()">
                </div>
            </div>
        </div>

        <!-- Centered Stats Bar -->
        <div class="stats-bar glass-panel" id="centralStatsBar">
            <span class="stat-chip lines" title="Raw Input Lines">
                <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span class="stat-value" id="beforeLinesVal">0</span> Lines
            </span>
            <span class="stat-divider"></span>
            <span class="stat-chip accounts" title="Processed Accounts">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="stat-value" id="afterAccountsVal">0</span> Accounts
            </span>
            <span class="stat-chip emails" title="Detected Email Addresses">
                <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span class="stat-value" id="afterEmailsVal">0</span> Emails
            </span>
            <span class="stat-chip users" title="Detected Usernames">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="stat-value" id="afterUsersVal">0</span> Users
            </span>
            <span class="stat-chip links" title="Two-Factor Authentication Links">
                <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                <span class="stat-value" id="afterLinksVal">0</span> 2FA
            </span>
            <span class="stat-divider"></span>
            <span class="stat-chip lines" title="Formatted Output Lines">
                <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span class="stat-value" id="afterTotalLinesVal">0</span> Output Lines
            </span>
            <span class="stat-chip dupes" id="afterDupesChip" style="display: none;" title="Duplicate Accounts">
                <svg viewBox="0 0 24 24"><rect x="2" y="6" width="13" height="13" rx="2" ry="2"/><path d="M9 2h9a2 2 0 0 1 2 2v9"/></svg>
                <span class="stat-value" id="afterDupesVal">0</span> Duplicates
            </span>
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
                <div class="editor-wrapper" id="beforeWrapper">
                    <div class="row-hover-highlight" id="beforeRowHighlight"></div>
                    <div class="line-numbers" id="beforeLineNums"></div>
                    <textarea class="preview-content before" id="beforePreview" wrap="off" placeholder="Paste your raw content here, or upload a file..." oninput="handleRawInput()"></textarea>
                    <button class="row-hover-copy" id="beforeRowCopy" title="Copy this line">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span>Copy</span>
                    </button>
                </div>
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
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button id="gutterModeBtn" class="mode-toggle-btn" onclick="toggleGutterMode()" title="Switch between Group counter and Line counter">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            </svg>
                            <span id="gutterModeLabel">Group #</span>
                        </button>
                        <button id="copyBtn" class="btn-icon" onclick="copyOutput()" title="Copy to Clipboard" disabled>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                        <span class="preview-badge" id="afterLinesBadge">0 Accounts</span>
                    </div>
                </div>
                
                <div class="editor-wrapper" id="afterWrapper">
                    <div class="row-hover-highlight" id="afterRowHighlight"></div>
                    <div class="line-numbers" id="afterLineNums"></div>
                    <textarea class="preview-content" id="afterPreview" wrap="off" placeholder="Awaiting processing... Feel free to edit this text before downloading!" oninput="saveTextareaCache()"></textarea>
                    <button class="row-hover-copy" id="afterRowCopy" title="Copy this line">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span>Copy</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- End content-wrapper -->

    <!-- Flash Toast Notification Container -->
    <div id="flashToastContainer" class="flash-toast-container"></div>

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

        // Line numbers & Group numbering (VS Code style)
        let afterGutterMode = 'group'; // Default: 'group' (1, 2, 3 per group)

        // Flash Toast Notification System
        function showToast(message, type = 'info', title = null, duration = 3200) {
            let container = document.getElementById('flashToastContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'flashToastContainer';
                container.className = 'flash-toast-container';
                document.body.appendChild(container);
            }

            // Auto-detect type if generic
            if (type === 'info') {
                const lower = String(message).toLowerCase();
                if (lower.includes('copied') || lower.includes('copy')) type = 'copy';
                else if (lower.includes('error') || lower.includes('failed') || lower.includes('fail')) type = 'error';
                else if (lower.includes('warn') || lower.includes('please') || lower.includes('required') || lower.includes('empty')) type = 'warning';
                else if (lower.includes('download')) type = 'download';
                else if (lower.includes('upload') || lower.includes('loaded')) type = 'upload';
                else if (lower.includes('success') || lower.includes('formatted')) type = 'success';
            }

            // Default title if not provided
            if (!title) {
                const titles = {
                    upload: 'File Uploaded',
                    download: 'File Downloaded',
                    success: 'Action Successful',
                    copy: 'Copied to Clipboard',
                    warning: 'Attention',
                    error: 'Action Failed',
                    info: 'Notification'
                };
                title = titles[type] || 'Notification';
            }

            const icons = {
                upload: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
                download: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
                success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
                copy: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>',
                warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            const toast = document.createElement('div');
            toast.className = `flash-toast flash-${type}`;
            toast.innerHTML = `
                <div class="flash-toast-icon">
                    ${icons[type] || icons.info}
                </div>
                <div class="flash-toast-body">
                    <div class="flash-toast-title">${title}</div>
                    <div class="flash-toast-msg">${message}</div>
                </div>
                <button type="button" class="flash-toast-close" title="Dismiss">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="flash-toast-progress" style="animation-duration: ${duration}ms;"></div>
            `;

            // Max 4 toasts simultaneously
            while (container.children.length >= 4) {
                dismissToast(container.firstElementChild);
            }

            container.appendChild(toast);

            let timer = setTimeout(() => {
                dismissToast(toast);
            }, duration);

            const closeBtn = toast.querySelector('.flash-toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    clearTimeout(timer);
                    dismissToast(toast);
                });
            }

            toast.addEventListener('click', () => {
                clearTimeout(timer);
                dismissToast(toast);
            });
        }

        function dismissToast(toastEl) {
            if (!toastEl || toastEl.classList.contains('dismissing')) return;
            toastEl.classList.add('dismissing');
            setTimeout(() => {
                if (toastEl && toastEl.parentNode) {
                    toastEl.parentNode.removeChild(toastEl);
                }
            }, 260);
        }

        // Copy a single row/line
        function copyRowText(textareaId, lineIndex, btnEl, lineNumEl) {
            const textarea = document.getElementById(textareaId);
            if (!textarea) return;
            const lines = textarea.value.split('\n');
            if (lineIndex < 0 || lineIndex >= lines.length) return;
            const textToCopy = lines[lineIndex];

            navigator.clipboard.writeText(textToCopy).then(() => {
                if (btnEl) {
                    btnEl.classList.add('copied');
                    const origHTML = btnEl.innerHTML;
                    btnEl.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Copied!</span>`;
                    setTimeout(() => {
                        btnEl.classList.remove('copied');
                        btnEl.innerHTML = origHTML;
                    }, 1200);
                }
                if (lineNumEl) {
                    lineNumEl.classList.add('copied');
                    const origText = lineNumEl.innerHTML;
                    lineNumEl.innerHTML = '✓';
                    setTimeout(() => {
                        lineNumEl.classList.remove('copied');
                        lineNumEl.innerHTML = origText;
                    }, 1200);
                }
                showToast(`Copied Line ${lineIndex + 1} to clipboard`, 'copy', 'Line Copied', 1800);
            }).catch(err => {
                console.error('Failed to copy row', err);
                showToast("Failed to copy line to clipboard.", "error", "Copy Failed");
            });
        }

        // Copy an entire account group from afterPreview
        function copyAccountGroup(startLineIdx) {
            const textarea = document.getElementById('afterPreview');
            if (!textarea) return;
            const lines = textarea.value.split('\n');
            if (startLineIdx < 0 || startLineIdx >= lines.length) return;

            const sepInput = document.getElementById('customSeparator');
            const customSeparator = sepInput ? sepInput.value.trim() : '';

            let accountLines = [];
            for (let i = startLineIdx; i < lines.length; i++) {
                const line = lines[i];
                const trimmed = line.trim();
                const isSep = customSeparator && (trimmed === customSeparator || trimmed.startsWith('---'));
                if (trimmed === '' || isSep) break;
                accountLines.push(line);
            }

            const textToCopy = accountLines.join('\n');
            if (!textToCopy) return;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const gutter = document.getElementById('afterLineNums');
                const lineEl = gutter ? gutter.querySelector(`.line-num[data-line="${startLineIdx}"]`) : null;
                if (lineEl) {
                    lineEl.classList.add('copied');
                    const orig = lineEl.innerHTML;
                    lineEl.innerHTML = '✓';
                    setTimeout(() => {
                        lineEl.classList.remove('copied');
                        lineEl.innerHTML = orig;
                    }, 1200);
                }
                showToast('Full account details copied to clipboard!', 'copy', 'Account Copied', 2000);
            }).catch(err => {
                console.error('Failed to copy account', err);
                showToast("Failed to copy account details.", "error", "Copy Failed");
            });
        }

        // Smooth pop animation on number change
        function setStatVal(id, val) {
            const el = document.getElementById(id);
            if (!el) return;
            const strVal = String(val);
            if (el.textContent !== strVal) {
                el.textContent = strVal;
                el.classList.remove('number-pop');
                void el.offsetWidth; // trigger reflow
                el.classList.add('number-pop');
            }
        }

        // Multi-color Theme Palette for Row Hover
        const hoverPalettes = [
            { border: '#3b82f6', borderGlow: 'rgba(96, 165, 250, 0.55)', bg: 'rgba(59, 130, 246, 0.22)', text: '#93c5fd' },  // Blue
            { border: '#a855f7', borderGlow: 'rgba(192, 132, 252, 0.55)', bg: 'rgba(168, 85, 247, 0.22)', text: '#d8b4fe' }, // Purple
            { border: '#10b981', borderGlow: 'rgba(52, 211, 153, 0.55)', bg: 'rgba(16, 185, 129, 0.22)', text: '#6ee7b7' },  // Emerald
            { border: '#f59e0b', borderGlow: 'rgba(251, 191, 36, 0.55)', bg: 'rgba(245, 158, 11, 0.22)', text: '#fcd34d' },  // Amber
            { border: '#06b6d4', borderGlow: 'rgba(34, 211, 238, 0.55)', bg: 'rgba(6, 182, 212, 0.22)', text: '#67e8f9' },   // Cyan
            { border: '#ec4899', borderGlow: 'rgba(244, 114, 182, 0.55)', bg: 'rgba(236, 72, 153, 0.22)', text: '#fbcfe8' }, // Pink
            { border: '#f97316', borderGlow: 'rgba(251, 146, 60, 0.55)', bg: 'rgba(249, 115, 22, 0.22)', text: '#fed7aa' }   // Orange
        ];

        // Row hover tracking & floating copy button
        function attachRowHover(wrapperId, textareaId, gutterId, copyBtnId, highlightId) {
            const wrapper = document.getElementById(wrapperId);
            const textarea = document.getElementById(textareaId);
            const gutter = document.getElementById(gutterId);
            const copyBtn = document.getElementById(copyBtnId);
            const highlightEl = document.getElementById(highlightId);
            if (!wrapper || !textarea || !gutter || !copyBtn) return;

            let activeLineIndex = -1;

            function updateHover(e) {
                const text = textarea.value;
                if (!text) {
                    copyBtn.style.display = 'none';
                    if (highlightEl) highlightEl.style.display = 'none';
                    return;
                }

                const lines = text.split('\n');
                const firstLineEl = gutter.querySelector('.line-num');
                const lineHeight = firstLineEl ? firstLineEl.getBoundingClientRect().height : 21.6;
                const rect = textarea.getBoundingClientRect();

                // Calculate hovered line index
                const relativeY = e.clientY - rect.top - 16 + textarea.scrollTop;
                const lineIdx = Math.floor(relativeY / lineHeight);

                if (lineIdx >= 0 && lineIdx < lines.length && lines[lineIdx].trim().length > 0) {
                    activeLineIndex = lineIdx;
                    const theme = hoverPalettes[lineIdx % hoverPalettes.length];

                    // Highlight line number in gutter with custom theme color
                    gutter.querySelectorAll('.line-num').forEach(el => {
                        const elIdx = parseInt(el.getAttribute('data-line'), 10);
                        if (elIdx === lineIdx) {
                            el.classList.add('row-hovered');
                            el.style.color = theme.text;
                            el.style.textShadow = `0 0 8px ${theme.borderGlow}`;
                        } else {
                            el.classList.remove('row-hovered');
                            el.style.color = '';
                            el.style.textShadow = '';
                        }
                    });

                    // Position highlight bar and floating copy button with dynamic colors
                    const topPos = 16 + (lineIdx * lineHeight) - textarea.scrollTop;
                    if (topPos >= 4 && topPos <= textarea.clientHeight - 20) {
                        if (highlightEl) {
                            highlightEl.style.display = 'block';
                            highlightEl.style.top = `${topPos}px`;
                            highlightEl.style.borderLeftColor = theme.border;
                            highlightEl.style.borderTopColor = theme.borderGlow;
                            highlightEl.style.borderBottomColor = theme.borderGlow;
                            highlightEl.style.background = `linear-gradient(90deg, ${theme.bg} 0%, ${theme.bg.replace('0.22', '0.08')} 40%, ${theme.bg.replace('0.22', '0.02')} 100%)`;
                        }
                        copyBtn.style.display = 'inline-flex';
                        copyBtn.style.top = `${topPos}px`;
                        copyBtn.style.borderColor = theme.border;
                        copyBtn.style.boxShadow = `0 4px 14px rgba(0, 0, 0, 0.5), 0 0 10px ${theme.borderGlow}`;
                    } else {
                        if (highlightEl) highlightEl.style.display = 'none';
                        copyBtn.style.display = 'none';
                    }
                } else {
                    activeLineIndex = -1;
                    copyBtn.style.display = 'none';
                    if (highlightEl) highlightEl.style.display = 'none';
                    gutter.querySelectorAll('.line-num').forEach(el => {
                        el.classList.remove('row-hovered');
                        el.style.color = '';
                        el.style.textShadow = '';
                    });
                }
            }

            wrapper.addEventListener('mousemove', updateHover);

            wrapper.addEventListener('mouseleave', () => {
                activeLineIndex = -1;
                copyBtn.style.display = 'none';
                if (highlightEl) highlightEl.style.display = 'none';
                gutter.querySelectorAll('.line-num').forEach(el => {
                    el.classList.remove('row-hovered');
                    el.style.color = '';
                    el.style.textShadow = '';
                });
            });

            textarea.addEventListener('scroll', () => {
                copyBtn.style.display = 'none';
                if (highlightEl) highlightEl.style.display = 'none';
            });

            copyBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (activeLineIndex !== -1) {
                    const lineNumEl = gutter.querySelector(`.line-num[data-line="${activeLineIndex}"]`);
                    copyRowText(textareaId, activeLineIndex, copyBtn, lineNumEl);
                }
            });

            // Gutter direct click to copy
            gutter.addEventListener('click', (e) => {
                const lineEl = e.target.closest('.line-num');
                if (!lineEl) return;
                const lineIdx = parseInt(lineEl.getAttribute('data-line'), 10);
                if (!isNaN(lineIdx)) {
                    if (lineEl.classList.contains('group-num') && textareaId === 'afterPreview' && afterGutterMode === 'group') {
                        copyAccountGroup(lineIdx);
                    } else {
                        copyRowText(textareaId, lineIdx, copyBtn, lineEl);
                    }
                }
            });
        }

        function toggleGutterMode() {
            afterGutterMode = (afterGutterMode === 'group') ? 'line' : 'group';
            const label = document.getElementById('gutterModeLabel');
            if (label) {
                label.textContent = afterGutterMode === 'group' ? 'Group #' : 'Line #';
            }
            updateAfterLineNumbers();
        }

        function updateBeforeLineNumbers() {
            const textarea = document.getElementById('beforePreview');
            const gutter = document.getElementById('beforeLineNums');
            if (!textarea || !gutter) return;
            const text = textarea.value;
            const lines = text ? text.split('\n') : [''];
            let html = '';
            for (let i = 0; i < lines.length; i++) {
                html += `<span class="line-num" data-line="${i}" title="Click to copy line ${i + 1}">${i + 1}</span>`;
            }
            gutter.innerHTML = html;
        }

        function updateAfterLineNumbers() {
            const textarea = document.getElementById('afterPreview');
            const gutter = document.getElementById('afterLineNums');
            if (!textarea || !gutter) return;

            const text = textarea.value;
            if (!text) {
                gutter.innerHTML = '<span class="line-num" data-line="0">&nbsp;</span>';
                return;
            }

            const lines = text.split('\n');
            let html = '';

            if (afterGutterMode === 'line') {
                for (let i = 0; i < lines.length; i++) {
                    html += `<span class="line-num" data-line="${i}" title="Click to copy line ${i + 1}">${i + 1}</span>`;
                }
            } else {
                // Group Mode: each account group is numbered 1, 2, 3...
                let groupNum = 0;
                let inGroup = false;
                const sepInput = document.getElementById('customSeparator');
                const customSeparator = sepInput ? sepInput.value.trim() : '';

                for (let i = 0; i < lines.length; i++) {
                    const trimmed = lines[i].trim();
                    const isSep = customSeparator && (trimmed === customSeparator || trimmed.startsWith('---'));
                    const isEmpty = trimmed === '';

                    if (!isEmpty && !isSep) {
                        if (!inGroup) {
                            groupNum++;
                            inGroup = true;
                            html += `<span class="line-num group-num" data-line="${i}" title="Click to copy Account #${groupNum}">${groupNum}</span>`;
                        } else {
                            html += `<span class="line-num" data-line="${i}" title="Click to copy line ${i + 1}">&nbsp;</span>`;
                        }
                    } else {
                        inGroup = false;
                        html += `<span class="line-num" data-line="${i}">&nbsp;</span>`;
                    }
                }
            }
            gutter.innerHTML = html;
        }

        function updateLineNumbers(textarea, gutter) {
            if (textarea && textarea.id === 'afterPreview') {
                updateAfterLineNumbers();
            } else {
                updateBeforeLineNumbers();
            }
        }

        function syncScroll(textarea, gutter) {
            gutter.scrollTop = textarea.scrollTop;
        }

        function setupEditor(textareaId, gutterId) {
            const textarea = document.getElementById(textareaId);
            const gutter = document.getElementById(gutterId);
            textarea.addEventListener('scroll', () => syncScroll(textarea, gutter));
            textarea.addEventListener('input', () => updateLineNumbers(textarea, gutter));
            updateLineNumbers(textarea, gutter);
        }

        // Initialize editors after DOM loads
        window.addEventListener('DOMContentLoaded', () => {
            setupEditor('beforePreview', 'beforeLineNums');
            setupEditor('afterPreview', 'afterLineNums');
            attachRowHover('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeRowCopy', 'beforeRowHighlight');
            attachRowHover('afterWrapper', 'afterPreview', 'afterLineNums', 'afterRowCopy', 'afterRowHighlight');
        });
        
        const formatBtn = document.getElementById('formatBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const copyBtn = document.getElementById('copyBtn');
        
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
                beforePreview.value = rawContent;
                const cachedLineCount = rawContent.split(/\r?\n/).length;
                beforeLinesBadge.textContent = `${cachedLineCount} Lines`;
                setStatVal('beforeLinesVal', cachedLineCount);
                
                fileNameDisplay.textContent = "Loaded from cache";
                formatBtn.disabled = false;
                toggleSmartTools(true);
                
                // If they had manual edits, restore them. Otherwise re-process.
                if (savedAfter) {
                    afterPreview.value = savedAfter;
                    updateLineNumbers(afterPreview, document.getElementById('afterLineNums'));
                    downloadBtn.disabled = false;
                    copyBtn.disabled = false;
                    statusDiv.innerText = "Restored from cache.";
                    statusDiv.style.color = "var(--text-muted)";
                    updateAfterStats(savedAfter);
                } else {
                    processFile(true);
                }
                updateLineNumbers(beforePreview, document.getElementById('beforeLineNums'));
                showToast("Restored previous session from cache.", "info", "Session Restored", 2200);
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
            updateAfterStats(afterPreview.value);
        }

        function updateAfterStats(text) {
            if (!text || !text.trim()) {
                afterLinesBadge.textContent = "0 Accounts";
                setStatVal('afterAccountsVal', 0);
                setStatVal('afterEmailsVal', 0);
                setStatVal('afterUsersVal', 0);
                setStatVal('afterLinksVal', 0);
                setStatVal('afterTotalLinesVal', 0);
                const dupesChip = document.getElementById('afterDupesChip');
                if (dupesChip) dupesChip.style.display = 'none';
                setStatVal('afterDupesVal', 0);
                return;
            }

            const savedStats = JSON.parse(localStorage.getItem('vsf_stats') || 'null');
            const lines = text.split(/\r?\n/);
            const totalLines = lines.length;

            const emailMatches = text.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g) || [];
            const twoFaMatches = text.match(/https:\/\/2fa-x\.com\/[A-Za-z0-9_-]+/g) || [];

            const seenEmails = new Set();
            let dupeCount = 0;
            for (let em of emailMatches) {
                const low = em.toLowerCase();
                if (seenEmails.has(low)) dupeCount++;
                else seenEmails.add(low);
            }

            let accounts = emailMatches.length;
            let emails = emailMatches.length;
            let twoFa = twoFaMatches.length;
            let users = accounts;
            let duplicates = dupeCount;

            if (savedStats && (savedStats.outputLines === totalLines || savedStats.accounts === accounts)) {
                accounts = savedStats.accounts ?? accounts;
                emails = savedStats.emails ?? emails;
                users = savedStats.users ?? users;
                twoFa = savedStats.twoFa ?? twoFa;
                duplicates = savedStats.duplicates ?? duplicates;
            } else {
                const newStats = {
                    accounts,
                    emails,
                    users,
                    twoFa,
                    duplicates,
                    outputLines: totalLines
                };
                localStorage.setItem('vsf_stats', JSON.stringify(newStats));
            }

            afterLinesBadge.textContent = `${accounts} Accounts`;
            setStatVal('afterAccountsVal', accounts);
            setStatVal('afterEmailsVal', emails);
            setStatVal('afterUsersVal', users);
            setStatVal('afterLinksVal', twoFa);
            setStatVal('afterTotalLinesVal', totalLines);

            const dupesChip = document.getElementById('afterDupesChip');
            if (dupesChip) {
                if (duplicates > 0) {
                    dupesChip.style.display = 'inline-flex';
                    setStatVal('afterDupesVal', duplicates);
                } else {
                    dupesChip.style.display = 'none';
                }
            }
        }

        function confirmClearWorkspace() {
            closeClearModal();
            
            localStorage.removeItem('vsf_raw');
            localStorage.removeItem('vsf_settings');
            localStorage.removeItem('vsf_after');
            localStorage.removeItem('vsf_stats');
            
            rawContent = "";
            beforePreview.value = "";
            afterPreview.value = "";
            updateLineNumbers(beforePreview, document.getElementById('beforeLineNums'));
            updateLineNumbers(afterPreview, document.getElementById('afterLineNums'));
            beforeLinesBadge.textContent = "0 Lines";
            afterLinesBadge.textContent = "0 Accounts";
            setStatVal('beforeLinesVal', 0);
            setStatVal('afterAccountsVal', 0);
            setStatVal('afterEmailsVal', 0);
            setStatVal('afterUsersVal', 0);
            setStatVal('afterLinksVal', 0);
            setStatVal('afterTotalLinesVal', 0);
            document.getElementById('afterDupesChip').style.display = 'none';
            setStatVal('afterDupesVal', 0);
            
            document.getElementById('fileInput').value = "";
            fileNameDisplay.textContent = "";
            
            document.getElementById('groupSize').value = "100";
            document.getElementById('customSeparator').value = "-----------------------------";
            document.getElementById('findInput').value = "";
            document.getElementById('replaceInput').value = "";
            
            formatBtn.disabled = true;
            downloadBtn.disabled = true;
            copyBtn.disabled = true;
            toggleSmartTools(false);
            
            statusDiv.innerText = "Workspace cleared.";
            statusDiv.style.color = "var(--text-muted)";
            showToast("Workspace cleared and local cache reset.", "info", "Workspace Cleared");
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

        function handleRawInput() {
            rawContent = beforePreview.value;
            const lineCount = rawContent ? rawContent.split(/\r?\n/).length : 0;
            beforeLinesBadge.textContent = `${lineCount} Lines`;
            setStatVal('beforeLinesVal', lineCount);
            
            if (rawContent) {
                formatBtn.disabled = false;
                toggleSmartTools(true);
                debouncedProcessFile();
            } else {
                formatBtn.disabled = true;
                toggleSmartTools(false);
                afterPreview.value = "";
                updateLineNumbers(afterPreview, document.getElementById('afterLineNums'));
                localStorage.removeItem('vsf_after');
                localStorage.removeItem('vsf_stats');
                afterLinesBadge.textContent = "0 Accounts";
                setStatVal('afterAccountsVal', 0);
                setStatVal('afterEmailsVal', 0);
                setStatVal('afterUsersVal', 0);
                setStatVal('afterLinksVal', 0);
                setStatVal('afterTotalLinesVal', 0);
                document.getElementById('afterDupesChip').style.display = 'none';
                setStatVal('afterDupesVal', 0);
                downloadBtn.disabled = true;
                copyBtn.disabled = true;
                statusDiv.innerText = "";
            }
        }

        // File selection event listener
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                fileNameDisplay.textContent = file.name;

                if (file.size === 0) {
                    showToast(`The selected file "${file.name}" is empty (0 bytes).`, 'warning', 'Empty File');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    rawContent = e.target.result;
                    beforePreview.value = rawContent;
                    updateLineNumbers(beforePreview, document.getElementById('beforeLineNums'));
                    
                    const lineCount = rawContent.split(/\r?\n/).length;
                    beforeLinesBadge.textContent = `${lineCount} Lines`;
                    setStatVal('beforeLinesVal', lineCount);
                    
                    // Reset after state
                    afterPreview.value = "";
                    localStorage.removeItem('vsf_after');
                    localStorage.removeItem('vsf_stats');
                    afterLinesBadge.textContent = "0 Accounts";
                    setStatVal('afterAccountsVal', 0);
                    setStatVal('afterEmailsVal', 0);
                    setStatVal('afterUsersVal', 0);
                    setStatVal('afterLinksVal', 0);
                    setStatVal('afterTotalLinesVal', 0);
                    document.getElementById('afterDupesChip').style.display = 'none';
                    setStatVal('afterDupesVal', 0);
                    downloadBtn.disabled = true;
                    copyBtn.disabled = true;
                    toggleSmartTools(true);
                    
                    // Enable process button
                    formatBtn.disabled = false;
                    
                    // Flash notification on file upload
                    showToast(`"${file.name}" uploaded successfully (${lineCount} lines).`, 'upload', 'File Uploaded');

                    // Save and Auto-process
                    saveToCache();
                    processFile(true);
                };
                reader.readAsText(file);
            } else {
                fileNameDisplay.textContent = "";
                formatBtn.disabled = true;
                toggleSmartTools(false);
            }
        });

        // Flash notification when user pastes directly into raw input
        beforePreview.addEventListener('paste', () => {
            setTimeout(() => {
                const count = beforePreview.value ? beforePreview.value.split(/\r?\n/).length : 0;
                if (count > 0) {
                    showToast(`Pasted ${count} lines into Raw Input.`, 'info', 'Content Pasted', 2200);
                }
            }, 60);
        });

        // Drag and drop event listeners
        const dropZone = document.getElementById('fileDropZone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('drag-over');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('drag-over');
                }, false);
            });

            dropZone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    fileInput.files = dt.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            }, false);
        }

        function processFile(isAuto = false) {
            if (!rawContent) {
                if (!isAuto) showToast("Please select or upload a file first.", "warning", "File Required");
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
                let emailCount = 0;
                let usernameCount = 0;
                let twoFaCount = 0;
                const seenEmails = new Set();
                let duplicateCount = 0;

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
                        let rawPass = (emailIndex + 1 < parts.length) ? parts[emailIndex + 1].trim() : "";
        // Remove unwanted token pattern if present
        let emailPass = rawPass.replace(/\|M\.C\d+_BAY[^|]*\|[\w-]+$/g, "").trim();
                        
                        // Smart 2FA Extraction: 
                        // Search for a 16-64 character uppercase alphanumeric string (typical base32 secret)
                        // strictly after the email to avoid matching passwords.
                        let twoFaCode = "";
                        if (emailIndex + 1 < parts.length) {
                            let afterEmailStr = parts.slice(emailIndex + 1).join(bestDelimiter);
                            let match = afterEmailStr.match(/\b[A-Z0-9]{16,64}\b/);
                            if (match) {
                                twoFaCode = match[0];
                            }
                        }

                        let accountString = `${username}\n${password}\n${email}\n${emailPass}\nhttps://2fa-x.com/${twoFaCode}`;
                        currentGroup.push(accountString);
                        successCount++;
                        if (email && email !== '') {
                            emailCount++;
                            const emailLower = email.toLowerCase();
                            if (seenEmails.has(emailLower)) {
                                duplicateCount++;
                            } else {
                                seenEmails.add(emailLower);
                            }
                        }
                        if (username && username !== 'UnknownUser') usernameCount++;
                        if (twoFaCode && twoFaCode !== '') twoFaCount++;
                        
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
                    updateLineNumbers(afterPreview, document.getElementById('afterLineNums'));
                    downloadBtn.disabled = true;
                    showToast("Could not detect any valid accounts in this file.", "error", "Formatting Failed");
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
                updateLineNumbers(afterPreview, document.getElementById('afterLineNums'));
                afterLinesBadge.textContent = `${successCount} Accounts`;
                
                // Update stat chips with pop animation
                setStatVal('afterAccountsVal', successCount);
                setStatVal('afterEmailsVal', emailCount);
                setStatVal('afterUsersVal', usernameCount);
                setStatVal('afterLinksVal', twoFaCount);
                const totalOutputLines = finalText.split(/\r?\n/).length;
                setStatVal('afterTotalLinesVal', totalOutputLines);
                
                // Show/hide duplicates chip
                const dupesChip = document.getElementById('afterDupesChip');
                if (duplicateCount > 0) {
                    dupesChip.style.display = 'inline-flex';
                    setStatVal('afterDupesVal', duplicateCount);
                } else {
                    dupesChip.style.display = 'none';
                }

                // Cache calculated statistics for session restoration
                const statsData = {
                    accounts: successCount,
                    emails: emailCount,
                    users: usernameCount,
                    twoFa: twoFaCount,
                    duplicates: duplicateCount,
                    outputLines: totalOutputLines
                };
                localStorage.setItem('vsf_stats', JSON.stringify(statsData));
                
                downloadBtn.disabled = false;
                copyBtn.disabled = false;
                
                let msg = `Success! Processed ${successCount} accounts (Grouped by ${groupSize}).`;
                if (replacedCount > 0) {
                    msg += ` Replaced ${replacedCount} occurrence(s).`;
                }
                
                statusDiv.innerText = msg;
                statusDiv.style.color = "var(--success)";

                if (!isAuto) {
                    let flashMsg = `Formatted ${successCount} accounts into ${groups.length} group${groups.length > 1 ? 's' : ''}.`;
                    if (replacedCount > 0) flashMsg += ` (${replacedCount} replacements)`;
                    showToast(flashMsg, "success", "Processing Complete");
                }
                
            }, 50);
        }

        function copyOutput() {
            const contentToCopy = afterPreview.value;
            if (!contentToCopy || !contentToCopy.trim()) {
                showToast("No formatted output to copy! Please process a file first.", "warning", "Nothing to Copy");
                return;
            }
            
            navigator.clipboard.writeText(contentToCopy).then(() => {
                const btn = document.getElementById('copyBtn');
                btn.classList.add('copied');
                const originalHTML = btn.innerHTML;
                
                btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>`;
                
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = originalHTML;
                }, 1500);

                showToast("All formatted accounts copied to clipboard!", "copy", "Copied to Clipboard");
            }).catch(err => {
                console.error('Failed to copy!', err);
                showToast("Failed to copy to clipboard. Check browser permissions.", "error", "Copy Failed");
            });
        }

        function downloadFormattedFile() {
            const contentToSave = afterPreview.value;
            if (!contentToSave || !contentToSave.trim()) {
                showToast("No formatted output to download! Please process a file first.", "warning", "Nothing to Download");
                return;
            }
            
            const filename = 'Smart_Output.txt';
            const blob = new Blob([contentToSave], { type: 'text/plain' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Flash toast on file download
            showToast(`"${filename}" downloaded successfully!`, "download", "File Downloaded");
        }
    </script>

</body>
</html>