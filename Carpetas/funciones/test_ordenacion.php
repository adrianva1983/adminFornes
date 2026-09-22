<?php
 //$accion:
 //0-Solo listar la lista de orden
 //1-Ordena ascendiendo
 //2-Ordena descendiendo
 //3-Ordena subiendo $elemento
 //4-Ordena bajando $elemento
 //5-Primero el $elemnto
 //6-Ultimo el $elemento

//Si quieres añadir elementos nuevos al principio, asignales de orden -1 o no les asignes Orden
//Si quieres añadir elemetos al final, 
//asignales de orden el maximo valor del campo Orden en la Base de datos
//Si no asignas valor a Orden el campo estará vacio y en el SELECT
//los elementos estan al principio, si quieres ordenarlos así,
//solo tienes que no asignar orden y luego llamar a ordena o a ordenaTotal después de crear
//el elemento, sección o publicación.
//
//Llama a ordenaTotal para ordenar todo:
//Ejemplo:ordenaTotal("Secciones","IdPadre",1);
//Llama a ordena para ordenar una sección o una publicación:
//Ejemplo:ordena("Secciones","IdPadre","80",5,3);

//Puedes usarlo tambien así: ordenaTotal("Publicaciones","IdSeccion",1)

require("ordenacion.php");

if ($_GET["test"]=="s")
$test=true;
else $test=false;
echo "mmm<li>$test</li>";

//Ordena todo en modo ascendente
//ordenaTotal("Secciones","IdPadre",1);

//Ordena todo en modo ascendente
ordenaTotal("Publicaciones","IdSeccion",1);

//Ordena todo en modo descendente
//ordenaTotal("Secciones","IdPadre",2);

//Ordena solo una seccion: la raiz
//ordena("Secciones","IdPadre","NULL",1);

//Ordena descendiente solo una seccion: La de padre 80
//ordena("Secciones","IdPadre","80",2);

//Ordena ascendiente solo una seccion: La de padre 80
//ordena("Secciones","IdPadre","80",1);

//Ordena subiendo un elemento: En la Seccion de padre 80 el elemento de orden 3
//ordena("Secciones","IdPadre","80",3,3);

//Ordena bajando un elemento: En la Seccion de padre 80 el elemento de orden 3
//ordena("Secciones","IdPadre","80",4,3);

//Ordena poniendo el primero: En la Seccion de padre 80 el elemento de orden 3
//ordena("Secciones","IdPadre","80",5,3);

//Ordena poniendo el último: En la Seccion de padre 80 el elemento de orden 4
//ordena("Secciones","IdPadre","80",6,4);

?>