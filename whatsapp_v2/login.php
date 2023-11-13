<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>

        body {
            background-size: cover; /* Ajusta el tamaño de la imagen al botón */
            background-position: center; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif; 
            margin: 0;
            flex-flow: column;

        }

        h1{
            color: white;
            font-size: 50px;
            z-index: 10;

        }

        /* Style for form container */
        .form-container {
            border-radius: 20px;
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            z-index: 10;

        }

        /* Style for form labels */
        label {
            display: block;
            margin-bottom: 5px;
        }

        /* Style for form input fields */
        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Style for error messages */
        .error-message {
            color: red;
            font-size: 14px;
        }

        /* Style for the submit button */
        input[type="submit"] {
            background-color: #fe57b1;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #CB458D;
        }
    </style>
</head>
<body>
    <video src="img/Grabación de pantalla 2023-11-13 a las 18.14.58.mov" autoplay muted loop style="position: absolute;"></video>
    <h1>Welcome!</h1><br>
    <div class="form-container">
        <form action="validacion.php" method="post">
            <!-- userName -->
            <?php if (isset($_GET['usernameVacio'])) {echo "<div class='error-message'>Username vacío. Has de introducir un username válido.</div>"; } ?>

            <?php if (isset($_GET['usernameMal'])) {echo "<div class='error-message'>Formato incorrecto de Username. Solo puede contener letras y números.</div>"; } ?>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="" value="<?php if(isset($_GET['username'])) {echo $_GET['username'];} ?>">

            <!-- nombre -->
            <?php if (isset($_GET['nombreVacio'])) {echo "<div class='error-message'>Nombre vacío. Has de introducir un nombre válido.</div>"; } ?>

            <?php if (isset($_GET['nombreMal'])) {echo "<div class='error-message'>El formato del nombre es incorrecto.</div>"; } ?>

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php if(isset($_GET["nombre"])) {echo $_GET["nombre"];} ?>">

            <!-- Email -->
            <?php if (isset($_GET['correoVacio'])) {echo "<div class='error-message'>Email vacío. Has de introducir un email válido.</div>"; } ?>

            <?php if (isset($_GET['correoMal'])) {echo "<div class='error-message'>El formato del email es incorrecto</div>"; } ?>

            <?php if (isset($_GET['correoExistente'])) {echo "<div class='error-message'>El correo ya existe.</div>"; } ?>

            <label for="correo">Email</label>
            <input type="text" name="correo" id="correo" value="<?php if(isset($_GET["correo"])) {echo $_GET["correo"];} ?>">

            <!-- Contraseña -->
            <?php if (isset($_GET['contrasenaVacio'])) {echo "<div class='error-message'>Contraseña vacía. Has de introducir una contraseña válida.</div>"; } ?>

            <?php if (isset($_GET['contrasenaMal'])) {echo "<div class='error-message'>El formato de la contraseña es incorrecto.</div>"; } ?>

            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" value="<?php if(isset($_GET["contrasena"])) {echo $_GET["contrasena"];} ?>">

            <br/><br>

            <input type="submit" name="enviar" value="Enviar" style="width: 100%;">

            <p style="text-align: center;">Ya tienes una cuenta? Inicia sesion <a href=" ./singup.php" style="color:#fe76c1">aqui</a></p>
        </form>
    </div>
</body>
</html>
