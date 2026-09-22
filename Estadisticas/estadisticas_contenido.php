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
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/estadisticas_contenido-".$_SESSION['idioma'].".conf");
// Mostramos el título de la sección en curso
$requete_estadisticas = "SELECT * FROM `Contenidos` WHERE `Id`='".$contenido."'";
$result_estadisticas = mysql_query($requete_estadisticas,$db);
$listado_estadisticas = mysql_fetch_object($result_estadisticas);
print "<h1><img src=\"/administra/Imagenes/secciones.png\"> ".$lang["estaUsted"]." <em>".$listado_estadisticas->Titulo."</em></h1>";
print "<hr/>";
print "<p><span style=\"background: #2f69bf;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> ".$lang["listados"]."<br/>";
print "<p><span style=\"background: #a2bf2f;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> ".$lang["impactos"]."</p>";
if (!isset($ano)) $ano = date("Y");
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=estadisticas_contenido&contenido=".$contenido."&ano=".($ano-1)."\">".$lang["anoAnterior"]." (".($ano-1).")</a> - <a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=estadisticas_contenido&contenido=".$contenido."&ano=".($ano+1)."\"> ".$lang["anoSiguiente"]." (".($ano+1).")</a></p>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.bar.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\">";
print "function drawChart(labels1, data1, data2, titulo) {\n";
print "var r = Raphael(\"holder\", 600, 300);\n";
print "var chart = r.g.barchart(10, 10, 580, 220, [data1,data2], {type: \"soft\"});\n";
print "chart.hover(function() {\n";
print "this.flag = r.g.popup(this.bar.x, this.bar.y, (this.bar.value || \"0\") + \"\").insertBefore(this);\n";
print "}, function() {\n";
print "this.flag.animate({opacity: 0}, 300, function () {this.remove();});\n";
print "});\n";
print "r.g.txtattr = {font:\"12px Verdana\"};\n";
print "r.g.text(160, 5, titulo);\n";
print "chart.label([labels1],true);\n";
print "}\n";
print "window.onload = function () {\n";
$serie1 = array();
$serie2 = array();
print "labels1 = [\"".$lang["ene"]."\", \"".$lang["feb"]."\", \"".$lang["mar"]."\", \"".$lang["abr"]."\", \"".$lang["may"]."\", \"".$lang["jun"]."\", \"".$lang["jul"]."\", \"".$lang["ago"]."\", \"".$lang["sep"]."\", \"".$lang["oct"]."\", \"".$lang["nov"]."\", \"".$lang["dic"]."\"];\n";
for ($i=1;$i<13;$i++)
{	
	if ($i==12) 
	{
		$ano_siguiente = $ano+1;
		$siguiente = 1;
	}
	else
	{
		$ano_siguiente = $ano;
		$siguiente = $i + 1 ;
	}
	$requete = "SELECT `Detalle`,`Listados` FROM `EstadisticasContenidos` WHERE `IdContenido`=".$contenido." AND `FechaInicio`>='".$ano."-".$i."-00' AND `FechaFin`<='".$ano_siguiente."-".$siguiente."-00'";	
	
	$serie1[$i] = 0 ;
	$serie2[$i] = 0 ;
	if ($result = mysqli_query($db, $requete))
	{		
 		while($listado = mysqli_fetch_object($result))
 		{
 			$serie1[$i] = $serie1[$i] + $listado->Detalle;
 			$serie2[$i] = $serie2[$i] + $listado->Listados;
		}
	}	
}
print "data1= [";
for ($j=1;($j<=count($serie1));$j++) 
{
	print $serie1[$j];
	if ($j<(count($serie1))) print ", ";
}
print "];\n";
print "data2=[";
for ($j=1;($j<=count($serie2));$j++) 
{
	print $serie2[$j];
	if ($j<(count($serie2))) print ", ";
}
print "];\n";
print "titulo=\"".$lang["estadisticasContenido"]."\";\n";
print "drawChart(labels1, data2,data1,titulo);\n";
print "};\n";
print "</script>\n";
print "<div id=\"holder\" style=\"height:250px;margin:0 auto;width:640px;\"></div>";
print "<table>";
print "<tr><th>".$lang["ano"]."</th><th>".$lang["visualizaciones"]."</th><th>".$lang["difVisualizaciones"]."</th><th>".$lang["impactos"]."</th><th>".$lang["difImpactos"]."</th></tr>";
$total1 = 0;
$total2 = 0;
$serie1 = 0 ;
$serie1Anterior = 0 ;
$serie2 = 0 ;
$serie2Anterior = 0 ;
$requete = "SELECT `Detalle`,`Listados`,`FechaFin` FROM `EstadisticasContenidos` WHERE `IdContenido`=".$contenido." ORDER BY `FechaFin`";

if ($result = mysqli_query($db, $requete))
{
	$ano_actual = 0;
	while($listado = mysqli_fetch_object($result))
	{		
		$ano = strftime("%Y", strtotime($listado->FechaFin));		
		if ($ano_actual==0) $ano_actual=$ano;
		if ($ano_actual!=$ano)
		{
			print "<tr>";
			print "<th>".$ano_actual."</th>";
			$ano_actual=$ano;
			print "<td>".$serie2."</td>";
			if ($serie2Anterior!=0)
			{
				$porcentaje = ($serie2Anterior/($serie2-$serie2Anterior)*100);
				if ($porcentaje>0) print "<td><span style=\"color:#00FF00;\">";
				else print "<td><span style=\"color:#FF0000;\">";
				printf("%.2f",$porcentaje)."</span></td>";					
			}
			else
			{
				print "<td>N/D</td>";
			}
			print "<td>".$serie1."</td>";
			if ($serie1Anterior!=0)
			{
				$porcentaje = ($serie1Anterior/($serie1-$serie1Anterior))*100;
				if ($porcentaje>0) print "<td><span style=\"color:#00FF00;\">";
				else print "<td><span style=\"color:#FF0000;\">";
				printf("%.2f",$porcentaje)."</span></td>";
			}
			else
			{
				print "<td>N/D</td>";
			}
			print "</tr>";
			$serie1Anterior = $serie1;
			$serie2Anterior = $serie2;
			$total1 = $total1+$serie1;
			$total2 = $total2+$serie2;
			$serie1 = 0;
			$serie2 = 0;	
		}
		$serie1= $serie1 + $listado->Detalle;
		$serie2= $serie2 + $listado->Listados;
	}
	print "<tr>";
	print "<th>".$ano_actual."</th>";
	$ano_actual=$ano;
	print "<td>".$serie2."</td>";
	if ($serie2Anterior!=0)
	{
		$porcentaje = ($serie2Anterior/($serie2-$serie2Anterior))*100;
		if ($porcentaje>0) print "<td><span style=\"color:#00FF00;\">";
		else print "<td><span style=\"color:#FF0000;\">";
		printf("%.2f",$porcentaje)."</span></td>";
	}
	else
	{
		print "<td>N/D</td>";
	}
	print "<td>".$serie1."</td>";
	if ($serie1Anterior!=0)
	{
		$porcentaje = ($serie1Anterior/($serie1-$serie1Anterior))*100;
		if ($porcentaje>0) print "<td><span style=\"color:#00FF00;\">";
		else print "<td><span style=\"color:#FF0000;\">";
		printf("%.2f",$porcentaje)."</span></td>";
	}
	else
	{
		print "<td>N/D</td>";
	}
	print "</tr>";
	$total1=$total1+$serie1;
	$total2=$total2+$serie2;
}
print "<tr><th>".$lang["totales"].":</th><td><strong>".$total2."</strong></td><td>&nbsp;</td><td><strong>".$total1."</strong></td><td>&nbsp;</td></tr>";
print "</table>";
//print "<img src=\"../Estadisticas/estadisticas_contenido_datos2.php?contenido=".$contenido."\"/>"; // GRÁFICO DE VISITAS DEL CONTENIDO ACTUAL
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>