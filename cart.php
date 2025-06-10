<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include_once 'includes/products_logic.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();

// Procesar acciones del carrito (igual que tu lógica)
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : 0;
    switch ($action) {
        case 'update_quantity':
            $quantity = intval($_POST['quantity']);
            if ($quantity > 0 && isset($_SESSION['cart'][$game_id])) {
                $_SESSION['cart'][$game_id] = $quantity;
                $message = "Cantidad actualizada";
                $message_type = "success";
            } elseif ($quantity <= 0) {
                unset($_SESSION['cart'][$game_id]);
                $message = "Producto eliminado del carrito";
                $message_type = "info";
            }
            break;
        case 'remove_item':
            if (isset($_SESSION['cart'][$game_id])) {
                unset($_SESSION['cart'][$game_id]);
                $message = "Producto eliminado del carrito";
                $message_type = "info";
            }
            break;
        case 'clear_cart':
            $_SESSION['cart'] = array();
            $message = "Carrito vaciado";
            $message_type = "info";
            break;
    }
}

// Calcular totales
$cart_items = array();
$subtotal = 0;
$total_items = 0;
foreach ($_SESSION['cart'] as $game_id => $quantity) {
    if (isset($games[$game_id])) {
        $game = $games[$game_id];
        $item_price = $game['price'];
        $item_total = $item_price * $quantity;
        $subtotal += $item_total;
        $total_items += $quantity;
        $cart_items[] = array(
            'id' => $game_id,
            'name' => $game['name'],
            'price' => $game['price'],
            'original_price' => $game['original_price'] ?? null,
            'image' => $game['image'],
            'platforms' => $game['platforms'] ?? [],
            'rating' => $game['rating'] ?? null,
            'quantity' => $quantity,
            'total' => $item_total
        );
    }
}

$total = $subtotal;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras | PixBuy</title>
    <link rel="stylesheet" href="assets/css/gamestore.css">
    <style>
    :root {
        --bg-main: #0a0a0a;
        --bg-card: #181a1b;
        --neon-green: #39ff14;
        --neon-green-glow: 0 0 12px #39ff14, 0 0 24px #39ff14;
        --white: #fff;
        --gray: #b6b6b6;
        --shadow: 0 4px 32px #39ff1420;
        --radius: 14px;
        --transition: all 0.2s cubic-bezier(.4,2,.6,1);
    }
    body {
        background: var(--bg-main);
        color: var(--white);
        font-family: 'Roboto', Arial, sans-serif;
        margin: 0;
        min-height: 100vh;
    }
    .navbar {
        background: #141414;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
        box-shadow: var(--shadow);
        border-bottom: 2px solid var(--neon-green);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .navbar h1 {
        font-family: 'Orbitron', sans-serif;
        color: var(--neon-green);
        font-size: 2rem;
        text-shadow: var(--neon-green-glow);
        margin: 0;
    }
    .navbar a {
        color: var(--white);
        background: #222;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        margin-left: 1rem;
        transition: var(--transition);
    }
    .navbar a:hover {
        background: var(--neon-green);
        color: #0a0a0a;
        text-shadow: var(--neon-green-glow);
    }
    .cart-container {
        max-width: 1020px;
        margin: 2rem auto 3rem auto;
        padding: 0 1rem;
    }
    .cart-empty {
        text-align: center;
        padding: 3rem 1rem;
        background: var(--bg-card);
        border-radius: 14px;
        margin: 2rem 0;
        color: var(--gray);
        box-shadow: var(--shadow);
    }
    .cart-empty h2 {
        color: var(--neon-green);
        text-shadow: var(--neon-green-glow);
        margin-bottom: 0.5rem;
    }
    .cart-item {
        display: grid;
        grid-template-columns: 80px 1fr 120px 120px 120px 80px;
        gap: 1rem;
        align-items: center;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #222b44;
        background: var(--bg-card);
        margin-bottom: 0.5rem;
        border-radius: 10px;
        color: var(--white);
        box-shadow: 0 2px 4px #39ff1433;
    }
    .cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: #151825;
        border: 1px solid #2a3150;
    }
    .item-info h4 {
        margin: 0 0 0.3rem 0;
        color: var(--neon-green);
        font-weight: 700;
        font-size: 1.08rem;
        text-shadow: var(--neon-green-glow);
    }
    .item-platforms {
        font-size: 0.95rem;
        color: #b6ffb3;
        margin-bottom: 0.2rem;
    }
    .item-rating {
        font-size: 0.9rem;
        color: #f7d325;
        font-weight: bold;
    }
    .item-price, .item-total {
        font-weight: bold;
        font-size: 1.12rem;
    }
    .item-price {
        color: var(--neon-green);
    }
    .item-original-price {
        color: #8bb7ff;
        text-decoration: line-through;
        font-size: 0.95rem;
        margin-left: 6px;
    }
    .item-total {
        color: #2fff8c;
    }
    .quantity-input {
        width: 60px;
        padding: 0.5rem;
        border: 1.5px solid var(--neon-green);
        border-radius: 8px;
        background: #181c2f;
        color: var(--neon-green);
        text-align: center;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 0 8px #39ff1422;
        transition: var(--transition);
    }
    .quantity-input:focus {
        outline: none;
        border-color: #39ff14;
        box-shadow: 0 0 15px #2cff05;
    }
    .remove-btn {
        background: #ff4060;
        color: white;
        border: none;
        padding: 0.5rem 0.7rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.2s;
        box-shadow: 0 0 10px #ff406055;
    }
    .remove-btn:hover {
        background: #c82333;
    }
    .cart-summary {
        background: var(--bg-card);
        padding: 2rem 1rem 1.5rem 1rem;
        border-radius: 14px;
        box-shadow: var(--shadow);
        margin: 2rem 0;
        color: var(--white);
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
    }
    .cart-summary h3 {
        color: var(--neon-green);
        text-shadow: var(--neon-green-glow);
        margin-bottom: 1.2rem;
        text-align: center;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #222b44;
        font-size: 1.08rem;
    }
    .summary-total {
        font-size: 1.3rem;
        font-weight: bold;
        color: #2fff8c;
        border-top: 2px solid #2fff8c;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    .cart-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin: 2rem 0 0 0;
        flex-wrap: wrap;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        transition: var(--transition);
        font-size: 1.08rem;
        margin-bottom: 0.5rem;
    }
    .btn-primary {
        background: var(--neon-green);
        color: #0a0a0a;
        box-shadow: 0 0 16px #39ff14;
    }
    .btn-primary:hover {
        background: #1edc6b;
        color: #0a0a0a;
    }
    .btn-danger {
        background: #ff4060;
        color: white;
        box-shadow: 0 0 16px #ff406044;
    }
    .btn-danger:hover {
        background: #c82333;
    }
    .btn-success {
        background: #181c2f;
        color: var(--neon-green);
        border: 2px solid var(--neon-green);
        box-shadow: 0 0 12px #39ff14;
    }
    .btn-success:hover {
        background: var(--neon-green);
        color: #0a0a0a;
    }
    .message {
        padding: 1rem;
        margin: 1rem 0;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        letter-spacing: 0.5px;
        font-size: 1.08rem;
        box-shadow: 0 0 16px #39ff1455;
    }
    .message.success { background: #1edc6b22; color: #2fff8c; border: 1.5px solid #2fff8c;}
    .message.info { background: #406aff22; color: #406aff; border: 1.5px solid #406aff;}
    @media (max-width: 900px) {
        .cart-item {
            grid-template-columns: 1fr 1fr 1fr;
            grid-auto-rows: auto;
            gap: 0.5rem;
        }
        .cart-summary {
            max-width: 100%;
        }
    }
    @media (max-width: 600px) {
        .cart-item {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .cart-summary {
            padding: 1rem;
        }
        .cart-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <h1><i class="fas fa-shopping-cart"></i> Tu Carrito</h1>
        <div>
            <a href="products.php" class="btn btn-success">← Seguir Comprando</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="cart-container">
        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>" id="messageAlert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="cart-empty">
                <h2>🛒 Tu carrito está vacío</h2>
                <p>¡Agrega algunos juegos para empezar a comprar!</p>
                <a href="products.php" class="btn btn-primary">Ver Juegos</a>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 1rem;">
                <h2 style="color:var(--neon-green);text-shadow:var(--neon-green-glow);">Tu Carrito (<?php echo $total_items; ?> juego<?php echo $total_items != 1 ? 's' : ''; ?>)</h2>
            </div>
            <div class="cart-items">
                <div class="cart-item" style="background: #222b44; font-weight: bold;">
                    <div>Imagen</div>
                    <div>Juego</div>
                    <div>Precio</div>
                    <div>Cantidad</div>
                    <div>Total</div>
                    <div>Acción</div>
                </div>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>"
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             onerror="this.src='assets/img/no-image.jpg'">
                        <div class="item-info">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <?php if (!empty($item['platforms'])): ?>
                                <div class="item-platforms">
                                    <?php echo implode(', ', $item['platforms']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['rating']): ?>
                                <div class="item-rating">⭐ <?php echo number_format($item['rating'], 1); ?>/5</div>
                            <?php endif; ?>
                        </div>
                        <div class="item-price">
                            $<?php echo number_format($item['price'], 0, ',', '.'); ?>
                            <?php if ($item['original_price'] && $item['original_price'] > $item['price']): ?>
                                <span class="item-original-price">
                                    $<?php echo number_format($item['original_price'], 0, ',', '.'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="hidden" name="game_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                       min="0" max="99" class="quantity-input"
                                       onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="item-total">
                            $<?php echo number_format($item['total'], 0, ',', '.'); ?>
                        </div>
                        <div>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="remove_item">
                                <input type="hidden" name="game_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="remove-btn" title="Eliminar del carrito">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Resumen de Compra</h3>
                <div class="summary-row">
                    <span>Subtotal (<?php echo $total_items; ?> juego<?php echo $total_items != 1 ? 's' : ''; ?>):</span>
                    <span>$<?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                </div>
                <div class="summary-row summary-total">
                    <span>TOTAL:</span>
                    <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
            </div>
            <div class="cart-actions">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="clear_cart">
                    <button type="submit" class="btn btn-danger">
                        🗑️ Vaciar Carrito
                    </button>
                </form>
                <a href="products.php" class="btn btn-primary">
                    ← Seguir Comprando
                </a>
                <a href="checkout.php" class="btn btn-success">
                    💳 Ir a Pagar
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Auto-ocultar mensajes
    document.addEventListener('DOMContentLoaded', function() {
        const messageAlert = document.getElementById('messageAlert');
        if (messageAlert) {
            setTimeout(function() {
                messageAlert.style.opacity = '0';
                setTimeout(function() {
                    messageAlert.style.display = 'none';
                }, 300);
            }, 3000);
        }
    });
    // Validar cantidades
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 99) this.value = 99;
        });
    });
    </script>
</body>
</html>
