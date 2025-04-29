<?php
// Antes de session_start(), configuramos la cookie de sesión con SameSite=Strict
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',                      // deja vacío para el dominio actual
    'secure'   => isset($_SERVER['HTTPS']),// true si usas HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

require_once 'conexion.php';

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Verificar CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token CSRF inválido.';
    }

    // 2) Sanitizar y validar campos
    $usuario    = trim($_POST['usuario']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    if ($usuario === '' || $contrasena === '') {
        $errors[] = 'Debes llenar todos los campos.';
    }

    // 3) Si no hay errores, intentamos autenticar
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, contrasena, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verificar hash de contraseña
            if (password_verify($contrasena, $row['contrasena'])) {
                // Éxito: regenerar ID de sesión y guardar datos
                session_regenerate_id(true);
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol']     = $row['rol'];
                header('Location: menu.php');
                exit;
            }
        }

        $errors[] = 'Usuario o contraseña incorrectos.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        /* Tu CSS aquí (opcional) */
        body { font-family: Arial; background: #f4f6f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; }
        .login-box h2 { margin-bottom: 20px; }
        .login-box input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        .login-box button { width: 100%; padding: 10px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .login-box button:hover { background: #34495e; }
        .error { color: #e74c3c; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <div class="error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required autocomplete="username">

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
