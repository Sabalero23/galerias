<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */
// solicitud_usuario.php

// Incluye el archivo admin.php
include 'admin/admin.php';

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén datos del formulario
    $correoUsuario = $_POST['correo'];
    $apellidoNombre = $_POST['apellidoNombre'];

    // Validación y seguridad (puedes agregar más validaciones según tus necesidades)

    // Mensaje de la solicitud
    $messageBody = "Nueva solicitud de usuario:\nCorreo: $correoUsuario\nApellido y Nombre: $apellidoNombre";

    // Construir el enlace de WhatsApp con el número y el mensaje
    $whatsappLink = "https://wa.me/{$adminConfig['adminPhoneNumber']}?text=" . urlencode($messageBody) . "&phone={$adminConfig['adminPhoneNumber']}";

    // Redirigir al enlace de WhatsApp
    header("Location: $whatsappLink");
    exit;
} else {
    // Acceso no permitido directo al script
    echo 'Acceso no permitido.';
}