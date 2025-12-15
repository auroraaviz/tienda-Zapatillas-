<?php

// confirmar_compra.php
// Consulta los artículos seleccionados en el carrito y guarda los datos en la base de datos
// muestra en forma de tabla el resumen del pedido

session_start();

//aseguramos que solo clientes logueados accedan a la confirmación de compra
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}
//recuperar el carrito de la sesión
$carrito = $_SESSION['carrito'] ?? [];

if(empty($carrito)) {
    echo "Carrito vacío. <a href='tienda.php'> Volver a la tienda </a>";
    exit();
}

//conexión con base de datos
require "conexion.php";

$total = 0;

//obtener todos los artículos del carrito
$ids = implode(',', array_keys($carrito));
$sql = "SELECT * FROM ARTICULOS WHERE id_articulo IN ($ids)";
$resultado = $connection->query($sql);

//cada linea corresponde con un un articulo
$articulos_carrito = [];
while ($row = $resultado->fetch_assoc()) {
    $articulos_carrito[$row['id_articulo']] = $row;
    $total += $row['precio'] * $carrito[$row['id_articulo']];
}

//insertar pedido en la tabla pedidos
$stmt_pedido = $connection->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)");
$stmt_pedido->bind_param("id", $_SESSION['id_usuario'], $total);
$stmt_pedido->execute();
$id_pedido = $stmt_pedido->insert_id;

//insertar cada artículo en la tabla detalle pedido
$stmt_detalle = $connection->prepare("INSERT INTO detalle_pedido (id_pedido, id_articulo, cantidad, subtotal) VALUES (?, ?, ?, ?)");
foreach ($carrito as $id_articulo => $cantidad) {
    $subtotal = $articulos_carrito[$id_articulo]['precio'] * $cantidad;
    $stmt_detalle->bind_param("iiid", $id_pedido, $id_articulo, $cantidad, $subtotal);
    $stmt_detalle->execute();
}

$_SESSION['carrito'] = [];

?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Confirmación de compra </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class = "container">
<h1> Compra confirmada </h1> 
<p> Gracias por tu compra. Resumen: </p>
    <!-- Tabla resumen de la compra -->
<table>
    <tr> 
        <th> Artículo </th> 
        <th> Precio </th> 
        <th> Cantidad </th> 
        <th> Subtotal </th> 
    </tr>
<!--recorre cada articulo del carrito, recupera la cantidad que el usuario seleccionó y calcula el total-->
<?php foreach($articulos_carrito as $id_articulo => $art):
    $cantidad = $carrito[$id_articulo] ?? 0;
    $subtotal = $art['precio'] * $cantidad;
?>

<!--muestra los datos en una tabla-->
<tr> 
    <td> <?=$art['nombre'] ?> </td>
    <td> <?=number_format($art['precio'], 2) ?> </td>
    <td> <?=$cantidad ?> </td>
    <td> <?=number_format($subtotal, 2) ?> </td>
</tr> 

<?php endforeach; ?>

<!--fila con el total-->
<tr> 
    <td colspan = "3"> Total </td>
    <td> <?=number_format($total, 2) ?> </td>
</tr>
</table>


<?php
$_SESSION['carrito'] = [];
?>

<br>
<a href = "tienda.php"> Volver a la tienda </a> 

</body>
</html> 