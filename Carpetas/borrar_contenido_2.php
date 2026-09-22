<?php
//VERSIÓN: v1.0 2014-02-11
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_POST["seccion"];
$contenido = $_POST["contenido"];
$ruta = $_POST["ruta"];
$seccion = $_POST["seccion"];
$borrar=$_POST["borrar"];
$cancelar=$_POST["cancelar"];
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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

if ($cancelar)
{
	if ($seccion!=""){
	   header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
	else {
	   header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`='".$contenido."'";

$listado = mysqli_fetch_object($result);
if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$listado->NomFich.".php")) unlink($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$listado->NomFich.".php");
//Borramos sus ampliaciones
$requete = "SELECT `Contenidos`.Id,`Contenidos`.Tipo FROM `Contenidos`,`Publicaciones` WHERE `IdAmpliacion`='".$contenido."' AND `Publicaciones`.IdContenido=`Contenidos`.Id";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	while ($listado = mysqli_fetch_object($result))
	{
		if ($listado->Tipo=="foro") //Si es foro tengo que borrar la tabla de mensajes y configuración relacionadas
		{
			$requete = "DELETE FROM `ForoMensajes` WHERE `IdForo`='".$listado->Id."'";
			mysqli_query($db,$requete);		
			$requete = "DELETE FROM `ForoConfiguracion` WHERE `Id`='".$listado->Id."'";
			mysqli_query($db,$requete);		
		}
		$requete = "DELETE FROM `Contenidos` WHERE `Id`='".$listado->Id."'";
		mysqli_query($db,$requete);	
	}
}
$requete = "DELETE FROM `Publicaciones` WHERE `IdAmpliacion`='".$contenido."'";
mysqli_query($db,$requete);
//Borramos sus publicaciones
$requete = "DELETE FROM `Publicaciones` WHERE `IdContenido`='".$contenido."'";
mysqli_query($db,$requete);
//Borramos campos adicionales
$requete = "DELETE FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."'";
mysqli_query($db,$requete);
//Borramos el contenido en si
$requete = "DELETE FROM `Contenidos` WHERE `Id`='".$contenido."'";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
if ($seccion!=""){
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else {
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>
