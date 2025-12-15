<?php

// test_sesion.php
// Valida que haya sesión iniciada y que el usuario sea administrador.
// Redirige a login.php si no se cumple alguna condición.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar rol
if (!isset($_SESSION['rol']) || strtolower(trim($_SESSION['rol'])) !== 'administrador') {
    header("Location: login.php");
    exit();
}
?>