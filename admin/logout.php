<?php
session_start();

// Cerrar sesión y redirigir a la página de inicio de sesión
session_destroy();
header('Location: login.php');
exit();
?>