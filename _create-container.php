<?php
// Initialize the session
session_start();

$nombre_dominio ="";
$fichero = 'pruebas/update.sql';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["nombre_dominio"]))){
        $username_err = "Por favor ingrese su dominio.";
    } else {
        $nombre_dominio = trim($_POST["new_password"]);

        $sql = "UPDATE wp_options SET option_value = $nombre_dominio
                WHERE option_name = blogname"

        echo $sql > $fichero;
    }
}

echo $nombre_dominio;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crear contenedor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="border:1px solid #ccc">
        <div class="container">
            <h1>Crear contenedor</h1>
            <p>Completa este formulario para crear tu contenedor</p>
                
            <h3>Dominio</h3>

                <label><b>Nombre de dominio</b></label>
                <input type="text" name="nombre_dominio" value="<?php echo $nombre_dominio; ?>" required>
                <br></br>

                <label><b>Título web</b></label>
                <input type="text" name="titulo_web" value="<?php echo $titulo_web; ?>" required>
                <br></br>
                
            <h3>Wordpress</h3>

                <label><b>Nombre de usuario</b></label>
                <input type="text" name="usuario" value="<?php echo $usuario; ?>" required>
                <br></br>

                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label for="password"><b>Contraseña</b></label>
                    <input type="password" name="password" value="<?php echo $password; ?>" required>
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
        
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label for="password"><b>Repite tu Contraseña</b></label>
                    <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" required>
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
            <div class="clearfix">
                <button type="submit" class="btn btn-success" value="añadir">Añadir</button>
            </div>
            <br></br>
        </div>
    </form>
</body>
</html>