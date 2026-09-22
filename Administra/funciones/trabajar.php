<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require("../../Interface/conexion.php");
$requete_actualiza = "SELECT * FROM `Tareas` WHERE `Id`='".$Id."'";
$result_actualiza = mysql_query($requete_actualiza,$db);
if (($result_actualiza) && (mysqli_num_rows($result_actualiza)>0))
{
	$listado_actualiza = mysql_fetch_object($result_actualiza);
	if (empty($listado_actualiza->EmpezadoTrabajar)) 
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
	mysql_query($requete_actualiza,$db);
}
require("../../Interface/cierre.php");
//Recargamos la herramienta en curso
header("Location:../../Interface/herramienta.php?modulo=Administra&herramienta=tareas&pagina=".$pagina);
?>