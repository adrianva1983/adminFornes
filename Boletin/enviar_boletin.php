<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/enviar_boletin-".$_SESSION['idioma'].".conf");

if ($_SESSION['usuario_nivel']>1)
{
	$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `IdPropietario`='".$_SESSION['usuario_id']."' AND `Id`='".$idboletin."'";
	
	if ($result = mysqli_query($db, $requete))
	{
	}
	else
	{
		print "<p class=\"accionError\">".$lang["sinPermisos"]."</p>";
		exit;
	}
}
print "<p class=\"mensaje\">".$lang["vasEnviar"]."</p>";
print "<form action=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=enviar_boletin2&idboletin=".$idboletin."\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"numEnvios\">".$lang["numEnvios"].":</label><br/>";
print "<select id=\"numEnvios\" name=\"numEnvios\">";
print "<option value=\"1000\" selected>1000</option>";
print "<option value=\"2000\">2000</option>";
print "<option value=\"3000\">3000</option>";
print "<option value=\"4000\">4000</option>";
print "<option value=\"5000\">5000</option>";
print "</select></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>