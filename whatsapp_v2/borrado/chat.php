<!DOCTYPE html>
<html>
<head>
    <title>Chat Local</title>
</head>
<body>
    <h1>Chat Local</h1>

    <form method="post" action="validacion_chat.php">
        <label for="id_emisor">ID Emisor:</label>
        <input type="text" name="id_emisor" required>
        <br>
        <label for="id_receptor">ID Receptor:</label>
        <input type="text" name="id_receptor" required>
        <br>
        <input type="submit" value="Iniciar Chat">
    </form>
</body>
</html>
