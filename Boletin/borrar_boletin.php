<?php
//Comprobamos el acceso
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/borrar_boletin-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Boletin/borrar_boletin_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<p>".$lang["seguro"]."</p>";

print "<input name=\"idboletin\" type=\"hidden\" value=\"".$idboletin."\">";
print $lang["confirmar"]." <input name=\"borrar\" type=\"submit\" value=\"".$lang["borrar"]."\">";
print $lang["cancelar"]." <input name=\"cancelar\" type=\"submit\" value=\"".$lang["NOborrar"]."\">";
print "</form>";
?>
