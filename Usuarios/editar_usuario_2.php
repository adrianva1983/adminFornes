<?php
//VERSIÓN: v1.1 2014-01-17
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$origen = $_GET["origen"];
$Num_Pagina = $_GET["Num_Pagina"];
$grupo = $_GET["grupo"];
$CamposMostrar = $_GET["CamposMostrar"];
$pagina = $_GET["pagina"];
$usuario = $_POST["usuario"];
$Nick = $_POST["Nick"];
$Nombre = $_POST["Nombre"];
$Apellidos = $_POST["Apellidos"];
$Direccion = $_POST["Direccion"];
$Ciudad = $_POST["Ciudad"];
$Municipio = $_POST["Municipio"];
$Provincia = $_POST["Provincia"];
$CP = $_POST["CP"];
$Pais = $_POST["Pais"];
$Email = $_POST["Email"];
$Telefono = $_POST["Telefono"];
$Movil = $_POST["Movil"];
$Password = $_POST["Password"];
$RedirigirLogin = $_POST["RedirigirLogin"];
$Activado = $_POST["Activado"];
$AltaBoletin = $_POST["AltaBoletin"];
$AltaSMS = $_POST["AltaSMS"];
$ExclusivoMailing = $_POST["ExclusivoMailing"];
$NivelAcceso = $_POST["NivelAcceso"];
$FechaCaducidad = $_POST["FechaCaducidad"];
$NombreEmpresa = $_POST["NombreEmpresa"];
$CIF = $_POST["CIF"];
$BorrarImagen = $_POST["BorrarImagen"];
$BorrarImagenFichero = $_POST["BorrarImagenFichero"];
$FechaNacimiento = $_POST["FechaNacimiento"];
$Foto = $_FILES["Foto"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/nuevo_usuario-".$_SESSION['idioma'].".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	if ($_SESSION['usuario_login']!=$Email)
	{
		print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
		exit;
	}		
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($Foto['tmp_name']!="")
{
	//Subimos el Fichero
	$NomFicherin = $usuario;
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
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/Imagenes/Perfiles/".$NomFicherin)) unlink($_SERVER['DOCUMENT_ROOT']."/Imagenes/Perfiles/".$NomFicherin);
	$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/Perfiles/".$NomFicherin, "x");
	$localfile = file_get_contents($Foto['tmp_name']);
	fwrite($ficherin, $localfile);
	fclose($ficherin);
	list($width, $height) = getimagesize($Foto['tmp_name']);
	$NomFoto = $NomFicherin;
	$requete = "UPDATE `Usuarios` SET `Foto`='".$NomFicherin."' WHERE `Id`=".$IdUsuarioCreado;
	mysqli_query($db,$requete);
}
$requete = "UPDATE `Usuarios` SET `Nick`='".$Nick."', `Nombre`='".$Nombre."', `Apellidos`='".$Apellidos."', `Direccion`='".$Direccion."', `Ciudad`='".$Ciudad."', `Municipio`='".$Municipio."',`Provincia`='".$Provincia."',`CP`='".$CP."', `Pais`='".$Pais."', `Email`='".$Email."', `Telefono`='".$Telefono."', `Movil`='".$Movil."'";
if (isset($Password)&&$Password!="") $requete.=", `Password`='".$Password."', `Passmd5`='".md5($Password)."'";
if ($RedirigirLogin!="") $requete.=", `RedirigirLogin`='".$RedirigirLogin."'";
else $requete.=", `RedirigirLogin`= NULL";
if ($FechaNacimiento!="") $requete.=", `FechaNacimiento`='".$FechaNacimiento."'";
else $requete.= ", `FechaNacimiento`= null";
if ($NomFoto!="") $requete = $requete.", `Foto`='".$NomFoto."'";
else
{
	if (isset($BorrarImagen) && $BorrarImagen !="")
	{
		$requete.= ",`Foto` = NULL";
	}
}
if ($Activado=="si") $requete.=", `Activado`='si'";
if ($AltaBoletin=="si") $requete.=", `AltaBoletin`='si'";
if ($AltaSMS=="si") $requete.=", `AltaSMS`='si'";
if ($ExclusivoMailing=="si") $requete.=", `ExclusivoMailing`='si'";
$requete.=", `NivelAcceso`='".$NivelAcceso."', `FechaCaducidad`='".$FechaCaducidad."', `NombreEmpresa`='".$NombreEmpresa."', `CIF`='".$CIF."' WHERE `Id`='".$usuario."';";
mysqli_query($db,$requete);
//Borramos icono y/o foto si corresponde
if (isset($BorrarImagen) && $BorrarImagen !="")
{	
	if ($BorrarImagenFichero!=""&&file_exists($_SERVER['DOCUMENT_ROOT'].$BorrarImagenFichero)) unlink($_SERVER['DOCUMENT_ROOT'].$BorrarImagenFichero);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos
switch ($origen) 
{
	case "usuarios_grupos":
		$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
		header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina);
		break;
	case "usuarios":
		$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
		header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios2&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina);
		break;
}
?>