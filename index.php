<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ToDo — Organizează-ți ziua</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg:        #0f1120;
      --surface:   #141622;
      --card:      #1a1d2e;
      --border:    #232642;
      --border2:   #2e3250;
      --accent:    #6c8ef5;
      --accent2:   #8faaff;
      --green:     #3dbf7c;
      --red:       #d96a6a;
      --orange:    #e08c3b;
      --yellow:    #c8a840;
      --text:      #dce0f5;
      --muted:     #9098b8;
      --dim:       #565d80;
      --font-head: 'Syne', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    html { scroll-behavior: smooth; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      line-height: 1.6;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
      pointer-events: none;
      z-index: 0;
    }

    .blob {
      position: fixed;
      border-radius: 50%;
      filter: blur(120px);
      opacity: 0.12;
      pointer-events: none;
      z-index: 0;
      animation: drift 18s ease-in-out infinite;
    }
    .blob-1 { width: 600px; height: 600px; background: var(--accent); top: -200px; left: -150px; animation-delay: 0s; }
    .blob-2 { width: 500px; height: 500px; background: #a87edb; bottom: -150px; right: -100px; animation-delay: -9s; }

    @keyframes drift {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33%       { transform: translate(40px, 30px) scale(1.05); }
      66%       { transform: translate(-20px, 50px) scale(0.97); }
    }

    header {
      position: sticky; top: 0; z-index: 100;
      background: rgba(15, 17, 32, 0.82);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      padding: 0 clamp(20px, 5vw, 80px);
      display: flex; align-items: center; justify-content: space-between;
      height: 64px;
      animation: slideDown 0.6s ease both;
    }

    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to   { transform: translateY(0);    opacity: 1; }
    }

    .nav-logo {
      font-family: var(--font-head);
      font-weight: 800;
      font-size: 22px;
      color: var(--accent);
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
    }

    .nav-logo .mark {
      background: var(--accent);
      color: #fff;
      width: 30px; height: 30px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
    }

    .nav-actions { display: flex; align-items: center; gap: 12px; }

    .btn {
      font-family: var(--font-body);
      font-weight: 500;
      font-size: 14px;
      padding: 9px 22px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s ease;
      display: inline-block;
    }

    .btn-ghost {
      background: transparent;
      border: 1px solid var(--border2);
      color: var(--muted);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

    .btn-primary {
      background: var(--accent);
      border: 1px solid var(--accent);
      color: #fff;
    }
    .btn-primary:hover { background: #5a7de0; transform: translateY(-1px); box-shadow: 0 6px 24px rgba(108,142,245,0.35); }

    .hero {
      position: relative; z-index: 1;
      min-height: calc(100vh - 64px);
      display: flex; align-items: center; justify-content: center;
      flex-direction: column;
      text-align: center;
      padding: 80px clamp(20px, 5vw, 80px) 60px;
    }

    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(108,142,245,0.1);
      border: 1px solid rgba(108,142,245,0.25);
      border-radius: 20px;
      padding: 6px 16px;
      font-size: 13px;
      color: var(--accent2);
      margin-bottom: 36px;
      animation: fadeUp 0.7s ease 0.2s both;
    }

    .hero-badge .dot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--green);
      animation: pulse 2s ease infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: 0.5; transform: scale(0.7); }
    }

    .hero h1 {
      font-family: var(--font-head);
      font-weight: 800;
      font-size: clamp(42px, 7vw, 88px);
      line-height: 1.05;
      letter-spacing: -2px;
      color: var(--text);
      margin-bottom: 28px;
      animation: fadeUp 0.7s ease 0.35s both;
    }

    .hero h1 .accent { color: var(--accent); }
    .hero h1 .line2  { display: block; color: var(--muted); font-weight: 600; }

    .hero p {
      font-size: clamp(16px, 2.2vw, 20px);
      color: var(--muted);
      max-width: 560px;
      margin: 0 auto 48px;
      font-weight: 300;
      animation: fadeUp 0.7s ease 0.5s both;
    }

    .hero-cta {
      display: flex; align-items: center; gap: 16px; flex-wrap: wrap; justify-content: center;
      animation: fadeUp 0.7s ease 0.65s both;
    }

    .btn-large {
      padding: 14px 36px;
      font-size: 16px;
      border-radius: 10px;
    }

    .hero-note {
      margin-top: 16px;
      font-size: 13px;
      color: var(--dim);
      animation: fadeUp 0.7s ease 0.8s both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .preview-wrap {
      position: relative; z-index: 1;
      padding: 0 clamp(20px, 5vw, 80px) 100px;
      animation: fadeUp 0.9s ease 0.9s both;
    }

    .preview-frame {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 40px 120px rgba(0,0,0,0.6), 0 0 0 1px rgba(108,142,245,0.08);
      max-width: 900px;
      margin: 0 auto;
    }

    .preview-bar {
      background: var(--bg);
      border-bottom: 1px solid var(--border);
      padding: 12px 16px;
      display: flex; align-items: center; gap: 10px;
    }

    .dot-row { display: flex; gap: 6px; }
    .dot-row span { width: 10px; height: 10px; border-radius: 50%; }
    .dot-row .d1 { background: #d96a6a; }
    .dot-row .d2 { background: #c8a840; }
    .dot-row .d3 { background: var(--green); }

    .url-bar {
      flex: 1;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 5px 14px;
      font-size: 12px;
      color: var(--dim);
      font-family: monospace;
      text-align: center;
    }

    .mini-app {
      display: flex;
      height: 380px;
      font-family: var(--font-body);
    }

    .mini-sidebar {
      width: 160px;
      background: var(--bg);
      border-right: 1px solid var(--border);
      padding: 16px 10px;
      display: flex; flex-direction: column; gap: 4px;
    }

    .mini-section-title {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--dim);
      padding: 4px 8px;
      margin-top: 8px;
    }

    .mini-nav-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 6px 8px;
      border-radius: 5px;
      font-size: 11px;
      color: var(--muted);
    }

    .mini-nav-item.active {
      background: rgba(108,142,245,0.12);
      color: var(--accent);
    }

    .mini-badge {
      background: var(--accent);
      color: #fff;
      font-size: 9px;
      padding: 1px 5px;
      border-radius: 8px;
    }

    .mini-badge.green  { background: var(--green); }
    .mini-badge.red    { background: var(--red); }
    .mini-badge.orange { background: var(--orange); }

    .mini-main {
      flex: 1;
      padding: 16px 20px;
      overflow: hidden;
    }

    .mini-stats {
      display: flex; gap: 10px; margin-bottom: 14px;
    }

    .mini-stat {
      background: var(--card);
      border: 1px solid var(--border2);
      border-radius: 7px;
      padding: 10px 12px;
      flex: 1;
      font-size: 10px;
      color: var(--dim);
    }

    .mini-stat .v {
      font-family: var(--font-head);
      font-size: 22px;
      font-weight: 700;
      color: var(--text);
      display: block;
    }

    .mini-card {
      background: var(--card);
      border: 1px solid var(--border2);
      border-radius: 7px;
      padding: 10px 12px;
      margin-bottom: 8px;
      display: flex; align-items: center; gap: 10px;
    }

    .mini-check {
      width: 13px; height: 13px;
      border-radius: 3px;
      border: 1.5px solid var(--border2);
      flex-shrink: 0;
    }

    .mini-check.done {
      background: var(--accent);
      border-color: var(--accent);
      position: relative;
    }

    .mini-check.done::after {
      content: '✓';
      position: absolute;
      font-size: 8px;
      color: #fff;
      top: 50%; left: 50%;
      transform: translate(-50%,-50%);
    }

    .mini-task-title {
      font-size: 11px;
      color: var(--text);
      flex: 1;
    }

    .mini-task-title.done { text-decoration: line-through; color: var(--dim); }

    .mini-tag {
      font-size: 9px;
      padding: 2px 6px;
      border-radius: 3px;
      font-weight: 600;
    }

    .mini-tag.san  { background: #1e4a3a; color: #5bc99a; }
    .mini-tag.high { background: #4a1e1e; color: #d96a6a; }
    .mini-tag.edu  { background: #3a2060; color: #a87edb; }
    .mini-tag.munca { background: #1e3a5f; color: #5ba3d4; }

    .section {
      position: relative; z-index: 1;
      padding: 100px clamp(20px, 5vw, 80px);
    }

    .section-header {
      text-align: center;
      margin-bottom: 64px;
    }

    .section-label {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--accent);
      margin-bottom: 16px;
    }

    .section-header h2 {
      font-family: var(--font-head);
      font-weight: 800;
      font-size: clamp(32px, 5vw, 52px);
      letter-spacing: -1px;
      line-height: 1.1;
    }

    .section-header p {
      color: var(--muted);
      font-size: 17px;
      margin-top: 16px;
      font-weight: 300;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .feat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 32px 28px;
      transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
      position: relative;
      overflow: hidden;
    }

    .feat-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top left, rgba(108,142,245,0.06) 0%, transparent 60%);
      pointer-events: none;
    }

    .feat-card:hover {
      border-color: rgba(108,142,245,0.4);
      transform: translateY(-4px);
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }

    .feat-icon {
      font-size: 32px;
      margin-bottom: 20px;
      display: block;
    }

    .feat-card h3 {
      font-family: var(--font-head);
      font-weight: 700;
      font-size: 18px;
      margin-bottom: 10px;
      color: var(--text);
    }

    .feat-card p {
      font-size: 14px;
      color: var(--muted);
      line-height: 1.65;
      font-weight: 300;
    }

    .stats-strip {
      position: relative; z-index: 1;
      background: var(--surface);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 60px clamp(20px, 5vw, 80px);
      display: flex;
      justify-content: center;
      gap: 80px;
      flex-wrap: wrap;
    }

    .stat-item {
      text-align: center;
    }

    .stat-item .number {
      font-family: var(--font-head);
      font-size: 52px;
      font-weight: 800;
      color: var(--accent);
      line-height: 1;
      display: block;
    }

    .stat-item .label {
      font-size: 13px;
      color: var(--muted);
      margin-top: 8px;
      font-weight: 300;
    }

    .steps {
      display: flex;
      gap: 0;
      max-width: 800px;
      margin: 0 auto;
      position: relative;
    }

    .steps::before {
      content: '';
      position: absolute;
      top: 28px;
      left: calc(28px + 16.66%);
      right: calc(28px + 16.66%);
      height: 1px;
      background: linear-gradient(90deg, var(--border2), var(--accent), var(--border2));
    }

    .step {
      flex: 1;
      text-align: center;
      padding: 0 16px;
    }

    .step-num {
      width: 56px; height: 56px;
      border-radius: 50%;
      background: var(--card);
      border: 1px solid var(--border2);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-head);
      font-weight: 800;
      font-size: 20px;
      color: var(--accent);
      margin: 0 auto 20px;
      position: relative; z-index: 1;
      transition: background 0.25s, border-color 0.25s;
    }

    .step:hover .step-num {
      background: rgba(108,142,245,0.15);
      border-color: var(--accent);
    }

    .step h4 {
      font-family: var(--font-head);
      font-weight: 700;
      font-size: 15px;
      margin-bottom: 8px;
    }

    .step p {
      font-size: 13px;
      color: var(--muted);
      font-weight: 300;
    }

    .cta-section {
      position: relative; z-index: 1;
      padding: 120px clamp(20px, 5vw, 80px);
      text-align: center;
    }

    .cta-box {
      background: linear-gradient(135deg, rgba(108,142,245,0.1) 0%, rgba(168,126,219,0.08) 100%);
      border: 1px solid rgba(108,142,245,0.2);
      border-radius: 24px;
      padding: 80px 40px;
      max-width: 720px;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
    }

    .cta-box::before {
      content: '✦';
      position: absolute;
      font-size: 300px;
      color: rgba(108,142,245,0.03);
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      font-family: var(--font-head);
      pointer-events: none;
    }

    .cta-box h2 {
      font-family: var(--font-head);
      font-size: clamp(28px, 4vw, 44px);
      font-weight: 800;
      letter-spacing: -1px;
      margin-bottom: 16px;
    }

    .cta-box p {
      color: var(--muted);
      font-size: 16px;
      margin-bottom: 40px;
      font-weight: 300;
    }

    footer {
      position: relative; z-index: 1;
      background: var(--surface);
      border-top: 1px solid var(--border);
      padding: 32px clamp(20px, 5vw, 80px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    footer .copy {
      font-size: 13px;
      color: var(--dim);
    }

    footer .links {
      display: flex; gap: 24px;
    }

    footer .links a {
      font-size: 13px;
      color: var(--dim);
      text-decoration: none;
      transition: color 0.2s;
    }

    footer .links a:hover { color: var(--muted); }

    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<header>
  <a href="landing.php" class="nav-logo">
    <span class="mark">✦</span>
    ToDo
  </a>
  <nav class="nav-actions">
    <a href="login.php" class="btn btn-ghost">Autentificare</a>
    <a href="register.php" class="btn btn-primary">Încearcă gratuit</a>
  </nav>
</header>

<section class="hero">
  <h1>
    Organizează-ți <span class="accent">ziua</span><br/>
    <span class="line2">fără efort.</span>
  </h1>

  <p>
    Gestionează sarcini, termene-limită și priorități dintr-un singur loc.
    Simplu, rapid și accesibil de oriunde.
  </p>

  <div class="hero-cta">
    <a href="register.php" class="btn btn-primary btn-large">Creează cont gratuit</a>
    <a href="login.php" class="btn btn-ghost btn-large">Intră în cont →</a>
  </div>

  <p class="hero-note">Fără plăți · Gratuit pentru totdeauna</p>
</section>

<div class="preview-wrap">
  <div class="preview-frame">
    <div class="preview-bar">
      <div class="dot-row">
        <span class="d1"></span>
        <span class="d2"></span>
        <span class="d3"></span>
      </div>
      <div class="url-bar">localhost/todo/index.php</div>
    </div>

    <div class="mini-app">
      <div class="mini-sidebar">
        <span class="mini-section-title">Meniu</span>
        <div class="mini-nav-item active">Toate sarcinile <span class="mini-badge">4</span></div>
        <div class="mini-nav-item">Azi <span class="mini-badge red">1</span></div>
        <div class="mini-nav-item">Finalizate <span class="mini-badge green">1</span></div>
        <div class="mini-nav-item">Expirate <span class="mini-badge orange">2</span></div>
        <span class="mini-section-title">Categorii</span>
        <div class="mini-nav-item">Muncă <span class="mini-badge">1</span></div>
        <div class="mini-nav-item">Personal <span class="mini-badge">1</span></div>
        <div class="mini-nav-item">Sănătate <span class="mini-badge">1</span></div>
        <div class="mini-nav-item">Educație <span class="mini-badge">1</span></div>
      </div>

      <div class="mini-main">
        <div class="mini-stats">
          <div class="mini-stat"><span class="v">4</span>Total</div>
          <div class="mini-stat"><span class="v">3</span>Active</div>
          <div class="mini-stat"><span class="v">1</span>Finalizate</div>
          <div class="mini-stat"><span class="v">2</span>Expirate</div>
        </div>

        <div class="mini-card">
          <div class="mini-check"></div>
          <span class="mini-task-title">Examen auto — Ora 12:40</span>
          <span class="mini-tag high">Ridicată</span>
        </div>
        <div class="mini-card">
          <div class="mini-check"></div>
          <span class="mini-task-title">Finalizează raportul DAW-241</span>
          <span class="mini-tag munca">Muncă</span>
        </div>
        <div class="mini-card">
          <div class="mini-check"></div>
          <span class="mini-task-title">Citit capitolul 5</span>
          <span class="mini-tag edu">Educație</span>
        </div>
        <div class="mini-card">
          <div class="mini-check done"></div>
          <span class="mini-task-title done">Exerciții dimineață</span>
          <span class="mini-tag san">Sănătate</span>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="section">
  <div class="section-header reveal">
    <p class="section-label">Funcționalități</p>
    <h2>Tot ce ai nevoie<br/>pentru a fi organizat</h2>
    <p>Fără complexitate inutilă. Exact ce trebuie.</p>
  </div>

  <div class="features-grid">
    <div class="feat-card reveal">
      <span class="feat-icon">📋</span>
      <h3>Gestionare sarcini</h3>
      <p>Adaugă, editează și șterge sarcini cu titlu, descriere, dată-limită și prioritate. Totul cu un singur click.</p>
    </div>
    <div class="feat-card reveal">
      <span class="feat-icon">🏷️</span>
      <h3>Categorii și priorități</h3>
      <p>Organizează pe categorii (Muncă, Personal, Sănătate, Educație) și niveluri de prioritate pentru o vizualizare clară.</p>
    </div>
    <div class="feat-card reveal">
      <span class="feat-icon">📊</span>
      <h3>Statistici în timp real</h3>
      <p>Monitorizează progresul cu carduri de statistici: total, active, finalizate și expirate — actualizate instant.</p>
    </div>
    <div class="feat-card reveal">
      <span class="feat-icon">🔍</span>
      <h3>Filtrare avansată</h3>
      <p>Filtrează sarcinile după stare, categorie sau prioritate. Găsești orice în secunde.</p>
    </div>
    <div class="feat-card reveal">
      <span class="feat-icon">🔐</span>
      <h3>Conturi personale</h3>
      <p>Înregistrare și autentificare securizată. Fiecare utilizator vede doar propriile sarcini.</p>
    </div>
    <div class="feat-card reveal">
      <span class="feat-icon">⚡</span>
      <h3>Rapid și ușor</h3>
      <p>Interfață AJAX fără reîncărcare de pagină. Acțiunile se reflectă imediat în interfață.</p>
    </div>
  </div>
</section>

<div class="stats-strip reveal">
  <div class="stat-item">
    <span class="number">4</span>
    <span class="label">Categorii de organizare</span>
  </div>
  <div class="stat-item">
    <span class="number">3</span>
    <span class="label">Niveluri de prioritate</span>
  </div>
  <div class="stat-item">
    <span class="number">100%</span>
    <span class="label">Gratuit</span>
  </div>
</div>

<section class="section">
  <div class="section-header reveal">
    <p class="section-label">Cum funcționează</p>
    <h2>Trei pași simpli</h2>
  </div>

  <div class="steps reveal">
    <div class="step">
      <div class="step-num">1</div>
      <h4>Creează contul</h4>
      <p>Înregistrare rapidă cu nume, email și parolă. Nicio informație inutilă.</p>
    </div>
    <div class="step">
      <div class="step-num">2</div>
      <h4>Adaugă sarcini</h4>
      <p>Completează titlul, categoria, prioritatea și data-limită. Gata în 10 secunde.</p>
    </div>
    <div class="step">
      <div class="step-num">3</div>
      <h4>Finalizează și urmărește</h4>
      <p>Bifează sarcinile completate și monitorizează progresul în timp real.</p>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-box reveal">
    <h2>Gata să fii mai organizat?</h2>
    <p>Creează-ți contul acum și începe să-ți gestionezi sarcinile.<br/>Este gratuit și durează mai puțin de un minut.</p>
    <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
      <a href="register.php" class="btn btn-primary btn-large">Încearcă gratuit</a>
      <a href="login.php" class="btn btn-ghost btn-large">Am deja cont</a>
    </div>
  </div>
</section>

<footer>
  <span class="copy">&copy; 2026 ToDo App — Micu Nicolae</span>
  <div class="links">
    <a href="login.php">Autentificare</a>
    <a href="register.php">Înregistrare</a>
  </div>
</footer>

<script>
  const observer = new IntersectionObserver(
    entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }),
    { threshold: 0.12 }
  );
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>