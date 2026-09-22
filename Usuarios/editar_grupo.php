<?php
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	header ("Location: $redir?error_login=5");
	exit;
}

if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/editar_grupo-".$_SESSION['idioma'].".conf");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Grupos` WHERE `Id`='".$grupo."'";

$listado = mysqli_fetch_object($result);
print "<form action=\"../Usuarios/editar_grupo_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li>".$lang["nombre"].": <input name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"40\" maxlength=\"40\"></li>";
print "<li>".$lang["nivel"].":";	
$requete2 = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";

print "<select name=\"NivelAcceso\">";
print "<option value=\"\"></option>";
while($listado2 = mysqli_fetch_object($result2))
{
	if ($listado2->Nivel>$_SESSION['usuario_nivel'])
	{
		if ($listado->NivelAcceso==$listado2->Nivel) print "<option selected value=\"".$listado2->Nivel."\">".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Nivel."\">".$listado2->Nombre."</option>";
	}
}
print "</select>";
print "</li>";
print "</ul>";
print "<input type=\"hidden\" name=\"grupo\" value=\"".$grupo."\">";
if ($grp!="") print "<input type=\"hidden\" name=\"grp\" value=\"".$grp."\"/>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>