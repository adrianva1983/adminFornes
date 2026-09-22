<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require("../../Interface/conexion.php");
$requete_actualiza = "UPDATE `guiarestaurantes_reservas` SET `Estado` = 'Borrado' WHERE `Id` =".$Id.";";	
mysql_query($requete_actualiza,$db);
require("../../Interface/cierre.php");
//Recargamos la herramienta en curso
switch ($origen)
{
  case "reservas":
		header("Location:../../Interface/herramienta.php?modulo=Comercio&herramienta=reservas&NumPagina=".$NumPagina."&pagina=".$pagina);
		break;
}
?>