<?php
// Conexión a la base de datos (igual que antes)

include_once('./conexion.php');

if (!isset($_POST['editar'])){
    header('Location: '.'./login.php');
    exit();

}else{

    $id_user = $_POST['id_user'];
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $nombre = mysqli_real_escape_string($conn,$_POST['nombre']);
    $correo = mysqli_real_escape_string($conn,$_POST['correo']);
    $contrasena = mysqli_real_escape_string($conn,$_POST['contrasena']);

    try{
    // En primer lugar, se desactiva la autoejecución de las consultas
    mysqli_autocommit($conn, false);

    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
    $sql_usuario = "UPDATE tbl_usuarios
                        SET username = ?, nombre = ?, correo = ?, contrasena = ?
                        WHERE id = ?";
    $stmt_usuario = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt_usuario, $sql_usuario);
    mysqli_stmt_bind_param($stmt_usuario, "ssss", $username, $nombre, $correo, $contrasena);

    // Se ejecuta la primera consulta
    mysqli_stmt_execute($stmt_usuario);

    // Eliminar los juegos actuales asociados al pedido
    // $sql_eliminar_juegos = "DELETE FROM `tbl_pedidos_juegos` WHERE id_pedido = ?";
    // $stmt_eliminar_juegos = mysqli_stmt_init($conn);
    // mysqli_stmt_prepare($stmt_eliminar_juegos, $sql_eliminar_juegos);
    // mysqli_stmt_bind_param($stmt_eliminar_juegos, "i", $pedido_id);
    // mysqli_stmt_execute($stmt_eliminar_juegos);

    // Insertar los nuevos juegos seleccionados
    // $sql_insertar_juegos = "INSERT INTO `tbl_pedidos_juegos` (`id`, `id_pedido`, `id_juego`) VALUES (NULL, ?, ?)";
    // $stmt_insertar_juegos = mysqli_stmt_init($conn);
    // mysqli_stmt_prepare($stmt_insertar_juegos, $sql_insertar_juegos);
    //mysqli_stmt_bind_param($stmt_insertar_juegos, "is", $pedido_id, $juego);
    // foreach ($juegos as $juego) {
    //     mysqli_stmt_bind_param($stmt_insertar_juegos, "is", $pedido_id, $juego);
    //     mysqli_stmt_execute($stmt_insertar_juegos);
    //     }

    // Se hace el commit y por lo tanto se confirman las tres consultas
    //     mysqli_commit($conn);
    
    // Se cierra la conexión
    // mysqli_stmt_close($stmt_pedido);
    // mysqli_stmt_close($stmt_eliminar_juegos);
    // mysqli_stmt_close($stmt_insertar_juegos);

        // Redirigimos a la página de listado del CRUD
    header('Location: ./login.php');       

    } catch (Exception $e){
        mysqli_rollback($conn);
        echo 'Error: '. $e->getMessage() .'';
    }
}
