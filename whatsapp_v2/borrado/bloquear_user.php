<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "Usuario vacío";
    exit();
} else {
    include_once("./conexion.php");

    $emisor = mysqli_real_escape_string($conn, $_SESSION['id_user']);
    $receptor = mysqli_real_escape_string($conn, $_POST['id_receptor']); // Asume que se recibe el ID del receptor por algún medio, como un formulario POST

    try {
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn);

        // Verifica si ya existe una solicitud pendiente entre estos dos usuarios
        $stmtCheck = mysqli_stmt_init($conn);
        $sqlCheck = "SELECT COUNT(*) FROM tbl_solicitudesAmistad WHERE id_emisor = ? AND id_receptor = ? AND estado = 'pendiente'";
        mysqli_stmt_prepare($stmtCheck, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ii", $emisor, $receptor);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        // Obtenemos el resultado de la consulta
        mysqli_stmt_bind_result($stmtCheck, $count);
        mysqli_stmt_fetch($stmtCheck);

        if ($count > 0) {
            // Si existe una solicitud, cambia el estado a bloqueado
            $estado = "bloqueado";
            $sqlUpdate = "UPDATE tbl_solicitudesAmistad SET estado = ? WHERE id_emisor = ? AND id_receptor = ?";
            $stmtUpdate = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtUpdate, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "sii", $estado, $emisor, $receptor);
            mysqli_stmt_execute($stmtUpdate);

            if (mysqli_stmt_affected_rows($stmtUpdate) == 1) {
                echo "Usuario bloqueado correctamente";

                // Commit para confirmar la transacción
                mysqli_commit($conn);
            } else {
                echo "Error al bloquear amistad";
            }

        } else {
            // Si no existe ninguna solicitud, inserta un estado de bloqueado
            $estado = "bloqueado";
            $sqlInsert = "INSERT INTO tbl_solicitudesAmistad (id_emisor, id_receptor, estado) VALUES (?, ?, ?)";
            $stmtInsert = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtInsert, $sqlInsert);
            mysqli_stmt_bind_param($stmtInsert, "iis", $emisor, $receptor, $estado);
            mysqli_stmt_execute($stmtInsert);

            if (mysqli_stmt_affected_rows($stmtInsert) == 1) {
                echo "Usuario bloqueado correctamente";

                // Commit para confirmar la transacción
                mysqli_commit($conn);
            } else {
                echo "Error al bloquear amistad";
            }
        }

        // Cerramos las declaraciones preparadas
        mysqli_stmt_close($stmtCheck);
        mysqli_stmt_close($stmtUpdate);
        mysqli_stmt_close($stmtInsert);

        // Cerrar la conexión
        $conn->close();

    } catch (mysqli_sql_exception $e) {
        // Deshacemos la inserción en caso de que se genere alguna excepción
        mysqli_rollback($conn);
        echo "Error al bloquear: " . $e->getMessage();
        die();
    }
}
?>