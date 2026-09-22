<?php
//VERSIÓN: v1.0 2014-05-29
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$referenciaIdioma = $_GET["referenciaIdioma"];
$codigoIdioma = $_GET["codigoIdioma"];
$rutaOrigen = $_GET["rutaOrigen"];
$seccionOrigen = $_GET["seccionOrigen"];
$herramientaOrigen = $_GET["herramientaOrigen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_formulario-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
print "<form action=\"/administra/Carpetas/nuevo_formulario_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
// Hacemos una consulta para ver las distintas plantillas de formulario
$requete = "SELECT `Id`,`Titulo` FROM `Formularios` WHERE `Tipo`='TITULO';";

// Listamos las plantillas existentes de formulario existentes
print "<li><label for=\"PlantillaFormulario\">".$lang["tipo"].": </label><select id=\"PlantillaFormulario\" name=\"PlantillaFormulario\">";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
  print "<option value=\"".$listado->Id."\">".$listado->Titulo."</option>";
 }
}
print "</select></li>";
print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Email\">".$lang["email"].": </label><input id=\"Email\" name=\"Email\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";
if ($herramientaOrigen!="") print "<input name=\"herramientaOrigen\" type=\"hidden\" value=\"".$herramientaOrigen."\">";
print "<input type=\"hidden\" name=\"Idioma\" value=\"".$_SESSION['idioma']."\">";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>