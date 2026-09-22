<?php
//VERSIÓN: v1.0 2015-6-1
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_apunte-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
print "<script language=\"javascript\" type=\"text/javascript\">\n";
print "\$(function() {";
print "    \$( \"#Fecha\" ).datepicker({dateFormat: 'yy-mm-dd'});";
print "});";
print "</script>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

print "<form action=\"/administra/Gestion/nuevo_apunte_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Titulo\">".$lang["titulo"].":</label><br/><input name=\"Titulo\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Importe\">".$lang["importe"].":</label><br/><input name=\"Importe\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Fecha\">".$lang["fecha"].":</label><br/><input name=\"Fecha\" id=\"Fecha\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"></li>";
print "<li><label for=\"IdCliente\">".$lang["cliente"].":</label><br/><input name=\"IdCliente\" type=\"text\" value=\"".$_GET['IdCliente']."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"IdCuenta\">".$lang["cuenta"].":</label><br/><input name=\"IdCuenta\" type=\"text\" value=\"".$_GET['IdCuenta']."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"IdEmpresa\">".$lang["empresa"].":</label><br/><select name=\"IdEmpresa\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `FacturasEmpresas` ORDER BY `NombreComercial`;";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->NombreComercial." (".$listado->DenominacionSocial.")</option>";
	}
}
print "</select></li>";
print "<li><label for=\"IdFactura\">".$lang["facturaRelacionada"].":</label><br/><input name=\"IdFactura\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>