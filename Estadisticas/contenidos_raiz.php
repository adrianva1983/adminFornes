<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/contenidos_raiz-".$_SESSION['idioma'].".conf");

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` ORDER BY `Clicks`DESC LIMIT 0,20";

print "<div style=\"width:50%;float:left;\">";
print "<h3>".$lang["maxClicks"]."</h3>";
print "<ul>";
while($listado = mysqli_fetch_object($result))
{
	print "<li>".$listado->Titulo."(".$listado->Clicks." clicks , ".$listado->Vistas." vistas)</li>";
}
print "</ul>";
print "</div>";
$requete = "SELECT * FROM `Contenidos` ORDER BY `Vistas` DESC LIMIT 0,20";

print "<div style=\"width:50%;float:left;\">";
print "<h3>".$lang["maxVistas"]."</h3>";
print "<ul>";
while($listado = mysqli_fetch_object($result))
{
	print "<li>".$listado->Titulo."(".$listado->Clicks." clicks , ".$listado->Vistas." vistas)</li>";
}
print "</ul>";
print "</div>";
if (!isset($ano)) $ano = date("Y");
if (!isset($mes)) $mes= date("m");
$siguiente = $mes + 1 ;
$requete = "SELECT * FROM `EstadisticasContenidos` WHERE `FechaInicio`>='".$ano."-".$mes."-00' AND `FechaFin`<='".$ano."-".$siguiente."-00' ORDER BY `Listados` DESC LIMIT 0,20";

print "<div style=\"width:50%;float:left;\">";
print "<h3>".$lang["maxVistasMes"]."</h3>";
print "<ul>";
while($listado = mysqli_fetch_object($result))
{
	$requete2 = "SELECT * FROM `Contenidos` WHERE `Id`='".$listado->IdContenido."'";
	
	$listado2 = mysqli_fetch_object($result2);
	print "<li>".$listado2->Titulo."(".$listado->Listados." listados , ".$listado->Detalle." detalle)</li>";
}
print "</ul>";
print "</div>";
$requete = "SELECT * FROM `EstadisticasContenidos` WHERE `FechaInicio`>='".$ano."-".$mes."-00' AND `FechaFin`<='".$ano."-".$siguiente."-00' ORDER BY `Detalle` DESC LIMIT 0,20";

print "<div style=\"width:50%;float:left;\">";
print "<h3>".$lang["maxClicksMes"]."</h3>";
print "<ul>";
while($listado = mysqli_fetch_object($result))
{
	$requete2 = "SELECT * FROM `Contenidos` WHERE `Id`='".$listado->IdContenido."'";
	
	$listado2 = mysqli_fetch_object($result2);
	print "<li>".$listado2->Titulo."(".$listado->Listados." listados , ".$listado->Detalle." detalle)</li>";
}
print "</ul>";
print "</div>";
print "<hr/>";
//Imprimimos el listado de carpetas
$requete = "SELECT * FROM `Secciones` WHERE `IdPadre` IS NULL ORDER BY `Orden`";

print "<ul>";
while($listado = mysqli_fetch_object($result))
{	
	print "<li>";
	print "<img src=\"/administra/Imagenes/secciones.png\">";
	$carpeta=$listado->NomFich;
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=contenidos&seccion=".$listado->Id."&ruta=".$carpeta."\">".$listado->Titulo."</a></li>";
}
print "</ul>";


mysql_free_result($result);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
