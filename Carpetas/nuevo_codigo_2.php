<?php
//VERSIÓN: v1.0 2014-05-15
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_POST["seccion"];
$ruta = $_POST["ruta"];
$codigoIdioma = $_POST["codigoIdioma"];
$Breve = $_POST["Breve"];
$Fichero = $_POST["Fichero"];
$Titulo = $_POST["Titulo"];
$Idioma = $_POST["Idioma"];
//Introducimos el Codigo en la base de datos
//IdPadre? Redireccionar?
require("../Interface/conexion.php");
//Miramos el idioma principal
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
{
$listado = mysqli_fetch_object($result);
$idiomaPrincipal = $listado->Codigo;
}

$requete = "INSERT INTO `Contenidos` (`Fecha`,`FechaComienzo`, `Titulo`, `Breve`, `NomFich`, `Tipo`";
$requete .= ", `Idioma`";
$requete .= ") VALUES ('".date("Y-m-d")."', '".date("Y-m-d")."', '".$Titulo."', '".$Breve."', '".$Fichero."', 'codigo'";
if ($codigoIdioma!="") $requete .= ", '".$codigoIdioma."'";
else $requete .= ", '".$idiomaPrincipal."'";
$requete .= ");";
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

$requete = "INSERT INTO `Publicaciones` (`IdContenido`,`IdSeccion`,`Orden`) VALUES ('".$IdContenido."', '".$seccion."', '".$Orden."');";
mysqli_query($db,$requete);

require("../Interface/cierre.php");

//Recargamos el directorio en curso
if (isset($seccion)){
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else {
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>