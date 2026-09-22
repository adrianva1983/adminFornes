<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_SMS-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Boletin/nuevo_SMS_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Titulo\">".$lang["titulo"].":</label><br/><input name=\"Titulo\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Texto\">".$lang["texto"].":</label><br/><input name=\"Texto\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Remitente\">".$lang["remitente"].":</label><br/><input name=\"Remitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li><label for=\"WAPPush\">".$lang["WAP"].":</label><br/><input name=\"WAPPush\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"MovilPruebas\">".$lang["pruebas"].":</label><br/><input name=\"MovilPruebas\" type=\"text\" value=\"\" size=\"50\" maxlength=\"50\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>