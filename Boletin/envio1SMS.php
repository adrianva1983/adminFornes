<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/envio1SMS-".$_SESSION['idioma'].".conf");

$requete = "SELECT * FROM `SMSCreditos` WHERE `IdUsuario`='".$_SESSION['usuario_id']."'";

$creditos_disponibles = 0;
$creditos_gastados = 0;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$creditos_disponibles = $listado->Creditos;
	$creditos_gastados = $listado->TotalesConsumidos;
}
if ($creditos_disponibles<1)
{
	print "<p class=\"mensajeKO\">Error: No tiene suficientes cr&eacute;ditos para realizar este env&iacute;o!</p>";
	exit;
}
else
{	
	print "<form action=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=envio1SMS_2\" enctype=\"multipart/form-data\" method=\"POST\">";
	print "<ul>";	
	print "<li>".$lang["texto"].": <input name=\"Texto\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
	print "<li>".$lang["remitente"].": <input name=\"Remitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
	print "<li>".$lang["destinatario"].": <input name=\"Destinatario\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
	print "<li>".$lang["WAP"].": <input name=\"WAPPush\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";	
	print "</ul>";
	print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
	print "</form>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>