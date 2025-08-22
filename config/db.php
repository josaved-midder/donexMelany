<?php
$host = 'localhost';
$dbname = 'donex';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<?php
require_once 'db.php';

// Credenciales nuevas seguras
$usuarios = [
    ['correo' => 'admin@donex.com', 'password' => 'admin123'],
    ['correo' => 'juan@example.com', 'password' => '12345'],
    ['correo' => 'juan1@example.com', 'password' => 'loaisa123']
];

foreach ($usuarios as $u) {
    $hash = password_hash($u['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
    $stmt->execute([$hash, $u['correo']]);
}

echo "Contraseñas actualizadas y encriptadas correctamente.";
