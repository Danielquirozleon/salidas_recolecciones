<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f6f8;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .usuario {
            font-size: 18px;
        }
        .header .cerrar-sesion {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .header .cerrar-sesion:hover {
            background-color: #c0392b;
        }
        .contenido-principal {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background: white;
            width: 280px;
            height: 160px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: #34495e;
            font-weight: bold;
            font-size: 18px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            background-color: #ecf0f1;
        }
        .icono {
            font-size: 40px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="usuario">👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></div>
    <a href="logout.php" class="cerrar-sesion">Cerrar Sesión</a>
</div>

<div class="contenido-principal">
    <div class="container">
        <a href="nueva_salida.php" class="card">
            <div class="icono">➕</div>
            Registrar Nueva Salida
        </a>

        <a href="nueva_salida_almacen.php" class="card">
            <div class="icono">🏢</div>
            Registrar Salida de Almacén
        </a>

        <a href="historial_salidas.php" class="card">
            <div class="icono">📜</div>
            Ver Historial de Salidas
        </a>

        <!-- Nueva tarjeta para el monitor de salidas de almacén -->
        <a href="monitor_salidas_almacen.php" class="card">
            <div class="icono">🖥️</div>
            Monitor Salidas Almacén
        </a>
    </div>
</div>

</body>
</html>
