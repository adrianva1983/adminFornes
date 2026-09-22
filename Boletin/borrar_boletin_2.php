<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

if ($cancelar!="")
{	
	header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletines");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `BoletinUsuarios` WHERE `IdBoletin`='".$idboletin."'";
mysqli_query($db,$requete);
$requete = "DELETE FROM `BoletinContenidos` WHERE `IdBoletin`='".$idboletin."'";
mysqli_query($db,$requete);
$requete = "DELETE FROM `BoletinConfiguracion` WHERE `Id`='".$idboletin."'";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletines");
?>