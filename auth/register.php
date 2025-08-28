<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../register.php');
  exit;
}

// CSRF
if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
  $_SESSION['flash'] = 'Token CSRF inválido. Intenta de nuevo.';
  header('Location: ../register.php');
  exit;
}

$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password']  ?? '';
$password2 = $_POST['password2'] ?? '';

// Validaciones
if ($username === '' || $email === '' || $password === '' || $password2 === '') {
  $_SESSION['flash'] = 'Completa todos los campos.';
  header('Location: ../register.php');
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['flash'] = 'Correo inválido.';
  header('Location: ../register.php');
  exit;
}
if (strlen($username) < 3 || strlen($username) > 50) {
  $_SESSION['flash'] = 'El usuario debe tener entre 3 y 50 caracteres.';
  header('Location: ../register.php');
  exit;
}
if ($password !== $password2) {
  $_SESSION['flash'] = 'Las contraseñas no coinciden.';
  header('Location: ../register.php');
  exit;
}
if (strlen($password) < 8) {
  $_SESSION['flash'] = 'La contraseña debe tener mínimo 8 caracteres.';
  header('Location: ../register.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

try {
  // duplicado username o email
  $check = $pdo->prepare("SELECT 1 FROM users WHERE username = :u OR email = :e LIMIT 1");
  $check->execute(['u' => $username, 'e' => $email]);
  if ($check->fetch()) {
    $_SESSION['flash'] = 'Usuario o correo ya registrado.';
    header('Location: ../register.php');
    exit;
  }

  // insertar
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $ins = $pdo->prepare(
    "INSERT INTO users (username, email, password_hash, role, is_active)
     VALUES (:u, :e, :p, 'user', 1)"
  );
  $ins->execute(['u' => $username, 'e' => $email, 'p' => $hash]);

  // mensaje y redirección al login
  $_SESSION['flash'] = 'Cuenta creada. Ahora inicia sesión.';
  unset($_SESSION['csrf']);
  header('Location: ../login.php');
  exit;

} catch (Throwable $e) {
  // error_log($e->getMessage());
  $_SESSION['flash'] = 'Error creando la cuenta.';
  header('Location: ../register.php');
  exit;
}
