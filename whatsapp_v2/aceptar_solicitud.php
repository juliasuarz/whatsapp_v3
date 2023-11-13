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
    
    // recuperamos los id que hemos enviamos y los guardamos en variables
    $emisor = mysqli_real_escape_string($conn, $_SESSION['id_user']);
    $receptor = mysqli_real_escape_string($conn, $_POST['id_receptor']); // Asume que se recibe el ID del receptor por algún medio, como un formulario POST

    try {
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn);

        // Verifica si existe una solicitud pendiente entre estos dos usuarios
        $stmtCheck = mysqli_stmt_init($conn);
        $sqlCheck = "SELECT COUNT(*) FROM tbl_solicitudesAmistad WHERE (id_emisor = ? AND id_receptor = ?) AND estado = 'pendiente'";
        mysqli_stmt_prepare($stmtCheck, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ii", $emisor, $receptor);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        // Obtenemos el resultado de la consulta
        mysqli_stmt_bind_result($stmtCheck, $count);
        mysqli_stmt_fetch($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // Actualiza el estado de la solicitud a 'amigo' para el emisor
            //ES LA PRIMERA LA QUE NO FUNCIONA 
            $estado = "amigo";
            $stmtInsert = mysqli_stmt_init($conn);
            $sqlInsert = "INSERT INTO tbl_solicitudesAmistad (id_emisor, id_receptor, estado) VALUES (?, ?, ?)";
            mysqli_stmt_prepare($stmtInsert, $sqlInsert);
            mysqli_stmt_bind_param($stmtInsert, "iis", $emisor, $receptor, $estado);

            if (mysqli_stmt_execute($stmtInsert)) {
                echo "Inserción exitosa";
            } else {
                echo "Error al insertar en la base de datos: " . mysqli_stmt_error($stmtInsert);
            }

            // echo "funciona 1";
            // Actualiza el estado de la solicitud a 'amigo' para el receptor
            $stmtUpdateReceptor = mysqli_stmt_init($conn);
            $sqlUpdateReceptor = "UPDATE tbl_solicitudesAmistad SET estado = ? WHERE id_emisor = ? AND id_receptor = ?";
            mysqli_stmt_prepare($stmtUpdateReceptor, $sqlUpdateReceptor);
            mysqli_stmt_bind_param($stmtUpdateReceptor, "sii", $estado, $receptor, $emisor);
            mysqli_stmt_execute($stmtUpdateReceptor);
            // echo "funciona 2";

            if (mysqli_stmt_affected_rows($stmtInsert) > 0 && mysqli_stmt_affected_rows($stmtUpdateReceptor) > 0) {
                echo "Usuarios ahora son amigos";
                
                mysqli_commit($conn);
            } else {
                echo "Error al actualizar el estado a 'amigo'";
            }
            
        } else {
            echo "No existe una solicitud pendiente entre estos dos usuarios";
        }

        // Cerramos las declaraciones preparadas
        mysqli_stmt_close($stmtCheck);
        mysqli_stmt_close($stmtInsert);
        mysqli_stmt_close($stmtUpdateReceptor);


        // Cerrar la conexión
        $conn->close();
    } catch (Exception $e) {
        // Deshacemos la actualización en caso de que se genere alguna excepción
        mysqli_rollback($conn);
        echo "Error al actualizar el estado o agregar a amigos: " . $e->getMessage();
        die();
    }
}
?>
<form action="home.php" method="get" style="padding: 15px;">
    <input type="submit"  value="Volver atrás" >
</form>

    
 </body>
 </html>


