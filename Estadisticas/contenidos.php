<?php
function botones($url_ima,$url_acc)
{
 print "<a href=$url_acc> <img src=$url_ima \\></a>\n";
}

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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion." ORDER BY `Orden`";

$listado = mysqli_fetch_object($result);

//Comprobamos que tenga permiso en la sección actual
include($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/permisos_seccion.php");
if (!permisos_seccion($_SESSION['usuario_id'],$_SESSION['usuario_nivel'],$seccion))
{
	die ("Error cod.: Usuario sin Permisos en esta sección");
  exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/contenidos-".$_SESSION['idioma'].".conf");

print "<h1><img src=\"/administra/Imagenes/secciones.png\"> ".$lang["estaUsted"]." <em>".$listado->Titulo."</em></h1>";
//--------------------------------
// Mostramos la ruta
print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=raiz\">".$lang["raiz"]."</a> / ";
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
 print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$temp."\">".$cabecera[$i]."</a>";
}
print " / <a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."\">".$temp_NomFich."</a>";
print "<hr>";
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

// Hacemos una consulta para ver cuantas secciones hay en esta ubicación
$requete = "SELECT `Id`,`Orden`,`NomFich`,`Titulo`, `Visibilidad` FROM `Secciones` WHERE `IdPadre`=".$seccion." ORDER BY `Orden`";

// Listamos las secciones existentes
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {	
	print "<li>";       
  print "<img src=\"/administra/Imagenes/secciones.png\">";
	$enruta=$ruta."/".$listado->NomFich;
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$enruta."\">".$listado->Titulo."</a>";
	print "</li>";
 }
}
print "</ul>";
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
