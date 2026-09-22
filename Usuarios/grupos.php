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
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/grupos-".$_SESSION['idioma'].".conf");

$requete = "SELECT * FROM `Grupos`";
if ($grp!="") $requete .= " WHERE `IdPadre`='".$grp."'";
else $requete .= " WHERE `IdPadre` IS NULL ORDER BY `Orden` ASC";


print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
 $requete2="SELECT `Id` FROM `PertenenciaGrupos` WHERE `IdGrupo`='".$listado->Id."'";
 $result2 = mysql_query($requete2);
 $usuarios=mysqli_num_rows($result2);
 print "<li>";
 print "<a href=\"/administra/Usuarios/funciones/borrar_grupo.php?grupo=".$listado->Id."&referencia=".$grp."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrarGrupo"]."\" alt=\"".$lang["borrarGrupo"]."\"/></a>";
 print "<img src=\"/administra/Imagenes/grupos.png\">";
 print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos&grupo=".$listado->Id."\">". $listado->Nombre."</a> (".$usuarios." ".$lang["usuarios"].")";
 $requete2="SELECT `Id` FROM `Grupos` Where `IdPadre`='".$listado->Id."'";
 $result2 = mysql_query($requete2);
 $subgrupos=mysqli_num_rows($result2);
 if  ($subgrupos>0) print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=grupos&grp=".$listado->Id."\"> (".$subgrupos." ".$lang["subgrupos"].")</a>";
 print "</li>";
 }
}
print "</ul>";

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>