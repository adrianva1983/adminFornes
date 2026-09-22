<?php

//Introducimos el Codigo en la base de datos
//IdPadre? Redireccionar?
require("../Interface/conexion.php");
$requete = "UPDATE `Contenidos` SET `Fecha`= '".date("Y-m-d")."', `Titulo` = '".$Titulo."', `Breve` = 'Modo:".$Modo."/Ampliable:".$Ampliable."/Estado:".$estado."/syncMax:".$syncMax."', `Redireccionar` ='".$Fuente."' WHERE `Id`='".$IdContenido."'";
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