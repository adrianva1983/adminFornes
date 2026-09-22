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
$campos=$_POST;
$titulos = array_keys($_POST);
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
for ($i=0;$i<count($campos);$i++)
{
	$requete = "UPDATE `Servidor` SET `Valor` = '".$campos[$titulos[$i]]."' WHERE `Campo` = '".$titulos[$i]."';";
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos la herramienta de colores
header("Location:/administra/Interface/herramienta.php?modulo=Administra&herramienta=colores_aplicacion");
?>