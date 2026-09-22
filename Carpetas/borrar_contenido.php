<?php
//VERSIÓN: v1.0 2014-02-11
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_GET["seccion"];
$contenido = $_GET["contenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$tipocontenido=$_GET["tipocontenido"];
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/borrar_contenido-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Carpetas/borrar_contenido_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<p class='alert alert-danger'>".$lang["seguro"]."</p>";

if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "<input name=\"contenido\" type=\"hidden\" value=\"".$contenido."\">";

print "<div class='row'><div class='col-md-6'>".$lang["confirmar"]."<br> <input class='btn btn-danger' name=\"borrar\" type=\"submit\" value=\"".$lang["borrar"]."\"></div>";
print "<div class='col-md-6'>".$lang["cancelar"]."<br> <input class='btn btn-white' name=\"cancelar\" type=\"submit\" value=\"".$lang["NOborrar"]."\"></div></div>";
print "</form>";
?>
