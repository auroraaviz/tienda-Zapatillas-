<?php

// admin_panel.php
// Este archivo muestra el panel principal del administrador 
// Controla el acceso a la página verificando si el usuario ha iniciado sesión
// y si su rol es "administrador"

    session_start();

    //verifica que el usuario haya iniciado sesión
    if (!isset ($_SESSION['id_usuario'])) {
        header("Location: login.php");
        exit();
    }
    //verifica si el usuario es administrador
    if ($_SESSION['rol'] !== 'administrador') {
        echo "Acceso denegado.";
        exit();
    }
?>

<!DOCTYPE html>

<html lang = "es">

<head>
    <meta charset = "UTF-8">
    <title> Panel de Administrador </title>
    <!--hoja de estilos-->
    <link rel="stylesheet" href="style.css">
</head>

<body> 

    <div class = "container">
        <!--Mensaje de bienvenida personalizado-->
    <h1> Bienvenido <?php echo $_SESSION ['nombre']; ?> </h1>
    <h2> Panel de Administración </h2> 
    <!--Lista con opciones de gestión-->
    <ul class = "admin-menu">
        <li> <a href = "gestionar_usuarios.php"> Gestionar usuarios </a> </li>
        <li> <a href = "gestionar_articulos.php"> Gestionar artículos </a> </li>
        <li> <a href = "admin_pedidos.php"> Gestionar pedidos </a> </li> 
        <li> <a href = "logout.php"> Cerrar sesión </a> </li>
    </ul>

</div>
</body>

</html> 