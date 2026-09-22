<?php
//VERSIÓN: v1.0 2014-03-25
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Nick = $_POST["Nick"];
$Nombre = $_POST["Nombre"];
$Apellidos = $_POST["Apellidos"];
$Password = $_POST["Password"];
$Activado = $_POST["Activado"];
$Direccion = $_POST["Direccion"];
$Ciudad = $_POST["Ciudad"];
$Municipio = $_POST["Municipio"];
$CP = $_POST["CP"];
$Provincia = $_POST["Provincia"];
$Email = $_POST["Email"];
$Telefono = $_POST["Telefono"];
$Movil = $_POST["Movil"];
$NombreEmpresa = $_POST["NombreEmpresa"];
$CIF = $_POST["CIF"];
$RedirigirLogin = $_POST["RedirigirLogin"];
$FechaCaducidad = $_POST["FechaCaducidad"];
$grupo = $_POST["grupo"];
$referencia = $_POST["referencia"];
$IdUsuario = $_POST["IdUsuario"];
$FechaNacimiento = $_POST["FechaNacimiento"];
$Foto = $_FILES["Foto"];
$Password = $_POST["Password"];
$NivelAcceso = $_POST["NivelAcceso"];
$Idioma = $_POST["Idioma"];
$Activado = $_POST["Activado"];
$AltaBoletin = $_POST["AltaBoletin"];
$AltaSMS = $_POST["AltaSMS"];
$ExclusivoMailing=$_POST["ExclusivoMailing"];
if ($_POST['TieneTareas']=='si') $TieneTareas = 1;
else $TieneTareas = 0;
if ($_POST['FamiliaPresupuestos']=='si') $FamiliaPresupuestos = 1;
else $FamiliaPresupuestos = 0;
if ($_POST['RepresentantePresupuestos']=='si') $RepresentantePresupuestos = 1;
else $RepresentantePresupuestos = 0;

//CONEXIÓN
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "INSERT INTO `Usuarios` (`Nombre`, `Apellidos`, `FechaCreacion`, `IntroducidoPor`, `NivelAcceso`, `Password`, `Passmd5`, `Idioma`, `Activado`, `AltaBoletin`, `AltaSMS`,`ExclusivoMailing`,`TieneTareas`,`FamiliaPresupuestos`,`RepresentantePresupuestos`";
if ($Nick!="") $requete.=",`Nick`";
if ($Direccion!="") $requete.=",`Direccion`";
if ($Ciudad!="") $requete.=",`Ciudad`";
if ($Municipio!="") $requete.=",`Municipio`";
if ($CP!="") $requete.=",`CP`";
if ($Provincia!="") $requete.=",`Provincia`";
if ($Email!="") $requete.=",`Email`";
if ($Telefono!="") $requete.=",`Telefono`";
if ($Movil!="") $requete.=",`Movil`";
if ($NombreEmpresa!="") $requete.=",`NombreEmpresa`";
if ($CIF!="") $requete.=",`CIF`";
if ($RedirigirLogin!="") $requete.=",`RedirigirLogin`";
if ($FechaCaducidad!="") $requete.=", `FechaCaducidad`";
if ($FechaNacimiento!="") $requete.=", `FechaNacimiento`";
$requete.=") VALUES (";
$requete.="'".$Nombre."', '".$Apellidos."','".date("Y-m-d h:i:s")."',".$IdUsuario;
if ($NivelAcceso!="") $requete.=", '".$NivelAcceso."'";
else $requete.=", '6'";
if ($Password!="") $requete.=", '".$Password."', '".md5($Password)."'";
else $requete.=",'',''";
if ($Idioma!="") $requete.=", '".$Idioma."'";
else $requete.=", 'ES-ES'";
if ($Activado=="si") $requete.=", 'si'";
else $requete.=", 'no'";
if ($AltaBoletin=="si") $requete.=", 'si'";
else $requete.=", 'no'";
if ($AltaSMS=="si") $requete.=", 'si'";
else $requete.=", 'no'";
if ($ExclusivoMailing=="si") $requete.=", 'si'";
else $requete.=", 'no'";
$requete.=",".$TieneTareas.",".$FamiliaPresupuestos.",".$RepresentantePresupuestos;
if ($Nick!="") $requete.=",'".$Nick."'";
if ($Direccion!="") $requete.=",'".$Direccion."'";
if ($Ciudad!="") $requete.=",'".$Ciudad."'";
if ($Municipio!="") $requete.=",'".$Municipio."'";
if ($CP!="") $requete.=",'".$CP."'";
if ($Provincia!="") $requete.=",'".$Provincia."'";
if ($Email!="") $requete.=",'".$Email."'";
if ($Telefono!="") $requete.=",'".$Telefono."'";
if ($Movil!="") $requete.=",'".$Movil."'";
if ($NombreEmpresa!="") $requete.=",'".$NombreEmpresa."'";
if ($CIF!="") $requete.=",'".$CIF."'";
if ($RedirigirLogin!="") $requete.=",'".$RedirigirLogin."'";
if ($FechaCaducidad!="") $requete.=",'".$FechaCaducidad."'";
if ($FechaNacimiento!="") $requete.=",'".$FechaNacimiento."'";
$requete.=");";
mysqli_query($db,$requete);
$IdUsuarioCreado = mysqli_insert_id($db);
if ($Foto['tmp_name']!="")
{
	//Subimos el Fichero
	$NomFicherin = $IdUsuarioCreado;
	switch ($Foto['type']) 
	{
		case "image/pjpeg":
		case "image/jpeg":			
			$NomFicherin .= ".jpg";
			break;
		case "image/gif":
			$NomFicherin .= ".gif";
			break;
		case "x-png":
		case "image/x-png":
		case "image/png":
			$NomFicherin .= ".png";
			break;
	}	
	$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/Perfiles/".$NomFicherin, "x");
	$localfile = file_get_contents($Foto['tmp_name']);
	fwrite($ficherin, $localfile);
	fclose($ficherin);
	list($width, $height) = getimagesize($Foto['tmp_name']);
	$NomFoto = $NomFicherin;
	$requete = "UPDATE `Usuarios` SET `Foto`='".$NomFicherin."' WHERE `Id`=".$IdUsuarioCreado;
	mysqli_query($db,$requete);
}
if ($grupo!="")
{
	//Se añade el usuario al grupo	
	$requete = "INSERT INTO `PertenenciaGrupos` (`IdUsuario`,`IdGrupo`) VALUES ('".$IdUsuarioCreado."','".$grupo."');";
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
switch ($referencia) 
{
	case "usuarios":
		header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios");
		break;
	case "usuarios2":
		header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios");
		break;
	case "usuarios_grupos":
		header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos&grupo=".$grupo);
		break;
	case "usuarios_grupos2":
		header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos&grupo=".$grupo);
		break;
}
?>