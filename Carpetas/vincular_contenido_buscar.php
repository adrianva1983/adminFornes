<?php
$contenido = $_GET['contenido'];
$seccion = $_GET['seccion'];
$ruta = $_GET['ruta'];
$tipocontenido = $_GET['tipocontenido'];
$modulo = $_GET['modulo'];
$herramienta = $_GET['herramienta'];
$busca = $_GET['busca'];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &áacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/vincular_contenido_buscar-".$_SESSION['idioma'].".conf");

if ($busca=="")
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_vincular_contenido'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
	print "<form name=\"fbusca\" action=\"/administra/Interface/herramienta.php\">";
	print "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
	print "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";
	print "<input name=\"contenido\" type=\"hidden\" value=\"".$contenido."\">";
	print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
	print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
	print "<input name=\"tipocontenido\" type=\"hidden\" value=\"".$tipocontenido."\">";
	print "<div class='form-group'><div class='col-sm-11'><input class=\"form-control\" name=\"busca\" value=\"".$buscar."\"></div>";
	print "<div class='col-sm-1'><input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["buscar"]."\"></div></div>";
	print "</form>";	
	print '</div></div></div></div>';
}
if ($busca!="")
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['vincular_buscar'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
	print "<form action=\"/administra/Carpetas/vincular_contenido_3.php?contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido."\" enctype=\"multipart/form-data\" method=\"POST\">";	
	print "<ul><li>".$lang["plantilla"].": <select name=\"Plantilla\">";
	// Hacemos una consulta para ver las distintas plantillas a aplicar
	$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='contenido';";
	$result = mysqli_query($db,$requete);
	// Listamos las plantillas existentes
	if (($result) && (mysqli_num_rows($result)>0))	
	{
 		while($listado = mysqli_fetch_object($result))
 		{
			print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
 		}
	}
	print "</select></li>";	
	print "</ul><hr>";	
	include "vincular_contenido_buscar_2.php";
	print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["vincular"]."\">";
	print "</form>";
	print '</div></div></div></div>';
}
?>