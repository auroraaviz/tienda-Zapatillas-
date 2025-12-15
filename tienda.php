<?php

// tienda.php
// Muestra el catálogo de productos a clientes logueados y permite agregarlos al carrito.

session_start();
require "conexion.php";

//comprobar que el usuario está logueado.
if(!isset($_SESSION['id_usuario'])) {
    header ("Location: login.php");
    exit();
}

//Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION ['carrito'] = [];
}

//Obtener todos los productos
$sql = "SELECT * FROM ARTICULOS";
$resultado = $connection -> query ($sql);
?>


<!DOCTYPE html>
<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Tienda de zapatillas </title> 
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class = "container">
    <h1> Bienvenido </h1>
    <h2> Catálogo de productos </h2>

    <div class = "nav-links">
    <a href = "historial_compras.php"> Historial de compras </a>
    <a href = "carrito.php"> Ver carrito (<?= count($_SESSION['carrito']) ?>) </a>
    <a href = "logout.php"> Cerrar sesión </a>
    </div>

    <div class = "productos-container">
    <!--resultado de la consulta a la bd mostrando los productos en filas-->
    <?php while ($articulo = $resultado -> fetch_assoc()): ?>
    
    <!--estilo para mostrar los productos -->
    <div class = "producto">
        <h3> <?= $articulo ['nombre'] ?> </h3>
        <p> <?= $articulo ['descripcion']?> </p>
        <p> Precio: <?= $articulo ['precio'] ?> </p>
       
        <!--muestra una imagen si la hay --> 
    <?php if ($articulo['imagen']): ?>
        <img src = "uploads/<?= $articulo['imagen'] ?>">
    <?php endif; ?>

    <form method = "POST" action = "agregar_carrito.php" class = "producto-form">
         <!--se envia la id del producto que añadimos al carrito-->
        <input type = "hidden" name = "id_articulo" value = "<?= $articulo['id_articulo'] ?>">
        <!--selección de unidades-->
        <input type = "number" name = "cantidad" value = "1" min= "1">
        <input type = "submit" value = "Agregar al carrito">
    </form>
    </div>

    <?php endwhile; ?>

    </div>

    </body>
    </html> 