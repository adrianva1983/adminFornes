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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_webcam-".$_SESSION['idioma'].".conf");
print "<form action=\"../Carpetas/nuevo_webcam_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["codigo"].": <input name=\"codigo\" size=\"50\" type=\"text\" value=\"\"></li>";
print "<li>".$lang["ancho"].": <input name=\"Ancho\" size=\"4\" type=\"text\" value=\"\"></li>";
if (isset($seccion)){
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
}
if (isset($ruta)){
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
}
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>