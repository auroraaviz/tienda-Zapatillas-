<?php

// carrito.php
// Muestra los artículos que el usuario ha agregado al carrito y permite confirmar la compra


session_start();
require "conexion.php";

//verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header ("Location: login.php");
    exit();
}

//iniciar carrito 
$carrito = $_SESSION['carrito'] ?? []; 
$articulos_carrito = [];

 if (!empty($carrito)): 
    //Convierte los IDs del carrito en una cadena para la consulta SQL
    $ids = implode(',', array_keys($carrito));
    $sql = "SELECT * FROM ARTICULOS WHERE id_articulo IN ($ids)";
    $resultado = $connection -> query ($sql);
    
    //cada fila es un articulo de la bd
    while ($row = $resultado -> fetch_assoc()) {
        //guarda cada articulo usando su id 
        $articulos_carrito[$row['id_articulo']] = $row;
    }

    endif;

 $total = 0;
?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Carrito de compras </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class = "container">
    <h1> Tu carrito </h1>
    
    <a href = "tienda.php"> Volver a la tienda </a>
    <a href = "logout.php"> Cerrar sesión </a> <br>

<!--si el carrito está vacio -->
<?php if (empty($carrito)): ?>
    <p> Tu carrito está vacío </p>
<!--si hay artículos genera una tabla con los articulos-->
<?php else: ?>
    <table border = "1" cellpadding = "5" cellspacing = "0">
    <tr>
        <th> Artículo </th>
        <th> Precio </th>
        <th> Cantidad </th>
        <th> Subtotal </th>
    </tr>

    <!--recorre los articulos del carrito (id y cantidad)-->
    <?php foreach ($carrito as $id => $cantidad):
        //obtiene toda la info del articulo
        $art = $articulos_carrito[$id];
        //calcula el precio total según la cantidad
        $subtotal = $art ['precio'] * $cantidad;
        $total += $subtotal;
    ?>

    <!-- se muestra cada articulo con sus características-->
    <tr>
        <td> <?= $art['nombre']?> </td>
        <!--mostramos el precio con 2 decimales-->
        <td> <?= number_format($art['precio'],2)?> </td>
        <td> <?= $cantidad ?> </td>
        <td> <?= number_format($subtotal, 2) ?> </td>
    </tr>
    <?php endforeach; ?>

    <tr> 
        <!-- se muestra el total -->
        <td colspan = "3"> Total </td>
        <td> <?= number_format ($total, 2) ?> </td>
    </tr>
    </table>

    <br>
    <form method = "post" action = "confirmar_compra.php">
        <input type = "submit" value ="Confirmar compra">
    </form>

    <?php endif; ?>
    
    </body>
    </html> 
    