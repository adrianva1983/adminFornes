<?php
//VERSIÓN: v1.0 2014-02-11
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$antiguo = $_GET["antiguo"];
$contenido = $_GET["contenido"];
//Control de acceso
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `Contenidos` WHERE `Id` = '".$contenido."';";
mysqli_query($db,$requete);
$requete = "DELETE FROM `Publicaciones` WHERE `IdContenido` = '".$contenido."'";
mysqli_query($db,$requete);
//Borramos si es Foro las filas en tablas auxiliares
$requete = "DELETE FROM `ForoConfiguracion` WHERE `Id` = '".$contenido."'";
mysqli_query($db,$requete);
$requete = "DELETE FROM `ForoMensajes` WHERE `IdForo` = '".$contenido."'";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
	header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&contenido=".$antiguo);

?>