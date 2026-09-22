<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

//Comprobamos que tenga permiso en la sección actual
include($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/permisos_seccion.php");
if (!permisos_seccion($_SESSION['usuario_id'],$_SESSION['usuario_nivel'],$seccion))
{
	die ("Error cod.: Usuario sin Permisos en esta sección");
  exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/estadisticas_seccion-".$_SESSION['idioma'].".conf");
// Mostramos el título de la sección en curso
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion." ORDER BY `Orden`";

$listado = mysqli_fetch_object($result);
print "<h1><img src=\"/administra/Imagenes/secciones.png\"> ".$lang["estaUsted"]." <em>".$listado->Titulo."</em></h1>";
print "<hr/>";
if (!isset($ano)) $ano = date("Y");
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=estadisticas_seccion&seccion=".$seccion."&ano=".($ano-1)."\">".$lang["anoAnterior"]." (".($ano-1).")</a> - <a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=estadisticas_seccion&seccion=".$seccion."&ano=".($ano+1)."\">".$lang["anoSiguiente"]." (".($ano+1).")</a></p>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.bar.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\">";
print "function drawHChart(labels1, data1, titulo, capa) {\n";
print "var r = Raphael(capa, 600, 1600);\n";
print "var chart = r.g.hbarchart(100, 10, 370, 1520, [data1], {type: \"soft\"});\n";
print "chart.hover(function() {\n";
print "this.flag = r.g.popup(this.bar.x, this.bar.y, (this.bar.value || \"0\") + \"\").insertBefore(this);\n";
print "}, function() {\n";
print "this.flag.animate({opacity: 0}, 300, function () {this.remove();});\n";
print "});\n";
print "r.g.txtattr = {font:\"12px Verdana\"};\n";
print "r.g.text(160, 5, titulo);\n";
print "chart.label([labels1],true);\n";
print "}\n";
print "function drawChart(labels1, data1, titulo, capa) {\n";
print "var r = Raphael(capa, 600, 300);\n";
print "var chart = r.g.barchart(10, 10, 580, 220, [data1], {type: \"soft\"});\n";
print "chart.hover(function() {\n";
print "this.flag = r.g.popup(this.bar.x, this.bar.y, (this.bar.value || \"0\") + \"\").insertBefore(this);\n";
print "}, function() {\n";
print "this.flag.animate({opacity: 0}, 300, function () {this.remove();});\n";
print "});\n";
print "r.g.txtattr = {font:\"12px Fontin-Sans, Arial, sans-serif\", fill:\"#000\", \"font-weight\": \"bold\"};\n";
print "r.g.text(160, 5, titulo);\n";
print "chart.label([labels1],true);\n";
print "}\n";
print "window.onload = function () {\n";
$serie1 = array();
$serie2 = array();
print "labels1 = [\"".$lang["ene"]."\", \"".$lang["feb"]."\", \"".$lang["mar"]."\", \"".$lang["abr"]."\", \"".$lang["may"]."\", \"".$lang["jun"]."\", \"".$lang["jul"]."\", \"".$lang["ago"]."\", \"".$lang["sep"]."\", \"".$lang["oct"]."\", \"".$lang["nov"]."\", \"".$lang["dic"]."\"];\n";
for ($i=1;$i<13;$i++)
{
	$siguiente = $i + 1 ;
	$requete = "SELECT `Valor` FROM `EstadisticasSecciones` WHERE `IdSeccion`=".$seccion." AND `Fecha`>'".$ano."-".$i."-01' AND `Fecha`<'".$ano."-".$siguiente."-01'";
	
	$serie1[$i] = 0 ;
	if ($result = mysqli_query($db, $requete))
	{		
 		while($listado = mysqli_fetch_object($result))
 		{
 			$serie1[$i] = $serie1[$i] + $listado->Valor;
		}
	}
}
print "data1= [";
for ($j=1;($j<count($serie1));$j++) 
{
	print $serie1[$j];
	if ($j<(count($serie1)-1)) print ", ";
}
print "];\n";
print "titulo=\"".$lang["estadisticasSeccion"]."\";\n";
print "drawChart(labels1,data1,titulo,\"holder\");\n";
$requete = "SELECT `Id`,`Orden`,`NomFich`,`Titulo`, `Visibilidad` FROM `Secciones` WHERE `IdPadre`=".$seccion." ORDER BY `Orden`";

// Listamos las secciones existentes
$i=0;
$serie1 = array();
$serie2 = array();
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {	
	$serie2[$i] = $listado->Titulo;
	$serie1[$i] = 0;
	$requete2 = "SELECT `Valor` FROM `EstadisticasSecciones` WHERE `IdSeccion`=".$listado->Id." AND (`Fecha`>'".$ano."-01-01' AND `Fecha`<'".$ano."-12-01')";
	
	if ($result2 = mysqli_query($db, $requete2))
	{
 		while($listado2 = mysqli_fetch_object($result2))
 		{
 			$serie1[$i] = $serie1[$i] + $listado2->Valor;
 		}
 	}
	$i++;	
 }
}
print "data2= [";
for ($j=1;($j<count($serie1));$j++) 
{
	print $serie1[$j];
	if ($j<(count($serie1)-1)) print ", ";
}
print "];\n";
print "labels= [";
for ($j=1;($j<count($serie2));$j++) 
{
	print "\"".$serie2[$j]."\"";
	if ($j<(count($serie2)-1)) print ", ";
}
print "];\n";
print "drawHChart(labels,data2,titulo,\"holder2\");\n";
print "};\n";
print "</script>\n";
print "<div id=\"holder\" style=\"height:240px;margin:0 auto;width:640px;\"></div>";
print "<div id=\"holder2\" style=\"height:1600px;margin:0 auto;width:640px;\"></div>";

if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

?>