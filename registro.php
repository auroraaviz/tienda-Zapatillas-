<?php

// registro.php
// Permite a un nuevo usuario registrarse en la tienda.
// Valida que el email no exista, encripta la contraseña y almacena los datos en la base de datos.

session_start();
require "conexion.php"; 

//verifica que se haya enviado el formulario con POST
if($_SERVER["REQUEST_METHOD"] === "POST") {

    //obtiene y limpia datos del formulario
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    //comprueba si el email ya existe para evitar duplicados
    $sqlCheck = "SELECT id_usuario FROM USUARIOS WHERE email = ?";
    $stmtCheck = $connection->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if($result->num_rows > 0) {
        $error = "El correo ya está registrado."; 
    } else {
        //se inserta un nuevo usuario en la base de datos con la contraseña encriptada
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO USUARIOS (nombre, email, password_hash, rol, telefono, direccion)
                VALUES (?, ?, ?, 'cliente', ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $email, $password_hash, $telefono, $direccion);
        $stmt->execute();

        header("Location: login.php?registro=ok");
        exit();
    }
}
?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class = "container">
    <h1>Crear cuenta</h1>
    <a href = "login.php"> Volver al login </a>

<!--se muestra si hay correo duplicado-->
<?php if (isset($error)): ?> 
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<!--formulario de registro de nuevo usuario-->
<form action="" method="POST">

    <label> Nombre: </label> 
    <input type = "text" name = "nombre" required>

    <label> Email: </label> 
    <input type = "email" name = "email" required> 

    <label> Contraseña: </label> 
    <input type = "password" name ="password" required> 

    <label> Teléfono: </label> 
    <input type = "text" name = "telefono">  

    <label> Dirección: </label> 
    <input type = "text" name = "direccion"> 

    <input type = "submit" value = "Crear cuenta"> 

</form> 
</div>

</body>
</html> 