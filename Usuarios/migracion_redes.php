<?php
//COMPROBAMOS EL NIVEL DE ACCESO
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/migracion_redes-".$_SESSION['idioma'].".conf");
if ($tipo=="")
{
	print "<ul>";
	print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=hotmail\"> <img src=\"/administra/Imagenes/msn_status_up.png\" alt=\"".$lang["MSN"]."\" title=\"".$lang["MSN"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=hotmail\"> <img src=\"/administra/Imagenes/hotmail_status_up.png\" alt=\"".$lang["Live"]."\" title=\"".$lang["Live"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=gmail\"> <img src=\"/administra/Imagenes/gmail_status_up.png\" alt=\"".$lang["Gmail"]."\" title=\"".$lang["Gmail"]."\"></a></li>";
	print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=flickr\"> <img src=\"/administra/Imagenes/flickr_status_up.png\" alt=\"".$lang["Flickr"]."\" title=\"".$lang["Flickr"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=hi5\"> <img src=\"/administra/Imagenes/hi5_status_up.png\" alt=\"".$lang["Hi5"]."\" title=\"".$lang["Hi5"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=linkedin\"> <img src=\"/administra/Imagenes/linkedin_status_up.png\" alt=\"".$lang["LinkedIn"]."\" title=\"".$lang["LinkedIn"]."\"></a></li>";
	print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=terra\"> <img src=\"/administra/Imagenes/terra_status_up.png\" alt=\"".$lang["Terra"]."\" title=\"".$lang["Terra"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=twitter\"> <img src=\"/administra/Imagenes/twitter_status_up.png\" alt=\"".$lang["Twitter"]."\" title=\"".$lang["Twitter"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=xing\"> <img src=\"/administra/Imagenes/xing_status_up.png\" alt=\"".$lang["Xing"]."\" title=\"".$lang["Xing"]."\"></a></li>";
	print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=yahoo\"> <img src=\"/administra/Imagenes/yahoo_status_up.png\" alt=\"".$lang["Yahoo"]."\" title=\"".$lang["Yahoo"]."\"></a> ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes&tipo=youtube\"> <img src=\"/administra/Imagenes/youtube_status_up.png\" alt=\"".$lang["Youtube"]."\" title=\"".$lang["Youtube"]."\"></a></li>";
	print "</ul>";
}
else
{	
	print "<form action=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes2\" enctype=\"multipart/form-data\" method=\"POST\">";
	print "<input type=\"hidden\" name=\"tipo\" value=\"".$tipo."\">";
	print "<ul>";
	print "<li><label for=\"Usuario\">".$lang["usuario"]."</label><input class=\"campo-contenido\" type=\"text\" name=\"Usuario\" value=\"\"></li>";
	print "<li><label for=\"Contrasena\">".$lang["contrasena"]."</label><input class=\"campo-contenido\" type=\"password\" name=\"Contrasena\" size=\"10\" value=\"\"></li>";	
	print "</ul>";
	print "<input class=\"boton\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"".$lang["extraer"]."\">";
	print "</form>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>