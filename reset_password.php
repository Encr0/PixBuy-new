<?php
session_start();

$errors = [];
$success = '';
$user_id = null;

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "ecommerce");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// 1. Verificar si hay token en la URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // 2. Buscar usuario con ese token y que no haya expirado
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // 3. Si se envió el formulario de nueva contraseña
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validaciones
            if (empty($new_password) || strlen($new_password) < 8) {
                $errors[] = "La nueva contraseña debe tener al menos 8 caracteres.";
            } elseif ($new_password !== $confirm_password) {
                $errors[] = "Las contraseñas no coinciden.";
            }

            // Si no hay errores, actualizar la contraseña
            if (empty($errors)) {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $mysqli->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                $update->bind_param("si", $password_hash, $user_id);
                if ($update->execute()) {
                    $success = "¡Contraseña actualizada correctamente! Ahora puedes <a href='login.php'>iniciar sesión</a>.";
                } else {
                    $errors[] = "Ocurrió un error al actualizar la contraseña.";
                }
                $update->close();
            }
        }
    } else {
        $errors[] = "El enlace de recuperación no es válido o ha expirado. Solicita uno nuevo.";
    }
    $stmt->close();
} else {
    $errors[] = "No se proporcionó un token de recuperación válido.";
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style_auth.css">
</head>
<body>
<div class="container">
    <h2>Restablecer Contraseña</h2>
    <?php
    if (!empty($success)) {
        echo "<div class='success-message'><p class='success'>$success</p></div>";
    } else {
        if (!empty($errors)) {
            echo "<div class='error-container'>";
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
            echo "</div>";
        }
        // Solo muestra el formulario si hay un usuario válido y no hay éxito todavía
        if ($user_id && empty($success)) {
    ?>
    <form method="post" id="resetForm">
        <div class="form-group">
            <label for="new_password">Nueva contraseña:</label>
            <input type="password" id="new_password" name="new_password" required minlength="8">
            <small>Mínimo 8 caracteres</small>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>
        <div class="form-group">
            <button type="submit">Actualizar contraseña</button>
        </div>
    </form>
    <script>
    // Validación en cliente
    document.getElementById('resetForm').addEventListener('submit', function(event) {
        const pass1 = document.getElementById('new_password').value;
        const pass2 = document.getElementById('confirm_password').value;
        if (pass1.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres.');
            event.preventDefault();
        } else if (pass1 !== pass2) {
            alert('Las contraseñas no coinciden.');
            event.preventDefault();
        }
    });
    </script>
    <?php
        }
    }
    ?>
</div>
</body>
</html>
