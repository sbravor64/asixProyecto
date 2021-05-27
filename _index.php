<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
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
        background-color: #4CAF50;
        color: white;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenid@ a W.A.H (Wordpress Automatic Hosting
).</h1>
        
    </div>
    <p>
        <a href="create-container.php" class="btn btn-success">Crear contenedor</a>
        <a href="reset-password.php" class="btn btn-warning">Cambia tu contraseña</a>
        <a href="logout.php" class="btn btn-danger">Cierra la sesión</a>

        <?php
        $fila = 1;
        if (($gestor = fopen("/home/ubuntu/pruebas/test.csv", "r")) !== FALSE) {
            echo '<table id="customers">';
            echo '<tr><th>ID de POD</th><th>Nombre</th><th>Estado</th><th>Fecha de Creación</th></tr>';
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $numero = count($datos);
                //echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;
                echo "<tr>";
                for ($c=0; $c < $numero; $c++) {
                    //echo $datos[$c] . "<br />\n";

                    echo "<td>".$datos[$c] ."</td>";
                        
                }
                echo "</tr>";
            }
            echo '</table>';

            fclose($gestor);
        }
        ?>
        
    </p>
</body>
</html>