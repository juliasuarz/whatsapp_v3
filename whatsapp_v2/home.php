<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #FEEEF7;
        }

        .container {
            display: flex;
        }

        .lista-users {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            flex: 1;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            float: left;
            width: 70%;
            min-height: 92vh; 
            height: auto;
   

        }

        .lista-amigos{
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            margin-left: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            
        }
        .lista-amigos{
            float: left;
            margin: 0px;
            width: 30%;  
            min-height: 92vh; 
            height: auto;        }

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
            margin: 10px auto;
            padding: 10px 20px;
            border: none;
            background-color: #fe57b1;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-outline-success{
            float: none;
        }
        input[type="submit"]:hover {
            background-color: #CB458D;
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
        <a class="nav-link" href="#">Todos </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="home_pendientes.php">Pendientes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="home_bloqueados.php">Bloqueados</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cerrar_sesion.php">Cerrar sesión</a>
      </li>
    </ul>
  </div>
</nav>


<?php
// inicia la sesion
session_start();

// Comprobar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_user'])) {
    header('Location: ./singup.php'); // Redirige a la página de inicio de sesión
    exit();
}

include_once("./conexion.php");

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




    // lista de usuarios
    echo "<div class='lista-users'>";
        echo "<div class='column-search' method='get' action=''>";
            echo "<form class='form-inline'>";

                // echo "<input class='form-control mr-sm-2' type='search' placeholder='Search' aria-label='Search'>";?>
                <input type="text" name="busca" id="busca" placeholder="" value="<?php if(isset($_GET['busca'])) {echo $_GET['busca'];} ?>" style="width: 70%; height: 36px; border-radius: 10px; border: 2px solid;">
<?php
            echo "<button class='btn btn-outline-success my-2 my-sm-0' type='submit'>Search</button>";
            echo "</form>";
        echo "</div>";
        

        echo "</br>";


        // Consulta SQL para obtener usuarios
        // $sql = "SELECT * FROM tbl_usuarios 
        // WHERE id_user != $id_usuario_actual 
        // AND id_user NOT IN (
        //     SELECT id_emisor FROM tbl_solicitudesAmistad 
        //     WHERE (id_receptor = $id_usuario_actual OR id_emisor = $id_usuario_actual)
        //     AND (estado = 'bloqueado' OR estado = 'amigo' OR estado = 'pendiente')
        // )";
        if (isset($_GET['busca'])) {
            $busca = mysqli_real_escape_string($conn, $_GET['busca']);

            $sql = "SELECT * FROM tbl_usuarios 
                    WHERE (id_user != $id_usuario_actual) 
                    AND (username LIKE '%$busca%' OR nombre LIKE '%$busca%')";
        } else {
            // Si no hay búsqueda, mostrar todos los usuarios
            $sql = "SELECT * FROM tbl_usuarios 
                    WHERE id_user != $id_usuario_actual";
        }
        // enviamos una consulta a la BD
        $result = mysqli_query($conn, $sql);

        // si hay resultados entramos en el bucle
        if ($result) {

            echo "<table border='1'>";
            echo "<tr>
            <th>Username</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Acción</th>
            <th>Bloquear</th>
            </tr>";

            // imprimimos los resultados
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                // echo "<td>" . $row["id_user"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["correo"] . "</td>";

                // Agregar un botón para enviar una solicitud de amistad
                echo "<td><form method='post' action='enviar_solicitud.php'>";
                // enviamos los id (del receptor y del emisor)
                echo "<input type='hidden' name='id_emisor' value='$id_usuario_actual'>";
                echo "<input type='hidden' name='id_receptor' value='" . $row["id_user"] . "'>";
                echo "<input type='submit' value='Añadir Amigo'>";
                echo "</form></td>";
                //bloquear usuario
                echo "<td><form method='post' action='bloquear_solicitud.php'>";
                echo "<input type='hidden' name='id_emisor' value='$id_usuario_actual'>";
                echo "<input type='hidden' name='id_receptor' value='" . $row["id_user"] . "'>";
                echo "<input type='submit' value='Bloquear'>";
                echo "</form></td>";

                echo "</tr>";
            }

            echo "</table>";
        } else {
            // en caso de que no haya ningun user en la bd muestra un mensaje
            echo "No se encontraron usuarios.";
        }
    echo "</div>";



    


// Cerrar la conexión a la base de datos

mysqli_close($conn);
// 
?>

</body>
</html>