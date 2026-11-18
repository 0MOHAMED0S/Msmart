<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Very Smart File Formatter</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        /* ===== PERFORMANCE: GPU hints, reduced-motion, layer isolation ===== */

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
            background: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 70px 0 0 0;
            margin: 0;
            position: relative;
            overflow-x: hidden;
            width: 100%;
        }

        /* ===== Animated Background ===== */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 80% 20%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse 50% 40% at 20% 80%, rgba(56, 189, 248, 0.06) 0%, transparent 50%);
            z-index: 0;
            pointer-events: none;
            transform: translateZ(0);
            contain: strict;
        }

        .bg-orbs {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            contain: strict;
            transform: translateZ(0);
        }

        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(30px);
            opacity: 0.3;
            will-change: transform;
            transform: translateZ(0);
            contain: layout style paint;
        }

        .bg-orb-1 {
            width: 500px; height: 500px;
            top: -10%; left: -5%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.5) 0%, transparent 70%);
            animation: orbFloat1 18s ease-in-out infinite alternate;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            top: 30%; right: -8%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.45) 0%, transparent 70%);
            animation: orbFloat2 22s ease-in-out infinite alternate;
        }
        .bg-orb-3 {
            width: 350px; height: 350px;
            bottom: -5%; left: 20%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.4) 0%, transparent 70%);
            animation: orbFloat3 20s ease-in-out infinite alternate;
        }
        .bg-orb-4 {
            width: 250px; height: 250px;
            top: 60%; left: 50%;
            background: radial-gradient(circle, rgba(244, 114, 182, 0.3) 0%, transparent 70%);
            animation: orbFloat4 24s ease-in-out infinite alternate;
        }
        .bg-orb-5 {
            width: 320px; height: 320px;
            top: 15%; right: 25%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.35) 0%, transparent 70%);
            animation: orbFloat5 26s ease-in-out infinite alternate;
        }

        @keyframes orbFloat1 {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(80px, 60px) scale(1.15); }
            100% { transform: translate(-30px, 100px) scale(0.9); }
        }
        @keyframes orbFloat2 {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(-60px, 80px) scale(1.1); }
            100% { transform: translate(40px, -40px) scale(0.95); }
        }
        @keyframes orbFloat3 {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(70px, -50px) scale(1.12); }
            100% { transform: translate(-50px, -30px) scale(0.88); }
        }
        @keyframes orbFloat4 {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(-40px, 40px) scale(1.2); }
            100% { transform: translate(60px, -60px) scale(0.85); }
        }
        @keyframes orbFloat5 {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(-50px, -40px) scale(1.18); }
            100% { transform: translate(30px, 50px) scale(0.9); }
        }

        /* Noise texture overlay for depth */
        .bg-noise {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            transform: translateZ(0);
            contain: strict;
        }

        /* All content above BG */
        .content-wrapper, nav, footer, .flash-toast-container {
            position: relative;
            z-index: 1;
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
            animation: badgeGlow 4s ease-in-out infinite alternate;
        }

        @keyframes badgeGlow {
            0% { box-shadow: 0 0 16px rgba(59, 130, 246, 0.2), 0 0 30px rgba(59, 130, 246, 0.05); }
            33% { box-shadow: 0 0 16px rgba(168, 85, 247, 0.25), 0 0 30px rgba(168, 85, 247, 0.08); border-color: rgba(168, 85, 247, 0.35); }
            66% { box-shadow: 0 0 16px rgba(56, 189, 248, 0.25), 0 0 30px rgba(56, 189, 248, 0.08); border-color: rgba(56, 189, 248, 0.35); }
            100% { box-shadow: 0 0 16px rgba(244, 114, 182, 0.2), 0 0 30px rgba(244, 114, 182, 0.05); border-color: rgba(244, 114, 182, 0.3); }
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
            animation: rgbDot 3s linear infinite;
        }

        @keyframes rgbDot {
            0%   { background: #3b82f6; box-shadow: 0 0 8px #3b82f6, 0 0 16px rgba(59, 130, 246, 0.4); }
            25%  { background: #a855f7; box-shadow: 0 0 8px #a855f7, 0 0 16px rgba(168, 85, 247, 0.4); }
            50%  { background: #f472b6; box-shadow: 0 0 8px #f472b6, 0 0 16px rgba(244, 114, 182, 0.4); }
            75%  { background: #34d399; box-shadow: 0 0 8px #34d399, 0 0 16px rgba(52, 211, 153, 0.4); }
            100% { background: #3b82f6; box-shadow: 0 0 8px #3b82f6, 0 0 16px rgba(59, 130, 246, 0.4); }
        }

        .header h1 {
            font-size: 2.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 25%, #f472b6 50%, #38bdf8 75%, #34d399 100%);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            animation: rgbTitle 6s linear infinite;
            filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.3));
        }

        @keyframes rgbTitle {
            0% { background-position: 0% 50%; }
            50% { background-position: 150% 50%; }
            100% { background-position: 300% 50%; }
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
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #a855f7, #f472b6, #fbbf24, #34d399, #38bdf8, #3b82f6);
            background-size: 300% 100%;
            border-radius: 2px;
            animation: rgbBorder 4s linear infinite;
        }

        @keyframes rgbBorder {
            0% { background-position: 0% 0%; }
            100% { background-position: 300% 0%; }
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
            transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.35);
        }

        .settings-panel:hover {
            background: rgba(15, 23, 42, 0.75);
            border-color: rgba(99, 102, 241, 0.35);
            box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.35);
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .file-upload-label:hover {
            border-color: #818cf8;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.15) 0%, rgba(15, 23, 42, 0.75) 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -8px rgba(99, 102, 241, 0.35), 0 0 25px rgba(168, 85, 247, 0.2);
        }

        .file-upload-label.drag-over {
            border-color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
            transform: scale(1.02);
            box-shadow: 0 0 35px rgba(56, 189, 248, 0.45);
        }

        .upload-icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.18), rgba(168, 85, 247, 0.18));
            border: 1px solid rgba(99, 102, 241, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.25);
            animation: uploadCirclePulse 4s ease-in-out infinite alternate;
        }

        @keyframes uploadCirclePulse {
            0%   { box-shadow: 0 0 15px rgba(59, 130, 246, 0.25); border-color: rgba(59, 130, 246, 0.4); }
            50%  { box-shadow: 0 0 25px rgba(168, 85, 247, 0.35); border-color: rgba(168, 85, 247, 0.45); }
            100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.3); border-color: rgba(56, 189, 248, 0.4); }
        }

        .file-upload-label:hover .upload-icon-circle {
            transform: translateY(-4px) scale(1.1);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.32), rgba(168, 85, 247, 0.32));
            border-color: #a855f7;
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.5);
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
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .button-group button, .modal-buttons button {
            padding: 12px 26px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 9px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }

        .button-group button svg, .modal-buttons button svg {
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .button-group button:active:not(:disabled), .modal-buttons button:active:not(:disabled) {
            transform: scale(0.97) translateY(1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 50%, #8b5cf6 100%);
            background-size: 200% 100%;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            animation: btnPrimaryGlow 3s ease-in-out infinite alternate;
        }

        @keyframes btnPrimaryGlow {
            0% { box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35), 0 0 20px rgba(59, 130, 246, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2); background-position: 0% 0%; }
            100% { box-shadow: 0 4px 20px rgba(99, 102, 241, 0.45), 0 0 25px rgba(139, 92, 246, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.2); background-position: 100% 0%; }
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
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0d9488 100%);
            background-size: 200% 100%;
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            animation: btnSuccessGlow 3s ease-in-out infinite alternate;
        }

        @keyframes btnSuccessGlow {
            0% { box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35), 0 0 20px rgba(16, 185, 129, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.2); background-position: 0% 0%; }
            100% { box-shadow: 0 4px 20px rgba(20, 184, 166, 0.45), 0 0 25px rgba(52, 211, 153, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.2); background-position: 100% 0%; }
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.6), 0 0 25px rgba(52, 211, 153, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.3);
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
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            width: 29px !important;
            height: 29px !important;
            min-width: 29px !important;
            max-width: 29px !important;
            padding: 0 !important;
            background: rgba(30, 41, 59, 0.7);
            color: var(--text-muted);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        .btn-icon:hover:not(:disabled) {
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
            border-color: rgba(96, 165, 250, 0.45);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
            transform: translateY(-1px);
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

        .preview-pane {
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: fadeIn 1s ease-in;
            min-width: 0;
        }

        /* Preview Header & Action Row - 100% Responsive on all screens */
        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 0 2px;
            min-height: 32px;
            width: 100%;
            box-sizing: border-box;
            flex-wrap: nowrap;
        }

        .preview-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            user-select: none;
        }

        .preview-title svg {
            flex-shrink: 0;
            stroke: #93c5fd;
        }

        .preview-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
            margin-left: auto;
        }

        .mode-toggle-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-direction: row !important;
            flex-shrink: 0 !important;
            white-space: nowrap !important;
            word-break: keep-all !important;
            min-width: 82px !important;
            width: auto !important;
            max-width: fit-content !important;
            background: rgba(30, 41, 59, 0.7);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 0 10px !important;
            height: 28px !important;
            border-radius: 8px;
            font-size: 0.74rem !important;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            gap: 5px;
            user-select: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            letter-spacing: normal;
        }

        .mode-toggle-btn:hover {
            color: #ffffff;
            border-color: rgba(99, 102, 241, 0.5);
            background: rgba(51, 65, 85, 0.85);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            transform: translateY(-1px);
        }

        .mode-toggle-btn:active {
            transform: translateY(0);
        }

        .mode-toggle-btn svg {
            flex-shrink: 0;
            stroke: #94a3b8;
            transition: stroke 0.2s ease;
        }

        .mode-toggle-btn:hover svg {
            stroke: #60a5fa;
        }

        #gutterModeLabel {
            white-space: nowrap !important;
            display: inline-block !important;
            word-break: keep-all !important;
        }

        .preview-badge {
            width: auto !important;
            max-width: fit-content !important;
            font-size: 0.74rem !important;
            font-weight: 600;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 0 9px !important;
            height: 28px !important;
            border-radius: 9999px;
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.2px;
            box-sizing: border-box;
        }

        #afterLinesBadge {
            color: #d8b4fe;
            border-color: rgba(167, 139, 250, 0.35);
            background: rgba(167, 139, 250, 0.12);
            box-shadow: 0 2px 8px rgba(167, 139, 250, 0.15);
        }

        #beforeLinesBadge {
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.35);
            background: rgba(59, 130, 246, 0.12);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
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

        /* Color Variants with Vibrant Hover & Glow — single shared animation */
        @keyframes statGlow {
            0% { box-shadow: 0 0 6px var(--chip-glow); }
            100% { box-shadow: 0 0 14px var(--chip-glow), 0 0 4px var(--chip-glow); }
        }

        .stat-chip.lines { 
            --chip-glow: rgba(59, 130, 246, 0.25);
            color: #60a5fa; 
            background: rgba(59, 130, 246, 0.08);  
            border-color: rgba(59, 130, 246, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
        }
        .stat-chip.lines:hover { 
            background: rgba(59, 130, 246, 0.2); 
            border-color: rgba(96, 165, 250, 0.55); 
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.45), 0 0 20px rgba(59, 130, 246, 0.2); 
            color: #93c5fd; 
        }

        .stat-chip.accounts { 
            --chip-glow: rgba(167, 139, 250, 0.25);
            color: #a78bfa; 
            background: rgba(167, 139, 250, 0.08); 
            border-color: rgba(167, 139, 250, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
            animation-delay: 0.5s;
        }
        .stat-chip.accounts:hover { 
            background: rgba(167, 139, 250, 0.2); 
            border-color: rgba(192, 132, 252, 0.55); 
            box-shadow: 0 6px 20px rgba(167, 139, 250, 0.45), 0 0 20px rgba(167, 139, 250, 0.2); 
            color: #d8b4fe; 
        }

        .stat-chip.emails { 
            --chip-glow: rgba(52, 211, 153, 0.25);
            color: #34d399; 
            background: rgba(52, 211, 153, 0.08);  
            border-color: rgba(52, 211, 153, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
            animation-delay: 1s;
        }
        .stat-chip.emails:hover { 
            background: rgba(52, 211, 153, 0.2); 
            border-color: rgba(110, 231, 183, 0.55); 
            box-shadow: 0 6px 20px rgba(52, 211, 153, 0.45), 0 0 20px rgba(52, 211, 153, 0.2); 
            color: #6ee7b7; 
        }

        .stat-chip.users { 
            --chip-glow: rgba(251, 191, 36, 0.25);
            color: #fbbf24; 
            background: rgba(251, 191, 36, 0.08);  
            border-color: rgba(251, 191, 36, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
            animation-delay: 1.5s;
        }
        .stat-chip.users:hover { 
            background: rgba(251, 191, 36, 0.2); 
            border-color: rgba(252, 211, 77, 0.55); 
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.45), 0 0 20px rgba(251, 191, 36, 0.2); 
            color: #fcd34d; 
        }

        .stat-chip.links { 
            --chip-glow: rgba(244, 114, 182, 0.25);
            color: #f472b6; 
            background: rgba(244, 114, 182, 0.08); 
            border-color: rgba(244, 114, 182, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
            animation-delay: 2s;
        }
        .stat-chip.links:hover { 
            background: rgba(244, 114, 182, 0.2); 
            border-color: rgba(249, 168, 212, 0.55); 
            box-shadow: 0 6px 20px rgba(244, 114, 182, 0.45), 0 0 20px rgba(244, 114, 182, 0.2); 
            color: #fbcfe8; 
        }

        .stat-chip.dupes { 
            --chip-glow: rgba(251, 146, 60, 0.25);
            color: #fb923c; 
            background: rgba(251, 146, 60, 0.08);  
            border-color: rgba(251, 146, 60, 0.2); 
            animation: statGlow 3s ease-in-out infinite alternate;
            animation-delay: 2.5s;
        }
        .stat-chip.dupes:hover { 
            background: rgba(251, 146, 60, 0.2); 
            border-color: rgba(253, 186, 116, 0.55); 
            box-shadow: 0 6px 20px rgba(251, 146, 60, 0.45), 0 0 20px rgba(251, 146, 60, 0.2); 
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
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            transform: translateZ(0);
            contain: layout style;
        }

        .editor-wrapper:hover {
            border-color: rgba(99, 102, 241, 0.35);
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3), 0 0 25px rgba(99, 102, 241, 0.15);
        }

        .editor-wrapper:focus-within {
            border-color: #818cf8;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3), 0 0 30px rgba(99, 102, 241, 0.3), 0 0 0 1px rgba(99, 102, 241, 0.5);
        }

        .line-numbers {
            position: relative;
            z-index: 4;
            min-width: 48px;
            width: auto;
            flex-shrink: 0;
            padding: 0;
            background: rgba(15, 23, 42, 0.75);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            user-select: none;
            text-align: right;
            box-sizing: border-box;
            contain: strict;
            overflow-anchor: none;
        }

        .line-numbers-track {
            padding: 16px 8px 16px 0;
            box-sizing: border-box;
            will-change: transform;
            transform: translate3d(0, 0, 0);
            pointer-events: auto;
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
            position: relative;
            z-index: 6;
            transition: color 0.15s ease;
            box-sizing: border-box;
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

        /* Gutter Duplicate styling */
        .line-numbers .line-num.is-duplicate {
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.18) !important;
            font-weight: 800 !important;
            border-left: 2px solid #ef4444 !important;
            box-shadow: inset 2px 0 6px rgba(239, 68, 68, 0.25);
        }

        .line-numbers .line-num.is-duplicate .dup-dot {
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            margin-right: 4px;
            animation: dupDotPulse 1.6s infinite ease-in-out;
            flex-shrink: 0;
        }

        @keyframes dupDotPulse {
            0% { transform: scale(0.8); opacity: 0.7; }
            50% { transform: scale(1.35); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.7; }
        }

        /* Editor Content Area for Textarea & Backdrop Overlay */
        .editor-content-area {
            position: relative;
            flex: 1 1 0%;
            min-width: 0;
            height: 100%;
            overflow: hidden;
            contain: layout style;
        }

        /* High-Performance Editor Overlay & Duplicate Highlights */
        .editor-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 3;
            box-sizing: border-box;
            contain: strict;
            overflow-anchor: none;
            user-select: none;
        }

        .editor-backdrop-track {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            box-sizing: border-box;
            will-change: transform;
            transform: translate3d(0, 0, 0);
            pointer-events: none;
        }

        /* Pure Row Highlight Band - Zero text duplication, 0.01ms rendering, zero lag */
        .dup-highlight-band {
            position: absolute;
            left: 0;
            right: 0;
            height: calc(0.9rem * 1.5);
            box-sizing: border-box;
            pointer-events: none;
        }

        .dup-band-bg {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(239, 68, 68, 0.18);
            border-left: 3px solid #ef4444;
            border-top: 1px solid rgba(239, 68, 68, 0.35);
            border-bottom: 1px solid rgba(239, 68, 68, 0.35);
            box-shadow: inset 0 0 14px rgba(239, 68, 68, 0.15);
            pointer-events: none;
        }

        .dup-band-actions {
            position: absolute;
            right: 14px;
            top: 2px;
            height: calc(0.9rem * 1.5 - 4px);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            z-index: 6;
            pointer-events: auto;
        }

        /* Duplicate Badge & Delete Button Container */
        .dup-badge-group {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 12px;
            vertical-align: middle;
            flex-shrink: 0;
            pointer-events: auto;
        }

        .dup-marker-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 1px 7px;
            border-radius: 4px;
            background: rgba(239, 68, 68, 0.25);
            border: 1px solid rgba(239, 68, 68, 0.55);
            color: #fca5a5;
            font-size: 0.65rem;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
            user-select: none;
            flex-shrink: 0;
            height: 18px;
            line-height: 16px;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s ease;
        }

        .dup-marker-tag:hover {
            background: rgba(239, 68, 68, 0.4);
            border-color: #fca5a5;
            color: #ffffff;
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.6);
            transform: translateY(-1px);
        }

        /* Inline Delete Row Button on Duplicate Badge */
        .dup-delete-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 1px 7px;
            border-radius: 4px;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.9), rgba(185, 28, 28, 0.95));
            border: 1px solid rgba(248, 113, 113, 0.6);
            color: #ffffff;
            font-size: 0.65rem;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            user-select: none;
            flex-shrink: 0;
            height: 18px;
            line-height: 16px;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dup-delete-btn:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-color: #fca5a5;
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.75), 0 3px 8px rgba(0, 0, 0, 0.4);
            transform: translateY(-1px) scale(1.05);
        }

        .dup-delete-btn:active {
            transform: scale(0.95);
        }

        .dup-delete-btn svg {
            flex-shrink: 0;
            stroke: #ffffff;
            transition: transform 0.15s ease;
        }

        .dup-delete-btn:hover svg {
            transform: scale(1.15);
        }

        .preview-content {
            background: transparent !important;
            border: none !important;
            outline: none !important;
            border-radius: 0 !important;
            padding: 16px 16px 16px 12px;
            height: 100%;
            width: 100%;
            overflow-y: scroll;
            overflow-x: auto;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            color: #a7f3d0 !important;
            -webkit-text-fill-color: #a7f3d0 !important;
            line-height: calc(0.9rem * 1.5);
            white-space: pre; 
            box-shadow: none !important;
            overflow-anchor: none;
            overscroll-behavior: contain;
            scroll-behavior: auto !important;
            -webkit-overflow-scrolling: touch;
            position: relative;
            z-index: 2;
            caret-color: #f8fafc;
        }

        #afterPreview {
            color: #c4b5fd !important;
            -webkit-text-fill-color: #c4b5fd !important;
        }

        .editor-wrapper.is-scrolling .row-hover-highlight,
        .editor-wrapper.is-scrolling .row-hover-copy,
        .editor-wrapper.is-scrolling .row-hover-delete,
        .editor-wrapper.is-scrolling .row-dup-jump {
            display: none !important;
            pointer-events: none !important;
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
            z-index: 4;
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

        /* Duplicate Jump Button in Input/Output Row */
        .row-dup-jump {
            position: absolute;
            right: 168px;
            top: 0;
            display: none;
            align-items: center;
            gap: 6px;
            padding: 2px 10px;
            height: calc(0.9rem * 1.5 - 4px);
            margin-top: 2px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(185, 28, 28, 0.95));
            border: 1px solid rgba(248, 113, 113, 0.5);
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4), 0 0 10px rgba(239, 68, 68, 0.3);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: auto;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .row-dup-jump:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            transform: translateY(-1px) scale(1.03);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.7);
        }

        .row-dup-jump:active {
            transform: scale(0.96) translateY(1px);
        }

        .row-dup-jump svg {
            flex-shrink: 0;
            stroke: #ffffff;
            transition: transform 0.2s ease;
        }

        .row-dup-jump:hover svg {
            transform: translateX(2px);
        }

        /* Floating Row Hover Delete Button */
        .row-hover-delete {
            position: absolute;
            right: 86px;
            top: 0;
            display: none;
            align-items: center;
            gap: 5px;
            padding: 2px 9px;
            height: calc(0.9rem * 1.5 - 4px);
            margin-top: 2px;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(239, 68, 68, 0.45);
            border-radius: 6px;
            color: #fca5a5;
            font-size: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5), 0 0 8px rgba(239, 68, 68, 0.2);
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
            pointer-events: auto;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .row-hover-delete:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-color: #fca5a5;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.6);
            transform: translateY(-1px);
        }

        .row-hover-delete:active {
            transform: scale(0.96) translateY(1px);
        }

        .row-hover-delete.is-dup-row {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.95), rgba(185, 28, 28, 0.95));
            border-color: rgba(248, 113, 113, 0.7);
            color: #ffffff;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.5);
        }

        .row-hover-delete svg {
            flex-shrink: 0;
            stroke: currentColor;
            transition: transform 0.15s ease;
        }

        .row-hover-delete:hover svg {
            transform: scale(1.15);
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
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(96, 165, 250, 0.35);
            border-radius: 6px;
            color: #e2e8f0;
            font-size: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5), 0 0 8px rgba(59, 130, 246, 0.15);
            transition: background 0.15s ease, border-color 0.15s ease;
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

        /* Flash Highlight when jumping to duplicate line */
        .flash-dup-target {
            position: absolute;
            left: 0;
            right: 0;
            height: calc(0.9rem * 1.5);
            pointer-events: none;
            z-index: 5;
            box-sizing: border-box;
            display: none;
        }

        .flash-dup-target.active-flash {
            display: block;
            animation: flashTargetGlow 2.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes flashTargetGlow {
            0% {
                background: rgba(239, 68, 68, 0.65);
                border-top: 2px solid #ef4444;
                border-bottom: 2px solid #ef4444;
                border-left: 4px solid #ef4444;
                box-shadow: 0 0 30px rgba(239, 68, 68, 0.8), inset 0 0 15px rgba(239, 68, 68, 0.5);
            }
            30% {
                background: rgba(245, 158, 11, 0.45);
                border-top: 2px solid #f59e0b;
                border-bottom: 2px solid #f59e0b;
                border-left: 4px solid #f59e0b;
                box-shadow: 0 0 25px rgba(245, 158, 11, 0.7);
            }
            100% {
                background: transparent;
                border-color: transparent;
                box-shadow: none;
            }
        }

        /* ================= Flash Notification Toast System - Responsive On All Screens ================= */
        .flash-toast-container {
            position: fixed;
            top: 84px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 999999;
            pointer-events: none;
            max-width: 400px;
            width: calc(100vw - 48px);
            box-sizing: border-box;
            transition: all 0.25s ease;
        }

        .flash-toast {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.05);
            pointer-events: auto;
            overflow: hidden;
            user-select: none;
            cursor: pointer;
            animation: flashToastInDesktop 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: transform 0.22s ease, opacity 0.22s ease, max-height 0.28s ease, margin 0.28s ease, padding 0.28s ease;
            box-sizing: border-box;
            width: 100%;
        }

        .flash-toast.dismissing {
            animation: flashToastOutDesktop 0.24s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes flashToastInDesktop {
            0% {
                opacity: 0;
                transform: translateX(36px) scale(0.94);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes flashToastOutDesktop {
            0% {
                opacity: 1;
                transform: translateX(0) scale(1);
                max-height: 140px;
            }
            100% {
                opacity: 0;
                transform: translateX(36px) scale(0.9);
                max-height: 0;
                margin-bottom: -10px;
                padding-top: 0;
                padding-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .flash-toast-container {
                top: 76px;
                right: 14px;
                left: 14px;
                width: auto;
                max-width: 440px;
                margin: 0 auto;
                gap: 8px;
            }

            .flash-toast {
                padding: 10px 12px;
                gap: 10px;
                border-radius: 11px;
                animation: flashToastInMobile 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            .flash-toast.dismissing {
                animation: flashToastOutMobile 0.24s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
        }

        @media (max-width: 480px) {
            .flash-toast-container {
                top: 74px;
                right: 10px;
                left: 10px;
                gap: 6px;
            }

            .flash-toast {
                padding: 9px 11px;
                gap: 9px;
                border-radius: 10px;
            }
        }

        @keyframes flashToastInMobile {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes flashToastOutMobile {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
                max-height: 140px;
            }
            100% {
                opacity: 0;
                transform: translateY(-16px) scale(0.9);
                max-height: 0;
                margin-bottom: -8px;
                padding-top: 0;
                padding-bottom: 0;
            }
        }

        .flash-toast-icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 8px;
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
            width: 17px;
            height: 17px;
        }

        .flash-toast-body {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
            justify-content: center;
        }

        .flash-toast-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: 0.2px;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .flash-toast-msg {
            font-size: 0.76rem;
            color: #94a3b8;
            line-height: 1.35;
            word-break: break-word;
        }

        .flash-toast-close {
            flex-shrink: 0;
            background: transparent;
            border: none;
            color: #64748b;
            padding: 4px;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            margin-top: -2px;
            margin-right: -2px;
            width: 26px;
            height: 26px;
        }

        .flash-toast-close:hover {
            color: #f1f5f9;
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 480px) {
            .flash-toast-icon {
                width: 28px;
                height: 28px;
                border-radius: 7px;
            }
            .flash-toast-icon svg {
                width: 15px;
                height: 15px;
            }
            .flash-toast-title {
                font-size: 0.78rem;
            }
            .flash-toast-msg {
                font-size: 0.72rem;
            }
            .flash-toast-close {
                width: 28px;
                height: 28px;
                padding: 6px;
            }
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

        /* Variant: Delete */
        .flash-toast.flash-delete {
            border-color: rgba(239, 68, 68, 0.45);
            box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.7), 0 0 24px -2px rgba(239, 68, 68, 0.3);
        }
        .flash-toast.flash-delete .flash-toast-icon {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.4);
            color: #ef4444;
        }
        .flash-toast.flash-delete .flash-toast-title { color: #fca5a5; }
        .flash-toast.flash-delete .flash-toast-progress { background: #ef4444; }

        .flash-toast-undo-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            padding: 3px 11px;
            border-radius: 6px;
            background: rgba(239, 68, 68, 0.22);
            border: 1px solid rgba(248, 113, 113, 0.55);
            color: #fecdd3;
            font-size: 0.72rem;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .flash-toast-undo-btn:hover {
            background: #ef4444;
            border-color: #fca5a5;
            color: #ffffff;
            box-shadow: 0 0 14px rgba(239, 68, 68, 0.6);
            transform: translateY(-1px);
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
            will-change: scroll-position;
            transform: translateZ(0);
            contain: content;
        }

        .editor-wrapper textarea.preview-content:focus {
            border: none;
            box-shadow: none;
        }
        
        .preview-content.before {
             color: #94a3b8;
        }
        
        textarea.preview-content {
            resize: none;
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

        /* Layout Rules */
        .content-wrapper {
            flex-grow: 1;
            width: 100%;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px clamp(12px, 3.5vw, 28px);
            box-sizing: border-box;
        }

        .navbar {
            width: 100%;
            height: 70px;
            padding: 0 clamp(16px, 4vw, 40px);
            display: flex;
            align-items: center;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transform: translateZ(0);
            contain: layout style;
        }

        .navbar::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #3b82f6, #a855f7, #f472b6, #34d399, transparent);
            background-size: 200% 100%;
            animation: rgbBorder 6s linear infinite;
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 25%, #f472b6 50%, #38bdf8 75%, #34d399 100%);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
            animation: rgbTitle 6s linear infinite;
            filter: drop-shadow(0 0 16px rgba(168, 85, 247, 0.45));
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
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 35%, #f472b6 70%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            letter-spacing: 4px;
            text-transform: uppercase;
            transform: translateY(100%);
            animation: slideUpReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            filter: drop-shadow(0 0 25px rgba(168, 85, 247, 0.5));
        }

        .loader-progress-bar {
            width: 200px;
            height: 3px;
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
            background: linear-gradient(90deg, #3b82f6, #a855f7, #f472b6, #34d399);
            border-radius: 4px;
            animation: progressFill 1.2s cubic-bezier(0.7, 0, 0.3, 1) forwards;
            box-shadow: 0 0 16px rgba(168, 85, 247, 0.7);
        }

        @keyframes slideUpReveal {
            to { transform: translateY(0); }
        }

        @keyframes progressFill {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* ==========================================================================
           PROFESSIONAL RESPONSIVE ARCHITECTURE (Mobile-First Scaled & Desktop-Refined)
           ========================================================================== */

        /* --- 1024px and below: Laptops & Large Tablets --- */
        @media (max-width: 1024px) {
            .main-container {
                max-width: 100%;
                gap: 18px;
            }
            .previews-container {
                gap: 16px;
            }
            .editor-wrapper {
                height: 400px;
            }
        }

        /* --- 900px and below: Tablets (Panels stack vertically) --- */
        @media (max-width: 900px) {
            .previews-container {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .editor-wrapper {
                height: 360px;
            }
            .top-settings-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        /* --- 768px and below: Mobile Devices & Portrait Tablets --- */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }
            .navbar {
                height: 60px;
                padding: 0 16px;
            }
            .nav-brand {
                font-size: 1.3rem;
            }
            .content-wrapper {
                padding: 20px clamp(10px, 3vw, 16px);
            }
            .header {
                margin-bottom: 20px;
            }
            .header h1 {
                font-size: clamp(1.6rem, 5vw, 2.1rem);
                margin-bottom: 8px;
            }
            .header p {
                font-size: 0.86rem;
                line-height: 1.5;
            }
            .header-badge {
                padding: 4px 11px;
                font-size: 0.68rem;
                margin-bottom: 10px;
            }

            .controls-card {
                padding: 20px 14px;
                gap: 16px;
                border-radius: 14px;
            }
            .file-upload-label {
                padding: 18px 14px;
                border-radius: 12px;
            }
            .upload-icon-circle {
                width: 44px;
                height: 44px;
                margin-bottom: 8px;
            }
            .upload-icon-svg {
                width: 22px;
                height: 22px;
            }
            .upload-title {
                font-size: 0.9rem;
            }
            .upload-subtitle {
                font-size: 0.72rem;
            }
            #fileName {
                font-size: 0.76rem;
                padding: 3px 12px;
            }

            .button-group {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                width: 100%;
            }
            .button-group button {
                width: 100%;
                justify-content: center;
                padding: 10px 16px;
                font-size: 0.88rem;
                min-height: 42px;
            }
            .button-group .btn-danger#clearBtn {
                grid-column: span 2;
            }

            .settings-panel {
                padding: 8px 12px;
                min-height: 38px;
                border-radius: 9px;
            }
            .setting-group label {
                font-size: 0.76rem;
            }
            .settings-panel input {
                height: 28px;
                font-size: 0.8rem;
            }

            /* Clean Stats Bar */
            .stats-bar {
                gap: 6px;
                padding: 8px 10px;
                border-radius: 10px;
            }
            .stat-chip {
                padding: 4px 10px;
                font-size: 0.72rem;
                height: 27px;
                border-radius: 7px;
                gap: 5px;
            }
            .stat-chip svg {
                width: 13px;
                height: 13px;
            }

            /* Responsive Preview Panes */
            .editor-wrapper {
                height: 330px;
                border-radius: 10px;
            }
            .line-numbers {
                min-width: 38px;
                width: 38px;
            }
            .line-numbers-track {
                padding: 16px 4px 16px 0;
            }
            .line-numbers .line-num {
                font-size: 0.75rem;
                padding: 0 6px 0 2px;
            }
            .preview-content {
                padding: 16px 14px 16px 10px;
                font-size: 0.86rem;
            }

            /* Preview Header & Actions (Wraps smoothly, zero overflow or clipping) */
            .preview-header {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                row-gap: 8px;
                column-gap: 8px;
                min-height: auto;
                padding: 0;
            }
            .preview-title {
                font-size: 0.9rem;
            }
            .preview-title svg {
                width: 16px;
                height: 16px;
            }
            .preview-actions {
                display: inline-flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 6px;
                margin-left: auto;
            }
            .mode-toggle-btn {
                height: 28px !important;
                padding: 0 9px !important;
                font-size: 0.72rem !important;
                min-width: auto !important;
                gap: 5px;
            }
            .preview-badge {
                height: 28px !important;
                padding: 0 9px !important;
                font-size: 0.72rem !important;
            }
            .btn-icon {
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
                max-width: 28px !important;
            }

            /* Touch screen: hide desktop hover-only floating buttons */
            .row-hover-copy,
            .row-hover-delete,
            .row-dup-jump,
            .row-hover-highlight {
                display: none !important;
                pointer-events: none !important;
            }

            /* Toasts on Mobile */
            .flash-toast-container {
                top: 68px;
                left: 12px;
                right: 12px;
                width: auto;
                max-width: 440px;
                margin: 0 auto;
                gap: 8px;
            }
            .flash-toast {
                padding: 10px 12px;
                gap: 10px;
                border-radius: 11px;
                animation: flashToastInMobile 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .flash-toast.dismissing {
                animation: flashToastOutMobile 0.24s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
            .loader-logo {
                font-size: 2.4rem;
            }
        }

        /* --- 540px and below: Small Smartphones --- */
        @media (max-width: 540px) {
            .navbar {
                height: 56px;
                padding: 0 14px;
            }
            body {
                padding-top: 56px;
            }
            .nav-brand {
                font-size: 1.2rem;
            }
            .content-wrapper {
                padding: 16px 10px;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .header p {
                font-size: 0.8rem;
            }

            /* Full-width inputs for Find & Replace so typing is effortless */
            .replace-panel {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 10px 12px;
            }
            .replace-panel .setting-group {
                width: 100%;
            }
            .replace-panel .setting-group.border-left {
                border-left: none;
                padding-left: 0;
                margin-left: 0;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                padding-top: 8px;
            }
            .replace-panel .setting-group label {
                min-width: 62px;
            }

            .group-panel {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 10px;
            }
            .group-panel #groupSize {
                width: 48px;
            }

            /* Stats Chips for Mobile */
            .stats-bar {
                gap: 5px;
                padding: 6px 8px;
            }
            .stat-chip {
                padding: 3px 8px;
                font-size: 0.68rem;
                height: 25px;
                border-radius: 6px;
                gap: 4px;
            }
            .stat-chip svg {
                width: 11px;
                height: 11px;
            }

            /* Editor on small phones */
            .editor-wrapper {
                height: 300px;
            }
            .line-numbers {
                min-width: 32px;
                width: 32px;
            }
            .line-numbers-track {
                padding: 16px 2px 16px 0;
            }
            .line-numbers .line-num {
                font-size: 0.68rem;
                padding: 0 4px 0 2px;
            }
            .preview-content {
                padding: 16px 10px 16px 8px;
                font-size: 0.82rem;
            }

            /* Duplicate band buttons on small screens */
            .dup-band-actions {
                right: 6px;
                gap: 4px;
            }
            .dup-marker-tag, .dup-delete-btn {
                padding: 1px 5px;
                font-size: 0.6rem;
                height: 17px;
                line-height: 15px;
            }
            .dup-marker-tag svg, .dup-delete-btn svg {
                width: 9px;
                height: 9px;
            }

            .mode-toggle-btn {
                height: 26px !important;
                padding: 0 7px !important;
                font-size: 0.68rem !important;
                gap: 3px;
            }
            .mode-toggle-btn svg {
                width: 12px;
                height: 12px;
            }
            .preview-badge {
                height: 26px !important;
                padding: 0 7px !important;
                font-size: 0.68rem !important;
            }
            .btn-icon {
                width: 26px !important;
                height: 26px !important;
                min-width: 26px !important;
                max-width: 26px !important;
            }
            .btn-icon svg {
                width: 13px;
                height: 13px;
            }

            /* Modal on Mobile */
            .modal-card {
                padding: 24px 18px;
                max-width: calc(100vw - 32px);
            }
            .modal-card h2 {
                font-size: 1.25rem;
            }
            .modal-card p {
                font-size: 0.88rem;
                margin-bottom: 20px;
            }
        }

        /* --- 380px and below: Extra-Narrow Phones (iPhone SE, Galaxy Z Flip outer) --- */
        @media (max-width: 380px) {
            .button-group {
                grid-template-columns: 1fr;
            }
            .button-group .btn-danger#clearBtn {
                grid-column: span 1;
            }
            .preview-title {
                font-size: 0.78rem;
            }
            .preview-actions {
                gap: 4px;
            }
            .mode-toggle-btn {
                height: 24px !important;
                padding: 0 5px !important;
                font-size: 0.64rem !important;
            }
            .preview-badge {
                height: 24px !important;
                padding: 0 5px !important;
                font-size: 0.64rem !important;
            }
            .btn-icon {
                width: 24px !important;
                height: 24px !important;
                min-width: 24px !important;
                max-width: 24px !important;
            }
        }

        /* ===== PERFORMANCE: Respect reduced-motion preference ===== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .bg-orb { animation: none !important; opacity: 0.2; }
            .bg-noise { display: none; }
        }

        /* ===== PERFORMANCE: Reduce effects on low-power / narrow screens ===== */
        @media (max-width: 640px) {
            .bg-orb { 
                filter: blur(20px); 
                opacity: 0.18;
            }
            .bg-orb-4, .bg-orb-5 { display: none; }
            .row-hover-copy { backdrop-filter: none; -webkit-backdrop-filter: none; }
        }
    </style>
</head>
<body>

    <!-- Animated Smooth Background Orbs & Ambient Noise -->
    <div class="bg-orbs" aria-hidden="true">
        <div class="bg-orb bg-orb-1"></div>
        <div class="bg-orb bg-orb-2"></div>
        <div class="bg-orb bg-orb-3"></div>
        <div class="bg-orb bg-orb-4"></div>
        <div class="bg-orb bg-orb-5"></div>
    </div>
    <div class="bg-noise" aria-hidden="true"></div>

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
            <div class="settings-panel group-panel">
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
            <div class="settings-panel replace-panel">
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
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Raw Input</span>
                    </div>
                    <div class="preview-actions">
                        <span class="stat-chip dupes" id="beforeDupesChip" style="display: none;" title="Duplicate rows detected — Click to cycle through duplicates">
                            <svg viewBox="0 0 24 24"><rect x="2" y="6" width="13" height="13" rx="2" ry="2"/><path d="M9 2h9a2 2 0 0 1 2 2v9"/></svg>
                            <span class="stat-value" id="beforeDupesVal">0</span> Duplicates
                        </span>
                        <button id="beforeRemoveDupesBtn" class="mode-toggle-btn" style="display: none;" onclick="removeAllDuplicates('beforePreview')" title="Remove all duplicate rows with one click">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Remove Duplicates</span>
                        </button>
                        <span class="preview-badge" id="beforeLinesBadge">0 Lines</span>
                    </div>
                </div>
                <div class="editor-wrapper" id="beforeWrapper">
                    <div class="row-hover-highlight" id="beforeRowHighlight"></div>
                    <div class="flash-dup-target" id="beforeFlashHighlight"></div>
                    <div class="line-numbers" id="beforeLineNums">
                        <div class="line-numbers-track" id="beforeLineNumsTrack"></div>
                    </div>
                    <div class="editor-content-area">
                        <div class="editor-backdrop" id="beforeBackdrop">
                            <div class="editor-backdrop-track" id="beforeBackdropTrack"></div>
                        </div>
                        <textarea class="preview-content before" id="beforePreview" wrap="off" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" placeholder="Paste your raw content here, or upload a file..." oninput="handleRawInput()"></textarea>
                    </div>
                    <button class="row-dup-jump" id="beforeRowDupJump" title="Jump to duplicate occurrence">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 16l-4-4m0 0l4-4m-4 4h18m-4 4l4-4m0 0l-4-4"/>
                        </svg>
                        <span id="beforeDupJumpLabel">Duplicate ↷ Jump</span>
                    </button>
                    <button class="row-hover-delete" id="beforeRowDelete" title="Delete this row">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        <span>Delete</span>
                    </button>
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
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Editable Output</span>
                    </div>
                    <div class="preview-actions">
                        <button id="afterRemoveDupesBtn" class="mode-toggle-btn" style="display: none;" onclick="removeAllDuplicates('afterPreview')" title="Remove duplicate accounts with one click">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Remove Duplicates</span>
                        </button>
                        <button id="gutterModeBtn" class="mode-toggle-btn" onclick="toggleGutterMode()" title="Switch between Group counter and Line counter">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            </svg>
                            <span id="gutterModeLabel">Group&nbsp;#</span>
                        </button>
                        <button id="copyBtn" class="btn-icon" onclick="copyOutput()" title="Copy to Clipboard" disabled>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                        <span class="preview-badge" id="afterLinesBadge">0 Accounts</span>
                    </div>
                </div>
                
                <div class="editor-wrapper" id="afterWrapper">
                    <div class="row-hover-highlight" id="afterRowHighlight"></div>
                    <div class="flash-dup-target" id="afterFlashHighlight"></div>
                    <div class="line-numbers" id="afterLineNums">
                        <div class="line-numbers-track" id="afterLineNumsTrack"></div>
                    </div>
                    <div class="editor-content-area">
                        <div class="editor-backdrop" id="afterBackdrop">
                            <div class="editor-backdrop-track" id="afterBackdropTrack"></div>
                        </div>
                        <textarea class="preview-content" id="afterPreview" wrap="off" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" placeholder="Awaiting processing... Feel free to edit this text before downloading!" oninput="saveTextareaCache()"></textarea>
                    </div>
                    <button class="row-dup-jump" id="afterRowDupJump" title="Jump to duplicate occurrence">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 16l-4-4m0 0l4-4m-4 4h18m-4 4l4-4m0 0l-4-4"/>
                        </svg>
                        <span id="afterDupJumpLabel">Duplicate ↷ Jump</span>
                    </button>
                    <button class="row-hover-delete" id="afterRowDelete" title="Delete this row">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        <span>Delete</span>
                    </button>
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
        function showToast(message, type = 'info', title = null, duration = 3200, onAction = null, actionText = 'Undo') {
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
                else if (lower.includes('deleted') || lower.includes('delete') || lower.includes('remove')) type = 'delete';
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
                    delete: 'Row Deleted',
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
                delete: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
                info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            const toast = document.createElement('div');
            toast.className = `flash-toast flash-${type}`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'polite');
            if (title) toast.setAttribute('data-toast-title', title);
            toast.innerHTML = `
                <div class="flash-toast-icon">
                    ${icons[type] || icons.info}
                </div>
                <div class="flash-toast-body">
                    <div class="flash-toast-title">${title}</div>
                    <div class="flash-toast-msg">${message}</div>
                    ${onAction ? `
                    <div style="margin-top: 4px;">
                        <button type="button" class="flash-toast-undo-btn">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                            <span>${actionText}</span>
                        </button>
                    </div>` : ''}
                </div>
                <button type="button" class="flash-toast-close" title="Dismiss" aria-label="Close notification">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="flash-toast-progress" style="animation-duration: ${duration}ms;"></div>
            `;

            // If cycling duplicate locations rapidly, update existing toast smoothly
            if (title === 'Duplicate Located') {
                const existingDup = container.querySelector('.flash-toast[data-toast-title="Duplicate Located"]');
                if (existingDup) {
                    existingDup.remove();
                }
            }

            // Responsive toast stacking: max 2 on mobile, max 3 on larger screens
            const maxToasts = window.innerWidth <= 640 ? 2 : 3;
            while (container.children.length >= maxToasts) {
                const oldest = container.firstElementChild;
                if (!oldest) break;
                oldest.remove();
            }

            container.appendChild(toast);

            let timer = setTimeout(() => {
                dismissToast(toast);
            }, duration);

            if (onAction) {
                const actionBtn = toast.querySelector('.flash-toast-undo-btn');
                if (actionBtn) {
                    actionBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        clearTimeout(timer);
                        dismissToast(toast);
                        onAction();
                    });
                }
            }

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

            // Mobile touch swipe to dismiss
            let startX = 0;
            let currentX = 0;
            let isTouching = false;

            toast.addEventListener('touchstart', (e) => {
                if (e.touches.length === 1) {
                    startX = e.touches[0].clientX;
                    isTouching = true;
                }
            }, { passive: true });

            toast.addEventListener('touchmove', (e) => {
                if (!isTouching) return;
                currentX = e.touches[0].clientX - startX;
                if (Math.abs(currentX) > 10) {
                    toast.style.transform = `translateX(${currentX}px)`;
                    toast.style.opacity = Math.max(0.2, 1 - Math.abs(currentX) / 200);
                }
            }, { passive: true });

            toast.addEventListener('touchend', () => {
                if (!isTouching) return;
                isTouching = false;
                if (Math.abs(currentX) > 70) {
                    clearTimeout(timer);
                    dismissToast(toast);
                } else {
                    toast.style.transform = '';
                    toast.style.opacity = '';
                }
                currentX = 0;
            });
        }

        function dismissToast(toastEl) {
            if (!toastEl || toastEl.classList.contains('dismissing')) return;
            toastEl.classList.add('dismissing');
            setTimeout(() => {
                if (toastEl && toastEl.parentNode) {
                    toastEl.parentNode.removeChild(toastEl);
                }
            }, 250);
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

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;')
                      .replace(/</g, '&lt;')
                      .replace(/>/g, '&gt;')
                      .replace(/"/g, '&quot;')
                      .replace(/'/g, '&#039;');
        }

        let currentBeforeDupMap = {};
        let currentAfterDupMap = {};
        let currentBeforeCycleIdx = -1;
        let currentAfterCycleIdx = -1;
        let currentCycleIdx = -1;
        let lastDeletedState = null;

        function showToastWithUndo(message, onUndo, type = 'delete', title = 'Row Deleted', duration = 4000) {
            showToast(message, type, title, duration, onUndo, 'Undo');
        }

        // Delete a specific row by index with instant animation & undo capability
        function deleteEditorRow(textareaId, lineIdx, event) {
            if (event) {
                event.stopPropagation();
                if (event.preventDefault) event.preventDefault();
            }
            const textarea = document.getElementById(textareaId);
            if (!textarea) return;

            const text = textarea.value;
            const lines = text.split('\n');
            if (lineIdx < 0 || lineIdx >= lines.length) return;

            const deletedLine = lines[lineIdx];
            const originalText = text;

            lines.splice(lineIdx, 1);
            textarea.value = lines.join('\n');

            lastDeletedState = {
                textareaId: textareaId,
                text: originalText,
                lineIdx: lineIdx,
                deletedLine: deletedLine
            };

            if (textareaId === 'beforePreview') {
                handleRawInput();
            } else {
                saveTextareaCache();
                updateAfterLineNumbers();
                updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
            }

            const previewSnippet = deletedLine.trim();
            const shortSnippet = previewSnippet.length > 24 ? previewSnippet.slice(0, 24) + '...' : previewSnippet;
            showToastWithUndo(
                `Deleted row ${lineIdx + 1}${shortSnippet ? ': "' + shortSnippet + '"' : ''}`,
                () => undoLastDelete(),
                'delete',
                'Row Deleted'
            );
        }

        // Undo last deletion
        function undoLastDelete() {
            if (!lastDeletedState) return;
            const textarea = document.getElementById(lastDeletedState.textareaId);
            if (!textarea) return;

            textarea.value = lastDeletedState.text;
            if (lastDeletedState.textareaId === 'beforePreview') {
                handleRawInput();
            } else {
                saveTextareaCache();
                updateAfterLineNumbers();
                updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
            }
            showToast(`Restored row ${lastDeletedState.lineIdx >= 0 ? lastDeletedState.lineIdx + 1 : ''}`, 'success', 'Undo Successful');
            lastDeletedState = null;
        }

        // 1-Click duplicate cleaner for entire editor
        function removeAllDuplicates(textareaId = 'beforePreview') {
            const textarea = document.getElementById(textareaId);
            if (!textarea || !textarea.value) return;

            const originalText = textarea.value;
            const dupInfo = findDuplicates(originalText);
            if (dupInfo.count === 0) {
                showToast("No duplicate rows found to remove.", "info", "No Duplicates");
                return;
            }

            const lines = originalText.split('\n');
            const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
            const seenKeys = new Set();
            const newLines = [];
            let removedCount = 0;

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i];
                const trimmed = line.trim();

                if (!trimmed || /^[-=_*~]{3,}$/.test(trimmed)) {
                    newLines.push(line);
                    continue;
                }

                const emailMatch = trimmed.match(emailRegex);
                const key = emailMatch ? 'email:' + emailMatch[0].toLowerCase() : 'line:' + trimmed.toLowerCase();

                if (seenKeys.has(key)) {
                    removedCount++;
                } else {
                    seenKeys.add(key);
                    newLines.push(line);
                }
            }

            textarea.value = newLines.join('\n');

            lastDeletedState = {
                textareaId: textareaId,
                text: originalText,
                lineIdx: -1,
                deletedLine: `Removed ${removedCount} duplicates`
            };

            if (textareaId === 'beforePreview') {
                handleRawInput();
            } else {
                saveTextareaCache();
                updateAfterLineNumbers();
                updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
            }

            showToastWithUndo(
                `Removed ${removedCount} duplicate row${removedCount > 1 ? 's' : ''}.`,
                () => undoLastDelete(),
                'delete',
                'Duplicates Removed'
            );
        }

        // Smart Duplicate Detector — Checks EVERY row thoroughly
        function findDuplicates(text) {
            if (!text || !text.trim()) return { count: 0, lineMap: {}, totalDuplicateLines: 0 };

            const lines = text.split(/\r?\n/);
            const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
            const keyMap = {}; // key -> array of line indices
            const ignoredValues = new Set([
                'unknownuser', 'unknownpass', 'https://2fa-x.com/', 'http://2fa-x.com/', 'https://2fa-x.com', 'http://2fa-x.com'
            ]);

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i];
                const trimmed = line.trim();
                if (!trimmed || /^[-=_*~]{3,}$/.test(trimmed)) continue;
                if (ignoredValues.has(trimmed.toLowerCase())) continue;

                const emailMatch = trimmed.match(emailRegex);
                let key = '';

                if (emailMatch) {
                    key = 'email:' + emailMatch[0].toLowerCase();
                } else {
                    key = 'line:' + trimmed.toLowerCase();
                }

                if (key) {
                    if (!keyMap[key]) keyMap[key] = [];
                    keyMap[key].push(i);
                }
            }

            const lineMap = {};
            let distinctDupCount = 0;
            let totalDupLines = 0;

            for (const key in keyMap) {
                const indices = keyMap[key];
                if (indices.length > 1) {
                    distinctDupCount++;
                    const cleanKey = key.startsWith('email:') ? key.replace('email:', '') : key.replace('line:', '');
                    for (let idx = 0; idx < indices.length; idx++) {
                        const lineIdx = indices[idx];
                        const nextOccurrenceIdx = indices[(idx + 1) % indices.length];
                        lineMap[lineIdx] = {
                            line: lineIdx,
                            key: cleanKey,
                            targetLine: nextOccurrenceIdx,
                            occurrence: idx + 1,
                            total: indices.length,
                            allLines: indices
                        };
                        totalDupLines++;
                    }
                }
            }

            return {
                count: distinctDupCount,
                totalDuplicateLines: totalDupLines,
                lineMap: lineMap
            };
        }

        // Render Synchronized Backdrop & Gutter Duplicate Markers with Delete Icons
        function updateEditorBackdrop(wrapperId, textareaId, gutterId, backdropId) {
            const textarea = document.getElementById(textareaId);
            const gutter = document.getElementById(gutterId);
            const backdrop = document.getElementById(backdropId);
            if (!textarea) return {};

            const text = textarea.value;
            const dupInfo = findDuplicates(text);
            const lineMap = dupInfo.lineMap;
            const lines = text ? text.split('\n') : [''];

            if (textareaId === 'beforePreview') {
                currentBeforeDupMap = lineMap;
            } else {
                currentAfterDupMap = lineMap;
            }

            // Update Duplicate Chip and Clean Button in UI header
            const dupesChipId = (textareaId === 'beforePreview') ? 'beforeDupesChip' : 'afterDupesChip';
            const dupesValId = (textareaId === 'beforePreview') ? 'beforeDupesVal' : 'afterDupesVal';
            const cleanBtnId = (textareaId === 'beforePreview') ? 'beforeRemoveDupesBtn' : 'afterRemoveDupesBtn';
            
            const dupesChip = document.getElementById(dupesChipId);
            const cleanBtn = document.getElementById(cleanBtnId);

            if (dupInfo.count > 0) {
                if (dupesChip) {
                    dupesChip.style.display = 'inline-flex';
                    setStatVal(dupesValId, dupInfo.count);
                    dupesChip.title = `${dupInfo.totalDuplicateLines} duplicate rows (${dupInfo.count} unique). Click to cycle.`;
                }
                if (cleanBtn) cleanBtn.style.display = 'inline-flex';
            } else {
                if (dupesChip) dupesChip.style.display = 'none';
                if (cleanBtn) cleanBtn.style.display = 'none';
                setStatVal(dupesValId, 0);
            }

            // 1. Render ONLY Duplicate Highlight Bands (Zero text, instant 0.01ms rendering)
            const backdropTrackId = textareaId === 'beforePreview' ? 'beforeBackdropTrack' : 'afterBackdropTrack';
            let backdropTrack = document.getElementById(backdropTrackId);
            if (!backdropTrack && backdrop) {
                backdropTrack = document.createElement('div');
                backdropTrack.className = 'editor-backdrop-track';
                backdropTrack.id = backdropTrackId;
                backdrop.appendChild(backdropTrack);
            }

            if (backdropTrack) {
                const LINE_HEIGHT = 21.6;
                let html = '';
                if (text && dupInfo.totalDuplicateLines > 0) {
                    for (const lineIdxStr in lineMap) {
                        const i = parseInt(lineIdxStr, 10);
                        const dup = lineMap[i];
                        const topPos = 16 + (i * LINE_HEIGHT);
                        html += `<div class="dup-highlight-band" style="top:${topPos}px;" data-line="${i}"><div class="dup-band-bg"></div><div class="dup-band-actions"><span class="dup-marker-tag" onclick="jumpToLine('${textareaId}', ${dup.targetLine}, '${wrapperId}')" title="Duplicate (${dup.occurrence}/${dup.total}): ${escapeHtml(dup.key)} — Click to auto-scroll to line ${dup.targetLine + 1}"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Duplicate</span><button type="button" class="dup-delete-btn" onclick="deleteEditorRow('${textareaId}', ${i}, event)" title="Delete this duplicate row (Line ${i + 1})"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete</button></div></div>`;
                    }
                }
                backdropTrack.innerHTML = html;
                backdropTrack.style.transform = `translate3d(0, -${textarea.scrollTop}px, 0)`;
            }

            // 2. Mark line numbers in gutter track with red color & glowing dot
            const gutterTrackId = textareaId === 'beforePreview' ? 'beforeLineNumsTrack' : 'afterLineNumsTrack';
            const gutterTrack = document.getElementById(gutterTrackId) || gutter;
            if (gutterTrack) {
                const lineElements = gutterTrack.children;
                for (let i = 0; i < lineElements.length; i++) {
                    const el = lineElements[i];
                    const lineIdx = parseInt(el.getAttribute('data-line'), 10);
                    const dup = lineMap[lineIdx];
                    if (dup) {
                        el.classList.add('is-duplicate');
                        el.setAttribute('title', `Duplicate (${dup.occurrence}/${dup.total}): ${dup.key}\nClick to jump to line ${dup.targetLine + 1}`);
                        if (!el.querySelector('.dup-dot')) {
                            const dot = document.createElement('span');
                            dot.className = 'dup-dot';
                            el.insertBefore(dot, el.firstChild);
                        }
                    } else {
                        el.classList.remove('is-duplicate');
                        const dot = el.querySelector('.dup-dot');
                        if (dot) dot.remove();
                    }
                }
            }

            return lineMap;
        }

        // Smooth Auto-Scroll to Duplicate Line
        function jumpToLine(textareaId, targetLineIdx, wrapperId) {
            const textarea = document.getElementById(textareaId);
            const wrapper = document.getElementById(wrapperId);
            if (!textarea || !wrapper) return;

            const lineHeight = 21.6;
            const targetScrollTop = Math.max(0, (targetLineIdx * lineHeight) - (textarea.clientHeight / 2) + (lineHeight / 2));

            textarea.scrollTo({
                top: targetScrollTop,
                behavior: 'smooth'
            });

            // Flash highlight animation on the target row
            const flashId = wrapperId === 'beforeWrapper' ? 'beforeFlashHighlight' : 'afterFlashHighlight';
            const flashEl = document.getElementById(flashId);
            if (flashEl) {
                const topPos = 16 + (targetLineIdx * lineHeight) - targetScrollTop;
                flashEl.style.top = `${topPos}px`;
                flashEl.classList.remove('active-flash');
                void flashEl.offsetWidth; // trigger reflow
                flashEl.classList.add('active-flash');
            }

            const dupMap = (textareaId === 'beforePreview') ? currentBeforeDupMap : currentAfterDupMap;
            const dup = dupMap ? dupMap[targetLineIdx] : null;
            const keyText = dup ? ` (${dup.key})` : '';

            showToast(`Auto-scrolled to duplicate at Line ${targetLineIdx + 1}${keyText}`, 'warning', 'Duplicate Located', 2000);
        }

        // Cycle through all duplicates sequentially
        function cycleDuplicates(textareaId = 'beforePreview', wrapperId = 'beforeWrapper') {
            const dupMap = (textareaId === 'beforePreview') ? currentBeforeDupMap : currentAfterDupMap;
            const lines = Object.keys(dupMap).map(Number).sort((a, b) => a - b);
            if (!lines || lines.length === 0) {
                showToast("No duplicate entries found in file.", "info", "No Duplicates");
                return;
            }
            if (textareaId === 'beforePreview') {
                currentBeforeCycleIdx = (currentBeforeCycleIdx + 1) % lines.length;
                currentCycleIdx = currentBeforeCycleIdx;
                const targetLine = lines[currentBeforeCycleIdx];
                jumpToLine(textareaId, targetLine, wrapperId);
            } else {
                currentAfterCycleIdx = (currentAfterCycleIdx + 1) % lines.length;
                currentCycleIdx = currentAfterCycleIdx;
                const targetLine = lines[currentAfterCycleIdx];
                jumpToLine(textareaId, targetLine, wrapperId);
            }
        }

        // Row hover tracking & floating buttons (Copy, Delete, & Jump to Duplicate)
        function attachRowHover(wrapperId, textareaId, gutterId, copyBtnId, highlightId, dupJumpBtnId, deleteBtnId) {
            const wrapper = document.getElementById(wrapperId);
            const textarea = document.getElementById(textareaId);
            const gutter = document.getElementById(gutterId);
            const copyBtn = document.getElementById(copyBtnId);
            const highlightEl = document.getElementById(highlightId);
            const dupJumpBtn = document.getElementById(dupJumpBtnId);
            const deleteBtn = document.getElementById(deleteBtnId);
            if (!wrapper || !textarea || !gutter || !copyBtn) return;

            const LINE_HEIGHT = 21.6;
            let activeLineIndex = -1;
            let rafPending = false;
            let prevHoveredLineEl = null;
            let cachedRect = null;
            let isScrolling = false;
            let scrollTimer = null;
            let cachedLines = null;

            function refreshCachedRect() {
                cachedRect = textarea.getBoundingClientRect();
            }

            function getLines() {
                if (cachedLines === null) {
                    const text = textarea.value;
                    cachedLines = text ? text.split('\n') : [''];
                }
                return cachedLines;
            }

            textarea.addEventListener('input', () => {
                cachedLines = null;
            }, { passive: true });

            wrapper.addEventListener('mouseenter', refreshCachedRect, { passive: true });
            window.addEventListener('resize', refreshCachedRect, { passive: true });

            function hideFloatingButtons() {
                if (copyBtn) copyBtn.style.display = 'none';
                if (deleteBtn) deleteBtn.style.display = 'none';
                if (dupJumpBtn) dupJumpBtn.style.display = 'none';
                if (highlightEl) highlightEl.style.display = 'none';
                if (prevHoveredLineEl) {
                    prevHoveredLineEl.classList.remove('row-hovered');
                    if (!prevHoveredLineEl.classList.contains('is-duplicate')) {
                        prevHoveredLineEl.style.color = '';
                        prevHoveredLineEl.style.textShadow = '';
                    }
                    prevHoveredLineEl = null;
                }
            }

            function updateHover(e) {
                if (isScrolling) return;

                const lines = getLines();
                if (!lines || lines.length === 0 || (lines.length === 1 && lines[0] === '')) {
                    hideFloatingButtons();
                    return;
                }

                if (!cachedRect) refreshCachedRect();
                const rect = cachedRect;

                const relativeY = e.clientY - rect.top - 16 + textarea.scrollTop;
                const lineIdx = Math.floor(relativeY / LINE_HEIGHT);

                if (lineIdx >= 0 && lineIdx < lines.length && lines[lineIdx].trim().length > 0) {
                    activeLineIndex = lineIdx;
                    const theme = hoverPalettes[lineIdx % hoverPalettes.length];

                    const gutterTrackId = textareaId === 'beforePreview' ? 'beforeLineNumsTrack' : 'afterLineNumsTrack';
                    const gutterTrack = document.getElementById(gutterTrackId) || gutter;

                    // O(1) active line highlight in gutter
                    const currentEl = gutterTrack.children[lineIdx];
                    if (prevHoveredLineEl && prevHoveredLineEl !== currentEl) {
                        prevHoveredLineEl.classList.remove('row-hovered');
                        if (!prevHoveredLineEl.classList.contains('is-duplicate')) {
                            prevHoveredLineEl.style.color = '';
                            prevHoveredLineEl.style.textShadow = '';
                        }
                    }
                    if (currentEl) {
                        currentEl.classList.add('row-hovered');
                        if (!currentEl.classList.contains('is-duplicate')) {
                            currentEl.style.color = theme.text;
                            currentEl.style.textShadow = `0 0 8px ${theme.borderGlow}`;
                        }
                        prevHoveredLineEl = currentEl;
                    }

                    const topPos = 16 + (lineIdx * LINE_HEIGHT) - textarea.scrollTop;
                    if (topPos >= 4 && topPos <= textarea.clientHeight - 20) {
                        if (highlightEl) {
                            highlightEl.style.display = 'block';
                            highlightEl.style.top = `${topPos}px`;
                            highlightEl.style.borderLeftColor = theme.border;
                            highlightEl.style.borderTopColor = theme.borderGlow;
                            highlightEl.style.borderBottomColor = theme.borderGlow;
                            highlightEl.style.background = `linear-gradient(90deg, ${theme.bg} 0%, ${theme.bg.replace('0.22', '0.08')} 40%, ${theme.bg.replace('0.22', '0.02')} 100%)`;
                        }

                        const dupMap = (textareaId === 'beforePreview') ? currentBeforeDupMap : currentAfterDupMap;
                        const dup = dupMap ? dupMap[lineIdx] : null;

                        if (dup) {
                            // Row already has permanent [▲ DUPLICATE] and [Delete] in .dup-band-actions
                            copyBtn.style.display = 'inline-flex';
                            copyBtn.style.top = `${topPos}px`;
                            copyBtn.style.right = '172px';
                            copyBtn.style.borderColor = theme.border;
                            copyBtn.style.boxShadow = `0 4px 14px rgba(0, 0, 0, 0.5), 0 0 10px ${theme.borderGlow}`;
                            if (deleteBtn) deleteBtn.style.display = 'none';
                            if (dupJumpBtn) dupJumpBtn.style.display = 'none';
                        } else {
                            copyBtn.style.display = 'inline-flex';
                            copyBtn.style.top = `${topPos}px`;
                            copyBtn.style.right = '12px';
                            copyBtn.style.borderColor = theme.border;
                            copyBtn.style.boxShadow = `0 4px 14px rgba(0, 0, 0, 0.5), 0 0 10px ${theme.borderGlow}`;
                            if (deleteBtn) {
                                deleteBtn.style.display = 'inline-flex';
                                deleteBtn.style.top = `${topPos}px`;
                                deleteBtn.style.right = '86px';
                                deleteBtn.classList.remove('is-dup-row');
                                deleteBtn.title = `Delete row ${lineIdx + 1}`;
                            }
                            if (dupJumpBtn) dupJumpBtn.style.display = 'none';
                        }
                    } else {
                        hideFloatingButtons();
                    }
                } else {
                    activeLineIndex = -1;
                    hideFloatingButtons();
                }
            }

            wrapper.addEventListener('mousemove', (e) => {
                if (isScrolling) return;
                if (rafPending) return;
                rafPending = true;
                requestAnimationFrame(() => {
                    updateHover(e);
                    rafPending = false;
                });
            }, { passive: true });

            wrapper.addEventListener('mouseleave', () => {
                activeLineIndex = -1;
                hideFloatingButtons();
            }, { passive: true });

            textarea.addEventListener('scroll', () => {
                isScrolling = true;
                wrapper.classList.add('is-scrolling');
                hideFloatingButtons();
                if (scrollTimer) clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    isScrolling = false;
                    wrapper.classList.remove('is-scrolling');
                }, 70);
            }, { passive: true });

            copyBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (activeLineIndex !== -1) {
                    const lineNumEl = gutter.querySelector(`.line-num[data-line="${activeLineIndex}"]`);
                    copyRowText(textareaId, activeLineIndex, copyBtn, lineNumEl);
                }
            });

            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (activeLineIndex !== -1) {
                        const targetIdx = activeLineIndex;
                        hideFloatingButtons();
                        deleteEditorRow(textareaId, targetIdx, e);
                    }
                });
            }

            // Gutter direct click to jump (for duplicates) or copy (for lines)
            gutter.addEventListener('click', (e) => {
                const lineEl = e.target.closest('.line-num');
                if (!lineEl) return;
                const lineIdx = parseInt(lineEl.getAttribute('data-line'), 10);
                if (!isNaN(lineIdx)) {
                    const dupMap = (textareaId === 'beforePreview') ? currentBeforeDupMap : currentAfterDupMap;
                    const dup = dupMap ? dupMap[lineIdx] : null;

                    if (dup && (e.target.closest('.dup-dot') || lineEl.classList.contains('is-duplicate'))) {
                        jumpToLine(textareaId, dup.targetLine, wrapperId);
                        return;
                    }

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
                label.innerHTML = (afterGutterMode === 'group') ? 'Group&nbsp;#' : 'Line&nbsp;#';
            }
            const btn = document.getElementById('gutterModeBtn');
            if (btn) {
                btn.title = (afterGutterMode === 'group') 
                    ? "Currently numbering by Account Groups (click to switch to Line #)" 
                    : "Currently numbering by Lines (click to switch to Group #)";
            }
            updateAfterLineNumbers();
            updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
        }

        function updateBeforeLineNumbers() {
            const textarea = document.getElementById('beforePreview');
            const gutter = document.getElementById('beforeLineNums');
            if (!textarea || !gutter) return;
            let track = document.getElementById('beforeLineNumsTrack');
            if (!track) {
                track = document.createElement('div');
                track.className = 'line-numbers-track';
                track.id = 'beforeLineNumsTrack';
                gutter.appendChild(track);
            }
            const text = textarea.value;
            const lines = text ? text.split('\n') : [''];
            let html = '';
            for (let i = 0; i < lines.length; i++) {
                html += `<span class="line-num" data-line="${i}" title="Click to copy line ${i + 1}">${i + 1}</span>`;
            }
            track.innerHTML = html;
            track.style.transform = `translate3d(0, -${textarea.scrollTop}px, 0)`;
        }

        function updateAfterLineNumbers() {
            const textarea = document.getElementById('afterPreview');
            const gutter = document.getElementById('afterLineNums');
            if (!textarea || !gutter) return;
            let track = document.getElementById('afterLineNumsTrack');
            if (!track) {
                track = document.createElement('div');
                track.className = 'line-numbers-track';
                track.id = 'afterLineNumsTrack';
                gutter.appendChild(track);
            }

            const text = textarea.value;
            if (!text) {
                track.innerHTML = '<span class="line-num" data-line="0">&nbsp;</span>';
                track.style.transform = 'translate3d(0, 0, 0)';
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
            track.innerHTML = html;
            track.style.transform = `translate3d(0, -${textarea.scrollTop}px, 0)`;
        }

        function updateLineNumbers(textarea, gutter) {
            if (textarea && textarea.id === 'afterPreview') {
                updateAfterLineNumbers();
            } else {
                updateBeforeLineNumbers();
            }
        }

        function syncScroll(textarea, gutterTrack, backdropTrack) {
            const sTop = textarea.scrollTop;
            if (gutterTrack) {
                gutterTrack.style.transform = `translate3d(0, -${sTop}px, 0)`;
            }
            if (backdropTrack) {
                backdropTrack.style.transform = `translate3d(0, -${sTop}px, 0)`;
            }
        }

        function setupEditor(textareaId, gutterId, backdropId) {
            const textarea = document.getElementById(textareaId);
            const gutter = document.getElementById(gutterId);
            const backdrop = document.getElementById(backdropId);
            if (!textarea) return;

            const gutterTrackId = textareaId === 'beforePreview' ? 'beforeLineNumsTrack' : 'afterLineNumsTrack';
            const backdropTrackId = textareaId === 'beforePreview' ? 'beforeBackdropTrack' : 'afterBackdropTrack';

            function getTracks() {
                return {
                    gutterTrack: document.getElementById(gutterTrackId),
                    backdropTrack: document.getElementById(backdropTrackId)
                };
            }

            textarea.addEventListener('scroll', () => {
                const tracks = getTracks();
                syncScroll(textarea, tracks.gutterTrack, tracks.backdropTrack);
            }, { passive: true });

            // Forward wheel events over gutter to textarea with passive listener (zero lag)
            if (gutter) {
                gutter.addEventListener('wheel', (e) => {
                    textarea.scrollTop += e.deltaY;
                    textarea.scrollLeft += e.deltaX;
                }, { passive: true });
            }

            textarea.addEventListener('input', () => {
                updateLineNumbers(textarea, gutter);
                const wrapperId = textareaId === 'beforePreview' ? 'beforeWrapper' : 'afterWrapper';
                updateEditorBackdrop(wrapperId, textareaId, gutterId, backdropId);
            });
            updateLineNumbers(textarea, gutter);
        }

        // Initialize editors after DOM loads
        window.addEventListener('DOMContentLoaded', () => {
            setupEditor('beforePreview', 'beforeLineNums', 'beforeBackdrop');
            setupEditor('afterPreview', 'afterLineNums', 'afterBackdrop');
            attachRowHover('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeRowCopy', 'beforeRowHighlight', 'beforeRowDupJump', 'beforeRowDelete');
            attachRowHover('afterWrapper', 'afterPreview', 'afterLineNums', 'afterRowCopy', 'afterRowHighlight', 'afterRowDupJump', 'afterRowDelete');

            const beforeDupesChip = document.getElementById('beforeDupesChip');
            if (beforeDupesChip) {
                beforeDupesChip.style.cursor = 'pointer';
                beforeDupesChip.addEventListener('click', () => {
                    cycleDuplicates('beforePreview', 'beforeWrapper');
                });
            }

            const dupesChip = document.getElementById('afterDupesChip');
            if (dupesChip) {
                dupesChip.style.cursor = 'pointer';
                dupesChip.setAttribute('title', 'Duplicate Accounts — Click to auto-scroll and cycle through duplicates');
                dupesChip.addEventListener('click', () => {
                    cycleDuplicates('afterPreview', 'afterWrapper');
                });
            }
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
                
                updateBeforeLineNumbers();
                updateEditorBackdrop('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeBackdrop');
                
                // If they had manual edits, restore them. Otherwise re-process.
                if (savedAfter && savedAfter.trim().length > 0) {
                    afterPreview.value = savedAfter;
                    updateAfterLineNumbers();
                    updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
                    downloadBtn.disabled = false;
                    copyBtn.disabled = false;
                    statusDiv.innerText = "Restored from cache.";
                    statusDiv.style.color = "var(--text-muted)";
                    updateAfterStats(savedAfter);

                    // Critical fix: If accounts stat resolved to 0 but raw input exists, auto-reprocess!
                    const restoredAccs = parseInt(document.getElementById('afterAccountsVal').textContent) || 0;
                    if (restoredAccs === 0) {
                        processFile(true);
                    }
                } else {
                    processFile(true);
                }
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
            updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
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
            const twoFaMatches = text.match(/https?:\/\/[^\s\r\n]*(?:2fa|auth|otp|token|[A-Z0-9]{16,64})[^\s\r\n]*/gi) || [];

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
            let duplicates = (savedStats && savedStats.duplicates !== undefined) ? savedStats.duplicates : dupeCount;

            if (savedStats && savedStats.accounts > 0 && (savedStats.outputLines === totalLines || savedStats.accounts === accounts)) {
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
            currentBeforeDupMap = {};
            currentAfterDupMap = {};
            const beforeTrack = document.getElementById('beforeBackdropTrack');
            const afterTrack = document.getElementById('afterBackdropTrack');
            if (beforeTrack) { beforeTrack.innerHTML = ''; beforeTrack.style.transform = 'translate3d(0, 0, 0)'; }
            if (afterTrack) { afterTrack.innerHTML = ''; afterTrack.style.transform = 'translate3d(0, 0, 0)'; }
            beforePreview.classList.remove('has-backdrop');
            afterPreview.classList.remove('has-backdrop');
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
            const beforeDupesChip = document.getElementById('beforeDupesChip');
            if (beforeDupesChip) beforeDupesChip.style.display = 'none';
            setStatVal('beforeDupesVal', 0);
            const beforeRemoveDupesBtn = document.getElementById('beforeRemoveDupesBtn');
            if (beforeRemoveDupesBtn) beforeRemoveDupesBtn.style.display = 'none';
            const afterRemoveDupesBtn = document.getElementById('afterRemoveDupesBtn');
            if (afterRemoveDupesBtn) afterRemoveDupesBtn.style.display = 'none';
            
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
            
            updateBeforeLineNumbers();
            updateEditorBackdrop('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeBackdrop');
            
            if (rawContent) {
                formatBtn.disabled = false;
                toggleSmartTools(true);
                debouncedProcessFile();
            } else {
                formatBtn.disabled = true;
                toggleSmartTools(false);
                afterPreview.value = "";
                updateAfterLineNumbers();
                updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
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
                const beforeDupesChip = document.getElementById('beforeDupesChip');
                if (beforeDupesChip) beforeDupesChip.style.display = 'none';
                setStatVal('beforeDupesVal', 0);
                const beforeRemoveDupesBtn = document.getElementById('beforeRemoveDupesBtn');
                if (beforeRemoveDupesBtn) beforeRemoveDupesBtn.style.display = 'none';
                const afterRemoveDupesBtn = document.getElementById('afterRemoveDupesBtn');
                if (afterRemoveDupesBtn) afterRemoveDupesBtn.style.display = 'none';
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
                    updateEditorBackdrop('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeBackdrop');
                    
                    const lineCount = rawContent.split(/\r?\n/).length;
                    beforeLinesBadge.textContent = `${lineCount} Lines`;
                    setStatVal('beforeLinesVal', lineCount);
                    
                    // Reset after state
                    afterPreview.value = "";
                    updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
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
                const seenAccounts = new Set();
                let duplicateCount = 0;

                const emailMatchRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
                const emailLineRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const twoFaUrlRegex = /^https?:\/\/[^\s]*(?:2fa|auth|otp|token|[A-Z0-9]{16,64})/i;

                // Helper: add a parsed account to groups (Deduplication enabled: never output duplicate data!)
                function pushAccount(accountString, username, email, hasTwoFa) {
                    let isDuplicate = false;
                    const emailLower = email ? email.trim().toLowerCase() : '';
                    const userLower = username ? username.trim().toLowerCase() : '';

                    if (emailLower) {
                        if (seenEmails.has(emailLower)) {
                            isDuplicate = true;
                        } else {
                            seenEmails.add(emailLower);
                        }
                    } else if (userLower && userLower !== 'unknownuser') {
                        if (seenAccounts.has('user:' + userLower)) {
                            isDuplicate = true;
                        } else {
                            seenAccounts.add('user:' + userLower);
                        }
                    } else {
                        const accountKey = accountString.trim().toLowerCase();
                        if (seenAccounts.has(accountKey)) {
                            isDuplicate = true;
                        } else {
                            seenAccounts.add(accountKey);
                        }
                    }

                    if (isDuplicate) {
                        duplicateCount++;
                    }

                    // Always add accounts to output; duplicates are highlighted and can be cleanly removed via "Remove Duplicates"
                    currentGroup.push(accountString);
                    successCount++;
                    if (emailLower) emailCount++;
                    if (username && username !== '' && username !== 'UnknownUser') {
                        usernameCount++;
                    }
                    if (hasTwoFa) {
                        twoFaCount++;
                    }

                    if (currentGroup.length === groupSize) {
                        groups.push(currentGroup.join('\n\n'));
                        currentGroup = [];
                    }
                    return true;
                }

                // Helper: parse a single-line account with delimiters (Robust and professional)
                function parseDelimiterLine(line) {
                    if (!line || !line.trim()) return false;
                    const trimmedLine = line.trim();

                    // 1. Locate email in line
                    const emailMatch = trimmedLine.match(emailMatchRegex);
                    if (!emailMatch) return false;

                    const email = emailMatch[0];
                    const emailStart = emailMatch.index;
                    const emailEnd = emailStart + email.length;
                    const supportedDelims = [':', '|', ';', ',', '\t'];

                    // Identify the delimiter immediately surrounding the email
                    const charBefore = emailStart > 0 ? trimmedLine[emailStart - 1] : '';
                    const charAfter = emailEnd < trimmedLine.length ? trimmedLine[emailEnd] : '';

                    let bestDelimiter = ':';
                    if (charBefore && supportedDelims.includes(charBefore)) {
                        bestDelimiter = charBefore;
                    } else if (charAfter && supportedDelims.includes(charAfter)) {
                        bestDelimiter = charAfter;
                    } else {
                        let maxSplits = 0;
                        for (let char of supportedDelims) {
                            let splits = trimmedLine.split(char).length;
                            if (splits > maxSplits) { maxSplits = splits; bestDelimiter = char; }
                        }
                    }

                    let parts = trimmedLine.split(bestDelimiter);
                    let emailIndex = parts.findIndex(p => emailLineRegex.test(p.trim()) || emailMatchRegex.test(p.trim()));
                    if (emailIndex === -1) return false;

                    // Smart username extraction
                    let username = '';
                    if (emailIndex >= 2) {
                        username = parts[emailIndex - 2].trim();
                    } else if (emailIndex === 1) {
                        username = parts[0].trim();
                    } else {
                        username = email.split('@')[0];
                    }

                    // Smart password extraction
                    let password = '';
                    if (emailIndex >= 1) {
                        password = parts[emailIndex - 1].trim();
                    } else if (parts.length > 1) {
                        password = parts[1].trim();
                    }
                    if (!password) password = "UnknownPass";

                    // Smart email password extraction (clean any pipe tokens)
                    let emailPass = '';
                    let afterEmailStr = '';
                    if (emailIndex + 1 < parts.length) {
                        afterEmailStr = parts.slice(emailIndex + 1).join(bestDelimiter);
                        let firstSegment = parts[emailIndex + 1].trim();
                        if (firstSegment.includes('|')) {
                            emailPass = firstSegment.split('|')[0].trim();
                        } else {
                            emailPass = firstSegment.replace(/\|M\.C\d+_BAY[^|]*\|[\w-]+$/g, "").trim();
                        }
                    }
                    if (!emailPass) emailPass = password;

                    // Smart 2FA Extraction
                    let twoFaUrl = "";
                    let hasTwoFa = false;
                    if (afterEmailStr) {
                        let urlMatch = afterEmailStr.match(/https?:\/\/[^\s|;:]+/i);
                        if (urlMatch) {
                            twoFaUrl = urlMatch[0];
                            hasTwoFa = true;
                        } else {
                            let codeMatch = afterEmailStr.match(/\b[A-Z0-9]{16,64}\b/);
                            if (codeMatch) {
                                twoFaUrl = `https://2fa-x.com/${codeMatch[0]}`;
                                hasTwoFa = true;
                            }
                        }
                    }
                    if (!twoFaUrl) twoFaUrl = 'https://2fa-x.com/';

                    const accountString = `${username}\n${password}\n${email}\n${emailPass}\n${twoFaUrl}`;
                    return pushAccount(accountString, username, email, hasTwoFa);
                }

                // --- Split input into blocks separated by blank lines or separator lines ---
                let blocks = [];
                let currentBlock = [];
                const currentSepTrimmed = customSeparator ? customSeparator.trim() : '';
                for (let i = 0; i < lines.length; i++) {
                    const trimmed = lines[i].trim();
                    const isSepLine = currentSepTrimmed && (trimmed === currentSepTrimmed || (currentSepTrimmed.startsWith('---') && trimmed.startsWith('---') && trimmed.length >= 3));
                    if (trimmed === '' || isSepLine) {
                        if (currentBlock.length > 0) {
                            blocks.push(currentBlock);
                            currentBlock = [];
                        }
                    } else {
                        currentBlock.push(trimmed);
                    }
                }
                if (currentBlock.length > 0) blocks.push(currentBlock);

                // --- Process each block individually (supports pre-formatted & delimiter formats) ---
                for (let b of blocks) {
                    // Check how many lines in this block contain an email
                    const emailIndices = [];
                    for (let idx = 0; idx < b.length; idx++) {
                        if (emailMatchRegex.test(b[idx])) {
                            emailIndices.push(idx);
                        }
                    }

                    // Pre-formatted multi-line account: exactly 1 email line and at least 2 lines in block
                    if (emailIndices.length === 1 && b.length >= 2) {
                        const emailIdx = emailIndices[0];
                        const emailMatch = b[emailIdx].match(emailMatchRegex);
                        const email = emailMatch ? emailMatch[0] : b[emailIdx].trim();
                        
                        let username = 'UnknownUser';
                        if (emailIdx >= 2) {
                            username = b[emailIdx - 2].trim();
                        } else if (emailIdx >= 1) {
                            username = b[0].trim();
                        } else if (b.length > 1 && emailIdx === 0) {
                            username = email.split('@')[0];
                        }

                        const hasTwoFa = b.some(l => twoFaUrlRegex.test(l) || /2fa/i.test(l) || /\b[A-Z0-9]{16,64}\b/.test(l));
                        const accountString = b.join('\n');
                        pushAccount(accountString, username, email, hasTwoFa);
                    } else {
                        // Multi-account block or single delimiter accounts: parse line by line
                        for (let line of b) {
                            const parsed = parseDelimiterLine(line);
                            if (!parsed && line.trim()) {
                                pushAccount(line.trim(), line.trim(), '', false);
                            }
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
                updateEditorBackdrop('afterWrapper', 'afterPreview', 'afterLineNums', 'afterBackdrop');
                updateEditorBackdrop('beforeWrapper', 'beforePreview', 'beforeLineNums', 'beforeBackdrop');
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
                
                let msg = `Success! Processed ${successCount} unique accounts (Grouped by ${groupSize}).`;
                if (duplicateCount > 0) {
                    msg = `Success! Processed ${successCount} unique accounts (${duplicateCount} duplicate${duplicateCount > 1 ? 's' : ''} removed).`;
                }
                if (replacedCount > 0) {
                    msg += ` Replaced ${replacedCount} occurrence(s).`;
                }
                
                statusDiv.innerText = msg;
                statusDiv.style.color = "var(--success)";

                if (!isAuto) {
                    let flashMsg = `Formatted ${successCount} unique accounts into ${groups.length} group${groups.length > 1 ? 's' : ''}.`;
                    if (duplicateCount > 0) flashMsg += ` (${duplicateCount} duplicate${duplicateCount > 1 ? 's' : ''} removed)`;
                    if (replacedCount > 0) flashMsg += ` • ${replacedCount} replacements`;
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