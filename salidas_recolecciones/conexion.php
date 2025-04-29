<?php
// Datos de conexión
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'salidas'; // Asegúrate que tu base se llame así

// Crear conexión
$conn = new mysqli('localhost', 'root', '', 'salidas', 3307); // o el puerto correcto

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
