<?php
session_start();
require 'conexion.php';

// Verificar sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se recibe el ID de la salida
if (!isset($_GET['id'])) {
    echo "Error: No se especificó la salida.";
    exit();
}

$id_salida = intval($_GET['id']);

// Buscar datos de la salida
$sql = "SELECT * FROM salidas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_salida);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "Error: Salida no encontrada.";
    exit();
}

$salida = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formato de Salida</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
        h3 {text-align:left }
        @media print {
         .no-imprimir {
        display: none;
         }
}
        

    </style>
</head>
<body>

<!-- Logo y Folio -->
<div style="text-align: center;">
    <img src="cicovisa1.png" alt="Logo" style="height: 80px;"><br>
    <strong>Folio de salida: <?php echo htmlspecialchars($salida['folio']); ?></strong><br>
    <strong>Fecha de creación: <?php echo htmlspecialchars($salida['fecha_creacion']); ?></strong><br>
</div>

<!-- Datos principales -->
<h3 class="titulo_comentario">Datos del Cliente</h3>
<p><strong>Cliente:</strong> <?php echo htmlspecialchars($salida['numero_cliente']); ?> - <?php echo htmlspecialchars($salida['dirigido_a']); ?></p>
<?php if ($salida['direccion_cliente']): ?>
<p><strong>Dirección:</strong> <?php echo htmlspecialchars($salida['direccion_cliente']); ?></p>
<?php endif; ?>

<h3 class="titulo_comentario">Tipo de Salida</h3>
<p><?php echo ($salida['tipo_salida'] == 'afecta') ? 'Afectando el sistema' : 'Sin afectar el sistema'; ?></p>

<!-- Productos solo si no afecta el sistema -->
<?php
if ($salida['tipo_salida'] == 'no_afecta') {
    $productos = $conn->query("SELECT * FROM detalles_salida WHERE id_salida = $id_salida");
?>
    <h3 class="titulo_comentario">Productos</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>    
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($producto = $productos->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($producto['codigo_producto']); ?></td>
                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php } ?>

<!-- Comentarios -->
<h3 class="titulo_comentario">Comentarios</h3>
<p><?php echo nl2br(htmlspecialchars($salida['comentario'])); ?></p>

<!-- Firma -->
<div style="margin-top: 50px; text-align: center;">
    ___________________________________<br>
    Recibido
</div>

<div class="no-imprimir">
    <a href="menu.php">
        <button type="button"> Volver al menú</button>
    </a>
</div>

</body>
</html>
