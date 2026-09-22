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
$requete = "SELECT * FROM `Contabilidad` WHERE `Id`=".$_GET["Id"];

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print "<form action=\"/administra/Gestion/editar_apunte_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Titulo\">".$lang["titulo"].":</label><br/><input name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Importe\">".$lang["importe"].":</label><br/><input name=\"Importe\" type=\"text\" value=\"".$listado->Importe."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Fecha\">".$lang["fecha"].":</label><br/><input name=\"Fecha\" id=\"Fecha\" type=\"text\" value=\"".$listado->Fecha."\" size=\"20\" maxlength=\"20\"></li>";
print "<li><label for=\"IdCliente\">".$lang["cliente"].":</label><br/><input name=\"IdCliente\" type=\"text\" value=\"".$listado->IdCliente."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"IdCuenta\">".$lang["cuenta"].":</label><br/><input name=\"IdCuenta\" type=\"text\" value=\"".$listado->IdCuenta."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"IdEmpresa\">".$lang["empresa"].":</label><br/><select name=\"IdEmpresa\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `FacturasEmpresas` ORDER BY `NombreComercial`;";

if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option ";
		if ($listado2->Id == $listado->IdEmpresa) print "selected ";
		print "value=\"".$listado2->Id."\">".$listado2->NombreComercial." (".$listado2->DenominacionSocial.")</option>";
	}
}
print "</select></li>";
print "<li><label for=\"IdFactura\">".$lang["facturaRelacionada"].":</label><br/><input name=\"IdFactura\" type=\"text\" value=\"".$listado->IdFactura."\" size=\"100\" maxlength=\"200\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "<input type='hidden' name='Id' value='".$_GET["Id"]."'/>";
print "</form>";
?>