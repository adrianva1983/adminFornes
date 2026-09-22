<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/boletin_prueba-".$_SESSION['idioma'].".conf");
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0];	
	return $lafecha;
}
$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `Id`='".$idboletin."';";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
$asunto = utf8_encode($listado->Asunto);
$email_remitente = $listado->RemitenteMail;
$remitente = $listado->Remitente;
$email_pruebas = $listado->MailPruebas;
//Cargamos los encabezados del mail
$dominio = $_SERVER['SERVER_NAME'];
$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= "From: $remitente <$email_remitente>\r\n";
$Id = $idboletin;
include($_SERVER['DOCUMENT_ROOT']."/Plantillas/boletin_electronico.php");		
mail($email_pruebas, $asunto, $mensaje, $headers);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el boletin contenido en curso
print "<a href=\"../Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."\">".$lang["estadisticas"]."</a> - <a href=\"../Interface/herramienta.php?modulo=Boletin&herramienta=boletines\">".$lang["boletines"]."</a>";
?>