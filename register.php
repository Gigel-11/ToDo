<?php
require_once 'php/auth.php';
require_once 'php/functions.php';

if (isLoggedIn()) {
  header('Location: todo.php');
  exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Toate câmpurile sunt obligatorii.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresa de email nu este validă.';
    } elseif (strlen($password) < 6) {
        $error = 'Parola trebuie să aibă cel puțin 6 caractere.';
    } elseif ($password !== $confirm) {
        $error = 'Parolele nu coincid.';
    } else {
        if (registerUser($name, $email, $password)) {
          header('Location: login.php?registered=1');
          exit;
        } else {
            $error = 'Această adresă de email este deja înregistrată.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Înregistrare – ToDo App</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .auth-box {
      background: #1e2136; border: 1px solid #2e3250; border-radius: 10px;
      padding: 36px 40px; width: 360px;
    }
    .auth-box h2 { color: #e0e4f8; margin-bottom: 24px; font-size: 20px; text-align: center; }
    .auth-box label { display: block; font-size: 12px; color: #9098b8; margin-bottom: 4px; }
    .auth-box input {
      width: 100%; padding: 9px 12px; margin-bottom: 16px; border-radius: 6px;
      border: 1px solid #2e3250; background: #141622; color: #c8cde0; font-size: 13px;
    }
    .auth-box button {
      width: 100%; padding: 10px; background: #6c8ef5; border: none; border-radius: 6px;
      color: white; font-size: 14px; font-weight: bold; cursor: pointer;
    }
    .auth-box button:hover { background: #5a7de0; }
    .auth-box .link { text-align: center; margin-top: 14px; font-size: 13px; color: #9098b8; }
    .auth-box .link a { color: #6c8ef5; text-decoration: none; }
    .msg-error   { background: #4a1e1e; color: #d96a6a; padding: 8px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
    .msg-success { background: #1e4a3a; color: #5bc99a; padding: 8px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
  </style>
</head>
<body>
<div class="auth-box">
  <h2>✦ Creare cont</h2>

  <?php if ($error):   ?><div class="msg-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="msg-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <?php if (!$success): ?>
  <form method="POST" action="register.php">
    <label>Nume complet</label>
    <input type="text" name="name" placeholder="Ex: Micu Nicolae" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required/>

    <label>Adresă email</label>
    <input type="email" name="email" placeholder="email@exemplu.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>

    <label>Parolă</label>
    <input type="password" name="password" placeholder="Minim 6 caractere" required/>

    <label>Confirmă parola</label>
    <input type="password" name="confirm" placeholder="Repetă parola" required/>

    <button type="submit">Creează cont</button>
  </form>
  <?php endif; ?>

  <div class="link">
    Ai deja cont? <a href="login.php">Autentifică-te</a>
  </div>
</div>
</body>
</html>