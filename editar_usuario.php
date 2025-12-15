<?php

// editar_usuario.php
// Permite al administrador editar los datos de un usuario existente con todos sus datos respectivamente

session_start();
require "test_sesion.php";
require "conexion.php";

//comprobar si hay un id en la url
if (!isset($_GET['id'])) {
    header("Location: gestionar_usuarios.php");
    exit();
}

$id_usuario = intval($_GET['id']);

//Obtener los datos del usuario de la BD evitando inyeccion SQL
$sql = "SELECT * FROM USUARIOS WHERE id_usuario = ?";
$stmt = $connection -> prepare($sql);
$stmt -> bind_param("i", $id_usuario);
$stmt -> execute();
$resultado = $stmt -> get_result();

if($resultado->num_rows !== 1) {
    echo "Usuario no encontrado";
    exit();
}

$usuario = $resultado -> fetch_assoc();

//envío del formulario de edición
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST ['nombre'];
    $email = $_POST ['email'];
    $rol = $_POST ['rol'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

//Si se ingresa una contraseña nueva, se encripta
if (!empty($_POST['password'])) {
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "UPDATE USUARIOS SET nombre=?, email=?, password_hash=?, rol=?, telefono=?, direccion=? WHERE id_usuario=?";
    $stmt = $connection -> prepare($sql);
     $stmt->bind_param("ssssssi", $nombre, $email, $password_hash, $rol, $telefono, $direccion, $id_usuario);
} else {
    $sql = "UPDATE USUARIOS SET nombre=?, email=?, rol=?, telefono=?, direccion=? WHERE id_usuario=?";
    $stmt = $connection -> prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $email, $rol, $telefono, $direccion, $id_usuario);
}

$stmt -> execute();

header("Location: gestionar_usuarios.php");
exit();

}
?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset ="UTF-8">
    <title> Editar Usuario </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class = "container">
    <h1> Editar usuario </h1>
    <a href = "gestionar_usuarios.php"> Volver </a>

<!-- Formulario para editar usuario -->
<form action ="" method="POST">
    <label> Nombre: </label> 
    <input type = "text" name = "nombre" value ="<?php echo $usuario['nombre']; ?>" required> 

    <label> Email: </label> 
    <input type = "text" name = "email" value = "<?php echo $usuario['email']; ?>" required>  

    <label> Contraseña: </label> 
    <input type = "password" name = "password"> 

    <label>Rol: </label> 
    <select name = "rol" required>
        <option value = "cliente" <?php if($usuario['rol'] == 'cliente') echo 'selected'; ?>> Cliente </option>
        <option value = "administrador" <?php if($usuario['rol'] == 'administrador') echo 'selected'; ?>> Administrador </option>
    </select> 

    <label> Teléfono: </label> 
    <input type = "text" name = "telefono" value = "<?php echo $usuario['telefono']; ?>"> 

    <label> Dirección: </label> 
    <input type = "text" name = "direccion" value = "<?php echo $usuario['direccion']; ?>"> 

    <input type = "submit" value = "Actualizar usuario">

</form>

</div> 
</body>
</html>