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

//Desasignamos en la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($Grupo!="") $requete = "DELETE FROM `SeccionesBusqueda` WHERE `IdSeccion`='".$seccion."' AND `IdGrupo`='".$Grupo."';";
else $requete = "DELETE FROM `SeccionesBusqueda` WHERE `IdSeccion`='".$seccion."' AND `IdGrupo` IS NULL;";
mysqli_query($db,$requete);
//Recargamos el directorio en curso
if (isset($seccion)){
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=asignar_buscable&seccion=".$seccion."&ruta=".$ruta);
}
?>