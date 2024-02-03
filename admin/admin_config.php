<?php
/*
 * Sistema de Galerías de Fotos
 * Desarrollador: Brach Gabriel
 * Versión: 1.0
 * Fecha de Creación: 01-02-2024
 * Contacto: [webmaster@cellcomweb.com.ar]
 */
// admin_config.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se han enviado datos de configuración
    if (isset($_POST['adminPhoneNumber'], $_POST['adminWhatsAppMessage'])) {
        // Actualiza la configuración del administrador en admin.php
        $newConfig = [
            'adminPhoneNumber' => $_POST['adminPhoneNumber'],
            'adminWhatsAppMessage' => $_POST['adminWhatsAppMessage']
        ];

        $configFile = 'admin.php';

        // Escribe el nuevo contenido en el archivo admin.php
        file_put_contents(
            $configFile,
            '<?php $adminConfig = ' . var_export($newConfig, true) . ';'
        );

        echo 'Configuración actualizada con éxito.';
    } else {
        echo 'Datos de configuración incompletos.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Configuración</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <!-- Botones en la parte superior derecha -->
        <div class="text-right mb-3">
            <a href="index.php" class="btn btn-primary mr-2">Regresar</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center">Configurar Número de Whatsapp del Administrador</h1>
<br>
        <h1 class="text-center">Anteponer 54 (ejemplo: 543482xxxxxx</h1>
<br>

    <!-- Formulario de configuración -->
    <!-- Formulario de configuración -->
    <form method="post" action="">
        <label for="adminPhoneNumber">Número de teléfono del administrador:</label>
        <input type="text" name="adminPhoneNumber" value="<?php echo $adminConfig['adminPhoneNumber']; ?>" required>

        <label for="adminWhatsAppMessage">Mensaje de WhatsApp del administrador:</label>
        <textarea name="adminWhatsAppMessage" required><?php echo $adminConfig['adminWhatsAppMessage']; ?></textarea>

        <button type="submit">Actualizar Configuración</button>
    </form>
</body>

</html>

<style>
    body {
        background-color: #f8f9fa;
        padding: 20px;
    }

    h1,
    h2 {
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