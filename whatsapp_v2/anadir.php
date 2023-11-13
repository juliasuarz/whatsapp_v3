<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// iniciamos la sesion
session_start();
// comprobamos que el username no esta vacio
if (!isset($_SESSION['username'])) {
    echo "Usuario vacío";
    // header('Location: '.'./login.php');
    exit();
} else {
    // añadimosla conexion con la base de datos
    include_once("./conexion.php");

    // usamos la funcion  mysqli_real_escape_string para asegurarnos que los datos solo son una cadena de texto
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    $nombre = mysqli_real_escape_string($conn, $_SESSION['nombre']);
    $correo = mysqli_real_escape_string($conn, $_SESSION['correo']);
    $contrasena = mysqli_real_escape_string($conn, $_SESSION['contrasena']);
    // $contrasenaEncriptada =hash("sha256", $contrasena);
    // encriptamos la contraseña del usuario con el método BCRYPT
    $contrasenaEncriptada = password_hash($contrasena, PASSWORD_BCRYPT);
    

    try {
        // desactivamos la funcion de que las sentencias sql se ejecuten de manera independiente
        mysqli_autocommit($conn, false);
        // solo si todas las sentencias son correctas se ejecutarán, si alguna falla no.
        mysqli_begin_transaction($conn);

        // Se define la primera consulta
        $stmt1 = mysqli_stmt_init($conn);
        $sqlUsuario = "INSERT INTO tbl_usuarios (id_user, username, nombre, correo, contrasena) VALUES (NULL, ?, ?, ?, ?)";
        mysqli_stmt_prepare($stmt1, $sqlUsuario);
        // asociamos las variables a los parametros que vamos a introducir que hemos definido en la sentencia
        // especificamos cual es la sentencia, el tipo de parametro, y cuales son las variable
        mysqli_stmt_bind_param($stmt1, "ssss", $username, $nombre, $correo, $contrasenaEncriptada);
        // la ejecutamos
        mysqli_stmt_execute($stmt1);

        // si al ejecutarla se ha modificado un row entramos en el if
        if (mysqli_stmt_affected_rows($stmt1) == 1) {
            echo "Usuario insertado correctamente en la tabla de usuarios<br>";

            // Recuperamos el ID generado en el insert de la primera tabla
            $lastid = mysqli_insert_id($conn);

            // Confirmamos la consulta
            mysqli_commit($conn);

            // Cerramos la conexión
            mysqli_stmt_close($stmt1);

            // Destruimos las variables de sesión y la sesión
            session_unset();
            session_destroy();

            echo "Usuario registrado";
            // Redirigimos a la home 
            header('Location: ./home.php');
        } else {
            // en caso de que no se haya ejecutado correctamente mostramos un mensaje al usuario
            echo "Error al insertar el usuario en la tabla de usuarios";
        }
    } catch (Exception $e) {
        // Deshacemos la inserción en el caso de que se genere alguna excepción
        mysqli_rollback($conn);
        echo "Error al agregar el usuario nuevo: " . $e->getMessage();
        die();
    }
}
?>
    
</body>
</html>

