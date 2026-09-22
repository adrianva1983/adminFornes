<?php
//VERSIÓN: v1.0 2014-5-7
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$id = $_GET["id"];
//Control de acceso
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Tags` SET `IdPadre`=NULL WHERE `IdPadre`= ".$id;
mysqli_query($db,$requete);
$requete = "DELETE FROM `Tags` WHERE `Id` = ".$id;
mysqli_query($db,$requete);
$requete = "DELETE FROM `TagsRelaciones` WHERE `IdTag` = ".$id;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
header("Location:../../Interface/herramienta.php?modulo=Contenidos&herramienta=tipo_contenidos");

?>