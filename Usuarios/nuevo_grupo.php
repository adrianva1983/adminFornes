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
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/nuevo_grupo-".$_SESSION['idioma'].".conf");

print "<form action=\"../Usuarios/nuevo_grupo_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li>".$lang["nombre"].": <input name=\"Nombre\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"></li>";
print "<li>".$lang["nivel"].":";	
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";

print "<select name=\"NivelAcceso\">";
while($listado = mysqli_fetch_object($result))
{
	if ($listado->Nivel>$_SESSION['usuario_nivel'])
	{
		print "<option value=\"".$listado->Nivel."\">".$listado->Nombre."</option>";		    
	}
}
print "</select>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "</li>";
print "</ul>";
if ($grp!="") print "<input type=\"hidden\" name=\"grp\" value=\"".$grp."\"/>";
print "<input type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>