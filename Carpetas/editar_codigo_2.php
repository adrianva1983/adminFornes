<?php
$contenido = $_POST["contenido"];
$seccion = $_POST["seccion"];
$ruta = $_POST["ruta"];
$IdContenido = $_POST["IdContenido"];
$Fichero = $_POST["Fichero"];
$Breve = $_POST["Breve"];
$Titulo = $_POST["Titulo"];
//Introducimos el Codigo en la base de datos
//IdPadre? Redireccionar?
require("../Interface/conexion.php");
$requete = "UPDATE `Contenidos` SET `Fecha`= '".date("Y-m-d")."', `Titulo` = '".$Titulo."', `Breve` = '".$Breve."', `NomFich` ='".$Fichero."' WHERE `Id`='".$IdContenido."'";
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