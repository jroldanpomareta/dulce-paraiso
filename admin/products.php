<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if(!isAdmin()){
    header('Location: ../public/login.php');
    exit;
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit;
}

$stmt = $pdo->query("SELECT p.*, c.nombre as categoria 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.id DESC");
$productos = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<div class="container">

  <!-- Título + botón de volver -->
  <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Productos</h3>
      <a class="btn btn-sm btn-secondary" href="index.php">← Panel admin</a>
  </div>

  <!-- Botón de nuevo producto -->
  <p>
      <a class="btn btn-sm btn-primary" href="product_form.php">+ Nuevo producto</a>
  </p>

  <!-- Tabla de productos -->
  <table class="table table-striped">
    <thead>
       <tr>
         <th>ID</th>
         <th>Nombre</th>
         <th>Precio</th>
         <th>Stock</th>
         <th>Categoría</th>
         <th>Acciones</th>
       </tr>
    </thead>

    <tbody>
      <?php foreach($productos as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= number_format($p['precio'],2) ?> €</td>
        <td><?= $p['stock'] ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        
        <td>
          <a class="btn btn-sm btn-secondary" href="product_form.php?id=<?= $p['id'] ?>">Editar</a>
          <a class="btn btn-sm btn-danger" 
             href="?delete=<?= $p['id'] ?>" 
             onclick="return confirm('¿Seguro que deseas borrar este producto?');">
             Borrar
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>
</body>
</html>

