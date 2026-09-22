<?php
$Responsable = $_POST['Responsable'];
$IdCliente = $_POST['IdCliente'];
$Nombre = $_POST['Nombre'];
$Descripcion = $_POST['Descripcion'];
$Id = $_POST['Id'];
$EquipoInvolucrado = $_POST['EquipoInvolucrado'];
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Proyectos` SET `Nombre` = '".$Nombre."',`Descripcion`='".$Descripcion."'";
if ($Responsable!="") $requete.=",`IdResponsable`=".$Responsable;
else $requete.=",`IdResponsable`=NULL";
if ($IdCliente!="") $requete.=",`IdCliente`=".$IdCliente;
else $requete.=",`IdCliente`=NULL";
$requete.= " WHERE `Id`='".$Id."';";
mysqli_query($db,$requete);
$requete = "DELETE FROM `ProyectosTrabajadores` WHERE `IdProyecto`=".$Id;
mysqli_query($db,$requete);
for ($i=0;$i<count($EquipoInvolucrado);$i++)
{
	$requete = "INSERT INTO `ProyectosTrabajadores` (`IdProyecto`,`IdUsuario`) VALUES (".$Id.",".$EquipoInvolucrado[$i].");";
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&pagina=".$pagina);
?>