<?php
session_start();

header("Content-Security-Policy: default-src 'self'");
header("X-Content-Type-Options: nosniff");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $errors[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El formato del correo electrónico no es válido.";
    }

    if (empty($errors)) {
        $mysqli = new mysqli("localhost", "root", "", "ecommerce");
        
        if ($mysqli->connect_error) {
            error_log("Error de conexión a la base de datos: " . $mysqli->connect_error);
            $errors[] = "Error temporal. Por favor intente más tarde.";
        } else {
            $stmt = $mysqli->prepare("SELECT id, username FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $username);
                $stmt->fetch();
                $stmt->close();

                // Generar token seguro
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hora de validez

                // Actualizar base de datos
                $update_stmt = $mysqli->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
                $update_stmt->bind_param("ssi", $token, $expires, $user_id);
                $update_stmt->execute();
                $update_stmt->close();

                // Configurar y enviar correo
                $reset_link = "http://localhost/PixBuy/reset_password.php?token=$token";
                
                try {
                    $mail = new PHPMailer(true);
                    
                    // Configuración SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'tu_correo@gmail.com'; 
                    $mail->Password = 'contraseña_aplicacion'; // Contraseña de aplicación
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    // Configurar correo
                    $mail->setFrom('tu_correo', 'Nombre de la pagina web');
                    $mail->addAddress($email, $username);
                    $mail->isHTML(true);
                    
                    // Asunto y contenido del correo
                    $mail->Subject = 'Recuperación de contraseña - PixBuy';
                    $mail->Body = "
<!DOCTYPE html>
<html lang='es'>
<head>
  <meta charset='UTF-8'>
  <title>Recuperar tu contraseña</title>
</head>
<body style='background-color:#f4f6fb; margin:0; padding:0; font-family:Segoe UI, Arial, sans-serif;'>
  <div style='background:#fff; max-width:480px; margin:40px auto; border-radius:12px; box-shadow:0 4px 24px rgba(37,99,235,0.08); padding:32px 24px;'>
    <img src='https://img.icons8.com/color/96/lock--v1.png' alt='Recuperar contraseña' style='display:block; margin:0 auto 24px auto; width:64px; height:64px;'>
    <h2 style='color:#2563eb; text-align:center; margin-bottom:12px; font-size:1.6em; font-weight:700;'>Hola $username,</h2>
    <p style='color:#334155; font-size:1em; line-height:1.6; margin-bottom:16px; text-align:center;'>
      Recibimos una solicitud para restablecer tu contraseña.<br>
      Haz clic en el siguiente botón para continuar:
    </p>
    <div style='text-align:center; margin: 24px 0;'>
      <a href='$reset_link'
         style='display:inline-block; background:linear-gradient(90deg,#2563eb 60%,#1e40af 100%); color:#fff; padding:14px 32px; border-radius:8px; text-decoration:none; font-size:1.1em; font-weight:600; box-shadow:0 2px 6px rgba(37,99,235,0.12);'>
        Restablecer contraseña
      </a>
    </div>
    <p style='color:#64748b; font-size:0.93em; text-align:center;'>
      Si no solicitaste este cambio, puedes ignorar este correo.<br>
      El enlace expirará en 1 hora.
    </p>
    <div style='border-top:1px solid #e5e7eb; margin-top:32px; padding-top:16px; text-align:center; color:#94a3b8; font-size:0.9em;'>
      © ".date('Y')." PixBuy. Todos los derechos reservados.
    </div>
  </div>
</body>
</html>
";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Error al enviar correo: " . $mail->ErrorInfo);
                    // No mostrar el error real al usuario
                }

                // Mensaje genérico de éxito
                $success = "Si el correo existe, te hemos enviado instrucciones para restablecer tu contraseña.";
            } else {
                // Mensaje genérico incluso si el email no existe
                $success = "Si el correo existe, te hemos enviado instrucciones para restablecer tu contraseña.";
            }
            
            $mysqli->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style_auth.css">
</head>
<body>
    <div class="container">
        <form id="forgotForm" method="post" action="">
            <h2>Recuperar Contraseña</h2>
            <?php
            if (!empty($success)) {
                echo '<div class="success-message"><p class="success">' . $success . '</p></div>';
            }
            if (!empty($errors)) {
                echo '<div class="error-container">';
                foreach ($errors as $error) {
                    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            ?>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <button type="submit">Enviar Instrucciones</button>
            </div>
            <div class="form-footer">
                <a href="login.php">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>
    <script>
    // Validación básica en el cliente
    document.getElementById('forgotForm').addEventListener('submit', function(event) {
        const email = document.getElementById('email').value.trim();
        let errorMessages = [];

        if (email === '') {
            errorMessages.push('El correo electrónico es obligatorio.');
        } else if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
            errorMessages.push('El correo electrónico no es válido.');
        }

        if (errorMessages.length > 0) {
            event.preventDefault();
            alert(errorMessages.join('\n'));
        }
    });
    </script>
</body>
</html>
