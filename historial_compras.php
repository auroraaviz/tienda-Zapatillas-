<?php

//historial_compras.php
//Muestra todos los pedidos de un cliente con detalle de artículos y totales

session_start();

//solo clientes logueados
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

require "conexion.php";

$id_usuario = $_SESSION['id_usuario'];

//obtener todos los pedidos del cliente
$sql_pedidos = "SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha ASC";
$stmt_pedidos = $connection->prepare($sql_pedidos);
$stmt_pedidos->bind_param("i", $id_usuario);
$stmt_pedidos->execute();
$result_pedidos = $stmt_pedidos->get_result();

?> 

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Historial de compras </title>
    <link rel = "stylesheet" href = "style.css">
</head>

<body>
    <div class = "container">
        <h1> Historial de compras </h1>
        <a href = "tienda.php"> Volver a la tienda </a>
        <a href = "logout.php"> Cerrar sesión </a>
        
        <!--verificar si hay pedidos-->
    <?php if ($result_pedidos->num_rows === 0): ?>
        <p> No tienes pedidos realizados </p>
    <?php else: ?>
        <!--recorre cada pedido mostrando el número de pedido y la fecha-->
        <?php while($pedido = $result_pedidos->fetch_assoc()): ?>
            <h2> Pedido #<?= $pedido['id_pedido'] ?> - Fecha: <?= $pedido['fecha'] ?> </h2>
            <table border="1" cellpadding="5" cellspacing="0">

            <tr>
                <th>Artículo</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>

            <?php 

            //obtener detalles del pedido
            $sql_detalle = "SELECT dp.cantidad, dp.subtotal, a.nombre, a.precio
                            FROM detalle_pedido dp
                            JOIN articulos a ON dp.id_articulo = a.id_articulo
                            WHERE dp.id_pedido = ?";
            $stmt_detalle = $connection->prepare($sql_detalle);
            $stmt_detalle->bind_param("i", $pedido['id_pedido']);
            $stmt_detalle->execute();
            $result_detalle = $stmt_detalle->get_result();
            ?>

           <!--muestra los artículos en tabla -->
            <?php while($detalle = $result_detalle->fetch_assoc()): ?>
                <tr>
                    <td> <?= htmlspecialchars($detalle['nombre']) ?> </td>
                    <td> <?= number_format($detalle['precio'], 2) ?> </td>
                    <td> <?= $detalle['cantidad'] ?> </td>
                    <td> <?= number_format($detalle['subtotal'], 2) ?> </td>
                </tr>
            <?php endwhile; ?>
            
            <tr> 
                <td colspan="3"> Total </td>
                <td> <?= number_format($pedido['total'], 2) ?> </td>
            </tr>
            
            </table>
        <?php endwhile; ?>
     <?php endif; ?>
    
    </div>
    </body>
    </html>
                