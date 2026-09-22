<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$NumeroFactura = $_POST["NumeroFactura"];
$SerieFactura = $_POST["SerieFactura"];
$IdSerie = $_POST["IdSerie"];
$Fecha = $_POST["Fecha"];
$Vencimiento = $_POST["Vencimiento"];
$IdEmpresa = $_POST["IdEmpresa"];
$IdCliente = $_POST["IdCliente"];
$IdEstado = $_POST["IdEstado"];
$IdPeriodicidad = $_POST["IdPeriodicidad"];
$IdPresupuesto = $_POST["IdPresupuesto"];
$FormaDePago = $_POST["FormaDePago"];
$Titulo = $_POST["Titulo"];
$Texto = $_POST["Texto"];
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
$requete = "INSERT INTO `Facturas` (`Titulo`";
if ($SerieFactura!="") $requete.=",`SerieFactura`";
if ($NumeroFactura!="") $requete.=",`NumeroFactura`";
if ($Fecha!="") $requete.=",`Fecha`";
if ($Vencimiento!="") $requete.=",`Vencimiento`";
if ($IdEmpresa!="") $requete.=",`IdEmpresa`";
if ($IdCliente!="") $requete.=",`IdCliente`";
if ($IdEstado!="") $requete.=",`IdEstado`";
if ($IdPeriodicidad!="") $requete.=",`IdPeriodicidad`";
if ($IdPresupuesto!="") $requete.=",`IdPresupuesto`";
if ($Texto!="") $requete.=",`Texto`";
if ($FormaDePago!="") $requete.=",`FormaDePago`";
$requete.=") VALUES ('".$Titulo."'";
if ($SerieFactura!="") $requete.=",'".$SerieFactura."'";
if ($NumeroFactura!="") $requete.=",'".$NumeroFactura."'";
if ($Fecha!="") $requete.=",'".$Fecha."'";
if ($Vencimiento!="") $requete.=",'".$Vencimiento."'";
if ($IdEmpresa!="") $requete.=",".$IdEmpresa;
if ($IdCliente!="") $requete.=",".$IdCliente;
if ($IdEstado!="") $requete.=",".$IdEstado;
if ($IdPeriodicidad!="") $requete.=",".$IdPeriodicidad;
if ($IdPresupuesto!="") $requete.=",".$IdPresupuesto;
if ($Texto!="") $requete.=",'".$Texto."'";
if ($FormaDePago!="") $requete.=",".$FormaDePago;
$requete.= ");";
mysqli_query($db,$requete);
$IdFactura = mysqli_insert_id($db);
for($i=0;$i<count($_POST["TextoLinea"]);$i++)
{
	if (($_POST["TextoLinea"][$i]!="")&&($_POST["BaseImponible"][$i]!="")&&($_POST["IVA"][$i]!="")&&($_POST["Porcentaje"][$i]!=""))
	{
		$tempValor = ($_POST["BaseImponible"][$i] * $_POST["Porcentaje"][$i]) / 100;
		$requete = "INSERT INTO `FacturasLineas` (`IdFactura`,`Texto`,`BaseImponible`,`Impuesto`) VALUES (".$IdFactura.",'".$_POST["TextoLinea"][$i]."','".$tempValor."','".$_POST["IVA"][$i]."');";		
		mysqli_query($db,$requete);
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=facturas&pagina=".$pagina);
?>