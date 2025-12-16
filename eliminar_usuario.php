<?php

// eliminar_usuario.php
// Permite al administrador eliminar un usuario, 
// evitando eliminarse a sí mismo o a otros administradores.

require "test_sesion.php";
require "conexion.php";

//comprobación de id enviado por POST
if (!isset($_POST['id'])) {
    header("Location: gestionar_usuarios.php");
    exit();
}

$id_usuario = intval($_POST['id']);

// Evita que el usuario administrador elimine su cuenta por error
if ($id_usuario == $_SESSION['id_usuario']) {
    header("Location: gestionar_usuarios.php?error=self_delete");
    exit();
}

// Comprobar rol del usuario a eliminar
$sqlCheck = "SELECT rol FROM USUARIOS WHERE id_usuario = ?";
$stmtCheck = $connection->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id_usuario);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$usuarioBorrar = $resultCheck->fetch_assoc();



// Eliminar usuario
$sql = "DELETE FROM USUARIOS WHERE id_usuario = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

header("Location: gestionar_usuarios.php?success=1");
exit();
?>