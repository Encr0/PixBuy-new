<?php
session_start();

// Si el usuario ya está logueado, redirigir a productos
if (isset($_SESSION['user_id'])) {
    header("Location: products.php");
    exit();
}

// Procesar formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "ecommerce");
    
    // Verificar conexión
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = [];
    
    // Validaciones básicas
    if (empty($username)) {
        $errors[] = "El nombre de usuario es obligatorio";
    }
    
    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria";
    }
    
    // Si no hay errores de validación, verificar credenciales
    if (empty($errors)) {
        // Prepared statement para buscar usuario
        $stmt = $mysqli->prepare("SELECT id, password, fullname FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $stored_password, $fullname);
            $stmt->fetch();
            
            // Verificar contraseña
            if (password_verify($password, $stored_password)) {
                // Login exitoso
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                
                // Regenerar ID de sesión por seguridad
                session_regenerate_id(true);
                
                // Registrar último login (opcional)
                $update_stmt = $mysqli->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Redirigir a página de productos
                header("Location: products.php");
                exit();
            } else {
                $errors[] = "Credenciales incorrectas";
            }
        } else {
            $errors[] = "Credenciales incorrectas";
        }
        
        $stmt->close();
    }
    
    $mysqli->close();
}

// Verificar mensajes de la sesión
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$logout_message = '';
if (isset($_GET['message']) && $_GET['message'] === 'logged_out') {
    $logout_message = 'Has cerrado sesión exitosamente';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PixBuy</title>
    <link rel="stylesheet" href="assets/css/style_auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <section class="auth-section">
        <div class="auth-container">
            <h2><i class="fas fa-gamepad"></i> Iniciar Sesión</h2>
            
            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($logout_message)): ?>
                <div class="message info"><?php echo $logout_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nombre de Usuario</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group" style="position: relative;">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" id="password" name="password" required>
                    <button type="button" id="togglePassword" class="password-toggles">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-group">
                    <button type="submit">ACCEDER <i class="fas fa-arrow-right"></i></button>
                </div>
            </form>

            <div class="form-footer">
                ¿Nuevo aquí? <a href="register.php">Crea una cuenta</a>
            </div>
            <div class="form-footer">
                <a href="forgot_password.php"><i class="fas fa-key"></i> Recuperar contraseña</a>
            </div>
        </div>
    </section>

    <script>
    // Toggle para mostrar/ocultar contraseña con ícono
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // Validación mejorada
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        let isValid = true;
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        // Reset errores
        document.querySelectorAll('.error-highlight').forEach(el => el.classList.remove('error-highlight'));
        
        if (!username) {
            document.getElementById('username').classList.add('error-highlight');
            isValid = false;
        }
        
        if (!password) {
            document.getElementById('password').classList.add('error-highlight');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor completa todos los campos requeridos');
        }
    });
    </script>
</body>
</html>