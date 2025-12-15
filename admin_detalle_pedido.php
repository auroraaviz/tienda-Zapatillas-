<?php 

// admin_detalle_pedido.php
// Muestra los detalles de un pedido específico para administradores
// Obtiene la info del pedido y sus artículos en una tabla

session_start();
require "conexion.php";

//comprobación del rol del usuario que inicia sesión
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
     header ("Location: login.php");
     exit();
}

//comprueba el id del pedido
if(!isset($_GET['id'])) {
    echo "Pedido no especificado";
    exit();
}
//se asegura que sea un número entero
$id_pedido = intval($_GET['id']);

//consulta los datos de el pedido
$sql_pedido = "
    SELECT p.id_pedido, p.fecha, p.total, u.nombre, u.email
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_pedido = ?
    ";
//seguridad para evitar inyecciones sql
$stmt_pedido = $connection->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $id_pedido);
$stmt_pedido->execute();
$result_pedido = $stmt_pedido->get_result();

if ($result_pedido->num_rows === 0) {
    echo "Pedido no encontrado";
    exit();
}
//se guarda la info del pedido
$pedido = $result_pedido->fetch_assoc(); 

//se obtienen los artículos que forman parte del pedido y sus datos
$sql_detalle = "
    SELECT a.nombre, a.precio, dp.cantidad, dp.subtotal
    FROM detalle_pedido dp
    JOIN articulos a ON dp.id_articulo = a.id_articulo
    WHERE dp.id_pedido = ?
";

$stmt_detalle = $connection->prepare($sql_detalle);
$stmt_detalle->bind_param("i", $id_pedido);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
?>

<!DOCTYPE html>
<html lang = "es">

<head>
    <meta charset="UTF-8">
    <title> Detalle del pedido </title>
    <link rel = "stylesheet" href = "style.css">
</head>

<body>
    <div class = "container">
        <h1>Detalle del pedido # <?= $pedido['id_pedido'] ?> </h1>
        <!--datos del pedido-->
        <p> Cliente: <?= htmlspecialchars($pedido['nombre']) ?> </p>
        <p> Email: <?= htmlspecialchars($pedido['email']) ?> </p>
        <p> Fecha: <?= $pedido['fecha'] ?> </p>

        <table border = "1" cellpadding = "5" cellspacing = "0">

        <tr>
            <th> Artículo </th>
            <th> Precio <th>
            <th> Cantidad </th>
            <th> Subtotal </th>
        </tr>
        <!--se recorre cada articulo mostrando su info-->
        <?php while ($detalle = $result_detalle->fetch_assoc()): ?>
            <tr>
                <td> <?= htmlspecialchars($detalle['nombre']) ?> </td>
                <td> <?= number_format ($detalle['precio'],2) ?> </td>
                <td> <?= $detalle['cantidad'] ?> </td>
                <td> <?= number_format($detalle['subtotal'],2) ?> </td>
        </tr>
        <?php endwhile; ?>

        <tr>
            <td colspan = "3"> Total </td>
            <td> <?= number_format($pedido['total'],2) ?> </td>
        </tr>

        </table>

        <a href = "admin_pedidos.php"> Volver a los pedidos </a> 