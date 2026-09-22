<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/contenidos_tipo-".$_SESSION['idioma'].".conf");

print "<h1>".$lang["detalle"]."</h1>";

print "<br/><a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=contenidos_tipo&tipo=".$tipo."&orden_listado=vistas\">".$lang["ordenarVistas"]."</a><a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=contenidos_tipo&tipo=".$tipo."&orden_listado=clicks\">".$lang["ordenarClicks"]."</a>";
print "<hr>";
// Hacemos una consulta para ver cuantas secciones hay en esta ubicación
//Consultamos el listado de Contenidos publicados en esta sección
switch ($orden_listado)
{
	case "clicks":
		$requete = "SELECT * FROM `Contenidos` WHERE IdTipoContenido = ".$tipo." ORDER BY `Clicks` DESC";
		break;
	case "vistas":
		$requete = "SELECT * FROM `Contenidos` WHERE IdTipoContenido = ".$tipo." ORDER BY `Vistas` DESC";
		break;
	default:
		$requete = "SELECT * FROM `Contenidos` WHERE IdTipoContenido = ".$tipo." ORDER BY `Titulo`";		
		break;	
}


if ($result = mysqli_query($db, $requete))
{
 print "<hr><ul>";
 while($listado = mysqli_fetch_object($result))
 {
	print "<li>";
	// Permitimos ver su ficha	

	//PERMISOS MÍNIMOS PARA EDITAR UN CONTENIDO COORDINADOR O PROPIETARIO DE CONTENIDO
        if (($nivel<2) OR ($_SESSION['usuario_id']==$listado->IdPropietario))
	{	 
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$listado->IdContenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/contenido.png\">";
		print $listado->Titulo;
		print "</a>";
	}
	print "( ".$lang["vistas"].": ".$listado->Vistas."- ".$lang["clicks"].": ".$listado->Clicks.")";
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
