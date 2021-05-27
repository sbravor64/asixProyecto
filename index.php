<?php
   session_start();
    
   // chequear si está la sesión iniciada
   if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
       header("location: login.php");
       exit;
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <title>Inicio</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <style>
      body {font-family: "Lato", sans-serif}
      .mySlides {display: none}
   </style>
   <body>
      <div class="w3-top">
         <div class="w3-bar w3-black w3-card">
            <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
            <a href="#" class="w3-bar-item w3-button w3-padding-large">INICIO</a>
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
            <h2 class="w3-wide">W.A.H</h2>
            <p class="w3-opacity"><i>Wordpress Automatic Hosting</i></p>
            <p class="w3-justify">Has pensado alguna vez lo difícil que es crear una página web para tu empresa, con W.A.H crea tu servidor web con solo un formulario, dispondrás de wordpress como tu gestor de contenido.</p>
         </div>
         <div class="w3-black" id="info">
            <div class="w3-container w3-content w3-padding-64" style="max-width:800px">
               <h2 class="w3-wide w3-center">INFORMACIÓN</h2>
               <p class="w3-opacity w3-center"><i>Proyecto Final de Grado (ASIX)</i></p>
               <br>
               <div class="w3-container w3-padding-32" id="quienes somos">
                  <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16">¿Quiénes somos?</h3>
                  <p>W.A. Hosting (Wordpress Automatic Hosting) consiste en facilitar la creación de hosting
                     (servicio de alojamiento para sitios web) y automatizar este servicio. Los usuarios
                     que se den de alta, mediante un panel web, podrán crear su hosting gratuitamente solo
                     rellenando un formulario con el nombre y las características que deseen en ese contenedor (hosting).
                  </p>
               </div>
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
