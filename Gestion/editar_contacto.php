<?php
//VERSIÓN: v1.1 2014-07-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$IdContacto = $_GET["IdContacto"];
$origen = $_GET["origen"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_contacto-".$_SESSION['idioma'].".conf");

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contactos` WHERE `Id`='".$IdContacto."';";

//Sacamos los datos del cliente
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print "<form action=\"/administra/Gestion/editar_contacto_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input type=\"hidden\" name=\"Idioma\" value=\"".$_SESSION['idioma']."\">";
print "<input type=\"hidden\" name=\"origen\" value=\"".$origen."\">";
print "<input type=\"hidden\" name=\"Id\" value=\"".$IdContacto."\">";
print "<input type=\"hidden\" name=\"IdCliente\" value=\"".$Id."\">";
print "<h1>".$lang["datosContactos"]."</h1>";
print "<ul id=\"contactos\">";
print "<li><label for=\"Nombre\">".$lang["nombreContacto"].":</label><br/><input name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Apellidos\">".$lang["apellidosContacto"].":</label><br/><input name=\"Apellidos\" type=\"text\" value=\"".$listado->Apellidos."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Cargo\">".$lang["cargo"].":</label><br/><input name=\"Cargo\" type=\"text\" value=\"".$listado->Cargo."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Email\">".$lang["emailContacto"].":</label><br/><input name=\"Email\" type=\"text\" value=\"".$listado->Email."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Movil\">".$lang["movilContacto"].":</label><br/><input name=\"Movil\" type=\"text\" value=\"".$listado->Movil."\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"IdRepresentante\">".$lang["representante"].":</label><br/><select name=\"IdRepresentante\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `RepresentantePresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->IdRepresentante==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>