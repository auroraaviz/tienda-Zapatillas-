<?php

// hash.php
// Actualiza los hashes de contraseña de usuarios específicos (administrador y cliente)
// en la base de datos.

require "conexion.php";

//hashes ya generados
$administrador_hash = '$2y$10$itwO1fcyvp0pbgD98Lm9auIzaC5AwjypZb6JdGZj.b2VIBA7W.oKq';
$cliente_hash = '$2y$10$3ZqoIhKO4f2YTeWCmNMeGeCkOHU.NmYiV54RmqFyiAXiuIakvtsnq';

$email_administrador = "jefa@tienda.com";
$email_cliente = "cliente@tienda.com";

//actualizar administrador
$sql1 = $connection -> prepare ("UPDATE USUARIOS SET password_hash=? WHERE email=?");
$sql1 -> bind_param("ss", $administrador_hash, $email_administrador);
$sql1 -> execute();

//actualizar cliente
$sql2 = $connection -> prepare ("UPDATE USUARIOS SET password_hash=? WHERE email=?");
$sql2 -> bind_param("ss", $cliente_hash, $email_cliente);
$sql2 -> execute();

echo "Hashes actualizados correctamente.";
?>