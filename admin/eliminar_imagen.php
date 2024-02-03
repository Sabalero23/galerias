<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_foto'])) {
        $foto_a_eliminar = $_POST['eliminar_foto'];

        // Validar que la foto a eliminar existe
        if (file_exists($foto_a_eliminar)) {
            // Eliminar la foto
            unlink($foto_a_eliminar);
            echo json_encode(['success' => true, 'message' => 'La imagen ha sido eliminada exitosamente.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'La imagen seleccionada no existe.']);
            exit;
        }
    }
}

// Si la solicitud no es POST o no se proporcionó el parámetro 'eliminar_foto'
echo json_encode(['success' => false, 'message' => 'Solicitud no válida.']);
exit;
?>