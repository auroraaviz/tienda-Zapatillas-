<?php

// validar_login.php
// Valida el login de un usuario verificando email y contraseña.
// Crea la sesión y redirige según rol: administrador o cliente.

ob_start(); // Captura TODO lo que imprima el archivo
session_start();
require "conexion.php";

//recoger datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

//se busca el usuario por el email y usamos placeholder para evitar ataques SQL Injection
$sql = "SELECT * FROM USUARIOS WHERE email = ?";
$stmt = $connection -> prepare($sql);
$stmt -> bind_param("s", $email);
$stmt -> execute();
$resultado = $stmt -> get_result();

//se comprueba si existe el usuario
if ($resultado -> num_rows === 1) {
    $usuario = $resultado -> fetch_assoc();
    
    //se comparan las contraseñas
    if (password_verify ($password, $usuario['password_hash'])) {
        //se crean variables de sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['nombre'] = $usuario['nombre'];
       
        //se comprueba el rol del usuario
        if ($usuario['rol'] === 'administrador') {
            header ("Location: admin_panel.php");
        } else {
            header ("Location: tienda.php");
        }

        exit();

    } else  {
        header("Location: login.php?error=1");
        exit();
    }

} else {
    header("Location: login.php?error=2");
    exit();
}

?>  