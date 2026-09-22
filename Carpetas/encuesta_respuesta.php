<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/encuestas_respuesta-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Carpetas/encuesta_respuesta_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input name=\"encuesta\" type=\"hidden\" value=\"".$encuesta."\">";
print "<ul>";
print "<li><label for=\"Opcion\">".$lang["opcion"].": </label><input id=\"Opcion\" name=\"Opcion\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Foto\">".$lang["imagen"].": </label><input id=\"Foto\" name=\"Foto\" type=\"file\">";
print "<li><label for=\"AnchoFoto\">".$lang["anchoImagen"].": </label><input id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\"> <label for=\"AltoFoto\">".$lang["altoImagen"].": </label><input id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\"></li>";
print "<li><label for=\"Alternativo\">".$lang["alternativo"].": </label><input id=\"Alternativo\" name=\"Alternativo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
if (isset($seccion))
{
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta))
{
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>