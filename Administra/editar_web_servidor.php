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
$requete = "SELECT * FROM `Servidor_webs` WHERE `Id`='".$Id."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/editar_web_servidor-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Administra/editar_web_servidor_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input name=\"Id\" type=\"hidden\" value=\"".$Id."\">";
print "<form action=\"/administra/Administra/nueva_web_servidor_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<li>".$lang["dominio"].": <input name=\"Dominio\" type=\"text\" value=\"".$listado->Dominio."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["version"].": <input name=\"Version\" type=\"text\" value=\"".$listado->Version."\" size=\"2\" maxlength=\"3\"></li>";
print "<li>".$lang["subversion"].": <input name=\"Subversion\" type=\"text\" value=\"".$listado->Subversion."\" size=\"2\" maxlength=\"3\"></li>";
print "<li>".$lang["baseDatosBD"].": <input name=\"BaseDatosBD\" type=\"text\" value=\"".$listado->BaseDatosBD."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["usuarioBD"].": <input name=\"UsuarioBD\" type=\"text\" value=\"".$listado->UsuarioBD."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["passBD"].": <input name=\"PassBD\" type=\"text\" value=\"".$listado->PassBD."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["hostFTP"].": <input name=\"hostFTP\" type=\"text\" value=\"".$listado->hostFTP."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["usuarioFTP"].": <input name=\"UsuarioFTP\" type=\"text\" value=\"".$listado->UsuarioFTP."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["passFTP"].": <input name=\"PassFTP\" type=\"text\" value=\"".$listado->PassFTP."\" size=\"40\" maxlength=\"100\"></li>";
print "<li>".$lang["estado"].": <select name=\"Estado\">";
if ($listado->Estado=="ok") print "<option value=\"ok\" selected>".$lang["ok"]."</option>";
else print "<option value=\"ok\">".$lang["ok"]."</option>";
if ($listado->Estado=="ko") print "<option value=\"ko\" selected>".$lang["ko"]."</option>";
else print "<option value=\"ko\">".$lang["ko"]."</option>";
print "</select></li>";
print "<input type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>