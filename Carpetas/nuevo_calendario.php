<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_calendario-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Carpetas/nuevo_calendario_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Breve\">".$lang["zona"].": </label><input id=\"Breve\" name=\"Breve\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"IdCalendario\">".$lang["tipo"].": <input id=\"IdCalendario\" name=\"IdCalendario\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>