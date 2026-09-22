<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

print "<form action=\"/administra/Contenidos/editar_mensaje_foro_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/editar_mensaje_foro-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `ForoMensajes` WHERE `Id`=".$mensaje;

$listado = mysqli_fetch_object($result);
//Listamos el formulario
	print "<li><label for=\"Titulo\">".$lang["titulo"].": </label><input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></li>";
	print "<li><label for=\"Breve\">".$lang["texto"].": </label><textarea id=\"Breve\" name=\"Breve\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Mensaje."</textarea></li>";
	print "<li><label for=\"Votacion\">".$lang["votacion"].": </label><input id=\"Votacion\" name=\"Votacion\" type=\"text\" value=\"".$listado->Votacion."\" size=\"50\" maxlength=\"255\"></li>";

	print "<input name=\"VotacionOriginal\" type=\"hidden\" value=\"".$listado->Votacion."\">";
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
	print "<input name=\"IdContenido\" type=\"hidden\" value=\"".$contenido."\">";
	print "<input name=\"origen\" type=\"hidden\" value=\"".$origen."\">";
	print "<input name=\"pagina\" type=\"hidden\" value=\"".$pagina."\">";
	print "<input name=\"IdForo\" type=\"hidden\" value=\"".$listado->IdForo."\">";
	print "<input name=\"mensaje\" type=\"hidden\" value=\"".$mensaje."\">";
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
?>