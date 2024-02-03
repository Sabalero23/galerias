<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Galería</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

        /* Nuevo estilo para el enlace de regreso */
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
    <div class="container">
        <h2 class="text-center">Crear Galería</h2>
        <form action="crear_galeria.php" method="POST">
            <div class="form-group">
                <label for="nombre_galeria">Nombre de la Galería:</label>
                <input type="text" name="nombre_galeria" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre_galeria = $_POST['nombre_galeria'];

                if (!empty($nombre_galeria)) {
                    $ruta_galeria = '../galerias/' . $nombre_galeria;
                    if (!file_exists($ruta_galeria)) {
                        mkdir($ruta_galeria);
                        echo "Swal.fire({
                                icon: 'success',
                                title: 'Galería creada con éxito',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = 'index.php'; // Redirigir a index.php
                            });";
                    } else {
                        echo "Swal.fire({
                                icon: 'warning',
                                title: 'La galería ya existe',
                                showConfirmButton: false,
                                timer: 1500
                            });";
                    }
                } else {
                    echo "Swal.fire({
                            icon: 'error',
                            title: 'Ingresa un nombre para la galería',
                            showConfirmButton: false,
                            timer: 1500
                        });";
                }
            }
        ?>
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 
</body>
</html>