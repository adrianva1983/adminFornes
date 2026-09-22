<?php
$resultados = array(); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
/* Extrae los valores enviados desde la aplicacion movil */
$id_usuario = $_GET['id_usuario'];
$id_proyecto = $_GET['id_proyecto'];
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM Usuarios WHERE Id=".$id_usuario." AND Activado=1";

$nivel_acceso = 10;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$nivel_acceso = $listado->NivelAcceso;
}
else
{
	$resultados["mensaje"] = "Usuario incorrecto";
	$resultados["validacion"] = "error";
	$resultadosJson = json_encode($resultados);
	echo $resultadosJson;
	exit();
}
if ($nivel_acceso>4)
{	
	$requete = "SELECT * FROM `Clientes` WHERE `IdUsuario`=".$id_usuario;
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
	}
	else
	{
		$resultados["mensaje"] = "No tiene acceso a proyectos";
		$resultados["validacion"] = "error";
		$resultadosJson = json_encode($resultados);
		echo $resultadosJson;
		exit();		
	}
}
if ($nivel_acceso>4) $requete = "SELECT * FROM Proyectos WHERE IdCliente=".$listado->Id." AND `Id`=".$id_proyecto;
else $requete = "SELECT * FROM Proyectos WHERE 1=1 AND `Id`=".$id_proyecto;

if ($result = mysqli_query($db, $requete))
{
	$resultados_tmp = array(); 
	$listado = mysqli_fetch_object($result);
	$requete = "SELECT * FROM Tareas WHERE IdProyecto=".$id_proyecto." AND Publica='si'";	
	
	if ($result = mysqli_query($db, $requete))
	{
		while ($listado = mysqli_fetch_object($result))
		{
			$resultados_tmp['id'] = $listado->Id;
			//$resultados_tmp['fecha_creacion'] = date('d/m/Y',strtotime($listado->FechaCreacion));
			$resultados_tmp['nombre'] = utf8_encode($listado->Nombre);
			if ($listado->FechaCierre!='') $porcentaje_ejecucion = 100;
			else $porcentaje_ejecucion = $listado->PorcentajeEjecucion;
			if ($listado->Pendiente==1) $resultados_tmp['pendiente']=1;
			else $resultados_tmp['pendiente']=0;
			$resultados_tmp['porcentaje_ejecucion'] = $porcentaje_ejecucion;
			array_push($resultados,$resultados_tmp);
		}
	}
}
else
{
	$resultados["mensaje"] = "No tiene acceso a proyectos";
	$resultados["validacion"] = "error";
	$resultadosJson = json_encode($resultados);
	echo $resultadosJson;
	exit();		
}
/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $resultadosJson;

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>