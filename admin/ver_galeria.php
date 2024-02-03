<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

$nombre_galeria = $_GET['nombre_galeria'];
$ruta_galeria = '../galerias/' . $nombre_galeria;

// Validar existencia de la galería
if (!file_exists($ruta_galeria)) {
    echo "La galería '$nombre_galeria' no existe.";
    exit;
}

$files = glob($ruta_galeria . '/*');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_foto'])) {
        $foto_a_eliminar = $_POST['eliminar_foto'];

        // Validar que la foto a eliminar existe
        if (in_array($foto_a_eliminar, $files)) {
            // Eliminar la foto
            unlink($foto_a_eliminar);

            // Redireccionar para evitar reenviar el formulario al actualizar la página
            header("Location: ver_galeria.php?nombre_galeria=$nombre_galeria");
            exit;
        } else {
            echo "La foto seleccionada no existe.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Galería</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            color: #007bff;
        }

        .regresar-inicio {
            position: fixed;
            left: 20px;
            bottom: 20px;
            text-decoration: none;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .regresar-inicio:hover {
            background-color: #0056b3;
        }

        .galeria-fotos {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

.thumbnail {
    position: relative;
    width: 300px;
    height: 200px;
    overflow: hidden;
    border: 1px solid #ddd;
}

.thumbnail img {
    width: 100%;
    height: auto;
    transition: transform 0.3s;
}

.thumbnail:hover img {
    transform: rotate(5deg);
}

.close-container {
    position: absolute;
    top: 5px;
    right: 5px;
    z-index: 2; /* Asegura que el botón esté sobre la imagen */
}

.close {
    padding: 5px;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.thumbnail:hover .close {
    background-color: rgba(255, 255, 255, 1);
}


.close:hover {
    background-color: rgba(255, 255, 255, 1);
    z-index: 1;  /* Ajusta el índice z para asegurar la visibilidad */
}


        .slider-container {
            max-width: 600px;
            margin: 20px auto;
        }

        .slider {
            display: flex;
            overflow-x: auto;
            gap: 10px;
        }

        .slider img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Ver Galería: <?php echo $nombre_galeria; ?></h2>
    <div>
        <!-- Enlace para subir más fotos -->
        <a href="subir_fotos.php?nombre_galeria=<?php echo $nombre_galeria; ?>" class="btn btn-primary">Subir Más Fotos</a>

    </div>
    <br>
    <!-- Galería de fotos con Fancybox -->
    <div class="galeria-fotos">
        <?php foreach ($files as $file) : ?>
            <div class="thumbnail">
                <form method="post" style="margin: 0;" onsubmit="return eliminarImagen(this, '<?php echo $file; ?>');">
                    <input type="hidden" name="eliminar_foto" value="<?php echo $file; ?>">
                    <button type="submit" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <a data-fancybox="gallery" href="<?php echo $file; ?>">
                        <img src="<?php echo $file; ?>" alt="Foto">
                    </a>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Fancybox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Enlace para regresar al inicio -->
    <a href="index.php" class="regresar-inicio">Regresar al Inicio</a>

<script>
function eliminarImagen(form, fotoAEliminar) {
    // Mostrar SweetAlert2 para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la imagen permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, eliminar la imagen
            $.ajax({
                type: "POST",
                url: "eliminar_imagen.php",
                data: { eliminar_foto: fotoAEliminar },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            title: 'Eliminada',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false, // Ocultar el botón de confirmación
                            timer: 2000 // Esperar 2 segundos antes de cerrar automáticamente
                        });

                        // Esperar 2 segundos antes de recargar la página
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    // Mostrar mensaje de error en caso de fallo en la solicitud AJAX
                    Swal.fire('Error', 'Hubo un error al intentar eliminar la imagen.', 'error');
                }
            });
        }
    });

    // Evitar el envío del formulario
    return false;
}
</script>
</body>
</html>