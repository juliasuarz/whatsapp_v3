<?php
session_start();

if (!isset($_SESSION['busca'])) {
    header('Location: ./home.php');
    exit();
}

include_once("./conexion.php");

$busca = mysqli_real_escape_string($conn, $_GET['busca']);


$sql = "SELECT * FROM tbl_usuarios 
        WHERE (id_user != $id_usuario_actual) 
        AND (username LIKE '%$busca%' OR nombre LIKE '%$busca%')";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<div class='lista-users'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='user'>";
        echo "Username: " . $row['username'] . "<br>";
        echo "Nombre: " . $row['nombre'] . "<br>";
        // Add other user details as needed
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Error in the query: " . mysqli_error($conn);
}

mysqli_close($conn);
?>