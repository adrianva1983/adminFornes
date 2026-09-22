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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/SMS-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `SMSCreditos` WHERE `IdUsuario`='".$_SESSION['usuario_id']."'";

$creditos_disponibles = 0;
$creditos_gastados = 0;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$creditos_disponibles = $listado->Creditos;
	$creditos_gastados = $listado->TotalesConsumidos;
}


$requete = "SELECT * FROM `SMSContenido` ORDER BY `FechaEnvio`";

print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/phone_envio.png\" alt=\"Enviar SMS en bloque\"> :: ".$lang["enviarSMSbloque"]."<br/>";
print "<img src=\"/administra/Imagenes/phone_editar.png\" alt=\"Editar SMS en bloque\"> :: ".$lang["editarSMSbloque"]."<br/>";
print "<strong>".$lang["creditosDisponibles"].":</strong> ".$creditos_disponibles."<br/>";
print "<strong>".$lang["creditosTotales"].":</strong> ".$creditos_gastados."<br/>";
print "</div>";
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		if (($_SESSION['usuario_nivel']<2)||(($_SESSION['usuario_nivel']==2)&&($listado->IdPropietario==$_SESSION['usuario_id'])))
		{
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=enviar_SMSBloque&idSMS=".$listado->IdSMS."\"><img src=\"/administra/Imagenes/phone_envio.png\" title=\"".$lang["enviarSMSbloque"]."\" alt=\"".$lang["enviarSMSbloque"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=editar_SMSBloque&idSMS=".$listado->IdSMS."\"><img src=\"/administra/Imagenes/phone_editar.png\" title=\"".$lang["editarSMSbloque"]."\" alt=\"".$lang["editarSMSbloque"]."\"></a>";
			if (isset($listado->FechaEnvio)) print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=SMSVer&idSMS=".$listado->IdSMS."\">".$listado->Titulo."</a> (".$listado->FechaEnvio.")";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=SMSVer&idSMS=".$listado->IdSMS."\">".$listado->Titulo."</a>";
		}
		print "</li>";
	}
}
print "</ul>";

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>