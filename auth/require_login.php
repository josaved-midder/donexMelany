<?php
// Incluir al inicio de cada página privada
session_start();
if (empty($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}
