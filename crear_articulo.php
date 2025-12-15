<?php

// crear_articulo.php
// Permite al administrador crear un nuevo artículo y guardarlo en la base de datos 

require "test_sesion.php";
require "conexion.php";

//comprobar si se envia el formulario y guardar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    //si se sube un archivo imagen
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $uploadDir = 'uploads/';
        
        //crea la carpeta uploads para guardar las imagenes subidas
        if(!file_exists($uploadDir)) {
            mkdir($uploadDir);
        }

        //se guarda la imagen con su nombre original
        $imagen = $_FILES['imagen']['name'];
       
        //mueve la imagen a uploads
        move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $imagen);
    }


    //insertar articulo en la bd evitando inyección sql
    $sql = "INSERT INTO ARTICULOS (nombre, descripcion, precio, stock, imagen) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection -> prepare ($sql);
    $stmt -> bind_param ("ssdis", $nombre, $descripcion, $precio, $stock, $imagen);
    $stmt -> execute();

    header("Location: gestionar_articulos.php");
    exit();
}
?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Crear Articulo </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class = "container">
    <h1> Crear nuevo artículo </h1>
    <a href = "gestionar_articulos.php" > Volver </a>

    <!-- Formulario para crear un artículo -->
    <form action = "" method = "POST" enctype = "multipart/form-data" class="form">
        
        <label> Nombre: </label> <br>
        <input type = "text" name = "nombre" required> <br>

        <label> Descripcion: </label> <br>
        <textarea name = "descripcion" rows = "4" cols = "50" required> </textarea> <br>

        <label> Precio: </label> <br>
        <input type = "number" name = "precio" step = "0.01" required> <br>

        <label> Stock: </label> <br>
        <input type = "number" name = "stock" required> <br>

        <label> Imagen: </label> <br>
        <input type = "file" name = "imagen" accept = "image/*"> <br>

        <input type = "submit" value = "Crear artículo">
    
    </form>
    </div>

</body>
</html> 