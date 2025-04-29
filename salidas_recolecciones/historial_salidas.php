<?php
session_start();
require 'conexion.php';

// Preparamos los filtros
$where = [];
$joins = "";

if (!empty($_GET['numero_cliente'])) {
    $numero_cliente = $conn->real_escape_string($_GET['numero_cliente']);
    $where[] = "salidas.numero_cliente LIKE '%$numero_cliente%'";
}

if (!empty($_GET['nombre_cliente'])) {
    $nombre_cliente = $conn->real_escape_string($_GET['nombre_cliente']);
    $where[] = "salidas.nombre_cliente LIKE '%$nombre_cliente%'";
}

if (!empty($_GET['folio'])) {
    $folio = $conn->real_escape_string($_GET['folio']);
    $where[] = "salidas.folio LIKE '%$folio%'";
}

if (!empty($_GET['tipo_salida'])) {
    $tipo_salida = $conn->real_escape_string($_GET['tipo_salida']);
    $where[] = "salidas.tipo_salida = '$tipo_salida'";
}

if (!empty($_GET['usuario_creacion'])) {
    $usuario_creacion = $conn->real_escape_string($_GET['usuario_creacion']);
    $where[] = "salidas.usuario_creacion LIKE '%$usuario_creacion%'";
}

if (!empty($_GET['codigo_producto'])) {
    $codigo_producto = $conn->real_escape_string($_GET['codigo_producto']);
    $joins = "INNER JOIN detalles_salida ON detalles_salida.id_salida = salidas.id";
    $where[] = "detalles_salida.codigo_producto LIKE '%$codigo_producto%'";
}

// Armamos consulta
$sql = "SELECT DISTINCT salidas.* FROM salidas $joins";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY salidas.fecha_creacion DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Salidas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f5f5f5; }
        form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, button { padding: 8px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background-color: #007bff; color: white; border: none; }
        button:hover { background-color: #0056b3; }
        .boton {
            display: inline-block;
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }
        .boton:hover {
            background-color: #218838;
        }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #e0e0e0; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2>🔍 Buscar Salidas</h2>

<form method="GET" action="historial_salidas.php">
    Número de cliente: <input type="text" name="numero_cliente" value="<?php echo htmlspecialchars($_GET['numero_cliente'] ?? ''); ?>">
    Nombre de cliente: <input type="text" name="nombre_cliente" value="<?php echo htmlspecialchars($_GET['nombre_cliente'] ?? ''); ?>">
    Folio: <input type="text" name="folio" value="<?php echo htmlspecialchars($_GET['folio'] ?? ''); ?>">
    Tipo de salida: 
    <select name="tipo_salida">
        <option value="">-- Todos --</option>
        <option value="afecta" <?php if (($_GET['tipo_salida'] ?? '') == 'afecta') echo 'selected'; ?>>Afectando el sistema</option>
        <option value="no_afecta" <?php if (($_GET['tipo_salida'] ?? '') == 'no_afecta') echo 'selected'; ?>>Sin afectar el sistema</option>
    </select>
    Usuario que generó: <input type="text" name="usuario_creacion" value="<?php echo htmlspecialchars($_GET['usuario_creacion'] ?? ''); ?>">
    Código de producto: <input type="text" name="codigo_producto" value="<?php echo htmlspecialchars($_GET['codigo_producto'] ?? ''); ?>">

    <button type="submit">🔎 Buscar</button>
    <a href="historial_salidas.php" class="boton">🔄 Limpiar</a>
    <a href="menu.php" class="boton">🏠 Volver al Menú</a>
</form>

<h2>📋 Resultados</h2>

<table>
    <thead>
        <tr>
            <th>Folio</th>
            <th>Número Cliente</th>
            <th>Nombre Cliente</th>
            <th>Tipo de Salida</th>
            <th>Solicitado por</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado->num_rows > 0): ?>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td>
                    <a href="formato_salida.php?id=<?php echo $row['id']; ?>" target="_blank">
                        <?php echo htmlspecialchars($row['folio']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($row['numero_cliente']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                <td><?php echo ($row['tipo_salida'] == 'afecta') ? 'Afectando el sistema' : 'Sin afectar el sistema'; ?></td>
                <td><?php echo htmlspecialchars($row['usuario_creacion']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_creacion']); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No se encontraron salidas.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<br>
<a href="menu.php" class="boton">🏠 Volver al Menú</a>

</body>
</html>
