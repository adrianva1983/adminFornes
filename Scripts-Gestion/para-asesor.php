<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="robots" content="no-index">
		<script type="text/javascript" src="/js/jquery/jquery-1.11.0.min.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css">
		<title>Solicitud</title>
	</head>
<?php
if (($_REQUEST['cod'])!="xanawapa") 
{
	print "<form action=\"/administra/Scripts-Gestion/listado-tiendas.php\" enctype=\"multipart/form-data\" method=\"POST\">";
	print "<input type=\"hidden\" id=\"formato\" name=\"formato\" value=\"listado\">";	
	print "<ul>";
	print "<li><label for=\"cod\">Contraseña: </label><input type=\"text\" name=\"cod\" value=\"\"></li>";
	print "<li><label for=\"fecha1\">Fecha1 (aaaa-mm-dd): </label><input id=\"fecha1\" name=\"fecha1\" type=\"text\"></li>";
	print "<li><label for=\"fecha2\">Fecha2 (aaaa-mm-dd): </label><input id=\"fecha2\" name=\"fecha2\" type=\"text\"></li>";
	print "<li><label for=\"desglosado\">Desglosado: </label><select name=\"desglosado\" id=\"desglosado\"><option>no</option><option value=\"si\">sí</option></select></li>";
	print "<li><input class=\"boton\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"Siguiente\"></li>";
	print "</ul>";
	print "</form>";	
	exit();
}
$numOutputs = 0;
$ventas = 0;
$comisiones = 0;
$total_ventas_BI = 0;
$total_impuestos = 0;
$total_productos_BI = 0;
$total_productos = 0;
$total_envio_BI = 0;
$total_envio = 0;
global $db;
//----------------------
$db= mysql_connect("localhost", "lasespinacas", "comprar1libro");
mysql_select_db("lasespinacasdepopeye");
$ventas = 0;
$comisiones = 0;
$requete = "SELECT SUM(total_products_wt) AS total_productos,SUM(total_products) AS total_productos_BI,SUM(total_paid) AS total_ventas,SUM(total_paid_tax_excl) AS total_ventas_BI,SUM(total_shipping) AS total_envio,SUM(total_shipping_tax_excl) AS total_envio_BI FROM ps_orders WHERE ";
if (!isset($_REQUEST['total'])&&!isset($_REQUEST['fecha1'])&&!isset($_REQUEST['fecha2'])) $requete.="ps_orders.date_add>='".date("Y")."-".date("m")."-01 00:00:01' AND ";
else
{
	if (isset($_REQUEST['fecha1'])) 
	{
		$requete.="ps_orders.date_add>='".$_REQUEST['fecha1']." 00:00:01' AND ";		
	}
	if (isset($_REQUEST['fecha2'])) 
	{
		$requete.="ps_orders.date_add<='".$_REQUEST['fecha2']." 23:59:59' AND ";		
	}
}
$requete.= "`valid`=1";

if ($result = mysqli_query($db, $requete))
{
	$i=0;
	$listado = mysqli_fetch_object($result);
	if ($listado->total_productos_BI!=null) $total_productos_BI = $listado->total_productos_BI;
	if ($listado->total_productos!=null) $total_productos = $listado->total_productos;
	if ($listado->total_ventas!=null) $total_ventas = $listado->total_ventas;
	if ($listado->total_ventas_BI!=null) $total_ventas_BI = $listado->total_ventas_BI;
	if ($listado->total_envio!=null) $total_envio = $listado->total_envio;
	if ($listado->total_envio_BI!=null) $total_envio = $listado->total_envio_BI;		
	print "<table border=\"1px\">";
	print "<tr>";
	print "<th>Total Productos BI</th><th>Total Productos</th><th>Total Ventas BI</th><th>Total Ventas</th><th>Total Envio BI</th><th>Total Envio</th>";
	print "</tr>";
	print "<tr>";
	print "<td>".$total_productos_BI."</td>";	
	print "<td>".$total_productos."</td>";
	print "<td>".$total_ventas_BI."</td>";
	print "<td>".$total_ventas."</td>";
	print "<td>".$total_envio_BI."</td>";
	print "<td>".$total_envio."</td>";
	print "</tr>";
	print "</table>";
}
if ($_REQUEST['desglosado']=="si")
{
	$requete = "SELECT id_order,reference,total_products,total_products_wt,total_paid,total_paid_tax_excl,total_shipping,total_shipping_tax_excl FROM ps_orders WHERE ";	
	if (!isset($_REQUEST['total'])&&!isset($_REQUEST['fecha1'])&&!isset($_REQUEST['fecha2'])) $requete.="ps_orders.date_add>='".date("Y")."-".date("m")."-01 00:00:01' AND ";
	else
	{
		if (isset($_REQUEST['fecha1'])) 
		{
			$requete.="ps_orders.date_add>='".$_REQUEST['fecha1']." 00:00:01' AND ";		
		}
		if (isset($_REQUEST['fecha2'])) 
		{
			$requete.="ps_orders.date_add<='".$_REQUEST['fecha2']." 23:59:59' AND ";		
		}
	}
	$requete.= "`valid`=1";	
	
	if ($result = mysqli_query($db, $requete))
	{	
		$arrayTemp["Desglose"] = "<h2>Desglose de pedidos:</h2><table border=\"1px\" cellpadding=\"0\" cellspacin=\"0\"><tr><th>Id Pedido</th><th>Referencia</th><th>Total BI productos</th><th>Total productos</th><th>Total pagado BI</th><th>Total pagado</th><th>Total envio BI</th><th>Total envio</th></tr>";
		$total_periodo = 0;
		$total_periodo_BI = 0;
		$total_periodo_IVA = 0;
		while ($listado = mysqli_fetch_object($result))
		{			
			$arrayTemp["Desglose"] .= "<tr>";
			$arrayTemp["Desglose"] .= "<td>".$listado->id_order."</td><td>".$listado->reference."</td><td>".$listado->total_products."</td><td>".$listado->total_products_wt."</td><td>".$listado->total_paid_tax_excl."</td><td>".$listado->total_paid."</td><td>".$listado->total_shipping_tax_excl."</td><td>".$listado->total_shipping."</td>";
			$listado->total_paid_tax_excl."</td><td>".
			$total_periodo_BI += $listado->total_paid_tax_excl;
			$total_periodo_IVA += $listado->total_paid-$listado->total_paid_tax_excl;
			$total_periodo += $listado->total_paid;
			$arrayTemp["Desglose"] .= "</tr>";
		}
		$arrayTemp["Desglose"] .= "<tr><th></th><th>Total BI:</th><th>".$total_periodo_BI."</th></tr>";		
		$arrayTemp["Desglose"] .= "<tr><th></th><th>Total IVA:</th><th>".$total_periodo_IVA."</th></tr>";		
		$arrayTemp["Desglose"] .= "<tr><th></th><th>Total:</th><th>".$total_periodo."</th></tr>";
		$arrayTemp["Desglose"] .= "</table>";
	}
}
$db = mysql_close($db);
if ($_REQUEST['desglosado']=="si")
{
	print "<hr/>";	
	print $arrayTemp["Desglose"];
}
?>
	</body>
</html>