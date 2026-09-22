<?php
//VERSIÓN: v1.0 2014-05-29
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_POST["ruta"];
$seccion = $_POST["seccion"];
$referenciaIdioma = $_POST["referenciaIdioma"];
$codigoIdioma = $_POST["codigoIdioma"];
$rutaOrigen = $_POST["rutaOrigen"];
$seccionOrigen = $_POST["seccionOrigen"];
$herramientaOrigen = $_POST["herramientaOrigen"];
$PlantillaFormulario = $_POST["PlantillaFormulario"];
$Titulo = $_POST["Titulo"];
$Email = $_POST["Email"];
$IdUsuario = $_POST["IdUsuario"];
$Idioma = $_POST["Idioma"];
$editar = $_POST["editar"];
$contenido =$_POST["contenido"];
$eliminar = $_POST["eliminar"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Miramos el idioma principal
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$idiomaPrincipal = $listado->Codigo;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_formulario-".$Idioma.".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}

if ($editar!="")
{
	//Introducimos el Contenido (Formulario) en la base de datos
	$requete = "UPDATE `Contenidos` SET `FechaModificacion`='".date("Y-m-d")."', `Titulo`='".$Titulo."', `Breve`='".$Email."', `IdTipoContenido`=".$PlantillaFormulario.", `IdPropietario`=".$IdUsuario.", `Idioma`=";
	if ($codigoIdioma!="") 
	{
		$requete .= "'".$codigoIdioma."'";
	}
	else 
	{
		$requete .= "'".$idiomaPrincipal."'";
	}
	$requete.= " WHERE `Id`=".$contenido;
	mysqli_query($db,$requete);
}
if ($eliminar!="")
{
	//Eliminamos el formulario
	$requete = "DELETE FROM `Publicaciones` WHERE `IdContenido`=".$contenido;
	mysqli_query($db,$requete);
	$requete = "DELETE FROM `Contenidos` WHERE `Id`=".$contenido;
	mysqli_query($db,$requete);
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el directorio en curso
if (($seccion!="")&&($codigoIdioma=="")){
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
}
else {
	if ($herramientaOrigen=="raiz") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	if ($codigoIdioma!="") header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccionOrigen."&ruta=".$rutaOrigen."&tipocontenido=".$tipocontenido);
	else header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>