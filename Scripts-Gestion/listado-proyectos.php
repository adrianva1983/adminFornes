<?php
$resultados = array(); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
/* Extrae los valores enviados desde la aplicacion movil */
$id_usuario = $_GET['id_usuario'];
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM Usuarios WHERE Id=".$id_usuario." AND Activado=1";

$tiene_tareas = false;
$nivel_acceso = 10;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->TieneTareas == 1) $tiene_tareas = true;
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
$requete = "SELECT * FROM Proyectos WHERE ";
if ($nivel_acceso>4) $requete.="IdCliente=".$listado->Id;
else
{
	if ($nivel_acceso>0)
	{
		$requete.= "`IdResponsable`=".$id_usuario;
	}
	else $requete.= "1=1";
}
$requete.= " AND `Activo`=1";

if ($result = mysqli_query($db, $requete))
{
	$resultados_tmp = array(); 
	while ($listado = mysqli_fetch_object($result))
	{
		$resultados_tmp['id'] = $listado->Id;
		$resultados_tmp['fecha_creacion'] = date('d/m/Y',strtotime($listado->FechaCreacion));
		$resultados_tmp['nombre'] = utf8_encode($listado->Nombre);								
		$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NOT NULL AND `Publica`='si'";
		$result2 = mysqli_query($db, $requete2);
		$total_tareas_abiertas = mysqli_num_rows($result2);
		$resultados_tmp['total_tareas_cerradas'] = $total_tareas_abiertas;
		$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NULL AND `Publica`='si'";
		$result2 = mysqli_query($db, $requete2);
		$total_tareas_cerradas = mysqli_num_rows($result2);
		$resultados_tmp['total_tareas_abiertas'] = $total_tareas_cerradas;
		$resultados_tmp['total_tareas'] = $total_tareas_cerradas + $total_tareas_abiertas;
		if (($total_tareas_cerradas + $total_tareas_abiertas)>0) $porcentajeEjecucion = ($total_tareas_abiertas*100) / ($total_tareas_cerradas + $total_tareas_abiertas);
		else $porcentajeEjecucion = 0;
		$resultados_tmp['porcentaje_ejecucion'] = round($porcentajeEjecucion,0);
		$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdResponsable;
		$result2 = mysqli_query($db, $requete2);
		if ($result2 = mysqli_query($db, $requete2))
		{			
			$listado2 = mysqli_fetch_object($result2);
			$resultados_tmp['foto_responsable'] = 'img/'.$listado2->Foto;
			$resultados_tmp['nombre_responsable'] = utf8_encode($listado2->Nombre." ".$listado2->Apellidos);
		}
		array_push($resultados,$resultados_tmp);
	}
}
/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $resultadosJson;

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>