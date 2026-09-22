<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
// CARGA DE EDITOR DE TEXTO
print "<script language=\"javascript\" type=\"text/javascript\" src=\"/herramientas/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>";
print "<script language=\"javascript\" type=\"text/javascript\">\n";
print "tinyMCE.init({\n";
print "	mode : \"textareas\",\n";
print "	theme : \"advanced\",\n";
print "	theme_advanced_buttons1 : \"bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink\",\n";
print "	theme_advanced_buttons2 : \"\",";
print "	theme_advanced_buttons3 : \"\",";
print "	theme_advanced_toolbar_location : \"top\",";
print "	theme_advanced_toolbar_align : \"left\",";
print "	theme_advanced_statusbar_location : \"bottom\",";
print "	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\"";
print "});";
print "</script>";
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/editar_contenido_administracion-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;

$listado = mysqli_fetch_object($result);
print "<form action=\"/administra/Administra/editar_contenido_administracion_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input name=\"contenido\" type=\"hidden\" value=\"".$contenido."\">";
print "<h1>".$listado->Titulo."</h1>";
print "<label for=\"Texto\">".$lang["texto"].": </label><textarea id=\"Texto\" name=\"Texto\" cols=\"94\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Breve."</textarea>";
print "<input type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>