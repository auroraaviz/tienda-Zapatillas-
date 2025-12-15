<!DOCTYPE html>

<html lang = "es"> 

<head>
    <meta charset = "UTF-8">
    <title> Login Tienda </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <div class = "container">
    <h2> Login </h2>
    
    <?php 

    //mensajes de error
    if(isset($_GET['error'])) {
        echo '<p class = "error">';
        if ($_GET['error'] == 1) echo "Contraseña incorrecta";
        if ($_GET['error'] == 2) echo "El email no existe.";
        echo '</p>';
    }
    //mensaje de registro completado
    if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
        echo '<p class = "success"> Registro completado. </p>';
    }
    ?>

<!--formulario de login con POST-->
<form action = "validar_login.php" method = "POST">
        
    <label> Email: </label>
    <input type = "email" name = "email" required> 

    <label> Contraseña: </label>
    <input type = "password" name = "password" required> 

    <input type = "submit" value = "Entrar"> 

</form>

    <p>
        Si aún no tienes cuenta <a href="registro.php">Regístrate aquí</a>
    </p>
</div>

</body>

</html>