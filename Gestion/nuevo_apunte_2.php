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

$requete = "INSERT INTO `Contabilidad` (`Importe`,`Fecha`";
if ($_POST['Titulo']!='') $requete.=",`Titulo`";
if ($_POST['IdCliente']!='') $requete.=",`IdCliente`";
if ($_POST['IdCuenta']!='') $requete.=",`IdCuenta`";
if ($_POST['IdEmpresa']!='') $requete.=",`IdEmpresa`";
if ($_POST['IdFactura']!='') $requete.=",`IdFactura`";
$requete.= ") VALUES ('".$Importe."', '".$Fecha."'";
if ($_POST['Titulo']!='') $requete.=",'".$_POST['Titulo']."'";
if ($_POST['IdCliente']!='') $requete.=",'".$_POST['IdCliente']."'";
if ($_POST['IdCuenta']!='') $requete.=",'".$_POST['IdCuenta']."'";
if ($_POST['IdEmpresa']!='') $requete.=",'".$_POST['IdEmpresa']."'";
if ($_POST['IdFactura']!='') $requete.=",'".$_POST['IdFactura']."'";
$requete.=");";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=contabilidad");
?>