<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO SUPERUSUARIO
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}


require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/encuestas-".$_SESSION['idioma'].".conf");
// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$encuesta;

$listado = mysqli_fetch_object($result);
print "<h1><img src=\"/administra/Imagenes/seccion_encuesta.png\"> ".$lang["estaUsted"]." <em>".$listado->Titulo."</em></h1>";

// Mostranos la ruta
print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz\">".$lang["raiz"]."</a> / ";
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion;

$listado = mysqli_fetch_object($result);
$temp_NomFich = $listado->NomFich;
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
print "<hr>";

// Hacemos una consulta para ver cuantas ampliaciones de contenido tiene el contenido actual
$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdPadre = ".$encuesta." AND IdContenido = `Contenidos`.Id ORDER BY `Orden`";

// Listamos las opciones de la encuesta existentes
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
	print "<li>";
	$orden=$listado->Orden;
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE
	  print "<a href=\"/administra/Carpetas/funciones/borrar_ampliacion.php?ruta=".$ruta."&seccion=".$seccion."&contenido=".$listado->Id."&antiguo=".$contenido."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";
	}
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE
	  print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si\"><img src=\"/administra/Imagenes/top_arriba.png\" title=\"".$lang["topArriba"]."\" alt=\"".$lang["topArriba"]."\"></a>";
	}
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE
	  print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si\"><img src=\"/administra/Imagenes/arriba.png\" alt=\"".$lang["arriba"]."\" title=\"".$lang["arriba"]."\"></a>";
	}
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE
	  print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si\"><img src=\"/administra/Imagenes/abajo.png\" alt=\"".$lang["abajo"]."\" title=\"".$lang["abajo"]."\"></a>";
	}
	if ($_SESSION['usuario_nivel']<=3)
	{ //NIVEL MÍNIMO RESPONSABLE
	  print "<a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Publicaciones&item=IdAmpliacion&iid=".$contenido."&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=".$seccion."&ampliacion=si\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topAbajo"]."\" title=\"".$lang["topAbajo"]."\"></a>";
	}
	// AMPLIACIÓN DE TIPO TEXTO
	if (($listado->Tipo)=="encuesta_opcion")
	{
	  print "<img src=\"/administra/Imagenes/encuesta_opcion.png\" title=\"".$lang["opcion"]."\" alt=\"".$lang["opcion"]."\">";
	  if ($_SESSION['usuario_nivel']<=3)
	  { //NIVEL MÍNIMO RESPONSABLE
	    print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_encuesta_opcion&seccion=".$seccion."&ruta=".$ruta."&encuesta=".$encuesta."&idencuesta=".$listado->Id."\">".$listado->Titulo."</a>";
	  }
	  else
	  {
	    print $listado->Titulo;
	  } //FIN NIVEL
	}
 }
}
print "</ul>";
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>