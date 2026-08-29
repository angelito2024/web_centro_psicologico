<?php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login_locked_out()) {
        $error = 'Demasiados intentos fallidos. Espera unos minutos e inténtalo de nuevo.';
    } elseif (!csrf_check($_POST['csrf_token'] ?? null)) {
        $error = 'La sesión del formulario expiró, recarga la página e intenta de nuevo.';
    } else {
        $config = admin_config();
        $hash = $config['password_hash'] ?? '';
        $password = (string) ($_POST['password'] ?? '');
        if ($hash !== '' && password_verify($password, $hash)) {
            register_successful_login();
            header('Location: index.php');
            exit;
        }
        register_failed_login();
        $error = 'Contraseña incorrecta.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresar | Blog CP Magusa</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-login-shell">
    <div class="admin-login-card">
      <h1 style="margin-bottom:6px;">Panel del Blog</h1>
      <p class="help-text" style="margin-bottom:24px;">Centro Psicológico Magusa Arcoiris</p>
      <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
      </form>
    </div>
  </div>
</body>
</html>
