<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_rss-".$_SESSION['idioma'].".conf");
print "<form action=\"../Carpetas/editar_rss_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;

$listado = mysqli_fetch_object($result);
//Listamos el formulario
print "<ul>";
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["fuente"].": <input name=\"Fuente\" type=\"text\" value=\"".$listado->Redireccionar."\" size=\"50\" maxlength=\"255\"></li>";
$variables = explode("/",$listado->Breve);
$temp = explode(":",$variables[0]);
if ($temp[1]=="crear") print "<li>".$lang["modo"].": ".$lang["crear"]." <input type=\"radio\" checked name=\"Modo\" value=\"crear\" style=\"display:inline;\">";
else print "<li>".$lang["modo"].": ".$lang["crear"]." <input type=\"radio\" name=\"Modo\" value=\"crear\" style=\"display:inline;\">";
if ($temp[1]=="visualizar") print $lang["visualizar"]." <input type=\"radio\" checked name=\"Modo\" value=\"visualizar\" style=\"display:inline;\">";
else print $lang["visualizar"]." <input type=\"radio\" name=\"Modo\" value=\"visualizar\" style=\"display:inline;\">";
print "</li>";
$temp = explode(":",$variables[1]);
if ($temp[1]=="si") print "<li>".$lang["ampliable"].": ".$lang["si"]." <input type=\"radio\" checked name=\"Ampliable\" value=\"si\" style=\"display:inline;\">";
else print "<li>".$lang["ampliable"].": ".$lang["si"]." <input type=\"radio\" name=\"Ampliable\" value=\"si\" style=\"display:inline;\">";
if ($temp[1]=="no") print $lang["no"]." <input type=\"radio\" checked name=\"Ampliable\" value=\"no\" style=\"display:inline;\">";
else print $lang["no"]." <input type=\"radio\" name=\"Ampliable\" value=\"no\" style=\"display:inline;\">";
print "</li>";
$temp = explode(":",$variables[2]);
print "<li>".$lang["estado"].": <select id=\"estado\" name=\"estado\">";
if ($temp[1]=="visible") print "<option value=\"visible\" selected>".$lang["visible"]."</option>";
else print "<option value=\"visible\">".$lang["visible"]."</option>";
if ($temp[1]=="oculto") print "<option value=\"oculto\" selected>".$lang["oculto"]."</option>";
else print "<option value=\"oculto\">".$lang["oculto"]."</option>";
if ($temp[1]=="pendiente") print "<option value=\"pendiente\" selected>".$lang["pendiente"]."</option>";
else print "<option value=\"pendiente\">".$lang["pendiente"]."</option>";
print "</select></li>";
$temp = explode(":",$variables[3]);
print "<li>".$lang["syncMax"].": <input name=\"syncMax\" type=\"text\" value=\"".$temp[1]."\" size=\"2\" maxlength=\"2\"></li>";

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<input name=\"IdContenido\" type=\"hidden\" value=\"".$contenido."\">";
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