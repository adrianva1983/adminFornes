<?php
//VERSIÓN: v1.1 2013-11-13
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$contenido = $_GET["contenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$tipocontenido = $_GET["tipocontenido"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/contenidos-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO SUPERUSUARIO
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
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
// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);
print '
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <h2>'.$lang["estaUsted"].' <em>'.$listado->Titulo.'</em></h2>
					<div class="row">
					<div class="col-lg-12">
                    <ol class="breadcrumb"><li><a href="/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz">'.$lang["raiz"].'</a></li>
                    </ol>
					</div>					
					</div>
				</div>
				</div>';

// Hacemos una consulta para ver cuantas ampliaciones de contenido tiene el contenido actual
$requete = "SELECT *,`Publicaciones`.FechaComienzo AS FechaC,`Publicaciones`.FechaFin AS FechaF FROM `Publicaciones`, `Contenidos` WHERE IdPadre = ".$contenido." AND IdContenido = `Contenidos`.Id ORDER BY `Orden`";
$result = mysqli_query($db,$requete);
// Listamos las ampliaciones de contenido existentes
if (($result) && (mysqli_num_rows($result)>0))
{
	print '<div class="row wrapper wrapper-content"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['ampliaciones'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row">';
	print '<table class="table table-striped"><tbody>';
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";	
		print "<td>";
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		$orden=$listado->Orden;
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Carpetas/funciones/borrar_ampliacion.php?ruta=".$ruta."&seccion=".$seccion."&contenido=".$listado->IdContenido."&antiguo=".$contenido."\"><i class=\"fa fa-remove\"></i> ".$lang["borrar"]."</a></li>";
		}
		switch ($listado->Visibilidad) 
		{
			case "visible":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=oculto&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&contenido=".$contenido."&tipocontenido=".$tipocontenido."&anticache=".time()."\"><i class=\"fa fa-pause\"></i> ".$lang["desactivar"]."</a></li>";
				break;
			case "oculto":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&contenido=".$contenido."&tipocontenido=".$tipocontenido."&anticache=".time()."\"><i class=\"fa fa-play\"></i> ".$lang["activar"]."</a></li>";
				break;
			case "pendiente":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&contenido=".$contenido."&tipocontenido=".$tipocontenido."&anticache=".time()."\"> ".$lang["pendientes"]."</a></li>";
				break;
		}
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si&anticache=".time()."\"><img src=\"/administra/Imagenes/top_arriba.png\" title=\"".$lang["topArriba"]."\" alt=\"".$lang["topArriba"]."\"> ".$lang["topArriba"]."</a></li>";
		}
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si&anticache=".time()."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"> ".$lang["arriba"]."</a></li>";
		}
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si&anticache=".time()."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"> ".$lang["abajo"]."</a></li>";
		}
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si&anticache=".time()."\"><img src=\"/administra/Imagenes/top_abajo.png\" title=\"".$lang["topAbajo"]."\" alt=\"".$lang["topAbajo"]."\"> ".$lang["topAbajo"]."</a></li>";
		}
		//Idiomas de las ampliaciones
		for ($i=1;($i<=count($idiomas));$i++)
		{
			//Si el contenido padre no está en el idioma no permitimos añadir el idioma a las ampliaciones
			$requete2 = "SELECT * FROM `Contenidos` WHERE `RelacionIdioma` = '".$contenido."' AND `Idioma`='".$idiomas[$i]."'";
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))						
			{
				$listado2 = mysqli_fetch_object($result2);
				$IdContenidoIdioma = $listado2->Id;
				$requete2 = "SELECT * FROM `Contenidos` WHERE `RelacionIdioma` = '".$listado->IdContenido."' AND `Idioma`='".$idiomas[$i]."'";
				$result2 = mysqli_query($db,$requete2);
				if (($result2) && (mysqli_num_rows($result2)>0))				
				{
					$listado2 = mysqli_fetch_object($result2);				
					print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&contenido=".$contenido."&ruta=".$ruta."&seccion=".$seccion."&idampliacion=".$listado2->Id."&ampliacion=".$listado2->Tipo."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
				}
				else
				{				
					print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&seccion=".$seccion."&ruta=".$ruta."&contenidoOrigen=".$contenido."&contenido=".$listado2->Id."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&ampliacion=".$listado->Tipo."&tipocontenido=".$tipocontenido."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
				}
			}
		}
		print "</ul></div></td>";
		print "<td>";
		switch ($listado->Tipo) 
		{
			case "texto":
				// AMPLIACIÓN DE TIPO TEXTO				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3)
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=texto\"><i class=\"fa fa-file-text-o\"></i> ";					
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaComienzo!='0000-00-00 00:00:00'&&$listado->FechaComienzo>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaFin!='0000-00-00 00:00:00'&&$listado->FechaFin<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					if ($listado->Titulo!="") print $listado->Titulo;
					else print $lang["textoNoTitulo"];
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
					print "<i class=\"fa fa-file-text-o\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';
					else 
					{
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}						
					if ($listado->Titulo!="") print $listado->Titulo;
					else print $lang["textoNoTitulo"];
					if ($listado->Visibilidad=='oculto') print '</span>';
					else 
					{						
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '</span>';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '</span">';
					}
				}
				//FIN NIVEL
				break;
			case "imagen":
				// AMPLIACIÓN DE TIPO FOTO				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) 
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=imagen\"><i class=\"fa fa-file-image-o\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';
					else 
					{						
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					if ($listado->Titulo!="") print $listado->Titulo;
					else print $lang["imagenNoTitulo"];
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
					print "<i class=\"fa fa-file-text-o\"></i> ";
					if ($listado->Visibilidad=='oculto') print '<span class="desactivado">';			
					else 
					{						
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '<span class="desactivado">';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '<span class="desactivado">';
					}
					if ($listado->Titulo!="") print $listado->Titulo;
					else print $lang["imagenNoTitulo"];
					if ($listado->Visibilidad=='oculto') print '</span>';
					else 
					{						
						if ($listado->FechaC!=''&&$listado->FechaC!='0000-00-00 00:00:00'&&$listado->FechaC>date('Y-m-d H:i:s')) print '</span>';
						else if ($listado->FechaF!=''&&$listado->FechaF!='0000-00-00 00:00:00'&&$listado->FechaF<date('Y-m-d H:i:s')) print '</span>';
					}
				}
				//FIN NIVEL
				break;
			case "video":
				// AMPLIACIÓN DE TIPO VIDEO				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) 				
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=video\"><i class=\"fa fa-file-video-o\"></i> ";
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
					print "<i class=\"fa fa-file-video-o\"></i> ";
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
				//FIN NIVEL
			break;
			case "fichero":
				// AMPLIACIÓN DE TIPO FICHERO
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3)
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=fichero\"><i class=\"fa fa-file-o\"></i> ";
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
					print "<i class=\"fa fa-file-o\"></i> ";
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
				//FIN NIVEL
			break;
			case "enlace":
				// AMPLIACIÓN DE TIPO ENLACE				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) 
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=enlace\"><i class=\"fa fa-link\"></i> ";
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
					print "<i class=\"fa fa-link\"></i> ";
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
				//FIN NIVEL
			break;
			case "mapa":
				// AMPLIACIÓN DE TIPO MAPA				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3)
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=mapa\"><i class=\"fa fa-map-marker\"></i> ";					
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
					print "<i class=\"fa fa-map-marker\"></i> ";
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
				//FIN NIVEL
			break;
			case "mapa2":
				// AMPLIACIÓN DE TIPO MAPA NO VERIFICADO				
				"<i class=\"fa fa-map-marker\"></i> ";
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3) 
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=mapa\">";					
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
					print "<i class=\"fa fa-map-marker\"></i> ";
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
				//FIN NIVEL
			break;
			case "ruta":
				// AMPLIACIÓN DE TIPO MAPA				
				//NIVEL MÍNIMO RESPONSABLE
				if ($_SESSION['usuario_nivel']<=3)
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=ruta\">";
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
				//FIN NIVEL
			break;
			case "foro":
				// AMPLIACIÓN DE TIPO FORO				
				if ($_SESSION['usuario_nivel']<=3)
				{ //NIVEL MÍNIMO RESPONSABLE
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=foro\"><i class=\"fa fa-comments-o\"></i> ".$listado->Titulo."</a>";
					//Pongo los mensajes
					$requete2 = "SELECT * FROM `ForoMensajes` WHERE `IdForo`='".$listado->Id."'";
					$result2 = mysqli_query($db,$requete2);
					if (($result2) && (mysqli_num_rows($result2)>0)) print " -- <a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=foro_editar_mensajes&seccion=".$seccion."&ruta=".$ruta."&IdForo=".$listado->Id."\">".mysqli_num_rows($result2)." ".$lang["mensajes"]." <img src=\"/administra/Imagenes/editar_mensajes_foro.png\" title=\"".$lang["editarMensajes"]."\" alt=\"".$lang["editarMensajes"]."\"></a>";
				}
				else print "<i class=\"fa fa-comments-o\"></i> ".$listado->Titulo;
				//FIN NIVEL
			break;
			case "bloque":
				// AMPLIACIÓN DE TIPO MAPA				
				//NIVEL MÍNIMO ADMINISTRADOR
				if ($_SESSION['usuario_nivel']<=1)
				{
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_ampliacion&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."&ampliacion=bloque\"><i class=\"fa fa-magic\"></i> ";
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
					print "<i class=\"fa fa-magic\"></i> ";	
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
				//FIN NIVEL
			break;  
			default:
				$requete2 = "SELECT * FROM `HerramientasPersonalizadas` WHERE `Activado`='si' AND `NombreGrupoNopersonalizada` = 'contenidos|".$listado->Tipo."'";
				$result2 = mysqli_query($db,$requete2);
				if (($result2) && (mysqli_num_rows($result2)>0))				
				{
					$listado2 = mysqli_fetch_object($result2);
					print "<img src=\"http://".$_SERVER['SERVER_NAME'].$listado2->URLIcono."\">";
					$tmp = explode(".",$listado2->FicheroPHP);
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Especifico&herramienta=".$tmp[0]."&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&idampliacion=".$listado->Id."\">";
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
			break;
		}
		print "</td></tr>";
	}
	print '</tbody></table>';
	print "</div></div>";
	print "</div></div></div>";
}
//CAMPOS ADICIONALES
//------------------
//Comprobamos si hay CAMPOS ADICIONALES
$requete = "SELECT * FROM CamposAdicionales WHERE IdContenido=".$contenido.";";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))			
{
	print '<div class="row wrapper wrapper-content"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['ampliaciones'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row">';
	print '<table class="table table-striped"><tbody>';
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE		
		print "<tr><td><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_campos_adicionales&seccion=".$seccion."&ruta=".$ruta."&contenido=".$contenido."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-check-square-o\"></i> ".$lang["editarCampos"]."</a></td></tr>";
	} //FIN NIVEL
	print '</tbody></table>';
	print "</div></div>";
	print "</div></div></div>";
}
//CAMPOS ADICIONALES FIN
//----------------------
if ($result) 
{
	mysqli_free_result($result);
}
?>