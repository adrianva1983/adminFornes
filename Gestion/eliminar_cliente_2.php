<?php
//VERSIÓN: v1.1 2013-11-13
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_POST["Id"];
$Idioma = $_POST["Idioma"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/eliminar_cliente-".$Idioma.".conf");
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print $lang["errorPermisos"];
	exit;
}

if ($_SERVER['HTTP_REFERER'] == "")
{
	die ($lang["accesoIncorrecto"]);
	exit;
}

if ($cancelar)
{
	header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=clientes");
	exit;
}
else
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete = "DELETE FROM `Clientes` WHERE `Id`=".$Id;
	mysqli_query($db,$requete);
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	//Recargamos el directorio en curso
	header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=clientes");
}
?>