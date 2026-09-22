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
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/nueva_web_servidor-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Administra/nueva_web_servidor_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<li>".$lang["dominio"].": <input name=\"Dominio\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["version"].": <input name=\"Version\" type=\"text\" value=\"\" size=\"2\" maxlength=\"3\"></li>";
print "<li>".$lang["subversion"].": <input name=\"Subversion\" type=\"text\" value=\"\" size=\"2\" maxlength=\"3\"></li>";
print "<li>".$lang["baseDatosBD"].": <input name=\"BaseDatosBD\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["usuarioBD"].": <input name=\"UsuarioBD\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["passBD"].": <input name=\"PassBD\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["hostFTP"].": <input name=\"hostFTP\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["usuarioFTP"].": <input name=\"UsuarioFTP\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["passFTP"].": <input name=\"PassFTP\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["estado"].": <select name=\"Estado\">";
print "<option value=\"ok\">".$lang["ok"]."</option>";
print "<option value=\"ko\">".$lang["ko"]."</option>";
print "</select></li>";
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>