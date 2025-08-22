<?php
session_start();
// Generar token CSRF si no existe
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
// Tomar y limpiar mensaje flash (errores)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tus CSS existentes -->
</head>
<body>
  <?php if ($flash): ?>
    <div class="alert"><?=$flash?></div>
  <?php endif; ?>

  <form method="POST" action="/auth/login.php" autocomplete="off">
    <label>Correo o usuario</label>
    <input type="text" name="login" required />

    <label>Contraseña</label>
    <input type="password" name="password" required />

    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
    <button type="submit">Entrar</button>
  </form>
</body>
</html>
