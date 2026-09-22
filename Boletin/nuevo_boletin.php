<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_boletin-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Boletin/nuevo_boletin_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
// SELECCIÓN DE PLANTILLAS
print "<li><label for=\"PlantillaCabecera\">".$lang["cabecera"].":</label><br/><select name=\"PlantillaCabecera\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"PlantillaCuerpo\">".$lang["cuerpo"].":</label><br/><select name=\"PlantillaCuerpo\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"PlantillaPie\">".$lang["pie"].":</label><br/><select name=\"PlantillaPie\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
	}
}
print "</select></li>";
//FIN SELECCIÓN DE PLANTILLAS
print "<li><label for=\"Titulo\">".$lang["titulo"].":</label><br/><input name=\"Titulo\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"NombreRemitente\">".$lang["remitente"].":</label><br/><input name=\"NombreRemitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li><label for=\"EmailRemitente\">".$lang["emailRemitente"].":</label><br/><input name=\"EmailRemitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li><label for=\"MailPruebas\">".$lang["pruebas"].":</label><br/><input name=\"MailPruebas\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li><label for=\"Zonas\">".$lang["zonas"].":</label><br/><input name=\"Zonas\" type=\"text\" value=\"\" size=\"2\" maxlength=\"2\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>