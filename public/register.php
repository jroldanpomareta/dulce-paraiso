<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre     = trim($_POST['nombre'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $pass       = $_POST['password'] ?? '';
    $direccion  = trim($_POST['direccion'] ?? '');
    $localidad  = trim($_POST['localidad'] ?? '');
    $privacidad = isset($_POST['privacidad']);

    // Validaciones
    if (!$privacidad) {
        $error = "Debes aceptar la política de privacidad.";
    } elseif (!$nombre || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
        $error = "Datos inválidos. Revisa la información introducida.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (nombre,email,password,direccion,localidad,role) 
                 VALUES (?,?,?,?,?, 'user')"
            );

            $stmt->execute([$nombre, $email, $hash, $direccion, $localidad]);

            // Autologin tras registro
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = 'user';

            header("Location: index.php");
            exit;

        } catch (PDOException $e) {
            $error = "El email ya está registrado.";
        }
    }
}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Crear Cuenta - Dulce Paraíso</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Título estilo Dulce Paraíso -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <style>
    .brand-title {
      font-family: 'Pacifico', Georgia, serif;
      font-size: 42px;
      color: #d63384;
      text-align: center;
      margin: 20px 0;
    }
  </style>
</head>

<body class="p-4">
<div class="container col-md-5">

  <div class="brand-title">Dulce Paraíso</div>

  <h3 class="text-center mb-3">Crear cuenta</h3>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">

    <div class="mb-2">
      <input name="nombre" class="form-control" placeholder="Nombre completo" required>
    </div>

    <div class="mb-2">
      <input name="email" type="email" class="form-control" placeholder="Email" required>
    </div>

    <div class="mb-2">
      <input name="password" type="password" class="form-control" placeholder="Contraseña (mínimo 6 caracteres)" required>
    </div>

    <div class="mb-2">
      <input name="direccion" class="form-control" placeholder="Dirección" required>
    </div>

    <div class="mb-2">
      <input name="localidad" class="form-control" placeholder="Localidad" required>
    </div>

    <div class="form-check my-3">
      <input class="form-check-input" type="checkbox" name="privacidad" id="privacidad">
      <label class="form-check-label" for="privacidad">
        Acepto la <a href="policy.php" target="_blank">política de privacidad</a>
      </label>
    </div>

    <!-- BOTÓN DE REGISTRO -->
    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            ✔ Crear cuenta
        </button>
    </div>

    <!-- Enlaces -->
    <div class="text-center mt-3">
        <a href="login.php" class="btn btn-link">Ya tengo cuenta</a>
        <br>
        <a href="index.php" class="btn btn-link">Volver a la portada</a>
        <br>
        <a href="shop.php" class="btn btn-link">Ir a la tienda</a>
    </div>

  </form>

</div>
</body>
</html>
