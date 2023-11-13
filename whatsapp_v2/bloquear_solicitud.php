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
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "Usuario vacío";
    exit();
} else {
    include_once("./conexion.php");
    
    // Recuperamos los ID que hemos enviado y los guardamos en variables
    $emisor = mysqli_real_escape_string($conn, $_SESSION['id_user']);
    $receptor = mysqli_real_escape_string($conn, $_POST['id_receptor']); // Asume que se recibe el ID del receptor por algún medio, como un formulario POST

    try {
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn);

        // Verifica si existe una solicitud pendiente entre estos dos usuarios
        $stmtCheck = mysqli_stmt_init($conn);
        $sqlCheck = "SELECT estado FROM tbl_solicitudesAmistad WHERE (id_emisor = ? AND id_receptor = ?)";
        mysqli_stmt_prepare($stmtCheck, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ii", $emisor, $receptor);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        // Obtenemos el resultado de la consulta
        mysqli_stmt_bind_result($stmtCheck, $estado);
        mysqli_stmt_fetch($stmtCheck);

        $stmtUpdateEmisor = null; // Inicializa las variables fuera del bloque condicional
        $stmtUpdateReceptor = null;

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            if ($estado === "bloqueado") {
                echo "El usuario ya está bloqueado";
                echo '<a href="home.php"><input type="button" value="Ir a Home" style="width: auto;"></a>';

            } else {
                // Actualiza el estado de la solicitud a 'bloqueado' para el emisor
                $estado = "bloqueado";
                $stmtUpdateEmisor = mysqli_stmt_init($conn);
                $sqlUpdateEmisor = "UPDATE tbl_solicitudesAmistad SET estado = ? WHERE id_emisor = ? AND id_receptor = ?";
                mysqli_stmt_prepare($stmtUpdateEmisor, $sqlUpdateEmisor);
                mysqli_stmt_bind_param($stmtUpdateEmisor, "sii", $estado, $emisor, $receptor);
                mysqli_stmt_execute($stmtUpdateEmisor);
                
                // Actualiza el estado de la solicitud a 'bloqueado' para el receptor
                $stmtUpdateReceptor = mysqli_stmt_init($conn);
                $sqlUpdateReceptor = "UPDATE tbl_solicitudesAmistad SET estado = ? WHERE id_emisor = ? AND id_receptor = ?";
                mysqli_stmt_prepare($stmtUpdateReceptor, $sqlUpdateReceptor);
                mysqli_stmt_bind_param($stmtUpdateReceptor, "sii", $estado, $receptor, $emisor);
                mysqli_stmt_execute($stmtUpdateReceptor);
            }
        } else {
            echo "No existe una solicitud pendiente entre estos dos usuarios";
            echo '<a href="home.php"><input type="button" value="Ir a Home" style="width: auto;"></a>';

        }

        // Verifica si las variables se han creado antes de intentar cerrarlas
        if ($stmtUpdateEmisor) {
            mysqli_stmt_close($stmtUpdateEmisor);
        }
        if ($stmtUpdateReceptor) {
            mysqli_stmt_close($stmtUpdateReceptor);
        }

        // Cerramos las declaraciones preparadas
        mysqli_stmt_close($stmtCheck);

        // Cerrar la conexión
        $conn->close();
    } catch (Exception $e) {
        // Deshacemos la actualización en caso de que se genere alguna excepción
        mysqli_rollback($conn);
        echo "Error al actualizar el estado o bloquear la solicitud: " . $e->getMessage();
        echo '<a href="home.php"><input type="button" value="Ir a Home" style="width: auto;"></a>';

        die();
    }
}
?>
    
</body>
</html>

