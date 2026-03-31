<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

if(!isAdmin()){
    header('Location: ../public/login.php');
    exit;
}


$id = (int)($_GET['id'] ?? 0);
$edit = false;
if($id){
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $prod = $stmt->fetch();
    $edit = (bool)$prod;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $category_id = $_POST['category_id'] ?: null;

    $imagenNombre = $prod['imagen'] ?? null;
    if(!empty($_FILES['imagen']['name'])){
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagenNombre = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], UPLOAD_DIR . $imagenNombre);
    }

    if($edit){
        $stmt = $pdo->prepare("UPDATE products SET nombre=?, descripcion=?, precio=?, stock=?, imagen=?, category_id=? WHERE id=?");
        $stmt->execute([$nombre,$descripcion,$precio,$stock,$imagenNombre,$category_id,$id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (nombre,descripcion,precio,stock,imagen,category_id) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nombre,$descripcion,$precio,$stock,$imagenNombre,$category_id]);
    }
    header('Location: products.php'); exit;
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY nombre")->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<div class="container">
  <h3><?= $edit ? 'Editar' : 'Nuevo' ?> producto</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-2"><input name="nombre" class="form-control" placeholder="Nombre" value="<?=htmlspecialchars($prod['nombre'] ?? '')?>" required></div>
    <div class="mb-2"><textarea name="descripcion" class="form-control" placeholder="Descripción"><?=htmlspecialchars($prod['descripcion'] ?? '')?></textarea></div>
    <div class="mb-2"><input name="precio" type="number" step="0.01" class="form-control" placeholder="Precio" value="<?=htmlspecialchars($prod['precio'] ?? '0.00')?>" required></div>
    <div class="mb-2"><input name="stock" type="number" class="form-control" placeholder="Stock" value="<?=htmlspecialchars($prod['stock'] ?? '0')?>"></div>
    <div class="mb-2">
      <select name="category_id" class="form-select">
        <option value="">-- Sin categoría --</option>
        <?php foreach($cats as $c): ?>
          <option value="<?=$c['id']?>" <?= (isset($prod['category_id']) && $prod['category_id']==$c['id']) ? 'selected' : '' ?>><?=htmlspecialchars($c['nombre'])?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-2"><input name="imagen" type="file" class="form-control"></div>
    <button class="btn btn-primary">Guardar</button>
    <a class="btn btn-link" href="products.php">Cancelar</a>
  </form>
</div>
</body></html>
