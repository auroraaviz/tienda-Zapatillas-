<?php

// crear_usuario.php
// Permite al administrador crear un nuevo usuario con rol, contraseña y demás datos
// y guardarlo en la base de datos

session_start();
require "conexion.php";

//procesar el formulario solo si se envia con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST ['rol'];
    $telefono = $_POST ['telefono'];
    $direccion = $_POST ['direccion'];

    //encriptar la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    //insertar en la BD usando placeholder para evitar inyecciones SQL
    $sql = "INSERT INTO USUARIOS (nombre, email, password_hash, rol, telefono, direccion) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection -> prepare ($sql);
    $stmt -> bind_param ("ssssss", $nombre, $email, $password_hash, $rol, $telefono, $direccion);
    $stmt -> execute();

    //Redirigir al listado de usuarios
    header("Location: gestionar_usuarios.php");
    exit();

}
?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Crear usuario </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class = "container">
    <h1> Crear nuevo usuario </h1>
    <a href = "gestionar_usuarios.php"> Volver </a>

    <!-- Formulario para crear un usuario -->
    <form action = "" method = "POST">

        <label> Nombre: </label> 
        <input type = "text" name = "nombre" required> 

        <label> Email: </label> 
        <input type = "email" name = "email" required> 

        <label> Contraseña: </label> 
        <input type = "password" name = "password" required> 

        <label> Rol: </label> 
            <select name = "rol" required>
                <option value = "cliente"> Cliente </option>
                <option value = "administrador"> Administrador </option>
            </select> 

        <label> Teléfono: </label> 
        <input type = "text" name = "telefono"> 

        <label> Dirección: </label> 
        <input type = "text" name = "direccion"> 

        <input type = "submit" value = "Crear usuario">
    
    </form>

</div>
</body>
</html>