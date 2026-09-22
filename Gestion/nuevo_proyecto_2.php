<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdCliente = $_POST["IdCliente"];
$Responsable = $_POST["Responsable"];
$Nombre = $_POST["Nombre"];
$Descripcion = $_POST["Descripcion"];
$Plantilla = $_POST["Plantilla"];
$Mantenimiento = $_POST["Mantenimiento"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
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

$requete = "INSERT INTO `Proyectos` (`Nombre`,`Descripcion`,`FechaCreacion`,`Activo`";
if ($Mantenimiento!="") $requete.=",`Mantenimiento`";
if ($IdCliente!="") $requete.=",`IdCliente`";
if ($Responsable!="") $requete.=",`IdResponsable`";
$requete.= ") VALUES ('".$Nombre."', '".$Descripcion."', '".date("Y-m-d")."', 1";
if ($Mantenimiento!="") $requete.=",1";
if ($IdCliente!="") $requete.=",".$IdCliente;
if ($Responsable!="") $requete.=",".$Responsable;
$requete.=");";
mysqli_query($db,$requete);
$IdProyecto = mysqli_insert_id($db);

//Si se usó una plantilla, cargamos todas las tareas de la plantilla
if ($Plantilla!="")
{
	//CONSULTAMOS LAS TAREAS
	$requete = "SELECT * FROM `Tareas` WHERE `IdPlantillaProyecto`=".$Plantilla;
	
	if ($result = mysqli_query($db, $requete))
	{
		$primeraFecha = "";
		while($listado = mysqli_fetch_object($result))
		{
			//Duplicamos la tarea
			$requete2 = "INSERT INTO `Tareas` (`Nombre`,`Descripcion`,`IdResponsable`,`IdProyecto`,`Fecha`,`FechaFin`,`Tipo`,`Publica`,`NotificacionCreacion`,`NotificacionModificacion`,`NotificacionCierre`,`NotificacionPasadoFecha`) SELECT `Nombre`,`Descripcion`,`IdResponsable`,".$IdProyecto;
			if ($primeraFecha=="")
			{
				//Fecha Inicio
				$primeraFecha = new DateTime($listado->Fecha);
				$requete2.= ",'".date("Y-m-d")."'";				
				//Fecha Fin
				$fecha = new DateTime(date("Y-m-d"));
				$fechaFinTarea = new DateTime($listado->FechaFin);
				$diferencia = $primeraFecha->diff($fechaFinTarea);				
				$fecha->add($diferencia);
				$requete2.=",'".$fecha->format('Y-m-d H:i:s')."'";
			}
			else
			{
				//Fecha Inicio
				$fechaTarea = new DateTime($listado->Fecha);
				$diferenciaInicio = $primeraFecha->diff($fechaTarea);
				$fecha = new DateTime(date("Y-m-d"));
				$fecha->add($diferenciaInicio);
				$requete2.=",'".$fecha->format('Y-m-d H:i:s')."'";
				//Fecha Fin				
				$fechaFinTarea = new DateTime($listado->FechaFin);
				$diferencia = $fechaTarea->diff($fechaFinTarea);				
				$fecha->add($diferencia);				
				$requete2.=",'".$fecha->format('Y-m-d H:i:s')."'";
			}			
			$requete2.=",`Tipo`,`Publica`,`NotificacionCreacion`,`NotificacionModificacion`,`NotificacionCierre`,`NotificacionPasadoFecha` FROM `Tareas` WHERE `Id`=".$listado->Id;
			mysql_query($requete2,$db);	
		}
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=proyectos");
?>