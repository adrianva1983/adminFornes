<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/grupo_camposadicionales-".$_SESSION['idioma'].".conf");

$requete = "SELECT * FROM `CamposAdicionalesGrupos` WHERE Tipo = '".$tipo."' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
{
	print "<hr><ul>";
	while ($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		//BOTONES ORDENACIÓN
		$orden=$listado->Orden;
		print "<a href=\"/administra/Administra/funciones/ordenacion.php?ordena=ordena&familia=CamposAdicionalesGrupos&item=Tipo&iid=".$tipo."&elemento=".$orden."&accion=5&tipo=".$tipo."\"><img src=\"/administra/Imagenes/top_arriba.png\" title=\"".$lang["toparriba"]."\" alt=\"".$lang["toparriba"]."\"></a>";
		print "<a href=\"/administra/Administra/funciones/ordenacion.php?ordena=ordena&familia=CamposAdicionalesGrupos&item=Tipo&iid=".$tipo."&elemento=".$orden."&accion=3&tipo=".$tipo."\"><img src=\"/administra/Imagenes/arriba.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"></a>";
		print "<a href=\"/administra/Administra/funciones/ordenacion.php?ordena=ordena&familia=CamposAdicionalesGrupos&item=Tipo&iid=".$tipo."&elemento=".$orden."&accion=4&tipo=".$tipo."\"><img src=\"/administra/Imagenes/abajo.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"></a>";
		print "<a href=\"/administra/Administra/funciones/ordenacion.php?ordena=ordena&familia=CamposAdicionalesGrupos&item=Tipo&iid=".$tipo."&elemento=".$orden."&accion=6&tipo=".$tipo."\"><img src=\"/administra/Imagenes/top_abajo.png\" title=\"".$lang["topabajo"]."\" alt=\"".$lang["topabajo"]."\"></a>";
		print $listado->Titulo;
		print "</li>";
	}
	print "</ul>";
}
print "<img src=\"/administra/Imagenes/suma.png\"> ".$lang["nuevo"].": ";
print "<form action=\"/administra/Administra/nuevo_eltocamposadicionales.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\">";
print "<input name=\"tipo\" type=\"hidden\" value=\"".$tipo."\">";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["anadir"]."\">";
print "</form>";
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
