<?php

// agregar_carrito.php
// Este archivo permite agregar productos al carrito de compras de un usuario
// Controla que el usuario haya iniciado sesión y maneja el carrito en sesión

session_start();

//verifica que el usuario haya iniciado sesión
if (!isset($_SESSION ['id_usuario'])) {
    header ("Location: login.php");
    exit();
}
//inicializa el carrito
if(!isset($_SESSION ['carrito'])) {
    $_SESSION ['carrito'] = [];
}

// Comprueba si se han enviado datos de artículo y cantidad con POST
if(isset ($_POST['id_articulo']) && isset ($_POST['cantidad'])) {
    //convierte id y cantidad a enteros
    $id = intval ($_POST ['id_articulo']);
    $cantidad = intval ($_POST ['cantidad']);

    //si el producto existe en el carrito suma la cantidad
    if(isset($_SESSION['carrito'][$id])) {
        $_SESSION ['carrito'][$id] += $cantidad;
        //sino, lo agrega
    } else {
        $_SESSION['carrito'][$id] = $cantidad;
    }

    //redirige a la tienda
    header ("Location: tienda.php");
    exit();  

} else {

    header ("Location: tienda.php");
    exit();
}
?> 