<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Validar datos básicos
if (!isset($_POST['tipo_salida']) || empty($_POST['tipo_salida'])) {
    die("Error: No se especificó el tipo de salida.");
}

$tipo_salida = $_POST['tipo_salida'];
$folio = $_POST['folio'] ?? '';
$solicitado_por = $_POST['solicitado_por'] ?? '';
$dirigido_a = $_POST['dirigido_a'] ?? '';
$numero_cliente = $_POST['numero_cliente'] ?? '';
$nombre_cliente = $_POST['nombre_cliente'] ?? '';
$direccion_cliente = $_POST['direccion_cliente'] ?? '';
$comentario = $_POST['comentario'] ?? '';
$usuario_creacion = $_SESSION['usuario'];
$fecha_creacion = date('Y-m-d H:i:s');
$folio_afectacion = $_POST['folio_afectacion'] ?? null;

// Validar productos si es salida "no afecta"
if ($tipo_salida === 'no_afecta') {
    if (!isset($_POST['productos_json']) || empty($_POST['productos_json'])) {
        die("Error: No se especificaron productos para la salida.");
    }
    $productos_json = $_POST['productos_json'];
    $productos = json_decode($productos_json, true);

    if (empty($productos)) {
        die("Error: Debes agregar al menos un producto para salida sin afectar.");
    }
} else {
    $productos = []; // Vacío si es "afecta"
}

// Guardar salida principal
$stmt = $conn->prepare("INSERT INTO salidas (folio, tipo_salida, solicitado_por, dirigido_a, numero_cliente, nombre_cliente, direccion_cliente, comentario, usuario_creacion, fecha_creacion, folio_afectacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $folio, $tipo_salida, $solicitado_por, $dirigido_a, $numero_cliente, $nombre_cliente, $direccion_cliente, $comentario, $usuario_creacion, $fecha_creacion, $folio_afectacion);

if ($stmt->execute()) {
    $id_salida = $stmt->insert_id;

    // Si es salida "no afecta", guardar los productos
    if ($tipo_salida == 'no_afecta' && !empty($productos)) {
        foreach ($productos as $producto) {
            $codigo_producto = $producto['codigo'];
            $nombre_producto = $producto['descripcion'];
            $cantidad = $producto['cantidad'];
    
            $stmt_prod = $conn->prepare("INSERT INTO detalles_salida (id_salida, codigo_producto, nombre_producto, cantidad) VALUES (?, ?, ?, ?)");
            $stmt_prod->bind_param("issi", $id_salida, $codigo_producto, $nombre_producto, $cantidad);
            $stmt_prod->execute();
        }
    }

    // Redirigir a impresión o mensaje de éxito
    header("Location: formato_salida.php?id=" . $id_salida);
    exit();
} else {
    echo "Error al guardar la salida: " . $conn->error;
}
?>
