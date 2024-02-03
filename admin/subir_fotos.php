<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

$nombre_galeria = isset($_GET['nombre_galeria']) ? $_GET['nombre_galeria'] : '';

// Validar existencia de la galería
$ruta_galeria = '../galerias/' . $nombre_galeria;
if (!file_exists($ruta_galeria)) {
    header("Location: error.php"); // Página de error o redirección al inicio
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurar límite de tiempo y memoria para la ejecución del script
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $fotos = $_FILES['fotos'];

    // Validar si se han seleccionado archivos
    if (empty($fotos['name'][0])) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona al menos una foto',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    } else {
        // Carpeta de destino para las fotos
        $destino = $ruta_galeria . '/';

        // Iterar sobre cada archivo seleccionado
        for ($i = 0; $i < count($fotos['name']); $i++) {
            $nombre_archivo = $fotos['name'][$i];
            $tipo_archivo = $fotos['type'][$i];
            $tamano_archivo = $fotos['size'][$i];
            $tmp_name = $fotos['tmp_name'][$i];

            // Validar tipo de archivo (puedes ajustar las extensiones permitidas según tus necesidades)
            $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

            if (in_array($extension, $extensiones_permitidas)) {
                // Mover el archivo a la carpeta de destino
                move_uploaded_file($tmp_name, $destino . $nombre_archivo);
            } else {
                // Tipo de archivo no permitido
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error: Archivo no permitido',
                            showConfirmButton: false,
                            timer: 1500
                        });
                      </script>";
            }
        }

        // Mensaje de éxito
        echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Fotos subidas con éxito',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Redireccionar a la página de la galería
                        window.location.href = 'ver_galeria.php?nombre_galeria=" . urlencode($nombre_galeria) . "';
                    });
                });
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Fotos a <?php echo $nombre_galeria; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            color: #007bff;
        }

        form {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <h2>Subir Fotos a <?php echo $nombre_galeria; ?></h2>

    <!-- Formulario para cargar fotos -->
    <form id="formularioSubida" action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fotos">Seleccionar Fotos:</label>
            <input type="file" name="fotos[]" id="fotos" multiple accept="image/*" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir Fotos</button>
    </form>

    <!-- Barra de progreso -->
    <div id="progressBarContainer" style="margin-top: 10px;">
        <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div id="progresoTexto" style="margin-top: 5px;"></div>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script personalizado para la carga de archivos -->
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var formulario = document.getElementById('formularioSubida');
    var inputArchivos = document.getElementById('fotos');
    var progressBar = document.getElementById('progressBar');
    var progresoTexto = document.getElementById('progresoTexto');

    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        var archivos = inputArchivos.files;
        var totalArchivos = archivos.length;
        var archivosProcesados = 0;

        function subirLote(start, end) {
            if (start < totalArchivos) {
                var archivosLote = Array.prototype.slice.call(archivos, start, end);
                var formData = new FormData();

                archivosLote.forEach(function (archivo) {
                    formData.append('fotos[]', archivo);
                });

                $.ajax({
                    url: 'subir_fotos.php?nombre_galeria=<?php echo $nombre_galeria; ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        archivosProcesados += archivosLote.length;
                        var progreso = (archivosProcesados / totalArchivos) * 100;
                        progressBar.style.width = progreso + '%';
                        progresoTexto.innerHTML = archivosProcesados + ' de ' + totalArchivos + ' archivos procesados';

                        subirLote(end, end + 5);  // Ajusta el tamaño del lote según tus necesidades

                        if (archivosProcesados === totalArchivos) {
                            // Mostrar mensaje de éxito con la cantidad de imágenes subidas
                            Swal.fire({
                                icon: 'success',
                                title: 'Fotos subidas con éxito',
                                text: 'Se han subido ' + archivosProcesados + ' imágenes.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function () {
                                window.location.href = 'ver_galeria.php?nombre_galeria=<?php echo urlencode($nombre_galeria); ?>';
                            });
                        }
                    },
                    error: function () {
                        // Manejar errores si es necesario
                    }
                });
            }
        }

        subirLote(0, 5);  // Inicia con los primeros 5 archivos (puedes ajustar según tus necesidades)
    });
});
    </script>

    <!-- Enlace para regresar al inicio -->
    <a href="index.php" class="regresar-inicio">Regresar al Inicio</a>
</body>
</html>