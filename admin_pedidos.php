<?php

// admin_pedidos.php
// Lista todos los pedidos de los clientes para que el administrador pueda verlos 

session_start();
require "conexion.php";

//solo pueden acceder administradores
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

//obtener todos los pedidos de los clientes
$sql = "SELECT p.id_pedido, p.fecha, p.total, u.nombre, u.email
        FROM pedidos p
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        ORDER BY p.fecha ASC";

$resultado = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Pedidos </title>
    <link rel = "stylesheet" href = "style.css">
</head>

<body>
    <div class = "container">
        <h1> Histórico de pedidos de clientes </h1>
        <a href = "admin_panel.php"> Volver al panel </a>
        
        <table border="1" cellpadding="5" cellspacing="0">

        <tr>
            <th> ID Pedido </th>
            <th> Cliente </th>
            <th> Email </th>
            <th> Fecha </th>
            <th> Total </th>
            <th> Acciones </th>
        </tr>

        <!-- Recorre cada pedido obtenido de la base de datos -->
        <?php while ($pedido = $resultado->fetch_assoc()): ?>
            <tr>
                    <td> <?= $pedido['id_pedido'] ?> </td>
                    <td> <?= htmlspecialchars($pedido['nombre']) ?> </td>
                    <td> <?= htmlspecialchars($pedido['email']) ?> </td>
                    <td> <?= $pedido['fecha'] ?> </td>
                    <td> <?= number_format($pedido['total'],2) ?> </td>
                <td>
                    <!-- Link para ver los detalles del pedido -->
                    <a href = "admin_detalle_pedido.php?id=<?= $pedido['id_pedido'] ?>"> Ver detalles </a>
                </td>
            </tr>
        <?php endwhile; ?>

        </table>
    </div>
    </body>
    </html>