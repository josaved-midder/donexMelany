<?php
session_start();

// si ya está logueado, envía al inicio
if (!empty($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}

// CSRF
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse</title>
  <link rel="stylesheet" href="css/registrar.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body d-flex">

        <div class="column" id="main" style="flex:1;">
          <h1>Regístrate</h1>

          <?php if ($flash): ?>
            <div class="alert alert-warning">
              <?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="auth/register.php" autocomplete="off">
            <div class="form-group">
              <label for="exampleInputName">Nombre de usuario</label>
              <input type="text" class="form-control" id="exampleInputName"
                     name="username" placeholder="Usuario" minlength="3" maxlength="50" required>
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Correo electrónico</label>
              <input type="email" class="form-control" id="exampleInputEmail1"
                     name="email" placeholder="Correo electrónico" maxlength="150" required>
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">Contraseña</label>
              <input type="password" class="form-control" id="exampleInputPassword1"
                     name="password" placeholder="Contraseña" minlength="8" required>
            </div>

            <div class="form-group">
              <label for="exampleInputPassword2">Repite la contraseña</label>
              <input type="password" class="form-control" id="exampleInputPassword2"
                     name="password2" placeholder="Repite la contraseña" minlength="8" required>
            </div>

            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <button type="submit" class="btn btn-primary">Continuar</button>
          </form>
        </div>

        <div aria-hidden="true" class="mx-3" style="display:flex; align-items:center;">
          <svg width="67px" height="578px" viewBox="0 0 67 578" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
              <path d="M11.3847656,0 C-7.44726562,36.7213542 5.14322917,126.757812 49.15625,270.109375 C70.9827986,341.199016 54.8877465,443.829224 0.87109375,578 L67,578 L67,0 L11.3847656,0 Z" fill="#F9BC35"></path>
            </g>
          </svg>
        </div>

        <div class="column" id="secondary" style="flex:1;">
          <div class="sec-content">
            <h2>¡Bienvenido!</h2>
            <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
            <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/custom.js"></script>
</body>
</html>
