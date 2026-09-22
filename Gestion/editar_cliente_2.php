<?php
//VERSIÓN: v1.0 2014-4-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdFamilia = $_POST["IdFamilia"];
$IdRepresentante = $_POST["IdRepresentante"];
$CIF = $_POST["CIF"];
$Email = $_POST["Email"];
$Telefono = $_POST["Telefono"];
$IBAN = $_POST["IBAN"];
$CuentaBanco = $_POST["CuentaBanco"];
$CuentaSucursal = $_POST["CuentaSucursal"];
$CuentaDigitoControl = $_POST["CuentaDigitoControl"];
$CuentaNumero = $_POST["CuentaNumero"];
$Poblacion = $_POST["Poblacion"];
$Municipio = $_POST["Municipio"];
$Provincia = $_POST["Provincia"];
$CP = $_POST["CP"];
$Direccion = $_POST["Direccion"];
$Notas = $_POST["Notas"];
$Web = $_POST["Web"];
$Login = $_POST["Login"];
$Pass = $_POST["Pass"];
$NombreComercial = $_POST["NombreComercial"];
$DenominacionSocial = $_POST["DenominacionSocial"];
$Referencia = $_POST["Referencia"];
$Tipo = $_POST["Tipo"];
$Estado = $_POST["Estado"];
$Id= $_POST["Id"];
if ($_SESSION['idioma']=="") $Idioma = "ES-ES";
else $Idioma = $_SESSION['idioma'];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_cliente-".$Idioma.".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($Login!=""&&$Pass!="")
{
	$requete = "SELECT * FROM Clientes WHERE Id=".$Id;
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		if ($listado->IdUsuario!="")
		{
			$requete = "UPDATE `Usuarios` SET `Email`='".$Login."', `Password`='".$Pass."',`Passmd5`='".md5($Pass)."' WHERE `Id`=".$listado->IdUsuario;
		}
		else 
		{
			$requete = "INSERT INTO `Usuarios` (`FechaCreacion`,`Nombre`,`Email`,`Activado`,`AltaBoletin`,`AltaSMS`,`Password`,`Passmd5`,`NivelAcceso`, `NombreEmpresa`) VALUES ('".date("Y-m-d h:i:s")."','".$DenominacionSocial."','".$Login."','si','no','no','".$Pass."','".md5($Pass)."',5,'".$DenominacionSocial."');";
		}
	}
	else $requete = "INSERT INTO `Usuarios` (`FechaCreacion`,`Nombre`,`Email`,`Activado`,`AltaBoletin`,`AltaSMS`,`Password`,`Passmd5`,`NivelAcceso`, `NombreEmpresa`) VALUES ('".date("Y-m-d h:i:s")."','".$DenominacionSocial."','".$Login."','si','no','no','".$Pass."','".md5($Pass)."',5,'".$DenominacionSocial."');";
	mysqli_query($db,$requete);
}
$requete = "UPDATE `Clientes` SET `DenominacionSocial`='".$DenominacionSocial."'";
if ($NombreComercial!="") $requete.=",`NombreComercial` = '".$NombreComercial."'";
else $requete.= ",`NombreComercial`=NULL";
if ($IdRepresentante!="") $requete.=",`IdRepresentante` = ".$IdRepresentante;
else $requete.= ",`IdRepresentante`=NULL";
if ($IdFamilia!="") $requete.= ",`IdFamilia`=".$IdFamilia;
else $requete.= ",`IdFamilia`=NULL";
if ($Email!="") $requete.= ",`Email`='".$Email."'";
else $requete.= ",`Email`=NULL";
if ($Telefono!="") $requete.=",`Telefono`='".$Telefono."'";
else $requete.=",`Telefono`=NULL";
if ($CIF!="") $requete.= ",`CIF`='".$CIF."'";
else $requete.= ",`CIF`=NULL";
if ($IBAN!="") $requete.= ",`IBAN`='".$IBAN."'";
else $requete.= ",`IBAN`=NULL";
if ($CuentaBanco!="") $requete.= ",`CuentaBanco`='".$CuentaBanco."'";
else $requete.= ",`CuentaBanco`=NULL";
if ($CuentaSucursal!="") $requete.= ",`CuentaSucursal`='".$CuentaSucursal."'";
else $requete.= ",`CuentaSucursal`=NULL";
if ($CuentaDigitoControl!="") $requete.= ",`CuentaDigitoControl`='".$CuentaDigitoControl."'";
else $requete.= ",`CuentaDigitoControl`=NULL";
if ($CuentaNumero!="") $requete.= ",`CuentaNumero`='".$CuentaNumero."'";
else $requete.= ",`CuentaNumero`=NULL";
if ($Direccion!="") $requete.= ",`Direccion`='".$Direccion."'";
else $requete.= ",`Direccion`=NULL";
if ($Poblacion!="") $requete.= ",`Poblacion`='".$Poblacion."'";
else $requete.= ",`Poblacion`=NULL";
if ($Municipio!="") $requete.= ",`Municipio`='".$Municipio."'";
else $requete.= ",`Municipio`=NULL";
if ($Provincia!="") $requete.= ",`Provincia`='".$Provincia."'";
else $requete.= ",`Provincia`=NULL";
if ($CP!="") $requete.= ",`CP`='".$CP."'";
else $requete.= ",`CP`=NULL";
if ($Web!="") $requete.= ",`Web`='".$Web."'";
else $requete.= ",`Web`=NULL";
if ($Notas!="") $requete.= ",`Notas`='".$Notas."'";
else $requete.= ",`Notas`=NULL";
if ($Estado!="") $requete.=",`IdEstado`=".$Estado;
else $requete.=",`IdEstado`=NULL";
if ($Tipo!="") $requete.=",`IdTipo`=".$Tipo;
else $requete.=",`IdTipo`=NULL";
if ($Referencia!="") $requete.=",`Referencia`='".$Referencia."'";
else $requete.=",`Referencia`=NULL";
$requete.=" WHERE `Id`='".$Id."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$pagina);
?>