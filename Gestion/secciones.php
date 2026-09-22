<?php
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/secciones-".$_SESSION['idioma'].".conf");
//Miramos los idiomas activados en la parte externa
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
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

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$num_contenidos_pagina = $listado->Valor;
	if (!isset($pagina)) $pagina = 0;
	$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido'";
	
	$total_contenidos_pagina = mysqli_num_rows($result);		
}
// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion." ORDER BY `Orden`";

$listado = mysqli_fetch_object($result);

//Si no es administrador comprobamos que se tengan permisos
if ($_SESSION['usuario_nivel'] > 1)
{// COMPROBACIÓN PERMISOS
	// Tratamos primer elemento. Si tenemos permiso sobre el elemento actual todo va bien	---USUARIO---
	$requete2 = "SELECT * FROM `Permisos` WHERE `IdSeccion`=".$seccion." AND `Nivel`<'5' AND `IdUsuarioSuscrito`='".$_SESSION['usuario_id']."'";
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado2 = mysqli_fetch_object($result2);
		$nivel = $listado2->Nivel;
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
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				$nivel = $listado2->Nivel;
			}
		}
	}
	if (!$nivel)
	{
		die ($lang["errorPermisos"]);
		exit;
	}
}
else $nivel=1;

print "<h1><img src=\"/administra/Imagenes/secciones.png\"> ".$lang["estaUsted"]." <em>".$listado->Titulo."</em></h1>";
//--------------------------------
// Mostramos la ruta
print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz\">".$lang["raiz"]."</a> / ";
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion;

$listado = mysqli_fetch_object($result);
$temp_NomFich = $listado->NomFich;
$IdEntorno = $listado->IdEntorno;
$cabecera = explode("/",$listado->Path);
$temp = "";
for ($i=0;$i<count($cabecera);$i++)
{
	if ($temp!="") {$temp = $temp."/".$cabecera[$i];}
	else {$temp = $cabecera[$i];}
	$requete = "SELECT * FROM `Secciones` WHERE `NomFich`='".$cabecera[$i]."'";
	
	$listado = mysqli_fetch_object($result);
	print " / ";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$temp."\">".$cabecera[$i]."</a>";
}
print " / <a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\">".$temp_NomFich."</a>";
//--------------------------------
// Mostramos la ruta
//print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz\">Raiz</a> / ";

//$cabecera = explode("/",$listado->Path);
//if ($cabecera[0]=="") $cabecera[0]=$cabecera[1];
//$temp = "";

//$requete = "SELECT * FROM `Secciones` WHERE `Id`='".$seccion."'";
//
//$listado = mysqli_fetch_object($result);
//$i=0;
//$rutacabecera = array();
//Consulto el padre
//$requete = "SELECT `Id`,`IdPadre` FROM `Secciones` WHERE `Id`='".$listado->IdPadre."'";
//
//while ($listado = mysqli_fetch_object($result))
//{
// $rutacabecera[$i]=$listado->Id;
// $requete = "SELECT `Id`,`IdPadre` FROM `Secciones` WHERE `Id`='".$listado->IdPadre."'";
//  
// $i++;
//}
//$i--;
//for ($j=0;$i>=0;$i--)
//{
// if ($temp!="") {$temp = $temp."/".$cabecera[$j];}
// else {$temp = $cabecera[$j];}
// print " / ";
// print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$rutacabecera[$i]."&ruta=".$temp."\">".$cabecera[$j]."</a>";
// $j++;
//}

// Herramientas superiores
print "<div id=\"botonera_cabecera\"><ul>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\">".$lang["ordenNormal"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=alfabetico\">".$lang["ordenAlfabetico"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=clicks\">".$lang["ordenClicks"]."</li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=vistas\">".$lang["ordenVistas"]."</li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=modificacion\">".$lang["ordenModificacion"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroXseccion\">".$lang["contenidosPor"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroPendientes\">".$lang["pendiente"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&info=numeroSuscritos\">".$lang["numeroSuscritos"]."</a></li>";
print "</ul></div>";
// Hacemos una consulta para ver cuantas secciones hay en esta ubicación
$requete = "SELECT `Id`,`Orden`,`NomFich`,`Titulo`, `Visibilidad`, `IdEntorno`, `NomFich`,`Plantilla` FROM `Secciones` WHERE `IdPadre`=".$seccion." AND `Idioma`='".$idiomas[0]."' ORDER BY `Orden`";

// Listamos las secciones existentes
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{	
		print "<li>";
		switch ($listado->Visibilidad)
		{
			case "visible":
				//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
				if ($nivel<3) print "<a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=oculto&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/pausa.png\" title=\"".$lang["desactivar"]."\" alt=\"".$lang["desactivar"]."\"></a>";
				break;
			case "oculto":
				//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
				if ($nivel<3) print "<a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=visible&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/play.png\" title=\"".$lang["activar"]."\" alt=\"".$lang["activar"]."\"></a>";
			break;
			case "privado":
			//PERMISOS NECESARIOS PARA QUITAR DE PRIVADO MÍNIMO ADMINISTRADOR
				if ($nivel<2) print "<a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=visible&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/privado.png\" title=\"".$lang["privado"]."\" alt=\"".$lang["privado"]."\"></a>";
			break;
		}
		$orden=$listado->Orden;
		//PERMISOS NECESARIOS PARA ORDENAR DE RESPONSABLE
		if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_arriba.png\" alt=\"".$lang["toparriba"]."\" title=\"".$lang["toparriba"]."\"></a>";
		if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"></a>";
		if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"></a>";
		if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=".$seccion."&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topabajo"]."\" title=\"".$lang["topabajo"]."\"></a>";
		for ($i=1;($i<=count($idiomas));$i++)
		{
			//Si el padre no está en el idioma no permitimos añadir el idioma a los hijos		
			$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
				else $rutaIdioma = $listado2->NomFich;
				$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$listado->Id."' AND `Idioma`='".$idiomas[$i]."'";
				
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);
					$rutaIdioma.="/".$listado2->NomFich;
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_seccion&seccion=".$listado2->Id."&ruta=".$rutaIdioma."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"></a>";
				}
				else
				{		
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nueva_seccion&seccion=".$listado2->Id."&ruta=".$rutaIdioma."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"></a>";
				}
			}
		}	        
		If ($IdEntorno=="") 
		{	
			if ($listado->Plantilla!="") print "<a href=\"/Secciones/".$ruta."/".$listado->NomFich."\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["verSeccion"]."\" alt=\"".$lang["verSeccion"]."\"></a> ";
		}
		else
		{
			$requete2 = "SELECT * FROM `Entornos` WHERE Id = ".$IdEntorno;
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				if ($listado2->URLSeccion!="") print "<a href=\"".$listado2->Dominio.$listado2->URLSeccion."?Id=".$listado->Id."\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["verSeccion"]."\" alt=\"".$lang["verSeccion"]."\"></a> ";
			}
		}
		print "<img src=\"/administra/Imagenes/secciones.png\">";
		$enruta=$ruta."/".$listado->NomFich;
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$enruta."\">".$listado->Titulo."</a>";
		//Mostramos si hay contenidos pendientes de revisar
		switch ($info)
		{
			case "numeroPendientes":
				$requete2 = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$listado->Id." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Visibilidad`='pendiente'";
				
				if ($result2 = mysqli_query($db, $requete2)) print " ".mysqli_num_rows($result2)." ".$lang["pendientes"];
				break;
			case "numeroXseccion":
				$requeteInfo = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$listado->Id." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido'";
				$resultInfo = mysql_query($requeteInfo,$db);
				$total_contenidos_seccion = mysqli_num_rows($resultInfo);
				print " <span style=\"background:#000000;color:#FFFFFF;\">".$total_contenidos_seccion."</span>";
				break;
			case "numeroSuscritos":
				$requeteInfo = "SELECT * FROM `ContenidosFavoritos` WHERE IdSeccion = ".$listado->Id." AND `TipoRelacion`<>'necesitan'";
				$resultInfo = mysql_query($requeteInfo,$db);
				$total_contenidos_seccion = mysqli_num_rows($resultInfo);
				print " <span style=\"background:#000000;color:#FFFFFF;\">".$total_contenidos_seccion."</span>";
				break;
		}
		print "</li>";
	}
}
print "</ul>";

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
		if (isset($num_contenidos_pagina)) $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma = '".$idiomas[0]."' ORDER BY `Titulo` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Titulo`";
		break;
	case "clicks":
		if (isset($num_contenidos_pagina)) $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Clicks` DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Clicks` DESC";
		break;
	case "vistas":
		if (isset($num_contenidos_pagina)) $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Vistas` DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Vistas` DESC";
		break;
	case "modificacion":
		if (isset($num_contenidos_pagina)) $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Contenidos`.FechaModificacion DESC LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Vistas` DESC";
		break;
	default:
		if (isset($num_contenidos_pagina)) $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Orden` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
		else $requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido' AND `Contenidos`.Idioma='".$idiomas[0]."' ORDER BY `Orden`";
		break;
}

if ($result = mysqli_query($db, $requete))
{
	print "<hr>";
	if (isset($num_contenidos_pagina))
	{
	//PAGINACIÓN
		print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
		print "<p>";
		$max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
		for ($i=0;($i<($max_pagina + 1));$i++)
		{
			if ($pagina == $i) print "<strong>".$i."</strong> - ";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
		}	
		print "</p>";
	}
	print "<ul>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		switch ($listado->Visibilidad) 
		{
			case "visible":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=oculto&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/pausa.png\" title=\"".$lang["desactivar"]."\" alt=\"".$lang["desactivar"]."\"></a>";
			break;
			case "oculto":
				//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
				if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/play.png\" title=\"".$lang["activar"]."\" alt=\"".$lang["activar"]."\"></a>";
			break;
			case "pendiente":
				//PERMISOS NECESARIOS PARA VALIDAR CONTENIDO PENDIENTE
				if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/pendientes.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"></a>";
			break;
		}
		$orden=$listado->Orden;
		//PERMISOS NECESARIOS PARA ORDENAR DE RESPONSABLE
		if (($nivel<4)&&(($orden_listado=="")||($orden_listado=="alfabetico"))) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_arriba.png\" alt=\"".$lang["toparriba"]."\" title=\"".$lang["toparriba"]."\"></a>";
		if (($nivel<4)&&($orden_listado=="")) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"></a>";
		if (($nivel<4)&&($orden_listado=="")) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"></a>";
		if (($nivel<4)&&(($orden_listado=="")||($orden_listado=="alfabetico"))) print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdSeccion&iid=$seccion&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topabajo"]."\" title=\"".$lang["topabajo"]."\"></a>";
		//Idiomas de los contenidos
		for ($i=1;($i<=count($idiomas));$i++)
		{
			//Si el apartado padre no está en el idioma no permitimos añadir el idioma a los contenidos
			$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$seccion."' AND `Idioma`='".$idiomas[$i]."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				if ($listado2->Path!="") $rutaIdioma = $listado2->Path."/".$listado2->NomFich;
				else $rutaIdioma = $listado2->NomFich;
				$IdSeccionIdioma = $listado2->Id;
				$requete2 = "SELECT * FROM `Contenidos` WHERE `RelacionIdioma` = '".$listado->IdContenido."' AND `Idioma`='".$idiomas[$i]."'";
								
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);							
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_contenido&contenido=".$listado2->Id."&ruta=".$rutaIdioma."&seccion=".$IdSeccionIdioma."&tipocontenido=".$listado2->IdTipoContenido."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"></a> ";
				}
				else
				{		
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nuevo_contenido&seccion=".$IdSeccionIdioma."&ruta=".$rutaIdioma."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&rutaOrigen=".$ruta."&seccionOrigen=".$seccion."\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"></a> ";				
				}
			}
		}	
		// Permitimos ver su ficha	
		//PERMISOS MÍNIMOS PARA EDITAR UN CONTENIDO COORDINADOR O PROPIETARIO DE CONTENIDO
		if (($nivel<3) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
		{		
			if ($listado->Plantilla!="Contenido desplegado")
			{//Permitimos visualizar si el contenido no tiene la plantilla desplegado. Si tenemos un entorno tenemos que poner la ruta al entorno
				If ($IdEntorno=="") print "<a href=\"/Secciones/".$ruta."/".$listado->NomFich.".php\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["verContenido"]."\" alt=\"".$lang["verContenido"]."\"></a>";
				else
				{
					$requete2 = "SELECT * FROM `Entornos` WHERE Id = ".$IdEntorno;
					
					if ($result2 = mysqli_query($db, $requete2))
					{
						$listado2 = mysqli_fetch_object($result2);
						if ($listado2->URLContenido!="") print "<a href=\"".$listado2->Dominio.$listado2->URLContenido."?Id=".$listado->IdContenido."\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["verContenido"]."\" alt=\"".$lang["verContenido"]."\"></a>";
					}
				}
			}
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/contenido.png\">";
			print $listado->Titulo;
			print "</a>";
		}
		else print $listado->Titulo;
		if (($orden_listado=="clicks")||($orden_listado=="vistas")) print " - <img src=\"/administra/Imagenes/estadisticas.png\"/> ".$lang["vistas"].": ".$listado->Vistas."- ".$lang["clicks"].": ".$listado->Clicks;
		if ($orden_listado=="modificacion")
		{
			if ($listado->FechaComienzo!="") print " - <img src=\"/administra/Imagenes/clock.png\" alt=\"".$lang["fechaCreacion"]."\" title=\"".$lang["fechaCreacion"]."\"/> ".cambiaf_a_normal($listado->FechaComienzo);
			if ($listado->FechaModificacion!="") print " - <img src=\"/administra/Imagenes/clock_edit.png\" alt=\"".$lang["fechaUltimaModificacion"]."\" title=\"".$lang["fechaUltimaModificacion"]."\"/> ".cambiaf_a_normal($listado->FechaModificacion);
		}
		print "</li>";
	}
	print "</ul>";
}
if (isset($num_contenidos_pagina))
{
	//PAGINACIÓN
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<p>";
	$max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
	for ($i=0;($i<($max_pagina + 1));$i++)
	{
		if ($pagina == $i) print "<strong>".$i."</strong> - ";
		else print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
	}	
	print "</p>";
}
//ELEMENTOS ESPECIALES
$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo<>'contenido' AND `Contenidos`.Idioma='".$idiomas[0]."'";

if ($result = mysqli_query($db, $requete))
{
	print "<hr><ul>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		if ($listado->Visibilidad=="visible")
		{
			//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
			if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=oculto&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/pausa.png\" title=\"".$lang["desactivar"]."\" alt=\"".$lang["desactivar"]."\"></a>";
		}
		else
		{
			//PERMISOS NECESARIOS PARA PLAY/PAUSA DE RESPONSABLE
			if ($nivel<4) print "<a href=\"/administra/Carpetas/funciones/visibilidad_contenido.php?pasada=visible&Id=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."\"><img src=\"/administra/Imagenes/play.png\" title=\"".$lang["activar"]."\" alt=\"".$lang["activar"]."\"></a>";
		}
		if (($nivel<3) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
		{
			switch ($listado->Tipo) 
			{
				case "encuesta":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=encuestas&encuesta=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/seccion_encuesta.png\" alt=\"".$lang["encuesta"]."\" title=\"".$lang["encuesta"]."\">";
					break;
				case "codigo":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_codigo&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/codigo.png\" title=\"".$lang["codigo"]."\" alt=\"".$lang["codigo"]."\">";
				break;
				case "formulario":
					print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_formulario&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/formularios.png\" title=\"".$lang["formulario"]."\" alt=\"".$lang["formulario"]."\">";
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
			print $listado->Titulo;
			print "</a>";
		}
		else print $listado->Titulo;
		print "</li>";
	}
	print "</ul>";
}
if ($result) 
{
	mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
