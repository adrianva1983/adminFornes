<?php
//VERSIÓN: v1.0 2014-05-15
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_GET["seccion"];
$ruta = $_GET["ruta"];
$codigoIdioma = $_GET["codigoIdioma"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_codigo-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
print "<form action=\"../Carpetas/nuevo_codigo_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<input name=\"Idioma\" type=\"hidden\" value=\"".$_SESSION['idioma']."\">";
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["fichero"].": <input name=\"Fichero\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["codigo"].": <textarea name=\"Breve\" cols=\"90\" rows=\"40\" class=\"form\" wrap=\"VIRTUAL\"></textarea></li>";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>