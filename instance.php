<?php
   session_start();
    
   // chequear si está la sesión iniciada
   if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
       header("location: login.php");
       exit;
   }
   
   // definición de variables
   $nombre_instancia = $nombre_instancia_err = $radio = $radio_err = $nombre_comprobado = $nombre_comprobado_err = "";
   
   if($_SERVER["REQUEST_METHOD"] == "POST"){
   
     // validar nombre de instancia
     if(empty(trim($_POST["nombre_instancia"]))){
         $nombre_instancia_err = "Por favor ingrese el nombre del contenedor";
     } else{
         $nombre_instancia = $_POST["nombre_instancia"];
     }
   
     // validar ration
     if(empty(trim($_POST['gestion']))){
       $radio_err = "Por favor seleccione que quiere hacer con la instancia";
     } else{
         $radio = $_POST['gestion'];
     }
   
   
     if (empty($nombre_instancia_err) && empty($radio_err)){
   
       if (($gestor = fopen("/home/ubuntu/listaInstancias.csv", "r")) !== FALSE) {
         while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
             $numero = count($datos);
             $fila++;
   
             if($datos[1] == $nombre_instancia){
               $nombre_comprobado = $nombre_instancia;
             } else {
               $nombre_comprobado_err = "Por favor ingrese un nombre válido";
             }
         }
         fclose($gestor);
       }
   
       if(empty($nombre_comprobado_err)){
         if (($gestor = fopen("/home/ubuntu/listaInstancias.csv", "r")) !== FALSE) {
           while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
               $numero = count($datos);
               $fila++;
     
               if ($_POST['gestion'] == 'iniciar') {
                 shell_exec("sudo podman pod start $nombre_instancia");
               } else if ($_POST['gestion'] == 'parar'){
                 shell_exec("sudo podman pod stop $nombre_instancia");
               } else if ($_POST['gestion'] == 'reiniciar'){
                 shell_exec("sudo podman pod restart $nombre_instancia");
               } else if ($_POST['gestion'] == 'eliminar'){
                 shell_exec("sudo podman pod stop $nombre_instancia");
                 shell_exec("sudo podman pod rm $nombre_instancia");
               }
           }
           header("location: instance.php");
           fclose($gestor);
         }
       }
     }
   
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <title>Instancias</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
   <style type="text/css">
      #customers {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      }
      #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
      }
      #customers tr:nth-child(even){background-color: #f2f2f2;}
      #customers tr:hover {background-color: #ddd;}
      #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #000000;
      color: white;
      }
   </style>
   <style>
      body {font-family: "Lato", sans-serif}
      .mySlides {display: none}
   </style>
   <body>
      <div class="w3-top">
         <div class="w3-bar w3-black w3-card">
            <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
            <a href="index.php" class="w3-bar-item w3-button w3-padding-large">INICIO</a>
            <a href="instance.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">INSTANCIAS</a>
            <a href="logout.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SALIR</a>
         </div>
      </div>
      <div id="navDemo" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
         <a href="instance.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">INSTANCIAS</a>
         <a href="logout.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">SALIR</a>
      </div>
      <div class="w3-content" style="max-width:2000px;margin-top:46px">
         <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="inicio">
            <h2 class="w3-wide">Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
            <p class="w3-opacity"><i>Gestiona tus instancias</i></p>
            <a href="create-instance.php" class="w3-button w3-light-grey w3-block">Crear Instancia</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
               <br>
               <div class="form-group <?php echo (!empty($nombre_instancia_err)) ? 'has-error' : ''; ?>">
                  <label for="nombre_dominio"><b>Instancia</b></label>
                  <input type="text" placeholder="Nombre" name="nombre_instancia" value="<?php echo $nombre_instancia; ?>" required>
               </div>
               <br>
               <div class="form-group<?php echo (!empty($radio_err)) ? 'has-error' : ''; ?>">
                  <input type="radio" name="gestion" value="iniciar"> Iniciar
                  <input type="radio" name="gestion" value="parar"> Parar
                  <input type="radio" name="gestion" value="reiniciar"> Reiniciar
                  <input type="radio" name="gestion" value="eliminar"> Eliminar
                  <button type="submit" class="enviarbtn">Enviar</button>
                  <p class="w3-opacity"><i class="help-block"><?php echo $radio_err; ?></i></p>
                  <p class="w3-opacity"><i class="help-block"><?php echo $nombre_comprobado_err; ?></i></p>
               </div>
            </form>
            <?php
               $fila = 1;
               shell_exec("sudo podman pod ls | sed 's/  /,/g' | sed '1d' | cut -d',' -f1,2,3,4 > /home/ubuntu/listaInstancias.csv");
               
               if (($gestor = fopen("/home/ubuntu/listaInstancias.csv", "r")) !== FALSE) {
                   echo '<table id="customers">';
                   echo '<tr><th>ID de POD</th><th>Nombre</th><th>Estado</th><th>Fecha de Creación</th><th>Sitio</th></tr>';
                   while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                       $numero = count($datos);
                       $fila++;
                       echo "<tr>";
                       for ($c=0; $c < $numero; $c++) {
                           echo "<td>".$datos[$c] ."</td>";  
                       }
                       echo "<td><a href='http://proyecto.ccff.site:8000'>Ver</a></td>";

                       echo "</tr>";
                   }
                   echo '</table>';
               
                   fclose($gestor);
               }
               ?>
            </p>
         </div>
      </div>
      </div>
      <footer class="w3-center w3-black w3-padding-16">
         <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></p>
      </footer>
      <script>
         var myIndex = 0;
         carousel();
         
         function carousel() {
           var i;
           var x = document.getElementsByClassName("mySlides");
           for (i = 0; i < x.length; i++) {
             x[i].style.display = "none";  
           }
           myIndex++;
           if (myIndex > x.length) {myIndex = 1}    
           x[myIndex-1].style.display = "block";  
           setTimeout(carousel, 4000);    
         }
         
         function myFunction() {
           var x = document.getElementById("navDemo");
           if (x.className.indexOf("w3-show") == -1) {
             x.className += " w3-show";
           } else { 
             x.className = x.className.replace(" w3-show", "");
           }
         }
         
         var modal = document.getElementById('ticketModal');
         window.onclick = function(event) {
           if (event.target == modal) {
             modal.style.display = "none";
           }
         }
      </script>
   </body>
</html>
