<?php
// Iniciar sesión para mensajes de error
session_start();

// Validación y procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "ecommerce");
    
    // Verificar conexión
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    
    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Validaciones del lado del servidor
    $errors = [];
    
    // Validar username (no vacío y único)
    if (empty($username)) {
        $errors[] = "El nombre de usuario es obligatorio";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Este nombre de usuario ya existe";
        }
        $stmt->close();
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria";
    } elseif (strlen($password) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres";
    }
    
    // Validar email
    if (empty($email)) {
        $errors[] = "El email es obligatorio";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Formato de email inválido";
    } else {
        // Verificar si el email ya existe
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Este email ya está registrado";
        }
        $stmt->close();
    }
    
    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
        // Hash de la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar usuario en la base de datos con prepared statement
       $stmt = $mysqli->prepare("INSERT INTO users (username, password, fullname, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $password_hash, $fullname, $email, $phone, $address);
        
        if ($stmt->execute()) {
            // Registro exitoso, redireccionar a login
            $_SESSION['success_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error al registrar usuario: " . $mysqli->error;
        }
        
        $stmt->close();
    }
    
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - PixBuy</title>
    <link rel="stylesheet" href="assets/css/style_auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <section class="auth-section">
        <div class="auth-container">
            <h2><i class="fas fa-user-plus"></i> Registro de Usuario</h2>
            
            <?php
            // Mostrar errores si existen
            if (!empty($errors)) {
                echo '<div class="error-container">';
                foreach ($errors as $error) {
                    echo '<p class="error">' . $error . '</p>';
                }
                echo '</div>';
            }
            ?>
            
            <form id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nombre de Usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group" style="position: relative;">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" id="password" name="password" required>
                    <small style="color:#b6ffb3;">Mínimo 8 caracteres</small>
                     <button type="button" id="togglePassword" class="password-toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-group">
                    <label for="fullname"><i class="fas fa-id-card"></i> Nombre Completo</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Teléfono</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                    <textarea id="address" name="address" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit">REGISTRARSE <i class="fas fa-arrow-right"></i></button>
                </div>
                
                <div class="form-footer">
                    ¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a>
                </div>
            </form>
        </div>
    </section>
    
    <script>
    // Validación del lado del cliente
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        let hasErrors = false;
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const email = document.getElementById('email').value.trim();
        const errorMessages = [];
        
        if (username === '') {
            errorMessages.push('El nombre de usuario es obligatorio');
            hasErrors = true;
        } else if (username.length < 3) {
            errorMessages.push('El nombre de usuario debe tener al menos 3 caracteres');
            hasErrors = true;
        }
        if (password === '') {
            errorMessages.push('La contraseña es obligatoria');
            hasErrors = true;
        } else if (password.length < 8) {
            errorMessages.push('La contraseña debe tener al menos 8 caracteres');
            hasErrors = true;
        }
        if (email === '') {
            errorMessages.push('El email es obligatorio');
            hasErrors = true;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errorMessages.push('Formato de email inválido');
            hasErrors = true;
        }
        if (hasErrors) {
            event.preventDefault();
            alert('Por favor, corrige los siguientes errores:\n' + errorMessages.join('\n'));
        }
    });
    </script>
</body>
</html>
