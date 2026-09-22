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
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/anadir_fuentes_rss-".$_SESSION['idioma'].".conf");
print "<form action=\"../Carpetas/anadir_fuentes_rss_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["fuente"].": <input name=\"Fuente\" type=\"text\" value=\"http://\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["modo"].": ".$lang["crear"]." <input type=\"radio\" checked name=\"Modo\" value=\"crear\" style=\"display:inline;\">";
print $lang["visualizar"]." <input type=\"radio\" name=\"Modo\" value=\"visualizar\" style=\"display:inline;\">";
print "</li>";
print "<li>".$lang["ampliable"].": ".$lang["si"]." <input type=\"radio\" name=\"Ampliable\" value=\"si\" style=\"display:inline;\">";
print $lang["no"]." <input type=\"radio\" checked name=\"Ampliable\" value=\"no\" style=\"display:inline;\">";
print "</li>";
print "<li>".$lang["estado"].": <select id=\"estado\" name=\"estado\">";
print "<option value=\"visible\">".$lang["visible"]."</option>";
print "<option value=\"oculto\">".$lang["oculto"]."</option>";
print "<option value=\"pendiente\">".$lang["pendiente"]."</option>";
print "</select></li>";
print "<li>".$lang["syncMax"].": <input name=\"syncMax\" type=\"text\" value=\"1\" size=\"2\" maxlength=\"2\"></li>";
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