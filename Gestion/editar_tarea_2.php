<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET["pagina"];
$Nombre = $_POST["Nombre"];
$Descripcion = $_POST["Descripcion"];
$PorcentajeEjecucion = $_POST["PorcentajeEjecucion"];
$Tipo = $_POST["Tipo"];
$Asignado = $_POST["Asignado"];
$publico = $_POST["publico"];
$Responsable = $_POST["Responsable"];
$ReAbrir = $_POST["ReAbrir"];
$FechaInicio = $_POST["FechaInicio"];
$FechaFin = $_POST["FechaFin"];
$origen = $_POST["origen"];
$NotificacionCreacion = $_POST["NotificacionCreacion"];
$NotificacionModificacion = $_POST["NotificacionModificacion"];
$NotificacionCierre = $_POST["NotificacionCierre"];
$NotificacionPasadoFecha = $_POST["NotificacionPasadoFecha"];
$Proyecto = $_POST["Proyecto"];
$IdProyecto = $_POST["IdProyecto"];
$confirmar = $_POST['confirmar'];
$pagina = $_POST["pagina"];
$eliminar_tarea = $_POST["eliminar_tarea"];
$Id = $_POST["Id"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");
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
$requete = "UPDATE `Tareas` SET `Nombre` = '".$Nombre."',`Descripcion`='".$Descripcion."',`PorcentajeEjecucion`='".$PorcentajeEjecucion."',`Tipo`='".$Tipo."'";
if ($eliminar_tarea!="") $requete.=",`Borrada`=1";
if ($confirmar==1) $requete.=",`Pendiente`=NULL";
else $requete.=",`Pendiente`=1";
if ($Asignado!="") $requete.= ",`IdUsuarioAsignado`=".$Asignado.",`Publica`='".$publico."'";
if ($Responsable!="") $requete.=",`IdResponsable`=".$Responsable;
else $requete.=",`IdResponsable`=NULL";
if ($ReAbrir!="") $requete.=",`FechaCierre`=NULL";
$requete.=",`Fecha`='".$FechaInicio."'";
if ($FechaFin!="") $requete.=",`FechaFin`='".$FechaFin."'";
else $requete.=",`FechaFin`= NULL";
if (count($NotificacionCreacion)>0) 
{
	$requete.=",`NotificacionCreacion`='";
	for ($i=0;$i<count($NotificacionCreacion);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionCreacion[$i];
	}
	$requete.="'";
}
else $requete.=",`NotificacionCreacion`= NULL";
if (count($NotificacionModificacion)>0) 
{
	$requete.=",`NotificacionModificacion`='";
	for ($i=0;$i<count($NotificacionModificacion);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionModificacion[$i];
	}
	$requete.="'";
}
else $requete.=",`NotificacionModificacion`= NULL";
if (count($NotificacionCierre)>0)
{
	$requete.=",`NotificacionCierre`='";
	for ($i=0;$i<count($NotificacionCierre);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionCierre[$i];
	}
	$requete.="'";
}
else $requete.=",`NotificacionCierre`= NULL";
if (count($NotificacionPasadoFecha)>0) 
{
	$requete.=",`NotificacionPasadoFecha`='";
	for ($i=0;$i<count($NotificacionPasadoFecha);$i++)
	{
		if ($i!=0) $requete.= ":";
		$requete.= $NotificacionPasadoFecha[$i];
	}
	$requete.="'";
}
else $requete.=",`NotificacionPasadoFecha`= NULL";
if ($Proyecto!="") $requete.=",`IdProyecto`=".$Proyecto;
else $requete.=",`IdProyecto`=NULL";
$requete.= " WHERE `Id`='".$Id."';";
mysqli_query($db,$requete);
if (count($NotificacionModificacion)>0)
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
	for ($i=0;$i<count($NotificacionModificacion);$i++)
	{	
		if ($Asignado!=""&&$NotificacionModificacion[$i]=="A")
		{
			//Miro para mandarle un mail
			$requete="SELECT * FROM `Usuarios` WHERE Id=".$Asignado;	
			
			if ($result = mysqli_query($db, $requete))
			{		
				$listado = mysqli_fetch_object($result);
				$EmailUsuario = $listado->Email;		
			}
			if ($EmailUsuario!=""&&$EmailEntorno!="")
			{
				//Mandamos el mail
				mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["tareaEditada"]), $mensaje, $headers);				
			}
		}
		if ($NotificacionModificacion[$i]=="R"&&$Responsable!="")
		{
			//Miro para mandarle un mail
			$requete="SELECT * FROM `Usuarios` WHERE Id=".$Responsable;	
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
				$EmailUsuario = $listado->Email;				
			}
			if ($EmailUsuario!=""&&$EmailEntorno!="")
			{
				mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["tareaEditadaResponsable"]), $mensaje, $headers);
			}
		}
		if ($NotificacionModificacion[$i]=="C"&&$Proyecto!=""&&$publico=="si")
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
								mail($EmailUsuario, utf8_encode("Dejávù - ".$lang["tareaEditada"]), $mensaje, $headers);
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
if ($origen!='')
{
	if ($origen=='home') header("Location:/administra/Interface/administra.php");
}
else
{	
	if ($IdProyecto!="") header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&pagina=".$pagina);
	else header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=tareas&pagina=".$pagina);
}
?>