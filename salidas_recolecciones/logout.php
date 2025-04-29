<?php
session_start();
session_destroy(); // Elimina toda la sesión actual
header("Location: login.php"); // Redirige al login
exit();
?>
