<?php
//VERSIÓN: v1.1 2013-10-23
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$contenido = $_GET['contenido'];
$seccion = $_GET["seccion"];
$referenciaIdioma = $_GET["referenciaIdioma"];
$codigoIdioma = $_GET["codigoIdioma"];
$ruta = $_GET["ruta"];
$rutaOrigen = $_GET["rutaOrigen"];
$seccionOrigen = $_GET["seccionOrigen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_producto-".$_SESSION['idioma'].".conf");
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
//JAVASCRIPTS NECESARIOS
print "<script type=\"text/javascript\">";
//Scripts de fecha
print "\$(function() {";
print "\$( \"#Fecha\" ).datepicker({dateFormat: 'yy-mm-dd'});";
print "});";
//Scripts de despliegue de bloques
print "function mostrar_capa(id,foto_desplegar)\n";
print "{";
print "if (document.getElementById(id).style.display==\"none\")";
print "{";
print "document.getElementById(id).style.display=\"\";";
print "document.getElementById(foto_desplegar).src=\"/administra/Imagenes/zoom_out.png\";";
print "document.getElementById(foto_desplegar).alt=\"".$lang["ocultar"]."\";";
print "document.getElementById(foto_desplegar).title=\"".$lang["ocultar"]."\";";
print "}";
print "else";
print "{";
print "document.getElementById(id).style.display=\"none\";";
print "document.getElementById(foto_desplegar).src=\"/administra/Imagenes/zoom_in.png\";";
print "document.getElementById(foto_desplegar).alt=\"".$lang["mostrar"]."\";";
print "document.getElementById(foto_desplegar).title=\"".$lang["mostrar"]."\";";
print "}";
print "}"; 
print "</script>";
//CARGA CONTADORES DE CARACTERES SEO
print "<script language=\"javascript\" type=\"text/javascript\">\n";
print "\$(document).ready(function() {";
//Scripts precio
print "\$(\"#PrecioBI\").on('change', function() {";
print "\$(\"#PrecioFinal\").val(parseFloat(\$(\"#PrecioBI\").val())+parseFloat(($(\"#PrecioBI\").val()*\$(\"#PrecioTAX\").val()/100)));";
print "});";
print "\$(\"#PrecioTAX\").on('change', function() {";
print "\$(\"#PrecioFinal\").val(parseFloat(\$(\"#PrecioBI\").val())+parseFloat(($(\"#PrecioBI\").val()*\$(\"#PrecioTAX\").val()/100)));";
print "});";
print "\$(\"#PrecioFinal\").on('change', function() {";
print "\$(\"#PrecioBI\").val(parseFloat(\$(\"#PrecioFinal\").val())/(1+parseFloat(\$(\"#PrecioTAX\").val()/100)));";
print "});";
//Contador título
print "\$(\".contador_SEOTit\").each(function(){";
print "var longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEOTit').html('<b>'+longitud+'</b> caracteres');";
print "\$(this).keyup(function(){ ";
print "var nueva_longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEOTit').html('<b>'+nueva_longitud+'</b> caracteres');";
print "if (nueva_longitud < \"15\") {";
print "\$('#longitud_contador_SEOTit').css('color', '#ff0000');";
print "}";
print "if ((nueva_longitud < \"70\")&&(nueva_longitud > \"15\")) {";
print "\$('#longitud_contador_SEOTit').css('color', '#00ff00');";
print "}";
print "if (nueva_longitud > \"70\") {";
print "\$('#longitud_contador_SEOTit').css('color', '#ff0000');";
print "}";
print "});";
print "});";
//Contador Keywords
print "\$(\".contador_SEOKey\").each(function(){";
print "var longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+longitud+'</b> caracteres');";
print "\$(this).keyup(function(){ ";
print "var nueva_longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+nueva_longitud+'</b> caracteres');";
print "if (nueva_longitud < \"50\") {";
print "\$('#longitud_contador_SEOKey').css('color', '#ff0000');";
print "}";
print "if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {";
print "\$('#longitud_contador_SEOKey').css('color', '#00ff00');";
print "}";
print "if (nueva_longitud > \"156\") {";
print "\$('#longitud_contador_SEOKey').css('color', '#ff0000');";
print "}";
print "});";
print "});";
//Contador Descripción
print "\$(\".contador_SEODesc\").each(function(){";
print "var longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+longitud+'</b> caracteres');";
print "\$(this).keyup(function(){ ";
print "var nueva_longitud = \$(this).val().length;";
print "\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+nueva_longitud+'</b> caracteres');";
print "if (nueva_longitud < \"50\") {";
print "\$('#longitud_contador_SEODesc').css('color', '#ff0000');";
print "}";
print "if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {";
print "\$('#longitud_contador_SEODesc').css('color', '#00ff00');";
print "}";
print "if (nueva_longitud > \"156\") {";
print "\$('#longitud_contador_SEODesc').css('color', '#ff0000');";
print "}";
print "});";
print "});";
print "});";
print "</script>";
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
print "<form action=\"/administra/Carpetas/editar_producto_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;

$listado = mysqli_fetch_object($result);
print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Breve\">".$lang["breve"].": </label><textarea id=\"Breve\" name=\"Breve\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\">".$listado->Breve."</textarea></li>";
print "<li><label for=\"Fecha\">".$lang["fecha"].": </label><input id=\"Fecha\" name=\"Fecha\" type=\"text\" value=\"".$listado->Fecha."\" size=\"10\" maxlength=\"10\"></li>";
if ($listado->Foto!="") 
{
	print "<li>";
	print "<img src=\"/Imagenes/".$listado->Foto."\" height=\"60px\">";
	print "<input id=\"BorrarImagen\" name=\"BorrarImagen\" type=\"checkbox\" value=\"1\" style=\"display:inline-block;\"> ".$lang["borrarImagen"];
	print "<input id=\"BorrarImagenFichero\" name=\"BorrarImagenFichero\" type=\"hidden\" value=\"/Imagenes/".$listado->Foto."\"/>";
	print "</li>";
}
print "<li><label for=\"Foto\">".$lang["imagenEditar"].": </label><input id=\"Foto\" name=\"Foto\" type=\"file\"><label for=\"FotoServidor\">".$lang["fotoServidor"].": </label><input id=\"FotoServidor\" name=\"FotoServidor\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
if ($listado->Icono!="") 
{
	print "<li>";
	print "<img src=\"/Imagenes/".$listado->Icono."\" width=\"50px\">";
	print "<input id=\"BorrarIcono\" name=\"BorrarIcono\" type=\"checkbox\" value=\"1\" style=\"display:inline-block;\"> ".$lang["borrarIcono"];
	print "<input id=\"BorrarIconoFichero\" name=\"BorrarIconoFichero\" type=\"hidden\" value=\"/Imagenes/".$listado->Icono."\"/>";
	print "</li>";
}
print "<li><label for=\"Icono\">".$lang["icono"].": </label><input id=\"Icono\" name=\"Icono\" type=\"file\"></li>";
print "<li><label for=\"PrecioBI\">".$lang["precioBI"].": </label> <input id=\"PrecioBI\" name=\"PrecioBI\" type=\"text\" value=\"".$listado->PrecioBI."\" size=\"8\" maxlength=\"6\">";
print " <label for=\"PrecioTAX\">".$lang["precioTAX"].": </label> <input id=\"PrecioTAX\" name=\"PrecioTAX\" type=\"text\" value=\"".$listado->PrecioTAX."\" size=\"8\" maxlength=\"6\">";
print " <label for=\"PrecioFinal\">".$lang["precioFinal"].": </label> <input id=\"PrecioFinal\" name=\"PrecioFinal\" type=\"text\" value=\"".($listado->PrecioBI+($listado->PrecioBI*$listado->PrecioTAX/100))."\" size=\"8\" maxlength=\"6\"></li>";
print "</ul>";
print "<p><a href=\"#desplegar_buscadores\" name=\"desplegar_buscadores\" onclick=\"mostrar_capa('buscadores','foto_desplegar_buscadores')\"><img src=\"/administra/Imagenes/zoom_in.png\" id=\"foto_desplegar_buscadores\" alt=\"".$lang["desplegar"]."\" title=\"".$lang["desplegar"]."\"> ".$lang["especialBuscadores"]."</a></p>";
print "<ul style=\"display:none;\" id=\"buscadores\">";
print "<li><label for=\"TituloBuscadores\">".$lang["tituloBuscadores"].": </label><textarea id=\"TituloBuscadores\" name=\"TituloBuscadores\" cols=\"60\" rows=\"3\" class=\"form contador_SEOTit\" wrap=\"VIRTUAL\">".$listado->TituloBuscadores."</textarea><div id=\"longitud_contador_SEOTit\"></div></li>";
print "<li><label for=\"DescripcionBuscadores\">".$lang["descripcionBuscadores"].": </label><textarea id=\"DescripcionBuscadores\" name=\"DescripcionBuscadores\" cols=\"60\" rows=\"10\" class=\"form contador_SEODesc\" wrap=\"VIRTUAL\">".$listado->DescripcionBuscadores."</textarea><div id=\"longitud_contador_SEODesc\"></div></li>";
print "<li><label for=\"URLAmigable\">".$lang["urlamigable"].": </label><input id=\"URLAmigable\" name=\"URLAmigable\" type=\"text\" value=\"".$listado->URLAmigable."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Keywords\">".$lang["keywords"].": </label><textarea id=\"Keywords\" name=\"Keywords\" cols=\"60\" rows=\"3\" class=\"form contador_SEOKey\" wrap=\"VIRTUAL\">".$listado->Keywords."</textarea><div id=\"longitud_contador_SEOKey\"></div></li>";	
print "</ul>";
print "<p><a href=\"#desplegar_presentacion\" name=\"desplegar_presentacion\" onclick=\"mostrar_capa('presentacion','foto_desplegar_presentacion')\"><img src=\"/administra/Imagenes/zoom_in.png\" id=\"foto_desplegar_presentacion\" alt=\"".$lang["desplegar"]."\" title=\"".$lang["desplegar"]."\"> ".$lang["especialPresentacion"]."</a></p>";
print "<ul style=\"display:none;\" id=\"presentacion\">";
$requete = "SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$seccion."' AND `IdContenido`='".$contenido."';";

$listado = mysqli_fetch_object($result);	
print "<li><label for=\"Notas\">".$lang["notas"].": </label><textarea id=\"Notas\" name=\"Notas\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Notas."</textarea></li>";

if ($seccion!="") print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($ruta!="") print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";

print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>