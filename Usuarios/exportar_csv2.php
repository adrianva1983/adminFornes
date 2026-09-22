<?php
//COMPROBAMOS EL NIVEL DE ACCESO
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/exportar_csv2-".$_SESSION['idioma'].".conf");
//Cargamos el sistema de importación / exportación
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/csv_import_export.php");
if ($SQL!="")
{
	print "<p>".$lang["procesado"]."</p>";
	$csv_export = new CSVExport;
	$csv_export->setDelim(";");
	$csv_export->createcsv($SQL,$db,$_SERVER['DOCUMENT_ROOT']."/Recursos/".$_SESSION['usuario_id']."_".date('Y-m-d')."_exportacion.csv");
	if ($csv_export->isOK()) 
	{
		print "<p>".$lang["OK"]."</p>";
		print "<p><a href=\"/Recursos/".$_SESSION['usuario_id']."_".date('Y-m-d')."_exportacion.csv\" target=\"_blank\">".$lang["descarga"]."</a></p>";
	}
	else print "<p>".$lang["KO"]."</p>";
}
else
{
	print "<p>No tenemos resultados</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>