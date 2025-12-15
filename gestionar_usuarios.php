<?php

// gestionar_usuarios.php
// Permite al administrador ver, editar o eliminar usuarios registrados en la tienda.

session_start(); 
require "conexion.php";

//valida que inicia sesión el usuario administrador 
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener todos los usuarios de la base de datos
$sql = "SELECT * FROM USUARIOS";
$resultado = $connection->query($sql);
?>


<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar usuarios</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class = "container">
    <h1>Usuarios registrados</h1>
    <!--links de navegación-->
    <a href="admin_panel.php">Volver al panel</a>
    <a href="crear_usuario.php">Crear usuario</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>

        <!--recorre los usuarios, cada fila corresponde a un usuario
        se usa htmlspecialchars para evitar inyecciones de codigo-->
        <?php while ($usuario = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                <td><?= htmlspecialchars($usuario['direccion']) ?></td>
                <td>
                    <!--acciones sobre cada usuario-->
                    <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>">Editar</a>

                    <form action="eliminar_usuario.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit"
                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>

    </div>
</body>
</html>