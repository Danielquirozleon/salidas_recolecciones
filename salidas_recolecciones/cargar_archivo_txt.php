<?php
session_start();
require 'conexion.php';

// Verificar sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Solo aceptar método POST y archivo .txt
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_txt'])) {
    
    $archivo = $_FILES['archivo_txt']['tmp_name'];

    if (mime_content_type($archivo) !== 'text/plain') {
        echo "<script>alert('Error: Solo se permiten archivos .txt'); window.history.back();</script>";
        exit();
    }

    // Abrir archivo
    $handle = fopen($archivo, "r");
    if ($handle === false) {
        echo "<script>alert('Error al abrir el archivo.'); window.history.back();</script>";
        exit();
    }

    while (($linea = fgets($handle)) !== false) {
        // Separar por tabulador
        $datos = explode("\t", trim($linea));

        if (count($datos) >= 2) {
            $codigo = $conexion->real_escape_string($datos[0]);
            $nombre = $conexion->real_escape_string($datos[1]);

            // Verificar si ya existe el producto
            $stmtCheck = $conexion->prepare("SELECT id FROM productos WHERE codigo_producto = ?");
            $stmtCheck->bind_param("s", $codigo);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result();

            if ($resCheck->num_rows == 0) {
                // Insertar producto nuevo
                $stmtInsert = $conexion->prepare("INSERT INTO productos (codigo_producto, nombre_producto) VALUES (?, ?)");
                $stmtInsert->bind_param("ss", $codigo, $nombre);
                $stmtInsert->execute();
            }
        }
    }

    fclose($handle);

    echo "<script>alert('Productos cargados correctamente.'); window.location.href='menu.php';</script>";
    exit();
} else {
    echo "<script>alert('Error al cargar el archivo.'); window.history.back();</script>";
}
?>
