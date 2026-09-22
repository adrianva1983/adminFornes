<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$origen = $_POST["origen"];
$pagina = $_POST["pagina"];
$IdFamilia = $_POST["IdFamilia"];
$IdRepresentante = $_POST["IdRepresentante"];
$IdCliente = $_POST["IdCliente"];
$NombreEmpresa = $_POST["NombreEmpresa"];
$Nombre = $_POST["Nombre"];
$Email = $_POST["Email"];
$IdFamilia = $_POST["IdFamilia"];
$Telefono = $_POST["Telefono"];
$TituloSolicitud = $_POST["TituloSolicitud"];
$DescripcionSolicitud = $_POST["Solicitud"];
$TituloEnvio = $_POST["TituloEnvio"];
$TextoEnvio = $_POST["TextoEnvio"];
$FechaEnvio = $_POST["FechaEnvio"];
$IdEstado = $_POST["IdEstado"];
$Fecha = $_POST["Fecha"];
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

$requete = "INSERT INTO `Presupuestos` (`Fecha`";
if ($IdFamilia!="") $requete.=",`IdFamilia`";
if ($IdRepresentante!="") $requete.=",`IdRepresentante`";
if ($IdCliente!="") $requete.=",`IdCliente`";
if ($NombreEmpresa!="") $requete.=",`NombreEmpresa`";
if ($Nombre!="") $requete.=",`Nombre`";
if ($Email!="") $requete.=",`Email`";
if ($Telefono!="") $requete.=",`Telefono`";
if ($TituloSolicitud!="") $requete.=",`TituloSolicitud`";
if ($DescripcionSolicitud!="") $requete.=",`Solicitud`";
if ($TituloEnvio!="") $requete.=",`TituloEnvio`";
if ($TextoEnvio!="") $requete.=",`TextoEnvio`";
if ($FechaEnvio!="") $requete.=",`FechaEnvio`";
if ($IdEstado!="") $requete.=",`IdEstado`";
if ($Notas!="") $requete.=",`Notas`";
$requete.=") VALUES ('".$Fecha."'";
if ($IdFamilia!="") $requete.=",".$IdFamilia;
if ($IdRepresentante!="") $requete.=",".$IdRepresentante;
if ($IdCliente!="") $requete.=",".$IdCliente;
if ($NombreEmpresa!="") $requete.=",'".$NombreEmpresa."'";
if ($Nombre!="") $requete.=",'".$Nombre."'";
if ($Email!="") $requete.=",'".$Email."'";
if ($Telefono!="") $requete.=",'".$Telefono."'";
if ($TituloSolicitud!="") $requete.=",'".$TituloSolicitud."'";
if ($DescripcionSolicitud!="") $requete.=",'".$Solicitud."'";
if ($TituloEnvio!="") $requete.=",'".$TituloEnvio."'";
if ($TextoEnvio!="") $requete.=",'".$TextoEnvio."'";
if ($FechaEnvio!="") $requete.=",'".$FechaEnvio."'";
if ($IdEstado!="") $requete.=",".$IdEstado;
if ($Notas!="") $requete.=",'".$Notas."'";
$requete.= ");";
mysqli_query($db,$requete);
$IdPresupuesto = mysqli_insert_id($db);

for($i=0;$i<count($_POST["Texto"]);$i++)
{
	if (($_POST["Texto"][$i]!="")&&($_POST["BaseImponible"][$i]!="")&&($_POST["IVA"][$i]!=""))
	{
		$requete = "INSERT INTO `PresupuestosLineas` (`IdPresupuesto`,`Texto`,`BaseImponible`,`Impuesto`) VALUES (".$IdPresupuesto.",'".$_POST["Texto"][$i]."','".$_POST["BaseImponible"][$i]."','".$_POST["IVA"][$i]."');";
		mysqli_query($db,$requete);
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
switch ($origen)
{
	case "clientes":
		header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$pagina);
		break;
	case "presupuestos":
		header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&pagina=".$pagina);
		break;
	default:
		header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&pagina=".$pagina);
		break;
}
?>