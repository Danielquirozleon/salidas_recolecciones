<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener último ID para folio consecutivo ALM
$result = $conn->query("SELECT id FROM salidas_almacen ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
$ultimo_id = $row ? $row['id'] + 1 : 1;
$folio = "ALM" . str_pad($ultimo_id, 5, '0', STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nueva Salida de Almacén</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f5f5f5; }
        form { background: white; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea, button { width: 100%; padding: 8px; margin-top: 8px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; }
        button { background-color: #007bff; color: white; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        #sugerencias div { padding: 5px; border-bottom: 1px solid #eee; cursor: pointer; }
        #sugerencias div:hover { background-color: #e0e0e0; }
    </style>
</head>
<body>

<h2 style="text-align: center;">Registrar Nueva Salida de Almacén</h2>

<form action="guardar_salida_almacen.php" method="POST" onsubmit="return prepararProductos()">

    <!-- Folio generado -->
    <strong>Folio generado:</strong> <?php echo $folio; ?>
    <input type="hidden" name="folio" value="<?php echo $folio; ?>">
    <br><br>

    <!-- Bodega -->
    <label>🏢 Bodega:</label>
    <select name="bodega" required>
        <option value="">-- Selecciona la bodega --</option>
        <option value="Brea">Brea</option>
        <option value="HP MPS">HP MPS</option>
        <option value="SIC">SIC</option>
    </select>

    <!-- Motivo -->
    <label>📋 Motivo de Salida:</label>
    <select name="motivo_salida" required>
        <option value="">-- Selecciona el motivo --</option>
        <option value="Cambio físico">Cambio físico</option>
        <option value="Complemento de entrega">Complemento de entrega</option>
        <option value="Devolución a proveedor">Devolución a proveedor</option>
        <option value="Garantía">Garantía</option>
        <option value="Traslado interno">Traslado interno</option>
    </select>

    <!-- Datos principales -->
    <label>Solicitado por:</label>
    <input type="text" name="solicitado_por" required>

    <label>Dirigido a:</label>
    <input type="text" name="dirigido_a" required>

    <label>Número de cliente:</label>
    <input type="text" name="numero_cliente" id="numero_cliente" onblur="buscarCliente()" required>

    <label>Nombre del cliente:</label>
    <input type="text" name="nombre_cliente" id="nombre_cliente" readonly>

    <label>Dirección de destino:</label>
    <input type="text" name="direccion_cliente" id="direccion_cliente" required>

    <label>Teléfono de destino:</label>
    <input type="text" name="telefono_destino" required>

    <label>Destinatario:</label>
    <input type="text" name="destinatario" required>

    <!-- Productos -->
    <h3>📦 Agregar Productos</h3>

    <label>Buscar producto:</label>
    <input type="text" id="buscar_producto" onkeyup="buscarProducto()" placeholder="Buscar por código o nombre...">
    <div id="sugerencias" style="border: 1px solid #ccc; max-height: 150px; overflow-y: auto; display: none;"></div>

    <input type="hidden" id="codigo_producto">
    <label>Descripción:</label>
    <input type="text" id="descripcion_producto" readonly>

    <label>Cantidad:</label>
    <input type="number" id="cantidad_producto" min="1">

    <button type="button" onclick="agregarProducto()">➕ Agregar Producto</button>

    <!-- Tabla productos agregados -->
    <h3>Productos Agregados</h3>
    <table id="tabla_productos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <label>📝 Comentarios:</label>
    <textarea name="comentarios" rows="4" placeholder="Comentario opcional..."></textarea>

    <input type="hidden" name="productos_json" id="productos_json">

    <button type="submit">Guardar salida</button>
    <a href="menu.php"><button type="button">Cancelar / Regresar</button></a>

</form>

<!-- Scripts -->
<script>
function buscarProducto() {
    const busqueda = document.getElementById('buscar_producto').value;
    const sugerencias = document.getElementById('sugerencias');

    if (busqueda.length < 2) {
        sugerencias.innerHTML = '';
        sugerencias.style.display = 'none';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "buscar_productos.php?query=" + encodeURIComponent(busqueda), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const resultados = JSON.parse(xhr.responseText);
            sugerencias.innerHTML = '';
            sugerencias.style.display = resultados.length > 0 ? 'block' : 'none';

            resultados.forEach(function(producto) {
                const div = document.createElement('div');
                div.textContent = producto.codigo_producto + " - " + producto.nombre_producto;
                div.onclick = function() {
                    document.getElementById('codigo_producto').value = producto.codigo_producto;
                    document.getElementById('descripcion_producto').value = producto.nombre_producto;
                    sugerencias.innerHTML = '';
                    sugerencias.style.display = 'none';
                };
                sugerencias.appendChild(div);
            });
        }
    };
    xhr.send();
}

let productos = [];

function agregarProducto() {
    const codigo = document.getElementById('codigo_producto').value;
    const descripcion = document.getElementById('descripcion_producto').value;
    const cantidad = document.getElementById('cantidad_producto').value;

    if (!codigo || !descripcion || cantidad <= 0) {
        alert("Debes seleccionar un producto y una cantidad válida.");
        return;
    }

    const existente = productos.find(prod => prod.codigo === codigo);
    if (existente) {
        alert("Este producto ya fue agregado.");
        return;
    }

    productos.push({ codigo: codigo, descripcion: descripcion, cantidad: cantidad });
    mostrarProductos();
    limpiarCamposProducto();
}

function mostrarProductos() {
    const tbody = document.getElementById('tabla_productos').getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';

    productos.forEach(function(prod, index) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${prod.codigo}</td>
            <td>${prod.descripcion}</td>
            <td>${prod.cantidad}</td>
            <td><button type="button" onclick="eliminarProducto(${index})">🗑️ Eliminar</button></td>
        `;
        tbody.appendChild(row);
    });
}

function eliminarProducto(index) {
    productos.splice(index, 1);
    mostrarProductos();
}

function limpiarCamposProducto() {
    document.getElementById('codigo_producto').value = '';
    document.getElementById('descripcion_producto').value = '';
    document.getElementById('cantidad_producto').value = '';
}

function prepararProductos() {
    if (productos.length === 0) {
        alert("Debes agregar al menos un producto.");
        return false;
    }

    document.getElementById('productos_json').value = JSON.stringify(productos);
    return true;
}
</script>

</body>
</html>
