<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

if ($herramienta=="arbol") {exit;}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/acciones-".$_SESSION['idioma'].".conf");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='20'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$textos[$listado->IdHerramienta]=$listado->Nombre;
	}
}
//Sacamos las herramientas de los grupos a los que pertenezca
$herramientas = array();
$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdUsuario` = '".$_SESSION['usuario_id']."'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `UsuariosHerramientas`.Activo='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='20'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			while($listado2 = mysqli_fetch_object($result2))
			{
				$herramientas[$listado2->IdHerramienta]="si";
			}
		}
	}
}
//Sacamos las herramientas que tiene disponibles el usuario
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `UsuariosHerramientas`.Activo='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='20'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$herramientas[$listado->IdHerramienta]="si";
	}
}
switch ($herramienta) {
   case "raiz":
	// ACCIONES CON ESTADISTICAS
	// -------------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[87]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[87]."</a></li>";
		}
	}
	if (($herramientas[88]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[88]."</a></li>";
		}
	}
	if (($herramientas[89]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[89]."</a></li>";
		}
	}
	if (($herramientas[90]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[90]."</a></li>";
		}
	}
	print "</ul>";
	// HERRAMIENTAS GENERALES
	print "<h1>".$lang["herramientas"]."</h1>";
	print "<ul>";
	if (($herramientas[91]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Estadisticas&herramienta=estadisticas_globales\"><img src=\"/administra/Imagenes/campos_adicionales.png\"> ".$textos[91]."</a></li>";
		}
	}
	print "</ul>";
	break;
  case "secciones":
	// ACCIONES CON ESTADISTICAS
	// -------------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[92]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[92]."</a></li>";
		}
	}
	if (($herramientas[93]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[93]."</a></li>";
		}
	}
	if (($herramientas[94]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[94]."</a></li>";
		}
	}
	if (($herramientas[95]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[95]."</a></li>";
		}
	}
	print "</ul>";
	// HERRAMIENTAS GENERALES
	print "<h1>".$lang["herramientas"]."</h1>";
	print "<ul>";
	if (($herramientas[96]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Estadisticas&herramienta=estadisticas_seccion&seccion=".$seccion."\"><img src=\"/administra/Imagenes/campos_adicionales.png\"> ".$textos[96]."</a></li>";
		}
	}
	print "</ul>";
	break;
  case "contenidos":
	// ACCIONES CON ESTADISTICAS
	// -------------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[97]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&orden=vistas\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[97]."</a></li>";
		}
	}
	if (($herramientas[98]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&orden=clicks\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[98]."</a></li>";
		}
	}
	if (($herramientas[99]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&orden=vistasMes\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[99]."</a></li>";
		}
	}
	if (($herramientas[100]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&orden=clicksMes\"><img src=\"/administra/Imagenes/contenido_ampliado.png\"> ".$textos[100]."</a></li>";
		}
	}
	print "</ul>";
	// HERRAMIENTAS GENERALES
	print "<h1>".$lang["herramientas"]."</h1>";
	print "<ul>";
	if (($herramientas[101]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Estadisticas&herramienta=estadisticas_seccion&seccion=".$seccion."\"><img src=\"/administra/Imagenes/campos_adicionales.png\"> ".$textos[101]."</a></li>";
		}
	}
	print "</ul>";
	break;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");