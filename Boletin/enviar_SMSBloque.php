<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
require($_SERVER['DOCUMENT_ROOT']."/herramientas/envioSMS.php");
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/enviar_SMSBloque-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `SMSCreditos` WHERE `IdUsuario`='".$_SESSION['usuario_id']."'";

$creditos_disponibles = 0;
$creditos_gastados = 0;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$creditos_disponibles = $listado->Creditos;
	$creditos_gastados = $listado->TotalesConsumidos;
}
$requete = "SELECT * FROM `SMSContenido` WHERE `IdSMS`='".$idSMS."';";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$Remitente = $listado->Remitente;
	$Texto = urlencode($listado->Texto);
}
$requete = "SELECT * FROM `SMSUsuarios`,`Usuarios` WHERE IdSMS=".$idSMS." AND IdUsuario=`Usuarios`.Id AND `Estado`='Pendiente';";

if (mysqli_num_rows($result)>$creditos_disponibles)
{
	die ($lang["noCreditos"]);
	exit;
}
else
{
	print "<p>";
	if ($result = mysqli_query($db, $requete))
	{
		$NumEnvios = 0;
		while ($listado = mysqli_fetch_object($result))
		{	
			print "<strong>".$listado->Movil.":</strong> ";			
			enviarSMS($listado->Movil,$Texto,$Remitente);
			print "<br/>";
			$requete2 ="UPDATE `SMSUsuarios` SET `Estado` = 'Enviado' WHERE `IdSMS` = '".$idSMS."' AND `IdUsuario`='".$listado->IdUsuario."'";
			mysql_query($requete2,$db);
			$creditos_disponibles--;
			$creditos_gastados++;
			$NumEnvios++;
		}
		$requete2 ="UPDATE `SMSCreditos` SET `Creditos` = '".$creditos_disponibles."',`TotalesConsumidos`='".$creditos_gastados."' WHERE `IdUsuario`= '".$_SESSION['usuario_id']."'";				
		mysql_query($requete2,$db);
		$requete2 ="UPDATE `SMSContenido` SET `FechaEnvio` = '".date('Y-m-d H:i:s')."',`IdUsuarioEnvio`='".$_SESSION['usuario_id']."' WHERE `IdSMS`= '".$idSMS."'";
		mysql_query($requete2,$db);
	}	
	print "</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<hr/>";
print "<p><strong>".$lang["numeroMandados"].":</strong> ".$NumEnvios."<br/>";
print "<strong>".$lang["creditosDisponibles"].":</strong> ".$creditos_disponibles."<br/>";
print "</p>";
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=SMS\">".$lang["volver"]."</a></p>";
?>