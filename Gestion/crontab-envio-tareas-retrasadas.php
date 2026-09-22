<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$cod = $_GET["cod"];
if ($cod!="fdfkhjhio23r03248sakalj12324") //Para poder hacer el crontab
{
	exit();
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Saco el mail del entorno
$requete="SELECT * FROM `Servidor` WHERE Campo='Mail Entorno'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$EmailEntorno = $listado->Valor;
}
$dominio = $_SERVER['SERVER_NAME'];
$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= "From: ".utf8_encode("Dejávù")." <".$EmailEntorno.">\r\n";
$requete = "SELECT * FROM `Tareas` WHERE `NotificacionPasadoFecha` IS NOT NULL AND `NotificacionPasadoFecha`<>'' AND `FechaFin`<'".date("Y-m-d h:i:s")."' AND `FechaCierre` IS NULL";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$msg = "<h1>".$listado->Nombre."</h1>".$listado->Descripcion;
		include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");		
		if (strpos($listado->NotificacionPasadoFecha,"R")!==false)
		{
			//Miro para mandarle un mail
			$requete2="SELECT * FROM `Usuarios` WHERE Id=".$listado->IdResponsable;
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				$EmailUsuario = $listado2->Email;
				if ($EmailUsuario!=""&&$EmailEntorno!="")
				{
					mail($EmailUsuario, utf8_encode("Dejávù - Tarea retrasada de la que es responsable"), $mensaje, $headers);
				}
			}
		}
		if (strpos($listado->NotificacionPasadoFecha,"A")!==false)
		{
			//Miro para mandarle un mail
			$requete2="SELECT * FROM `Usuarios` WHERE Id=".$listado->IdUsuarioAsignado;			
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				$EmailUsuario = $listado2->Email;
				if ($EmailUsuario!="" && $EmailEntorno!="")
				{
					//Mandamos el mail
					mail($EmailUsuario, utf8_encode("Dejávù - Tarea retrasada que tiene asignada"), $mensaje, $headers);
				}
			}
		}
		if (strpos($listado->NotificacionPasadoFecha,"C")!==false)
		{			
			$requete2 = "SELECT * FROM `Proyectos` WHERE `Id`=".$listado->IdProyecto;			
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				$requete2 = "SELECT * FROM `Clientes` WHERE `Id`=".$listado2->IdCliente;				
				
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);
					$EmailUsuario = $listado2->Email;
					if ($EmailUsuario!=""&&$EmailEntorno!="")
					{
						mail($EmailUsuario, utf8_encode("Dejávù - Tarea retrasada de la que es cliente"), $mensaje, $headers);
					}
				}				
			}
		}		
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>