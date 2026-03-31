<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Si no hay dirección o carrito volvemos al checkout
if (empty($_SESSION['checkout_address']) || empty($_SESSION['cart'])) {
    header("Location: checkout.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Método de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>.title{font-size:32px;font-weight:bold;color:#d63384;text-align:center;margin-bottom:20px;}</style>
</head>
<body class="p-4">
<div class="container col-md-6">
    <div class="title">Método de pago</div>

    <form id="paymentForm">
        <div class="mb-3">
            <label class="form-label">Selecciona método de pago</label>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" id="pagoVisa" value="VISA" required>
                <label class="form-check-label" for="pagoVisa">VISA</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" id="pagoMaster" value="Mastercard" required>
                <label class="form-check-label" for="pagoMaster">Mastercard</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" id="pagoPaypal" value="PayPal" required>
                <label class="form-check-label" for="pagoPaypal">PayPal</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" id="pagoBizum" value="Bizum" required>
                <label class="form-check-label" for="pagoBizum">Bizum</label>
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="condiciones" name="condiciones" required>
            <label class="form-check-label" for="condiciones">
                Acepto las <a href="condiciones.php" target="_blank">condiciones de compra</a>.
            </label>
        </div>

        <button type="submit" class="btn btn-success w-100">Confirmar pago</button>
        <a href="checkout.php" class="btn btn-link mt-2 d-block text-center">Volver atrás</a>
    </form>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastPago" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Pago realizado correctamente 🎉</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const fd = new FormData(e.target);
    // fetch hacia payment_confirm.php (POST)
    try {
        const resp = await fetch('payment_confirm.php', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        });
        const json = await resp.json();

        if (json && (json.status === 'ok' || json.success === true)) {
            const toastEl = document.getElementById('toastPago');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            // redirigir tras mostrar toast
            setTimeout(() => { window.location.href = 'index.php?msg=pedido_ok'; }, 1800);
        } else {
            // Mostrar mensaje de error devuelto por el servidor
            alert('Error: ' + (json.message || json.msg || 'No se pudo completar el pago.'));
        }
    } catch (err) {
        console.error(err);
        alert('Error de comunicación con el servidor.');
    }
});
</script>
</body>
</html>


