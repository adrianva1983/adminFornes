<?php
//VERSIÓN: v1.1 2014-8-12
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$codigoIdioma = $_GET["codigoIdioma"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/glosario_anadir_termino-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
// CARGA DE EDITOR DE TEXTO
print "<script language=\"javascript\" type=\"text/javascript\" src=\"/herramientas/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>";
print "<script language=\"javascript\" type=\"text/javascript\">\n";
print "tinyMCE.init({\n";
print "	mode : \"exact\",\n";
print "	elements : \"Breve\",\n";
print "	width : \"90%\",\n";
print "	language : \"".$_SESSION['idioma']."\",\n";
print "	theme : \"advanced\",\n";
//print "	plugins : \"safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager\",\n";
print "	plugins : \"safari,spellchecker,pagebreak,style,layer,table,fullscreen\",\n";
print "	theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,separator,bullist,numlist,separator,undo,redo,link,unlink,separator,spellchecker,fullscreen,code\",\n";
print "	theme_advanced_buttons2 : \"justifyleft,justifycenter,justifyright,justifyfull,separator,tablecontrols\",";
print "	theme_advanced_toolbar_location : \"top\",";
print "	theme_advanced_toolbar_align : \"left\",";
print "	theme_advanced_statusbar_location : \"bottom\",";
print "	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\"";
print "});";
print "</script>";
print "<form action=\"/administra/Contenidos/glosario_anadir_termino_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Termino\">".$lang["termino"].": </label><input id=\"Termino\" name=\"Termino\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Descripcion\">".$lang["descripcion"].": </label><textarea id=\"Descripcion\" name=\"Descripcion\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\"></textarea></li>";
print "<li><label for=\"Url\">".$lang["enlace"].": </label><input id=\"Url\" name=\"Url\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "</ul>";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
else print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$_SESSION['idioma']."\">";
print "<input name=\"id\" type=\"hidden\" value=\"".$id."\">";
print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>