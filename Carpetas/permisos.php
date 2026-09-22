<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/permisos-".$_SESSION['idioma'].".conf");
print "<div id=\"botonera_cabecera\"><ul>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=permisos_GRUPOS&ruta=".$ruta."&seccion=".$seccion."\">";
print "<img src=\"/administra/Imagenes/grupos.png\"> ";
print $lang["grupos"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=permisos_USUARIOS&ruta=".$ruta."&seccion=".$seccion."\">";
print "<img src=\"/administra/Imagenes/usuario.png\"> ";
print $lang["usuarios"]."</a></li>";
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>