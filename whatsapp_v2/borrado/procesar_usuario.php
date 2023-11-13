<?php
session_start();


if (!isset($_SESSION['usuariook']) || !isset($_SESSION['username'])){
    header('Location: '.'./login.php');
    exit();

}else{

    include_once("./conexion.php");

    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    $nombre = mysqli_real_escape_string($conn, $_SESSION['nombre']);
    $correo = mysqli_real_escape_string($conn, $_SESSION['correo']);
    $contrasena = mysqli_real_escape_string($conn, $_SESSION['contrasena']);



    try{
        mysqli_autocommit($conn,false);
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        // Se define la primera consulta
        $stmt1= mysqli_stmt_init($conn);
        $sqlUsuario=  "INSERT INTO tbl_usuarios (id_user, username, nombre, correo, contrasena) VALUES (NULL, ?, ?, ?, ?);";
        mysqli_stmt_prepare($stmt1,$sqlUsuarios);
        mysqli_stmt_bind_param($stmt1,"ssss",$username, $nombre, $correo, $contrasena,);
        mysqli_stmt_execute($stmt1);

        echo "pedido insertado correctamente en la tabla de usuarios <br/>";

        // Recuperamos el ID generado en el insert de la primera tabla
        $lastid = mysqli_insert_id($conn);

        // Se define la segunda consulta
        // $sqlJuegos = "INSERT INTO tbl_pedidos_juegos (id, id_pedido, id_juego) VALUES (NULL, ?, ?);";
        // $stmt2= mysqli_stmt_init($conn);
        // mysqli_stmt_prepare($stmt2,$sqlJuegos);
        
        // foreach ($juegos as $juego){
        //     mysqli_stmt_bind_param($stmt2,"is", $lastid, $juego);
        //     mysqli_stmt_execute($stmt2);
        // }

        // Confirmamos las consultas
        mysqli_commit($conn);

        // Cerramos las conexiones
        mysqli_stmt_close($stmt1);
        // mysqli_stmt_close($stmt2);
 
        // Destruimos las variables de sesión y la sesión
        session_unset();
        session_destroy();

        echo "pedido registrado";
        
        header('Location: ./login.php');  

    } catch (Exception $e){
        // Deshacemos las inserciones en el caso de que se genere alguna excepción
        mysqli_rollback($conn);
        echo "Error al agregar el pedido nuevo: ". $e->getMessage();
        die();
    }

}