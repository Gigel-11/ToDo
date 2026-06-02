<?php
require_once 'php/auth.php';
require_once 'php/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Completează toate câmpurile.';
    } else {
        $user = loginUser($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email sau parolă incorectă.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Autentificare – ToDo App</title>
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
    .msg-error { background: #4a1e1e; color: #d96a6a; padding: 8px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
  </style>
</head>
<body>
<div class="auth-box">
  <h2>✦ Autentificare</h2>

  <?php if ($error): ?>
  <div class="msg-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <label>Adresă email</label>
    <input type="email" name="email" placeholder="email@exemplu.com"
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>

    <label>Parolă</label>
    <input type="password" name="password" placeholder="Parola ta" required/>

    <button type="submit">Intră în cont</button>
  </form>

  <div class="link">
    Nu ai cont? <a href="register.php">Înregistrează-te</a>
  </div>
</div>
</body>
</html>