<?php
$resultados = array(); 
/* Extrae los valores enviados desde la aplicacion movil */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$loginOK = true;
if ($_GET['key']!='gfdhg8423gjr90d9093hklfds7g2ls')
{	
	$resultados["mensaje"] = "Fallo sistema seguridad.";
	$resultados["validacion"] = "error";
	$loginOK = false;
}
if ($_GET['id_tarea']=='')
{
	$resultados["mensaje"] = "No proporcionado la tarea.";
	$resultados["validacion"] = "error";
	$loginOK = false;
}
if ($loginOK)
{
	$requete = "SELECT * FROM `Tareas` WHERE `Id`=".$_GET['id_tarea'];
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		if ($listado->FechaCierre!='')
		{
			$requete = "UPDATE `Tareas` SET `FechaCierre`=NULL WHERE `Id`=".$_GET['id_tarea'];			
			if (mysqli_query($db,$requete))
			{
				$resultados["mensaje"] = "Reabierta tarea correctamente.";
				$resultados["validacion"] = "ok";
			}
			else
			{
				$resultados["mensaje"] = utf8_encode("Problemas realizando acción en la base de datos con el cambio de estado.");
				$resultados["validacion"] = "error";		
			}
		}
		else
		{
			$requete = "UPDATE `Tareas` SET `FechaCierre`='".date('Y-m-d H:i:s')."' WHERE `Id`=".$_GET['id_tarea'];
			if (mysqli_query($db,$requete))
			{
				$resultados["mensaje"] = "Finalizada tarea correctamente.";
				$resultados["validacion"] = "ok";
			}
			else
			{
				$resultados["mensaje"] = utf8_encode("Problemas realizando acción en la base de datos con el cambio de estado.");
				$resultados["validacion"] = "error";		
			}
		}
	}
}
/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $resultadosJson;
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>