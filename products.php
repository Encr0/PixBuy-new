<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once 'includes/products_logic.php';

$mostrados = [];

// Inicializa carrito y wishlist si no existen
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = array();

// Procesa adición al carrito
if (isset($_GET['action']) && $_GET['action'] === 'add_to_cart' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($games[$product_id])) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        $message = "Videojuego agregado al carrito";
        $message_type = "success";
    } else {
        $message = "Videojuego no encontrado";
        $message_type = "error";
    }
}

// Procesa adición a wishlist
if (isset($_GET['action']) && $_GET['action'] === 'add_to_wishlist' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($games[$product_id]) && !in_array($product_id, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $product_id;
        $message = "Videojuego agregado a tu lista de deseos";
        $message_type = "success";
    }
}

$cart_count = array_sum($_SESSION['cart']);
$wishlist_count = count($_SESSION['wishlist']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixBuy - Catálogo</title>
    <link rel="stylesheet" href="assets/css/gamestore.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-logo">
            <i class="fas fa-gamepad"></i>
            <span>PixBuy</span>
        </div>
        <div class="nav-links">
            <a href="#" class="nav-link active"><i class="fas fa-home"></i> Inicio</a>
            <a href="#populares" class="nav-link"><i class="fas fa-fire"></i> Populares</a>
            <a href="#nuevos" class="nav-link"><i class="fas fa-star"></i> Nuevos</a>
            <a href="#ofertas" class="nav-link"><i class="fas fa-tags"></i> Ofertas</a>
            <a href="wishlist.php" class="nav-link"><i class="far fa-heart"></i> Wishlist</a>
            <a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Carrito</a>
        </div>
        <div class="nav-user">
            <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
            <a href="logout.php" class="logout-btn" title="Cerrar sesión"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <h1>Descubre los mejores <span class="neon">videojuegos</span></h1>
        <p>Catálogo actualizado, ofertas exclusivas y una experiencia gamer única.</p>
    </header>

    <!-- Filtros y Búsqueda -->
    <section class="filters-section">
        <div class="filters-bar">
            <input type="text" id="searchInput" placeholder="Buscar juegos..." aria-label="Buscar juegos">
    <select id="consoleSelect" class="console-select" aria-label="Filtrar por consola">
        <option value="all">Todas las consolas</option>
        <option value="switch">Nintendo Switch</option>
        <option value="pc">PC</option>
        <option value="xbox">Xbox</option>
        <option value="playstation">PlayStation</option>
        <option value="wiiu">Wii U</option>
    </select>

            <div class="filters-btns">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="accion">Acción</button>
                <button class="filter-btn" data-filter="aventura">Aventura</button>
                <button class="filter-btn" data-filter="rpg">RPG</button>
                <button class="filter-btn" data-filter="deportes">Deportes</button>
                <button class="filter-btn" data-filter="estrategia">Estrategia</button>
            </div>
        </div>
    </section>
    

    <!-- Mensajes -->
    <?php if (isset($message)): ?>
        <div class="message <?php echo $message_type; ?>" id="messageAlert">
            <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- POPULARES -->
<section class="featured-section" id="populares">
    <h2><i class="fas fa-fire"></i> Juegos Populares</h2>
    <div class="product-grid">
        <?php foreach ($games as $game): ?>
            <?php if (!empty($game['is_popular'])): ?>
                <?php include 'includes/game_card.php'; ?>
                <?php $mostrados[] = $game['id']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- NUEVOS -->
<section class="featured-section" id="nuevos">
    <h2><i class="fas fa-star"></i> Juegos Nuevos</h2>
    <div class="product-grid">
        <?php foreach ($games as $game): ?>
            <?php if (!empty($game['is_new']) && !in_array($game['id'], $mostrados)): ?>
                <?php include 'includes/game_card.php'; ?>
                <?php $mostrados[] = $game['id']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- OFERTAS -->
<section class="featured-section" id="ofertas">
    <h2><i class="fas fa-tags"></i> Juegos en Oferta</h2>
    <div class="product-grid">
        <?php foreach ($games as $game): ?>
            <?php if (
                (!empty($game['is_offer']) || (isset($game['original_price']) && $game['original_price'] > $game['price']))
                && !in_array($game['id'], $mostrados)
            ): ?>
                <?php include 'includes/game_card.php'; ?>
                <?php $mostrados[] = $game['id']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- TODOS LOS JUEGOS (solo los que no están en $mostrados) -->
<section class="products-section" id="todos">
    <h2><i class="fas fa-th"></i> Todos los Juegos</h2>
    <div class="product-grid">
        <?php
        $hay_juegos = false;
        foreach ($games as $game):
            if (!in_array($game['id'], $mostrados)):
                include 'includes/game_card.php';
                $hay_juegos = true;
            endif;
        endforeach;
        if (!$hay_juegos): ?>
            <div class="no-games">
                <i class="fas fa-gamepad"></i>
                <p>No hay más juegos para mostrar.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <h2>PixBuy Chile</h2>
                <p>Tu tienda gamer de confianza. ¡Gracias por visitarnos!</p>
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
    <script src="assets/js/gamestore.js"></script>
</body>
</html>
