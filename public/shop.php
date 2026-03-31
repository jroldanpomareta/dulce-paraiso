<?php
// public/shop.php — Tienda pública (no requiere login para ver)
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// obtener productos (compatibilidad si no existe created_at)
try {
    $stmt = $pdo->query("SELECT p.*, c.nombre as categoria FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $stmt = $pdo->query("SELECT p.*, c.nombre as categoria FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
    $productos = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dulce Paraíso — Tienda</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">

  <style>
    .brand-title {
      font-family: 'Pacifico', Georgia, serif;
      font-size: 48px;
      color: #d63384;
      margin: 4px 0 0;
      letter-spacing: 1px;
      display:inline-block;
      line-height:1;
    }
    .navbar-brand { padding: .25rem 0; }
    @media (max-width: 576px) { .brand-title{ font-size:36px; } }
    .card-img-top { object-fit: cover; height: 200px; }
    .admin-gear { font-size:22px; color:#222; width:40px; height:40px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; }
    .admin-gear:hover { background: rgba(0,0,0,0.04); }
  </style>
</head>
<body>
<nav class="navbar navbar-expand bg-light mb-4">
  <div class="container d-flex align-items-center justify-content-between">
    <a class="navbar-brand" href="index.php">
      <span class="brand-title">Dulce Paraíso</span>
    </a>

    <div class="d-flex align-items-center gap-2">
      <a class="btn btn-link" href="contact.php">Contacto</a>
      <a class="btn btn-outline-primary" href="cart.php">Carrito</a>

      <?php if (isAdmin()): ?>
        <!-- RUTA CORREGIDA -->
        <a href="/dulce-paraiso/admin/" class="admin-gear" title="Panel de administración"><i class="bi bi-gear-fill"></i></a>
      <?php endif; ?>

      <?php if(!isLoggedIn()): ?>
        <a class="btn btn-link" href="login.php">Entrar</a>
      <?php else: ?>
        <a class="btn btn-link" href="logout.php">Cerrar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container">
  <?php if (empty($productos)): ?>
    <div class="alert alert-info">No hay productos disponibles por el momento.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach($productos as $p): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if(!empty($p['imagen'])): ?>
              <img src="<?= UPLOAD_URL . htmlspecialchars($p['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
              <?php if(!empty($p['categoria'])): ?>
                <small class="text-muted"><?= htmlspecialchars($p['categoria']) ?></small>
              <?php endif; ?>
              <p class="card-text"><?= htmlspecialchars(mb_substr($p['descripcion'],0,100)) ?><?= (mb_strlen($p['descripcion'])>100)?'...':'' ?></p>
              <p class="mt-auto"><strong><?= number_format($p['precio'],2) ?> €</strong></p>
              <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-primary">Ver</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
