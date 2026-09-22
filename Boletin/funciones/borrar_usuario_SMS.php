<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `SMSUsuarios` WHERE `IdUsuario` = '".$idusuario."' AND `IdSMS`='".$IdSMS."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
header("Location:../../Interface/herramienta.php?modulo=Boletin&herramienta=SMSVer&idSMS=".$IdSMS);
?>