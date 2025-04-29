<?php
require_once 'conexion.php'; // Incluimos la conexión correctamente

// Verificar si llegaron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    if (!empty($usuario) && !empty($contrasena)) {
        // Hashear la contraseña (muy importante para seguridad)
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $contrasenaHash);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario creado exitosamente'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error al crear usuario'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Debes llenar todos los campos'); window.history.back();</script>";
    }
} else {
    // Si no llegaron datos, mostramos el formulario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nuevo Usuario</title>
</head>
<body>
    <h2>Registrar Nuevo Usuario</h2>
    <form method="POST" action="crear_usuario.php">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>

<?php
}
?>
