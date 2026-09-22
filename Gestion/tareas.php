<?php
//VERSIÓN: v1.0 2014-4-1
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdProyecto = $_GET["IdProyecto"];
$activas = $_GET["activas"];
$IdUsuario = $_GET["IdUsuario"];
$Fecha1 = $_GET["Fecha1"];
$Fecha2 = $_GET["Fecha2"];
$pagina = $_GET["pagina"];

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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/tareas-".$_SESSION['idioma'].".conf");
if ($IdProyecto!="")
{
	$requete = "SELECT * FROM Proyectos WHERE Id=".$IdProyecto;
	
	$listado = mysqli_fetch_object($result);
	$NombreProyecto = $listado->Nombre;	
}
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Tareas` WHERE 1=1 AND (`Borrada` IS NULL OR `Borrada`=0)";
if ($activas=="no") $requete.=" AND `FechaCierre` IS NOT NULL";
if ($activas=="si" || $activas =="") $requete.=" AND `FechaCierre` IS NULL";
if ($IdUsuario!="") $requete .= " AND `IdUsuarioAsignado`='".$IdUsuario."'";
if ($IdProyecto!="") $requete.= " AND `IdProyecto`=".$IdProyecto;
if ($_GET['Buscar']!='') $requete.=" AND `Nombre` LIKE '%".$_GET['Buscar']."%'";
if ($Fecha1!='') $requete.=" AND `Fecha`>='".$Fecha1."'";
if ($Fecha2!='') $requete.=" AND `Fecha`<='".$Fecha2."'";
if ($_SESSION['usuario_nivel']>1) $requete.= " AND (`IdUsuarioAsignado`=".$_SESSION['usuario_id']." OR `IdUsuarioCreador`=".$_SESSION['usuario_id'].")";
$result = mysqli_query($db, $requete);
$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `Fecha`";
if ($Fecha1!=''&&$Fecha2!='') {}
else $requete .= " LIMIT ".$intervalo_inicial.",".$Num_Pagina;
print '<div class="row">';
print "<div class=\"btn-group col-md-12\">";
print "<a class=\"btn btn-sm";
if ($_GET['activas']=='si') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=si\">".$lang["tareasAbiertas"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['activas']=='no') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=no\">".$lang["tareasCerradas"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['activas']=='todas') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=todas\">".$lang["tareasTodas"]."</a>";
print "</div></div>";
if ($_SESSION['usuario_nivel']<3)
{
	print '<div class="row">';
	print "<div class=\"btn-group col-md-12\">";
	$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";
	
	// Listamos los representantes existentes
	if ($result2 = mysqli_query($db, $requete2))
	{
		while($listado2 = mysqli_fetch_object($result2))
		{
			print "<a class=\"btn btn-sm";
			if ($_GET['IdUsuario']==$listado2->Id) print " btn-primary";
			else print " btn-white";
			print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$listado2->Id."&pagina=".$pagina."&activas=si\">";
			if ($listado2->Foto!='') print '<img alt="image" width="18px" class="img-circle" alt="'.$listado2->Nombre.' '.$listado2->Apellidos.'" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
			print ' '.$listado2->Nombre."</a>";
		}
	}
	print "</div></div>";
}
if (isset($Num_Pagina))
{
	//PAGINACIÓN
	print '<ul class="pagination">';
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else 
		{
			print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&pagina=".$i;
			if ($IdProyecto!="") print "&IdProyecto=".$IdProyecto;
			if ($activas!="") print "&activas=".$activas;
			if ($IdUsuario!="") print "&IdUsuario=".$IdUsuario;
			print "\">".$i."</a></li>";
		}
	}	
	print "</ul>";
}

$titulo_pagina = "";
if ($NombreProyecto!='') $titulo_pagina = $lang["tareasDeProyecto"]." ".$NombreProyecto;
print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$titulo_pagina.'</h5>';
print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
print '<div class="ibox-content">';
print '
	<div class="row"><form method="get"><div class="col-md-2"><label>'.$lang['desde'].'</label><input class="form-control" format="yyyy-MM-dd" type="date" value="'.$_GET['Fecha1'].'" name="Fecha1"></div><div class="col-md-2"><label>'.$lang['hasta'].'</label><input class="form-control" type="date" format="yyyy-MM-dd" value="'.$_GET['Fecha2'].'" name="Fecha2"></div><div class="col-md-8">
						
						<div class="input-group">	
							<input type="hidden" name="modulo" value="Gestion"/>
							<input type="hidden" name="herramienta" value="tareas"/>
							<input type="hidden" name="Activos" value="'.$_GET['Activos'].'"/>
							<input type="hidden" name="activas" value="'.$_GET['activas'].'"/>							
							<input type="hidden" name="IdProyecto" value="'.$_GET['IdProyecto'].'"/>
							<input type="hidden" name="pagina" value="'.$_GET['pagina'].'"/>
							<input type="text" name="Buscar" placeholder="'.$lang['buscar'].'" class="input form-control" name="Buscar">
							<span class="input-group-btn">
									<button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i></button>
							</span>
						</div>
						</div></form></div>';

print '<div class="row"><div>';
if ($result = mysqli_query($db, $requete))
{
	print '<table class="table table-striped">
								<thead>';
	print "<tr><th>".$lang["acciones"]."</th><th>Id</th><th>".$lang["nombre"]."</th><th>".$lang["fecha"]."</th><th>".$lang["fechaFin"]."</th><th>".$lang["creador"]."</th><th>".$lang["asignado"]."</th><th>".$lang["tipo"]."</th><th>".$lang["horas"]."</th><th>".$lang["ejecucion"]."</th></tr></thead></tbody>";
	while ($listado = mysqli_fetch_object($result))
	{
		if ($listado->Pendiente==1) print "<tr class='warning'>";
		else print "<tr>";
		print "<td>";
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		if ($listado->FechaCierre=="") 
		{
			print "<li><a href=\"/administra/Gestion/funciones/hecho.php?Id=".$listado->Id."&IdProyectoReferencia=".$IdProyecto."&anticache=".time()."\"><i class='fa fa-check-square-o'></i> ".$lang["hecho"]."</a></li>";
			print "<li><a href=\"/administra/Gestion/funciones/trabajar.php?Id=".$listado->Id."&IdProyectoReferencia=".$IdProyecto."&anticache=".time()."\">";
			if ($listado->EmpezadoTrabajar!=NULL) print "<i class='fa fa-clock-o'></i> ".$lang["parar"]."</a></li>";
			else print "<i class='fa fa-clock-o'></i> ".$lang["entrar"]."</a></li>";
		}
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_tarea&Id=".$listado->Id."&pagina=".$pagina."&IdProyecto=".$IdProyecto."\"><i class='fa fa-edit'></i> ".$lang["editar"]."</a></li>";
		print "<ul></div></td>";
		print "<td>".$listado->Id."</td>";
		print "<td>";	
		if ($listado->EmpezadoTrabajar!='') print "<strong>";
		print $listado->Nombre;
		if ($listado->EmpezadoTrabajar!='') print "</strong>";
		print "</td>";
		$tmp = explode(" ",$listado->Fecha);
		$tmp = explode("-",$tmp[0]);
		print "<td>".$tmp[2]."/".$tmp[1]."/".$tmp[0]."</td>";
		if ($listado->FechaFin!="")
		{
			$tmp = explode(" ",$listado->FechaFin);
			$tmp = explode("-",$tmp[0]);
			print "<td>".$tmp[2]."/".$tmp[1]."/".$tmp[0]."</td>";
		}
		else print "<td>".$lang["noAplica"]."</td>";
		print "<td>";
		$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdUsuarioCreador;
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
		}
		if ($listado2->Foto!='') print '<img alt="image" width="38px" class="img-circle" alt="'.$listado2->Nombre.' '.$listado2->Apellidos.'" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
		else print $listado2->Nombre." ".$listado2->Apellidos;
		print "</td>";
		$requete2 = "SELECT * FROM `Usuarios` WHERE `Id` = '".$listado->IdUsuarioAsignado."'";
		if ($result2 = mysqli_query($db, $requete2))
		{		
			$listado2 = mysqli_fetch_object($result2);
		}
		print "<td>";
		if ($listado2->Foto!='') print '<img alt="image" width="38px" class="img-circle" alt="'.$listado2->Nombre.' '.$listado2->Apellidos.'" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
		else print $listado2->Nombre." ".$listado2->Apellidos;
		print "</td>";
		print "<td>";
		$requete2 = "SELECT * FROM `TareasTipos` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Id`='".$listado->Tipo."'";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
		}
		print $listado2->Nombre;
		print "</td>";
		print "<td>";
		printf ("%.2f", (($listado->TiempoDedicado)/60)/60);
		print "</td>";
		print "<td>";
		if ($listado->FechaCierre!='') $porcentaje_ejecucion = 100;
		else $porcentaje_ejecucion = $listado->PorcentajeEjecucion;
		print '<span class="pie">'.$porcentaje_ejecucion.'/100</span> '.number_format($porcentaje_ejecucion,0,'','')."%";
		print "</td>";
		print "</tr>";
	}
	print "</tbody><tfoot><tr><td colspan=\"5\"><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</td></tr></tfoot></table>";
}
print "</div></div></div>";
if (isset($Num_Pagina))
{
	//PAGINACIÓN
	print '<ul class="pagination">';
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else 
		{
			print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&pagina=".$i;
			if ($IdProyecto!="") print "&IdProyecto=".$IdProyecto;
			if ($activas!="") print "&activas=".$activas;
			if ($IdUsuario!="") print "&IdUsuario=".$IdUsuario;
			print "\">".$i."</a></li>";
		}
	}	
	print "</ul>";
}
?>
