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
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_formulario-".$Idioma.".conf");
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
//Introducimos el Contenido (Formulario) en la base de datos
//IdPadre? Redireccionar?
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Miramos el idioma principal
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$idiomaPrincipal = $listado->Codigo;
}
$requete = "INSERT INTO `Contenidos` (`Fecha`, `Titulo`, `Breve`, `IdTipoContenido`, `IdPropietario`, `Tipo`, `Idioma`) VALUES ('".date("Y-m-d")."', '".$Titulo."', '".$Email."', '".$PlantillaFormulario."', '".$IdUsuario."', 'formulario',";
if ($codigoIdioma!="") 
{
	$requete .= "'".$codigoIdioma."'";
}
else 
{
	$requete .= "'".$idiomaPrincipal."'";
}
$requete.=");";
mysqli_query($db,$requete);

//Introducimos la publicación del Contenido en la sección
$IdContenido = mysqli_insert_id($db);

//Buscamos el minimo Orden para introducir este elemento en primer lugar
$requete = "SELECT MIN(Orden) FROM Publicaciones WHERE IdSeccion='".$seccion."';";

if ($result = mysqli_query($db, $requete))
{
 $row = mysql_fetch_row($result);
 $Orden=$row[0];
}
else
{
  $Orden=0;
}
$Orden--;

$requete = "INSERT INTO `Publicaciones` (`IdContenido`,`IdSeccion`,`Orden`,`FechaComienzo`) VALUES ('".$IdContenido."', '".$seccion."', '".$Orden."','".date("Y-m-d")."');";
mysqli_query($db,$requete);

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