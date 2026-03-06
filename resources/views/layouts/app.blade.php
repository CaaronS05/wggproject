<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SeasonStock — Inventory Simulator')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (for development) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- GSAP + plugins --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['"Playfair Display"', 'Georgia', 'serif'],
                        'body': ['"DM Sans"', 'sans-serif'],
                    },
                    colors: {
                        /* WGG Primary — deep blue-navy */
                        'bark':   {
                            50:  '#f0f4ff',
                            100: '#dde8ff',
                            200: '#c3d4ff',
                            300: '#9db6ff',
                            400: '#7090f5',
                            500: '#4f6de8',
                            600: '#3a52d4',
                            700: '#2d3faa',
                            800: '#253488',
                            900: '#1e2d6e',
                        },
                        /* WGG Accent — soft purple-lavender */
                        'forest': {
                            50:  '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        /* WGG Mid — periwinkle/cornflower */
                        'clay':   {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        /* Neutral — cool blue-grey */
                        'slate':  {
                            50:  '#f8faff',
                            100: '#f0f4ff',
                            200: '#dde6ff',
                            300: '#b8caed',
                            400: '#8aa3cc',
                            500: '#6080aa',
                            600: '#486090',
                            700: '#3a4f78',
                            800: '#2e3f62',
                            900: '#263452',
                        },
                        'cream':  '#f0f4ff',
                        'parch':  '#e8eeff',
                    },
                    boxShadow: {
                        'warm':    '0 4px 24px rgba(79,109,232,0.14)',
                        'warm-lg': '0 8px 40px rgba(79,109,232,0.22)',
                        'season':  '0 2px 16px rgba(30,45,110,0.10)',
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', Georgia, serif; }

        /* ═══════════════════════════════════════
           CSS Variables — WGG 2026 Ocean Theme
        ═══════════════════════════════════════ */
        :root {
            /* Core palette — arctic ocean */
            --wgg-sky:          #8ec8e8;
            --wgg-ocean:        #4a7fa5;
            --wgg-deep:         #2c4f72;
            --wgg-abyss:        #1a2d47;
            --wgg-ice:          #c8e8f4;
            --wgg-frost:        rgba(200,232,244,0.15);
            --wgg-glow:         rgba(142,200,232,0.25);

            /* Glass card system */
            --bg-card:          rgba(255,255,255,0.12);
            --bg-card-solid:    rgba(255,255,255,0.18);
            --bg-parch:         rgba(200, 232, 244, 0.08);
            --bg-input:         rgba(255,255,255,0.15);
            --border-card:      rgba(200,232,244,0.3);
            --border-input:     rgba(200,232,244,0.4);

            /* Navbar */
            --navbar-bg:        rgba(26,45,71,0.75);
            --navbar-border:    rgba(200,232,244,0.15);

            /* Text — always light on dark ocean bg */
            --text-primary:     #e8f4fc;
            --text-secondary:   #a8d0e8;
            --text-muted:       #7aadcc;
            --text-body:        #c0ddf0;

            /* Shadows */
            --shadow-card:      0 8px 32px rgba(0,0,0,0.25), 0 2px 8px rgba(0,0,0,0.15);
            --shadow-hover:     0 16px 48px rgba(0,0,0,0.35), 0 4px 16px rgba(142,200,232,0.2);

            /* Table */
            --table-head-bg:    rgba(44,79,114,0.6);
            --table-row-hover:  rgba(200,232,244,0.08);
            --table-divider:    rgba(200,232,244,0.12);

            /* Scrollbar */
            --scrollbar-track:  #1a2d47;
            --scrollbar-thumb:  #4a7fa5;
        }

        /* Dark mode — deeper ocean */
        body.dark {
            --bg-card:          rgba(15,30,55,0.7);
            --bg-card-solid:    rgba(18,34,60,0.85);
            --bg-parch:         rgba(15,30,55,0.5);
            --bg-input:         rgba(15,30,55,0.6);
            --border-card:      rgba(100,160,210,0.2);
            --border-input:     rgba(100,160,210,0.3);
            --navbar-bg:        rgba(8,18,35,0.88);
            --navbar-border:    rgba(100,160,210,0.12);
            --text-primary:     #e0f0fc;
            --text-secondary:   #90c0e0;
            --text-muted:       #5090b8;
            --text-body:        #b0d4ec;
            --shadow-card:      0 8px 32px rgba(0,0,0,0.5);
            --shadow-hover:     0 16px 48px rgba(0,0,0,0.6);
            --table-head-bg:    rgba(10,22,45,0.8);
            --table-row-hover:  rgba(100,160,210,0.06);
            --table-divider:    rgba(100,160,210,0.08);
            --scrollbar-track:  #0a1628;
            --scrollbar-thumb:  #2c5a80;
        }

        /* ═══════════════════════════════════════
           Base — WGG 2026 arctic ocean background
        ═══════════════════════════════════════ */
        body {
            background: linear-gradient(180deg,
                #6ab0d4 0%,
                #4a8ab8 18%,
                #3a6b96 35%,
                #2c5278 52%,
                #5b4f8a 70%,
                #7a5fa0 85%,
                #8a6ab8 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            transition: color 0.4s ease;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 0%, rgba(142,200,232,0.18) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 60%, rgba(120,90,160,0.2) 0%, transparent 50%),
                radial-gradient(ellipse at 10% 80%, rgba(74,127,165,0.15) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        .content-layer { position: relative; z-index: 1; }

        /* ═══════════════════════════════════════
           Navbar
        ═══════════════════════════════════════ */
        .navbar-glass {
            background: var(--navbar-bg);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--navbar-border);
            transition: background 0.5s ease, border-color 0.5s ease;
        }

        /* ═══════════════════════════════════════
           Cards — WGG glassmorphism (pekat)
        ═══════════════════════════════════════ */
        .card-season {
            background: rgba(15,35,65,0.72);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(200,232,244,0.22);
            box-shadow: 0 8px 32px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .card-season:hover {
            background: rgba(20,45,80,0.80);
            border-color: rgba(200,232,244,0.38);
            box-shadow: 0 16px 48px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }

        .stat-card {
            transition: all 0.25s ease;
            background: rgba(15,35,65,0.68);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(200,232,244,0.2);
            box-shadow: 0 4px 24px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.07);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            background: rgba(20,45,80,0.78);
            border-color: rgba(200,232,244,0.35);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
        }

        /* ═══════════════════════════════════════
           Buttons
        ═══════════════════════════════════════ */
        .btn-primary {
            background: linear-gradient(135deg, rgba(74,127,165,0.9) 0%, rgba(44,79,114,0.95) 100%);
            color: #e8f4fc;
            border: 1px solid rgba(200,232,244,0.35);
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, rgba(100,160,200,0.9) 0%, rgba(60,100,145,0.95) 100%);
            border-color: rgba(200,232,244,0.55);
            box-shadow: 0 6px 24px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }

        /* ═══════════════════════════════════════
           Text — always light on ocean background
        ═══════════════════════════════════════ */
        .text-slate-800, .text-bark-900, .text-bark-800 { color: #e8f4fc !important; }
        .text-slate-700 { color: #c8e4f0 !important; }
        .text-slate-600 { color: #a8cce0 !important; }
        .text-slate-500, .text-bark-500  { color: #88b8d4 !important; }
        .text-slate-400, .text-slate-300 { color: #6898b8 !important; }
        .text-bark-700  { color: #b8ddf0 !important; }
        .text-bark-600  { color: #98c8e4 !important; }

        /* Borders + backgrounds on glass */
        .border-bark-100, .border-bark-50  { border-color: rgba(200,232,244,0.18) !important; }
        .border-slate-200 { border-color: rgba(200,232,244,0.2) !important; }
        .bg-parch\/50, .bg-bark-50  { background: rgba(255,255,255,0.06) !important; }
        .bg-bark-100                { background: rgba(255,255,255,0.1) !important; }
        .hover\:bg-slate-50:hover   { background: rgba(255,255,255,0.08) !important; }
        .hover\:bg-bark-50:hover    { background: rgba(255,255,255,0.08) !important; }
        .hover\:bg-bark-100:hover   { background: rgba(255,255,255,0.12) !important; }

        thead tr { background: rgba(44,79,114,0.5) !important; }
        .table-row-hover:hover { background: rgba(200,232,244,0.07) !important; }
        .divide-bark-50 > * + * { border-color: rgba(200,232,244,0.1) !important; }

        .bg-bark-100.text-bark-700 {
            background: rgba(142,200,232,0.18) !important;
            color: #c8e8f4 !important;
            border-color: rgba(142,200,232,0.3) !important;
        }

        .bg-bark-100 { background: rgba(255,255,255,0.1) !important; }
        .bg-red-50   { background: rgba(239,68,68,0.12) !important; }
        .bg-amber-50 { background: rgba(245,158,11,0.12) !important; }
        .bg-green-50 { background: rgba(34,197,94,0.12) !important; }

        .bg-bark-50     { background: rgba(255,255,255,0.07) !important; }
        .border-bark-200  { border-color: rgba(200,232,244,0.22) !important; }
        .border-slate-100 { border-color: rgba(200,232,244,0.12) !important; }
        footer { border-color: rgba(200,232,244,0.12) !important; }
        .border-t { border-color: rgba(200,232,244,0.12) !important; }
        .bg-white { background: rgba(15,35,65,0.72) !important; backdrop-filter: blur(20px); }
        .bg-red-50\/50 { background: rgba(239,68,68,0.12) !important; }
        .border-red-100 { border-color: rgba(239,68,68,0.22) !important; }

        /* Dark mode — deepen everything */
        body.dark .bg-white { background: rgba(8,20,42,0.82) !important; }
        body.dark thead tr  { background: rgba(10,22,45,0.75) !important; }

        /* ═══════════════════════════════════════
           Stock badges — glass style
        ═══════════════════════════════════════ */
        .badge-empty   { background: rgba(239,68,68,0.22);   color: #fca5a5; border: 1px solid rgba(239,68,68,0.4); }
        .badge-low     { background: rgba(245,158,11,0.22);  color: #fcd34d; border: 1px solid rgba(245,158,11,0.4); }
        .badge-medium  { background: rgba(142,200,232,0.22); color: #b8e4f8; border: 1px solid rgba(142,200,232,0.4); }
        .badge-high    { background: rgba(34,197,94,0.22);   color: #86efac; border: 1px solid rgba(34,197,94,0.4); }

        /* Dots */
        .dot-empty  { background: #ef4444; animation: pulse-red 2s infinite; }
        .dot-low    { background: #f59e0b; animation: pulse-amber 2s infinite; }
        .dot-medium { background: #4f6de8; }
        .dot-high   { background: #22c55e; }

        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            50%       { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        }
        @keyframes pulse-amber {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0.4); }
            50%       { box-shadow: 0 0 0 6px rgba(245,158,11,0); }
        }

        /* ═══════════════════════════════════════
           Table / inputs / misc
        ═══════════════════════════════════════ */
        .table-row-hover { transition: background 0.15s ease; }
        .table-row-hover:hover { background: rgba(200,232,244,0.07); }

        .input-season {
            border: 1.5px solid rgba(200,232,244,0.35);
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            color: #e8f4fc;
            transition: all 0.2s ease;
        }
        .input-season::placeholder { color: rgba(200,232,244,0.5); }
        .input-season:focus {
            outline: none;
            border-color: rgba(200,232,244,0.6);
            box-shadow: 0 0 0 3px rgba(142,200,232,0.15);
            background: rgba(255,255,255,0.16);
        }

        .page-enter { animation: fadeUp 0.4s ease forwards; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════
           Page Reveal — Loader Screen
        ═══════════════════════════════════════ */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            pointer-events: all;
            background: #0d1633;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Water-like shimmer on loader */
        #page-loader::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(79,109,232,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 75% 65%, rgba(139,92,246,0.14) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Blue-purple radial glow */
        #loader-glow {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79,109,232,0.14) 0%, rgba(139,92,246,0.08) 40%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* Main text block */
        #loader-text-block {
            position: relative;
            z-index: 2;
            text-align: center;
            line-height: 1;
        }

        .loader-line {
            display: block;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .loader-line-inner {
            display: block;
            transform: translateY(110%);
            will-change: transform;
        }

        /* Line 1 — "Strengthened Through" */
        .loader-l2 {
            font-family: 'DM Sans', sans-serif;
            font-size: clamp(1rem, 3vw, 2rem);
            font-weight: 300;
            color: rgba(165,180,252,0.75);
            letter-spacing: 0.28em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* Line 2 — "Every" */
        .loader-l1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(2.8rem, 8vw, 7rem);
            font-weight: 700;
            color: #dde8ff;
            letter-spacing: -0.02em;
        }

        /* Line 3 — "Season." */
        .loader-l3 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(2.8rem, 8vw, 7rem);
            font-weight: 700;
            color: #818cf8;
            letter-spacing: -0.02em;
            font-style: italic;
        }

        /* Divider */
        #loader-divider {
            width: 0px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(129,140,248,0.55), transparent);
            margin: 24px auto;
            position: relative;
            z-index: 2;
        }

        /* Bottom meta */
        #loader-meta {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
        }
        #loader-meta-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #4f6de8, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #loader-meta-text {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            color: rgba(165,180,252,0.65);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* Progress line */
        #loader-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(79,109,232,0.1);
            z-index: 3;
        }
        #loader-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #3a52d4, #818cf8, #dde8ff);
        }

        /* Exit curtain */
        #loader-curtain {
            position: fixed;
            inset: 0;
            z-index: 99998;
            transform-origin: top;
            transform: scaleY(0);
            background: #0d1633;
            pointer-events: none;
        }

        /* Mask so content doesn't show during reveal */
        #site-content {
            opacity: 0;
            will-change: opacity, transform;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--scrollbar-track); }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 3px; }

        /* ═══════════════════════════════════════
           Dark Mode Toggle Button
        ═══════════════════════════════════════ */
        .dm-toggle {
            position: relative;
            width: 44px;
            height: 24px;
            border-radius: 999px;
            cursor: pointer;
            border: 1px solid rgba(200,232,244,0.3);
            outline: none;
            overflow: hidden;
            flex-shrink: 0;
            transition: background 0.4s ease;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        body.dark .dm-toggle {
            background: rgba(10,22,45,0.6);
            border-color: rgba(100,160,210,0.25);
        }
        .dm-thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        body.dark .dm-thumb {
            transform: translateX(20px);
            background: #c7d2fe;
        }
        .dm-icon-sun, .dm-icon-moon {
            position: absolute;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .dm-icon-sun  { opacity: 1; transform: scale(1); }
        .dm-icon-moon { opacity: 0; transform: scale(0.5); }
        body.dark .dm-icon-sun  { opacity: 0; transform: scale(0.5); }
        body.dark .dm-icon-moon { opacity: 1; transform: scale(1); }

        /* ═══════════════════════════════════════
           Loader Logo — WGG style reveal
        ═══════════════════════════════════════ */
        #loader-logo-stage {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 4;
            pointer-events: none;
        }

        #loader-logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            opacity: 0;
            transform: scale(0.6);
            will-change: transform, opacity;
        }

        /* Outer glowing ring */
        #loader-logo-ring {
            position: relative;
            width: 160px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Spinning dashed orbit ring */
        #loader-logo-ring::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1.5px dashed rgba(129,140,248,0.35);
            animation: orbit-spin 8s linear infinite;
        }

        /* Solid outer ring */
        #loader-logo-ring::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px solid rgba(129,140,248,0.2);
            box-shadow:
                0 0 40px rgba(79,109,232,0.25),
                inset 0 0 30px rgba(79,109,232,0.08);
        }

        @keyframes orbit-spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Inner circle */
        #loader-logo-circle {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: linear-gradient(145deg, rgba(79,109,232,0.18), rgba(139,92,246,0.12));
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(129,140,248,0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            position: relative;
            z-index: 1;
            box-shadow:
                0 0 60px rgba(79,109,232,0.2),
                0 0 120px rgba(79,109,232,0.08);
        }


        #loader-logo-sub {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.55rem;
            font-weight: 500;
            color: rgba(165,180,252,0.7);
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        /* 4 dot ornaments around ring */
        .loader-logo-dot {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(129,140,248,0.6);
            box-shadow: 0 0 8px rgba(129,140,248,0.5);
        }
        .loader-logo-dot:nth-child(1) { top: -3px;    left: 50%; transform: translateX(-50%); }
        .loader-logo-dot:nth-child(2) { bottom: -3px; left: 50%; transform: translateX(-50%); }
        .loader-logo-dot:nth-child(3) { left: -3px;   top: 50%;  transform: translateY(-50%); }
        .loader-logo-dot:nth-child(4) { right: -3px;  top: 50%;  transform: translateY(-50%); }

        /* Text under logo */
        #loader-logo-name {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        #loader-logo-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #dde8ff;
            letter-spacing: -0.01em;
        }
        #loader-logo-tagline {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.65rem;
            color: rgba(129,140,248,0.65);
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        #fluid-overlay {
            position: fixed;
            inset: 0;
            z-index: 99998;
            pointer-events: none;
            overflow: hidden;
        }
        .fluid-circle {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            transform-origin: center center;
            pointer-events: none;
        }
    </style>



    @stack('styles')
</head>
<body class="min-h-screen">

    {{-- Fluid ripple overlay for dark mode transition --}}
    <div id="fluid-overlay"></div>

    {{-- Page Reveal Loader (index only — controlled by JS) --}}
    <div id="page-loader" aria-hidden="true" style="display:none">
        <div id="loader-glow"></div>

        {{-- Main headline text --}}
        <div id="loader-text-block">
            <span class="loader-line loader-l2">
                <span class="loader-line-inner" id="ll-sub1">Strengthened Through</span>
            </span>
            <span class="loader-line loader-l1">
                <span class="loader-line-inner" id="ll-main1">Every</span>
            </span>
            <span class="loader-line loader-l3">
                <span class="loader-line-inner" id="ll-main2">Season.</span>
            </span>
        </div>

        <div id="loader-divider"></div>

        {{-- SeasonStock brand --}}
        <div id="loader-meta">
            <div id="loader-meta-icon">
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 8a2 2 0 002 2h8a2 2 0 002-2l1-8M10 12h4"/>
                </svg>
            </div>
            <span id="loader-meta-text">SeasonStock · Inventory Simulator</span>
        </div>

        {{-- Logo reveal stage (WGG-style) --}}
        <div id="loader-logo-stage">
            <div id="loader-logo-wrap">
                {{-- Ring + circle emblem --}}
                <div id="loader-logo-ring">
                    <span class="loader-logo-dot"></span>
                    <span class="loader-logo-dot"></span>
                    <span class="loader-logo-dot"></span>
                    <span class="loader-logo-dot"></span>
                    <div id="loader-logo-circle">
                        {{-- Inventory box icon --}}
                        <svg width="48" height="48" fill="none" stroke="rgba(165,180,252,0.9)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:4px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 8a2 2 0 002 2h8a2 2 0 002-2l1-8M10 12h4"/>
                        </svg>
                        <div id="loader-logo-sub">Season Stock</div>
                    </div>
                </div>

                {{-- Name below emblem --}}
                <div id="loader-logo-name">
                    <div id="loader-logo-title">SeasonStock</div>
                    <div id="loader-logo-tagline">Inventory Simulator</div>
                </div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div id="loader-progress">
            <div id="loader-progress-fill"></div>
        </div>
    </div>

    {{-- Exit curtain --}}
    <div id="loader-curtain" aria-hidden="true"></div>

    {{-- All site content wrapped --}}

    {{-- WGG-Style Background Assets — arctic ocean scene --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index:0">
    {{-- Asset kiri bawah (darat2 flipped) — large ice floe bottom-left --}}
    <div class="absolute bottom-0 left-0 w-[380px] md:w-[520px] lg:w-[650px]" style="bottom:-2%;left:-3%">
        <img src="https://wgg.petra.ac.id/assets/darat2.png"
             alt=""
             class="w-full h-auto object-contain"
             style="-webkit-transform:scaleX(-1);transform:scaleX(-1);filter:brightness(0.8) drop-shadow(0 20px 60px rgba(0,0,0,0.5));opacity:0.75">
    </div>

    {{-- Asset kanan atas (darat2) — ice island top-right --}}
    <div class="absolute top-0 right-0 w-[320px] md:w-[480px] lg:w-[620px]" style="top:-1%;right:-4%">
        <img src="https://wgg.petra.ac.id/assets/darat2.png"
             alt=""
             class="w-full h-auto object-contain"
             style="filter:brightness(0.75) drop-shadow(0 20px 60px rgba(0,0,0,0.5));opacity:0.65">
    </div>

    {{-- Asset kiri atas (darat3) — small rock top-left --}}
    <div class="absolute top-0 left-0 w-[160px] md:w-[220px] lg:w-[280px]" style="top:8%;left:2%">
        <img src="https://wgg.petra.ac.id/assets/darat3.png"
             alt=""
             class="w-full h-auto object-contain"
             style="filter:brightness(0.8) drop-shadow(0 12px 32px rgba(0,0,0,0.4));opacity:0.6">
    </div>

    {{-- Asset kanan bawah (darat3) — small rock bottom-right --}}
    <div class="absolute bottom-0 right-0 w-[140px] md:w-[200px] lg:w-[260px]" style="bottom:12%;right:3%">
        <img src="https://wgg.petra.ac.id/assets/darat3.png"
             alt=""
             class="w-full h-auto object-contain"
             style="filter:brightness(0.75) drop-shadow(0 12px 32px rgba(0,0,0,0.4));opacity:0.5">
    </div>

    {{-- Water ripple overlay --}}
    <div class="absolute inset-0" style="background:repeating-linear-gradient(180deg,transparent,transparent 60px,rgba(255,255,255,0.015) 60px,rgba(255,255,255,0.015) 61px);pointer-events:none"></div>
</div>

{{-- Overlay untuk gelap-in supaya konten lebih readable --}}
<div class="fixed inset-0 pointer-events-none" style="z-index:0;background:rgba(15,30,55,0.35)"></div>

    <div id="site-content">


    {{-- Navigation --}}
    <nav class="navbar-glass sticky top-0 z-50 content-layer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('items.index') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.15);border:1px solid rgba(200,232,244,0.35);backdrop-filter:blur(8px);box-shadow:0 2px 12px rgba(0,0,0,0.2)">
                        <svg class="w-5 h-5" fill="none" stroke="rgba(200,232,244,0.9)" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 8a2 2 0 002 2h8a2 2 0 002-2l1-8M10 12h4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display font-700 text-xl leading-none" style="color:#e8f4fc">SeasonStock</span>
                        <p class="text-xs leading-none mt-0.5 font-body" style="color:rgba(168,208,232,0.75)">Inventory Simulator</p>
                    </div>
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:flex items-center gap-1.5 text-xs font-body" style="color:rgba(168,208,232,0.7)">
                        <svg class="w-3.5 h-3.5" style="color:rgba(168,208,232,0.7)" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span id="nav-clock"></span>
                    </span>

                    {{-- Dark Mode Toggle --}}
                    <button class="dm-toggle" id="dm-toggle" onclick="toggleDarkMode(event)" title="Toggle Dark Mode" aria-label="Toggle dark mode">
                        <div class="dm-thumb">
                            {{-- Sun icon --}}
                            <svg class="dm-icon-sun" width="10" height="10" fill="none" stroke="#f59e0b" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="4"/>
                                <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                            </svg>
                            {{-- Moon icon --}}
                            <svg class="dm-icon-moon" width="10" height="10" fill="#c8d8f8" stroke="none" viewBox="0 0 24 24">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                            </svg>
                        </div>
                    </button>

                    <a href="{{ route('items.create') }}"
                       class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium font-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none" style="min-width:320px;max-width:380px"></div>

    {{-- Hidden flash data untuk JS --}}
    @if(session('success'))
        <span id="flash-success-data" data-msg="{{ session('success') }}" class="hidden"></span>
    @endif
    @if(session('error'))
        <span id="flash-error-data" data-msg="{{ session('error') }}" class="hidden"></span>
    @endif

    {{-- Main Content --}}
    <main class="content-layer max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="content-layer border-t mt-16 py-6" style="border-color:rgba(200,232,244,0.12)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs font-body" style="color:rgba(168,208,232,0.6)">
                <span class="font-display" style="color:rgba(200,232,244,0.85)">SeasonStock</span> · Strengthened Through Every Season
            </p>
            <p class="text-xs font-body" style="color:rgba(168,208,232,0.5)">Inventory Simulator © {{ date('Y') }}</p>
        </div>
    </footer>

    </div>{{-- /site-content --}}

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(10,20,40,0.6);backdrop-filter:blur(8px)">
        <div class="rounded-2xl p-8 max-w-sm w-full mx-4" style="background:rgba(26,45,71,0.88);backdrop-filter:blur(24px);border:1px solid rgba(200,232,244,0.22);box-shadow:0 24px 64px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.1)">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:rgba(239,68,68,0.18);border:1px solid rgba(239,68,68,0.3)">
                    <svg class="w-7 h-7" style="color:#fca5a5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-semibold mb-2" style="color:#e8f4fc">Hapus Barang?</h3>
                <p class="text-sm font-body mb-2" style="color:rgba(168,208,232,0.75)">Anda akan menghapus:</p>
                <p class="font-semibold font-body mb-1" style="color:#c8e8f4" id="delete-item-name">—</p>
                <p class="text-xs font-body mb-6" style="color:rgba(168,208,232,0.5)">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3 w-full">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium font-body transition-colors" style="background:rgba(255,255,255,0.08);border:1px solid rgba(200,232,244,0.2);color:rgba(200,232,244,0.8)">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-medium font-body transition-colors" style="background:rgba(239,68,68,0.75);border:1px solid rgba(239,68,68,0.4);color:white">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Toast styles */
        .toast {
            pointer-events: all;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
            box-shadow: 0 8px 32px rgba(0,0,0,0.35), 0 2px 8px rgba(0,0,0,0.2);
            backdrop-filter: blur(20px);
            border: 1px solid transparent;
            transform: translateX(110%);
            opacity: 0;
            transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s ease;
            position: relative;
            overflow: hidden;
        }
        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .toast.hide {
            transform: translateX(110%);
            opacity: 0;
            transition: transform 0.35s ease, opacity 0.3s ease;
        }
        .toast-success {
            background: rgba(22,60,40,0.88);
            border-color: rgba(34,197,94,0.35);
            color: #86efac;
        }
        .toast-error {
            background: rgba(60,20,22,0.88);
            border-color: rgba(239,68,68,0.35);
            color: #fca5a5;
        }
        .toast-info {
            background: rgba(26,45,71,0.88);
            border-color: rgba(142,200,232,0.35);
            color: #a8d0e8;
        }
        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .toast-success .toast-icon { background: rgba(34,197,94,0.2); }
        .toast-error   .toast-icon { background: rgba(239,68,68,0.2); }
        .toast-info    .toast-icon { background: rgba(142,200,232,0.15); }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 0 16px;
            animation: toast-shrink linear forwards;
        }
        .toast-success .toast-progress { background: #22c55e; }
        .toast-error   .toast-progress { background: #ef4444; }
        .toast-info    .toast-progress { background: #3b82f6; }
        @keyframes toast-shrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
        .toast-close {
            margin-left: auto;
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.15s, background 0.15s;
        }
        .toast-close:hover { opacity: 1; background: rgba(0,0,0,0.06); }
    </style>

    <script>
        // ═══════════════════════════════════════════
        // Dark Mode with Fluid Ripple Effect
        // ═══════════════════════════════════════════
        const DARK_KEY = 'seasonstock_dark';

        function toggleDarkMode(event) {
            const isDark     = document.body.classList.contains('dark');
            const willBeDark = !isDark;

            // Get button position for ripple origin
            const btn  = document.getElementById('dm-toggle');
            const rect = btn.getBoundingClientRect();
            const cx   = rect.left + rect.width  / 2;
            const cy   = rect.top  + rect.height / 2;

            // Calculate max radius to cover entire screen
            const maxDist = Math.max(
                Math.hypot(cx, cy),
                Math.hypot(window.innerWidth - cx, cy),
                Math.hypot(cx, window.innerHeight - cy),
                Math.hypot(window.innerWidth - cx, window.innerHeight - cy)
            );
            const radius = maxDist * 2.2;

            // Ripple color: going dark → deep navy; going light → soft blue
            const rippleColor = willBeDark
                ? '#0d1224'
                : '#e8eeff';

            const overlay = document.getElementById('fluid-overlay');

            // Create ripple circle
            const circle = document.createElement('div');
            circle.className = 'fluid-circle';
            circle.style.cssText = `
                width:  ${radius * 2}px;
                height: ${radius * 2}px;
                left:   ${cx - radius}px;
                top:    ${cy - radius}px;
                background: ${rippleColor};
                transform: scale(0);
                transform-origin: center center;
            `;
            overlay.appendChild(circle);

            // Phase 1 — Expand ripple
            const duration = 650;
            let start = null;

            function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

            function animateExpand(ts) {
                if (!start) start = ts;
                const progress = Math.min((ts - start) / duration, 1);
                const scale    = easeOutQuart(progress);
                circle.style.transform = `scale(${scale})`;

                // Apply theme at 40% through animation
                if (progress >= 0.4 && !circle._themeApplied) {
                    circle._themeApplied = true;
                    if (willBeDark) {
                        document.body.classList.add('dark');
                    } else {
                        document.body.classList.remove('dark');
                    }
                    localStorage.setItem(DARK_KEY, willBeDark ? '1' : '0');
                }

                if (progress < 1) {
                    requestAnimationFrame(animateExpand);
                } else {
                    // Phase 2 — Fade out ripple
                    circle.style.transition = 'opacity 0.35s ease';
                    circle.style.opacity = '0';
                    setTimeout(() => circle.remove(), 380);
                }
            }

            requestAnimationFrame(animateExpand);
        }

        // Apply saved preference on load (before paint)
        (function() {
            const saved = localStorage.getItem(DARK_KEY);
            if (saved === '1') document.body.classList.add('dark');
        })();

        // ═══════════════════════════════════════════
        // Toast Notification System
        // ═══════════════════════════════════════════
        function showToast(message, type = 'success', duration = 4500) {
            const container = document.getElementById('toast-container');
            const icons = {
                success: `<svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`,
                error:   `<svg width="18" height="18" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`,
                info:    `<svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            };
            const titles = { success: 'Berhasil!', error: 'Terjadi Kesalahan', info: 'Informasi' };

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">${icons[type]}</div>
                <div style="flex:1;min-width:0">
                    <p style="font-weight:600;font-size:13px;margin-bottom:2px">${titles[type]}</p>
                    <p style="opacity:0.8;font-size:12.5px;line-height:1.4">${message}</p>
                </div>
                <div class="toast-close" onclick="dismissToast(this.parentElement)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="toast-progress" style="animation-duration:${duration}ms"></div>
            `;
            container.appendChild(toast);
            requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
            const timer = setTimeout(() => dismissToast(toast), duration);
            toast._timer = timer;
            return toast;
        }

        function dismissToast(toast) {
            if (!toast || toast._dismissed) return;
            toast._dismissed = true;
            clearTimeout(toast._timer);
            toast.classList.add('hide');
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const s = document.getElementById('flash-success-data');
            const e = document.getElementById('flash-error-data');
            if (s) showToast(s.dataset.msg, 'success');
            if (e) showToast(e.dataset.msg, 'error');
        });

        // ═══════════════════════════════════════════
        // Clock
        // ═══════════════════════════════════════════
        function updateClock() {
            const el = document.getElementById('nav-clock');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ═══════════════════════════════════════════
        // Delete Modal
        // ═══════════════════════════════════════════
        function openDeleteModal(itemId, itemName) {
            document.getElementById('delete-item-name').textContent = itemName;
            document.getElementById('delete-form').action = '/items/' + itemId;
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>

    <script>
        // ═══════════════════════════════════════════════════════
        // PAGE REVEAL — WGG-inspired, index only
        // Text lines slam in → divider draws → curtain wipes down
        // ═══════════════════════════════════════════════════════
        (function () {
            CustomEase.create('slap',      'M0,0 C0.05,0.9 0.1,1 1,1');
            CustomEase.create('softEntry', 'M0,0 C0.25,0.46 0.45,0.94 1,1');
            CustomEase.create('curtain',   'M0,0 C0.77,0 0.18,1 1,1');

            // Only run on index page (route: /items or /)
            const isIndex = window.location.pathname === '/'
                         || window.location.pathname === '/items'
                         || window.location.pathname.match(/^\/items(\?.*)?$/);

            // Skip loader if coming from store/update (set by create/edit form submit)
            const skipLoader = sessionStorage.getItem('ss_skip_loader');
            if (skipLoader) sessionStorage.removeItem('ss_skip_loader');

            const loader   = document.getElementById('page-loader');
            const curtain  = document.getElementById('loader-curtain');
            const content  = document.getElementById('site-content');
            const navbar   = document.querySelector('nav');
            const main     = document.querySelector('main');
            const footer   = document.querySelector('footer');

            if (!isIndex || !loader || skipLoader) {
                // Not index, or returning from form submit — skip loader
                if (content) gsap.set(content, { opacity: 1 });
                return;
            }

            // Show loader
            loader.style.display = 'flex';
            gsap.set(content, { opacity: 0 });
            gsap.set(navbar,  { opacity: 0, y: -16 });
            gsap.set(main,    { opacity: 0, y: 24 });
            gsap.set(footer,  { opacity: 0 });
            // Cards start hidden — revealed in stagger step
            gsap.set('.stat-card',   { opacity: 0, y: 28, scale: 0.94 });
            gsap.set('.card-season', { opacity: 0, y: 22, scale: 0.96 });

            const tl = gsap.timeline();

            // ── 1. Lines fly up from bottom (slam effect)
            tl.to('#ll-sub1', {
                y: 0, duration: 0.7, ease: 'slap', delay: 0.15
            })
            .to('#ll-main1', {
                y: 0, duration: 0.75, ease: 'slap'
            }, '-=0.45')
            .to('#ll-main2', {
                y: 0, duration: 0.8, ease: 'slap'
            }, '-=0.5')

            // ── 2. Divider line draws across
            .to('#loader-divider', {
                width: '280px', duration: 0.6, ease: 'power2.inOut'
            }, '-=0.3')

            // ── 3. Brand meta fades in
            .to('#loader-meta', {
                opacity: 1, y: 0, duration: 0.45, ease: 'softEntry'
            }, '-=0.3')

            // ── 4. Progress bar fills
            .to('#loader-progress-fill', {
                width: '100%', duration: 0.75, ease: 'power1.inOut'
            }, '-=0.2')

            // ── 5. Lines scatter — each line exits differently
            .to('#ll-sub1', {
                y: '-120%', opacity: 0, duration: 0.4, ease: 'power2.in'
            }, '+=0.25')
            .to('#ll-main1', {
                y: '120%', opacity: 0, duration: 0.45, ease: 'power2.in'
            }, '-=0.35')
            .to('#ll-main2', {
                x: '8%', opacity: 0, duration: 0.5, ease: 'power2.in'
            }, '-=0.4')
            .to(['#loader-divider', '#loader-meta'], {
                opacity: 0, duration: 0.3, ease: 'power2.in'
            }, '-=0.45')

            // ══════════════════════════════════════════
            // ── 6. LOGO REVEAL — WGG style
            // Logo pops in from scale(0.6) → scale(1.05) → scale(1)
            // ══════════════════════════════════════════
            .to('#loader-logo-wrap', {
                opacity: 1,
                scale: 1.05,
                duration: 0.55,
                ease: 'back.out(1.8)',
            }, '-=0.1')

            // Settle to 1 with micro bounce
            .to('#loader-logo-wrap', {
                scale: 1,
                duration: 0.25,
                ease: 'power2.out',
            })

            // Glow pulse on the ring
            .to('#loader-logo-circle', {
                boxShadow: '0 0 80px rgba(79,109,232,0.45), 0 0 160px rgba(79,109,232,0.18)',
                duration: 0.5,
                ease: 'power2.out',
            }, '-=0.3')

            // Hold a moment so user can see the logo
            .to({}, { duration: 0.65 })

            // ── 7. Logo shrinks and fades OUT (like WGG logo zooming away)
            .to('#loader-logo-wrap', {
                scale: 0.1,
                opacity: 0,
                duration: 0.55,
                ease: 'power3.in',
            })

            // ── 8. Curtain wipes DOWN covering loader
            .to(curtain, {
                scaleY: 1,
                transformOrigin: 'top',
                duration: 0.7,
                ease: 'curtain',
                onStart: () => { curtain.style.background = '#0d1633'; },
                onComplete: () => {
                    loader.style.display = 'none';
                }
            }, '-=0.15')

            // ── 9. Site content appears behind curtain
            .to(content, {
                opacity: 1, duration: 0.01
            }, '-=0.1')

            // ── 10. Curtain wipes UP revealing the page
            .to(curtain, {
                scaleY: 0,
                transformOrigin: 'bottom',
                duration: 0.75,
                ease: 'curtain',
            }, '-=0.05')

            // ── 11. Navbar, main, footer cascade in
            .to(navbar, {
                opacity: 1, y: 0, duration: 0.55, ease: 'softEntry'
            }, '-=0.4')
            .to(main, {
                opacity: 1, y: 0, duration: 0.65, ease: 'softEntry'
            }, '-=0.4')
            .to(footer, {
                opacity: 1, duration: 0.4, ease: 'softEntry'
            }, '-=0.35')

            // ── 12. Cards stagger in — kiri atas ke kanan bawah
            .fromTo('.stat-card', {
                opacity: 0,
                y: 28,
                scale: 0.94,
            }, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.5,
                ease: 'back.out(1.4)',
                stagger: { amount: 0.45, from: 'start' },
            }, '-=0.25')
            .fromTo('.card-season', {
                opacity: 0,
                y: 22,
                scale: 0.96,
            }, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.55,
                ease: 'back.out(1.3)',
                stagger: { amount: 0.5, from: 'start' },
            }, '-=0.3');

        })();
    </script>

    @stack('scripts')
</body>
</html>