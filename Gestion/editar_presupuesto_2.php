<?php
//VERSIÓN: v1.0 2014-5-21
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_POST["Id"];
$Fecha = $_POST["Fecha"];
$IdRepresentante = $_POST["IdRepresentante"];
$IdFamilia = $_POST["IdFamilia"];
$IdCliente = $_POST["IdCliente"];
$NombreEmpresa = $_POST["NombreEmpresa"];
$Nombre = $_POST["Nombre"];
$Email = $_POST["Email"];
$Telefono = $_POST["Telefono"];
$TituloSolicitud = $_POST["TituloSolicitud"];
$Solicitud = $_POST["Solicitud"];
$TituloEnvio = $_POST["TituloEnvio"];
$TextoEnvio = $_POST["TextoEnvio"];
$FechaEnvio = $_POST["FechaEnvio"];
$IdEstado = $_POST["IdEstado"];
$Notas = $_POST["Notas"];
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
$requete = "UPDATE `Presupuestos` SET `Fecha`='".$Fecha."'";
if ($IdRepresentante!="") $requete.=",`IdRepresentante` = ".$IdRepresentante;
else $requete.= ",`IdRepresentante`=NULL";
if ($IdFamilia!="") $requete.= ",`IdFamilia`=".$IdFamilia;
else $requete.= ",`IdFamilia`=NULL";
if ($IdCliente!="") $requete.= ",`IdCliente`=".$IdCliente;
else $requete.= ",`IdCliente`=NULL";
if ($NombreEmpresa!="") $requete.= ",`NombreEmpresa`='".$NombreEmpresa."'";
else $requete.= ",`NombreEmpresa`=NULL";
if ($Nombre!="") $requete.= ",`Nombre`='".$Nombre."'";
else $requete.= ",`Nombre`=NULL";
if ($Email!="") $requete.= ",`Email`='".$Email."'";
else $requete.= ",`Email`=NULL";
if ($Telefono!="") $requete.=",`Telefono`='".$Telefono."'";
else $requete.=",`Telefono`=NULL";
if ($TituloSolicitud!="") $requete.=",`TituloSolicitud`='".$TituloSolicitud."'";
else $requete.=",`TituloSolicitud`=NULL";
if ($Solicitud!="") $requete.=",`Solicitud`='".$Solicitud."'";
else $requete.=",`Solicitud`=NULL";
if ($TituloEnvio!="") $requete.=",`TituloEnvio`='".$TituloEnvio."'";
else $requete.=",`TituloEnvio`=NULL";
if ($TextoEnvio!="") $requete.=",`TextoEnvio`='".$TextoEnvio."'";
else $requete.=",`TextoEnvio`=NULL";
if ($FechaEnvio!="") $requete.=",`FechaEnvio`='".$FechaEnvio."'";
else $requete.=",`FechaEnvio`=NULL";
if ($IdEstado!="") $requete.= ",`IdEstado`=".$IdEstado;
else $requete.= ",`IdEstado`=NULL";
if ($Notas!="") $requete.= ",`Notas`='".$Notas."'";
else $requete.= ",`Notas`=NULL";
$requete.=" WHERE `Id`='".$Id."';";
mysqli_query($db,$requete);
//Consultamos las líneas de presupuesto
$requete2 = "SELECT * FROM `PresupuestosLineas` WHERE `IdPresupuesto`=".$Id;

$i=0;
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		$lineas_texto[$i] = $listado2->Texto;
		$lineas_baseimponible[$i] = $listado2->BaseImponible;
		$lineas_impuesto[$i] = $listado2->Impuesto;
		$lineas_id[$i] = $listado2->Id;
		$i++;
	}
}
for($i=0;$i<count($_POST["TextoLinea"]);$i++)
{
	if (($_POST["IdLinea"][$i]!="")&&($_POST["TextoLinea"][$i]!="")&&($_POST["BaseImponible"][$i]!="")&&($_POST["IVA"][$i]!=""))
	{
		$requete = "UPDATE `PresupuestosLineas` SET `Texto`='".$_POST["TextoLinea"][$i]."',`BaseImponible`='".$_POST["BaseImponible"][$i]."',`Impuesto`='".$_POST["IVA"][$i]."' WHERE `Id`=".$_POST["IdLinea"][$i];
	}
	else
	{
		if (($_POST["TextoLinea"][$i]!="")&&($_POST["BaseImponible"][$i]!="")&&($_POST["IVA"][$i]!=""))
		{
			$requete = "INSERT INTO `PresupuestosLineas` (`IdPresupuesto`,`Texto`,`BaseImponible`,`Impuesto`) VALUES (".$Id.",'".$_POST["TextoLinea"][$i]."','".$_POST["BaseImponible"][$i]."','".$_POST["IVA"][$i]."');";
		}
	}
	mysqli_query($db,$requete);
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&pagina=".$pagina);
?>