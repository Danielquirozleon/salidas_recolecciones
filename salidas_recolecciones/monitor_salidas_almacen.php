<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Capturar filtros
$buscar_folio = isset($_GET['buscar_folio']) ? $_GET['buscar_folio'] : '';
$buscar_cliente = isset($_GET['buscar_cliente']) ? $_GET['buscar_cliente'] : '';
$buscar_bodega = isset($_GET['buscar_bodega']) ? $_GET['buscar_bodega'] : '';
$buscar_solicitado = isset($_GET['buscar_solicitado']) ? $_GET['buscar_solicitado'] : '';
$buscar_producto = isset($_GET['buscar_producto']) ? $_GET['buscar_producto'] : '';

// Armar la consulta
$query = "SELECT DISTINCT sa.* 
          FROM salidas_almacen sa
          LEFT JOIN detalle_salidas_almacen dsa ON sa.id = dsa.id_salida
          WHERE 1";

if (!empty($buscar_folio)) {
    $query .= " AND sa.folio LIKE '%$buscar_folio%'";
}
if (!empty($buscar_cliente)) {
    $query .= " AND sa.nombre_cliente LIKE '%$buscar_cliente%'";
}
if (!empty($buscar_bodega)) {
    $query .= " AND sa.bodega = '$buscar_bodega'";
}
if (!empty($buscar_solicitado)) {
    $query .= " AND sa.solicitado_por LIKE '%$buscar_solicitado%'";
}
if (!empty($buscar_producto)) {
    $query .= " AND dsa.descripcion_producto LIKE '%$buscar_producto%'";
}

$query .= " ORDER BY sa.fecha_registro DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Monitor de Salidas de Almacén</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        h2 { text-align: center; color: #2c3e50; }
        form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        input, select { padding: 8px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #2980b9; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #3498db; color: white; }
        .volver {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .volver a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }
        .volver a:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<h2>📦 Monitor de Salidas de Almacén</h2>

<!-- Formulario de filtros -->
<form method="GET" action="monitor_salidas_almacen.php">
    <label>Buscar por Folio:</label>
    <input type="text" name="buscar_folio" value="<?php echo htmlspecialchars($buscar_folio); ?>">

    <label>Buscar por Cliente:</label>
    <input type="text" name="buscar_cliente" value="<?php echo htmlspecialchars($buscar_cliente); ?>">

    <label>Buscar por Bodega:</label>
    <select name="buscar_bodega">
        <option value="">-- Todas las Bodegas --</option>
        <option value="Brea" <?php if ($buscar_bodega == 'Brea') echo 'selected'; ?>>Brea</option>
        <option value="HP MPS" <?php if ($buscar_bodega == 'HP MPS') echo 'selected'; ?>>HP MPS</option>
        <option value="SIC" <?php if ($buscar_bodega == 'SIC') echo 'selected'; ?>>SIC</option>
    </select>

    <label>Buscar por Solicitado por:</label>
    <input type="text" name="buscar_solicitado" value="<?php echo htmlspecialchars($buscar_solicitado); ?>">

    <label>Buscar por Producto:</label>
    <input type="text" name="buscar_producto" value="<?php echo htmlspecialchars($buscar_producto); ?>">

    <button type="submit">🔎 Buscar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Folio</th>
            <th>Bodega</th>
            <th>Motivo</th>
            <th>Solicitado por</th>
            <th>Cliente</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['folio']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bodega']) . "</td>";
                echo "<td>" . htmlspecialchars($row['motivo_salida']) . "</td>";
                echo "<td>" . htmlspecialchars($row['solicitado_por']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron resultados.</td></tr>";
        }
        ?>
    </tbody>
</table>

<div class="volver">
    <a href="menu.php">🏠 Volver al Menú</a>
</div>

</body>
</html>
