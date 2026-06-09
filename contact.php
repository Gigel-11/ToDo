<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Completează toate câmpurile.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresa de email nu este validă.';
    } else {
      $success = 'Mesajul a fost trimis. Îți vom răspunde curând.';
      $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact — ToDo App</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    .contact-box { max-width:760px; margin:48px auto; background:#141622; border:1px solid #2e3250; padding:24px; border-radius:10px; }
    .contact-box h2 { color:#e0e4f8; margin-bottom:12px; }
    .contact-box label { display:block; color:#9098b8; font-size:13px; margin-top:10px; }
    .contact-box input, .contact-box textarea { width:100%; padding:10px; border-radius:6px; border:1px solid #2e3250; background:#1e2136; color:#c8cde0; }
    .contact-box button { margin-top:12px; padding:10px 16px; background:#6c8ef5; border:none; color:white; border-radius:6px; cursor:pointer; }
    .msg-success { background:#1e4a3a; color:#5bc99a; padding:8px 12px; border-radius:6px; margin-bottom:14px; }
    .msg-error { background:#4a1e1e; color:#d96a6a; padding:8px 12px; border-radius:6px; margin-bottom:14px; }
  </style>
</head>
<body>
  <header>
    <a href="index.php" class="nav-logo">
      <span class="mark">✦</span>
      ToDo
    </a>
    <nav class="nav-actions">
      <a href="login.php" class="btn btn-ghost">Autentificare</a>
      <a href="register.php" class="btn btn-primary">Încearcă gratuit</a>
    </nav>
  </header>

  <main>
    <div class="contact-box">
      <h2>Contact</h2>
      <p style="color:#9098b8;">Ai nevoie de asistență sau vrei informații despre aplicație? Completează formularul de mai jos sau folosește datele noastre de contact.</p>

      <?php if ($error): ?>
        <div class="msg-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="msg-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="contact.php">
        <label>Nume</label>
        <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required/>

        <label>Adresă email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>

        <label>Mesaj</label>
        <textarea name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit">Trimite mesaj</button>
      </form>

      <div style="margin-top:16px;color:#565d80;font-size:14px;display:flex;gap:20px;flex-wrap:wrap;">
        <div>
          <strong>Email</strong>
          <div><a style="color:#6c8ef5;">ToDo@gmail.com</a></div>
        </div>
        <div>
          <strong>Telefon</strong>
          <div><a style="color:#6c8ef5;">068-274-193</a></div>
        </div>
        <div>
          <strong>Program</strong>
          <div><a style="color:#6c8ef5;">Luni–Vineri, 09:00–17:00</div>
        </div>
      </div>
    </div>
  </main>

  <footer style="margin-top:60px;text-align:center;color:#565d80;padding:24px;">&copy; 2026 ToDo App</footer>
</body>
</html>
