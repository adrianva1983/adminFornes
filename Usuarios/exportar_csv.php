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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/exportar_csv-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=exportar_csv2\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"SQL\">".$lang["SQL"].": </label><textarea id=\"SQL\" name=\"SQL\" style=\"width:90%\"></textarea></li>";
print "</ul>";
print "<input type=\"submit\" name=\"submit\" id=\"submit\" value=\"".$lang["siguiente"]."\">";
print "</form>";
?>