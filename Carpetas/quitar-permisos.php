<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$usuario = $_GET["usuario"];
$grupo = $_GET["grupo"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

$campos=$_POST;
$titulos = array_keys($_POST);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//BORRAMOS LA SUSCRIPCION QUE SE PIDE
if ($usuario!="")
{
  $requete = "DELETE FROM `Permisos` WHERE `IdSeccion`='".$seccion."' AND `IdUsuarioSuscrito`='".$usuario."';";
  mysqli_query($db,$requete);
}
if ($grupo!="")
{
  $requete = "DELETE FROM `Permisos` WHERE `IdSeccion`='".$seccion."' AND `IdGrupoSuscrito`='".$grupo."';";
  mysqli_query($db,$requete);  
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos la herramienta de permisos
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=permisos&seccion=".$seccion."&ruta=".$ruta);
?>
