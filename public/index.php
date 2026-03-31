<?php
// public/index.php — Portada principal con fondo
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dulce Paraíso</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --overlay-color: rgba(0,0,0,0.35);
      --accent-color: #d63384;
    }
    html,body { height:100%; margin:0; }
    body { background:#fff; }

    .hero {
      min-height: 100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      position:relative;
      overflow:hidden;
      color:#fff;
    }
    .hero::before {
      content:"";
      position:absolute;
      inset:0;
      background-image:url('assets/img/portada.jpg');
      background-size:cover;
      background-position:center;
      filter:brightness(0.95);
      z-index:0;
    }
    .hero::after {
      content:"";
      position:absolute;
      inset:0;
      background:var(--overlay-color);
      z-index:1;
    }
    .hero-inner { position:relative; z-index:2; padding:48px 24px; }

    .brand-title {
      font-family:'Pacifico',Georgia,serif;
      font-size:72px;
      margin-bottom:8px;
      text-shadow:0 3px 10px rgba(0,0,0,0.45);
    }

    .top-links {
      position:absolute;
      top:14px;
      right:18px;
      z-index:3;
      display:flex;
      gap:8px;
    }

    .admin-gear {
      font-size:22px;
      color:#fff;
      background:rgba(0,0,0,0.25);
      padding:8px;
      border-radius:8px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }
  </style>
</head>

<body>
  <main class="hero">

    <div class="top-links">
      <?php if (isAdmin()): ?>
        <!-- RUTA CORREGIDA -->
        <a href="/dulce-paraiso/admin/" class="admin-gear"><i class="bi bi-gear-fill"></i></a>
      <?php endif; ?>

      <?php if (!isLoggedIn()): ?>
        <a class="btn btn-sm btn-outline-light" href="login.php">Entrar</a>
      <?php else: ?>
        <a class="btn btn-sm btn-outline-light" href="logout.php">Cerrar sesión</a>
      <?php endif; ?>
    </div>

    <div class="hero-inner container text-center">
      <h1 class="brand-title">Dulce Paraíso</h1>
      <p class="hero-sub">Tartas, pasteles y dulces artesanos. Entra y descubre nuestras delicias.</p>

      <a href="shop.php" class="btn btn-primary go-shop-btn">Ir a la tienda</a>
    </div>

  </main>
</body>
</html>
