<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `PertenenciaGrupos` WHERE `IdGrupo` = '".$grupo."';";
mysqli_query($db,$requete);
$requete = "DELETE FROM `Grupos` WHERE `Id` = '".$grupo."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=grupos&grp=".$referencia);
?>