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

$campos=$_POST;
$titulos = array_keys($_POST);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");	
for ($jj=0;$jj<count($campos["usuario"]);$jj++)
{
	$requete = "DELETE FROM `BoletinUsuarios` WHERE `IdBoletin`='".$idboletin."' AND `IdUsuario`='".$campos["usuario"][$jj]."';";	
	mysqli_query($db,$requete);
	$requete ="INSERT INTO `BoletinUsuarios` (`IdBoletin`, `IdUsuario`) VALUES ('".$idboletin."', '".$campos["usuario"][$jj]."');";	
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin);
?>