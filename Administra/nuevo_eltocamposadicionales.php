<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Buscamos el orden que le corresponde
$requete = "SELECT MAX(Orden) FROM CamposAdicionalesGrupos WHERE Tipo = '".$tipo."';";

if ($result = mysqli_query($db, $requete))
{
 $row = mysql_fetch_row($result);
 $Orden=$row[0];
}
$Orden++;
//Introducimos en la base de datos
$requete = "INSERT INTO `CamposAdicionalesGrupos` (`Tipo`, `Titulo`, `Orden`) VALUES ('".$tipo."', '".$Titulo."', '".$Orden."');";
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el directorio en curso
header("Location:/administra/Interface/herramienta.php?modulo=Administra&herramienta=grupo_camposadicionales&tipo=".$tipo);
?>