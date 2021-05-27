<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
$comando = shell_exec("sudo podman pod ls");
echo "<pre>$comando</pre>";

$comando1 = shell_exec("sudo podman container ls");
echo "<pre>$comando1</pre>";
?>

<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nom_bd = "use `wordpress`;" . "\n";
$nombre_contenedor = $nombre_dominio = $titulo_web = $username = $email = $password = $confirm_password = "";
$nombre_contenedor_err = $nombre_dominio_err = $titulo_web_err = $username_err = $email_err = $password_err = $confirm_password_err = "";
$fileSQL = $comando = $sql = "";

$file_sample = 'yml/container_sample.yml';
$copy = 'yml/container.yml';

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fileSQL=fopen('sql/update.sql', 'w');
    fwrite($fileSQL, $nom_bd);

    // Validate nombre_contenedor
    if(empty(trim($_POST["nombre_contenedor"]))){
        $nombre_contenedor_err = "Por favor ingrese el nombre del contenedor.";
    } else{
        $nombre_contenedor = $_POST["nombre_contenedor"];        

        // reemplazar el nombre del pod por defecto
        copy($file_sample, $copy);
        file_put_contents($copy,str_replace('nombreContainer',"$nombre_contenedor",file_get_contents($copy)));
        
        // Creación de pod con 3 contenedores
        shell_exec("sudo podman play kube /var/www/site/html/yml/container.yml");
        shell_exec("rm -rf yml/container.yml");

    }

    // Validate titulo_web
    if(empty(trim($_POST["titulo_web"]))){
        $titulo_web_err = "Por favor ingrese el título para su web.";
    } else{
        $titulo_web = $_POST["titulo_web"];
        // Prepare a select statement
        $sql = "UPDATE wp_options SET option_value = '$titulo_web'
                WHERE option_name = 'blogname';" . "\n";
        
        fwrite($fileSQL, $sql);
    }

    // Validate nombre_dominio
    if(empty(trim($_POST["nombre_dominio"]))){
        $nombre_dominio_err = "Por favor ingrese el nombre del dominio";
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña al menos debe tener 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
        
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirma tu contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "No coincide la contraseña.";
        }
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor ingrese un email";
    }

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese un nombre de usuario";
    } else if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        //$nombre_contenedor_new = $nombre_contenedor;        

        $username = $_POST["username"];
        $nombre_dominio = $_POST["nombre_dominio"] . ".proyecto.ccff.site:8080";
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        // Prepare a select statement
        $sql = "UPDATE wp_users SET user_login = '$username',
                user_pass = MD5('$password'), user_email = '$email', 
                user_url = '$nombre_dominio'
                WHERE ID = 1;" . "\n";
            
        fwrite($fileSQL, $sql);
        fclose($fileSQL);

        sleep(2);
        shell_exec("sudo sh scripts/script.sh $nombre_contenedor");
        header("location: instance.php");
    }
}
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear contenedor</title>
    <meta name="viewport" content="width=p, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="styles/create-instance.css">
</head>
<body>
    <div class="wrapper">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="container">
            <h1>Crear contenedor</h1>
            <p>Completa este formulario para crear tu contenedor.</p>
            <hr>

            <div class="form-group <?php echo (!empty($nombre_contenedor_err)) ? 'has-error' : ''; ?>">
                <label for="nombre_contenedor"><b>Nombre del contenedor</b></label>
                <input type="text" placeholder="Añadir nombre de contenedor" name="nombre_contenedor" value="<?php echo $nombre_contenedor; ?>" required>
                <span class="help-block"><?php echo $nombre_contenedor_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($nombre_dominio_err)) ? 'has-error' : ''; ?>">
                <label for="nombre_dominio"><b>Nombre de dominio</b></label>
                <input type="text" placeholder="Añadir nombre de dominio" name="nombre_dominio" value="<?php echo $nombre_dominio; ?>" required>
                <span class="help-block"><?php echo $nombre_dominio_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($titulo_web_err)) ? 'has-error' : ''; ?>">
                <label for="nombre_dominio"><b>Título web</b></label>
                <input type="text" placeholder="Añadir título web" name="titulo_web" value="<?php echo $titulo_web; ?>" required>
                <span class="help-block"><?php echo $titulo_web_err; ?></span>
            </div>

            <h4>Wordpress</h3>
            <br>

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="username"><b>Usuario</b></label>
                <input type="text" placeholder="Añadir usuario" name="username" value="<?php echo $username; ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label for="email"><b>E-mail</b></label>
                <input type="email" placeholder="Añadir Email"  name="email" value="<?php echo $email; ?>" required>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="password"><b>Contraseña</b></label>
                <input type="password" placeholder="Añadir Contraseña" name="password" value="<?php echo $password; ?>" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
        
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label for="password"><b>Repite tu Contraseña</b></label>
                <input type="password" placeholder="Añadir Contraseña" name="confirm_password" value="<?php echo $confirm_password; ?>" required>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
        
            <hr>

            <div class="form-group">
                <button type="submit" class="createbtn">Crear</button>
            </div>

          </div>
          
          <div class="container index">
            <a href="instance.php">Volver</a>
          </div>
        </form>
    </div>    
</body>
</html>