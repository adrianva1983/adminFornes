<?php
date_default_timezone_set ('Europe/Madrid');
//VERSIÓN: v1.1 2013-11-13
//COMPATIBLE PHP 7
//DEFINICIÓN VARIABLES
$acciones_disponibles = true;
if ($_GET["pagina"]!='') $pagina = $_GET["pagina"];
else $pagina = 0;
$seccion = $_GET["seccion"];
$ruta = $_GET["ruta"];
$orden_listado=$_GET["orden_listado"];
$info=$_GET["info"];
function botones($url_ima,$url_acc)
{
	print "<a href=$url_acc> <img src=$url_ima \\></a>\n";
}
require("funciones/ordenacion_2.php");
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	header ("Location: $redir?error_login=5");
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0];	
	return $lafecha;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/secciones-".$_SESSION['idioma'].".conf");
//Miramos los idiomas activados en la parte externa
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	$i=0;
	while($listado = mysqli_fetch_object($result))
	{
		$idiomas[$i]=$listado->Codigo;
		$descripcionIdioma[$i]=$listado->Descripcion;
		$i++;
	}
}

//Miramos el número de Contenidos por Página configurado
$requete = "SELECT * FROM `Servidor` WHERE `Campo` LIKE '%Secciones - Contenidos por Página%'";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	$listado = mysqli_fetch_object($result);
	$num_contenidos_pagina = $listado->Valor;
	if (!isset($pagina)) $pagina = 0;
	$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto')";
	$result = mysqli_query($db,$requete);
	$total_contenidos_pagina = mysqli_num_rows($result);		
}
// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion." ORDER BY `Orden`";
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);

//Si no es administrador comprobamos que se tengan permisos
if ($_SESSION['usuario_nivel'] > 1)
{// COMPROBACIÓN PERMISOS
	// Tratamos primer elemento. Si tenemos permiso sobre el elemento actual todo va bien	---USUARIO---
	$requete2 = "SELECT * FROM `Permisos` WHERE `IdSeccion`=".$seccion." AND `Nivel`<'5' AND `IdUsuarioSuscrito`='".$_SESSION['usuario_id']."'";
	$result = mysqli_query($db,$requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		$listado2 = mysqli_fetch_object($result2);
		$nivel = $listado2->Nivel;
	}
	// Tratamos primer elemento. Si tenemos permiso sobre el elemento actual todo va bien a nivel de grupos	---GRUPOS---
	$requete2 = "SELECT * FROM Grupos,PertenenciaGrupos WHERE `PertenenciaGrupos`.IdGrupo = `Grupos`.Id AND `PertenenciaGrupos`.IdUsuario = ".$_SESSION['usuario_id'];	
	$result2 = mysqli_query($db,$requete2);
	if (($result2) && (mysqli_num_rows($result2)>0))
	{
		while($listado2 = mysqli_fetch_object($result2))
		{			
			$requete3 = "SELECT Secciones.Id,Secciones.Titulo,Secciones.Path,Secciones.NomFich,Permisos.* FROM Permisos,Secciones WHERE IdGrupoSuscrito=".$listado2->IdGrupo." AND Secciones.Id=Permisos.IdSeccion;";
			$result3 = mysqli_query($db,$requete3);
			if (($result3) && (mysqli_num_rows($result3)>0))
			{				
				$listado3 = mysqli_fetch_object($result3);
				if ($nivel == "") $nivel = $listado3->Nivel;
				else
				{
					if ($listado3->Nivel< $nivel) $nivel = $listado3->Nivel;
				}
			}
		}
	}
	// Si no tenemos permiso sobre el elemento actual recorremos todos los padres ---USUARIO---
	$listado3=$listado;
	if ((!$nivel) AND ($listado3->IdPadre))
	{ 
		while (($listado3->IdPadre) AND (!$nivel))
		{
			$requete3 = "SELECT * FROM `Secciones` WHERE `Id`='".$listado3->IdPadre."'";
			
			$listado3 = mysqli_fetch_object($result3);
			$requete2 = "SELECT * FROM `Permisos` WHERE `IdSeccion`='".$listado3->Id."' AND `Nivel`<'5' AND `IdUsuarioSuscrito`='".$_SESSION['usuario_id']."'";
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))
			{
				$listado2 = mysqli_fetch_object($result2);
				$nivel = $listado2->Nivel;
			}
		}
	}
	if (!$nivel)
	{
		die ("<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>");
		exit;
	}
}
else $nivel=1;
print '
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <h2>'.$lang["estaUsted"].' <em>'.$listado->Titulo.'</em></h2>
					<div class="row">
					<div class="col-lg-12">
                    <ol class="breadcrumb"><li><a href="/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz">'.$lang["raiz"].'</a></li>';
//--------------------------------
// Mostramos la ruta
$tmp_ruta = '';
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion;
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$tmp_ruta= '<li><a href="/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion='.$listado->Id.'">'.$listado->Titulo.'</a></li>'.$tmp_ruta;
		if ($listado->IdPadre!='')
		{
			$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$listado->IdPadre;			
		}
		else break;
	}
}
print $tmp_ruta.'					
                    </ol>
                </div>
				</div>
				</div>';

// Herramientas superiores
print '<div class="col-lg-12"><div class="row">';
print "<div class=\"btn-group col-md-12\">";
print "<a class=\"btn btn-sm";
if ($_GET['orden_listado'] =='') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\">".$lang["ordenNormal"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['orden_listado'] =='alfabetico') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=alfabetico\">".$lang["ordenAlfabetico"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['orden_listado'] =='clicks') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=clicks\">".$lang["ordenClicks"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['orden_listado'] =='vistas') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=vistas\">".$lang["ordenVistas"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['orden_listado'] =='modificacion') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=modificacion\">".$lang["ordenModificacion"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['info'] =='numXseccion') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroXseccion\">".$lang["contenidosPor"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['info'] =='numeroPendientes') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroPendientes\">".$lang["pendiente"]."</a>";
print "<a class=\"btn btn-sm";
if ($_GET['info'] =='numeroSuscritos') print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroSuscritos\">".$lang["numeroSuscritos"]."</a>";
for ($i=1;($i<=count($idiomas));$i++)
{
	//Si el padre no está en el idioma no permitimos añadir el idioma a los hijos		
	$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
	$result2 = mysqli_query($db,$requete2);
	if (($result2) && (mysqli_num_rows($result2)>0))	
	{		
		$listado2 = mysqli_fetch_object($result2);
		if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
		else $rutaIdioma = $listado2->NomFich;
		$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$listado->Id."' AND `Idioma`='".$idiomas[$i]."'";
		$result2 = mysqli_query($db,$requete2);
		if (($result2) && (mysqli_num_rows($result2)>0))		
		{
			$listado2 = mysqli_fetch_object($result2);
			$rutaIdioma.="/".$listado2->NomFich;			
			print "<a class=\"btn btn-sm";
			print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado2->Id."&ruta=".$rutaIdioma."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"></a>";			
		}		
	}
}
print "</div></div>";
print '</div></div>';
print '<div class="wrapper wrapper-content">';
// Hacemos una consulta para ver cuantas secciones hay en esta ubicación
$requete = "SELECT `Id`,`Orden`,`NomFich`,`Titulo`, `Visibilidad`, `IdEntorno`, `NomFich`,`Plantilla` FROM `Secciones` WHERE `IdPadre`=".$seccion." ORDER BY `Orden`";
$result = mysqli_query($db,$requete);
// Listamos las secciones existentes
if (($result) && (mysqli_num_rows($result)>0))
{	
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['secciones'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row">';
	print '<table class="table table-striped"><tbody>';
	while($listado = mysqli_fetch_object($result))
	{	
		print "<tr>";	
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		if ($nivel<4)
		{
			$requete2= "SELECT * FROM `Entornos` WHERE `SeccionPrincipal`=".$listado->Id;
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))
			{
				$listado2 = mysqli_fetch_object($result2);
				print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=configuracion_entorno&entorno=".$listado2->Id."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/application_form.png\" alt=\"".$lang["parametrosEntorno"]."\" title=\"".$lang["parametrosEntorno"]."\"> ".$lang["parametrosEntorno"]."</a></li>";
			}
		}
		if (($herramientas[61]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[61]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
		{
			if ($_SESSION['usuario_nivel']<=2)
			{ //NIVEL MÍNIMO COORDINADOR
				print '<li><a href="herramienta.php?modulo=Carpetas&amp;herramienta=editar_seccion&amp;seccion='.$listado->Id.'"><i class="fa fa-edit"></i> '.$lang['editar_seccion'].'</a></li>';
			}
		}
		switch ($listado->Visibilidad)
		{
			case "visible":
				//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
				if ($nivel<3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=oculto&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-pause\"></i> ".$lang["desactivar"]."</a></li>";
				break;
			case "oculto":
				//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
				if ($nivel<3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=visible&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-play\"></i> ".$lang["activar"]."</a></li>";
			break;
			case "privado":
			//PERMISOS NECESARIOS PARA QUITAR DE PRIVADO MÍNIMO ADMINISTRADOR
				if ($nivel<2) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=visible&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-key\"></i> ".$lang["privado"]."</a></li>";
			break;
		}
		$orden=$listado->Orden;
		//PERMISOS NECESARIOS PARA ORDENAR DE RESPONSABLE
		if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_arriba.png\" alt=\"".$lang["toparriba"]."\" title=\"".$lang["toparriba"]."\"> ".$lang["toparriba"]."</a></li>";
		if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"> ".$lang["arriba"]."</a></li>";
		if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"> ".$lang["abajo"]."</a></li>";
		if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topabajo"]."\" title=\"".$lang["topabajo"]."\"> ".$lang["topabajo"]."</a></li>";
		for ($i=1;($i<=count($idiomas));$i++)
		{
			//Si el padre no está en el idioma no permitimos añadir el idioma a los hijos		
			$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))						
			{
				$listado2 = mysqli_fetch_object($result2);
				if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
				else $rutaIdioma = $listado2->NomFich;
				$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$listado->Id."' AND `Idioma`='".$idiomas[$i]."'";
				$result2 = mysqli_query($db,$requete2);
				if (($result2) && (mysqli_num_rows($result2)>0))				
				{
					$listado2 = mysqli_fetch_object($result2);
					$rutaIdioma.="/".$listado2->NomFich;
					print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_seccion&seccion=".$listado2->Id."&ruta=".$rutaIdioma."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."</a>";
				}
				else
				{		
					print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nueva_seccion&seccion=".$listado2->Id."&ruta=".$rutaIdioma."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
				}
			}
		}	        
		if ($IdEntorno=="") 
		{	
			if ($listado->Plantilla!="") print "<li><a href=\"/Secciones/".$ruta."/".$listado->NomFich."\" target=\"_blank\"><i class=\"fa fa-eye\"></i> ".$lang["verSeccion"]."</a></li>";
		}
		else
		{
			$requete2 = "SELECT * FROM `Entornos` WHERE Id = ".$IdEntorno;
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))
			{
				$listado2 = mysqli_fetch_object($result2);
				if ($listado2->URLSeccion!="") print "<li><a href=\"".$listado2->Dominio.$listado2->URLSeccion."?Id=".$listado->Id."\" target=\"_blank\"><i class=\"fa fa-eye\"></i> ".$lang["verSeccion"]."</a></li>";
			}
		}		
		print "</ul></div></td>";
		print "<td>";
		$enruta=$ruta."/".$listado->NomFich;
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$enruta."\"><i class=\"fa fa-folder\"></i> ".$listado->Titulo."</a>";
		print "</td>";
		//Mostramos si hay contenidos pendientes de revisar
		if ($info!='')
		{			
			print "<td>";
			switch ($info)
			{
				case "numeroPendientes":
					$requete2 = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$listado->Id." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Visibilidad`='pendiente'";
					$result2 = mysqli_query($db,$requete2);
					if (($result2) && (mysqli_num_rows($result2)>0)) print " ".mysqli_num_rows($result2)." ".$lang["pendientes"];
					break;
				case "numeroXseccion":
					$requeteInfo = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$listado->Id." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido'";
					$resultInfo = mysql_query($requeteInfo,$db);
					$total_contenidos_seccion = mysqli_num_rows($resultInfo);
					print " <span style=\"background:#000000;color:#FFFFFF;\">".$total_contenidos_seccion."</span>";
					break;
				case "numeroSuscritos":
					$requeteInfo = "SELECT * FROM `ContenidosFavoritos` WHERE IdSeccion = ".$listado->Id." AND `TipoRelacion`<>'necesitan'";
					$resultInfo = mysqli_query($db,$requeteInfo);
					$total_contenidos_seccion = mysqli_num_rows($resultInfo);
					print " <span style=\"background:#000000;color:#FFFFFF;\">".$total_contenidos_seccion."</span>";
					break;
			}
			print "</td>";
		}
		print "</tr>";
	}
	print '</tbody></table>';
	print "</div></div>";
	print "</div></div></div>";
}

//$test=true;
/*
require("../Interface/cierre.php");
ordenaTotal("Publicaciones","IdSeccion",$seccion);
require("../Interface/conexion.php");
*/

//Consultamos el listado de Contenidos publicados en esta sección
switch ($orden_listado)
{
	case "alfabetico":
		if ($num_contenidos_pagina!=''&&$num_contenidos_pagina>0) $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Titulo` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') AND ORDER BY `Titulo`";
		break;
	case "clicks":
		if ($num_contenidos_pagina!=''&&$num_contenidos_pagina>0) $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') AND ORDER BY `Clicks` DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') AND ORDER BY `Clicks` DESC";
		break;
	case "vistas":
		if ($num_contenidos_pagina!=''&&$num_contenidos_pagina>0) $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Vistas` DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Vistas` DESC";
		break;
	case "modificacion":
		if ($num_contenidos_pagina!=''&&$num_contenidos_pagina>0) $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') AND ORDER BY `Contenidos`.FechaModificacion DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Vistas` DESC";
		break;
	default:		
		if ($num_contenidos_pagina!=''&&$num_contenidos_pagina>0) $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Orden` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND ( `Contenidos`.Tipo='contenido' OR `Contenidos`.Tipo='formulario' OR `Contenidos`.Tipo='codigo' OR `Contenidos`.Tipo='producto') ORDER BY `Orden`";		
		break;
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
		else print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a></li>";
	}	
	print "</ul>";
}
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['contenidos'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row">';	

	print '<table class="table table-striped"><tbody>';
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		if (($herramientas[46]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[46]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
		{
			if ($_SESSION['usuario_nivel']<=3)
			{ //NIVEL MÍNIMO RESPONSABLE
				print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=editar_contenido&seccion=".$seccion."&contenido=".$listado->IdContenido."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-edit\"></i> ".$textos[46]."</a></li>";
			}
		}
		switch ($listado->Visibilidad) 
		{
			case "visible":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=oculto&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><i class=\"fa fa-pause\"></i> ".$lang["desactivar"]."</a></li>";
			break;
			case "oculto":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><i class=\"fa fa-play\"></i> ".$lang["activar"]."</a></li>";
			break;
			case "pendiente":
				//PERMISOS NECESARIOS PARA VALIDAR CONTENIDO PENDIENTE
				if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/pendientes.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"> ".$lang["pendiente"]."</a></li>";
			break;
		}
		$orden=$listado->Orden;
		//PERMISOS NECESARIOS PARA ORDENAR DE RESPONSABLE
		if (($nivel<4)&&(($orden_listado=="")||($orden_listado=="alfabetico"))) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_arriba.png\" alt=\"".$lang["toparriba"]."\" title=\"".$lang["toparriba"]."\"> ".$lang["toparriba"]."</a></li>";
		if (($nivel<4)&&($orden_listado=="")) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"> ".$lang["arriba"]."</a></li>";
		if (($nivel<4)&&($orden_listado=="")) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"> ".$lang["abajo"]."</a></li>";
		if (($nivel<4)&&(($orden_listado=="")||($orden_listado=="alfabetico"))) print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topabajo"]."\" title=\"".$lang["topabajo"]."\"> ".$lang["topabajo"]."</a></li>";
		switch ($listado->Tipo) 
		{
			case "contenido":
				//Idiomas de los contenidos
				for ($i=1;($i<=count($idiomas));$i++)
				{
					//Si el apartado padre no está en el idioma no permitimos añadir el idioma a los contenidos
					$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
					$result2 = mysqli_query($db,$requete2);
					if (($result2) && (mysqli_num_rows($result2)>0))					
					{
						$listado2 = mysqli_fetch_object($result2);
						if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
						else $rutaIdioma = $listado2->NomFich;
						$IdSeccionIdioma = $listado2->Id;
						$requete2 = "SELECT * FROM `Contenidos` WHERE `RelacionIdioma` = '".$listado->IdContenido."' AND `Idioma`='".$idiomas[$i]."'";
						$result2 = mysqli_query($db,$requete2);
						if (($result2) && (mysqli_num_rows($result2)>0))						
						{
							$listado2 = mysqli_fetch_object($result2);							
							print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_contenido&contenido=".$listado2->Id."&ruta=".$rutaIdioma."&seccion=".$IdSeccionIdioma."&tipocontenido=".$listado2->IdTipoContenido."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
						}
						else
						{		
							print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nuevo_contenido&seccion=".$IdSeccionIdioma."&ruta=".$rutaIdioma."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
						}
					}
				}
				// Permitimos ver su ficha	
				//PERMISOS MÍNIMOS PARA EDITAR UN CONTENIDO COORDINADOR O PROPIETARIO DE CONTENIDO
				if (($nivel<3) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
				{		
					if ($listado->Plantilla!="Contenido desplegado")
					{//Permitimos visualizar si el contenido no tiene la plantilla desplegado. Si tenemos un entorno tenemos que poner la ruta al entorno
						If ($IdEntorno=="") print "<li><a href=\"/Secciones/".$ruta."/".$listado->NomFich.".php\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["verContenido"]."\" alt=\"".$lang["verContenido"]."\"> ".$lang["verContenido"]."</a></li>";
						else
						{
							$requete2 = "SELECT * FROM `Entornos` WHERE Id = ".$IdEntorno;
							$result2 = mysqli_query($db,$requete2);
							if (($result2) && (mysqli_num_rows($result2)>0))							
							{
								$listado2 = mysqli_fetch_object($result2);
								if ($listado2->URLContenido!="") print "<li><a href=\"".$listado2->Dominio.$listado2->URLContenido."?Id=".$listado->IdContenido."\" target=\"_blank\"><i class=\"fa fa-eye\"></i> ".$lang["verContenido"]."</a></li>";
							}
						}
					}					
					print "</ul></div></td>";
					print "<td>";
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><i class=\"fa fa-file\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					print $listado->Titulo;					
					if ($listado->Visibilidad=='oculto') print '</span>';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '</span>';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '</span>';
					}
					print "</a>";
				}
				else 
				{
					print "</ul></div></td>";
					print "<td><i class=\"fa fa-file\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					print $listado->Titulo;
					if ($listado->Visibilidad=='oculto') print '</span>';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '</span>';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '</span>';
					}
				}
				print '</td>';
			break;
			case "producto":
				//Idiomas de los contenidos
				for ($i=1;($i<=count($idiomas));$i++)
				{
					//Si el apartado padre no está en el idioma no permitimos añadir el idioma a los contenidos
					$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
					$result2 = mysqli_query($db,$requete2);
					if (($result2) && (mysqli_num_rows($result2)>0))					
					{
						$listado2 = mysqli_fetch_object($result2);
						if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
						else $rutaIdioma = $listado2->NomFich;
						$IdSeccionIdioma = $listado2->Id;
						$requete2 = "SELECT * FROM `Contenidos` WHERE `RelacionIdioma` = '".$listado->IdContenido."' AND `Idioma`='".$idiomas[$i]."'";
						$result2 = mysqli_query($db,$requete2);
						if (($result2) && (mysqli_num_rows($result2)>0))						
						{
							$listado2 = mysqli_fetch_object($result2);							
							print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_producto&contenido=".$listado2->Id."&ruta=".$rutaIdioma."&seccion=".$IdSeccionIdioma."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"></a></li>";
						}
						else
						{		
							print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nuevo_producto&seccion=".$IdSeccionIdioma."&ruta=".$rutaIdioma."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"></a></li>";
						}
					}
				}	
				// Permitimos ver su ficha	
				//PERMISOS MÍNIMOS PARA EDITAR UN CONTENIDO COORDINADOR O PROPIETARIO DE CONTENIDO
				print "<ul></div></td>";
				print "<td>";
				if (($nivel<3) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
				{		
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_producto&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-shopping-cart\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					print " ".$listado->Titulo." <strong>".number_format(($listado->PrecioBI+($listado->PrecioBI*$listado->PrecioTAX/100)),2,',','.')." &euro;</strong>";
					if ($listado->Visibilidad=='oculto') print '</span>';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '</span>';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '</span>';
					}
					print "</a>";
				}
				else print "<i class=\"fa fa-shopping-cart\"></i> ".$listado->Titulo." <strong>".number_format(($listado->PrecioBI+($listado->PrecioBI*$listado->PrecioTAX/100)),2,',','.')." &euro;</strong>";
				print '</td>';
			break;
			case "codigo":
				print "<ul></div></td>";
				print "<td>";
				print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_codigo&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><i class=\"fa fa-code\"></i> ";
				if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
				print $listado->Titulo;
				if ($listado->Visibilidad=='oculto') print '</span>';
				print "</a>";
				print "</td>";
			break;
			case "formulario":
				print "<ul></div></td>";
				print "<td>";
				print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_formulario&contenido=".$listado->IdContenido."&ruta=".$rutaIdioma."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/formularios.png\" title=\"".$lang["formulario"]."\" alt=\"".$lang["formulario"]."\">";
				if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
				print $listado->Titulo;
				if ($listado->Visibilidad=='oculto') print '</span>';				
				print "</a>";
				print "</td>";
			break;
		}
		if (($orden_listado=="clicks")||($orden_listado=="vistas")) print "<td><img src=\"/administra/Imagenes/estadisticas.png\"/> ".$lang["vistas"].": ".$listado->Vistas."- ".$lang["clicks"].": ".$listado->Clicks."</td>";
		if ($orden_listado=="modificacion")
		{
			if ($listado->FechaComienzo!="") print "<td><img src=\"/administra/Imagenes/clock.png\" alt=\"".$lang["fechaCreacion"]."\" title=\"".$lang["fechaCreacion"]."\"/> ".cambiaf_a_normal($listado->FechaComienzo)."</td>";
			if ($listado->FechaModificacion!="") print "<td><img src=\"/administra/Imagenes/clock_edit.png\" alt=\"".$lang["fechaUltimaModificacion"]."\" title=\"".$lang["fechaUltimaModificacion"]."\"/> ".cambiaf_a_normal($listado->FechaModificacion)."</td>";
		}
		print "</tr>";
	}
	print '</tbody></table>';	
	print "</div></div>";
	print "</div></div></div>";
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
		else print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a></li>";
	}	
	print "</ul>";
}
//ELEMENTOS ESPECIALES
$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND (`Contenidos`.Tipo=='encuesta'||`Contenidos`.Tipo=='calendario'||`Contenidos`.Tipo=='webcam'||`Contenidos`.Tipo=='rss') AND `Contenidos`.Idioma='".$idiomas[0]."'";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	print '<div class="row><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['elementos_especiales'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row">';	

	print '<table class="table table-striped"><tbody>';

	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		
		if ($listado->Visibilidad=="visible")
		{
			//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
			if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=oculto&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/pausa.png\" title=\"".$lang["desactivar"]."\" alt=\"".$lang["desactivar"]."\"> ".$lang["desactivar"]."</a></li>";
		}
		else
		{
			//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
			if ($nivel<4) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/play.png\" title=\"".$lang["activar"]."\" alt=\"".$lang["activar"]."\"> ".$lang["activar"]."</a></li>";
		}
		print "</ul></div></td>";
		print "<td>";
		if (($nivel<3) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
		{
			switch ($listado->Tipo) 
			{
				case "encuesta":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=encuestas&encuesta=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/seccion_encuesta.png\" alt=\"".$lang["encuesta"]."\" title=\"".$lang["encuesta"]."\">";
					break;
				case "calendario":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_calendario&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/calendario.png\" title=\"".$lang["calendario"]."\" alt=\"".$lang["calendario"]."\">";
				break;
				case "webcam":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_webcam&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/webcam.png\" title=\"".$lang["webcam"]."\" alt=\"".$lang["webcam"]."\">";
				break;	    
				case "rss":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_rss&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/feed.png\" title=\"".$lang["rss"]."\" alt=\"".$lang["rss"]."\">";
				break;
			} 
			if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
			print $listado->Titulo;
			if ($listado->Visibilidad=='oculto') print '</span>';
			print "</a>";
		}
		else 
		{
			if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
			print $listado->Titulo;
			if ($listado->Visibilidad=='oculto') print '</span>';
		}
		print "</td>";
		print "</tr>";
	}
	print '</tbody></table>';	
	print "</div></div>";
	print "</div></div></div>";
}
print "</div>"; //wrapper wrapper-content
if ($result) 
{
	mysqli_free_result($result);
}
?>
