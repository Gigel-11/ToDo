<?php
$menuItems = [
    ['label' => 'Toate sarcinile', 'href' => 'index.php', 'badge' => 0, 'class' => 'active'],
    ['label' => 'Azi', 'href' => '#', 'badge' => 0, 'badgeClass' => 'red'],
    ['label' => 'Finalizate', 'href' => '#', 'badge' => 0, 'badgeClass' => 'green'],
    ['label' => 'Priorități înalte', 'href' => '#'],
    ['label' => 'Expirate', 'href' => '#', 'badge' => 0, 'badgeClass' => 'orange'],
];

$categories = [
    ['name' => 'Muncă', 'color' => '#5ba3d4', 'badge' => 0],
    ['name' => 'Personal', 'color' => '#d96a6a', 'badge' => 0, 'badgeClass' => 'red'],
    ['name' => 'Sănătate', 'color' => '#5bc99a', 'badge' => 0, 'badgeClass' => 'green'],
    ['name' => 'Educație', 'color' => '#c8a840', 'badge' => 0],
];

$stats = [
    ['icon' => '📋', 'label' => 'Total sarcini', 'value' => 0],
    ['icon' => '⚡', 'label' => 'Active', 'value' => 0],
    ['icon' => '✅', 'label' => 'Finalizate', 'value' => 0],
    ['icon' => '⚠️', 'label' => 'Expirate', 'value' => 0],
];

$filters = [
    ['label' => 'Toate', 'class' => 'active'],
    ['label' => 'Active'],
    ['label' => 'Finalizate'],
    ['label' => 'Toate categoriile'],
    ['label' => 'Toate prioritățile'],
];

$tasks = [
    
];

$activeTasks = array_filter($tasks, fn($task) => !$task['done']);
$doneTasks = array_filter($tasks, fn($task) => $task['done']);

function renderTag(array $tag): string
{
    return sprintf('<span class="tag %s">%s</span>', $tag['class'] ?? '', $tag['text']);
}

function renderTask(array $task): string
{
    $doneClass = $task['done'] ? 'done' : '';
    $checked = $task['done'] ? 'checked' : '';
    $description = $task['desc'] ?? '';
    $actions = $task['done'] ? '<button>✕</button>' : '<button>✏</button><button>✕</button>';

    $tagsHtml = '';
    foreach ($task['tags'] as $tag) {
        $tagsHtml .= renderTag($tag);
    }

    return <<<HTML
<div class="task-card {$doneClass}">
  <input type="checkbox" {$checked} />
  <div class="task-info">
    <div class="task-title">{$task['title']}</div>
    {$description}
    <div class="tags">
      {$tagsHtml}
    </div>
  </div>
  <div class="task-actions">
    {$actions}
  </div>
</div>
HTML;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ToDo App</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header>
  <div class="logo">
    <span>✦</span> ToDo
  </div>
  <div class="header-right">
    <input type="text" placeholder="🔍 Caută sarcini..." />
    <button class="notif-btn">🔔</button>
    <button class="notif-btn">☰</button>
  </div>
</header>

<div class="wrapper">

  <nav>
    <p class="section-title">Meniu</p>
    <ul>
      <?php foreach ($menuItems as $item): ?>
        <li>
          <a href="<?= $item['href'] ?>" <?= isset($item['class']) ? 'class="' . $item['class'] . '"' : '' ?>>
            <?= $item['label'] ?>
            <?php if (isset($item['badge'])): ?>
              <span class="badge <?= $item['badgeClass'] ?? '' ?>"><?= $item['badge'] ?></span>
            <?php endif; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <p class="section-title">Categorii</p>
    <ul>
      <?php foreach ($categories as $category): ?>
        <li>
          <a href="#">
            <span class="cat-dot" style="background:<?= $category['color'] ?>"></span>
            <?= $category['name'] ?>
            <span class="badge <?= $category['badgeClass'] ?? '' ?>"><?= $category['badge'] ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="progress-box">
      <p>Progres azi</p>
      <p style="font-size:12px;color:#9098b8;margin-bottom:4px;">Sarcini &nbsp; 0 / 0</p>
      <div class="bar"><div class="fill"></div></div>
      <small>0% completat</small>
    </div>

    <div class="user">
      <div class="avatar">MN</div>
      <div>
        <div class="name">Micu Nicolae</div>
        <div class="sub">Cont personal</div>
      </div>
    </div>
  </nav>

  <main>
    <h1>Toate Sarcinile</h1>

    <div class="stats">
      <?php foreach ($stats as $stat): ?>
        <div class="stat-card">
          <div class="icon"><?= $stat['icon'] ?></div>
          <div class="info">
            <div class="label"><?= $stat['label'] ?></div>
            <div class="value"><?= $stat['value'] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="filters">
      <?php foreach ($filters as $filter): ?>
        <button <?= isset($filter['class']) ? 'class="' . $filter['class'] . '"' : '' ?>><?= $filter['label'] ?></button>
      <?php endforeach; ?>
      <button class="add-btn">+ Sarcină nouă</button>
    </div>

    <p class="section-label">Sarcini active <span><?= count($activeTasks) ?> sarcini</span></p>
    <?php foreach ($activeTasks as $task): ?>
      <?= renderTask($task) ?>
    <?php endforeach; ?>

    <p class="section-label" style="margin-top:24px;">Finalizate <span><?= count($doneTasks) ?> sarcini</span></p>
    <?php foreach ($doneTasks as $task): ?>
      <?= renderTask($task) ?>
    <?php endforeach; ?>
  </main>
</div>

<footer>
  &copy; 2026 ToDo App — Micu Nicolae
</footer>

<script src="js/script.js"></script>
</body>
</html>
