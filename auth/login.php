<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /login.php');
  exit;
}

// CSRF
if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
  $_SESSION['flash'] = 'Token CSRF inválido.';
  header('Location: /login.php');
  exit;
}

$login = trim($_POST['login'] ?? '');
$pass  = $_POST['password'] ?? '';

if ($login === '' || $pass === '') {
  $_SESSION['flash'] = 'Completa todos los campos.';
  header('Location: /login.php');
  exit;
}

require_once __DIR__ . '/../config/db.php';

// Permitir login por email o username
$sql = "SELECT id, username, email, password_hash, role, is_active
        FROM users
        WHERE email = :login OR username = :login
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password_hash'])) {
  $_SESSION['flash'] = 'Credenciales inválidas.';
  header('Location: /login.php');
  exit;
}

if ((int)$user['is_active'] === 0) {
  $_SESSION['flash'] = 'Usuario inactivo. Contacta al administrador.';
  header('Location: /login.php');
  exit;
}

// OK: guardar sesión mínima
$_SESSION['user'] = [
  'id'       => $user['id'],
  'username' => $user['username'],
  'email'    => $user['email'],
  'role'     => $user['role'],
];

unset($_SESSION['csrf']); // rotar token tras login
header('Location: /index.php'); // redirige a tu home
exit;
