<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include_once 'includes/products_logic.php';

// Obtener wishlist antes de procesar acciones
$wishlist = $_SESSION['wishlist'] ?? array();

// Procesar eliminación de la wishlist
if (isset($_GET['action']) && $_GET['action'] === 'remove_from_wishlist' && isset($_GET['id'])) {
    $remove_id = intval($_GET['id']);
    if (($key = array_search($remove_id, $wishlist)) !== false) {
        unset($wishlist[$key]);
        $_SESSION['wishlist'] = array_values($wishlist); // Reindexar el array
        $message = "Juego eliminado de tu lista de deseos.";
        $message_type = "success";
    }
}
$wishlist = $_SESSION['wishlist'] ?? array();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - PixBuy</title>
    <link rel="stylesheet" href="assets/css/gamestore.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .wishlist-section {
        max-width: 1200px;
        margin: 2rem auto 3rem auto;
        background: #181a1b;
        border-radius: 16px;
        padding: 2rem 1rem;
        box-shadow: 0 4px 32px #39ff1420;
    }
    .wishlist-section h2 {
        color: #39ff14;
        font-family: 'Orbitron', sans-serif;
        text-shadow: 0 0 12px #39ff14, 0 0 24px #39ff14;
        margin-bottom: 2rem;
        text-align: center;
    }
    .wishlist-section p {
        color: #b6b6b6;
        text-align: center;
        font-size: 1.2rem;
        margin-top: 2rem;
    }
    .wishlist-section .product-grid {
        margin-top: 1rem;
    }
    .wishlist-section .game-card {
        border: 2px solid #2cff05;
        box-shadow: 0 0 18px #2cff0522;
        background: #181a1b;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .wishlist-section .game-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 0 32px #39ff14, 0 2px 16px #000;
    }
    .wishlist-section .add-to-cart-btn {
        background: #0a0a0a;
        color: #39ff14;
        border: 2px solid #39ff14;
        border-radius: 8px;
        padding: 0.7rem 1.1rem;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.2s;
        box-shadow: 0 0 8px #39ff14;
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .wishlist-section .add-to-cart-btn:hover {
        background: #39ff14;
        color: #0a0a0a;
        box-shadow: 0 0 24px #39ff14;
    }
    .remove-wishlist-btn {
        background: #ff4060;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.7rem 1.1rem;
        font-size: 1rem;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        margin-left: 0.5rem;
        box-shadow: 0 0 8px #ff4060;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .remove-wishlist-btn:hover {
        background: #c82333;
        color: #fff;
        box-shadow: 0 0 16px #ff4060;
    }
    @media (max-width: 900px) {
        .wishlist-section { padding: 1rem 0.5rem; }
        .product-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .wishlist-section { padding: 1rem 0.2rem; }
        .product-grid { grid-template-columns: 1fr; }
    }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-logo">
            <i class="fas fa-gamepad"></i>
            <span>PixBuy</span>
        </div>
        <div class="nav-links">
            <a href="products.php" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
            <a href="wishlist.php" class="nav-link active"><i class="far fa-heart"></i> Wishlist</a>
            <a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Carrito</a>
        </div>
        <div class="nav-user">
            <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
            <a href="logout.php" class="logout-btn" title="Cerrar sesión"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>

    <section class="wishlist-section">
        <div class="container">
            <h2><i class="far fa-heart"></i> Mi Lista de Deseos</h2>
            <?php if (isset($message)): ?>
                <div class="message <?php echo $message_type ?? ''; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <?php if (empty($wishlist)): ?>
                <p>No tienes juegos en tu lista de deseos.</p>
                <div style="text-align:center;margin:2rem 0;">
                    <a href="products.php" class="add-to-cart-btn"><i class="fas fa-plus"></i> Explorar Juegos</a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($wishlist as $product_id): ?>
                        <?php if (isset($games[$product_id])): ?>
                            <?php $game = $games[$product_id]; ?>
                            <div class="game-card" data-category="<?php echo htmlspecialchars($game['category'] ?? 'general'); ?>">
                                <div class="game-image">
                                    <img src="<?php echo htmlspecialchars($game['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($game['name']); ?>"
                                         onerror="this.src='assets/img/no-game.jpg'">
                                </div>
                                <div class="game-info">
                                    <h3 class="game-title"><?php echo htmlspecialchars($game['name']); ?></h3>
                                    <p class="game-genre"><?php echo htmlspecialchars($game['genre'] ?? 'Videojuego'); ?></p>
                                    <div class="game-price-container">
                                        <?php if (isset($game['original_price']) && $game['original_price'] > $game['price']): ?>
                                            <span class="original-price">$<?php echo number_format($game['original_price'], 0, ',', '.'); ?></span>
                                            <div class="discount-badge">
                                                -<?php echo round((($game['original_price'] - $game['price']) / $game['original_price']) * 100); ?>%
                                            </div>
                                        <?php endif; ?>
                                        <span class="current-price">$<?php echo number_format($game['price'], 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="game-actions">
                                        <a href="products.php?action=add_to_cart&id=<?php echo $game['id']; ?>" 
                                           class="add-to-cart-btn"
                                           title="Agregar al carrito">
                                            <i class="fas fa-cart-plus"></i>
                                            <span>Agregar al Carrito</span>
                                        </a>
                                        <a href="wishlist.php?action=remove_from_wishlist&id=<?php echo $game['id']; ?>" 
                                           class="remove-wishlist-btn"
                                           title="Quitar de la Wishlist"
                                           onclick="return confirm('¿Seguro que deseas quitar este juego de tu wishlist?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div>
                <h2>PixBuy</h2>
                <p>Tu tienda gamer futurista. ¡Gracias por visitarnos!</p>
            </div>
            <div>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2025 PixBuy. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>
