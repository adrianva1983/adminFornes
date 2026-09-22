<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
switch ($accion)
{
	case "OK":
		$requete ="UPDATE `ForoMensajes` SET `Validado` = 'si' WHERE `Id` = '".$mensaje."';";
	break;
	case "KO":
		$requete ="UPDATE `ForoMensajes` SET `Validado` = 'no' WHERE `Id` = '".$mensaje."';";
	breack;
}
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