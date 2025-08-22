<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Eliminar evento (solo admin)
if (isset($_GET['eliminar']) && $usuario['tipo_usuario'] === 'admin') {
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id_evento = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: eventos.php");
    exit;
}

$eventos = $pdo->query("SELECT * FROM eventos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h1>Eventos</h1>
    <p><a href="../index.php">Volver</a></p>
    <?php if($usuario['tipo_usuario'] === 'admin'): ?>
        <a href="nuevo_evento.php" class="btn btn-success mb-3">Nuevo Evento</a>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Lugar</th>
                <?php if($usuario['tipo_usuario'] === 'admin'): ?><th>Acciones</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($eventos as $e): ?>
            <tr>
                <td><?php echo $e['id_evento']; ?></td>
                <td><?php echo $e['titulo']; ?></td>
                <td><?php echo $e['fecha']; ?></td>
                <td><?php echo $e['descripcion']; ?></td>
                <td><?php echo $e['lugar']; ?></td>
                <?php if($usuario['tipo_usuario'] === 'admin'): ?>
                <td>
                    <a href="editar_evento.php?id=<?php echo $e['id_evento']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eventos.php?eliminar=<?php echo $e['id_evento']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar evento?');">Eliminar</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
