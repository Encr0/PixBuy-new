<?php
// Prepara la categoría y consolas antes del HTML
$category = strtolower(str_replace(
    [' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'],
    ['-', 'a', 'e', 'i', 'o', 'u', 'n'],
    $game['category'] ?? 'general'
));
$consoles = isset($game['consoles']) && is_array($game['consoles']) ? $game['consoles'] : [];
$data_consoles = strtolower(implode(',', $consoles));
?>
<div class="game-card"
     data-category="<?php echo $category; ?>"
     data-consoles="<?php echo strtolower(implode(',', $game['consoles'])); ?>">

    <div class="game-image" style="position:relative;">
        <img src="<?php echo htmlspecialchars($game['image']); ?>" 
             alt="Portada de <?php echo htmlspecialchars($game['name']); ?>"
             onerror="this.src='assets/img/no-game.jpg'">
        <div class="game-overlay">
            <div class="rating-info-group">
                <div class="rating" title="Valoración">
                    <i class="fas fa-star"></i>
                    <span><?php echo $game['rating'] ?? '4.5'; ?></span>
                </div>
                <?php if (!empty($game['genre'])): ?>
                    <span class="genre-badge" title="Género">
                        <?php echo htmlspecialchars($game['genre']); ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($game['subgenre'])): ?>
                    <span class="subgenre-badge" title="Subgénero">
                        <?php echo htmlspecialchars($game['subgenre']); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="badge-stack">
            <?php if (!empty($game['is_new'])): ?>
                <span class="badge badge-new" title="¡Nuevo!">NUEVO</span>
            <?php endif; ?>
            <?php if (!empty($game['is_popular'])): ?>
                <span class="badge badge-popular" title="Popular"><i class="fas fa-fire"></i></span>
            <?php endif; ?>
            <?php if (!empty($game['is_offer']) || (isset($game['original_price']) && $game['original_price'] > $game['price'])): ?>
                <span class="badge badge-offer" title="En Oferta"><i class="fas fa-tags"></i></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="game-info">
        <h3 class="game-title" title="<?php echo htmlspecialchars($game['name']); ?>">
            <?php echo htmlspecialchars($game['name']); ?>
        </h3>
        <div class="platform-list">
            <?php 
            $platforms = $game['platforms'] ?? ['PC'];
            foreach ($platforms as $platform): 
            ?>
                <span class="platform-tag" title="Disponible en <?php echo htmlspecialchars($platform); ?>">
                    <?php echo htmlspecialchars($platform); ?>
                </span>
            <?php endforeach; ?>
        </div>
        <div class="game-price-container">
            <?php if (isset($game['original_price']) && $game['original_price'] > $game['price']): ?>
                <span class="original-price" title="Precio anterior">
                    $<?php echo number_format($game['original_price'], 0, ',', '.'); ?>
                </span>
                <div class="discount-badge" title="Descuento">
                    -<?php echo round((($game['original_price'] - $game['price']) / $game['original_price']) * 100); ?>%
                </div>
            <?php endif; ?>
            <span class="current-price" title="Precio actual">
                $<?php echo number_format($game['price'], 0, ',', '.'); ?>
            </span>
        </div>
        <div class="game-actions">
            <a href="?action=add_to_cart&id=<?php echo $game['id']; ?>" 
               class="add-to-cart-btn"
               title="Agregar al carrito">
                <i class="fas fa-cart-plus"></i>
                <span>Agregar</span>
            </a>
            
            <a href="?action=add_to_wishlist&id=<?php echo $game['id']; ?>" 
               class="wishlist-btn" 
               title="Agregar a Wishlist">
                <i class="far fa-heart"></i>
            </a>
            <?php if (isset($showRemoveWishlist) && $showRemoveWishlist): ?>
            <a href="?action=remove_from_wishlist&id=<?php echo $game['id']; ?>"
               class="remove-wishlist-btn"
               title="Quitar de Wishlist">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
