<?php
//VERSIÓN: v1.0 2013-11-05
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pasada = $_GET["pasada"];
$Id = $_GET["Id"];
$seccion = $_GET["seccion"];
$ruta = $_GET["ruta"];


if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require("../../Interface/conexion.php");
$requete = "UPDATE `Secciones` SET `Visibilidad` = '$pasada' WHERE `Id` =$Id;";
mysqli_query($db,$requete);
require("../../Interface/cierre.php");
//Recargamos el directorio en curso
if (isset($seccion)&&$seccion!=""){
	header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else {
	header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>