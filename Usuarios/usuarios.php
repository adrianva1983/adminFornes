<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/usuarios-".$_SESSION['idioma'].".conf");
print "<h1>".$lang["usuarios"]."</h1>";
print "<div id=\"instrucciones\"><span style=\"background: #2f69bf;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> ".$lang["altas"]."<br/></div>";
if (!isset($ano)) $ano = date("Y");
print "<p><a class=\"boton_linea\" href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios&ano=".($ano-1)."\">".$lang["anoAnterior"]." (".($ano-1).")</a> - <a class=\"boton_linea\" href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios&ano=".($ano+1)."\"> ".$lang["anoSiguiente"]." (".($ano+1).")</a></p>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.raphael.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\" src=\"/herramientas/raphaelCharts/g.bar.js\"></script>";
print "<script charset=\"utf-8\" type=\"text/javascript\">";
print "window.onload = function () {\n";
print "var r = Raphael(\"holder\"),";
print "fin = function () {";
print "this.flag = r.g.popup(this.bar.x, this.bar.y, this.bar.value || \"0\").insertBefore(this);";
print "},";
print "fout = function () {";
print "this.flag.animate({opacity: 0}, 300, function () {this.remove();});";
print "},";
print "fin2 = function () {";
print "var y = [], res = [];";
print "for (var i = this.bars.length; i--;) {";
print "y.push(this.bars[i].y);";
print "res.push(this.bars[i].value || \"0\");";
print "}";
print "this.flag = r.g.popup(this.bars[0].x, Math.min.apply(Math, y), res.join(\", \")).insertBefore(this);";
print "},";
print "fout2 = function () {";
print "this.flag.animate({opacity: 0}, 300, function () {this.remove();});";
print "};";
print "r.g.txtattr.font = \"12px Verdana\";";
print "r.g.text(160, 10, \"".$lang["usuarios"]."\");";
$serie1 = array();
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
	$requete = "SELECT `Id` FROM `Usuarios` WHERE `FechaCreacion`>='".$ano."-".$i."-00' AND `FechaCreacion`<='".$ano_siguiente."-".$siguiente."-00' AND (`ExclusivoMailing`='no' OR `ExclusivoMailing`='')";	
	
	$serie1[$i] = 0 ;	
	if ($result = mysqli_query($db, $requete))
	{		
		$serie1[$i] = mysqli_num_rows($result); 			
	}
}
print "data1= [";
for ($j=1;($j<=count($serie1));$j++) 
{
	print $serie1[$j];
	if ($j<(count($serie1))) print ", ";
}
print "];\n";
print "var chart = r.g.barchart(10, 10, 580, 220, [data1], {type: \"soft\"}).hover(fin, fout);";
print "labels1 = [\"".$lang["ene"]."\", \"".$lang["feb"]."\", \"".$lang["mar"]."\", \"".$lang["abr"]."\", \"".$lang["may"]."\", \"".$lang["jun"]."\", \"".$lang["jul"]."\", \"".$lang["ago"]."\", \"".$lang["sep"]."\", \"".$lang["oct"]."\", \"".$lang["nov"]."\", \"".$lang["dic"]."\"];\n";
print "chart.label([labels1],true);\n";
print "};\n";
print "</script>\n";
print "<div id=\"holder\" style=\"height:250px;margin:0 auto;width:640px;\"></div>";
print "<p>";
print "<strong>".$lang["totalUsuarios"].":</strong>";
$requete = "SELECT `Id` FROM `Usuarios` WHERE (`ExclusivoMailing`='no' OR `ExclusivoMailing`='')";

print mysqli_num_rows($result)."<br/>";
print "<strong>".$lang["totalUsuariosBoletines"].":</strong>";
$requete = "SELECT `Id` FROM `Usuarios` WHERE `ExclusivoMailing`='si'";

print mysqli_num_rows($result)."<br/>";
print "<strong>".$lang["accesosHoy"].":</strong>";
$requete = "SELECT `Id` FROM `Usuarios` WHERE `FechaUltimoAcceso`='".date("Y-m-d")."'";

print mysqli_num_rows($result)."<br/>";
print "<strong>".$lang["accesosMes"].":</strong>";
$requete = "SELECT `Id` FROM `Usuarios` WHERE `FechaUltimoAcceso`>'".date("Y")."-".date("m")."-00'";

print mysqli_num_rows($result)."<br/>";
print "<strong>".$lang["accesosAno"].":</strong>";
$requete = "SELECT `Id` FROM `Usuarios` WHERE `FechaUltimoAcceso`>'".date("Y")."-00-00'";

print mysqli_num_rows($result)."<br/>";
print "</p>";
?>