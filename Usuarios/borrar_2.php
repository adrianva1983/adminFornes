<?php
//VERSIÓN: v1.0 2014-03-17
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$usuario = $_GET["usuario"];
$referencia = $_GET["referencia"];
$origen = $_GET["origen"];
$Num_Pagina = $_GET["Num_Pagina"];
$CamposMostrar = $_GET["CamposMostrar"];
$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
print_r($CamposMostrar);
$pagina= $_GET["pagina"];
$borrar=$_GET["borrar"];
$cancelar=$_GET["cancelar"];
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/borrar-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print $lang["errorPermisos"];	
	exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ($lang["accesoIncorrecto"]);
	exit;
}

if ($cancelar)
{	
	if ($referencia!="")
	{		
		header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=".$referencia."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."&origen=".$origen."&accion=1");
	}
	exit;
}
else
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete = "DELETE FROM `GuestDatos`WHERE IdUsuario=".$usuario;
	if (mysqli_query($db,$requete)) {}
	$requete = "DELETE FROM `PertenenciaGrupos` WHERE `IdUsuario`='".$usuario."'";
	if (mysqli_query($db,$requete)) {}
	$requete = "DELETE FROM `Usuarios`WHERE Id=".$usuario;
	if (mysqli_query($db,$requete)) {}

	//Recargamos el directorio en curso
	if ($referencia!="")
	{
		header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=".$referencia."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."&origen=".$origen."&accion=1");
	}
}
?>