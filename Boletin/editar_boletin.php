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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/editar_boletin-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `Id`='".$idboletin."';";

if ((!$result) || (mysqli_num_rows($result)<=0)){
	die ($lang["noExiste"]);
	exit;
}
$listado = mysqli_fetch_object($result);
if (($_SESSION['usuario_nivel']==2)&&($listado->IdPropietario!=$_SESSION['usuario_id'])){
	die ($lang["noPropietario"]);
	exit;
}

print "<form action=\"/administra/Boletin/editar_boletin_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input style=\"border:0px;\" type=\"hidden\" name=\"idboletin\" value=\"".$idboletin."\">";
// SELECCIÓN DE PLANTILLAS
print "<ul>";
print "<li><label for=\"PlantillaCabecera\">".$lang["cabecera"].":</label><br/><select name=\"PlantillaCabecera\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete2 = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->PlantillaCabecera==$listado2->Nombre) print "<option value=\"".$listado2->Nombre."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Nombre."\">".$listado2->Nombre."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"PlantillaCuerpo\">".$lang["cuerpo"].":</label><br/><select name=\"PlantillaCuerpo\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete2 = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->PlantillaCuerpo==$listado2->Nombre) print "<option value=\"".$listado2->Nombre."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Nombre."\">".$listado2->Nombre."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"PlantillaPie\">".$lang["pie"].":</label><br/><select name=\"PlantillaPie\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete2 = "SELECT * FROM `Plantillas` WHERE `Tipo`='boletin';";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->PlantillaPie==$listado2->Nombre) print "<option value=\"".$listado2->Nombre."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Nombre."\">".$listado2->Nombre."</option>";
	}
}
print "</select></li>";
//FIN SELECCIÓN DE PLANTILLAS
print "<li><label for=\"Titulo\">".$lang["titulo"].":</label><br/><input name=\"Titulo\" type=\"text\" size=\"100\" maxlength=\"200\" value=\"".$listado->Asunto."\"></li>";
print "<li><label for=\"NombreRemitente\">".$lang["remitente"].":</label><br/><input name=\"NombreRemitente\" type=\"text\" size=\"50\" maxlength=\"200\" value=\"".$listado->Remitente."\"></li>";
print "<li><label for=\"EmailRemitente\">".$lang["emailRemitente"].":</label><br/><input name=\"EmailRemitente\" type=\"text\" size=\"50\" maxlength=\"200\" value=\"".$listado->RemitenteMail."\"></li>";
print "<li><label for=\"MailPruebas\">".$lang["pruebas"].":</label><br/><input name=\"MailPruebas\" type=\"text\" size=\"50\" maxlength=\"200\" value=\"".$listado->MailPruebas."\"></li>";
print "<li><label for=\"Zonas\">".$lang["zonas"].":</label><br/><input name=\"Zonas\" type=\"text\" value=\"".$listado->Zonas."\" size=\"2\" maxlength=\"2\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>