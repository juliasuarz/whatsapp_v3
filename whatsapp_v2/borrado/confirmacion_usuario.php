<?php
    session_start();

    if (!isset($_SESSION['usuariook'])){
        header('Location: '.'./cerrar_sesion.php');
        exit();
    }else{ 
    
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>confirmacion usuario</title>
    </head>
    <body>

    <?php if (isset($_GET['error']) && ($_GET['error']=='confirmacionVacia')){echo "Has de rellenar la información  solicitada"; }?>

    <form action="./validacion.php" method="post">
        <h1>Confirma tus datos guapa</h1>
        <p>Estimado <?php echo $_SESSION['username']; ?>, para completar su pedido en la tienda tu pedido de los juegos: </p>
        <p> Este es tu nombre<strong><?php echo $_SESSION['nombre'] ?></strong>  </p> <br>
        <p> Este es tu contraseña<strong><?php echo $_SESSION['contrasena'] ?></strong>  </p>
        <p> Este es tu correo guapa<strong><?php echo $_SESSION['correo'] ?></strong>  </p>
        <input type="submit" name="confirmacion" value="Confirmar usuario">

    </form>
    <br/>
    <a href="./cerrar_sesion.php">Descartar usuario</a>

    </body>
    </html>
<?php
    }
?>