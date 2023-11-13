
<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "Usuario vacío";
    exit();
} else {
    include_once("./conexion.php");
    // Valida si son amigos
    $emisor = mysqli_real_escape_string($conn, $_SESSION['id_user']);
    $receptor = mysqli_real_escape_string($conn, $_POST['id_receptor']);

    // Consulta SQL para verificar la amistad
    $sql = "SELECT * FROM tbl_solicitudesAmistad 
            WHERE id_emisor = $emisor AND id_receptor = $receptor AND estado = 'amigo'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Son amigos, almacena sus IDs en la sesión
        $_SESSION['id_emisor'] = $emisor;
        $_SESSION['id_receptor'] = $receptor;
    } else {
        echo "No son amigos. Mensaje de error o redirección a una página de error.";
        exit();
    }
    // Consulta SQL para obtener los mensajes entre los usuarios
    $sql = "SELECT * FROM tbl_mensajes 
    WHERE (id_emisor = $emisor AND id_receptor = $receptor) OR (id_emisor = $receptor AND id_receptor = $emisor) 
    ORDER BY FechaEnvio DESC"; // Cambio en ORDER BY para mensajes más recientes primero
    $result = mysqli_query($conn, $sql);
}   
    //limpiar variable mensaje
    $mensaje= "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat Local</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight - chatContainer.clientHeight;

            // Ajusta el desplazamiento para que comience desde lo más nuevo
        });

    </script>
    <style>
    .chat-container {
        flex-direction: column;
        background-color: #FEEEF7;
        display: inline-block;
        padding: 15px;
        overflow-x: hidden;
    }
    
    .primer:nth-child(1) {
        order: 1;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background-color: #FEEEF7;
    }
    
    .chat {
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 20px;
        flex: 1;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        float: left;
        width: 70%;
        height: 92vh;   
        overflow-x: hidden; 

    }

    .burbujas{
        background-color:#FEEEF7 ;
        border: 1px solid #fe57b1;        
    }
    
    .lista-amigos,
    .chat {
        background-color: #FEEEF7;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
    .lista-amigos{
        float: left;
        margin: 0px;
        width: 30%;  
        height: 92vh;    
    }
    h2 {
        color: #fe57b1;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid #fe57b1;
        background-color: #FEEEF7;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    form {
        margin-top: 20px;
    }

    input[type="submit"] {
        display: block;
        margin: 10px;
        padding: 10px 20px;
        border: none;
        background-color: #fe57b1;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        float: left;
    }

    input[type="submit"]:hover {
        background-color: #CB458D;
    }
    textarea{
        float: left;
        width: 86%;
    }
    
    .sent-message {
        text-align: right;
        margin-left: auto!important; /* Para centrar el mensaje a la derecha */

    }
    ::-webkit-scrollbar {
        width: 10px; /* Ancho de la barra de desplazamiento */
    }

    ::-webkit-scrollbar-thumb {
        background: #007bff; /* Color del pulgar de la barra de desplazamiento */
        border-radius: 5px; /* Borde redondeado del pulgar */
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0056b3; /* Cambiar el color al pasar el cursor sobre el pulgar */
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1; /* Color del fondo de la barra de desplazamiento */
    }

    .chat-container{
        background-color: #FCF5F6;
        border: 3px solid #fe57b1;

    }
    .chat-container .comentario{
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #fe57b1;

    }

    .chat-container .comentario p{
        margin: 0 0 10px 0;
    }

    
    .burbuja {
        border: 1px solid #fe57b1;
        position: relative;
        background-color: #ffffff;
        padding: 20px;
        color: #222;
        border-radius: 3px;
        margin-left: 20px;
        max-width: 50%;

    }

    .burbuja:after {
        content: "";
        display: block;
        position: absolute;
        top: 15px;
        right: -15px; /* Cambia la dirección a la derecha para invertir el triángulo */
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-left: 15px solid #fe57b1; /* Cambia el lado del triángulo para invertirlo */

    }
    .received-message {
        position: relative;
        background-color: #ffffff;
        padding: 20px;
        color: #222;
        border-radius: 3px;
        margin-left: 20px;
    }

    .received-message:after {
        content: "";
        display: block;
        position: absolute;
        top: 15px;
        left: -35px; /* Ajusta la posición a la izquierda */
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 15px solid #fe57b1;
        border-left: 0px;
        left: -15px;

    }
    td{
        text-align: center;
    }

    


    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light " style="background-image: url(img/Captura\ de\ pantalla\ 2023-11-13\ a\ las\ 18.29.12.png)">
    <a class="navbar-brand" href="#">Whatsapp</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="home.php">Todos </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="home_pendientes.php">Pendientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="home_bloqueados.php">Bloqueados</a>
        </li>
        </ul>
    </div>
    </nav>
    <?php
    // Obtener el ID del usuario actual
    $id_usuario_actual = $_SESSION['id_user'];

    // Lista usuarios amigos
    echo "<div class='lista-amigos'>";

    echo "<h2>Amigos</h2>";

    // Consulta SQL para obtener los amigos del usuario actual
    $sqlAmigos = "SELECT tbl_usuarios.id_user, tbl_usuarios.nombre, tbl_usuarios.correo
                FROM tbl_usuarios
                INNER JOIN tbl_solicitudesAmistad ON tbl_usuarios.id_user = tbl_solicitudesAmistad.id_emisor
                WHERE tbl_solicitudesAmistad.id_receptor = $id_usuario_actual
                AND tbl_solicitudesAmistad.estado = 'amigo'
                AND tbl_usuarios.id_user != $id_usuario_actual";
        
    // enviamos una consulta a la BD
    $resultAmigos = mysqli_query($conn, $sqlAmigos);

    // Si hay resultados, mostramos la tabla de amigos
    if ($resultAmigos && mysqli_num_rows($resultAmigos) > 0) {
        echo "<table border='1'>";
        echo "<tr>
            <th>Nombre</th>
            <th>Correo</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($resultAmigos)) {
            echo "<tr>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["correo"] . "</td>";

            //chatear usuario
            echo "<td><form method='post' action='validacion_chat.php'>";
            echo "<input type='hidden' name='id_emisor' value='$id_usuario_actual'>";
            echo "<input type='hidden' name='id_receptor' value='" . $row["id_user"] . "'>";
            echo "<input type='submit' value='chat'>";
            echo "</form></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No tienes amigos actualmente.";
    }
    echo "</div>";
    ?>


    <div class="chat"> 
        <h1>Chat Local</h1>

        <div class="chat-container" id="chat-container" style="height: 75%; overflow-y: scroll; display: flex; ">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Obtener el valor del campo 'mensaje' enviado por el formulario
            $mensaje = $_POST["mensaje"];
            
            // Realizar tu validación personalizada
            if (empty($mensaje)) {
                $mensajemal = 1;
            } elseif (strlen($mensaje) > 250) {
                $mensajemal = 2;
            } else {
                // La validación pasó, puedes procesar el mensaje y guardarlo en la base de datos
                $mensaje = mysqli_real_escape_string($conn, $mensaje);
                $sqlInsert = "INSERT INTO tbl_mensajes (id_emisor, id_receptor, Mensaje, FechaEnvio) 
                            VALUES ($emisor, $receptor, '$mensaje', NOW())";
                mysqli_query($conn, $sqlInsert);

                // Llamar al modelo ChatGPT para obtener una respuesta
                // Aquí debes incluir el código que interactúa con el modelo para obtener la respuesta

                // Mostrar tu mensaje
                echo "<div class='burbuja comentario sent-message primer'>";
                echo "<p><strong>Tú <br></strong> " . $mensaje . "</p>";
                echo "</div>";

                // Mostrar la respuesta del modelo después de tu mensaje
                // Aquí debes mostrar la respuesta generada por el modelo ChatGPT
            }
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Obtener el nombre del emisor
            $emisorId = $row['id_emisor'];
            $claseCSS = ($emisorId == $emisor) ? 'sent-message' : 'received-message'; // Agrega la clase 'sent-message' si el emisor coincide con id_user
        
            $sqlUsuario = "SELECT nombre FROM tbl_usuarios WHERE id_user = $emisorId";
            $resultUsuario = mysqli_query($conn, $sqlUsuario);
            $rowUsuario = mysqli_fetch_assoc($resultUsuario);
            $nombreEmisor = $rowUsuario['nombre'];
        
            // Mostrar el nombre del emisor y el mensaje con la clase CSS correspondiente
            echo "<div class='burbuja comentario $claseCSS'><p><strong>$nombreEmisor</strong><br> " . $row['Mensaje'] . "</p></div>";
        }
        
        ?>
        </div>

        <?php
        if ($mensajemal == 1) {
            echo "El mensaje no puede estar vacío.";
        }
        if ($mensajemal == 2) {
            echo "El mensaje no puede tener más de 250 caracteres.";
        } 
        ?>


        <form method="post" action="validacion_chat.php">
            <input type="hidden" name="id_emisor" value="<?php echo $emisor; ?>">
            <input type="hidden" name="id_receptor" value="<?php echo $receptor; ?>">
            <textarea name="mensaje" rows="4" cols="50" ></textarea>
            <br>
            <input type="submit" name="enviar" value="Enviar Mensaje">
        </form>

</body>
</html>