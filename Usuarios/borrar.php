<?php
//VERSIÓN: v1.0 2014-03-17
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$usuario = $_GET["usuario"];
$referencia = $_GET["referencia"];
$origen = $_GET["origen"];
$Num_Pagina = $_GET["Num_Pagina"];
$CamposMostrar = $_GET["CamposMostrar"];
$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
$pagina= $_GET["pagina"];

//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/borrar-".$_SESSION['idioma'].".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print $lang["errorPermisos"];
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ($lang["accesoIncorrecto"]);
	exit;
}
print "<form action=\"/administra/Usuarios/borrar_2.php\" enctype=\"multipart/form-data\" method=\"GET\">";
print "<p>".$lang["seguro"]."</p>";
print "<input name=\"CamposMostrar\" type=\"hidden\" value=\"".urlencode(serialize($CamposMostrar))."\">";
print "<input name=\"usuario\" type=\"hidden\" value=\"".$usuario."\">";
print "<input name=\"referencia\" type=\"hidden\" value=\"".$referencia."\">";
print "<input name=\"origen\" type=\"hidden\" value=\"".$origen."\">";
print "<input name=\"Num_Pagina\" type=\"hidden\" value=\"".$Num_Pagina."\">";
print "<input name=\"pagina\" type=\"hidden\" value=\"".$pagina."\">";
print $lang["confirmar"]." <input name=\"borrar\" class=\"boton_riesgo\" type=\"submit\" value=\"".$lang["borrar"]."\">";
print $lang["cancelar"]." <input name=\"cancelar\" type=\"submit\" value=\"".$lang["NOBorrar"]."\">";
print "</form>";
?>
