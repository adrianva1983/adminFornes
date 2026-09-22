<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Imprimimos el listado de carpetas
$requete = "SELECT * FROM `Secciones` WHERE `IdPadre` IS NULL ORDER BY `Orden`";

print "<ul>";
while($listado = mysqli_fetch_object($result)){	
	print "<li>";
	print "<img src=\"/administra/Imagenes/secciones.png\">";
	$carpeta=$listado->NomFich;
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$carpeta."\">".$listado->Titulo."</a></li>";
}
print "</ul>";


mysql_free_result($result);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
