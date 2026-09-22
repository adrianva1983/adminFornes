<?php
 //$accion:
 //0-Solo listar la lista de orden
 //1-Ordena ascendiendo
 //2-Ordena descendiendo
 //3-Ordena subiendo $elemento
 //4-Ordena bajando $elemento
 //5-Primero el $elemnto
 //6-Ultimo el $elemento

//No uses el include al menos que quieras hacer pruebas
//Si quieres probarlo todo cambia test_ordenacion.php y descomenta estas dos lineas
//include "test_ordenacion.php";
//die;
//Para ver lo que hace usa esta url:http://www.guiarestaurantes.org/administra/Carpetas/funciones/ordenacion.php?test=s

//Esto funciona si se pasa por argumentos GET a la URL
$_ordena=$_GET["ordena"];
$_familia=$_GET["familia"];
$_item=$_GET["item"];
$_iid=$_GET["iid"];
$_accion=$_GET["accion"];
$_elemento=$_GET["elemento"];
$test=$_GET["test"];

require("ordenacion_2.php");

if ($_ordena=="ordena") 
{
 //ordenaTotal($_familia,$_item,$_accion);
 ordena($_familia,$_item,$_iid,$_accion,$_elemento);
}
if ($_ordena=="total") ordenaTotal($_familia,$_item,$_accion);


//Recargamos
header("Location:../../Interface/herramienta.php?modulo=Administra&herramienta=grupo_camposadicionales&tipo=".$tipo);
?>