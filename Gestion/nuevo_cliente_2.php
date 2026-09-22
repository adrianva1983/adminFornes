<?php
//VERSIÓN: v1.0 2014-4-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdFamilia = $_POST["IdFamilia"];
$IdRepresentante = $_POST["IdRepresentante"];
$IdUsuario = $_POST["IdUsuario"];
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
$Estado = $_POST["Estado"];
$Tipo = $_POST["Tipo"];
$Referencia = $_POST["Referencia"];
$Proveedor = $_POST["Proveedor"];
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
$errores = "";
if ($Telefono!="")
{
	$requete = "SELECT * FROM Clientes WHERE `Telefono`='".$Telefono."'";
	$result = mysqli_query($db, $requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		$errores.= $lang["errorTelefono"];		
	}
	$requete = "SELECT * FROM Referencia WHERE `Telefono`='".$Referencia."'";
	$result = mysqli_query($db, $requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		$errores.= $lang["errorReferencia"];
	}
}
if ($errores=="")
{
	if (($Login!="")&&($Pass!=""))
	{
		$requete = "INSERT INTO `Usuarios` (`FechaCreacion`,`Nombre`,`Email`,`Activado`,`AltaBoletin`,`AltaSMS`,`Password`,`Passmd5`,`NivelAcceso`, `NombreEmpresa`)";
		$requete .= " VALUES ('".date("Y-m-d h:i:s")."',";
		if ($_POST["Nombre0"]!="") $requete.= "'".$_POST["Nombre0"]."'";
		else $requete.= "'".$DenominacionSocial."'";
		$requete.=",'".$Login."','si','no','no','".$Pass."','".md5($Pass)."',5,'".$DenominacionSocial."');";	
		mysqli_query($db,$requete);
		$IdUsuario = mysqli_insert_id($db);
	}

	$requete = "INSERT INTO `Clientes` (`FechaCreacion`,`DenominacionSocial`";
	if ($IdFamilia!="") $requete.=",`IdFamilia`";
	if ($IdRepresentante!="") $requete.=",`IdRepresentante`";
	if ($CIF!="") $requete.=",`CIF`";
	if ($Email!="") $requete.=",`Email`";
	if ($Telefono!="") $requete.=",`Telefono`";
	if ($IBAN!="") $requete.=",`IBAN`";
	if ($CuentaBanco!="") $requete.=",`CuentaBanco`";
	if ($CuentaSucursal!="") $requete.=",`CuentaSucursal`";
	if ($CuentaDigitoControl!="") $requete.=",`CuentaDigitoControl`";
	if ($CuentaNumero!="") $requete.=",`CuentaNumero`";
	if ($Poblacion!="") $requete.=",`Poblacion`";
	if ($Municipio!="") $requete.=",`Municipio`";
	if ($Provincia!="") $requete.=",`Provincia`";
	if ($CP!="") $requete.=",`CP`";
	if ($Direccion!="") $requete.=",`Direccion`";
	if ($Notas!="") $requete.=",`Notas`";
	if ($Web!="") $requete.=",`Web`";
	if ($Proveedor!="") $requete.=",`Proveedor`";
	if ($NombreComercial!="") $requete.=",`NombreComercial`";
	if (isset($IdUsuario)&&$IdUsuario!="") $requete.=",`IdUsuario`";
	if ($Estado!="") $requete.=",`IdEstado`";
	if ($Tipo!="") $requete.=",`IdTipo`";
	if ($Referencia!="") $requete.=",`Referencia`";
	$requete.=") VALUES ('".date("Y-m-d h:i:s")."','".$DenominacionSocial."'";
	if ($IdFamilia!="") $requete.=",".$IdFamilia;
	if ($IdRepresentante!="") $requete.=",".$IdRepresentante;
	if ($CIF!="") $requete.=",'".$CIF."'";
	if ($Email!="") $requete.=",'".$Email."'";
	if ($Telefono!="") $requete.=",'".$Telefono."'";
	if ($IBAN!="") $requete.=",'".$IBAN."'";
	if ($CuentaBanco!="") $requete.=",'".$CuentaBanco."'";
	if ($CuentaSucursal!="") $requete.=",'".$CuentaSucursal."'";
	if ($CuentaDigitoControl!="") $requete.=",'".$CuentaDigitoControl."'";
	if ($CuentaNumero!="") $requete.=",'".$CuentaNumero."'";
	if ($Poblacion!="") $requete.=",'".$Poblacion."'";
	if ($Municipio!="") $requete.=",'".$Municipio."'";
	if ($Provincia!="") $requete.=",'".$Provincia."'";
	if ($CP!="") $requete.=",'".$CP."'";
	if ($Direccion!="") $requete.=",'".$Direccion."'";
	if ($Notas!="") $requete.=",'".$Notas."'";
	if ($Web!="") $requete.=",'".$Web."'";
	if ($Proveedor!="") $requete.=",".$Proveedor;
	if ($NombreComercial!="") $requete.=",'".$NombreComercial."'";
	if (isset($IdUsuario)&&$IdUsuario!="") $requete.=",".$IdUsuario;
	if ($Estado!="") $requete.=",".$Estado;
	if ($Tipo!="") $requete.=",".$Tipo;
	if ($Referencia!="") $requete.=",'".$Referencia."'";
	$requete.= ");";	
	mysqli_query($db,$requete);
	$IdCliente = mysqli_insert_id($db);
	$i = 0;
	while ($_POST["Nombre".$i]!="")
	{
		$requete = "INSERT INTO `Contactos` (`IdCliente`,`Nombre`";
		if ($_POST["Apellidos".$i]!="") $requete.=",`Apellidos`";
		if ($_POST["Email".$i]!="") $requete.=",`Email`";
		if ($_POST["Movil".$i]!="") $requete.=",`Movil`";
		if ($_POST["Cargo".$i]!="") $requete.=",`Cargo`";
		if ($_POST["IdRepresentante".$i]!="") $requete.=",`IdRepresentante`";	
		$requete.= ") VALUES (".$IdCliente.",'".$_POST["Nombre".$i]."'";
		if ($_POST["Apellidos".$i]!="") $requete.=",'".$_POST["Apellidos".$i]."'";
		if ($_POST["Email".$i]!="") $requete.=",'".$_POST["Email".$i]."'";
		if ($_POST["Movil".$i]!="") $requete.=",'".$_POST["Movil".$i]."'";
		if ($_POST["Cargo".$i]!="") $requete.=",'".$_POST["Cargo".$i]."'";
		if ($_POST["IdRepresentante".$i]!="") $requete.=",".$_POST["IdRepresentante".$i];
		$requete.=");";
		mysqli_query($db,$requete);
		$i++;
	}
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

	//Recargamos el contenido en curso
	 header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=clientes");
}
else
{
	print "<p class=\"mensajeKO\">".$errores."</p>";
	print "<a href=\"../Interface/herramienta.php?modulo=Gestion&herramienta=clientes\">".$lang["volver"]."</a>";
}
?>