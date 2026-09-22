<?php
$Id= $_POST["Id"];
$idusuario= $_POST["idusuario"];
$Estado= $_POST["Estado"];
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Reservas` SET `Estado` = '".$Estado."', `GestionadoPor`=".$idusuario." WHERE `Id`=".$Id;
print $requete;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso o el contenido en curso
header("Location:/administra/Interface/herramienta.php?modulo=Comercio&herramienta=reservas");
?>