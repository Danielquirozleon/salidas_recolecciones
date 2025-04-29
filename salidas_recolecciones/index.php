<?php
session_start();
if (isset($_SESSION['usuario'])) {
    // Si ya está logueado, redireccionarlo al menú
    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido al Sistema de Salidas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
            background-color: #f5f5f5;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            font-size: 18px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Bienvenido al Sistema de Control de Salidas</h1>

    <a href="login.php">Iniciar Sesión</a>

</body>
</html>
