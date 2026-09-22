<?php
//VERSIÓN: v1.0 2014-05-29
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$contenido = $_GET["contenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$referenciaIdioma = $_GET["referenciaIdioma"];
$codigoIdioma = $_GET["codigoIdioma"];
$rutaOrigen = $_GET["rutaOrigen"];
$seccionOrigen = $_GET["seccionOrigen"];
$herramientaOrigen = $_GET["herramientaOrigen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_formulario-".$_SESSION['idioma'].".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
print "<form action=\"/administra/Carpetas/editar_formulario_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * From Contenidos WHERE Id=".$contenido;

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
// Hacemos una consulta para ver las distintas plantillas de formulario
$requete2 = "SELECT `Id`,`Titulo` FROM `Formularios` WHERE `Tipo`='TITULO';";

// Listamos las plantillas existentes de formulario existentes
print "<li><label for=\"PlantillaFormulario\">".$lang["tipo"].": </label><select id=\"PlantillaFormulario\" name=\"PlantillaFormulario\">";
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option value=\"".$listado2->Id."\"";
		if ($listado->IdTipoContenido ==$listado2->Id) print " selected";
		print ">".$listado2->Titulo."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Email\">".$lang["email"].": </label><input id=\"Email\" name=\"Email\" type=\"text\" value=\"".$listado->Breve."\" size=\"50\" maxlength=\"255\"></li>";
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
print "<input name=\"contenido\" type=\"hidden\" value=\"".$contenido."\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";
if ($herramientaOrigen!="") print "<input name=\"herramientaOrigen\" type=\"hidden\" value=\"".$herramientaOrigen."\">";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["editar"]."\" name=\"editar\">";
print "<input type=\"hidden\" value=\"".$_SESSION['idioma']."\" name=\"Idioma\">";
print "<input class=\"boton_riesgo\" type=\"submit\" value=\"".$lang["eliminar"]."\" name=\"eliminar\">";
print "</form>";
?>