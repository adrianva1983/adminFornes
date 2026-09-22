<?php
if (($_REQUEST['cod'])!="fdakghkldehgo284234893e21a-<") 
{
	exit;
}
$ventas = 0;
$comisiones = 0;
$comisiones_excepcion1 = 0;
$comisiones_excepcion2 = 0;
$comisiones_excepcion3 = 0;
global $db;
$db= mysql_connect("localhost", "delicatessen15", "comprar1libro");
mysql_select_db("delicatessen15");
$requete = "SELECT ps_orders.id_order,ps_order_detail.product_quantity,ps_order_detail.product_id,ps_order_detail.total_price_tax_excl FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id<>8 AND ps_order_detail.product_id<>9 AND ps_order_detail.product_id<>10 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		print_r($listado);
		print "<br/>";
	}
}
$requete = "SELECT SUM(total_price_tax_excl) AS total FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id<>8 AND ps_order_detail.product_id<>9 AND ps_order_detail.product_id<>10 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->total!=null) $ventas = $listado->total;
	$comisiones = $ventas * 0.05;
}
print "<h2>".$listado->total."</h2>";

$requete = "SELECT ps_orders.id_order,ps_order_detail.product_quantity,ps_order_detail.product_id,ps_order_detail.total_price_tax_excl FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=8 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		print_r($listado);
		print "<br/>";
	}
}

$requete = "SELECT SUM(ps_order_detail.product_quantity) AS total FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=8 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->total!=null) $num_excepcion1 = $listado->total;
	$comisiones_excepcion1 = $num_excepcion1 * 0.9;
}
print "<h2>".$listado->total."</h2>";

$requete = "SELECT ps_orders.id_order,ps_order_detail.product_quantity,ps_order_detail.product_id,ps_order_detail.total_price_tax_excl FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=9 AND ";
if (!isset($_REQUEST['total'])&&!isset($_REQUEST['fecha1'])&&!isset($_REQUEST['fecha2'])) $requete.="`date_add`>='".date("Y")."-".date("m")."-01 00:00:01' AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		print_r($listado);
		print "<br/>";
	}
}

$requete = "SELECT SUM(ps_order_detail.product_quantity) AS total FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=9 AND ";
if (!isset($_REQUEST['total'])&&!isset($_REQUEST['fecha1'])&&!isset($_REQUEST['fecha2'])) $requete.="`date_add`>='".date("Y")."-".date("m")."-01 00:00:01' AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->total!=null) $num_excepcion2 = $listado->total;
	$comisiones_excepcion2 = $num_excepcion2 * 1.4;
}
print "<h2>".$listado->total."</h2>";


$requete = "SELECT ps_orders.id_order,ps_order_detail.product_quantity,ps_order_detail.product_id,ps_order_detail.total_price_tax_excl FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=10 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		print_r($listado);
		print "<br/>";
	}
}

$requete = "SELECT SUM(ps_order_detail.product_quantity) AS total FROM ps_orders,ps_order_detail WHERE ps_orders.id_order=ps_order_detail.id_order AND ps_order_detail.product_id=10 AND ";
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
$requete.= "ps_orders.valid=1";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($listado->total!=null) $num_excepcion3 = $listado->total;
	$comisiones_excepcion3 = $num_excepcion3 * 4.5;
}
print "<h2>".$listado->total."</h2>";

$db = mysql_close($db);
?>
