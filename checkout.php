<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
include_once 'includes/products_logic.php';

// Calcula totales igual que en el carrito
$subtotal = 0;
$total_items = 0;
foreach ($_SESSION['cart'] as $game_id => $quantity) {
    if (isset($games[$game_id])) {
        $game = $games[$game_id];
        $subtotal += $game['price'] * $quantity;
        $total_items += $quantity;
    }
}

$total = $subtotal;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Selecciona Método de Pago</title>
    <link rel="stylesheet" href="assets/css/gamestore.css">
    <style>
    .checkout-container {
        max-width: 480px;
        margin: 3rem auto;
        background: #181a1b;
        border-radius: 14px;
        box-shadow: 0 0 32px #39ff1420;
        padding: 2.5rem 2rem;
        color: #fff;
    }
    .checkout-container h2 {
        color: #39ff14;
        font-family: 'Orbitron', sans-serif;
        text-align: center;
        margin-bottom: 2rem;
        letter-spacing: 1px;
    }
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        margin-bottom: 2rem;
    }
    .payment-method {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #111;
        border: 2px solid #39ff14;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .payment-method:hover, .payment-method.selected {
        box-shadow: 0 0 24px #39ff14;
        border-color: #2cff05;
    }
    .payment-method input[type="radio"] {
        accent-color: #39ff14;
        width: 1.2rem;
        height: 1.2rem;
    }
    .checkout-summary {
        background: #141414;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        color: #b6ffb3;
    }
    .checkout-summary span {
        float: right;
        color: #fff;
        font-weight: bold;
    }
    .btn-pay {
        width: 100%;
        padding: 1rem;
        background: #39ff14;
        color: #0a0a0a;
        border: none;
        border-radius: 10px;
        font-family: 'Orbitron', sans-serif;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 0 16px #39ff14;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    .btn-pay:hover {
        background: #2cff05;
        color: #0a0a0a;
        box-shadow: 0 0 32px #39ff14;
    }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Selecciona tu método de pago</h2>
        <form action="generate_invoice.php" method="post" id="paymentForm">
            <div class="checkout-summary">
                <div>Subtotal: <span>$<?php echo number_format($subtotal, 0, ',', '.'); ?></span></div>
                <div style="margin-top:1rem;font-size:1.1rem;">TOTAL: <span style="color:#39ff14;">$<?php echo number_format($total, 0, ',', '.'); ?></span></div>
            </div>
            <div class="payment-methods">
    <label class="payment-method">
        <input type="radio" name="metodo_pago" value="paypal" required>
        <i class="fab fa-paypal" style="font-size:1.5rem;color:#039be5;"></i>
        PayPal
        <img src="assets/img/paypal-logo.png" alt="Logo Banco" style="height: 1.5rem; margin-left: 0.5rem;">
    </label>
    <label class="payment-method">
        <input type="radio" name="metodo_pago" value="credito">
        <i class="fas fa-credit-card" style="font-size:1.5rem;color:#39ff14;"></i>
        Tarjeta de Crédito/Débito
        <img src="assets/img/banco-chile.jpg" alt="banco estado" style="height:1.5rem; margin-left:0.1rem;">
    <img src="assets/img/scotia-logo.png" alt="Google Pay" style="height:1.5rem; margin-left:0.5rem;">
     <img src="assets/img/fallabela-logo.png" alt="Google Pay" style="height:1.5rem; margin-left:0.5rem;">
    </label>
    <label class="payment-method">
        <input type="radio" name="metodo_pago" value="transferencia">
        <i class="fas fa-university" style="font-size:1.5rem;color:#fff;"></i>
        Transferencia Bancaria
        <img src="assets/img/bancoestado.png" alt="banco estado" style="height:1.5rem; margin-left:0.5rem;">
    <img src="assets/img/scotia-logo.png" alt="Google Pay" style="height:1.5rem; margin-left:0.5rem;">
     <img src="assets/img/fallabela-logo.png" alt="Google Pay" style="height:1.5rem; margin-left:0.5rem;">
    </label>
    <label class="payment-method">
        <input type="radio" name="metodo_pago" value="webpay">
        <img src="assets/img/logo-webpay.svg" alt="Webpay" style="height: 1.5rem;">
        Webpay (Transbank)
    </label>
    <label class="payment-method">
    <input type="radio" name="metodo_pago" value="applepay">
    <i class="fab fa-apple-pay" style="font-size:1.5rem;color:#fff;"></i>
    Pagos móviles
    <img src="assets/img/applepay.png" alt="Apple Pay" style="height:1.5rem; margin-left:0.5rem;">
    <img src="assets/img/googlepay.png" alt="Google Pay" style="height:1.5rem; margin-left:0.5rem;">
</label>

</div>
<button type="submit" class="btn-pay">Proceder al Pago</button>

        </form>
    </div>
    <script>
    // Resalta el método seleccionado
    document.querySelectorAll('.payment-method input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(function(label) {
                label.classList.remove('selected');
            });
            this.closest('.payment-method').classList.add('selected');
        });
    });
    </script>
</body>
</html>
