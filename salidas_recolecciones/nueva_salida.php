<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Generar folio automático
$folio = "SAL-" . date("Ymd") . "-" . rand(1000, 9999);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nueva Salida</title>
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

<h2 style="text-align: center;">Registrar Nueva Salida</h2>


<form action="guardar_salida.php" method="POST" onsubmit="return prepararProductos()">

    <!-- Tipo de salida -->
    <label>⚙️ Tipo de salida:</label>
    <select name="tipo_salida" id="tipo_salida" required onchange="mostrarSecciones()">
        <option value="">-- Selecciona --</option>
        <option value="afecta">Afectando el sistema</option>
        <option value="no_afecta">Sin afectar el sistema</option>
    </select>

    <!-- Folio generado -->
    <strong>Folio generado:</strong> <?php echo $folio; ?>
    <input type="hidden" name="folio" value="<?php echo $folio; ?>">
    <br>
    <br>
    <!-- Datos principales -->
    <label>Solicitado por:</label>
    <input type="text" name="solicitado_por" required>

    <label>Dirigido a:</label>
    <input type="text" name="dirigido_a" required>

    <label>Número de cliente:</label>
    <input type="text" name="numero_cliente" id="numero_cliente" onblur="buscarCliente()" required>

    <label>Nombre del cliente:</label>
    <input type="text" name="nombre_cliente" id="nombre_cliente" readonly>

    <!-- Selección de dirección -->
    <div id="seccion_direccion" style="display: none;">
        <label>Dirección:</label>
        <select name="direccion_cliente" id="direccion_cliente">
            <option value="">-- Selecciona una dirección --</option>
        </select>
        <button type="button" onclick="abrirNuevaDireccion()">➕ Agregar nueva dirección</button>
    </div>

    <!-- Campo extra para folio de afectación -->
    <div id="campo_folio_afectacion" style="display:none;">
        <label>Folio de afectación (folio externo):</label>
        <input type="text" name="folio_afectacion" id="folio_afectacion">
    </div>

    <!-- Productos (solo si es salida "sin afectar") -->
    <div id="productos_section" style="display:none;">
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
    </div>

    <label>📝 Comentario:</label>
    <textarea name="comentario" rows="4" placeholder="Comentario opcional..."></textarea>

    <input type="hidden" name="productos_json" id="productos_json">

    <button type="submit">Guardar salida</button>
    <a href="menu.php"><button type="button">Cancelar / Regresar</button></a>

</form>

<!-- Scripts -->
<script>
// Mostrar/ocultar secciones según tipo de salida
function mostrarSecciones() {
    const tipo = document.getElementById('tipo_salida').value;
    document.getElementById('productos_section').style.display = (tipo === 'no_afecta') ? 'block' : 'none';
    document.getElementById('campo_folio_afectacion').style.display = (tipo === 'afecta') ? 'block' : 'none';
    document.getElementById('seccion_direccion').style.display = 'block'; // dirección siempre visible
}

// Buscar cliente y llenar datos
function buscarCliente() {
    const numero = document.getElementById('numero_cliente').value;
    if (numero.length > 0) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'buscar_cliente.php?numero_cliente=' + encodeURIComponent(numero), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const datos = JSON.parse(xhr.responseText);
                document.getElementById('nombre_cliente').value = datos.nombre_cliente || "";

                const selectDireccion = document.getElementById('direccion_cliente');
                selectDireccion.innerHTML = '<option value="">-- Selecciona una dirección --</option>';
                datos.direcciones.forEach(function(direccion) {
                    const option = document.createElement('option');
                    option.value = direccion;
                    option.textContent = direccion;
                    selectDireccion.appendChild(option);
                });
            }
        };
        xhr.send();
    }
}

// Abrir ventana para agregar nueva dirección
function abrirNuevaDireccion() {
    const numero = document.getElementById('numero_cliente').value;
    if (!numero) {
        alert("Primero debes llenar el número de cliente.");
        return;
    }
    window.open('nueva_direccion.php?numero_cliente=' + encodeURIComponent(numero), "Agregar Dirección", "width=600,height=400");
}

// Buscar producto
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

// Agregar productos
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

// Preparar productos para enviar
function prepararProductos() {
    if (document.getElementById('tipo_salida').value === 'no_afecta' && productos.length === 0) {
        alert("Debes agregar al menos un producto.");
        return false;
    }

    document.getElementById('productos_json').value = JSON.stringify(productos);
    return true;
}
</script>

</body>
</html>
