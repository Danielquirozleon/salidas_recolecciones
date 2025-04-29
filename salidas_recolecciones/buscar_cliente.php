<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Validar que llegue el número de cliente
if (!isset($_GET['numero_cliente']) || empty($_GET['numero_cliente'])) {
    echo json_encode([]);
    exit();
}

$numero_cliente = $conn->real_escape_string($_GET['numero_cliente']); // ← CAMBIADO a $conn

// Buscar cliente
$sqlCliente = "SELECT id, nombre_cliente FROM clientes WHERE numero_cliente = ?";
$stmtCliente = $conn->prepare($sqlCliente);
$stmtCliente->bind_param("s", $numero_cliente);
$stmtCliente->execute();
$resCliente = $stmtCliente->get_result();

$respuesta = [];

if ($resCliente->num_rows > 0) {
    $cliente = $resCliente->fetch_assoc();
    $respuesta['nombre_cliente'] = $cliente['nombre_cliente'];

    // Buscar direcciones del cliente
    $id_cliente = $cliente['id'];
    $sqlDirecciones = "SELECT direccion FROM direcciones_clientes WHERE id_cliente = ?";
    $stmtDirecciones = $conn->prepare($sqlDirecciones);
    $stmtDirecciones->bind_param("i", $id_cliente);
    $stmtDirecciones->execute();
    $resDirecciones = $stmtDirecciones->get_result();

    $direcciones = [];
    while ($dir = $resDirecciones->fetch_assoc()) {
        $direcciones[] = $dir['direccion'];
    }
    $respuesta['direcciones'] = $direcciones;

} else {
    // No encontrado
    $respuesta['nombre_cliente'] = "";
    $respuesta['direcciones'] = [];
}

// Devolver respuesta JSON
echo json_encode($respuesta);
?>
