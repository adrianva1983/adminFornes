<?php
//VERSIÓN: v1.0 2015-6-1
//COMPATIBLE PHP 5.5

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_apunte-".$_SESSION['idioma'].".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$requete = "UPDATE `Contabilidad` SET `Importe`='".$_POST['Importe']."',`Fecha`='".$_POST['Fecha']."'";
if ($_POST['Titulo']!='') $requete.=",`Titulo`='".$_POST['Titulo']."'";
else $requete.= ",`Titulo` = NULL";
if ($_POST['IdCliente']!='') $requete.=",`IdCliente`='".$_POST['IdCliente']."'";
else $requete.= ",`IdCliente` = NULL";
if ($_POST['IdCuenta']!='') $requete.=",`IdCuenta`='".$_POST['IdCuenta']."'";
else $requete.= ",`IdCuenta` = NULL";
if ($_POST['IdEmpresa']!='') $requete.=",`IdCliente`='".$_POST['IdEmpresa']."'";
else $requete.= ",`IdEmpresa` = NULL";
if ($_POST['IdFactura']!='') $requete.=",`IdFactura`='".$_POST['IdFactura']."'";
else $requete.= ",`IdFactura` = NULL";
$requete.=" WHERE `Id`=".$_POST['Id'].";";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=contabilidad");
?>