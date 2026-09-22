<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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

$campos=$_GET;
$titulos = array_keys($_GET);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
for ($i=0;$i<count($campos);$i++)
{
   $tipodecampo = explode("/",$titulos[$i]);
   switch ($tipodecampo[0]) 
   {
     case "usuario":
	$requete = "DELETE FROM `SMSUsuarios` WHERE `IdSMS`='".$idSMS."' AND `IdUsuario`='".$campos[$titulos[$i]]."';";
	mysqli_query($db,$requete);
	$requete ="INSERT INTO `SMSUsuarios` (`IdSMS`, `IdUsuario`) VALUES ('".$idSMS."', '".$campos[$titulos[$i]]."');";
	mysqli_query($db,$requete);
	break;
     case "grupo":
	break;
   }
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=SMSVer&idSMS=".$idSMS);
?>