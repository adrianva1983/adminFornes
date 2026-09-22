<?php
$resultados = array(); 
/* Extrae los valores enviados desde la aplicacion movil */
$titulo = $_GET['titulo'];
$descripcion= $_GET['descripcion'];
$id_usuario = $_GET['id_usuario'];
$id_proyecto = $_GET['id_proyecto'];
$Key = $_GET["key_acceso"];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$loginOK = true;
//Nos aseguramos de que no nos suplantan al usuario
$requete = "SELECT * FROM `Usuarios` WHERE `Id`= ".$id_usuario;	
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->NivelAcceso>5)
	{
		$resultados2["mensaje"] = "Fallo sistema seguridad";
		$resultados2["validacion"] = "error";
		$loginOK = false;
	}
}
else 
{
	$resultados2["mensaje"] = "Usuario inexistente";
	$resultados2["validacion"] = "error";
	$loginOK = false;
}
if ($Key=="c5zwCl4e1vw5oY609T5GX4rD71UVx1Rf") $loginOK= true;
if ($loginOK)
{
	$requete = "SELECT * FROM `Proyectos` WHERE `Id`=".$id_proyecto;		
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$id_responsable = $listado->IdResponsable;
		$titulo_proyecto = $listado->Titulo;
		$requete = "SELECT * FROM `Usuarios` WHERE `Id`=".$id_responsable;		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$EmailResponsable = $listado->Email;
		}
	}
	$requete = "INSERT INTO Tareas (`Nombre`,`Descripcion`,`Fecha`,`TiempoDedicado`,`PorcentajeEjecucion`,`IdUsuarioCreador`,`Publica`,`Pendiente`,`IdResponsable`,`IdProyecto`,`NotificacionCreacion`,`NotificacionModificacion`,`ToDo`)";
	$requete.=" VALUES ('".addslashes(utf8_decode($titulo))."','".addslashes(utf8_decode($descripcion))."','".date('Y-m-d H:i:s')."',0,0,".$id_usuario.",'si',1,".$id_responsable.",".$id_proyecto.",'R','R',0);";	
	if (mysqli_query($db,$requete))
	{
		$resultados["mensaje"] = "Registrada y enviada su solicitud";
		$resultados["validacion"] = "ok";
		$dominio = $_SERVER['SERVER_NAME'];
		$EmailEntorno = "info@semillaproyectos.com";
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= "From: ".utf8_encode("Dejávù")." <".$EmailEntorno.">\r\n";
		$msg = "<h1>".$titulo_proyecto."</h1>";
		$msg.= "<h2>".utf8_decode($titulo)."</h2>";		
		$msg.= "<p>".utf8_decode($descripcion)."</p>";
		mail($EmailResponsable, utf8_encode("APP Móvil - Solicitud tarea"), $msg, $headers);
	}
	else
	{
		$resultados["mensaje"] = utf8_encode("Hemos encontrado algún problema registrando su información.");
		$resultados["validacion"] = "error";
	}
}
/*convierte los resultados a formato json*/
if ($loginOK) $resultadosJson = json_encode($resultados);
else $resultadosJson = json_encode($resultados2);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $resultadosJson;
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>