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

// SELECCIÓN DE PLANTILLAS
print "<li>".$lang["cabecera"].": <select name=\"PlantillaCabecera\">";
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
print "<li>".$lang["cuerpo"].": <select name=\"PlantillaCuerpo\">";
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
print "<li>".$lang["pie"].": <select name=\"PlantillaPie\">";
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
print "<li>".$lang["titulo"].": <input name=\"Titulo\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li>".$lang["remitente"].": <input name=\"NombreRemitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li>".$lang["emailRemitente"].": <input name=\"EmailRemitente\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li>".$lang["pruebas"].": <input name=\"MailPruebas\" type=\"text\" value=\"\" size=\"50\" maxlength=\"200\"></li>";
print "<li>".$lang["zonas"].": <input name=\"Zonas\" type=\"text\" value=\"\" size=\"2\" maxlength=\"2\"></li>";
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>