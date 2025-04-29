<?php
session_start();
require_once 'conexion.php';

// Validar que exista el número de cliente
if (!isset($_GET['numero_cliente']) || empty($_GET['numero_cliente'])) {
    echo "<script>alert('No se recibió el número de cliente'); window.close();</script>";
    exit();
}

$numero_cliente = $_GET['numero_cliente'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nueva Dirección</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; }
        button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<h2>Agregar Nueva Dirección</h2>

<form id="formDireccion">
    <input type="hidden" name="numero_cliente" id="numero_cliente" value="<?php echo htmlspecialchars($numero_cliente); ?>">

    <label>Calle:</label>
    <input type="text" name="calle" id="calle" required>

    <label>Número:</label>
    <input type="text" name="numero" id="numero" required>

    <label>Interior (opcional):</label>
    <input type="text" name="interior" id="interior">

    <label>Municipio/Alcaldía:</label>
    <input type="text" name="municipio" id="municipio" required>

    <label>Estado:</label>
    <input type="text" name="estado" id="estado" required>

    <label>Código Postal:</label>
    <input type="text" name="cp" id="cp" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" id="telefono" required>

    <label>Referencias (opcional):</label>
    <textarea name="referencias" id="referencias"></textarea>

    <button type="button" onclick="guardarDireccion()">Guardar Dirección</button>
</form>

<script>
// Función para guardar nueva dirección usando AJAX
function guardarDireccion() {
    const formData = new FormData(document.getElementById('formDireccion'));

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "guardar_direccion.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);

            if (respuesta.status === "ok") {
                alert('✅ Dirección guardada correctamente');
                window.opener.buscarCliente(); // Recargar direcciones en la ventana principal
                window.close(); // Cerrar esta ventana
            } else {
                alert('❌ Error: ' + respuesta.mensaje);
            }
        }
    };
    xhr.send(formData);
}
</script>

</body>
</html>
