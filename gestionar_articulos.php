<?php

// gestionar_articulos.php
// Permite al administrador ver, editar o eliminar artículos existentes en la tienda.

session_start(); 
require "conexion.php";

//valida que inicia sesión el usuario administrador 
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener todos los artículos de la base de datos
$sql = "SELECT * FROM ARTICULOS";
$resultado = $connection->query($sql);
?>


<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar artículos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <div class = "admin-container">
    <h1>Artículos registrados</h1>

    <!--links de navegación-->
    <div class = "admin-links">
        <a href="admin_panel.php">Volver al panel</a>
        <a href="crear_articulo.php">Crear artículo</a>
    </div>

    <table class = "admin-table">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <!--recorrer los articulos, cada uno en una fila-->
        <?php while ($articulo = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $articulo['id_articulo'] ?></td>
                <td><?= $articulo['nombre'] ?></td>
                <td><?= $articulo['descripcion'] ?></td>
                <td><?= $articulo['precio'] ?></td>
                <td><?= $articulo['stock'] ?></td>
                <td>
                    <?php if ($articulo['imagen']): ?>
                        <img src="uploads/<?= $articulo['imagen'] ?>" width="50" alt="Imagen">
                    <?php endif; ?>
                </td>
                <td>
                    <!-- acciones sobre cada artículo-->
                    <a href="editar_articulo.php?id=<?= $articulo['id_articulo'] ?>">Editar</a>
                    
                    <form action="eliminar_articulos.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $articulo['id_articulo'] ?>">
                        <button type="submit" onclick="return confirm('¿Desea borrar el artículo?');">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
    </div>

</body>
</html>