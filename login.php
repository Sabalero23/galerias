<?php
session_start();

$archivoUsuarios = 'admin/usuarios_data.php';

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

// Función para verificar las credenciales del usuario
function verificarCredenciales($nombreUsuario, $contrasena, $usuarios) {
    foreach ($usuarios as $usuario) {
        if ($usuario['nombre'] === $nombreUsuario && password_verify($contrasena, $usuario['password'])) {
            return true;
        }
    }
    return false;
}

// Procesar el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = $_POST['contrasena'];

    // Validación del formulario
    if (empty($nombreUsuario) || empty($contrasena)) {
        $mensajeError = 'Por favor, completa todos los campos.';
    } else {
        // Crear el archivo usuarios_data.php y el usuario inicial si no existe
        if (!file_exists($archivoUsuarios)) {
            $usuarioInicial = [
                'id' => 1,
                'nombre' => 'admin',
                'password' => password_hash('password', PASSWORD_DEFAULT),
            ];

            file_put_contents($archivoUsuarios, '<?php return [' . PHP_EOL . var_export($usuarioInicial, true) . PHP_EOL . '];');
        }

        // Obtener la lista actual de usuarios
        $listaUsuarios = require $archivoUsuarios;

        // Verificar las credenciales del usuario
        if (verificarCredenciales($nombreUsuario, $contrasena, $listaUsuarios)) {
            // Iniciar sesión y redirigir a la página principal
            $_SESSION['usuario'] = $nombreUsuario;
            $response = ['status' => 'success', 'redirect' => 'index.php'];
        } else {
            $mensajeError = 'Credenciales incorrectas. Inténtalo de nuevo.';
            $response = ['status' => 'error', 'message' => $mensajeError];
        }

        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(); // Salir para evitar cualquier salida adicional no deseada
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }

        /* Estilo adicional para ocultar el formulario de solicitud por defecto */
        #solicitudForm {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Iniciar Sesión</h1>

        <?php if (isset($mensajeError)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $mensajeError; ?>
            </div>
        <?php endif; ?>

        <!-- Botón para mostrar el formulario de solicitud -->
        <button class="btn btn-secondary mb-3" onclick="mostrarSolicitudForm()">Solicitar Usuario</button>

        <!-- Formulario de solicitud (inicialmente oculto) -->
        <form action="solicitud_usuario.php" method="POST" id="solicitudForm">
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellidoNombre">Apellido y Nombre:</label>
                <input type="text" name="apellidoNombre" id="apellidoNombre" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>

        <!-- Formulario de inicio de sesión -->
        <form action="login.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario:</label>
                <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para SweetAlert2 y mostrar/ocultar formularios -->
    <script>
        function mostrarSolicitudForm() {
            // Ocultar el formulario de inicio de sesión
            document.getElementById('loginForm').style.display = 'none';

            // Mostrar el formulario de solicitud
            document.getElementById('solicitudForm').style.display = 'block';
        }
// Script para SweetAlert2 y mostrar/ocultar formularios
$(document).ready(function () {
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Inicio de sesión exitoso',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        // Redirigir a la página especificada
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la solicitud al servidor'
                });
            }
        });
    });
});
    </script>
</body>
</html>