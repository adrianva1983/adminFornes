<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$pagina = $_GET["pagina"];
$IdProyectoReferencia = $_GET["IdProyectoReferencia"];


require("../../Interface/conexion.php");
$requete_actualiza = "SELECT * FROM `Tareas` WHERE `Id`='".$Id."'";
$result_actualiza = mysqli_query($db,$requete_actualiza);
if (($result_actualiza) && (mysqli_num_rows($result_actualiza)>0))
{
	$listado_actualiza = mysqli_fetch_object($result_actualiza);
	//print_r($listado_actualiza);
	if ($listado_actualiza->EmpezadoTrabajar=='')
	{
		$requete_actualiza = "UPDATE `Tareas` SET `EmpezadoTrabajar` = '".time()."' WHERE `Id` =".$Id.";";
	}
	else
	{
		$empezo = $listado_actualiza->EmpezadoTrabajar;
		$tiempoDedicado = $listado_actualiza->TiempoDedicado;
		$diferencia = time() - $empezo;
		$tiempoDedicado += $diferencia;
		$requete_actualiza = "UPDATE `Tareas` SET `EmpezadoTrabajar` = NULL,`TiempoDedicado`='".$tiempoDedicado."' WHERE `Id` =".$Id.";";		
	}		
	mysqli_query($db,$requete_actualiza);
}
require("../../Interface/cierre.php");
//Recargamos la herramienta en curso
header("Location:../../Interface/herramienta.php?modulo=Gestion&herramienta=tareas&pagina=".$pagina."&IdProyecto=".$IdProyectoReferencia);
?>