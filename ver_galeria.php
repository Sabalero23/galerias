<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */
$nombre_galeria = $_GET['nombre_galeria'];
$ruta_galeria = 'galerias/' . $nombre_galeria;

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

        /* Estilo para la galería de fotos */
        .galeria-fotos {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .thumbnail {
            width: 300px;
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .thumbnail img {
            width: 100%;
            height: auto;
            transition: transform 0.3s;
        }
            .thumbnail:hover img {
        transform: rotate(5deg); /* Puedes ajustar los valores según tus preferencias */
    }

    .close {
        position: absolute;
        top: 5px;
        right: 5px;
        padding: 5px;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        cursor: pointer;
        transition: none; /* Evita que la transición afecte al botón de cerrar */
    }

    .close:hover {
        background-color: rgba(255, 255, 255, 1);
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
.slider {
    display: flex;
    overflow-x: auto;
    gap: 10px;
    scroll-behavior: smooth; /* Añade scroll suave */
    scroll-snap-type: x mandatory; /* Hace que el desplazamiento se ajuste a las posiciones de inicio/fin de las imágenes */
    -webkit-overflow-scrolling: touch; /* Añade soporte de desplazamiento suave en iOS */
    scroll-snap-points-x: repeat(100%); /* Asegura que cada imagen se detenga en la posición correcta */
    scroll-snap-stop: always;
    animation: sliderAnimation 2s infinite linear; /* Ajusta la duración de la transición */
}

@keyframes sliderAnimation {
    0%, 100% {
        transform: translateX(0);
    }
    20%, 25% {
        transform: translateX(calc(-100% + 10px)); /* Ajusta el porcentaje y el valor de píxeles según sea necesario */
    }
    40%, 45% {
        transform: translateX(calc(-200% + 20px)); /* Ajusta el porcentaje y el valor de píxeles según sea necesario */
    }
    60%, 65% {
        transform: translateX(calc(-300% + 30px)); /* Ajusta el porcentaje y el valor de píxeles según sea necesario */
    }
    80%, 85% {
        transform: translateX(calc(-400% + 40px)); /* Ajusta el porcentaje y el valor de píxeles según sea necesario */
    }
}

    </style>
</head>
<body>
    <h2>Galería: <?php echo $nombre_galeria; ?></h2>
<div>
            <!-- Botón para copiar la dirección -->
        <button id="copiarDireccion" class="btn btn-primary">Copiar Dirección</button>
</div>
<br>
<!-- Galería de fotos con Fancybox -->
    <div class="galeria-fotos">
        <?php foreach ($files as $file) : ?>
            <div class="thumbnail">
                <form method="post" style="margin: 0;">
                    <input type="hidden" name="eliminar_foto" value="<?php echo $file; ?>">
                    <button type="submit" class="close" aria-label="Close" style="position: absolute; top: 5px; right: 5px;">
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
   }

<script>
    document.getElementById('copiarDireccion').addEventListener('click', function() {
        // Obtener la URL actual
        var direccionActual = window.location.href;

        // Crear un elemento de texto temporal
        var tempInput = document.createElement('input');
        tempInput.value = direccionActual;

        // Agregar el elemento a la página
        document.body.appendChild(tempInput);

        // Seleccionar el contenido del campo de texto temporal
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); /* Para dispositivos móviles */

        // Copiar el contenido al portapapeles
        document.execCommand('copy');

        // Eliminar el elemento de texto temporal
        document.body.removeChild(tempInput);

        // Mostrar mensaje con SweetAlert2
        Swal.fire({
            title: 'Copiado',
            text: 'La dirección se ha copiado al portapapeles: ' + direccionActual,
            icon: 'success',
            timer: 2000 // Mostrar durante 2 segundos
        });
    });
</script>

</body>
</html>