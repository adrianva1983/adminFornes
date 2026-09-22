<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$NivelAcceso = $_GET["NivelAcceso"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

$campos=$_GET;
$titulos = array_keys($_GET);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//ACTUALIZO PERMISOS YA  METIDOS
for ($i=0;$i<count($campos);$i++)
{
	if ($campos[$titulos[$i]]!="")
	{
		$tipodecampo = explode("/",$titulos[$i]);
		switch ($tipodecampo[0]) 
		{
			case "CambioNivelGrupo":
				if ($campos[$titulos[$i]]!="Quitar Permisos")
				{
					$requete ="UPDATE `Permisos` SET `Nivel` = '".$campos[$titulos[$i]]."' WHERE `IdGrupoSuscrito` = '".$tipodecampo[1]."' AND `IdSeccion` = '".$seccion."'";
					mysqli_query($db,$requete);
				}
				else
				{
					$requete ="DELETE FROM `Permisos` WHERE `IdGrupoSuscrito` = '".$tipodecampo[1]."' AND `IdSeccion` = '".$seccion."'";
					mysqli_query($db,$requete);
				}
				break;
			case "CambioNivelUsuario":
				if ($campos[$titulos[$i]]!="Quitar Permisos")
				{
					$requete ="UPDATE `Permisos` SET `Nivel` = '".$campos[$titulos[$i]]."' WHERE `IdUsuarioSuscrito` = '".$tipodecampo[1]."' AND `IdSeccion` = '".$seccion."'";
					mysqli_query($db,$requete);
				}
				else
				{
					$requete ="DELETE FROM `Permisos` WHERE `IdUsuarioSuscrito` = '".$tipodecampo[1]."' AND `IdSeccion` = '".$seccion."'";
					mysqli_query($db,$requete);
				}
				break;
		}		
	}
}
//INSERTO NUEVOS PERMISOS DE GRUPOS
for ($i=0;$i<count($campos["grupos"]);$i++)
{
	$requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdGrupoSuscrito`, `InicioSuscripcion`) VALUES ('".$seccion."', '".$NivelAcceso."', '".$campos["grupos"][$i]."', '".date("Y-m-d H:i:s")."');";	
	mysqli_query($db,$requete);
}
//INSERTO NUEVOS PERMISOS DE USUARIOS
for ($i=0;$i<count($campos["usuarios"]);$i++)
{
	$requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdUsuarioSuscrito`, `InicioSuscripcion`) VALUES ('".$seccion."', '".$NivelAcceso."', '".$campos["usuarios"][$i]."', '".date("Y-m-d H:i:s")."');";
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
?>
