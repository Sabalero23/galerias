<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */

session_start();

$archivoUsuarios = 'usuarios_data.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Obtener la lista actual de usuarios
$listaUsuarios = [];
if (file_exists($archivoUsuarios)) {
    $listaUsuarios = require $archivoUsuarios;
}

// Procesar la creación de un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearUsuario'])) {
    $nuevoUsuario = [
        'id' => uniqid(), // Puedes generar un ID único aquí
        'nombre' => $_POST['nombreNuevoUsuario'],
        'password' => password_hash($_POST['contrasenaNuevoUsuario'], PASSWORD_DEFAULT),
    ];

    // Agregar el nuevo usuario a la lista
    $listaUsuarios[] = $nuevoUsuario;

    // Guardar la lista actualizada en usuarios_data.php
    file_put_contents($archivoUsuarios, '<?php return ' . var_export($listaUsuarios, true) . ';');

    // Definir mensaje de éxito
    $mensajeExitoCrear = 'Usuario creado con éxito';
}

// Procesar la eliminación de un usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarUsuario'])) {
    $idUsuarioEliminar = $_POST['idUsuarioEliminar'];

    // Filtrar la lista para eliminar el usuario por su ID
    $listaUsuarios = array_filter($listaUsuarios, function ($usuario) use ($idUsuarioEliminar) {
        return $usuario['id'] !== $idUsuarioEliminar;
    });

    // Guardar la lista actualizada en usuarios_data.php
    file_put_contents($archivoUsuarios, '<?php return ' . var_export($listaUsuarios, true) . ';');

    // Definir mensaje de éxito
    $mensajeExitoEliminar = 'Usuario eliminado con éxito';

    // Enviar una respuesta JSON con el mensaje de éxito
    echo json_encode(['mensajeExitoEliminar' => $mensajeExitoEliminar]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Estilos adicionales de index.php -->
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
    </style>
</head>
<body>
        <!-- Botones en la parte superior derecha -->
        <div class="text-right mb-3">
            <a href="index.php" class="btn btn-primary mr-2">Regresar</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    <div class="container mt-5">
        <h1 class="mb-4">Administración de Usuarios</h1>
        <br>
        <h2 class="mt-4" id="nuevoUsuarioTitle">Nuevo Usuario</h2>
        <p>(Click para crear usuario)</p>
        <!-- Formulario para crear un nuevo usuario -->
        <form id="formNuevoUsuario" action="usuarios.php" method="POST" style="display: none;">
            <div class="form-group">
                <label for="nombreNuevoUsuario">Nombre de Usuario:</label>
                <input type="text" name="nombreNuevoUsuario" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contrasenaNuevoUsuario">Contraseña:</label>
                <input type="password" name="contrasenaNuevoUsuario" class="form-control" required>
            </div>
            <button type="submit" name="crearUsuario" class="btn btn-primary">Crear Usuario</button>
        </form>

        <!-- Lista de usuarios -->
        <h2 class="mt-4">Lista de Usuarios</h2>
        <ul>
            <?php foreach ($listaUsuarios as $usuario) : ?>
                <li>
                    <?php echo $usuario['nombre']; ?>
                    <form id="formEliminarUsuario_<?php echo $usuario['id']; ?>" class="d-inline">
                        <input type="hidden" name="idUsuarioEliminar" value="<?php echo $usuario['id']; ?>">
                        <button type="button" class="btn btn-danger btn-sm ml-2" onclick="confirmarEliminacion('<?php echo $usuario['id']; ?>')">Eliminar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tu código JavaScript -->
    <script>
        // Mostrar y ocultar el formulario de nuevo usuario al hacer clic en el título
        $(document).ready(function () {
            $("#nuevoUsuarioTitle").click(function () {
                $("#formNuevoUsuario").toggle();
            });
        });

        function confirmarEliminacion(idUsuario) {
            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el usuario. No podrás deshacer esta acción.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar usuario',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar la eliminación solo si se confirma en el lado del cliente
                    $.ajax({
                        type: 'POST',
                        url: 'usuarios.php',
                        data: { eliminarUsuario: true, idUsuarioEliminar: idUsuario },
                        dataType: 'json',
                        success: function (response) {
                            // Redirigir a usuarios.php después de eliminar y mostrar el mensaje de éxito
                            window.location.href = 'usuarios.php';
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar usuario',
                                text: 'Ha ocurrido un problema al eliminar el usuario. Inténtalo de nuevo.',
                            });
                        }
                    });
                }
            });
        }
    </script>

</body>
</html>