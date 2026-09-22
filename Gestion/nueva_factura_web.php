<?php
//NECESITA:
//cod: para acceder
//id_cliente: a quien se le crea la factura
//id_empresa: con que empresa se factura
//id_estado: en que estado se queda la factura
//Saldada: 0 o 1 para saber si está o no pagada
//BI: base imponible de factura
//titulo: título de la factura
//tax: impuestos de la factura
//moneda: Moneda de la factura
//DEVUELVE
//IdFactura: Id de la factura creada

if ($cod!="dsgjlo39801ekldjsad0913ered123sla0"){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Facturas` WHERE `IdEstado`=1 ORDER BY Id DESC LIMIT 0,1";

$NumFactura = 0;
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);	
	$NumFactura = $listado->NumeroFactura;	
}
$NumFactura++;
$requete = "INSERT INTO `Facturas` (`Titulo`,`NumeroFactura`,`Fecha`,`Vencimiento`,`IdEmpresa`,`IdCliente`,`IdEstado`,`Saldada`";
$requete.=") VALUES ('".$titulo."'";
$requete.=",'".str_pad($NumFactura, 8, '0', STR_PAD_LEFT)."','".date("Y-m-d")."','".date("Y-m-d")."',".$id_empresa.",".$id_cliente;
$requete.=",".$id_estado.",".$Saldada;
$requete.= ");";
mysqli_query($db,$requete);
$IdFactura = mysqli_insert_id($db);
$requete = "INSERT INTO `FacturasLineas` (`IdFactura`,`Texto`,`BaseImponible`,`Impuesto`,`Moneda`) VALUES (".$IdFactura.",'".$titulo."','".$BI."','".$tax."','".$Moneda."');";
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>