<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

// Verifica si se proporciona el nombre de la galería a eliminar
if (isset($_GET['nombre_galeria'])) {
    $nombreGaleria = $_GET['nombre_galeria'];

    // Ruta de la galería
    $rutaGaleria = '../galerias/' . $nombreGaleria;

    // Lógica para eliminar la galería
    if (eliminarGaleria($rutaGaleria)) {
        // Si la eliminación es exitosa
        $response = array('status' => 'success', 'message' => 'Galería eliminada con éxito');
    } else {
        // Si hay un error en la eliminación
        $response = array('status' => 'error', 'message' => 'Error al eliminar la galería');
    }
} else {
    // Si no se proporciona el nombre de la galería
    $response = array('status' => 'error', 'message' => 'Nombre de la galería no proporcionado');
}

// Devuelve el resultado como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Función para eliminar la galería y su contenido
function eliminarGaleria($ruta)
{
    if (is_dir($ruta)) {
        // Elimina todos los archivos y subdirectorios de la galería
        $files = array_diff(scandir($ruta), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$ruta/$file")) ? eliminarGaleria("$ruta/$file") : unlink("$ruta/$file");
        }

        // Elimina la galería
        return rmdir($ruta);
    }

    return false;
}
?>