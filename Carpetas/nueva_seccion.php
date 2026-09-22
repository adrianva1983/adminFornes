<?php
//VERSIÓN: v1.0 2015-06-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion=$_GET["seccion"];
$referenciaIdioma=$_GET["referenciaIdioma"];
$codigoIdioma=$_GET["codigoIdioma"];
$ruta=$_GET["ruta"];
$rutaOrigen=$_GET["ruta_Origen"];
$seccionOrigen=$_GET["seccionOrigen"];
$herramientaOrigen=$_GET["herramientaOrigen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nueva_seccion-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//CARGA CONTADORES DE CARACTERES SEO
$javascript_onready .= "
	tinyMCE.init({
		mode : 'exact',
		width : '100%',
		elements : 'Texto',";
if ($_SESSION['idioma'] == "ES-ES") $javascript_onready.="		language: 'es',";
$javascript_onready.="
		plugins: [
			'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
			'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
			'save table contextmenu directionality emoticons template paste textcolor'			
			],
		toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
		
	});";
//Contador título
$javascript_onready .= "\$(\".contador_SEOTit\").each(function(){
				var longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEOTit').html('<b>'+longitud+'</b> caracteres');
				\$(this).keyup(function(){
				var nueva_longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEOTit').html('<b>'+nueva_longitud+'</b> caracteres');
				if (nueva_longitud < \"15\") {
				\$('#longitud_contador_SEOTit').css('color', '#ff0000');
				}
				if ((nueva_longitud < \"70\")&&(nueva_longitud > \"15\")) {
				\$('#longitud_contador_SEOTit').css('color', '#00ff00');
				}
				if (nueva_longitud > \"70\") {
				\$('#longitud_contador_SEOTit').css('color', '#ff0000');
				}
				});
				});";
//Contador Keywords
$javascript_onready .= "\$(\".contador_SEOKey\").each(function(){
				var longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+longitud+'</b> caracteres');
				\$(this).keyup(function(){
				var nueva_longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+nueva_longitud+'</b> caracteres');
				if (nueva_longitud < \"50\") {
				\$('#longitud_contador_SEOKey').css('color', '#ff0000');
				}
				if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {
				\$('#longitud_contador_SEOKey').css('color', '#00ff00');
				}
				if (nueva_longitud > \"156\") {
				\$('#longitud_contador_SEOKey').css('color', '#ff0000');
				}
				});
				});";
//Contador Descripción
$javascript_onready .="\$(\".contador_SEODesc\").each(function(){
				var longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+longitud+'</b> caracteres');
				\$(this).keyup(function(){
				var nueva_longitud = \$(this).val().length;
				\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+nueva_longitud+'</b> caracteres');
				if (nueva_longitud < \"50\") {
				\$('#longitud_contador_SEODesc').css('color', '#ff0000');
				}
				if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {
				\$('#longitud_contador_SEODesc').css('color', '#00ff00');
				}
				if (nueva_longitud > \"156\") {
				\$('#longitud_contador_SEODesc').css('color', '#ff0000');
				}
				});
				});";
$javascript_onready .= "
	$(function() 
	{
		$('#fechaComienzo' ).datepicker({format: 'yyyy-mm-dd',language:'es-ES'});
	});
	$(function() 
	{
		$( '#fechaFin' ).datepicker({format: 'yyyy-mm-dd'});
	});
	";

$requete = "SELECT * FROM `Secciones` WHERE `Id`='".$seccion."';";	

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{		
	$listado = mysqli_fetch_object($result);	
	$IdEntorno = $listado->IdEntorno;
}
//Miramos el idioma principal
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$idiomaPrincipal = $listado->Codigo;	
}
if ($codigoIdioma!="") $Idioma = $codigoIdioma;
else $Idioma = $idiomaPrincipal;
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nueva_seccion'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form id=\"formulario_nueva_seccion\" action=\"/administra/Carpetas/nueva_seccion_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class='form-control' required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" required maxlength=\"255\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Texto\">".$lang["texto"]."</label><div class='col-sm-10'><textarea id=\"Texto\" name=\"Texto\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\"></textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Redireccionar\">".$lang["redireccionar"]."</label><div class='col-sm-10'><input id=\"Redireccionar\" name=\"Redireccionar\" class=\"form-control\" type=\"text\" value=\"\" maxlength=\"255\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Foto\">".$lang["imagen"]."</label><div class='col-sm-10'><input id=\"Foto\" class=\"form-control\" name=\"Foto\" type=\"file\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"RetocarFoto\">".$lang["retocar"]."</label><div class='col-sm-10'>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"si\" name=\"RetocarFoto\"> <i></i> ".$lang["si"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"RetocarFoto\" checked>".$lang["no"]."</label></div></div></div>";
print "<div id=\"retocarImagenFoto\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto\">".$lang["tamano_imagenes"]."</label><div class='col-sm-5'><input class=\"form-control\" placeholder=\"".$lang["anchoimagen"]."\" id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\"></div><div class='col-sm-5'><input class=\"form-control\" id=\"AltoFoto\" placeholder=\"".$lang["altoimagen"]."\" name=\"AltoFoto\" size=\"4\"></div></div>";
print "</div>";
print "<div class=\"hr-line-dashed\"></div><div class='form-group'><label class='col-sm-2 control-label' for=\"Icono\">".$lang["icono"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Icono\" name=\"Icono\" type=\"file\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"RetocarIcono\">".$lang["retocarIcono"].": </label><div class='col-sm-10'>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"si\" name=\"RetocarIcono\"> <i></i> ".$lang["si"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"RetocarIcono\" checked> <i></i> ".$lang["no"]."</label></div></div></div>";
print "<div id=\"retocarImagenIcono\">";
print "<div class='form-group'><label class='col-sm-2 control-label' >".$lang["tamano_imagenes"]."</label><div class='col-sm-5'><input placeholder=\"".$lang["anchoimagen"]."\" id=\"AnchoIcono\" class=\"form-control\" name=\"AnchoIcono\" size=\"4\"></div><div class='col-sm-5'><input placeholder=\"".$lang["altoimagen"]."\" class=\"form-control\" id=\"AltoIcono\" name=\"AltoIcono\" size=\"4\"></div></div>";
print "</div>";
print "<div class=\"hr-line-dashed\"></div>";
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaComienzo']."</label><div class='col-sm-5'><input type='text' value='' name='fechaComienzo' id='fechaComienzo' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaComienzoHoras' value='' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaFin']."</label><div class='col-sm-5'><input type='text' value='' name='fechaFin' id='fechaFin' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaFinHoras' value='' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumXPag\">".$lang["paginacion"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"NumXPag\" name=\"NumXPag\" size=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Visibilidad\">".$lang["visibilidad"]."</label><div class='col-sm-10'><select class=\"form-control\" id=\"Visibilidad\" name=\"Visibilidad\"><option value=\"visible\">".$lang["visible"]."</option><option value=\"oculto\">".$lang["oculto"]."</option><option value=\"privado\">".$lang["privado"]."</option></select></div></div>";
if ($IdEntorno!="")
{
	/* ARREGLAR
	print "<script language=\"JavaScript\" type=\"text/JavaScript\">";
	print "\$(document).ready(function(){\n";
	$requete = "SELECT * FROM `Entornos` WHERE Id=".$IdEntorno;	
		
	if ($result = mysqli_query($db, $requete))
	{		
		$listado = mysqli_fetch_object($result);		
		$IdPlantillaEntorno = $listado->IdPlantilla;
	}
	print "var id = ".$IdPlantillaEntorno.";\n";
	print "\$(\"#Plantilla\").load('/administra/Carpetas/genera-select-plantillas-seccion-miniweb.php',{idioma:'".$Idioma."',id:id});\n";
	print "});\n";
	print "</script>";
	print "<li><label for=\"Plantilla\">".$lang["plantillaseccionminiweb"].": </label><select name=\"Plantilla\" id=\"Plantilla\">";
	print "</select></li>";
	*/
}
else
{
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Plantilla\">".$lang["plantilla"].": </label><div class='col-sm-10'><select required class=\"form-control\" id=\"Plantilla\" name=\"Plantilla\">";
	// Hacemos una consulta para ver las distintas plantillas a aplicar
	$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='seccion';";
	
	// Listamos las plantillas existentes
	if ($result = mysqli_query($db, $requete))
	{
		print "<option value=\"\"></option>"; 
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
		}
	}
	print "</select></div></div>";
}
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumColumnas\">".$lang["numColumnas"].": </label><div class='col-sm-10'><input class=\"form-control\" name=\"NumColumnas\" size=\"4\" value=\"".$listado->NumColumnas."\"></div></div>";
if ($IdEntorno)
{
	print "<input id=\"IdEntorno\" name=\"IdEntorno\" type=\"hidden\" value=\"".$listado->IdEntorno."\" size=\"50\" maxlength=\"255\">";
}
else
{	
	print "<div id=\"microsites\"><div class=\"hr-line-dashed\"></div>";
	$requete2 = "SELECT * FROM `Entornos`";
	
	// Listamos las plantillas existentes
	if ($result2 = mysqli_query($db, $requete2))
	{	
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Entorno\">".$lang["entorno"].": </label><select class=\"form-control\" name=\"Entorno\"><option value=\"\"></option>";
		while ($listado2 = mysqli_fetch_object($result2))
		{
			print "<option value=\"".$listado2->Id."\">".$listado2->Dominio."</option>";
		}
		print "</select></div></div>";
	}
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Dominio\">".$lang["dominio"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Dominio\" name=\"Dominio\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
	//print "<li><label for=\"URLSeccion\">".$lang["URLSeccion"].": </label><input id=\"URLSeccion\" name=\"URLSeccion\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
	//print "<li><label for=\"URLContenido\">".$lang["URLContenido"].": </label><input id=\"URLContenido\" name=\"URLContenido\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"ColorMiniweb\">".$lang["colorminiweb"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"ColorMiniweb\" name=\"ColorMiniweb\" type=\"text\" value=\"\" size=\"7\" maxlength=\"7\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"LogoMiniweb\">".$lang["logominiweb"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"LogoMiniweb\" name=\"LogoMiniweb\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
	//print "<li><label for=\"ListadoFotoAncho\">".$lang["ListadoFotoAncho"].": </label><input id=\"ListadoFotoAncho\" name=\"ListadoFotoAncho\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
	//print "<li><label for=\"ListadoFotoAlto\">".$lang["ListadoFotoAlto"].": </label><input id=\"ListadoFotoAlto\" name=\"ListadoFotoAlto\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";			
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Analytics\">".$lang["Analytics"]."</label><div class='col-sm-10'><textarea id=\"Analytics\" name=\"Analytics\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\"></textarea></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"PlantillaEntorno\">".$lang["plantillaminiweb"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"PlantillaEntorno\" id=\"PlantillaEntorno\"><option value=\"\"></option>";
	$requete2 = "SELECT * FROM `EntornosPlantillas`";
	
	// Listamos las plantillas existentes
	if ($result2 = mysqli_query($db, $requete2))
	{
		while ($listado2 = mysqli_fetch_object($result2))
		{
			print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
		}
	}
	print "</select></div></div>";
	/* ARREGLAR
	print "<script language=\"JavaScript\" type=\"text/JavaScript\">";
	print "\$(document).ready(function(){\n";
	print "\$(\"#PlantillaEntorno\").change(function(event){\n";
	print "var id = \$(\"#PlantillaEntorno\").find(':selected').val();\n";
	print "\$(\"#PlantillaSeccionEntorno\").load('/administra/Carpetas/genera-select-plantillas-seccion-miniweb.php',{idioma:'".$Idioma."',id:id});\n";
	print "});\n";
	print "});\n";
	print "</script>";
	*/
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"PlantillaSeccionEntorno\">".$lang["plantillaseccionminiweb"].": </label><div class='col-sm-10'><select class=\"form-control\" name=\"PlantillaSeccionEntorno\" id=\"PlantillaSeccionEntorno\">";
	print "</select></div></div>";	
	print "</div>";
}		
print "<div id=\"buscadores\"><div class=\"hr-line-dashed\"></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"TituloBuscadores\">".$lang["tituloBuscadores"].": </label><div class='col-sm-10'><textarea id=\"TituloBuscadores\" name=\"TituloBuscadores\" cols=\"60\" rows=\"3\" class=\"form contador_SEOTit\" wrap=\"VIRTUAL\"></textarea><div id=\"longitud_contador_SEOTit\"></div></div></div></div></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"DescripcionBuscadores\">".$lang["descripcionBuscadores"].": </label><div class='col-sm-10'><textarea id=\"DescripcionBuscadores\" name=\"DescripcionBuscadores\" cols=\"60\" rows=\"10\" class=\"form contador_SEODesc\" wrap=\"VIRTUAL\"></textarea><div id=\"longitud_contador_SEODesc\"></div></div></div></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"Keywords\">".$lang["keywords"].": </label><div class='col-sm-10'><textarea id=\"Keywords\" name=\"Keywords\" cols=\"60\" rows=\"3\" class=\"form contador_SEOKey\" wrap=\"VIRTUAL\"></textarea><div id=\"longitud_contador_SEOKey\"></div></div></div></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"URLAmigable\">".$lang["urlamigable"].": </label><div class='col-sm-10'><input class=\"form-control\" id=\"URLAmigable\" name=\"URLAmigable\" type=\"text\" value=\"\" size=\"100\" maxlength=\"255\"></div></div></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"Indexable\">".$lang["indexable"].": </label><div class='col-sm-10'><div class='i-checks'><label><input class=\"form-control\" type=\"radio\" value=\"si\" name=\"Indexable\" checked/> <i></i>".$lang["si"]."</label></div><div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"Indexable\"/> <i></i> ".$lang["no"]."</label></div></div></div></div>";
print "<div class='row'><div class='form-group'><label class='col-sm-2 control-label' for=\"Prioridad\">".$lang["prioridad"].": </label><div class='col-sm-10'><select class=\"form-control\" id=\"Prioridad\" name=\"Prioridad\">";
print "<option value=\"\"></option>"; 
print "<option value=\"0.2\">".$lang["MP"]."</option>";
print "<option value=\"0.4\">".$lang["P"]."</option>";
print "<option value=\"0.6\">".$lang["M"]."</option>";
print "<option value=\"0.8\">".$lang["I"]."</option>";
print "<option value=\"1\">".$lang["MI"]."</option>";
print "</select></div></div></div></div>";

if ($seccion!="") print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($ruta!="") print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";
if ($herramientaOrigen!="") print "<input name=\"herramientaOrigen\" type=\"hidden\" value=\"".$herramientaOrigen."\">";
print "<div class='row'><div class='col-md-12'><input class=\"btn btn-primary pull-right\" type=\"submit\" value=\"".$lang["enviar"]."\"></div></div>";
print "</form>";
print '</div></div></div>';
?>