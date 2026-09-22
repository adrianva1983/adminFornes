<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Proyecto = $_POST["Proyecto"];
$Responsable = $_POST["Responsable"];
$Nombre = $_POST["Nombre"];
$Descripcion = $_POST["Descripcion"];
$Tipo = $_POST["Tipo"];
$Asignado = $_POST["Asignado"];
$FechaInicio = $_POST["FechaInicio"];
$FechaFin = $_POST["FechaFin"];
$NotificacionCreacion = $_POST["NotificacionCreacion"];
$NotificacionModificacion = $_POST["NotificacionModificacion"];
$NotificacionCierre = $_POST["NotificacionCierre"];
$NotificacionPasadoFecha = $_POST["NotificacionPasadoFecha"];
$Tiempo = $_POST['Tiempo'];
$ToDo = $_POST["ToDo"];
$publico = $_POST['publico'];


//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");

$requete = "INSERT INTO `Tareas` (`Nombre`,`Descripcion`,`PorcentajeEjecucion`,`Tipo`,`IdUsuarioCreador`,`IdUsuarioAsignado`,`Votos`,`Publica`";
$requete.=",`TiempoDedicado`";
if ($ToDo==1) $requete.=",`ToDo`";
if ($Proyecto!="") $requete.=",`IdProyecto`";
if ($Responsable!="") $requete.=",`IdResponsable`";
$requete.=",`Fecha`";
if ($FechaFin!="") $requete.=",`FechaFin`";
if (count($NotificacionCreacion)>0) $requete.=",`NotificacionCreacion`";
if (count($NotificacionModificacion)>0) $requete.=",`NotificacionModificacion`";
if (count($NotificacionCierre)>0) $requete.=",`NotificacionCierre`";
if (count($NotificacionPasadoFecha)>0) $requete.=",`NotificacionPasadoFecha`";
$requete.= ") VALUES ('".$Nombre."', '".$Descripcion."', '0', '".$Tipo."', '".$_SESSION['usuario_id']."', '".$Asignado."','0','".$publico."'";
if ($Tiempo!='') $requete.=",".($Tiempo*60*60);
else $requete.=",1";
if ($ToDo==1) $requete.=",1";
if ($Proyecto!="") $requete.=",".$Proyecto;
if ($Responsable!="") $requete.=",".$Responsable;
if ($FechaInicio!="") $requete.= ",'".$FechaInicio."'";
else $requete.=",'".date("Y-m-d")."'";
if ($FechaFin!="") $requete.=",'".$FechaFin."'";
if (count($NotificacionCreacion)>0)
{
	$requete.= ",'";
	for ($i=0;$i<count($NotificacionCreacion);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionCreacion[$i];
	}
	$requete.= "'";
}
if (count($NotificacionModificacion)>0)
{
	$requete.= ",'";
	for ($i=0;$i<count($NotificacionModificacion);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionModificacion[$i];
	}
	$requete.= "'";
}
if (count($NotificacionCierre)>0)
{
	$requete.= ",'";
	for ($i=0;$i<count($NotificacionCierre);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionCierre[$i];
	}
	$requete.= "'";
}
if (count($NotificacionPasadoFecha)>0)
{
	$requete.= ",'";
	for ($i=0;$i<count($NotificacionPasadoFecha);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionPasadoFecha[$i];
	}
	$requete.= "'";
}
$requete.=");";
mysqli_query($db,$requete);
if (count($NotificacionCreacion)>0)
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
	$msg = "<h1>".$Nombre."</h1>".$Descripcion;
	include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");			
	for ($i=0;$i<count($NotificacionCreacion);$i++)
	{
		if ($NotificacionCreacion[$i]=="A"&&$Asignado!="")
		{			
			//Miro para mandarle un mail
			$requete="SELECT * FROM `Usuarios` WHERE Id=".$Asignado;
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
				$EmailUsuario = $listado->Email;
			}
			if (($EmailUsuario!="")&&($EmailEntorno!=""))
			{
				//Mandamos el mail
				mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["nuevaTarea"]), $mensaje, $headers);
			}
		}
		if ($NotificacionCreacion[$i]=="R"&&$IdResponsable!="")
		{			
			//Miro para mandarle un mail
			$requete="SELECT * FROM `Usuarios` WHERE Id=".$IdResponsable;
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
				$EmailUsuario = $listado->Email;
			}
			if (($EmailUsuario!="")&&($EmailEntorno!=""))
			{
				//Mandamos el mail
				mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["nuevaTarea"]), $mensaje, $headers);
			}
		}
		if ($NotificacionCreacion[$i]=="C"&&$Proyecto!=""&&$publico=="si")
		{
			//Miro para mandarle un mail
			$requete="SELECT * FROM `Proyectos` WHERE Id=".$Proyecto;
			
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
								mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["nuevaTarea"]), $mensaje, $headers);
							}
						}
					}					
				}
			}
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso

if ($ToDo==1) header("Location:/administra/Interface/administra.php");
else header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=tareas");

?>