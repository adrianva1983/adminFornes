<?php
$ruta= $_GET["ruta"];
$seccion= $_GET["seccion"];
$pagina= $_GET["pagina"];
$tipocontenido= $_GET["tipocontenido"];
$contenido= $_GET["contenido"];
$pasada=$_GET["pasada"];
$Id = $_GET["Id"];
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($contenido!="") $requete = "UPDATE `Publicaciones` SET `Visibilidad` = '$pasada' WHERE (`IdContenido` =$Id AND `IdAmpliacion`=$contenido);";
else $requete = "UPDATE `Publicaciones` SET `Visibilidad` = '$pasada' WHERE (`IdContenido` =$Id AND `IdSeccion`=$seccion);";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso o el contenido en curso
if ($contenido!="") header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
else
{
	if (isset($seccion)){
		if ($pagina!="") header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&pagina=".$pagina);
		else header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
	else {
		header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}
}
?>