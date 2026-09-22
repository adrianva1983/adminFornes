<?php
//VERSIÓN: v1.0 2014-5-7
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$id = $_GET["id"];
$pagina = $_GET["pagina"];
//Control de acceso
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `Glosario` WHERE `Id` = ".$id;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
$url_retorno = "/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=glosario";
if ($pagina!="") $url_retorno.= "&pagina=".$pagina;
header("Location:".$url_retorno);

?>