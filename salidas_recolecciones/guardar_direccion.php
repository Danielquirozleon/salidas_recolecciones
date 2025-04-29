<?php
session_start();
require_once 'conexion.php';

// Asegurarse que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "mensaje" => "No autorizado"]);
    exit();
}

// Validar que lleguen todos los datos
$campos_obligatorios = ['numero_cliente', 'calle', 'numero', 'municipio', 'estado', 'cp', 'telefono'];
foreach ($campos_obligatorios as $campo) {
    if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
        echo json_encode(["status" => "error", "mensaje" => "Falta el campo: $campo"]);
        exit();
    }
}

// Recibir datos
$numero_cliente = trim($_POST['numero_cliente']);
$calle = trim($_POST['calle']);
$numero = trim($_POST['numero']);
$interior = trim($_POST['interior'] ?? ''); // Opcional
$municipio = trim($_POST['municipio']);
$estado = trim($_POST['estado']);
$cp = trim($_POST['cp']);
$telefono = trim($_POST['telefono']);
$referencias = trim($_POST['referencias'] ?? ''); // Opcional

// Buscar ID del cliente
$stmtCliente = $conn->prepare("SELECT id FROM clientes WHERE numero_cliente = ?");
$stmtCliente->bind_param("s", $numero_cliente);
$stmtCliente->execute();
$resCliente = $stmtCliente->get_result();

if ($resCliente->num_rows == 0) {
    echo json_encode(["status" => "error", "mensaje" => "Cliente no encontrado"]);
    exit();
}

$cliente = $resCliente->fetch_assoc();
$id_cliente = $cliente['id'];

// Armar dirección completa
$direccion_completa = "$calle #$numero";
if (!empty($interior)) {
    $direccion_completa .= " Int. $interior";
}
$direccion_completa .= ", $municipio, $estado, CP: $cp, Tel: $telefono";
if (!empty($referencias)) {
    $direccion_completa .= ". Referencias: $referencias";
}

// Insertar nueva dirección
$stmtInsert = $conn->prepare("INSERT INTO direcciones_clientes (id_cliente, direccion) VALUES (?, ?)");
$stmtInsert->bind_param("is", $id_cliente, $direccion_completa);

if ($stmtInsert->execute()) {
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al guardar la dirección"]);
}
?>
