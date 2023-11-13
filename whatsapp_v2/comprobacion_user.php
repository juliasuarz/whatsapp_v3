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
session_start(); // Inicia la sesión

if (!filter_has_var(INPUT_POST, 'enviar')) {
    header('Location: ./singup.php');
    exit();
}

$correo = isset($_POST['correo']) ? $_POST['correo'] : "";
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";
// $contrasenaIntroducida =hash("sha256", $contrasena);
$contrasenaIntroducida = password_hash($contrasena, PASSWORD_BCRYPT);


// Validación de campos
if (empty($correo) || empty($contrasena)) {
    header("Location: ./singup.php?loginVacio=true");
    exit();
}

// Conexión a la base de datos (asegúrate de tener una conexión configurada en conexion.php)
include_once("./conexion.php");

// Consulta SQL para verificar el correo
$sql = "SELECT correo, contrasena, id_user FROM tbl_usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $correoAlmacenado, $contrasenaAlmacenada, $id_user);
mysqli_stmt_fetch($stmt);

if ($correo == $correoAlmacenado && password_verify($contrasena, $contrasenaAlmacenada)) {  
    $_SESSION['correo'] = $correo;
    $_SESSION['id_user'] = $id_user;
    header("Location: ./home.php");
    exit();
} else {
    // Las credenciales no son válidas
    header("Location: ./singup.php?loginError=true");
    exit();
}
?>
    
</body>
</html>
