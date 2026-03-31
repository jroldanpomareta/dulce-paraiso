<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$product_id = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));
$stmt = $pdo->prepare("SELECT id,nombre,precio FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$p = $stmt->fetch();
if(!$p){ header('Location: index.php'); exit; }

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if(isset($_SESSION['cart'][$product_id])){
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}
header('Location: cart.php');
exit;
