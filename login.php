<?php
   session_start();
    
   // chequear si está la sesión iniciada
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
     header("location: index.php");
     exit;
   }
    
   // Incluir fichero de configuración de mysql
   require_once "config.php";
    
   // definición de variables
   $username_err = $password_err = "";
    
   if($_SERVER["REQUEST_METHOD"] == "POST"){
    
       if(empty(trim($_POST["username"]))){
           $username_err = "Por favor ingrese su usuario.";
       } else{
           $username = trim($_POST["username"]);
       }
       
       if(empty(trim($_POST["password"]))){
           $password_err = "Por favor ingrese su contraseña.";
       } else{
           $password = trim($_POST["password"]);
       }
       
       if(empty($username_err) && empty($password_err)){
           // Preparando sql para comprobar si está registrado el usuario
           $sql = "SELECT id, username, password FROM users WHERE username = ?";
           
           if($stmt = mysqli_prepare($link, $sql)){
               mysqli_stmt_bind_param($stmt, "s", $param_username);
               
               $param_username = $username;
               
               if(mysqli_stmt_execute($stmt)){
                   mysqli_stmt_store_result($stmt);
                   
                   if(mysqli_stmt_num_rows($stmt) == 1){                    
                       mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                       if(mysqli_stmt_fetch($stmt)){
                           if(password_verify($password, $hashed_password)){
                               session_start();
                               
                               $_SESSION["loggedin"] = true;
                               $_SESSION["id"] = $id;
                               $_SESSION["username"] = $username;                            
                               
                               header("location: index.php");
                           } else{
                               $password_err = "La contraseña que has ingresado no es válida.";
                           }
                       }
                   } else{
                       $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                   }
               } else{
                   echo "Algo salió mal, por favor vuelve a intentarlo.";
               }
           }
           
           mysqli_stmt_close($stmt);
       }
       mysqli_close($link);
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="styles/login.css">
   </head>
   <body>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="border:1px solid #ccc">
         <div class="container">
            <h1>Iniciar Sesión</h1>
            <p>Completa este formulario para iniciar sesión</p>
            <hr>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
               <label ><b>Usuario</b></label>
               <input type="text" placeholder="Añadir Usuario" name="username" value="<?php echo $username; ?>" required>
               <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
               <label for="psw"><b>Contraseña</b></label>
               <input type="password" placeholder="Añadir Contraseña" name="password" required>
               <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="clearfix">
               <div class="form-group">
                  <button type="submit" class="signupbtn" value="Ingresar">Entrar</button>
               </div>
            </div>
            <p class="register">¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
         </div>
      </form>
   </body>
</html>
