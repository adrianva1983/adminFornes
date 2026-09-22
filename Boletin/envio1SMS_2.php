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
$requete = "SELECT * FROM `SMSCreditos` WHERE `IdUsuario`='".$_SESSION['usuario_id']."'";

$creditos_disponibles = 0;
$creditos_gastados = 0;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$creditos_disponibles = $listado->Creditos;
	$creditos_gastados = $listado->TotalesConsumidos;
}
$Texto = urlencode($Texto);
if ($creditos_disponibles<1)
{
	die ("Error: No tiene suficientes cr&eacute;ditos para realizar este env&iacute;o!");
	exit;
}
else
{
	print "<p>";
	$NumEnvios = 0;
	print "<strong>".$Destinatario.":</strong> ";
	require($_SERVER['DOCUMENT_ROOT']."/herramientas/envioSMS.php");
	enviarSMS($Destinatario,$Texto,$Remitente);	
	print "<br/>";
	$creditos_disponibles--;
	$creditos_gastados++;
	$NumEnvios++;
	$requete2 ="UPDATE `SMSCreditos` SET `Creditos` = '".$creditos_disponibles."',`TotalesConsumidos`='".$creditos_gastados."' WHERE `IdUsuario`= '".$_SESSION['usuario_id']."'";
	mysql_query($requete2,$db);
	print "</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<hr/>";
print "<p><strong>N&Uacute;MERO DE SMS MANDADOS:</strong> ".$NumEnvios."<br/>";
print "<strong>N&Uacute;MERO DE CR&Eacute;DITOS DISPONIBLES TRAS ENV&Iacute;O:</strong> ".$creditos_disponibles."<br/>";
print "</p>";
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=SMS\">Volver al listado de SMS Marketing</a></p>";
?>