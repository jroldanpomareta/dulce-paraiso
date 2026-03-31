<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    header("Location: cart.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $direccion = trim($_POST["direccion"] ?? '');

    if (!$direccion) {
        $error = "Introduce una dirección.";
    } else {
        // Guardamos la dirección en sesión bajo checkout_address
        $_SESSION["checkout_address"] = $direccion;

        // Redirigimos al selector de pago
        header("Location: payment.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dirección de envío</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container col-md-6">
  <h3>Dirección de envío</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-2">
      <label class="form-label">Dirección completa</label>
      <textarea name="direccion" class="form-control" placeholder="Dirección completa" required rows="3"><?= htmlspecialchars($_SESSION['checkout_address'] ?? '') ?></textarea>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Continuar al pago</button>

      <!-- Botón para volver al carrito -->
      <a href="cart.php" class="btn btn-outline-secondary">Volver al carrito</a>

      <!-- Botón para volver a la tienda (index) -->
      <a href="index.php" class="btn btn-link">Volver a la tienda</a>
    </div>
  </form>
</div>
</body>
</html>
