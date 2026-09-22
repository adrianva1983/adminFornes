<?php
//VERSIÓN: v1.1 2014-07-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Idioma = $_POST["Idioma"];
$origen = $_POST["origen"];
$IdContacto = $_POST["Id"];
$IdCliente = $_POST["IdCliente"];
$Nombre = $_POST["Nombre"];
$Apellidos = $_POST["Apellidos"];
$Cargo = $_POST["Cargo"];
$Email = $_POST["Email"];
$Movil = $_POST["Movil"];
$IdRepresentante = $_POST["IdRepresentante"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_contacto-".$Idioma.".conf");

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Contactos` SET `Nombre`='".$Nombre."'";
if ($Apellidos!="") $requete.=",`Apellidos` = '".$Apellidos."'";
if ($Email!="") $requete.=",`Email` = '".$Email."'";
if ($Movil!="") $requete.=",`Movil` = '".$Movil."'";
if ($Cargo!="") $requete.=",`Cargo` = '".$Cargo."'";
if ($IdRepresentante!="") $requete.=",`IdRepresentante` = '".$IdRepresentante."'";
$requete.=" WHERE `Id`='".$IdContacto."';";
print $requete;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=contactos_cliente&Id=".$IdCliente);
?>