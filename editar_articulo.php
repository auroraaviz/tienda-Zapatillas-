<?php 

// editar_artículo.php
// Permite al administrador editar los datos referidos a cada artículo (precio, stock, nombre...).

require "test_sesion.php";
require "conexion.php";

//mensaje si no hay ID en la descripción del artículo
if(!isset($_GET['id'])) {
    echo "Falta ID de artículo";
    exit();
}

$id = $_GET['id'];

//acceder al articulo evitando inyección sql
$sql = "SELECT * FROM ARTICULOS WHERE id_articulo = ? ";
$stmt = $connection -> prepare ($sql);
$stmt -> bind_param("i", $id);
$stmt -> execute();
$result = $stmt -> get_result();

if($result -> num_rows === 0) {
    echo "Articulo no encontrado";
    exit();
}

$articulo = $result -> fetch_assoc(); 

//envío del formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST ['nombre'];
    $descripcion = $_POST ['descripcion'];
    $precio = $_POST ['precio'];
    $stock = $_POST ['stock'];

    $imagen = $articulo ['imagen'];

    //si se sube un archivo imagen
    if(!empty ($_FILES['imagen']['name'])) {
        $uploadDir = 'uploads/';

        //crea la carpeta uploads si no existe
        if (!file_exists($uploadDir)) {
            mkdir ($uploadDir);
        }

        //se guarda con su nombre original
        $imagen = $_FILES['imagen']['name'];

        //mueve la imagen a uploads
        move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $imagen);
    }

    //actualizar base de datos
    $sql = "UPDATE ARTICULOS
        SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?
        WHERE id_articulo = ?";
    $stmt = $connection -> prepare ($sql);
    $stmt -> bind_param ("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen, $id);
    $stmt -> execute ();

    header("Location: gestionar_articulos.php");
    exit();

}

?>

<!DOCTYPE html> 

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Editar artículo </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class = "form-container"> 
    <h1> Editar artículo </h1>
    <a href = "gestionar_articulos.php"> Volver </a>

    <!-- Formulario para editar artículo -->
    <form method = "POST" enctype = "multipart/form-data" class = "form">

        <label> Nombre: </label> <br>
        <input type = "text" name = "nombre" value = "<?= $articulo ['nombre'] ?>" required> <br>

        <label> Descripcion: </label> <br>
        <textarea name ="descripcion"> <?= $articulo['descripcion'] ?> </textarea> <br>

        <label> Precio: </label> <br>
        <input type = "number" name = "precio" value = "<?= $articulo['precio'] ?>" required> <br>

        <label> Stock: </label> <br>
        <input type = "number" name = "stock" value = "<?= $articulo['stock'] ?>" required> <br> 

        <label> Imagen actual: </label> <br>
            <?php if ($articulo ['imagen']): ?>
                <img src = "uploads/<?= $articulo ['imagen'] ?>"> <br>
            <?php endif; ?>

        <label> Nueva imagen (opcional):</label> <br>
        <input type = "file" name = "imagen"> <br>

        <input type = "submit" value = "Guardar cambios">

    </form>
    </div>

    </body>
    </html> 