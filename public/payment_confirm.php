<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Método no permitido']);
    exit;
}

// Usuario autenticado
if (!isLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'No autenticado']);
    exit;
}

// Session: dirección y carrito
$direccion = $_SESSION['checkout_address'] ?? null;
$cart = $_SESSION['cart'] ?? [];
if (!$direccion || !$cart) {
    echo json_encode(['status'=>'error','message'=>'Datos de compra incompletos']);
    exit;
}

// Recogemos método y aceptación
$metodo = $_POST['pago'] ?? '';
$cond = isset($_POST['condiciones']) ? true : false;
$allowed = ['VISA','Mastercard','PayPal','Bizum'];

if (!in_array($metodo, $allowed, true)) {
    echo json_encode(['status'=>'error','message'=>'Método de pago inválido']);
    exit;
}
if (!$cond) {
    echo json_encode(['status'=>'error','message'=>'Debe aceptar las condiciones']);
    exit;
}

// Proceso: crear pedido en transacción
try {
    $pdo->beginTransaction();

    $ids = array_keys($cart);
    $in = str_repeat('?,', count($ids)-1) . '?';
    $stmt = $pdo->prepare("SELECT id,precio,stock FROM products WHERE id IN ($in) FOR UPDATE");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();

    $byId = []; $total = 0;
    foreach ($rows as $r) $byId[$r['id']] = $r;

    foreach ($cart as $pid => $qty) {
        if (!isset($byId[$pid])) throw new Exception("Producto no encontrado: $pid");
        if ($byId[$pid]['stock'] < $qty) throw new Exception("Stock insuficiente para el producto $pid");
        $total += $byId[$pid]['precio'] * $qty;
    }

    $stmt = $pdo->prepare("INSERT INTO orders (user_id,total,direccion,metodo_pago,created_at) VALUES (?,?,?,?,NOW())");
    $stmt->execute([$_SESSION['user_id'],$total,$direccion,$metodo]);
    $orderId = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id,product_id,cantidad,precio_unit) VALUES (?,?,?,?)");
    $stmtUpd = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($cart as $pid => $qty) {
        $precio = $byId[$pid]['precio'];
        $stmtItem->execute([$orderId,$pid,$qty,$precio]);
        $stmtUpd->execute([$qty,$pid]);
    }

    $pdo->commit();

    // limpiar sesión checkout
    unset($_SESSION['cart'], $_SESSION['checkout_address']);

    echo json_encode(['status'=>'ok']);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
}

