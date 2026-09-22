<?php
//VERSIÓN: v1.0 2014-4-1
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdUsuario = $_GET["IdUsuario"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/empleados-".$_SESSION['idioma'].".conf");

$requete = "SELECT * FROM `Usuarios` WHERE `TieneTareas`=1 OR `RepresentantePresupuestos`=1";
$result = mysqli_query($db, $requete);
$total_contenidos_pagina = mysqli_num_rows($result);

$titulo_pagina = "";
if ($NombreProyecto!='') $titulo_pagina = $lang["tareasDeProyecto"]." ".$NombreProyecto;
print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$titulo_pagina.'</h5>';
print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
print '<div class="ibox-content">';

print '<div class="row"><div>';
if ($result = mysqli_query($db, $requete))
{
	print '<table class="table table-striped">
								<thead>';
	print "<tr><th>Id</th><th>".$lang["nombre"]."</th><th>".$lang["tareas_asignadas"]."</th><th>".$lang["tareas_responsable"]."</th><th>".$lang["horas_semana"]."</th><th>".$lang["horas_hoy"]."</th></tr></thead></tbody>";
	while ($listado = mysqli_fetch_object($result))
	{		
		print "<tr>";
		print "<td>".$listado->Id."</td>";
		print "<td>";
		if ($listado->Foto!='') print '<img alt="image" width="38px" class="img-circle" alt="'.$listado->Nombre.' '.$listado->Apellidos.'" src="/Imagenes/Perfiles/'.$listado->Foto.'"> ';
		print $listado->Nombre." ".$listado->Apellidos;
		print "</td>";
		print "<td>";
		$requete2 = "SELECT count(*) as total FROM `Tareas` WHERE `IdUsuarioAsignado` = '".$listado->Id."' AND `FechaCierre` IS NULL";		
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print '<a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdUsuario='.$listado->Id.'" class="badge badge-primary">'.$listado2->total.'</a>';
		}
		else print '<a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdUsuario='.$listado->Id.'" class="badge badge-primary">0</a>';
		print "</td>";
		print "<td>";
		$requete2 = "SELECT count(*) as total FROM `Tareas` WHERE `IdResponsable` = '".$listado->Id."' AND `FechaCierre` IS NULL";		
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print '<a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdResponsable='.$listado->Id.'" class="badge badge-primary">'.$listado2->total.'</a>';
		}
		else print '<a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdResponsable='.$listado->Id.'" class="badge badge-primary">0</a>';
		print "</td>";
		print "<td>";
		$requete2 = "SELECT * FROM `TareasFichar` WHERE `IdUsuario`=".$listado->Id." AND `FechaEntrada`>'".date('Y-m-d',strtotime('-1 week'))." 23:59:59'";
		$total_horas = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			
			while ($listado2 = mysqli_fetch_object($result2))
			{
				$tmp1 = strtotime($listado2->FechaSalida);
				$tmp2 = strtotime($listado2->FechaEntrada);
				$dif = $tmp1 - $tmp2;
				$total_horas += $dif/(60*60);
			}
		}
		print number_format($total_horas,2,',','').'h';
		print "</td>";
		print "<td>";
		$requete2 = "SELECT * FROM `TareasFichar` WHERE `IdUsuario`=".$listado->Id." AND `FechaEntrada`>'".date('Y-m-d',strtotime('-1 day'))." 23:59:59'";		
		$total_horas = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			
			while ($listado2 = mysqli_fetch_object($result2))
			{
				$tmp1 = strtotime($listado2->FechaSalida);
				$tmp2 = strtotime($listado2->FechaEntrada);
				$dif = $tmp1 - $tmp2;
				$total_horas += $dif/(60*60);
			}
		}
		print number_format($total_horas,2,',','').'h';
		print "</td>";
		print "</tr>";
	}
	print "</tbody></table>";
}
print "</div></div></div>";
?>
