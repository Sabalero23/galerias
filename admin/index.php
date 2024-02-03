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
    $ruta_galeria = '../galerias/' . $nombre_galeria;
    $fotos = glob($ruta_galeria . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    return count($fotos);
}

$galerias_recientes = array_slice(array_reverse(glob('../galerias/*', GLOB_ONLYDIR)), 0, 3);

// Inicializar la variable $nombre_galeria_busqueda
$nombre_galeria_busqueda = '';



// Procesar la búsqueda de galerías
$resultados_busqueda = [];

if (isset($_GET['nombre_galeria'])) {
    $nombre_galeria_busqueda = strtolower($_GET['nombre_galeria']); // Convertir la búsqueda a minúsculas

    // Obtener todas las galerías recientes
    $galerias_recientes = array_slice(array_reverse(glob('../galerias/*', GLOB_ONLYDIR)), 0, 3);

    // Filtrar las galerías recientes basadas en la búsqueda
    $galerias_encontradas = array_filter($galerias_recientes, function ($galeria) use ($nombre_galeria_busqueda) {
        // Convertir el nombre de la galería a minúsculas antes de comparar
        $nombre_galeria = strtolower(basename($galeria));
        return strpos($nombre_galeria, $nombre_galeria_busqueda) !== false;
    });

    // Procesar las galerías encontradas
    foreach ($galerias_encontradas as $galeria) {
        $nombre_galeria = basename($galeria);
        $resultados_busqueda[] = [
            'nombre' => $nombre_galeria,
            'numero_fotos' => obtenerNumeroFotos($nombre_galeria),
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Galerías</title>

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

        .resultados-busqueda {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Botones en la parte superior derecha -->
        <div class="text-right mb-3">
            <a href="usuarios.php" class="btn btn-primary mr-2">Usuarios</a>
            <a href="admin_config.php" class="btn btn-primary mr-3">Nro Whatsapp</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center">Bienvenido al Administrador de Galerías</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Últimas Galerías Creadas</h2>
                <ul>
                    <?php foreach ($galerias_recientes as $galeria) : ?>
                        <?php $nombre_galeria = basename($galeria); ?>
                        <li>
    <a href="ver_galeria.php?nombre_galeria=<?php echo $nombre_galeria; ?>">
        <?php echo $nombre_galeria; ?> (<?php echo obtenerNumeroFotos($nombre_galeria); ?> fotos)
    </a>
    <button class="btn btn-danger btn-sm ml-2 eliminar-galeria" data-nombre="<?php echo $nombre_galeria; ?>">Eliminar</button>
</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            


            <div class="col-md-6">
    <!-- Agregar el botón "Crear Galería" y el modal -->
    <button type="button" class="btn btn-success mt-3" data-toggle="modal" data-target="#crearGaleriaModal">
        Crear Galería
    </button>
<br>
                <h2>Buscar Galería por Nombre</h2>
                <form action="index.php" method="GET">
                    <div class="form-group">
                        <label for="buscar_galeria">Nombre de la Galería:</label>
                        <input type="text" name="nombre_galeria" id="buscar_galeria" class="form-control" value="<?php echo $nombre_galeria_busqueda; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>

        <div class="resultados-busqueda">
            <?php if (!empty($resultados_busqueda)) : ?>
                <h2>Resultados de la Búsqueda</h2>
                <ul>
                    <?php foreach ($resultados_busqueda as $resultado) : ?>
                        <li>
                            <a href="ver_galeria.php?nombre_galeria=<?php echo $resultado['nombre']; ?>">
                                <?php echo $resultado['nombre']; ?> (<?php echo $resultado['numero_fotos']; ?> fotos)
                            </a>
                            <button class="btn btn-primary btn-sm ml-2 copiar-enlace" data-enlace="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/ver_galeria.php?nombre_galeria=' . $resultado['nombre']; ?>">Copiar Enlace</button>
                            <a href="index.php?eliminar_galeria=<?php echo $resultado['nombre']; ?>" class="btn btn-danger btn-sm ml-2 eliminar-galeria">Eliminar</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Puedes buscar Galerías por su nombre.</p>
                <!-- Agregar esta línea para depuración -->
                <p> <?php print_r($galerias_encontradas); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para crear galería -->
    <div class="modal fade" id="crearGaleriaModal" tabindex="-1" role="dialog" aria-labelledby="crearGaleriaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearGaleriaModalLabel">Crear Nueva Galería</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('crear_galeria.php'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <!-- Puedes agregar botones adicionales según tu necesidad -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

    document.addEventListener('DOMContentLoaded', function () {
        // Captura del evento clic en el botón eliminar-galeria
        document.querySelectorAll('.eliminar-galeria').forEach(function (button) {
            button.addEventListener('click', function () {
                var nombreGaleria = button.getAttribute('data-nombre');

                // Mostrar SweetAlert2 para confirmar la eliminación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'La galería será eliminada permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar una solicitud fetch para manejar la respuesta
                        fetch('eliminar_galeria.php?nombre_galeria=' + nombreGaleria)
                            .then(response => response.json())
                            .then(data => {
                                // Mostrar mensaje de éxito o error
                                if (data.status === 'success') {
                                    mostrarMensaje('Galería eliminada con éxito', 'success');

                                    // Puedes agregar aquí redirección o cualquier otra acción después del éxito
                                    // Por ejemplo, recargar la página para actualizar la lista de galerías
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    mostrarMensaje('Error al eliminar la galería', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error al procesar la solicitud:', error);
                                mostrarMensaje('Error en la solicitud al servidor', 'error');
                            });
                    }
                });
            });
        });
    });
</script>
</body>

</html>