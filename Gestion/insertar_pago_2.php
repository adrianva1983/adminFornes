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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");

$requete = "INSERT INTO `Tareas` (`Nombre`,`Descripcion`,`Fecha`,`TiempoDedicado`,`PorcentajeEjecucion`,`Tipo`,`IdUsuarioCreador`,`IdUsuarioAsignado`,`Votos`,`Publica`";
if ($Proyecto!="") $requete.=",`IdProyecto`";
if ($Responsable!="") $requete.=",`IdResponsable`";
$requete.= ") VALUES ('".$Nombre."', '".$Descripcion."', '".date("Y-m-d")."', '0', '0', '".$Tipo."', '".$_SESSION['usuario_id']."', '".$Asignado."','0','".$publico."'";
if ($Proyecto!="") $requete.=",".$Proyecto;
if ($Responsable!="") $requete.=",".$Responsable;
$requete.=");";
mysqli_query($db,$requete);
if ($Asignado!="")
{
	//Saco el mail del entorno
	$requete="SELECT * FROM `Servidor` WHERE Campo='Mail Entorno'";
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$EmailEntorno = $listado->Valor;
	}
	//Miro para mandarle un mail
	$requete="SELECT * FROM `Usuarios` WHERE Id=".$Asignado;
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$EmailUsuario = $listado->Email;
	}
	if (($EmailUsuario!="")&&($EmailEntorno!=""))
	{
		//Mandamos el mail
		$dominio = $_SERVER['SERVER_NAME'];
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= "From: ".utf8_encode("Dejávù")." <".$EmailEntorno.">\r\n";
		$msg = "<h1>".$Nombre."</h1>".$Descripcion;
		include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");			
		mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["nuevaTarea"]), $mensaje, $headers);
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=tareas");
?>