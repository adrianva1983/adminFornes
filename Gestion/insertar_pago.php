<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Gestion/nueva_tarea_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Proyecto\">".$lang["proyecto"].":</label><br/><select name=\"Proyecto\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Proyectos` WHERE `Activo` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($IdProyecto==$listado->Id) print "<option value=\"".$listado->Id."\" selected>".$listado->Nombre."</option>";
		else print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
	}
}
print "</select></li>";
print "<li><label for=\"Nombre\">".$lang["nombre"].":</label><br/><input name=\"Nombre\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></li>";
print "<li><label for=\"Descripcion\">".$lang["descripcion"].":</label><br/><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\"></textarea></li>";
print "<li><label for=\"Asignado\">".$lang["AsignadoA"].":</label><br/><select name=\"Asignado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
	}
}
print "</select></li>";
print "<li><label for=\"Responsable\">".$lang["responsable"].":</label><br/><select name=\"Responsable\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
	}
}
print "</select></li>";
// SELECCIÓN DE TIPO DE TAREA
print "<li>".$lang["tipo"].": <select name=\"Tipo\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `TareasTipos` WHERE `Idioma`='".$_SESSION['idioma']."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
	}
}
print "</select></li>";
print "<li>".$lang["publico"].": <select name=\"publico\">";
print "<option value=\"si\">".$lang["si"]."</option>";
print "<option value=\"no\">".$lang["no"]."</option>";
print "</select></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>