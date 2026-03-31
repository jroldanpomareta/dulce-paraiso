<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.nombre as categoria FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if(!$p) { http_response_code(404); echo "No encontrado"; exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title><?=htmlspecialchars($p['nombre'])?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<div class="container">
  <a href="index.php" class="btn btn-link">← Volver</a>
  <div class="row">
    <div class="col-md-6">
      <?php if($p['imagen']): ?><img src="<?=UPLOAD_URL.htmlspecialchars($p['imagen'])?>" class="img-fluid"><?php endif; ?>
    </div>
    <div class="col-md-6">
      <h2><?=htmlspecialchars($p['nombre'])?></h2>
      <p><?=nl2br(htmlspecialchars($p['descripcion']))?></p>
      <p><strong><?=number_format($p['precio'],2)?> €</strong></p>
      <form method="post" action="add_to_cart.php">
        <input type="hidden" name="product_id" value="<?=$p['id']?>">
        <div class="mb-2"><input class="form-control" type="number" name="qty" value="1" min="1" max="<?=intval($p['stock'])?>"></div>
        <button class="btn btn-success">Añadir al carrito</button>
      </form>
    </div>
  </div>
</div>
</body></html>
