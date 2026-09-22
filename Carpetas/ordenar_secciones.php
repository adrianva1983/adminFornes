<?php
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/ordenar_secciones-".$_SESSION['idioma'].".conf");

print "<p class=\"mensaje\">".$lang["seleccione"].".</p>";
print "<div id=\"botonera_cabecera\"><ul>";
print "<li><a class=\"boton_linea\" href=\"/administra/Carpetas/ordenar_secciones2.php?tipo=alfabetico&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$TipoContenido."\">".$lang["alfabeticoSecciones"]."</a></li>";
print "<li><a class=\"boton_linea\" href=\"/administra/Carpetas/ordenar_contenidos2.php?tipo=alfabetico&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$TipoContenido."\">".$lang["alfabeticoContenidos"]."</a></li>";
print "</ul></div>";
?>