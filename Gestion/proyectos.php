<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET["pagina"];
$Activos = $_GET["Activos"];
$Mantenimientos = $_GET["Mantenimientos"];
$Plantillas = $_GET["Plantillas"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
/*
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
*/
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/proyectos-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
if ($_SESSION['usuario_nivel']>=3) $requete="SELECT * FROM `Proyectos`,`ProyectosTrabajadores` WHERE `Proyectos`.Id=`ProyectosTrabajadores`.IdProyecto";
else $requete="SELECT * FROM `Proyectos` WHERE 1=1";
if ($_GET['Buscar']!='') $requete.=" AND `Nombre` LIKE '%".$_GET['Buscar']."%'";
if ($Activos=="si") $requete.=" AND `Activo`=1";
if ($Activos=="no") $requete.=" AND `Activo`=0";
if ($Mantenimientos!="") $requete.=" AND `Mantenimiento`=1";
if ($Plantillas!="") $requete.=" AND `IdPlantilla` IS NOT NULL";
else $requete.=" AND `IdPlantilla` IS NULL";
if ($_SESSION['usuario_nivel']>=3) $requete.=" AND `ProyectosTrabajadores`.IdUsuario=".$_SESSION['usuario_id'];
$result = mysqli_query($db, $requete);
$total_contenidos_pagina = mysqli_num_rows($result);
if ($_SESSION['usuario_nivel']>=3) $requete="SELECT `Proyectos`.* FROM `Proyectos`,`ProyectosTrabajadores` WHERE `Proyectos`.Id=`ProyectosTrabajadores`.IdProyecto";
else $requete="SELECT * FROM `Proyectos` WHERE 1=1";
if ($_SESSION['usuario_nivel']>=3) $requete.=" AND `ProyectosTrabajadores`.IdUsuario=".$_SESSION['usuario_id'];
if ($_GET['Buscar']!='') $requete.=" AND `Nombre` LIKE '%".$_GET['Buscar']."%'";
if ($Activos=="si") $requete.=" AND `Activo`=1";
if ($Activos=="no") $requete.=" AND `Activo`=0";
if ($Mantenimientos!="") $requete.=" AND `Mantenimiento`=1";
if ($Plantillas!="") $requete.=" AND `IdPlantilla` IS NOT NULL";
else $requete.=" AND `IdPlantilla` IS NULL";
$requete .= " ORDER BY `Id` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;
print '<div class="row">';
print "<div class=\"btn-group col-md-12\">";
print "<a class=\"btn btn-sm";
if ($_GET['Activos']=='si') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Activos=si\">".$lang["proyectosActivos"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['Activos']=='no') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Activos=no\">".$lang["proyectosNoActivos"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['Activos']=='todas') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Activos=todas\">".$lang["proyectosTodos"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['Mantenimientos']=='si') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Mantenimientos=si\">".$lang["proyectosMantenimientos"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['Plantillas']=='si') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Plantillas=si\">".$lang["proyectosPlantillas"]."</a>";
print "</div></div>";
if (isset($Num_Pagina))
{
	//PAGINACIÓN	
	print '<ul class="pagination">';
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Activos=".$Activos."&pagina=".$i."\">".$i."</a></li>";
	}	
	print "</ul>";
}
if ($result = mysqli_query($db, $requete))
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['titulo-proyectos'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content">';
	print '
		<div class="row"><div class="col-md-12">
							<form method="get">
                            <div class="input-group">	
								<input type="hidden" name="modulo" value="Gestion"/>
								<input type="hidden" name="herramienta" value="proyectos"/>
								<input type="hidden" name="Activos" value="'.$_GET['Activos'].'"/>
								<input type="hidden" name="pagina" value="'.$_GET['pagina'].'"/>
                                <input type="text" name="Buscar" placeholder="'.$lang['buscar'].'" class="input form-control" name="Buscar">
                                <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i></button>
                                </span>
                            </div>
							</form></div></div>';
	print '<div class="row"><div class="">
                                <table class="table table-striped">
                                    <thead>';
	print "<tr><th>".$lang["acciones"]."</th><th>Id</th><th>".$lang["cliente"]."</th><th>".$lang["nombre"]."</th><th>".$lang["tareasAbiertas"]."</th><th>".$lang["tareasCerradas"]."</th><th>".$lang["porcentaje"]."</th></tr>";
	print '</thead><tbody>';	
	while ($listado = mysqli_fetch_object($result))
	{
		print "<tr>";	
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		if ($listado->Activo)
		{
			print "<li><a href=\"/administra/Gestion/funciones/hecho.php?IdProyecto=".$listado->Id."\"><i class='fa fa-check-square-o'></i> ".$lang["hecho"]."</a></li>";			
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_tarea&IdProyecto=".$listado->Id."\"><i class='fa fa-plus'></i> ".$lang["nuevaTarea"]."</a></li>";
		}
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$listado->Id."\"><i class='fa fa-tasks'></i> ".$lang["tareas"]."</a></li>";
		print "<ul></div></td>";
		print "<td>".$listado->Id."</td>";
		print "<td>";
		$requete2="SELECT * FROM Clientes WHERE Id=".$listado->IdCliente;		
		if ($result2 = mysqli_query($db, $requete2)) 
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->DenominacionSocial;
		}
		else print "&nbsp;";
		print "</td>";
		print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_proyecto&Id=".$listado->Id."\">";
		print $listado->Nombre;
		print "</a></td>";
		$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NULL";
		$result2 = mysqli_query($db, $requete2);
		$total_tareas_abiertas = mysqli_num_rows($result2);
		print "<td>".$total_tareas_abiertas."</td>";
		$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NOT NULL";
		$result2 = mysqli_query($db, $requete2);
		$total_tareas_cerradas = mysqli_num_rows($result2);
		print "<td>".$total_tareas_cerradas."</td>";
		print "<td>";
		if (($total_tareas_cerradas + $total_tareas_abiertas)>0) $porcentajeEjecucion = ($total_tareas_cerradas*100) / ($total_tareas_cerradas + $total_tareas_abiertas);		
		else $porcentajeEjecucion = 0;
		print '<span class="pie">'.$porcentajeEjecucion.'/100</span> '.number_format($porcentajeEjecucion,0,'','')."%";
		print "</td>";
		print "</tr>";
	}
	print "</tbody><tfoot><tr><td colspan=\"5\"><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</td></tr></tfoot></table></div></div></div>";
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN		
		print '<ul class="pagination">';
		if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
		else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
			else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=proyectos&Activos=".$Activos."&pagina=".$i."\">".$i."</a></li>";
		}	
		print "</ul>";
	}
}
?>
