<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Quitar un producto del carrito
if (isset($_GET['action']) && $_GET['action'] === 'remove') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = array_keys($cart);
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT id,nombre,precio,imagen FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $r['cantidad'] = $cart[$r['id']];
        $r['subtotal'] = $r['precio'] * $r['cantidad'];
        $total += $r['subtotal'];
        $items[] = $r;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Carrito - Dulce Paraíso</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .brand-title {
      font-family: 'Pacifico', Georgia, serif;
      font-size: 48px;
      color: #d63384;
      text-align: center;
      margin: 18px 0 30px;
      letter-spacing: 1px;
    }
    .product-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0; /* evita ensanchar la celda */
    }
    .product-img {
      height: 48px;
      width: 48px;
      object-fit: cover;
      border-radius: 6px;
      flex-shrink: 0; /* evita deformación */
    }
    .product-name {
      font-weight: 600;
      white-space: normal; /* permite que el texto baje de línea */
      overflow-wrap: break-word;
      flex-grow: 1;
    }
    .actions-col { width: 110px; text-align: center; }
    .cart-empty { margin-top: 20px; }
  </style>
</head>

<body class="p-4">
<div class="container">

  <div class="brand-title text-center">Dulce Paraíso</div>

  <!-- Botones navegación -->
  <div class="d-flex justify-content-start gap-2 mb-3">
      <a href="shop.php" class="btn btn-secondary">← Volver a la tienda</a>
      <a href="index.php" class="btn btn-outline-secondary">Portada</a>
  </div>

  <h3>Carrito</h3>

  <?php if (empty($items)): ?>
      <div class="cart-empty">
          <p>El carrito está vacío.</p>
      </div>

  <?php else: ?>

      <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th class="actions-col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td>
              <div class="product-wrapper">
                <?php if (!empty($it['imagen'])): ?>
                  <img class="product-img" src="<?= UPLOAD_URL . htmlspecialchars($it['imagen']) ?>" alt="">
                <?php endif; ?>
                <span class="product-name"><?= htmlspecialchars($it['nombre']) ?></span>
              </div>
            </td>
            <td><?= (int)$it['cantidad'] ?></td>
            <td><?= number_format($it['precio'],2) ?> €</td>
            <td><?= number_format($it['subtotal'],2) ?> €</td>
            <td class="actions-col">
              <a href="cart.php?action=remove&id=<?= (int)$it['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Quitar este producto del carrito?');">Quitar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      </div>

      <div class="mt-3">
        <strong>Total: <?= number_format($total,2) ?> €</strong>
        <a class="btn btn-success ms-3" href="checkout.php">Finalizar compra</a>
      </div>

  <?php endif; ?>

</div>
</body>
</html>

