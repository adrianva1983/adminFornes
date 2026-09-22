<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_POST["Id"];
$pagina = $_POST["pagina"];
$NumeroFactura = $_POST["NumeroFactura"];
$SerieFactura = $_POST["SerieFactura"];
$IdSerie = $_POST["IdSerie"];
$Fecha = $_POST["Fecha"];
$Vencimiento = $_POST["Vencimiento"];
$IdEmpresa = $_POST["IdEmpresa"];
$IdCliente = $_POST["IdCliente"];
$IdEstado = $_POST["IdEstado"];
$Saldada = $_POST["Saldada"];
$SaldadaComision = $_POST["SaldadaComision"];
$IdPeriodicidad = $_POST["IdPeriodicidad"];
$IdPresupuesto = $_POST["IdPresupuesto"];
$FechaSaldada = $_POST["FechaSaldada"];
$FormaDePago = $_POST["FormaDePago"];
$Titulo = $_POST["Titulo"];
$Texto = $_POST["Texto"];
$IdProveedor = $_POST["IdProveedor"];
$Rectificativa = $_POST['Rectificativa'];
$IdFacturaRelacionada = $_POST['IdFacturaRelacionada'];
$CodigoFacturaGasto = $_POST["CodigoFacturaGasto"];
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Facturas` SET `Titulo`='".$Titulo."', `Saldada`=".$Saldada.", `SaldadaComision`=".$SaldadaComision;
if ($Fecha!="") $requete.=",`Fecha` = '".$Fecha."'";
else $requete.= ",`Fecha`=NULL";
if ($CodigoFacturaGasto!="") $requete.=",`CodigoFacturaGasto`='".$CodigoFacturaGasto."'";
else $requete.= ",`CodigoFacturaGasto`=NULL";
if ($IdProveedor!="") $requete.=",`IdProveedor`=".$IdProveedor;
else $requete.=",`IdProveedor`=NULL";
if ($Vencimiento!="") $requete.= ",`Vencimiento`='".$Vencimiento."'";
else $requete.= ",`Vencimiento`=NULL";
if ($IdCliente!="") $requete.= ",`IdCliente`=".$IdCliente;
else $requete.= ",`IdCliente`=NULL";
if ($IdEmpresa!="") $requete.= ",`IdEmpresa`=".$IdEmpresa;
else $requete.= ",`IdEmpresa`=NULL";
if ($IdPeriodicidad!="") $requete.= ",`IdPeriodicidad`=".$IdPeriodicidad;
else $requete.= ",`IdPeriodicidad`=NULL";
if ($IdEstado!="") $requete.= ",`IdEstado`=".$IdEstado;
else $requete.= ",`IdEstado`=NULL";
if ($SerieFactura!="") $requete.= ",`SerieFactura`='".$SerieFactura."'";
else $requete.= ",`NumeroFactura`=NULL";
if ($NumeroFactura!="") $requete.= ",`NumeroFactura`='".$NumeroFactura."'";
else $requete.= ",`NumeroFactura`=NULL";
if ($IdSerie!="") $requete.= ",`IdSerie`='".$IdSerie."'";
else $requete.= ",`IdSerie`=NULL";
if ($Rectificativa!="") $requete.= ",`Rectificativa`=".$Rectificativa;
else $requete.= ",`Rectificativa`=NULL";
if ($IdFacturaRelacionada!="") $requete.= ",`IdFacturaRelacionada`='".$IdFacturaRelacionada."'";
else $requete.= ",`IdFacturaRelacionada`=NULL";
if ($FormaDePago!="") $requete.= ",`FormaDePago`=".$FormaDePago;
else $requete.= ",`FormaDePago`=NULL";
$requete.=" WHERE `Id`='".$Id."';";
mysqli_query($db,$requete);
if ($Saldada==1)
{
	$requete = "SELECT * FROM `Contabilidad` WHERE `IdFactura`=".$Id;
	
	$llevaPagado = 0;
	if ($result = mysqli_query($db, $requete))
	{			
		while ($listado = mysqli_fetch_object($result))
		{
			$llevaPagado+= $listado->Importe;
		}
	}
	$requete = "SELECT * FROM `FacturasLineas` WHERE `IdFactura`=".$Id;	
	
	$importeFactura = 0;
	if ($result = mysqli_query($db, $requete))
	{			
		while ($listado = mysqli_fetch_object($result))
		{
			$importeFactura+=$listado->BaseImponible;
		}
	}
	if (($importeFactura-$llevaPagado)>0)
	{
		$requete = "INSERT INTO `Contabilidad` (`IdEmpresa`,`IdFactura`,`Importe`,`Fecha`,`Titulo`,`IdCliente`) VALUES (".$IdEmpresa.",".$Id.",'".($importeFactura-$llevaPagado)."','".$FechaSaldada."','".$lang["pagoFactura"].": ".$Titulo."',".$IdCliente.");";
		mysqli_query($db,$requete);
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=facturas&pagina=".$pagina);
?>