<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Entrar - Dulce Paraíso</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .brand-title {
        font-family: 'Pacifico', cursive;
        font-size: 42px;
        color: #d63384;
        text-align: center;
        margin-bottom: 5px;
    }
    .nav-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
  </style>

  <!-- Fuente bonita -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body class="p-4">

<div class="container col-md-4">

  <!-- Título -->
  <div class="brand-title">Dulce Paraíso</div>

  <!-- Botones de navegación -->
  <div class="nav-buttons">
      <a class="btn btn-secondary" href="index.php">← Portada</a>
      <a class="btn btn-outline-primary" href="shop.php">Ir a la tienda</a>
  </div>

  <h3 class="text-center mb-3">Iniciar sesión</h3>

  <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-2">
      <input name="email" type="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="mb-2">
      <input name="password" type="password" class="form-control" placeholder="Contraseña" required>
    </div>

    <button class="btn btn-primary w-100 mt-2">Entrar</button>

    <div class="text-center mt-3">
      <a href="register.php" class="btn btn-link">Crear cuenta nueva</a>
    </div>
  </form>
</div>

</body>
</html>
