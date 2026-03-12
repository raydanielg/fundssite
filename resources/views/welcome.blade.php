<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>Help Save Cliff's Life</title>
    <meta name="description" content="A husband, father of 2, fighting for his life. Track contributions and join hundreds supporting Cliff's kidney treatment.">
    <meta name="keywords" content="Medical Fundraiser, Stand With Cliff, Donation, Help Cliff, Medical Support Tanzania">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Help Save Cliff's Life">
    <meta property="og:description" content="A husband, father of 2, fighting for his life. Track contributions and join hundreds supporting Cliff's kidney treatment.">
    <meta property="og:image" content="{{ asset('WhatsApp Image 2026-03-11 at 10.34.15.jpeg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Help Save Cliff's Life">
    <meta property="twitter:description" content="A husband, father of 2, fighting for his life. Track contributions and join hundreds supporting Cliff's kidney treatment.">
    <meta property="twitter:image" content="{{ asset('WhatsApp Image 2026-03-11 at 10.34.15.jpeg') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,200..700,0..1,-50..200" />
    <style>
        :root {
            --bg: #f0f7f4;
            --surface: #ffffff;
            --border: #d4eae2;
            --deep: #0d3d2e;
            --forest: #1a5c45;
            --emerald: #2e9e72;
            --mint: #6fcfad;
            --gold: #f4a225;
            --amber: #e8852a;
            --sky: #3b9fd4;
            --coral: #e8604c;
            --text: #0d2e22;
            --muted: #6b8f7e;
            --light: #a8c9bc;
            --serif: 'Playfair Display', Georgia, serif;
            --sans: 'DM Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            font-weight: 400;
            line-height: 1.55;
            letter-spacing: -0.005em;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                radial-gradient(circle at 15% 15%, rgba(46,158,114,0.07) 0%, transparent 50%),
                radial-gradient(circle at 85% 80%, rgba(59,159,212,0.05) 0%, transparent 50%);
        }
        a { color: inherit; text-decoration: none; }

        .mi { display: inline-flex; align-items: center; justify-content: center; vertical-align: -0.18em; }
        .mi > .material-symbols-outlined { font-size: 1.1em; line-height: 1; }
        .hero-tag .mi { margin-right: 6px; }
        .bb-label .mi { margin-right: 8px; }
        .stat-icon .material-symbols-outlined { font-size: 1.35rem; }

        .hero {
            background: linear-gradient(135deg, var(--deep) 0%, #1d6b50 60%, #1a5c45 100%);
            position: relative; overflow: hidden;
        }
        .hero::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 56px;
            background: var(--bg); clip-path: ellipse(55% 100% at 50% 100%);
        }
        .hero-deco {
            position: absolute; top: -100px; right: -100px;
            width: 450px; height: 450px; border-radius: 50%;
            background: radial-gradient(circle, rgba(111,207,173,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-deco2 {
            position: absolute; bottom: -50px; left: -60px;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(244,162,37,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-inner {
            display: grid; grid-template-columns: 1fr auto; gap: 36px; align-items: center;
            padding: 52px 40px 82px; max-width: 1100px; margin: 0 auto; position: relative; z-index: 1;
        }
        @media (max-width: 700px) {
            .hero-inner { grid-template-columns: 1fr; padding: 36px 24px 76px; text-align: center; }
            .photo-ring { margin: 0 auto; }
            .hero-tags { justify-content: center; }
            .top-links { justify-content: center; }
            .hero-cta { justify-content: center; }
        }

        .topbar { display: flex; justify-content: flex-end; margin-bottom: 18px; }
        .top-links { display: flex; gap: 10px; flex-wrap: wrap; }
        .tlink {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.09em;
            padding: 8px 12px; border-radius: 12px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9);
            transition: transform 0.15s, background 0.15s;
        }
        .tlink:hover { transform: translateY(-1px); background: rgba(255,255,255,0.14); }
        .tlink.primary { background: rgba(111,207,173,0.16); border-color: rgba(111,207,173,0.35); }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(111,207,173,0.15); border: 1px solid rgba(111,207,173,0.3);
            color: var(--mint); padding: 5px 14px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 18px;
        }
        .pulse-dot { width: 7px; height: 7px; background: var(--mint); border-radius: 50%; animation: pulse 1.8s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1) } 50% { opacity: 0.5; transform: scale(1.4) } }
        .hero h1 { font-family: var(--serif); font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 14px; }
        .hero h1 span { color: var(--mint); }
        .hero-bio { color: rgba(255,255,255,0.72); font-size: 0.93rem; line-height: 1.8; max-width: 500px; margin-bottom: 24px; }
        .hero-bio strong { color: rgba(111,207,173,0.9); }
        .hero-bio em { color: rgba(255,255,255,0.45); font-size: 0.85em; }
        .hero-tags { display: flex; gap: 8px; flex-wrap: wrap; }
        .hero-tag { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.8); padding: 5px 13px; border-radius: 12px; font-size: 0.72rem; font-weight: 500; }

        .hero-cta { display: flex; align-items: center; gap: 14px; margin-top: 18px; flex-wrap: wrap; }
        .play-btn {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.22);
            background: rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.92);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease;
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
        }
        .play-btn:hover { transform: translateY(-1px) scale(1.02); background: rgba(255,255,255,0.14); border-color: rgba(111,207,173,0.55); }
        .play-btn:active { transform: translateY(0) scale(0.98); }
        .play-btn .material-symbols-outlined { font-size: 1.4rem; }
        .cta-line { height: 1px; width: 54px; background: rgba(255,255,255,0.28); }
        .donate-btn {
            border: none;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 14px;
            font-weight: 900;
            letter-spacing: 0.02em;
            color: #072b1a;
            background: linear-gradient(90deg, rgba(111,207,173,1) 0%, rgba(244,162,37,1) 100%);
            box-shadow: 0 16px 36px rgba(0,0,0,0.25);
            transition: transform 0.18s ease, filter 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            font-size: 0.78rem;
        }
        .donate-btn:hover { transform: translateY(-1px); filter: brightness(1.02); }
        .donate-btn:active { transform: translateY(0); }
        .donate-btn:disabled { opacity: 0.65; cursor: not-allowed; }
        .donate-btn .material-symbols-outlined { font-size: 1.1rem; }
        @media (max-width: 700px) {
            .hero-cta { justify-content: center; }
            .cta-line { display: none; }
        }

        .modal-backdrop { position: fixed; inset: 0; background: rgba(10, 28, 22, 0.62); display: none; align-items: center; justify-content: center; padding: 18px; z-index: 50; }
        .modal-backdrop.open { display: flex; }
        .modal {
            width: min(560px, 100%);
            border-radius: 18px;
            background: var(--surface);
            border: 1px solid rgba(212,234,226,0.9);
            box-shadow: 0 30px 80px rgba(0,0,0,0.35);
            overflow: hidden;
            transform: translateY(10px);
            opacity: 0;
            transition: transform 0.18s ease, opacity 0.18s ease;
        }
        .modal-backdrop.open .modal { transform: translateY(0); opacity: 1; }
        .modal-head {
            padding: 18px 18px 14px;
            background: linear-gradient(135deg, var(--deep) 0%, #124b38 65%, var(--forest) 100%);
            color: rgba(255,255,255,0.92);
            position: relative;
        }
        .modal-title { display: flex; align-items: center; gap: 10px; font-family: var(--serif); font-weight: 900; font-size: 1.15rem; letter-spacing: -0.01em; }
        .modal-title .material-symbols-outlined { font-size: 1.3rem; color: var(--mint); }
        .modal-sub { margin-top: 6px; color: rgba(255,255,255,0.66); font-size: 0.82rem; line-height: 1.5; }
        .modal-x {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.90);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.18s ease, transform 0.18s ease;
        }
        .modal-x:hover { background: rgba(255,255,255,0.14); transform: translateY(-1px); }
        .modal-body { padding: 18px; }
        .mgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 600px) { .mgrid { grid-template-columns: 1fr; } }
        .mgrp { display: flex; flex-direction: column; gap: 6px; }
        .mgrp label { font-size: 0.72rem; color: var(--muted); font-weight: 700; letter-spacing: 0.02em; }
        .minput {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px;
            color: var(--text);
            font-family: var(--sans);
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }
        .minput:focus { border-color: rgba(46,158,114,0.7); box-shadow: 0 0 0 4px rgba(46,158,114,0.10); }
        .modal-actions { display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 14px; }
        .btn.modal {
            border-radius: 12px;
            padding: 11px 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 800;
        }
        .btn.modal.secondary { background: rgba(13,61,46,0.08); color: var(--forest); border: 1px solid rgba(13,61,46,0.12); box-shadow: none; }
        .btn.modal.secondary:hover { background: rgba(13,61,46,0.12); transform: translateY(-1px); }
        .modal-note { margin-top: 10px; font-size: 0.72rem; color: var(--muted); line-height: 1.5; }
        .modal-error { margin-top: 10px; font-size: 0.78rem; color: var(--coral); font-weight: 700; display: none; }
        .modal-error.show { display: block; }

        .photo-ring { width: 190px; height: 190px; border-radius: 50%; padding: 4px; background: linear-gradient(135deg, var(--mint), var(--gold)); box-shadow: 0 0 50px rgba(111,207,173,0.25); flex-shrink: 0; position: relative; z-index: 0; }
        .photo-ring::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(111,207,173,0.0), rgba(111,207,173,0.9), rgba(244,162,37,0.9), rgba(111,207,173,0.0));
            filter: blur(1px);
            opacity: 0.85;
            animation: ringSpin 3.8s linear infinite;
            z-index: -1;
            pointer-events: none;
        }
        .photo-ring::after {
            content: '';
            position: absolute;
            inset: -14px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(111,207,173,0.18) 0%, rgba(244,162,37,0.10) 35%, transparent 70%);
            opacity: 0.55;
            pointer-events: none;
            z-index: -2;
        }
        @keyframes ringSpin { to { transform: rotate(360deg); } }
        .photo-inner { width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #1a5c45, #0d3d2e); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: outline 0.2s; position: relative; z-index: 1; }
        .photo-inner:hover { outline: 2px dashed rgba(111,207,173,0.5); outline-offset: 2px; }
        .photo-inner img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .photo-placeholder { display: flex; flex-direction: column; align-items: center; gap: 6px; color: rgba(255,255,255,0.45); font-size: 0.68rem; text-align: center; padding: 16px; }
        .photo-placeholder .icon { font-size: 2rem; }
        #photo-input { display: none; }

        .balance-banner { background: linear-gradient(90deg, #f4a225 0%, #e8852a 100%); display: flex; align-items: center; flex-wrap: wrap; overflow: hidden; }
        .bb-item { flex: 1; min-width: 160px; padding: 16px 28px; border-right: 1px solid rgba(0,0,0,0.1); }
        .bb-item:last-child { border-right: none; }
        .bb-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(0,0,0,0.5); margin-bottom: 4px; }
        .bb-value { font-family: var(--mono); font-size: 1.4rem; font-weight: 600; color: #1a1a1a; line-height: 1; }
        .bb-sub { font-size: 0.7rem; color: rgba(0,0,0,0.5); margin-top: 3px; }

        .main-wrap { padding: 36px 0 0; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 1; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 18px 18px 16px; position: relative; box-shadow: 0 1px 10px rgba(13,62,46,0.06); transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease; animation: fadeUp 0.5s ease both; }
        .stat-card:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(13,62,46,0.10); border-color: rgba(46,158,114,0.35); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: rgba(46,158,114,0.9); border-radius: 16px 16px 0 0; }
        .stat-icon { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: rgba(46,158,114,0.10); color: var(--forest); margin-bottom: 10px; }
        .stat-icon .material-symbols-outlined { font-size: 1.2rem; }
        .stat-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.09em; color: var(--muted); margin-bottom: 8px; }
        .stat-value {
            font-family: var(--sans);
            font-variant-numeric: tabular-nums;
            font-feature-settings: 'tnum' 1, 'liga' 0;
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--text);
            line-height: 1.05;
        }
        .stat-sub { font-size: 0.72rem; color: var(--muted); margin-top: 6px; }

        .progress-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(13,62,46,0.08); animation: fadeUp 0.6s 0.1s ease both; position: relative; overflow: hidden; }
        .progress-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--emerald); }
        .prog-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; gap: 20px; }
        .prog-title { font-family: var(--serif); font-size: 1.4rem; font-weight: 900; color: var(--deep); line-height: 1.2; }
        .prog-subtitle { font-size: 0.85rem; color: var(--muted); margin-top: 6px; font-weight: 500; }
        .prog-pct { font-family: var(--sans); font-variant-numeric: tabular-nums; font-feature-settings: 'tnum' 1, 'liga' 0; font-size: 2.8rem; font-weight: 900; color: var(--emerald); line-height: 1; letter-spacing: -0.03em; display: flex; flex-direction: column; align-items: flex-end; }
        .prog-pct span { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--light); margin-top: 4px; font-weight: 700; }
        .prog-track { height: 24px; background: #eef5f2; border-radius: 12px; overflow: hidden; border: 1px solid rgba(46,158,114,0.15); box-shadow: inset 0 2px 4px rgba(0,0,0,0.03); position: relative; }
        .prog-fill { height: 100%; border-radius: 12px; background: linear-gradient(90deg, var(--forest), var(--emerald), var(--mint)); transition: width 1.4s cubic-bezier(0.22,1,0.36,1); position: relative; }
        .prog-fill::after { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transform: translateX(-100%); animation: shimmer 2.5s infinite; }
        @keyframes shimmer { 100% { transform: translateX(100%); } }
        .prog-labels { display: flex; justify-content: space-between; margin-top: 14px; font-size: 0.75rem; font-family: var(--mono); color: var(--muted); font-weight: 600; }
        .prog-labels .current { color: var(--emerald); font-weight: 700; font-size: 0.85rem; padding: 2px 10px; background: rgba(46,158,114,0.08); border-radius: 6px; }
        .milestones { display: flex; gap: 8px; margin-top: 24px; flex-wrap: wrap; }
        .ms { font-size: 0.7rem; padding: 6px 12px; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .ms i { font-size: 0.9rem; }
        .ms.done { background: var(--emerald); color: white; box-shadow: 0 4px 12px rgba(46,158,114,0.2); }
        .ms.todo { background: white; color: var(--light); border: 1px solid var(--border); }
        @media (max-width: 600px) {
            .progress-card { padding: 20px; }
            .prog-top { flex-direction: column; align-items: flex-start; }
            .prog-pct { align-items: flex-start; margin-top: 10px; font-size: 2.2rem; }
            .prog-title { font-size: 1.2rem; }
        }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 24px; }
        @media (max-width: 700px) { .two-col { grid-template-columns: 1fr; } }
        .section-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 14px rgba(13,62,46,0.07); animation: fadeUp 0.5s 0.15s ease both; }
        .sec-header { padding: 13px 20px; border-bottom: 1.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: rgba(240,247,244,0.6); }
        .sec-title { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.09em; color: var(--muted); }
        .sec-badge { font-size: 0.67rem; font-family: var(--mono); background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 2px 8px; color: var(--emerald); }

        .pie-wrap { padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .pie {
            width: 132px;
            height: 132px;
            border-radius: 50%;
            background: conic-gradient(var(--emerald) 0deg, var(--emerald) 0deg, rgba(13,61,46,0.10) 0deg, rgba(13,61,46,0.10) 360deg);
            border: 1px solid rgba(212,234,226,0.9);
            box-shadow: 0 10px 24px rgba(13,62,46,0.10);
            position: relative;
            flex-shrink: 0;
        }
        .pie::after {
            content: '';
            position: absolute;
            inset: 16px;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid rgba(212,234,226,0.9);
        }
        .pie-mid {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            text-align: center;
            z-index: 1;
            padding: 16px;
        }
        .pie-mid .pct { font-family: var(--sans); font-variant-numeric: tabular-nums; font-feature-settings: 'tnum' 1, 'liga' 0; font-weight: 900; font-size: 1.2rem; color: var(--forest); line-height: 1; }
        .pie-mid .lbl { font-size: 0.68rem; color: var(--muted); margin-top: 4px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; }
        .pie-meta { display: flex; flex-direction: column; gap: 10px; }
        .kpi { display: flex; align-items: center; justify-content: space-between; gap: 12px; font-size: 0.78rem; }
        .kpi .k { color: var(--muted); font-weight: 700; }
        .kpi .v { font-family: var(--mono); font-weight: 700; color: var(--text); }
        .dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .dot.ok { background: var(--emerald); }
        .dot.pend { background: rgba(13,61,46,0.22); }

        .clist { max-height: 300px; overflow-y: auto; }
        .citem { display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; border-bottom: 1px solid rgba(212,234,226,0.5); transition: background 0.15s; }
        .citem:hover { background: rgba(46,158,114,0.04); }
        .citem:last-child { border-bottom: none; }
        .cavatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--emerald), var(--mint)); display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; color: white; flex-shrink: 0; margin-right: 10px; }
        .cavatar.pav { background: linear-gradient(135deg, #d0d0d0, #b8b8b8); }
        .cname { font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .cgrp { font-size: 0.63rem; color: var(--light); margin-top: 1px; }
        .cright { display: flex; align-items: center; gap: 8px; }
        .camt { font-family: var(--mono); font-size: 0.78rem; font-weight: 600; color: var(--forest); }
        .bdg { font-size: 0.57rem; padding: 2px 7px; border-radius: 5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
        .bdg.paid { background: rgba(46,158,114,0.12); color: var(--forest); border: 1px solid rgba(46,158,114,0.25); }
        .bdg.pending { background: rgba(244,162,37,0.12); color: var(--amber); border: 1px solid rgba(244,162,37,0.3); }

        .tab-nav { display: flex; gap: 2px; background: var(--surface); border: 1.5px solid var(--border); border-radius: 12px; padding: 4px; margin-bottom: 20px; width: fit-content; }
        .tab-btn { padding: 8px 18px; border-radius: 9px; border: none; background: transparent; color: var(--muted); font-family: var(--sans); font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
        .tab-btn.active { background: var(--deep); color: white; }
        .tab-btn:not(.active):hover { background: var(--bg); color: var(--text); }

        .bar-chart { padding: 16px 20px; }
        .brow { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 0.72rem; }
        .blabel { width: 100px; text-align: right; color: var(--muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex-shrink: 0; }
        .btrack { flex: 1; height: 16px; background: var(--bg); border-radius: 4px; overflow: hidden; border: 1px solid var(--border); }
        .bfill { height: 100%; background: linear-gradient(90deg, var(--emerald), var(--mint)); border-radius: 4px; display: flex; align-items: center; justify-content: flex-end; padding-right: 6px; font-size: 0.6rem; font-family: var(--mono); color: white; font-weight: 600; transition: width 1s ease; }

        .tbl { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
        .tbl th { text-align: left; padding: 9px 16px; font-size: 0.63rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); border-bottom: 1.5px solid var(--border); background: rgba(240,247,244,0.5); }
        .tbl td { padding: 9px 16px; border-bottom: 1px solid rgba(212,234,226,0.4); }
        .tbl tr:last-child td { border-bottom: none; }
        .tbl tr:hover td { background: rgba(46,158,114,0.03); }
        .tbl-wrap { max-height: 420px; overflow-y: auto; }

        .modal-success { margin-top: 14px; padding: 18px; background: rgba(46,158,114,0.08); border: 1px solid rgba(46,158,114,0.2); border-radius: 14px; text-align: center; display: none; }
        .modal-success.show { display: block; animation: fadeUp 0.4s ease both; }
        .modal-success .icon { font-size: 2.5rem; color: var(--emerald); margin-bottom: 10px; }
        .modal-success h4 { font-family: var(--serif); color: var(--deep); margin-bottom: 6px; }
        .modal-success p { font-size: 0.85rem; color: var(--muted); margin-bottom: 0; }
        .hidden { display: none; }
        .spin { animation: fa-spin 2s infinite linear; display: inline-block; }
        @keyframes fa-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .pay-grid { display: flex; flex-direction: column; gap: 14px; }
        .pay-card { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 14px; border-radius: 14px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.10); }
        .pay-left { display: flex; align-items: center; gap: 12px; }
        .pay-ic { width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.10); font-size: 1.25rem; }
        .pay-ic .material-symbols-outlined { font-size: 1.35rem; }
        .pay-title { font-size: 0.72rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 4px; }
        .pay-name { font-family: var(--serif); font-weight: 900; font-size: 1.05rem; color: rgba(255,255,255,0.94); margin-bottom: 3px; }
        .pay-num { font-family: var(--mono); font-weight: 800; font-size: 1.05rem; letter-spacing: 0.06em; color: rgba(255,255,255,0.88); }
        .pay-actions { display: flex; flex-direction: column; gap: 8px; align-items: flex-end; }
        .pay-btn { border: 1px solid rgba(255,255,255,0.18); background: rgba(0,0,0,0.18); color: rgba(255,255,255,0.90); padding: 8px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: transform 0.15s, background 0.15s, border-color 0.15s; }
        .pay-btn:hover { transform: translateY(-1px); background: rgba(0,0,0,0.28); border-color: rgba(111,207,173,0.45); }
        .pay-note { font-size: 0.75rem; color: rgba(255,255,255,0.60); line-height: 1.55; margin-top: 10px; }
        .hero-paywrap { margin-top: 24px; width: 100%; overflow-x: auto; padding-bottom: 8px; }
        .hero-payhead { font-size: 0.7rem; letter-spacing: 0.22em; text-transform: uppercase; color: rgba(255,255,255,0.60); margin-bottom: 12px; }
        .hero-paywrap .pay-grid { 
            display: flex;
            flex-direction: row;
            gap: 12px;
            width: max-content;
            min-width: 100%;
        }
        .hero-paywrap .pay-card { 
            background: rgba(255,255,255,0.08); 
            border: 1px solid rgba(255,255,255,0.12); 
            padding: 14px;
            flex: 0 0 280px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            border-left: 3px solid var(--mint) !important;
        }
        .hero-paywrap .pay-card:hover {
            background: rgba(255,255,255,0.14);
            transform: translateY(-2px);
        }
        .hero-paywrap .pay-num { font-size: 1.05rem; }
        .hero-paywrap .pay-name { font-size: 0.95rem; }
        .hero-paywrap .pay-title { font-size: 0.7rem; color: var(--mint) !important; }
        .hero-paywrap .pay-ic { width: 40px; height: 40px; font-size: 1.2rem; color: var(--mint) !important; }
        
        @media (max-width: 700px) {
            .hero-paywrap .pay-grid { 
                flex-direction: column;
                width: 100%;
            }
            .hero-paywrap .pay-card { 
                flex: 1 1 auto;
                width: 100%;
            }
        }
        footer { border-top: 1px solid rgba(212,234,226,0.9); background: linear-gradient(135deg, var(--deep) 0%, #124b38 60%, var(--forest) 100%); }
        .site-footer {
            max-width: 1100px;
            margin: 0 auto;
            padding: 26px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: rgba(255,255,255,0.78);
            font-size: 0.75rem;
            position: relative;
            z-index: 1;
        }
        .site-footer::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 15% 50%, rgba(111,207,173,0.16) 0%, transparent 55%),
                radial-gradient(circle at 85% 30%, rgba(244,162,37,0.10) 0%, transparent 50%);
            opacity: 0.55;
            z-index: -1;
        }
        .foot-left { display: flex; align-items: center; gap: 10px; }
        .foot-mark {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.16);
            color: rgba(255,255,255,0.88);
        }
        .foot-mark .material-symbols-outlined { font-size: 1.25rem; }
        .foot-title { font-weight: 800; color: rgba(255,255,255,0.92); letter-spacing: -0.01em; }
        .foot-title strong { color: var(--mint); font-weight: 900; }
        .foot-sub { color: rgba(255,255,255,0.62); margin-top: 2px; font-size: 0.72rem; }
        .foot-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
        .foot-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.76);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .foot-pill .material-symbols-outlined { font-size: 1.05rem; }
        @media (max-width: 700px) {
            .site-footer { flex-direction: column; align-items: flex-start; }
            .foot-right { justify-content: flex-start; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-deco"></div>
        <div class="hero-deco2"></div>
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow"><span class="pulse-dot"></span> Live Medical Fundraiser</div>
                <h1>Stand With <span>Cliff</span></h1>
                <p class="hero-bio">
                    Cliff is a devoted <strong>husband</strong>, a proud <strong>father of two beautiful children</strong>, and a man absolutely full of life.
                    He has been diagnosed with <strong>Chronic Kidney Disease (CKD)</strong> and urgently needs lifetime dialysis or a kidney transplant.
                    <br><br>
                    His family, his friends, his community — all of us — have come together because some things are worth fighting for.
                    <br><br>
                    <em>Together, we are stronger than any diagnosis.</em>
                </p>
                <div class="hero-tags">
                    <span class="hero-tag"><span class="mi"><span class="material-symbols-outlined">favorite</span></span>Husband</span>
                    <span class="hero-tag"><span class="mi"><span class="material-symbols-outlined">family_restroom</span></span>Father of 2</span>
                    <span class="hero-tag"><span class="mi"><span class="material-symbols-outlined">location_city</span></span>Dar es Salaam</span>
                    <span class="hero-tag"><span class="mi"><span class="material-symbols-outlined">fitness_center</span></span>Fighter</span>
                    <span class="hero-tag"><span class="mi"><span class="material-symbols-outlined">bolt</span></span>Full of Life</span>
                </div>

                <div class="hero-cta">
                    <button class="play-btn" type="button" onclick="openDonate()" aria-label="Donate">
                        <span class="material-symbols-outlined">play_arrow</span>
                    </button>
                    <span class="cta-line" aria-hidden="true"></span>
                    <button class="donate-btn" type="button" onclick="openDonate()">
                        <span class="material-symbols-outlined">volunteer_activism</span>
                        Click here to donate
                    </button>
                </div>

                <div class="hero-paywrap">
                    <div class="hero-payhead">Other ways to pay</div>
                    <div class="pay-grid">
                        <div class="pay-card" onclick="copyPay('{{ str_replace(' ', '', $settings['selcom_number'] ?? '') }}')">
                            <div class="pay-left">
                                <div class="pay-ic"><span class="material-symbols-outlined">phone_iphone</span></div>
                                <div>
                                    <div class="pay-title">Selcom Microfinance</div>
                                    <div class="pay-name">{{ $settings['selcom_name'] ?? '' }}</div>
                                    <div class="pay-num">{{ $settings['selcom_number'] ?? '' }}</div>
                                </div>
                            </div>
                            <div class="pay-actions">
                                @if(!empty($settings['selcom_number']))
                                    <button class="pay-btn" type="button">Copy</button>
                                    <button class="pay-btn" type="button" onclick="event.stopPropagation(); callPay('{{ str_replace(' ', '', $settings['selcom_number']) }}')">Call</button>
                                @endif
                            </div>
                        </div>

                        <div class="pay-card" onclick="copyPay('{{ str_replace(' ', '', $settings['tigo_number'] ?? '') }}')">
                            <div class="pay-left">
                                <div class="pay-ic"><span class="material-symbols-outlined">smartphone</span></div>
                                <div>
                                    <div class="pay-title">Tigo Pesa</div>
                                    <div class="pay-name">{{ $settings['tigo_name'] ?? '' }}</div>
                                    <div class="pay-num">{{ $settings['tigo_number'] ?? '' }}</div>
                                </div>
                            </div>
                            <div class="pay-actions">
                                @if(!empty($settings['tigo_number']))
                                    <button class="pay-btn" type="button">Copy</button>
                                    <button class="pay-btn" type="button" onclick="event.stopPropagation(); callPay('{{ str_replace(' ', '', $settings['tigo_number']) }}')">Call</button>
                                @endif
                            </div>
                        </div>

                        <div class="pay-card" onclick="copyPay('{{ str_replace(' ', '', $settings['crdb_number'] ?? '') }}')">
                            <div class="pay-left">
                                <div class="pay-ic"><span class="material-symbols-outlined">account_balance</span></div>
                                <div>
                                    <div class="pay-title">CRDB Bank</div>
                                    <div class="pay-name">{{ $settings['crdb_name'] ?? '' }}</div>
                                    <div class="pay-num">{{ $settings['crdb_number'] ?? '' }}</div>
                                </div>
                            </div>
                            <div class="pay-actions">
                                @if(!empty($settings['crdb_number']))
                                    <button class="pay-btn" type="button">Copy</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="photo-ring">
                    <div class="photo-inner" onclick="openPayInfo()" title="Click to view other payment methods">
                        <img id="cliff-photo" src="{{ asset('WhatsApp Image 2026-03-10 at 17.56.33.jpeg') }}" alt="Cliff" style="display:block" />
                        <div class="photo-placeholder" id="photo-ph" style="display:none">
                            <span class="icon"><span class="material-symbols-outlined">photo_camera</span></span>
                            <span>Add Cliff's photo</span>
                        </div>
                    </div>
                </div>
                <input type="file" id="photo-input" accept="image/*" onchange="loadPhoto(event)">
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="payInfoModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal" style="max-width: 760px;">
            <div class="modal-head">
                <button class="modal-x" type="button" onclick="closePayInfo()" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <div class="modal-title"><span class="material-symbols-outlined">account_balance_wallet</span>Other payment methods</div>
                <div class="modal-sub">Use any of the options below. Tap <strong>Copy</strong> to copy the number/account.</div>
            </div>
            <div class="modal-body">
                <div class="pay-grid">
                    <div class="pay-card" style="border-left: 3px solid rgba(46,158,114,0.9);">
                        <div class="pay-left">
                            <div class="pay-ic" style="color: rgba(111,207,173,0.95);"><span class="material-symbols-outlined">phone_iphone</span></div>
                            <div>
                                <div class="pay-title" style="color: rgba(111,207,173,0.95);">Selcom Microfinance</div>
                                <div class="pay-name">Joseph Msuya</div>
                                <div class="pay-num" data-copy="0714172979">0714 172 979</div>
                            </div>
                        </div>
                        <div class="pay-actions">
                            <button class="pay-btn" type="button" onclick="copyPay('0714172979')">Copy</button>
                            <button class="pay-btn" type="button" onclick="callPay('0714172979')">Call</button>
                        </div>
                    </div>

                    <div class="pay-card" style="border-left: 3px solid rgba(91,156,255,0.9);">
                        <div class="pay-left">
                            <div class="pay-ic" style="color: rgba(91,156,255,0.95);"><span class="material-symbols-outlined">smartphone</span></div>
                            <div>
                                <div class="pay-title" style="color: rgba(91,156,255,0.95);">Tigo Pesa</div>
                                <div class="pay-name">Joseph Msuya</div>
                                <div class="pay-num" data-copy="0714172979">0714 172 979</div>
                            </div>
                        </div>
                        <div class="pay-actions">
                            <button class="pay-btn" type="button" onclick="copyPay('0714172979')">Copy</button>
                            <button class="pay-btn" type="button" onclick="callPay('0714172979')">Call</button>
                        </div>
                    </div>

                    <div class="pay-card" style="border-left: 3px solid rgba(255,106,66,0.9);">
                        <div class="pay-left">
                            <div class="pay-ic" style="color: rgba(255,106,66,0.95);"><span class="material-symbols-outlined">account_balance</span></div>
                            <div>
                                <div class="pay-title" style="color: rgba(255,106,66,0.95);">CRDB Bank</div>
                                <div class="pay-name">Joseph Abdallah Msuya</div>
                                <div class="pay-num" data-copy="0152396008400">0152 396 008 400</div>
                            </div>
                        </div>
                        <div class="pay-actions">
                            <button class="pay-btn" type="button" onclick="copyPay('0152396008400')">Copy</button>
                        </div>
                    </div>
                </div>

                <div class="pay-note">After paying using these methods, you can be recorded in the system via <strong>Admin → Manual Donations</strong>.</div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="donateModal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal">
            <div class="modal-head">
                <button class="modal-x" type="button" onclick="closeDonate()" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <div class="modal-title"><span class="material-symbols-outlined">volunteer_activism</span>Donate securely</div>
                <div class="modal-sub" id="modal-subtitle">You will be redirected to Snippe checkout to complete your donation.</div>
            </div>
            <div class="modal-body">
                <div id="donate-form">
                    <div class="mgrid">
                        <div class="mgrp">
                            <label>Full name</label>
                            <input class="minput" id="don-name" type="text" placeholder="e.g. Jane Mwangi" autocomplete="name" />
                        </div>
                        <div class="mgrp">
                            <label>Phone (optional)</label>
                            <input class="minput" id="don-phone" type="text" placeholder="e.g. +2557XXXXXXXX" autocomplete="tel" />
                        </div>
                    </div>
                    <div class="mgrid" style="margin-top:12px">
                        <div class="mgrp">
                            <label>Amount (TZS)</label>
                            <input class="minput" id="don-amount" type="number" min="1000" step="1" placeholder="e.g. 50000" />
                        </div>
                        <div class="mgrp">
                            <label>Email (optional)</label>
                            <input class="minput" id="don-email" type="email" placeholder="e.g. name@example.com" autocomplete="email" />
                        </div>
                    </div>

                    <div class="modal-error" id="don-err">Something went wrong.</div>

                    <div class="modal-actions">
                        <button class="btn modal secondary" type="button" onclick="closeDonate()">Cancel</button>
                        <button class="btn modal" type="button" id="don-btn" onclick="startDonate()">
                            <span class="material-symbols-outlined" style="font-size:1.15rem">lock</span>
                            Continue
                        </button>
                    </div>

                    <div class="modal-note">If you use mobile money, you may receive a prompt on your phone to authorize the payment.</div>
                </div>

                <div class="modal-success" id="don-success">
                    <div class="icon"><span class="material-symbols-outlined">check_circle</span></div>
                    <h4>Thank you so much!</h4>
                    <p id="success-msg">Your donation has been received successfully. Every shilling counts in Cliff's journey.</p>
                    <button class="btn modal primary" style="margin-top:18px; width:100%" onclick="closeDonate()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="balance-banner">
        <div class="bb-item">
            <div class="bb-label"><span class="mi"><span class="material-symbols-outlined">bolt</span></span>Live Net Balance</div>
            <div class="bb-value" id="bb-balance">—</div>
            <div class="bb-sub" id="bb-status">vs. expenses so far</div>
        </div>
        <div class="bb-item">
            <div class="bb-label"><span class="mi"><span class="material-symbols-outlined">payments</span></span>Total Raised</div>
            <div class="bb-value" id="bb-raised">—</div>
            <div class="bb-sub" id="bb-contributors">— contributors</div>
        </div>
        <div class="bb-item">
            <div class="bb-label"><span class="mi"><span class="material-symbols-outlined">target</span></span>Campaign Target</div>
            <div class="bb-value">{{ ($settings['currency'] ?? 'TZS') }} {{ number_format($settings['target_amount'] ?? 150000000) }}</div>
            <div class="bb-sub" id="bb-remaining">— remaining</div>
        </div>
        <div class="bb-item">
            <div class="bb-label"><span class="mi"><span class="material-symbols-outlined">local_hospital</span></span>Medical Expenses</div>
            <div class="bb-value">{{ ($settings['currency'] ?? 'TZS') }} {{ number_format($settings['expenses_amount'] ?? 2289225) }}</div>
            <div class="bb-sub">13 entries recorded</div>
        </div>
    </div>

    <div class="main-wrap" id="main">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card sc-green">
                    <div class="stat-icon"><span class="material-symbols-outlined">payments</span></div>
                    <div class="stat-label">Total Collected</div>
                    <div class="stat-value" id="s-collected">TZS —</div>
                    <div class="stat-sub" id="s-paid-n">— paid</div>
                </div>
                <div class="stat-card sc-gold">
                    <div class="stat-icon"><span class="material-symbols-outlined">target</span></div>
                    <div class="stat-label">Fundraising Target</div>
                    <div class="stat-value">{{ ($settings['currency'] ?? 'TZS') }} {{ number_format($settings['target_amount'] ?? 150000000) }}</div>
                    <div class="stat-sub" id="s-remaining">— remaining</div>
                </div>
                <div class="stat-card sc-coral">
                    <div class="stat-icon"><span class="material-symbols-outlined">local_hospital</span></div>
                    <div class="stat-label">Medical Expenses</div>
                    <div class="stat-value">{{ ($settings['currency'] ?? 'TZS') }} {{ number_format($settings['expenses_amount'] ?? 2289225) }}</div>
                    <div class="stat-sub">13 expense entries</div>
                </div>
                <div class="stat-card sc-sky">
                    <div class="stat-icon"><span class="material-symbols-outlined">hourglass_empty</span></div>
                    <div class="stat-label">Pending Contributors</div>
                    <div class="stat-value" id="s-pending">—</div>
                    <div class="stat-sub">yet to contribute</div>
                </div>
                <div class="stat-card sc-deep">
                    <div class="stat-icon"><span class="material-symbols-outlined">monitoring</span></div>
                    <div class="stat-label">Campaign Progress</div>
                    <div class="stat-value" id="s-pct">0%</div>
                    <div class="stat-sub">of target</div>
                </div>
            </div>

            <div class="progress-card">
                <div class="prog-top">
                    <div>
                        <div class="prog-title">Funding Journey</div>
                        <div class="prog-subtitle">Target: {{ ($settings['currency'] ?? 'TZS') }} {{ number_format($settings['target_amount'] ?? 150000000) }} — Every shilling counts.</div>
                    </div>
                    <div class="prog-pct" id="p-pct">0%<span>completed</span></div>
                </div>
                <div class="prog-track">
                    <div class="prog-fill" id="prog-bar" style="width:0%"></div>
                </div>
                <div class="prog-labels">
                    <span id="p-min">TZS 0</span>
                    <span class="current" id="p-mid">TZS 0 raised</span>
                    <span id="p-max">TZS {{ number_format($settings['target_amount'] ?? 150000000) }}</span>
                </div>
                <div class="milestones" id="milestones"></div>
            </div>

            <div class="tab-nav">
                <button class="tab-btn active" onclick="showTab('overview')">Overview</button>
                <button class="tab-btn" onclick="showTab('contributors')">All Contributors</button>
                <button class="tab-btn" onclick="showTab('expenses')">Expenses</button>
            </div>

            <div id="tab-overview">
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:24px">
                    <div class="section-card">
                        <div class="sec-header">
                            <span class="sec-title">Recent Paid Transactions</span>
                            <span id="paid-bdg" class="sec-badge">0 paid</span>
                        </div>
                        <div id="recent-paid" class="clist"></div>
                    </div>

                    <div class="section-card">
                        <div class="sec-header">
                            <span class="sec-title">Pledges (Ahadi/Manual)</span>
                            <span id="pledge-bdg" class="sec-badge" style="background:rgba(255,165,0,0.1);color:orange">0 pledges</span>
                        </div>
                        <div id="pledge-list" class="clist"></div>
                    </div>

                    <div class="section-card">
                        <div class="sec-header">
                            <span class="sec-title">Top Contributors</span>
                        </div>
                        <div id="bar-chart" style="padding:16px 0"></div>
                    </div>
                </div>
            </div>

            <div id="tab-contributors" class="hidden">
                <div class="section-card" style="margin-bottom:24px">
                    <div class="sec-header"><span class="sec-title">All contributions</span><span class="sec-badge" id="all-bdg">—</span></div>
                    <div class="tbl-wrap">
                        <table class="tbl">
                            <thead><tr><th>#</th><th>Name</th><th>Amount</th><th>Status</th><th>Paid</th><th>Reference</th></tr></thead>
                            <tbody id="all-tbl"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-expenses" class="hidden">
                <div class="section-card" style="margin-bottom:24px">
                    <div class="sec-header">
                        <span class="sec-title">Medical Expenses Log</span>
                        <span class="sec-badge">{{ ($settings['currency'] ?? 'TZS') }} {{ number_format($expenses->sum('amount') ?? 0) }}</span>
                    </div>
                    <div class="tbl-wrap">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount ({{ $settings['currency'] ?? 'TZS' }})</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody id="exp-tbl">
                                @forelse($expenses as $exp)
                                    <tr>
                                        <td style="font-family:var(--mono);font-size:0.72rem;color:var(--muted)">{{ $exp->spent_at->format('Y-m-d') }}</td>
                                        <td style="font-weight:500">{{ $exp->description }}</td>
                                        <td style="font-family:var(--mono);color:var(--coral)">{{ number_format($exp->amount) }}</td>
                                        <td>
                                            @if($exp->receipt_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($exp->receipt_path) }}" target="_blank" class="text-mint" style="font-size:0.75rem; text-decoration:underline">View Receipt</a>
                                            @else
                                                <span style="color:rgba(255,255,255,0.2)">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align:center; padding:20px; color:rgba(255,255,255,0.4)">No expenses recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="site-footer">
            <div class="foot-left">
                <span class="foot-mark"><span class="material-symbols-outlined">volunteer_activism</span></span>
                <div>
                    <div class="foot-title">Made with love for <strong>Cliff</strong></div>
                    <div class="foot-sub">By his people, for his life · Dar es Salaam, Tanzania</div>
                </div>
            </div>
            <div class="foot-right">
                <span class="foot-pill"><span class="material-symbols-outlined">shield</span>Transparency</span>
                <span class="foot-pill"><span class="material-symbols-outlined">monitoring</span>Live updates</span>
            </div>
        </div>
    </footer>

    <script>
        const SETTINGS = @json($settings ?? ['target_amount' => 150000000, 'expenses_amount' => 2289225, 'currency' => 'TZS']);
        const TARGET = parseInt(SETTINGS.target_amount || 150000000, 10);
        const EXPENSES = parseInt(SETTINGS.expenses_amount || 2289225, 10);
        const CUR = (SETTINGS.currency || 'TZS');

        const transactions = @json($transactions ?? []);

        let contributors = transactions.map(t => ({
            n: t.customer_name,
            a: parseInt(t.amount || 0, 10) || 0,
            s: t.status,
            e: t.webhook_event,
            paid_at: t.paid_at,
            created_at: t.created_at,
            ref: t.reference,
        })).filter(c => c.n && c.a);

        const f = n => Math.round(n).toLocaleString('en-TZ');
        const ini = name => name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
        const fmtDT = (iso) => {
            if (!iso) return '—';
            const d = new Date(iso);
            if (Number.isNaN(d.getTime())) return '—';
            return d.toLocaleString('en-TZ', { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit' });
        };

        function render() {
            const paid = contributors.filter(c => c.s === 'completed' && c.a > 10).sort((a, b) => b.a - a.a);
            const pend = contributors.filter(c => c.s === 'pending' && c.e === 'manual').sort((a, b) => b.a - a.a);
            const total = paid.reduce((s, c) => s + c.a, 0);
            const balance = total - EXPENSES;
            const remaining = TARGET - total;
            const pct = Math.min(100, total / TARGET * 100);
            const pctStr = pct < 1 ? pct.toFixed(3) : pct.toFixed(2);

            document.getElementById('bb-balance').textContent = (balance >= 0 ? '+' : '') + CUR + ' ' + f(balance);
            document.getElementById('bb-status').textContent = balance >= 0 ? 'Expenses fully covered' : 'Shortfall of ' + CUR + ' ' + f(Math.abs(balance));
            document.getElementById('bb-raised').textContent = CUR + ' ' + f(total);
            document.getElementById('bb-contributors').textContent = paid.length + ' contributors paid';
            document.getElementById('bb-remaining').textContent = CUR + ' ' + f(Math.max(0, remaining)) + ' remaining';

            document.getElementById('s-collected').textContent = CUR + ' ' + f(total);
            document.getElementById('s-paid-n').textContent = paid.length + ' contributors paid';
            document.getElementById('s-remaining').textContent = CUR + ' ' + f(Math.max(0, remaining)) + ' to go';
            document.getElementById('s-pending').textContent = pend.length;
            document.getElementById('s-pct').textContent = pctStr + '%';

            document.getElementById('p-pct').innerHTML = pctStr + '%<span>completed</span>';
            document.getElementById('prog-bar').style.width = pct + '%';
            document.getElementById('p-mid').textContent = CUR + ' ' + f(total) + ' raised';
            document.getElementById('p-min').textContent = CUR + ' 0';
            document.getElementById('p-max').textContent = CUR + ' ' + f(TARGET);

            const marks = [
                { l: '10M', v: 10000000 },
                { l: '25M', v: 25000000 },
                { l: '50M', v: 50000000 },
                { l: '100M', v: 100000000 },
                { l: 'Target', v: TARGET },
            ];
            document.getElementById('milestones').innerHTML = marks.map(m =>
                `<span class="ms ${total >= m.v ? 'done' : 'todo'}">
                    <span class="material-symbols-outlined" style="font-size:1rem">${total >= m.v ? 'check_circle' : 'radio_button_unchecked'}</span>
                    ${m.l}
                </span>`
            ).join('');

            const overviewPaid = [...paid].sort((a, b) => b.a - a.a);
            const allPaid = [...paid].sort((a, b) => {
                const da = new Date(a.paid_at || a.created_at).getTime();
                const db = new Date(b.paid_at || b.created_at).getTime();
                return db - da;
            });

            document.getElementById('paid-bdg').textContent = paid.length + ' paid';
            document.getElementById('recent-paid').innerHTML = overviewPaid.slice(0, 15).map(c => `
                <div class="citem">
                    <div style="display:flex;align-items:center">
                        <div class="cavatar">${ini(c.n)}</div>
                        <div><div class="cname">${c.n}</div><div class="cgrp">${fmtDT(c.paid_at || c.created_at)}</div></div>
                    </div>
                    <div class="cright"><span class="camt">${CUR} ${f(c.a)}</span><span class="bdg paid">paid</span></div>
                </div>
            `).join('');

            // Pledges (Ahadi)
            document.getElementById('pledge-bdg').textContent = pend.length + ' pledges';
            document.getElementById('pledge-list').innerHTML = pend.length ? pend.slice(0, 15).map(c => `
                <div class="citem">
                    <div style="display:flex;align-items:center">
                        <div class="cavatar" style="background:rgba(255,165,0,0.1);color:orange">${ini(c.n)}</div>
                        <div><div class="cname">${c.n}</div><div class="cgrp">Pledged on ${fmtDT(c.created_at)}</div></div>
                    </div>
                    <div class="cright"><span class="bdg" style="background:rgba(255,165,0,0.1);color:orange">ahadi</span></div>
                </div>
            `).join('') : '<div style="padding:20px;text-align:center;color:var(--muted);font-size:0.8rem">No pending pledges found.</div>';

            // Home page should display paid contributions only
            document.getElementById('all-bdg').textContent = paid.length + ' paid';
            document.getElementById('all-tbl').innerHTML = allPaid.map((c, i) => `
                <tr>
                    <td style="color:var(--light);font-family:var(--mono);font-size:0.7rem">${i + 1}</td>
                    <td style="font-weight:600">${c.n}</td>
                    <td style="font-family:var(--mono);color:${c.s === 'completed' ? 'var(--forest)' : 'var(--light)'}">${CUR} ${f(c.a)}</td>
                    <td><span class="bdg ${c.s === 'completed' ? 'paid' : 'pending'}">${c.s === 'completed' ? 'paid' : 'pending'}</span></td>
                    <td style="font-family:var(--mono);font-size:0.72rem;color:var(--muted)">${c.s === 'completed' ? fmtDT(c.paid_at || c.created_at) : '—'}</td>
                    <td style="font-family:var(--mono);font-size:0.72rem;color:var(--muted)">${c.ref || '—'}</td>
                </tr>
            `).join('');

            document.getElementById('exp-tbl').innerHTML = `
                <tr>
                    <td style="font-family:var(--mono);font-size:0.7rem;color:var(--muted)">—</td>
                    <td style="font-weight:500">Total medical expenses to date</td>
                    <td style="font-family:var(--mono);color:var(--coral)">${CUR} ${f(EXPENSES)}</td>
                </tr>
            `;

            const top10 = overviewPaid.slice(0, 10);
            const max = top10[0]?.a || 1;
            document.getElementById('bar-chart').innerHTML = top10.map(c => {
                const w = Math.round(c.a / max * 100);
                return `<div class="brow">
                    <div class="blabel">${c.n}</div>
                    <div class="btrack"><div class="bfill" style="width:${w}%">${w > 28 ? CUR + ' ' + f(c.a) : ''}</div></div>
                    ${w <= 28 ? `<span style="font-family:var(--mono);font-size:0.63rem;color:var(--forest);margin-left:4px">${CUR} ${f(c.a)}</span>` : ''}
                </div>`;
            }).join('');
        }

        function showTab(tab) {
            ['overview', 'contributors', 'expenses'].forEach(t => {
                document.getElementById('tab-' + t).classList.toggle('hidden', t !== tab);
            });
            document.querySelectorAll('.tab-btn').forEach((b, i) => {
                b.classList.toggle('active', ['overview', 'contributors', 'expenses'][i] === tab);
            });
        }

        function loadPhoto(e) {
            const file = e.target.files[0];
            if (!file) return;
            const r = new FileReader();
            r.onload = ev => {
                const img = document.getElementById('cliff-photo');
                img.src = ev.target.result;
                img.style.display = 'block';
                document.getElementById('photo-ph').style.display = 'none';
            };
            r.readAsDataURL(file);
        }

        function openDonate() {
            const m = document.getElementById('donateModal');
            m.classList.add('open');
            m.setAttribute('aria-hidden', 'false');
            document.getElementById('don-err').style.display = 'none';
            document.getElementById('don-success').classList.remove('show');
            document.getElementById('donate-form').style.display = 'block';
            document.getElementById('modal-subtitle').style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('don-name')?.focus(), 50);
        }

        function closeDonate() {
            const m = document.getElementById('donateModal');
            m.classList.remove('open');
            m.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        async function startDonate() {
            const btn = document.getElementById('don-btn');
            const err = document.getElementById('don-err');
            
            const name = document.getElementById('don-name').value.trim();
            const email = document.getElementById('don-email').value.trim();
            const phone = document.getElementById('don-phone').value.trim();
            const amount = document.getElementById('don-amount').value.trim();

            if (!amount || parseInt(amount) < 1000) {
                err.innerText = 'Please enter a valid amount (Min TZS 1,000)';
                err.style.display = 'block';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined spin">sync</span> Processing...';
            err.style.display = 'none';

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ route('donate.session') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ name, email, phone, amount })
                });

                const data = await res.json();

                if (res.ok && data.checkout_url) {
                    window.location.href = data.checkout_url;
                } else {
                    throw new Error(data.message || 'Unable to start donation');
                }
            } catch (e) {
                err.innerText = e.message;
                err.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:1.15rem">lock</span> Continue';
            }
        }

        function openPayInfo() {
            const m = document.getElementById('payInfoModal');
            m.classList.add('open');
            m.setAttribute('aria-hidden', 'false');
        }

        function closePayInfo() {
            const m = document.getElementById('payInfoModal');
            m.classList.remove('open');
            m.setAttribute('aria-hidden', 'true');
        }

        async function copyPay(text) {
            try {
                if (navigator.clipboard?.writeText) {
                    await navigator.clipboard.writeText(text);
                } else {
                    const ta = document.createElement('textarea');
                    ta.value = text;
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                }
                alert('Copied: ' + text);
            } catch (e) {
                alert('Unable to copy. Please copy manually: ' + text);
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDonate();
        });

        document.getElementById('donateModal')?.addEventListener('click', (e) => {
            if (e.target?.id === 'donateModal') closeDonate();
        });

        render();
    </script>
</body>
</html>
