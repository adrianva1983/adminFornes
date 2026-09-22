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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/suscripcion_usuarios_grupos-".$_SESSION['idioma'].".conf");
print "<p>";
print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=suscripcion_usuarios_grupos_GRUPOS&ruta=".$ruta."&seccion=".$seccion."\">";
print "<img src=\"/administra/Imagenes/grupos.png\"> ";
print $lang["grupos"]."</a><br/>";
print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=suscripcion_usuarios_grupos_USUARIOS&ruta=".$ruta."&seccion=".$seccion."\">";
print "<img src=\"/administra/Imagenes/usuario.png\"> ";
print $lang["usuarios"]."</a>";
print "</p>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>