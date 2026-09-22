<?php
$seccion = $_POST["seccion"];
$contenido = $_POST["contenido"];
$ruta = $_POST["ruta"];
$seccion = $_POST["seccion"];
$borrar=$_POST["borrar"];
$cancelar=$_POST["cancelar"];
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

if ($cancelar)
{
	if ($seccion!="")
	{
		header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
	else 
	{
		header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`='".$contenido."'";

$listado = mysqli_fetch_object($result);
$requete = "DELETE FROM `Publicaciones` WHERE `IdContenido`='".$contenido."' AND `IdSeccion`='".$seccion."'";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
if ($seccion!="")
{
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else 
{
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>