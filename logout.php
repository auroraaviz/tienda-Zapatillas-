<?php 

// logout.php
// Cierra la sesión del usuario y lo redirige al login

session_start();

//vacìa todas los datos de inicios de sesión
$_SESSION = [];

// borrar la cookie de sesión si existe
// se respetan los parámetros de la cookie actual (ruta,dominio,seguridad)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: login.php");
exit();
?>