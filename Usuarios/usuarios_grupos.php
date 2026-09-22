<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$grupo = $_GET["grupo"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/usuarios_grupos-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Interface/herramienta.php\" enctype=\"multipart/form-data\" method=\"GET\">";
print "<input name=\"grupo\" type=\"hidden\" value=\"".$grupo."\">";
print "<input name=\"modulo\" type=\"hidden\" value=\"Usuarios\">";
print "<input name=\"herramienta\" type=\"hidden\" value=\"usuarios_grupos2\">";
print "<ul>";
print "<li><label for=\"Num_Pagina\">".$lang["numpagina"].": </label><input id=\"Num_Pagina\" name=\"Num_Pagina\" type=\"text\" value=\"10\" size=\"4\" maxlength=\"8\"></li>";
print "<li>".$lang["campos"].":</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Nombre\" checked> ".$lang["nombre"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Apellidos\" checked> ".$lang["apellidos"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Email\" checked> ".$lang["email"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Provincia\"> ".$lang["provincia"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Ciudad\"> ".$lang["ciudad"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Telefono\"> ".$lang["telefono"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Movil\"> ".$lang["movil"]."</li>";
print "<li style=\"background:#EEE;float:left;margin-right:10px;\"><input style=\"display:inline-block\" name=\"CamposMostrar[]\" type=\"checkbox\" value=\"NombreEmpresa\"> ".$lang["empresa"]."</li>";
print "</ul>";
print "<input name=\"origen\" type=\"hidden\" value=\"usuarios_grupos\">";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>