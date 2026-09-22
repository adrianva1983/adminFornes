<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if (($Email!="")||($Telefono!=""))
{
	$requete_enviar_reserva =	"SELECT * FROM `Usuarios` WHERE (`Email` = '".$Email."' AND `Email`<>'') OR (`Movil`='".$Telefono."' AND `Movil`<>'')";
	$result_enviar_reserva = mysql_query($requete_enviar_reserva,$db);
	if (($result_enviar_reserva) && (mysqli_num_rows($result_enviar_reserva)>0)) //Comprobamos si ese Mail tiene usuario para darle puntos y la reserva
	{
		$listado_enviar_reserva = mysql_fetch_object($result_enviar_reserva);		
	}
}
$restauranteArray = explode("/",$Restaurante);
function cambiaf_a_mysql($fecha){
	$mifecha = explode("/", $fecha);    		
	$lafecha=$mifecha[2]."-".str_pad($mifecha[1], 2, "0", STR_PAD_LEFT)."-".str_pad($mifecha[0], 2, "0", STR_PAD_LEFT);    		
	return $lafecha;
} 
$fecha_reserva = cambiaf_a_mysql($Dia);
$fecha_reserva = $fecha_reserva." ".$Hora;
$fecha_reserva = $fecha_reserva.":00";
$ahora = time() + (9*60*60);
$FechaInserccion = date('Y-m-d H:i:s',$ahora);
$requete_enviar_reserva = "INSERT INTO `guiarestaurantes_reservas` (`IntroducidoPor`,`Nombre`,`Telefono`,`Email`,`IdRestaurante`,`Fecha`,`FechaInserccion`,`Comensales`,`TipoAlerta`";
if ($listado_enviar_reserva->Id!="0") $requete_enviar_reserva.=",`IdUsuario`";
$requete_enviar_reserva .=") VALUES (";
$requete_enviar_reserva .="'".$_SESSION['usuario_id']."','".$Nombre."','".$Telefono."','".$Email."','".$restauranteArray[1]."','".$fecha_reserva."','".$FechaInserccion."','".$Comensales."','".$Alerta."'";
if ($listado_enviar_reserva->Id!="0") $requete_enviar_reserva .=",'".$listado_enviar_reserva->Id."'";
$requete_enviar_reserva .=")";
mysql_query($requete_enviar_reserva,$db); //Guardamos la Reserva en BD

// PUNTOS BONOGOURMETS SI IDENTIFICAMOS A USUARIO
if ($listado_enviar_reserva->Id!="")
{
	$requete_enviar_reserva2 = "INSERT INTO `PuntosLog` (`IdUsuario`,`Actividad`,`Puntos`,`TipoIngreso`,`IdContenido`, `FechaMovimiento`) VALUES ('".$listado_enviar_reserva->Id."', 'Ingreso', '3', 'ReservaRestaurante', '".$restauranteArray[1]."', '".date("Y-m-d")."');";
	mysql_query($requete_enviar_reserva2,$db);
	$requete_enviar_reserva2 = "SELECT * FROM `PuntosUsuarios` WHERE `IdUsuario` = '".$listado_enviar_reserva->Id."'";
	$result_enviar_reserva2 = mysql_query($requete_enviar_reserva2,$db);
	if (($result_enviar_reserva2) && (mysqli_num_rows($result_enviar_reserva2)>0))
	{
 		$listado_enviar_reserva2 = mysql_fetch_object($result_enviar_reserva2);
 		$PuntosAcumulados = $listado_enviar_reserva2->PuntosAcumulados+3;
 		$PuntosDisponibles = $listado_enviar_reserva2->PuntosDisponibles+3;
 		$requete_enviar_reserva2 = "UPDATE `PuntosUsuarios`  SET `PuntosDisponibles` = '".$PuntosDisponibles."', `PuntosAcumulados` = '".$PuntosAcumulados."' WHERE `IdUsuario`= '".$listado_enviar_reserva->Id."'";
 	}
	else $requete_enviar_reserva2 = "INSERT INTO `PuntosUsuarios` (`IdUsuario`,`PuntosDisponibles`,`PuntosAcumulados`) VALUES ('".$listado_enviar_reserva->Id."', '3', '3');";
	mysql_query($requete_enviar_reserva2,$db);
}
//Sacamos el Id de la reserva
$requete_enviar_reserva2 = "SELECT * FROM `guiarestaurantes_reservas` WHERE `FechaInserccion`='".$FechaInserccion."' AND `Fecha` = '".$fecha_reserva."' AND `IdRestaurante`='".$restauranteArray[1]."' AND `Comensales` = '".$Comensales."' AND `Email`='".$Email."' AND `Telefono`='".$Telefono."' AND `Nombre`='".$Nombre."'";
$result_enviar_reserva2 = mysql_query($requete_enviar_reserva2,$db);
if (($result_enviar_reserva2) && (mysqli_num_rows($result_enviar_reserva2)>0))
{
	$listado_enviar_reserva2 = mysql_fetch_object($result_enviar_reserva2);
	$IdReserva = $listado_enviar_reserva2->Id;
}
//ENVIAMOS MAIL A USUARIO
if ($Email!="")
{
	//Sacamos el email del entorno
	$requete_enviar_reserva2 = "SELECT `Valor` FROM `Servidor` WHERE `Campo` = 'Mail Entorno'";
	$result_enviar_reserva2 = mysql_query($requete_enviar_reserva2,$db);
	if (($result_enviar_reserva2) && (mysqli_num_rows($result_enviar_reserva2)>0))
	{
		$listado_enviar_reserva2 = mysql_fetch_object($result_enviar_reserva2);
		$mail_entorno = $listado_enviar_reserva2->Valor;
	}
	//Cargamos los encabezados del mail
	$dominio = $_SERVER['SERVER_NAME'];
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: $dominio <$mail_entorno>\r\n";
	//Enviamos la confirmación de la solicitud de reserva al mail del usuario
  $msg = "<p>Su solicitud de reserva ha sido cursada correctamente.</p>";
  $msg .= "<p>Procedemos a comprobar disponibilidad para el d&iacute;a ".$Dia." a las ".$Hora." en el restaurante ".$restauranteArray[0]." y nos pondremos en contacto con usted para mantenerle informado.</p><p>El C&oacute;digo de su solicitud de reserva es: <strong>".$IdReserva."</strong></p>";
	include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");
	mail($Email, "Solicitud de Reserva Recibida:".$IdReserva, $mensaje, $headers);
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Comercio&herramienta=reservas");
?>