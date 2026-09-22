<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Introducimos la asignación en la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($Grupo=="todos") $requete = "SELECT * FROM `SeccionesBusqueda` WHERE `IdSeccion`='".$seccion."' AND `IdGrupo` IS NULL;";
else $requete = "SELECT * FROM `SeccionesBusqueda` WHERE `IdSeccion`='".$seccion."' AND `IdGrupo`='".$Grupo."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	//No se mete en el sistema porque ya está.
}
else
{
	if ($Grupo=="todos") $requete = "INSERT INTO `SeccionesBusqueda` (`IdSeccion`) VALUES ('".$seccion."');";
	else $requete = "INSERT INTO `SeccionesBusqueda` (`IdSeccion`, `IdGrupo`) VALUES ('".$seccion."', '".$Grupo."');";
	mysqli_query($db,$requete);
}
//Recargamos el directorio en curso
if (isset($seccion)){
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
?>