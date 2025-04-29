<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Validar que llegue el término de búsqueda
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo json_encode([]);
    exit();
}

$busqueda = $conn->real_escape_string($_GET['query']); // ← CAMBIADO a $conn

// Buscar productos por código o nombre
$sql = "SELECT codigo_producto, nombre_producto FROM productos WHERE codigo_producto LIKE ? OR nombre_producto LIKE ?";
$stmt = $conn->prepare($sql);
$likeBusqueda = "%$busqueda%";
$stmt->bind_param("ss", $likeBusqueda, $likeBusqueda);
$stmt->execute();
$res = $stmt->get_result();

$productos = [];

while ($row = $res->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
?>
