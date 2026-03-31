<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Panel de Administración - Dulce Paraíso</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
      .admin-card {
          min-height: 180px;
      }
  </style>
</head>

<body class="p-4">

<div class="container">

    <h1 class="mb-4 text-center" style="color:#d63384;">Panel de Administración</h1>

    <div class="text-end mb-3">
        <a href="../public/index.php" class="btn btn-outline-secondary">Volver a la web</a>
        <a href="../public/logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
    </div>

    <!-- FILA DE 3 TARJETAS PERFECTAMENTE ALINEADAS -->
    <div class="row g-4">

        <!-- Gestionar productos -->
        <div class="col-md-4 d-flex">
            <div class="card shadow-sm admin-card w-100">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title">Productos</h4>
                    <p class="card-text flex-grow-1">Añadir, editar o eliminar productos.</p>
                    <a href="products.php" class="btn btn-primary mt-auto">Gestionar productos</a>
                </div>
            </div>
        </div>

        <!-- Gestionar categorías -->
        <div class="col-md-4 d-flex">
            <div class="card shadow-sm admin-card w-100">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title">Categorías</h4>
                    <p class="card-text flex-grow-1">Crear o modificar categorías de productos.</p>
                    <a href="categories.php" class="btn btn-primary mt-auto">Gestionar categorías</a>
                </div>
            </div>
        </div>

        <!-- Gestión de pedidos -->
        <div class="col-md-4 d-flex">
            <div class="card shadow-sm admin-card w-100">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title">Pedidos</h4>
                    <p class="card-text flex-grow-1">Consultar los pedidos realizados.</p>
                    <a href="orders.php" class="btn btn-primary mt-auto">Pedidos</a>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>

