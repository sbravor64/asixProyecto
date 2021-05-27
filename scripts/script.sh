#!/bin/sh
#podman exec -i admin2-mimariadb bash -c 'exec mysql -uandres -p"Andres_10"' < pruebas/update.sql
#echo "Hola esto es un mensaje en el codigo de shell script<br>";
#echo "comando n 1:<br>";
#date

podman exec -i $1-mariadb bash -c 'exec mysql -uandres -p"Andres_10"' < sql/update.sql

podman container ls -a

#echo "<br>";
#echo "comando n 2:<br>";
#lsb_release -a
