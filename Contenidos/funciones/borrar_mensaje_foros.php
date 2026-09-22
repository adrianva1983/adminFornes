<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Miro si tiene valoración.
$requete = "SELECT * FROM `ForoMensajes` WHERE `Id` = '".$mensaje."'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	//Si hay votos, actualizo foro configuración
	if ($listado->Votacion!="")
	{
		$votacion = $listado->Votacion;
		$IdForo = $listado->IdForo;
		$requete = "SELECT * FROM `ForoConfiguracion` WHERE `Id` = '".$IdForo."'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$SumaVotos = $listado->SumaVotos - $votacion;
			$NumeroVotos = $listado->NumeroVotos - 1;
			if ($NumeroVotos>=0)
			{
				$requete = "UPDATE `ForoConfiguracion` SET `SumaVotos` = '".$SumaVotos."', `NumeroVotos` ='".$NumeroVotos."' WHERE `Id` ='".$IdForo."';";
				mysqli_query($db,$requete);
			}
		}		
	}
}
$requete = "DELETE FROM `ForoMensajes` WHERE `Id` = '".$mensaje."';";
mysqli_query($db,$requete);
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