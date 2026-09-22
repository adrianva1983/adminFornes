<?php
//VERSIÓN: v1.0 2014-5-7
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$id = $_GET["id"];
$origen = $_GET["origen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/anadir_tag-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print $lang["errorPermisos"];
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ($lang["accesoIncorrecto"]);
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
print "<form action=\"/administra/Contenidos/editar_tag_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input type=\"hidden\" name=\"origen\" value=\"".$origen."\">";
print "<input type=\"hidden\" name=\"id\" value=\"".$id."\">";
print "<input type=\"hidden\" name=\"Idioma\" value=\"".$Idioma."\">";
print "<h1>".$lang["datos"]."</h1>";
print "<ul>";
print "<li>";
print "<li><label for=\"IdPadre\">".$lang["padre"].":</label><br/><select name=\"IdPadre\">";
$requete = "SELECT * FROM `Tags` WHERE `id`=".$id;

if ($result = mysqli_query($db, $requete))
{		
	$listado = mysqli_fetch_object($result);
}
print "<option value=\"\">".$lang["sinValor"]."</option>";
function obtener_tags($idpadre,$seleccionado,$sangrado,$db)
{
	$requete = "SELECT * FROM `Tags` WHERE `IdPadre`";	
	if ($idpadre == "") $requete.= " IS NULL";
	else $requete.= "=".$idpadre;	
	$requete.= " ORDER BY `Nombre`;";	
	
	if ($result = mysqli_query($db, $requete))
	{		
		while($listado = mysqli_fetch_object($result))
		{
			print "<option ";
			if ($listado->Id == $seleccionado) print "selected ";
			print "value=\"".$listado->Id."\">".$sangrado.$listado->Nombre."</option>";
			obtener_tags($listado->Id,$seleccionado,$sangrado."--",$db);
		}
	}
}
obtener_tags("",$listado->IdPadre,"",$db);
print "</select></li>";
print "<li><label for=\"Nombre\">".$lang["nombre"].":</label><br/><input name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Sinonimos\">".$lang["sinonimos"].":</label><br/><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"3\" cols=\"60\" name=\"Sinonimos\" id=\"Sinonimos\">".$listado->Sinonimos."</textarea></li>";
print "<li><label for=\"URL\">".$lang["url"].":</label><br/><input name=\"URL\" type=\"text\" value=\"".$listado->URL."\" size=\"100\" maxlength=\"200\"></li>";
print "<li>";
if ($listado->Imagen!="") 
{
	print "<img src=\"".$listado->Imagen."\" height=\"50px\"/>";
	print "<input id=\"BorrarImagen\" name=\"BorrarImagen\" type=\"checkbox\" value=\"1\" style=\"display:inline-block;\"> ".$lang["borrarImagen"];
	print "<input id=\"BorrarImagenFichero\" name=\"BorrarImagenFichero\" type=\"hidden\" value=\"".$listado->Imagen."\"/><br/>";
}
print "<label for=\"Imagen\">".$lang["imagen"].": </label><input id=\"Imagen\" name=\"Imagen\" type=\"file\">";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>