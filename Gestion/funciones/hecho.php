<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$IdProyecto = $_GET["IdProyecto"];
$IdProyectoReferencia = $_GET["IdProyectoReferencia"];
$pagina = $_GET["pagina"];

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($Id!="")
{
	$requete_actualiza = "SELECT * FROM `Tareas` WHERE `Id`='".$Id."'";	
	$result_actualiza = mysqli_query($db,$requete_actualiza);
	if (($result_actualiza) && (mysqli_num_rows($result_actualiza)>0))
	{
		$listado_actualiza = mysqli_fetch_object($result_actualiza);
		$Nombre = $listado_actualiza->Nombre;
		$Descripcion = $listado_actualiza->Descripcion;
		if (empty($listado_actualiza->EmpezadoTrabajar)) 
		{
			$requete_actualiza = "UPDATE `Tareas` SET `FechaCierre`='".date('Y-m-d H:i:s')."',`PorcentajeEjecucion`='100' WHERE `Id` =".$Id.";";		
		}
		else 
		{
			$empezo = $listado_actualiza->EmpezadoTrabajar;
			$tiempoDedicado = $listado_actualiza->TiempoDedicado;
			$diferencia = time() - $empezo;
			$tiempoDedicado += $diferencia;
			$requete_actualiza = "UPDATE `Tareas` SET `EmpezadoTrabajar` = NULL,`TiempoDedicado`='".$tiempoDedicado."',`FechaCierre`='".date("Y-m-d")."' WHERE `Id` =".$Id.";";
		}			
		mysqli_query($db,$requete_actualiza);
		//Ahora miro para mandar mails
		if ($listado_actualiza->NotificacionCierre!="") $NotificacionCierre = explode(":",$listado_actualiza->NotificacionCierre);
		if (count($NotificacionCierre)>0)
		{
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
			$msg = "<h1>Tarea terminada: ".$Nombre."</h1>";
			$msg.= $Descripcion;
			include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");			
			for ($i=0;$i<count($NotificacionCierre);$i++)
			{
				if ($NotificacionCierre[$i]=="A"&&$listado_actualiza->Asignado!="")
				{	
					//Miro para mandarle un mail
					$requete="SELECT * FROM `Usuarios` WHERE Id=".$listado_actualiza->Asignado;
					
					if ($result = mysqli_query($db, $requete))
					{
						$listado = mysqli_fetch_object($result);
						$EmailUsuario = $listado->Email;
					}
					if (($EmailUsuario!="")&&($EmailEntorno!=""))
					{
						//Mandamos el mail
						mail($EmailUsuario, utf8_encode("Dejávù - Tarea terminada"), $mensaje, $headers);
					}
				}
				if ($NotificacionCierre[$i]=="R"&&$listado_actualiza->IdResponsable!="")
				{			
					//Miro para mandarle un mail
					$requete="SELECT * FROM `Usuarios` WHERE Id=".$listado_actualiza->IdResponsable;
					
					if ($result = mysqli_query($db, $requete))
					{
						$listado = mysqli_fetch_object($result);
						$EmailUsuario = $listado->Email;
					}
					if (($EmailUsuario!="")&&($EmailEntorno!=""))
					{
						//Mandamos el mail
						mail($EmailUsuario, utf8_encode("Dejávù - Tarea terminada"), $mensaje, $headers);
					}
				}
				if (($NotificacionCierre[$i]=="C")&&($listado_actualiza->IdProyecto!="")&&($listado_actualiza->Publica=="si"))
				{
					//Miro para mandarle un mail
					$requete="SELECT * FROM `Proyectos` WHERE Id=".$listado_actualiza->IdProyecto;
					
					if ($result = mysqli_query($db, $requete))
					{
						$listado = mysqli_fetch_object($result);
						if ($listado->IdCliente!="")
						{
							$requete.= "SELECT * FROM `Contactos` WHERE `IdCliente`=".$listado->IdCliente;
							
							if ($result = mysqli_query($db, $requete))
							{
								while ($listado = mysqli_fetch_object($result))
								{ //Envío mails a todos los contactos del cliente
									$EmailUsuario = $listado->Email;
									if (($EmailUsuario!="")&&($EmailEntorno!=""))
									{
										//Mandamos el mail
										mail($EmailUsuario, utf8_encode("Dejávù - Tarea terminada"), $mensaje, $headers);
									}
								}
							}					
						}
					}
				}
			}
		}
	}	
	//Recargamos la herramienta en curso
	header("Location:../../Interface/herramienta.php?modulo=Gestion&herramienta=tareas&pagina=".$pagina."&IdProyecto=".$IdProyectoReferencia);
}
if ($IdProyecto!="")
{
	$requete_actualiza = "UPDATE `Proyectos` SET `Activo`= 0 WHERE `Id` =".$IdProyecto.";";		
	mysql_query($requete_actualiza,$db);
	
	//Recargamos la herramienta en curso
	header("Location:../../Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&pagina=".$pagina);
}
require($_SERVER['DOCUMENT_ROOT']."/Interface/cierre.php");
?>