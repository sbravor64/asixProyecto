<?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'usuari01');
   define('DB_PASSWORD', 'usuari01');
   define('DB_NAME', 'web');
    
   $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
   // Chequeo de la conexión
   if($link === false){
       die("ERROR: Could not connect. " . mysqli_connect_error());
   }
?>
