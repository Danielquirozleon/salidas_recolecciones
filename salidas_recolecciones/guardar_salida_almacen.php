<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Recibir los datos del formulario
$folio = $_POST['folio'];
$bodega = $_POST['bodega'];
$motivo_salida = $_POST['motivo_salida'];
$solicitado_por = $_POST['solicitado_por'];
$dirigido_a = $_POST['dirigido_a'];
$numero_cliente = $_POST['numero_cliente'];
$nombre_cliente = $_POST['nombre_cliente'];
$direccion_cliente = $_POST['direccion_cliente'];
$telefono_destino = $_POST['telefono_destino'];
$destinatario = $_POST['destinatario'];
$comentarios = $_POST['comentarios'];
$usuario = $_SESSION['usuario']; // Usuario que registró la salida
$fecha_registro = date('Y-m-d H:i:s'); // Fecha actual

// Insertar los datos en la tabla "salidas_almacen"
$sql = "INSERT INTO salidas_almacen (folio, bodega, motivo_salida, solicitado_por, dirigido_a, numero_cliente, nombre_cliente, direccion_cliente, telefono_destino, destinatario, comentarios, usuario_registro, fecha_registro)
VALUES ('$folio', '$bodega', '$motivo_salida', '$solicitado_por', '$dirigido_a', '$numero_cliente', '$nombre_cliente', '$direccion_cliente', '$telefono_destino', '$destinatario', '$comentarios', '$usuario', '$fecha_registro')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('✅ Salida de almacén registrada exitosamente.');
            window.location.href='menu.php';
          </script>";
} else {
    echo "<script>
            alert('❌ Error al guardar la salida: " . $conn->error . "');
            window.history.back();
          </script>";
}

$conn->close();
?>

