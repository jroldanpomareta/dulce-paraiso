<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

// Obtener pedidos (sin columna status)
$stmt = $pdo->query("
    SELECT 
        o.id,
        o.user_id,
        o.total,
        o.direccion,
        o.created_at,
        u.nombre AS usuario
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
$pedidos = $stmt->fetchAll();

// Obtener productos de cada pedido
function obtenerItemsPedido($orderId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT 
            oi.cantidad,
            oi.precio_unit,
            p.nombre
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Pedidos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<div class="container">

    <!-- Título + botón vuelta -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Pedidos</h3>
        <a class="btn btn-sm btn-secondary" href="index.php">← Panel admin</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="alert alert-info">No hay pedidos registrados todavía.</div>
    <?php else: ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Dirección</th>
                    <th>Fecha</th>
                    <th>Detalles</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['usuario'] ?? 'Usuario eliminado') ?></td>
                    <td><?= number_format($p['total'], 2) ?> €</td>
                    <td><?= htmlspecialchars($p['direccion']) ?></td>
                    <td><?= $p['created_at'] ?></td>
                    <td>
                        <!-- Botón para mostrar detalles -->
                        <button class="btn btn-sm btn-primary" 
                                data-bs-toggle="collapse"
                                data-bs-target="#pedido<?= $p['id'] ?>">
                            Ver
                        </button>
                    </td>
                </tr>

                <!-- Productos dentro del pedido -->
                <tr class="collapse bg-light" id="pedido<?= $p['id'] ?>">
                    <td colspan="6">
                        <strong>Productos:</strong><br>
                        <ul>
                        <?php 
                            $items = obtenerItemsPedido($p['id'], $pdo);
                            foreach ($items as $it): 
                        ?>
                            <li>
                                <?= htmlspecialchars($it['nombre']) ?> — 
                                <?= $it['cantidad'] ?> ud. × 
                                <?= number_format($it['precio_unit'], 2) ?> €
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

