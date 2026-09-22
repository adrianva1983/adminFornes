<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/tipo_contenidos-".$_SESSION['idioma'].".conf");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print $lang["errorPermisos"];
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ($lang["accesoIncorrecto"]);
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$datos = array();
$requete = "SELECT * FROM `TipoContenidos`";

$i=0;
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT `Id` FROM `Contenidos` WHERE `IdTipoContenido`='".$listado->Id."'";
		$result2 = mysql_query($requete2);
		 $contenidos = mysqli_num_rows($result2);
		 $datos[$i][0] = $listado->Tipo;
		 $datos[$i][1] = $contenidos;
		 $datos[$i][2] = $listado->Id;
		 $i++;
	}
}
print "<div style=\"width:50%;float:left;\">";
print "<h2>".$lang["tipos"]."</h2>";
print "<ul>";
for ($j=0;($j<count($datos));$j++) 
{
	print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=contenidos_tipo&tipo=".$datos[$j][2]."\">".$datos[$j][0]." (".$datos[$j][1].")</a></li>";
}
print "</ul>";
print "</div>";
print "<div style=\"width:50%;float:left;\">";
print "<h2>".$lang["tags"]."</h2>";
print "<script type=\"text/javascript\">";
print "function plegar_desplegar(hijos,boton){";
print "if (hijos.css('display')=='none'){";
print "hijos.fadeIn();";
print "boton.attr('src','/administra/Imagenes/plegar_menos_tags.png');";
print "}else{";
print "hijos.fadeOut();";
print "boton.attr('src','/administra/Imagenes/desplegar_mas_tags.png');";
print "}";
print "}";
print "</script>";
function obtener_tags($idpadre,$db)
{
	$requete = "SELECT * FROM `Tags` WHERE `IdPadre`";	
	if ($idpadre == "") $requete.= " IS NULL";
	else $requete.= "=".$idpadre;	
	$requete.= " ORDER BY `Nombre`;";	
	
	if ($result = mysqli_query($db, $requete))
	{	
		if ($idpadre!="") print "<a href=\"#\" onclick=\"plegar_desplegar(\$('.subtags".$idpadre."'),\$('.pliega_despliega".$idpadre."'));return false\" style=\"float:left;margin:0px;\"><img class=\"pliega_despliega".$idpadre."\" src=\"/administra/Imagenes/desplegar_mas_tags.png\"/></a>";		
		print "<ul";
		if ($idpadre!="") print " class=\"subtags".$idpadre."\" style=\"display:none;\"";
		print ">";		
		while($listado = mysqli_fetch_object($result))
		{
			print "<li style=\"border-bottom:1px solid #DDD; width:100%;\">";
			print $listado->Nombre;
			print "<a style=\"float:right;margin:0px 5px;\" href=\"/administra/Contenidos/funciones/eliminar_tag.php?id=".$listado->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";
			print "<a style=\"float:right;margin:0px 5px;\" href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=editar_tag&id=".$listado->Id."\"><img src=\"/administra/Imagenes/tag_blue_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
			print "<a style=\"float:right;margin:0px 5px;\" href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=anadir_tag&id=".$listado->Id."\"><img src=\"/administra/Imagenes/tag_blue_add.png\" title=\"".$lang["anadirHijo"]."\" alt=\"".$lang["anadirHijo"]."\"></a>";
			obtener_tags($listado->Id,$db);
			print "</li>";
		}
		print "</ul>";
	}
}
obtener_tags("",$db);
print "</div>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>