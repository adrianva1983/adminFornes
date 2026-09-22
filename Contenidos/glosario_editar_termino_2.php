<?php
//VERSIÓN: v1.1 2014-03-19
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$id = $_POST["id"];
$pagina = $_POST["pagina"];
$Termino = $_POST["Termino"];
$Descripcion = $_POST["Descripcion"];
$Url = $_POST["Url"]; 
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/glosario_anadir_termino-".$_SESSION['idioma'].".conf");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$traduccionCaracteres = array("'"=> "\'", "\""=> "\\\"");
$Termino = strtr($Termino, $traduccionCaracteres);
$requete = "UPDATE `Glosario` SET `Termino` = '".$Termino."', `Descripcion`='".$Descripcion."', `Url`='".$Url."' WHERE `Id`=".$id;
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el directorio en curso
$url_retorno = "/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=glosario";
if ($pagina!="") $url_retorno.="&pagina=".$pagina;
header("Location:".$url_retorno);
?>