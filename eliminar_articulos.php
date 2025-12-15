<?php

// eliminar_articulos.php
// Permite al administrador eliminar un artículo de la base de datos usando su ID, 
// con seguridad contra inyección SQL.

require "test_sesion.php"; 
require "conexion.php";

//Comprobación de ID enviado por POST
if(!isset($_POST['id'])) {
    echo "Falta ID de articulo";
    exit();
}

$id_articulo = intval($_POST['id']);

//eliminación de un artículo con prepare para evitar inyecciones sql
$sql = "DELETE FROM ARTICULOS WHERE id_articulo = ?";
$stmt = $connection -> prepare($sql);
$stmt -> bind_param ("i", $id_articulo);
$stmt -> execute();

header ("Location: gestionar_articulos.php");
exit();

?>