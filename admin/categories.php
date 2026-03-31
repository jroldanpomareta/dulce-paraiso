<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if(!isAdmin()){
    header('Location: ../public/login.php');
    exit;
}

// Crear categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    if ($nombre !== "") {
        $stmt = $pdo->prepare("INSERT INTO categories (nombre) VALUES (?)");
        $stmt->execute([$nombre]);
    }
    header("Location: categories.php");
    exit;
}

// Eliminar categoría
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: categories.php");
    exit;
}

// Obtener categorías existentes
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categorias = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Categorías</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<div class="container">

  <!-- Título + botón de volver -->
  <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Categorías</h3>
      <a class="btn btn-sm btn-secondary" href="index.php">← Panel admin</a>
  </div>

  <!-- Formulario para crear categoría -->
  <div class="card mb-4">
      <div class="card-body">
          <form method="post" class="d-flex gap-2">
              <input type="text" name="nombre" class="form-control" placeholder="Nueva categoría" required>
              <button class="btn btn-primary">Añadir</button>
          </form>
      </div>
  </div>

  <!-- Tabla -->
  <table class="table table-striped">
      <thead>
          <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Acciones</th>
          </tr>
      </thead>

      <tbody>
          <?php foreach($categorias as $c): ?>
              <tr>
                  <td><?= $c['id'] ?></td>
                  <td><?= htmlspecialchars($c['nombre']) ?></td>
                  <td>
                      <a href="?delete=<?= $c['id'] ?>" 
                         class="btn btn-sm btn-danger"
                         onclick="return confirm('¿Eliminar esta categoría?');">
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

