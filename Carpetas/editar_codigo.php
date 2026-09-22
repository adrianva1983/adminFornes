<?php
$contenido = $_GET["contenido"];
$seccion = $_GET["seccion"];
$ruta = $_GET["ruta"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_codigo-".$_SESSION['idioma'].".conf");
print "<form action=\"../Carpetas/editar_codigo_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;

$listado = mysqli_fetch_object($result);
//Listamos el formulario
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["fichero"].": <input name=\"Fichero\" type=\"text\" value=\"".$listado->NomFich."\" size=\"50\" maxlength=\"255\"></li>";
print "<li>".$lang["codigo"].": <textarea name=\"Breve\" cols=\"90\" rows=\"40\" class=\"form\" wrap=\"VIRTUAL\">".$listado->Breve."</textarea></li>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<input name=\"IdContenido\" type=\"hidden\" value=\"".$contenido."\">";
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