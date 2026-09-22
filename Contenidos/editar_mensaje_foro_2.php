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
//Actualizamos el mensaje
$requete ="UPDATE `ForoMensajes` SET `Titulo` = '".$Titulo."', `Mensaje` = '".$Breve."'";
if ($Votacion!=$VotacionOriginal) $requete.=",`Votacion`=".$Votacion;
$requete.= " WHERE `Id` = '".$mensaje."';";
mysqli_query($db,$requete);
if ($Votacion!=$VotacionOriginal)
{	
	$requete = "SELECT * FROM `ForoConfiguracion` WHERE  `Id`=".$IdForo;	
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$VotacionAcumulada = $listado->SumaVotos;
		$VotacionAcumulada = $VotacionAcumulada-$VotacionOriginal+$Votacion;
		$requete ="UPDATE `ForoConfiguracion` SET `SumaVotos` = ".$VotacionAcumulada;		
		$requete.= " WHERE `Id` = '".$IdForo."';";		
		mysqli_query($db,$requete);
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
switch ($origen)
{
	case "ultimos_mensajes_foros":
		header("Location:/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=ultimos_mensajes_foros&pagina=".$pagina);
	break;
	case "foro_editar_mensajes":		
		header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=foro_editar_mensajes&pagina=".$pagina."&seccion=".$seccion."&ruta=".$ruta."&IdForo=".$IdForo);
	break;	
}
?>