<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdPresupuesto = $_POST['IdPresupuesto'];
$IdCliente = $_POST['IdCliente'];
$Titulo = $_POST['Titulo'];
$Descripcion = $_POST['Descripcion'];
$Asignado = $_POST['Asignado'];
$FechaPlanificada = $_POST['FechaPlanificada'];
$FechaRealizada = $_POST['FechaRealizada'];
$Id = $_POST['Id'];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_accion_crm-".$_SESSION['idioma'].".conf");

$requete = "UPDATE `ClientesCRM` SET `FechaPlanificada` = '".$FechaPlanificada."',`IdUsuario`=".$Asignado.",`Titulo`='".addslashes($Titulo)."'";
if ($IdPresupuesto!='') $requete.=",`IdPresupuesto`=".$IdPresupuesto;
else $requete.=",`IdPresupuesto`=NULL";
if ($FechaRealizada!='') $requete.=",`FechaRealizada`='".$FechaRealizada."'";
else $requete.=",`FechaRealizada`=NULL";
if ($Descripcion!='') $requete.=",`Notas`='".addslashes($Descripcion)."'";
else $requete.=",`Notas`=NULL";
if ($Tipo!='') $requete.=",`Tipo`=".$Tipo;
else $requete.=",`Tipo`=NULL";
if ($IdCliente!='') $requete.=",`IdCliente`=".$IdCliente;
else $requete.=",`IdCliente`=NULL";
$requete.=" WHERE `Id`=".$Id;
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
if ($origen=="home") header("Location:/administra/Interface/administra.php");
else header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=crm");
?>