<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
function obtenerNumeroFotos($nombre_galeria) {
    $ruta_galeria = 'galerias/' . $nombre_galeria;
    $fotos = glob($ruta_galeria . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    return count($fotos);
}

$galerias_recientes = array_slice(array_reverse(glob('galerias/*', GLOB_ONLYDIR)), 0, 3000);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para mostrar mensaje con SweetAlert2 -->
    <script>
        function mostrarMensaje(mensaje, tipo) {
            Swal.fire({
                icon: tipo,
                title: mensaje,
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h1, h2 {
            color: #007bff;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        /* Estilo adicional si es necesario */
    </style>

</head>
<body>
    <div class="container">
        <!-- Botones en la parte superior derecha -->
        <div class="text-right mb-3">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
        <h1 class="text-center">Bienvenido al Administrador de Galerías</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Galerías</h2>
                <ul>
                    <?php foreach ($galerias_recientes as $galeria) : ?>
                        <?php $nombre_galeria = basename($galeria); ?>
                        <li>
                            <a href="ver_galeria.php?nombre_galeria=<?php echo $nombre_galeria; ?>">
                                <?php echo $nombre_galeria; ?> (<?php echo obtenerNumeroFotos($nombre_galeria); ?> fotos)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            function mostrarMensaje(mensaje, tipo) {
                Swal.fire({
                    icon: tipo,
                    title: mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    </script>
</body>
</html>