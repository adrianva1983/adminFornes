<?php
$contenido = $_GET['contenido'];
$seccion = $_GET['seccion'];
$ruta = $_GET['ruta'];
$tipocontenido = $_GET['tipocontenido'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/vincular_contenido-".$_SESSION['idioma'].".conf");
print "<div id=\"botonera_cabecera\"><div class=\"btn-group col-md-12\">";
print "<a class=\"btn btn-sm btn-white\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=vincular_contenido_arbol&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido."\"><img src=\"/administra/Imagenes/arbol.png\"/> ".$lang["arbol"]."</a>";
print "<a class=\"btn btn-sm btn-white\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=vincular_contenido_buscar&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido."\"><img src=\"/administra/Imagenes/buscar_seccion.png\"/> ".$lang["busqueda"]."</a>";
print "</div></div>";
?>