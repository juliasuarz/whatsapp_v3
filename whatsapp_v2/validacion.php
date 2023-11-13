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
// si no ha llegado aquí pulsando el botón enviar, le dirigimos a login.php
if (!filter_has_var(INPUT_POST, 'enviar')) {
    header('Location: '.'./login.php');
    exit();
} else {
// iniciamos la sesión
    session_start(); 

// conexion con la bd
include_once("./conexion.php");

// recogemos las variables e iniciamos las sesiones
    $_SESSION['usuariook'] = isset($_POST['usuariook']) ? $_POST['usuariook'] : "";
    $_SESSION['username'] = isset($_POST['username']) ? $_POST['username'] : "";
    $_SESSION['nombre'] = isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $_SESSION['correo'] = isset($_POST['correo']) ? $_POST['correo'] : "";
    $_SESSION['contrasena'] = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";
 
// creamos las variables correspondientes
    $errores = "";
    $username = $_POST['username'];
    $correo = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];

    // creamos una función para que nos atude a validar los campos que ha rellenado el usuario
    function validaCampoVacio($campo) {
        if (empty($campo)) {
            return true; // Hay un error
        } else {
            return false; // No hay un error
        }
    }
    // require_once('./funciones.php');

    // con la funcion anterior validamos los campos y guardamos los errores en la variable errores que hemos creado antes
    if (validaCampoVacio($username)) {
        if (!$errores) {
            $errores .= "?usernameVacio=true";
        } else {
            $errores .= "&usernameVacio=true";
        }
    } else {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            if (!$errores) {
                $errores .= "?usernameMal=true";
            } else {
                $errores .= "&usernameMal=true";
            }
        }
    }

    if (validaCampoVacio($nombre)) {
        if (!$errores) {
            $errores .= "?nombreVacio=true";
        } else {
            $errores .= "&nombreVacio=true";
        }
    } else {
        if (!preg_match("/^[a-zA-Z]*$/", $nombre)) {
            if (!$errores) {
                $errores .= "?nombreMal=true";
            } else {
                $errores .= "&nombreMal=true";
            }
        }
    }


    if (validaCampoVacio($correo)) {
        if (!$errores) {
            $errores .= "?correoVacio=true";
        } else {
            $errores .= "&correoVacio=true";
        }
    } else {
        if (!filter_input(INPUT_POST, "correo", FILTER_VALIDATE_EMAIL)) {
            if (!$errores) {
                $errores .= "?correoMal=true";
            } else {
                $errores .= "&correoMal=true";
            }
        } else {
            // Verificar si el correo ya existe en la base de datos
            $sql = "SELECT correo FROM tbl_usuarios WHERE correo = ?";
            $stmt =  mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $correo);
            mysqli_stmt_execute($stmt);
            // mysqli_stmt_bind_result($stmt, $correoAlmacenado);
            // mysqli_stmt_fetch($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // El correo ya existe en la base de datos
                if (!$errores) {
                    $errores .= "?correoExistente=true";
                } else {
                    $errores .= "&correoExistente=true";
                }
            }
            
            $stmt->close();
        }
    }

    if (validaCampoVacio($contrasena)) {
        if (!$errores) {
            $errores .= "?contrasenaVacio=true";
        } else {
            $errores .= "&contrasenaMal=true";
        }
    } else {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $contrasena)) {
            if (!$errores) {
                $errores .= "?contrasenaMal=true";
            } else {
                $errores .= "&contrasenaMal=true";
            }
        }
    }



// si hay algun error los guardamos en el array
    if ($errores != "") {
        $datosRecibidos = array(
            'username' => $username,
            'nombre' => $nombre,
            'correo' => $correo,
            'contrasena' => $contrasena,
        );
        $datosDevueltos = http_build_query($datosRecibidos);
        // redirigimos a la pagina de login y en la url le mostramos los errores
        header("Location: ./login.php" . $errores . "&" . $datosDevueltos);
        exit();
    } else {
        // en caso de que no haya ningún error nos lleva a la página de check para que el usuario pueda comprobar los datos introducidos
        echo "<form id='EnvioCheck' action='check.php' method='POST'>";
        echo "<input type='hidden' id='username' name='username' value='" . $username . "'>";
        echo "<input type='hidden' id='nombre' name='nombre' value='" . $nombre . "'>";
        echo "<input type='hidden' id='correo' name='correo' value='" . $correo . "'>";
        echo "<input type='hidden' id='contrasena' name='contrasena' value='" . $contrasena . "'>";
        echo "</form>";
        echo "<script>document.getElementById('EnvioCheck').submit();</script>";
    }
}
?>
</body>
</html>
