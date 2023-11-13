<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobación de usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <video src="img/Grabación de pantalla 2023-11-13 a las 18.14.58.mov" autoplay muted loop style="position: absolute;"></video>

    <div class="form-container">
        <h2>Comprobación de usuario</h2>
        <?php
        // recogemos el valor de las variables y las mostramos
        $username = $_POST['username'];
        echo "Username: " . $username . "<br/><br/>";

        $nombre = $_POST['nombre'];
        echo "Nombre: " . $nombre . "<br/><br/>";

        $correo = $_POST['correo'];
        echo "Correo: " . $correo . "<br/><br/>";

        $contrasena = $_POST['contrasena'];
        echo "Contraseña: " . $contrasena . "<br/>";
        ?>
        <br>
        <!-- si el usuario quiere cambiar algún dato lo redirigimos a la pagina de login -->
        <form action="login.php" method="get" style="padding: 15px;">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
            <input type="hidden" name="correo" value="<?php echo $correo ?>">
            <input type="hidden" name="contrasena" value="<?php echo $contrasena ?>">
            <input type="submit"  value="Modificar usuario" >
        </form>
        <!-- si el usuario no necesita modificar ningún dato lo añadimos a la bd -->
        <form action="anadir.php" method="post" style="padding: 15px;">
            <input type="hidden" name="usuariook" value="usuariook">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
            <input type="hidden" name="correo" value="<?php echo $correo ?>">
            <input type="hidden" name="contrasena" value="<?php echo $contrasena ?>">
            <input type="submit" class="realizar" name="pedido" value="Realizar usuario">
        </form>
        
    </div>
</body>
</html>
