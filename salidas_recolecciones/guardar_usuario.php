<?php
require_once 'conexion.php'; // Asegurarnos de incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Validación básica
    if (empty($usuario) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Hashear la contraseña para mayor seguridad
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $usuario, $password_hash);
        if ($stmt->execute()) {
            echo "Usuario registrado exitosamente.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error al registrar usuario.";
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }
} else {
    echo "Método no permitido.";
}
?>
