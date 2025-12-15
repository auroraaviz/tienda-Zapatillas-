<?php

// conexion.php
// Este archivo establece la conexión a la base de datos
// Detecta si se ejecuta en local o en el servidor y usa los datos correspondientes

//detectar si es local o servidor
if ($_SERVER['SERVER_NAME'] === 'localhost') { 
    $host = "localhost";
    $user = "root";
    $pass = "";
    $basedatos = "tiendaZapatillas";
} else { 
    $host = "sql211.infinityfree.com";
    $user = "if0_40688842";
    $pass = "Zapas10051992";
    $basedatos = "if0_40688842_tiendazapatillas";
}
//conectar con MySQL
$connection = new mysqli ($host, $user, $pass, $basedatos);

if ($connection -> connect_error) {
    die ("Conexión fallida: ". $connection -> connect_error); 
}

?>