<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0]." ".$mifecha[1];	
	return $lafecha;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/referidos-".$_SESSION['idioma'].".conf");

if ($_SESSION['usuario_nivel']<2)
{
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS NOT NULL";
	
	$total_reservas = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS NOT NULL AND `Estado`='Confirmada'";
	
	$total_reservas_confirmadas = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido` IS NOT NULL AND `Estado`='Pendiente'";
	
	$total_reservas_pendientes = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS NOT NULL AND `Saldado`='no' AND `Estado`='Confirmada'";
	
	$total_pendiente_saldar = mysqli_num_rows($result);
}
else
{
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`='".$_SESSION['usuario_id']."'";
	
	$total_reservas = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`='".$_SESSION['usuario_id']."' AND `Estado`='Confirmada'";
	
	$total_reservas_confirmadas = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`='".$_SESSION['usuario_id']."' AND `Estado`='Pendiente'";
	
	$total_reservas_pendientes = mysqli_num_rows($result);
	$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `IdReferido`='".$_SESSION['usuario_id']."' AND `Saldado`='no' AND `Estado`='Confirmada'";
	
	$total_pendiente_saldar = mysqli_num_rows($result);
}
print "<p>";
print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/reservas.png\" title=\"".$lang["recibidas"]."\" alt=\"".$lang["recibidas"]."\"> :: <strong>".$lang["total"].":</strong> ".$total_reservas."<br/>";
print "<img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["confirmada"]."\" alt=\"".$lang["confirmada"]."\"> :: <strong>".$lang["totalConfirmadas"].":</strong> ".$total_reservas_confirmadas."<br/>"; 
print "<img src=\"/administra/Imagenes/pendiente.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"> :: <strong>".$lang["totalPendientes"].":</strong> ".$total_reservas_pendientes."<br/>"; 
print "<img src=\"/administra/Imagenes/cross.png\" title=\"".$lang["cancelada"]."\" alt=\"".$lang["cancelada"]."\"> :: <strong>".$lang["totalCanceladas"].":</strong> ".($total_reservas-$total_reservas_confirmadas-$total_reservas_pendientes)."<br/>";
print "<img src=\"/administra/Imagenes/money.png\" title=\"".$lang["saldar"]."\" alt=\"".$lang["saldar"]."\"> :: <strong>".$lang["totalSaldar"].":</strong> ".$total_pendiente_saldar."<br/>";
print "</div>";
if (!isset($ano)) $ano = date("Y");
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=referidos&&ano=".($ano-1)."\">".$lang["anoAnterior"]." (".($ano-1).")</a> - <a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=referidos&ano=".($ano+1)."\">".$lang["anoSiguiente"]." (".($ano+1).")</a></p>";
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
	if ($_SESSION['usuario_nivel']<2) $requete = "SELECT `Id` FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS NOT NULL AND `Fecha`>='".$ano."-".$i."-00' AND `Fecha`<='".$ano."-".$siguiente."-00' AND `Estado`='Confirmada'";
	else $requete = "SELECT `Id` FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS '".$_SESSION['usuario_id']."' `Fecha`>='".$ano."-".$i."-00' AND `Fecha`<='".$ano."-".$siguiente."-00' AND `Estado`='Confirmada'";	
	
	$serie1[$i] = mysqli_num_rows($result);
	if ($_SESSION['usuario_nivel']<2) $requete = "SELECT `Id` FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS NOT NULL AND `Fecha`>='".$ano."-".$i."-00' AND `Fecha`<='".$ano."-".$siguiente."-00' AND `Estado`='Pendiente'";
	else $requete = "SELECT `Id` FROM `guiarestaurantes_reservas` WHERE `IdReferido`IS '".$_SESSION['usuario_id']."' `Fecha`>='".$ano."-".$i."-00' AND `Fecha`<='".$ano."-".$siguiente."-00' AND `Estado`='Pendiente'";
	
	$serie2[$i] = mysqli_num_rows($result);	
}
print "data1= [";
for ($j=1;($j<count($serie1));$j++) 
{
	print $serie1[$j];
	if ($j<(count($serie1)-1)) print ", ";
}
print "];\n";
print "data2=[";
for ($j=1;($j<count($serie2));$j++) 
{
	print $serie2[$j];
	if ($j<(count($serie2)-1)) print ", ";
}
print "];\n";
print "titulo=\"".$lang["estadisticas"]."\";\n";
print "drawChart(labels1, data2,data1,titulo);\n";
print "};\n";
print "</script>\n";
print "<div id=\"holder\" style=\"height:480px;margin:0 auto;width:640px;\"></div>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>