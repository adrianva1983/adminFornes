<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_encuesta_opcion-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$idencuesta;

$listado = mysqli_fetch_object($result);
print "<form action=\"/administra/Carpetas/editar_encuesta_opcion_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input name=\"encuesta\" type=\"hidden\" value=\"".$encuesta."\">";
print "<input name=\"idencuesta\" type=\"hidden\" value=\"".$idencuesta."\">";
print "<ul>";
print "<li><label for=\"Opcion\">".$lang["opcion"].": </label><input id=\"Opcion\" name=\"Opcion\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
if ($listado->Foto!="") print "<li><img src=\"/Imagenes/".$listado->Foto."\" width=\"".$listado->AnchoFoto."\"></li>";
print "<li><label for=\"Foto\">".$lang["imagen"].": </label><input id=\"Foto\" name=\"Foto\" type=\"file\">";
print "<li><label for=\"AnchoFoto\">".$lang["anchoImagen"].": </label><input id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\" value=\"".$listado->AnchoFoto."\"> <label for=\"AltoFoto\">".$lang["altoImagen"].": </label><input id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\" value=\"".$listado->AltoFoto."\"></li>";
print "<li><label for=\"Alternativo\">".$lang["alternativo"].": </label><input id=\"Alternativo\" name=\"Alternativo\" type=\"text\" value=\"".$listado->Alternativo."\" size=\"50\" maxlength=\"255\"></li>";
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

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>