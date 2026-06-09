<?php
require_once 'php/auth.php';
require_once 'php/functions.php';

requireLogin();

$user  = getCurrentUser();
$tasks = getItemsByUser($user['id']);
$stats = getStats($tasks);

$filterStatus   = $_GET['status']   ?? 'toate';
$filterCategory = $_GET['category'] ?? 'toate';
$filterPriority = $_GET['priority'] ?? 'toate';

$filtered = $tasks;
if ($filterStatus === 'active')     $filtered = array_filter($filtered, fn($t) => !$t['done']);
if ($filterStatus === 'finalizate') $filtered = array_filter($filtered, fn($t) => $t['done']);
if ($filterCategory !== 'toate')    $filtered = array_filter($filtered, fn($t) => $t['category'] === $filterCategory);
if ($filterPriority !== 'toate')    $filtered = array_filter($filtered, fn($t) => $t['priority'] === $filterPriority);
$filtered = array_values($filtered);

$activeTasks = array_values(array_filter($filtered, fn($t) => !$t['done']));
$doneTasks   = array_values(array_filter($filtered, fn($t) => $t['done']));

$categories = ['Muncă', 'Personal', 'Sănătate', 'Educație'];
$catColors  = ['Muncă' => '#5ba3d4', 'Personal' => '#d96a6a', 'Sănătate' => '#5bc99a', 'Educație' => '#c8a840'];

$catBadges = [];
foreach ($categories as $cat) {
    $catBadges[$cat] = count(array_filter($tasks, fn($t) => !$t['done'] && $t['category'] === $cat));
}

function priorityClass(string $p): string {
    return match($p) { 'ridicată' => 'high', 'medie' => 'med', 'scăzută' => 'low', default => 'low' };
}
function categoryClass(string $c): string {
    return match($c) { 'Muncă' => 'munca', 'Personal' => 'high', 'Sănătate' => 'san', 'Educație' => 'edu', default => 'med' };
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ToDo App</title>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<div id="taskModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <div class="modal-header">
      <h3>+ Sarcină nouă</h3>
      <button class="modal-close" id="modalClose">✕</button>
    </div>

    <div id="modalError" class="msg-error" style="display:none;"></div>

    <label>Titlu <span class="req">*</span></label>
    <input type="text" id="taskTitle" placeholder="Ex: Finalizează raportul..." maxlength="120"/>

    <label>Descriere</label>
    <textarea id="taskDesc" placeholder="Detalii opționale..." rows="3"></textarea>

    <div class="modal-row">
      <div>
        <label>Categorie</label>
        <select id="taskCategory">
          <option value="Personal">Personal</option>
          <option value="Muncă">Muncă</option>
          <option value="Sănătate">Sănătate</option>
          <option value="Educație">Educație</option>
        </select>
      </div>
      <div>
        <label>Prioritate</label>
        <select id="taskPriority">
          <option value="scăzută">Scăzută</option>
          <option value="medie" selected>Medie</option>
          <option value="ridicată">Ridicată</option>
        </select>
      </div>
      <div>
        <label>Dată limită</label>
        <input type="date" id="taskDue"/>
      </div>
    </div>

    <div class="modal-actions">
      <button id="modalCancel" class="btn-cancel">Anulează</button>
      <button id="modalSave"   class="btn-save">Salvează sarcina</button>
    </div>
  </div>
</div>

<header>
  <a href="index.php" class="nav-logo">
    <span class="mark">✦</span>
    ToDo
  </a>
  <div class="header-right">
    <input type="text" id="searchInput" placeholder="🔍 Caută sarcini..."/>
    <a href="logout.php" class="notif-btn" title="Deconectare">⏻</a>
  </div>
</header>

<div class="wrapper">
  <nav>
    <p class="section-title">Meniu</p>
    <ul>
      <li><a href="?status=toate"     <?= $filterStatus==='toate'     ? 'class="active"':'' ?>>Toate sarcinile <span class="badge"><?= $stats['total'] ?></span></a></li>
      <li><a href="?status=azi"       <?= $filterStatus==='azi'       ? 'class="active"':'' ?>>Azi <span class="badge red"><?= count(iterator_to_array((function() use($stats){ foreach($stats['todayList'] as $t) yield $t; })())) ?></span></a></li>
      <li><a href="?status=finalizate"<?= $filterStatus==='finalizate'? 'class="active"':'' ?>>Finalizate <span class="badge green"><?= $stats['done'] ?></span></a></li>
      <li><a href="?priority=ridicată"<?= $filterPriority==='ridicată'? 'class="active"':'' ?>>Priorități înalte</a></li>
      <li><a href="?status=expirate"  <?= $filterStatus==='expirate'  ? 'class="active"':'' ?>>Expirate <span class="badge orange"><?= $stats['expired'] ?></span></a></li>
    </ul>

    <p class="section-title">Categorii</p>
    <ul>
      <?php foreach ($categories as $cat): ?>
      <li>
        <a href="?category=<?= urlencode($cat) ?>" <?= $filterCategory===$cat ? 'class="active"':'' ?>>
          <span class="cat-dot" style="background:<?= $catColors[$cat] ?>"></span>
          <?= $cat ?>
          <span class="badge"><?= $catBadges[$cat] ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <div class="user">
      <div class="avatar"><?= strtoupper(substr($user['name'], 0, 2)) ?></div>
      <div>
        <div class="name"><?= htmlspecialchars($user['name']) ?></div>
        <div class="sub">Cont personal</div>
      </div>
    </div>
  </nav>

  <main>
    <h1>Toate Sarcinile</h1>

    <div class="stats">
      <div class="stat-card"><div class="icon">📋</div><div class="info"><div class="label">Total sarcini</div><div class="value"><?= $stats['total'] ?></div></div></div>
      <div class="stat-card"><div class="icon">⚡</div><div class="info"><div class="label">Active</div><div class="value"><?= $stats['active'] ?></div></div></div>
      <div class="stat-card"><div class="icon">✅</div><div class="info"><div class="label">Finalizate</div><div class="value"><?= $stats['done'] ?></div></div></div>
      <div class="stat-card"><div class="icon">⚠️</div><div class="info"><div class="label">Expirate</div><div class="value"><?= $stats['expired'] ?></div></div></div>
    </div>

    <div class="filters">
      <a href="?status=toate"><button <?= $filterStatus==='toate' ? 'class="active"':'' ?>>Toate</button></a>
      <a href="?status=active"><button <?= $filterStatus==='active' ? 'class="active"':'' ?>>Active</button></a>
      <a href="?status=finalizate"><button <?= $filterStatus==='finalizate' ? 'class="active"':'' ?>>Finalizate</button></a>
      <select onchange="location='?category='+this.value">
        <option value="toate" <?= $filterCategory==='toate' ? 'selected':'' ?>>Toate categoriile</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat ?>" <?= $filterCategory===$cat ? 'selected':'' ?>><?= $cat ?></option>
        <?php endforeach; ?>
      </select>
      <select onchange="location='?priority='+this.value">
        <option value="toate" <?= $filterPriority==='toate' ? 'selected':'' ?>>Toate prioritățile</option>
        <option value="ridicată" <?= $filterPriority==='ridicată' ? 'selected':'' ?>>Ridicată</option>
        <option value="medie"    <?= $filterPriority==='medie'    ? 'selected':'' ?>>Medie</option>
        <option value="scăzută"  <?= $filterPriority==='scăzută'  ? 'selected':'' ?>>Scăzută</option>
      </select>
      <button class="add-btn" id="openModal">+ Sarcină nouă</button>
    </div>

    <p class="section-label">Sarcini active <span><?= count($activeTasks) ?> sarcini</span></p>
    <?php foreach ($activeTasks as $task): ?>
    <div class="task-card" data-id="<?= $task['id'] ?>">
      <input type="checkbox" onchange="toggleTask('<?= $task['id'] ?>', this)"/>
      <div class="task-info">
        <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
        <?php if ($task['description']): ?>
        <div class="task-desc"><?= htmlspecialchars($task['description']) ?></div>
        <?php endif; ?>
        <div class="tags">
          <span class="tag <?= categoryClass($task['category']) ?>"><?= $task['category'] ?></span>
          <span class="tag <?= priorityClass($task['priority']) ?>"><?= ucfirst($task['priority']) ?></span>
          <?php if ($task['due_date']): ?>
          <span class="tag date">📅 <?= $task['due_date'] ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="task-actions">
        <button onclick="deleteTask('<?= $task['id'] ?>')">✕</button>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($activeTasks)): ?>
    <p style="color:#565d80;font-size:13px;padding:12px 0;">Nicio sarcină activă.</p>
    <?php endif; ?>

    <p class="section-label" style="margin-top:24px;">Finalizate <span><?= count($doneTasks) ?> sarcini</span></p>
    <?php foreach ($doneTasks as $task): ?>
    <div class="task-card done" data-id="<?= $task['id'] ?>">
      <input type="checkbox" checked onchange="toggleTask('<?= $task['id'] ?>', this)"/>
      <div class="task-info">
        <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
        <div class="tags">
          <span class="tag <?= categoryClass($task['category']) ?>"><?= $task['category'] ?></span>
        </div>
      </div>
      <div class="task-actions">
        <button onclick="deleteTask('<?= $task['id'] ?>')">✕</button>
      </div>
    </div>
    <?php endforeach; ?>
  </main>
</div>

<footer>&copy; 2026 ToDo App — Micu Nicolae</footer>
<script src="js/script.js"></script>
</body>
</html>