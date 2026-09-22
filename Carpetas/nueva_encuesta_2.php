<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Introducimos la Encuesta en la base de datos
$requete = "INSERT INTO `Contenidos` (`Fecha`, `FechaFin`, `Titulo`, `Breve`, `IdPropietario`, `Tipo`) VALUES ('".date("Y-m-d")."', '".$Fecha."', '".$Titulo."', '".$Breve."', '".$IdUsuario."', 'encuesta');";
mysqli_query($db,$requete);

//Introducimos la publicación de la Encuesta en la sección
$requete = "SELECT MAX(Id) FROM Contenidos;";

$row = mysql_fetch_row($result);
$IdContenido = $row[0];
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
if (isset($seccion)){
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
}
else {
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>