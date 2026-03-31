<?php
require_once __DIR__ . '/../includes/config.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contacto - Dulce Paraíso</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente bonita del título -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <style>
    .brand-title {
      font-family: 'Pacifico', Georgia, serif;
      font-size: 48px;
      color: #d63384;
      text-align: center;
      margin: 20px 0 30px;
    }
  </style>
</head>

<body class="p-4">
<div class="container col-md-6">

  <div class="brand-title">Dulce Paraíso</div>

  <h3 class="mb-4">Contacto</h3>

  <p><strong>Dirección:</strong><br>
     Calle de las Tartas 6</p>

  <p><strong>Teléfono:</strong><br>
     666 666 666</p>

  <p><strong>Email:</strong><br>
     dulceparaiso@tartas.es</p>

  <hr>

  <div class="d-flex gap-2 mt-3">
    <a href="shop.php" class="btn btn-primary">← Volver a la tienda</a>
    <a href="index.php" class="btn btn-secondary">← Volver a la portada</a>
  </div>

</div>
</body>
</html>
