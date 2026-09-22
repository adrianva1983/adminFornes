<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/asignar_buscable-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `SeccionesBusqueda` WHERE `IdSeccion`='".$seccion."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	print "<ul>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";		
		if ($listado->IdGrupo!="")
		{
			$requete2 = "SELECT * FROM `Grupos` WHERE `Id`='".$listado->IdGrupo."';";
			
			$listado2 = mysqli_fetch_object($result2);						
			print "<a href=\"/administra/Carpetas/desasignar_buscable.php?Grupo=".$listado->IdGrupo."&seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/borrar.png\"/ title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";
			print "<img src=\"/administra/Imagenes/grupos.png\"/> ".$listado2->Nombre;
		}
		else print "<a href=\"/administra/Carpetas/desasignar_buscable.php?seccion=".$seccion."&ruta=".$ruta."\"><img src=\"/administra/Imagenes/borrar.png\"/></a><img src=\"/administra/Imagenes/grupos.png\"/> ".$lang["general"].".";
		print "</li>";
	}
	print "</ul>";
}
else
{
	print "<p>".$lang["sinAsignaciones"].".</p>";
}
print "<form action=\"/administra/Carpetas/asignar_buscable_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
?>
<ul>
<?php
print "<li><label for=\"Grupo\">".$lang["asignar"].": </label><select id=\"Grupo\" name=\"Grupo\">";
// Hacemos una consulta para ver los distintos grupos a asignar
$requete = "SELECT * FROM `Grupos`;";

print "<option value=\"todos\">".$lang["todos"]."</option>";
// Listamos los grupos existentes
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
  print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
 }
}
print "</select></li>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

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