<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password']; // Del formulario

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario_data = $resultado->fetch_assoc();

        // OJO: aquí comparamos contra `contrasena` de la base de datos
        if (password_verify($password, $usuario_data['contrasena'])) {
            $_SESSION['usuario'] = $usuario_data['usuario'];
            $_SESSION['nivel'] = $usuario_data['nivel'];
            header("Location: menu.php");
            exit();
        } else {
            echo "<p style='color:red;'>❌ Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Usuario no encontrado.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Acceso no autorizado.</p>";
}
?>
