<?php
//VERSIÓN: v1.0 2015-06-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_GET["seccion"];
$borrarpase = $_GET["borrarpase"];
$referenciaIdioma = $_GET["referenciaIdioma"];
$codigoIdioma = $_GET["codigoIdioma"];
$ruta = $_GET["ruta"];
$rutaOrigen = $_GET["rutaOrigen"];
$seccionOrigen = $_GET["seccionOrigen"];
$herramientaOrigen = $_GET["herramientaOrigen"];
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
$javascript_onready .= "
	tinyMCE.init({
		mode : 'exact',
		width : '90%',
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
//CARGA CONTADORES DE CARACTERES SEO
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
$javascript_onready .= "\$(\".contador_SEOKey\").each(function(){";
$javascript_onready .= "var longitud = \$(this).val().length;";
$javascript_onready .= "\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+longitud+'</b> caracteres');";
$javascript_onready .= "\$(this).keyup(function(){ ";
$javascript_onready .= "var nueva_longitud = \$(this).val().length;";
$javascript_onready .= "\$(this).parent().find('#longitud_contador_SEOKey').html('<b>'+nueva_longitud+'</b> caracteres');";
$javascript_onready .= "if (nueva_longitud < \"50\") {";
$javascript_onready .= "\$('#longitud_contador_SEOKey').css('color', '#ff0000');";
$javascript_onready .= "}";
$javascript_onready .= "if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {";
$javascript_onready .= "\$('#longitud_contador_SEOKey').css('color', '#00ff00');";
$javascript_onready .= "}";
$javascript_onready .= "if (nueva_longitud > \"156\") {";
$javascript_onready .= "\$('#longitud_contador_SEOKey').css('color', '#ff0000');";
$javascript_onready .= "}";
$javascript_onready .= "});";
$javascript_onready .= "});";
//Contador Descripción
$javascript_onready .= "\$(\".contador_SEODesc\").each(function(){";
$javascript_onready .= "var longitud = \$(this).val().length;";
$javascript_onready .= "\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+longitud+'</b> caracteres');";
$javascript_onready .= "\$(this).keyup(function(){ ";
$javascript_onready .= "var nueva_longitud = \$(this).val().length;";
$javascript_onready .= "\$(this).parent().find('#longitud_contador_SEODesc').html('<b>'+nueva_longitud+'</b> caracteres');";
$javascript_onready .= "if (nueva_longitud < \"50\") {";
$javascript_onready .= "\$('#longitud_contador_SEODesc').css('color', '#ff0000');";
$javascript_onready .= "}";
$javascript_onready .= "if ((nueva_longitud >= \"50\")&&(nueva_longitud <= \"156\")) {";
$javascript_onready .= "\$('#longitud_contador_SEODesc').css('color', '#00ff00');";
$javascript_onready .= "}";
$javascript_onready .= "if (nueva_longitud > \"156\") {";
$javascript_onready .= "\$('#longitud_contador_SEODesc').css('color', '#ff0000');";
$javascript_onready .= "}";
$javascript_onready .= "});";
$javascript_onready .= "});";
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


print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_seccion'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Carpetas/editar_seccion_2.php\" enctype=\"multipart/form-data\" id=\"formulario_edita_seccion\" method=\"POST\" class=\"form-horizontal\">";
$requete = "SELECT * FROM `Secciones` WHERE `Id`=".$seccion;
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);
if ($borrarpase!="")
{
	//nos piden borrar foto del pase de fotos
	$i=0;
	print $i."-".$borrarpase."<br/>";
	while (file_exists ($_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado->Path."/".$listado->NomFich."/seccion".$i.".jpg"))
	{
		if ($i == $borrarpase)
		{
			unlink($_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado->Path."/".$listado->NomFich."/seccion".$i.".jpg");
		}
		else
		{
			if ($i > $borrarpase)
			{
				$j = $i-1;
				rename($_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado->Path."/".$listado->NomFich."/seccion".$i.".jpg",$_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado->Path."/".$listado->NomFich."/seccion".$j.".jpg");
			}
		}
		$i++;
 	}	
}
//Listamos el formulario
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class='form-control' required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Texto\">".$lang["texto"]."</label><div class='col-sm-10'><textarea id=\"Texto\" name=\"Texto\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Texto."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Redireccionar\">".$lang["redireccionar"]."</label><div class='col-sm-10'><input id=\"Redireccionar\" name=\"Redireccionar\" type=\"text\" value=\"".$listado->Redireccionar."\" size=\"50\" maxlength=\"255\"></div></div>";
print '<div class="hr-line-dashed"></div>';
if (($listado->Foto!="")&&($listado->Foto!="no"))
{
	print "<img src=\"/Imagenes/Secciones/".$listado->Foto."\" width=\"100px\">";		
}
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Foto\">".$lang["imagenEditar"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Foto\" name=\"Foto\" type=\"file\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto\">".$lang["tamano_imagenes"]."</label><div class='col-sm-5'><input class=\"form-control\" id=\"AnchoFoto\" placeholder=\"".$lang["anchoimagen"]."\" name=\"AnchoFoto\" size=\"4\"></div><div class='col-sm-5'><input placeholder=\"".$lang["altoimagen"]."\" class=\"form-control\" id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\"></div></div>";
print "<div class=\"hr-line-dashed\"></div>";
if ($listado->Icono!="") print "<img src=\"/Imagenes/Secciones/".$listado->Icono."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Icono\">".$lang["icono"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Icono\" name=\"Icono\" type=\"file\"></div></div>";
print "<div class=\"hr-line-dashed\"></div>";
$fecha_inicio_array = explode(" ",$listado->FechaComienzo);
$fecha_fin_array = explode(" ",$listado->FechaFin);
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaComienzo']."</label><div class='col-sm-5'><input type='text' value='".$fecha_inicio_array[0]."' name='fechaComienzo' id='fechaComienzo' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaComienzoHoras' value='".$fecha_inicio_array[1]."' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaFin']."</label><div class='col-sm-5'><input type='text' value='".$fecha_fin_array[0]."' name='fechaFin' id='fechaFin' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaFinHoras' value='".$fecha_fin_array[1]."' placeholder='hh:mm:ss' class='form-control'></div></div>";
if ($listado->NumXPag!="") print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumXPag\">".$lang["paginacion"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"NumXPag\" name=\"NumXPag\" size=\"4\" value=\"".$listado->NumXPag."\"></div></div>";
else print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumXPag\">".$lang["paginacion"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"NumXPag\" name=\"NumXPag\" size=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Visibilidad\">".$lang["visibilidad"]."</label><div class='col-sm-10'><select id=\"Visibilidad\" class=\"form-control\" name=\"Visibilidad\">";
print "<option value=\"visible\"";
if ($listado->Visibilidad=="visible") print " selected";
print ">".$lang["visible"]."</option>";
print "<option value=\"oculto\"";
if ($listado->Visibilidad=="oculto") print " selected";
print ">".$lang["oculto"]."</option>";
print "<option value=\"privado\"";
if ($listado->Visibilidad=="privado") print " selected";
print ">".$lang["privado"]."</option>";
print "</select></div></div>";
print "<input name=\"antiguaPlantilla\" type=\"hidden\" value=\"".$listado->Plantilla."\">";
print "<input name=\"antigua\" type=\"hidden\" value=\"".$listado->NomFich."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Plantilla\">".$lang["plantilla"]." (".$listado->Plantilla.")</label><div class='col-sm-10'><select class=\"form-control\" required id=\"Plantilla\" name=\"Plantilla\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete2 = "SELECT * FROM `Plantillas` WHERE `Tipo`='seccion';";
$result2 = mysqli_query($db,$requete2);
// Listamos las plantillas existentes
if (($result2) && (mysqli_num_rows($result2)>0))
{
	print "<option value=\"\"></option>";
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->Plantilla==$listado2->Nombre) print "<option value=\"".$listado2->Nombre."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Nombre."\">".$listado2->Nombre."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumColumnas\">".$lang["numColumnas"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NumColumnas\" size=\"4\" value=\"".$listado->NumColumnas."\"></div></div>";
print "<div id=\"buscadores\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloBuscadores\">".$lang["tituloBuscadores"]."</label><div class='col-sm-10'><textarea id=\"TituloBuscadores\" name=\"TituloBuscadores\" cols=\"60\" rows=\"3\" class=\"form contador_SEOTit\" wrap=\"VIRTUAL\">".$listado->TituloBuscadores."</textarea><div id=\"longitud_contador_SEOTit\"></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"DescripcionBuscadores\">".$lang["descripcionBuscadores"]."</label><div class='col-sm-10'><textarea id=\"DescripcionBuscadores\" name=\"DescripcionBuscadores\" cols=\"60\" rows=\"10\" class=\"form contador_SEODesc\" wrap=\"VIRTUAL\">".$listado->DescripcionBuscadores."</textarea><div id=\"longitud_contador_SEODesc\"></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"URLAmigable\">".$lang["urlamigable"]."</label><div class='col-sm-10'><input id=\"URLAmigable\" class=\"form-control\" name=\"URLAmigable\" type=\"text\" value=\"".$listado->URLAmigable."\" size=\"100\" maxlength=\"255\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Keywords\">".$lang["keywords"]."</label><div class='col-sm-10'><textarea id=\"Keywords\" name=\"Keywords\" cols=\"60\" rows=\"3\" class=\"form contador_SEOKey\" wrap=\"VIRTUAL\">".$listado->Keywords."</textarea><div id=\"longitud_contador_SEOKey\"></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Indexable\">".$lang["indexable"]."</label>";
if ($listado->Prioridad=="-1") print "<div class='col-sm-10'><div class='i-checks'><label><input type=\"radio\" value=\"si\" name=\"Indexable\"/> <i></i> ".$lang["si"]."</label></div><div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"Indexable\" checked/> <i></i> ".$lang["no"]."</div></div></div>";
else
{
	print "<div class='col-sm-10'><div class='i-checks'><label><input type=\"radio\" value=\"si\" name=\"Indexable\" checked/> <i></i> ".$lang["si"]."</div><div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"Indexable\"/> <i></i> ".$lang["no"]."</div></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Prioridad\">".$lang["prioridad"]."</label><div class='col-sm-10'><select class=\"form-control\" id=\"Prioridad\" name=\"Prioridad\">";
	print "<option value=\"\"></option>"; 
	if ($listado->Prioridad=="0.2") print "<option value=\"0.2\" selected>".$lang["MP"]."</option>";
	else print "<option value=\"0.2\">".$lang["MP"]."</option>";	
	if ($listado->Prioridad=="0.4") print "<option value=\"0.4\" selected>".$lang["P"]."</option>";
	else print "<option value=\"0.4\">".$lang["P"]."</option>";
	if ($listado->Prioridad=="0.6") print "<option value=\"0.6\" selected>".$lang["M"]."</option>";
	print "<option value=\"0.6\">".$lang["M"]."</option>";
	if ($listado->Prioridad=="0.8") print "<option value=\"0.8\" selected>".$lang["I"]."</option>";
	print "<option value=\"0.8\">".$lang["I"]."</option>";
	if ($listado->Prioridad=="1") print "<option value=\"1\" selected>".$lang["MI"]."</option>";
	print "<option value=\"1\">".$lang["MI"]."</option>";
	print "</select></div></div>";
}
if ($seccion!="") print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($ruta!="") print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";
if ($herramientaOrigen!="") print "<input name=\"herramientaOrigen\" type=\"hidden\" value=\"".$herramientaOrigen."\">";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>