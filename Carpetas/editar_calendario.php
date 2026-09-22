<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_calendario-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Carpetas/editar_calendario_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;

$listado = mysqli_fetch_object($result);
//Listamos el formulario
print "<ul>";
print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Breve\">".$lang["zona"].": </label><input id=\"Breve\" name=\"Breve\" type=\"text\" value=\"".$listado->Breve."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"IdCalendario\">".$lang["tipo"].": <input id=\"IdCalendario\" name=\"IdCalendario\" type=\"text\" value=\"".$listado->IdTipoContenido."\" size=\"50\" maxlength=\"255\"></li>";
print "<input name=\"IdContenido\" type=\"hidden\" value=\"".$contenido."\">";
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