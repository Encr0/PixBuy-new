<?php
/**
 * Lógica de productos para PixBuy - Tienda de Videojuegos
 * Incluye definición de videojuegos y funciones auxiliares para el catálogo.
 */

$games = array(
    1 => array(
        "id" => 1,
        "name" => "The Legend of Zelda: Breath of the Wild",
        "consoles" => ["wiiu", "switch"],
        "price" => 29990,
        "original_price" => 34990,
        "image" => "assets/img/LOZBOTW.jpg",
        "description" => "Aventura épica en mundo abierto para Nintendo Switch.",
        "category" => "Aventura",
        "genre" => "Aventura",
        "subgenre" => "Accion",
        "platforms" => ["Nintendo Switch", "Wii U"],
        "rating" => 4.9,
        "stock" => 15,
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => true,
    ),
    2 => array(
        "id" => 2,
        "name" => "God of War: Ragnarok",
         "consoles" => ["playstation", "pc"],
        "price" => 34990,
        //"original_price" => 39990,
        "image" => "assets/img/GOWR.jpg",
        "description" => "Kratos y Atreus enfrentan el fin del mundo nórdico.",
        "category" => "Acción",
        "genre" => "Acción",
        "platforms" => ["PlayStation 5", "PC"],
        "rating" => 4.8,
        "stock" => 10, 
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => false,
    ),
    3 => array(
        "id" => 3,
        "name" => "FIFA 25",
        "consoles" => ["playstation", "xbox", "pc", "switch"],
        "price" => 15990,
        "original_price" => 29990,
        "image" => "assets/img/FIFA25.jpg",
        "description" => "El simulador de fútbol más popular, ahora con nuevas ligas.",
        "category" => "Deportes",
        "genre" => "Deportes",
        "subgenre" => "Simulación",
        "platforms" => ["PlayStation 5", "Xbox Series X", "PC", "Nintendo Switch"],
        "rating" => 4.5,
        "stock" => 25, 
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => true,
    ),
    4 => array(
        "id" => 4,
        "name" => "Elden Ring",
        "consoles" => ["playstation", "xbox", "pc"],
        "price" => 31990,
        //"original_price" => 36990,
        "image" => "assets/img/eldenring.png",
        "description" => "Explora un mundo oscuro y desafiante en este RPG de acción.",
        "category" => "RPG",
        "genre" => "RPG",
        "platforms" => ["PlayStation 5", "Xbox Series X", "PC"],
        "rating" => 4.7,
        "stock" => 12,
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => false,
    ),
    5 => array(
        "id" => 5,
        "name" => "Mario Kart 8 Deluxe",
        "consoles" => ["switch"],
        "price" => 25990,
        //"original_price" => 25990,
        "image" => "assets/img/mariokart.jpg",
        "description" => "Compite con tus amigos en las pistas más locas de Mario.",
        "category" => "Carreras",
        "genre" => "Carreras",
        "platforms" => ["Nintendo Switch"],
        "rating" => 4.6,
        "stock" => 20, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => false,
    ),
    6 => array(
        "id" => 6,
        "name" => "Call of Duty: Modern Warfare III",
        "consoles" => ["playstation", "xbox", "pc"],
        "price" => 39990,
        //"original_price" => 39990,
        "image" => "assets/img/codmw3.jpg",
        "description" => "Acción bélica en primera persona con modo multijugador.",
        "category" => "Shooter",
        "genre" => "Shooter",
        "platforms" => ["PlayStation 5", "Xbox Series X", "PC"],
        "rating" => 4.4,
        "stock" => 18, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => false,
    ),
    7 => array(
        "id" => 7,
        "name" => "Animal Crossing: New Horizons",
        "consoles" => ["switch"],
        "price" => 21990,
        "original_price" => 24990,
        "image" => "assets/img/animalcross.jpg",
        "description" => "Crea tu isla y vive una vida relajada con tus vecinos.",
        "category" => "Simulación",
        "genre" => "Simulación",
        "platforms" => ["Nintendo Switch"],
        "rating" => 4.7,
        "stock" => 14, 
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => true,
    ),
    8 => array(
        "id" => 8,
        "name" => "Final Fantasy XVI",
        "consoles" => ["playstation", "xbox", "pc"],
        "price" => 25990,
        "original_price" => 42990,
        "image" => "assets/img/FFXVI.jpg",
        "description" => "La nueva entrega de la saga RPG más famosa.",
        "category" => "RPG",
        "genre" => "RPG",
        "platforms" => ["PlayStation 5", "XBOX X|S", "PC"],
        "rating" => 4.8,
        "stock" => 8, 
        'is_new' => false,
        'is_popular' => true,
        'is_offer' => true,
    ),
    9 => array(
        "id" => 9,
        "name" => "Spider-Man 2",
        "consoles" => ["playstation", "pc"],
        "price" => 23000,
        "original_price" => 41990,
        "image" => "assets/img/SM2.jpg",
        "description" => "La aventura definitiva de Spider-Man en Nueva York.",
        "category" => "Acción",
        "genre" => "Acción",
        "platforms" => ["PlayStation 5", "PC"],
        "rating" => 4.9,
        "stock" => 9, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => true,
    ),
    10 => array(
        "id" => 10,
        "name" => "Gran Turismo 7",
        "consoles" => ["playstation"],
        "price" => 29990,
        "original_price" => 34990,
        "image" => "assets/img/GT7.jpg",
        "description" => "El simulador de carreras más realista para PlayStation.",
        "category" => "Carreras",
        "genre" => "Carreras",
        "platforms" => ["PlayStation 5"],
        "rating" => 4.5,
        "stock" => 11, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => true,
    ),
    11 => array(
        "id" => 11,
        "name" => "Halo Infinite",
        "consoles" => ["xbox", "pc"],
        "price" => 28990,
        "original_price" => 32990,
        "image" => "assets/img/HI.jpg",
        "description" => "El Jefe Maestro regresa en la batalla por la humanidad.",
        "category" => "Shooter",
        "genre" => "Shooter",
        "platforms" => ["Xbox Series X", "PC"],
        "rating" => 4.3,
        "stock" => 13, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => true,
    ),
    12 => array(
        "id" => 12,
        "name" => "Pokémon Escarlata",
        "consoles" => ["switch"],
        "price" => 45990,
        //"original_price" => 29990,
        "image" => "assets/img/PE.jpg",
        "description" => "Nueva generación de Pokémon en mundo abierto.",
        "category" => "Aventura",
        "genre" => "Aventura",
        "platforms" => ["Nintendo Switch"],
        "rating" => 4.2,
        "stock" => 17, 
        'is_new' => true,
        'is_popular' => false,
        'is_offer' => false,
    ),
     13 => array(
        "id" => 13,
        "name" => "Grand theft auto V",
        "consoles" => ["playstation", "xbox", "pc"],
        "price" => 12990,
        "original_price" => 29990,
        "image" => "assets/img/GTA5.jpg",
        "description" => "Nueva generación de Gta en mundo abierto.",
        "category" => "Simulación",
        "genre" => "Simulación",
        "platforms" => ["PS4", "PS5", "PC", "XBOX X|S"],
        "rating" => 4.2,
        "stock" => 17, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => true,
     ),
     14 => array(
        "id" => 14,
        "name" => "NBA2K25",
        "consoles" => ["playstation", "xbox", "pc", "switch"],
        "price" => 24990,
        //"original_price" => 29990,
        "image" => "assets/img/nba2k25.jpg",
        "description" => "Nueva generación de Gta en mundo abierto.",
        "category" => "Deportes",
        "genre" => "Deportes",
        "subgenre" => "Simulación",
        "platforms" => ["PS5", "PC", "XBOX X|S", "Nintendo Switch"],
        "rating" => 4.1,
        "stock" => 57, 
        'is_new' => false,
        'is_popular' => false,
        'is_offer' => false,
     ),

    );
// =================== FUNCIONES ===================

/**
 * Obtiene un juego por su ID
 */
if (!function_exists('getGameById')) {
    function getGameById($id, $games) {
        foreach ($games as $game) {
            if ($game['id'] == $id) {
                return $game;
            }
        }
        return null;
    }
}


/**
 * Obtiene todos los juegos
 */
if (!function_exists('getAllGames')) {
    function getAllGames() {
        global $games;
        return $games;
    }
}



/**
 * Filtra juegos por categoría
 */
if (!function_exists('getGamesByCategory')) {
    function getGamesByCategory($category, $games) {
    $filtered = [];
    $category = strtolower($category);
    foreach ($games as $game) {
        if (
            strtolower($game['category']) === $category ||
            (isset($game['genre']) && strtolower($game['genre']) === $category) ||
            (isset($game['subgenre']) && strtolower($game['subgenre']) === $category)
        ) {
            $filtered[] = $game;
        }
    }
    return $filtered;
}

}

/**
 * Busca juegos por nombre, descripción, género o plataforma
 */
if (!function_exists('searchGames')) {
    function searchGames($search) {
        global $games;
        $results = array();
        $search = strtolower($search);
        foreach ($games as $game) {
            if (
                strpos(strtolower($game['name']), $search) !== false ||
                strpos(strtolower($game['description']), $search) !== false ||
                (isset($game['genre']) && strpos(strtolower($game['genre']), $search) !== false) ||
                (isset($game['platforms']) && is_array($game['platforms']) && array_filter($game['platforms'], function($platform) use ($search) {
                    return strpos(strtolower($platform), $search) !== false;
                }))
            ) {
                $results[] = $game;
            }
        }
        return $results;
    }
}


/**
 * Verifica si hay stock disponible
 */
if (!function_exists('checkStock')) {
function checkStock($game_id, $quantity) {
    global $games;
    if (!isset($games[$game_id])) {
        return false;
    }
    return $games[$game_id]['stock'] >= $quantity;
}
}
/**
 * Obtiene categorías únicas de juegos
 */
if (!function_exists('getCategories')) {
function getCategories() {
    global $games;
    $categories = array();
    foreach ($games as $game) {
        if (!in_array($game['category'], $categories)) {
            $categories[] = $game['category'];
        }
    }
    return $categories;
}
}
/**
 * Formatea precio con separadores de miles
 */
if (!function_exists('formatPrice')) {
function formatPrice($price) {
    return '$' . number_format($price, 0, ',', '.');
}
}
/**
 * Calcula descuentos por cantidad
 */
if (!function_exists('calculateBulkDiscount')) {
function calculateBulkDiscount($quantity, $price) {
    $discount_rate = 0;
    if ($quantity >= 10) {
        $discount_rate = 0.15;
    } elseif ($quantity >= 5) {
        $discount_rate = 0.10;
    } elseif ($quantity >= 3) {
        $discount_rate = 0.05;
    }
    $original_total = $quantity * $price;
    $discount_amount = $original_total * $discount_rate;
    $final_total = $original_total - $discount_amount;
    return array(
        'original_total' => $original_total,
        'discount_rate' => $discount_rate,
        'discount_amount' => $discount_amount,
        'final_total' => $final_total
    );
}
}
/**
 * Valida datos de juego
 */
if (!function_exists('validateGameData')) {
function validateGameData($game_data) {
    $errors = array();
    if (empty($game_data['name'])) {
        $errors[] = "El nombre del juego es obligatorio";
    }
    if (empty($game_data['price']) || !is_numeric($game_data['price']) || $game_data['price'] <= 0) {
        $errors[] = "El precio debe ser un número mayor a 0";
    }
    if (empty($game_data['category'])) {
        $errors[] = "La categoría es obligatoria";
    }
    if (isset($game_data['stock']) && (!is_numeric($game_data['stock']) || $game_data['stock'] < 0)) {
        $errors[] = "El stock debe ser un número mayor o igual a 0";
    }
    return $errors;
}
}
?>
