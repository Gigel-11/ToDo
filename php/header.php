<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
?>
<header>
  <a href="index.php" class="nav-logo">
    <span class="mark">✦</span>
    ToDo
  </a>
  <div class="header-right">
    <?php if (isLoggedIn()): ?>
      <input type="text" id="searchInput" placeholder="🔍 Caută sarcini..."/>
      <a href="logout.php" class="notif-btn" title="Deconectare">⏻</a>
    <?php else: ?>
      <nav class="nav-actions">
        <a href="login.php" class="btn btn-ghost">Autentificare</a>
        <a href="register.php" class="btn btn-primary">Încearcă gratuit</a>
      </nav>
    <?php endif; ?>
    <button id="themeToggle" class="btn btn-ghost" title="Schimbă tema">☀️</button>
    <select id="langSelect" aria-label="Language" style="margin-left:8px;background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);padding:6px;border-radius:6px;">
      <option value="ro">RO</option>
      <option value="en">EN</option>
    </select>
  </div>
</header>
