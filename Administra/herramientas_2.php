<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "<p class=\"mensajeKO\">No tiene permisos para acceder a este &aacute;rea</p>";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
$herramientas = $_POST['herramientas'];

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$requete = "UPDATE `Herramientas` SET Activado='no'";
mysqli_query($db,$requete);
for ($i=0;$i<count($herramientas);$i++)
{
	$requete = "UPDATE `Herramientas` SET Activado='si' WHERE `IdHerramienta`=".$herramientas[$i];	
	mysqli_query($db,$requete);	
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
header("Location:../Interface/herramienta.php?modulo=Administra&herramienta=herramientas");
?>
