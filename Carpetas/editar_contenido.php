<?php
//VERSIÓN: v1.1 2013-10-23
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$contenido = $_GET['contenido'];
$seccion = $_GET['seccion'];
$ruta = $_GET['ruta'];
$codigoIdioma = $_GET['codigoIdioma'];
$rutaOrigen = $_GET['rutaOrigen'];
$seccionOrigen = $_GET['seccionOrigen'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_contenido-".$_SESSION['idioma'].".conf");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
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
		width : '100%',
		elements : 'Breve',";
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
//Contador título
$javascript_onready .="\$(\".contador_SEOTit\").each(function(){
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
$javascript_onready .="\$(\".contador_SEOKey\").each(function(){
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
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_contenido'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form id=\"formulario_editar_contenido\" action=\"/administra/Carpetas/editar_contenido_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
$requete = "SELECT * FROM `Contenidos` WHERE `Id`=".$contenido;
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);
//Listamos el formulario
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"50\" maxlength=\"255\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Breve\">".$lang["texto"]."</label><div class='col-sm-10'><textarea id=\"Breve\" name=\"Breve\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Breve."</textarea></div></div>";	
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fecha"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Fecha\" name=\"Fecha\" type=\"text\" value=\"".$listado->Fecha."\" size=\"10\" maxlength=\"10\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Foto\">".$lang["imagenEditar"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Foto\" name=\"Foto\" type=\"file\">";
if ($listado->Foto!="") 
{		
	print "<img src=\"/Imagenes/".$listado->Foto."\" height=\"60px\">";
	print "<div class='i-checks'><label><input id=\"BorrarImagen\" name=\"BorrarImagen\" type=\"checkbox\" value=\"1\"><i></i> ".$lang["borrarImagen"]."</label></div>";
	print "<input id=\"BorrarImagenFichero\" name=\"BorrarImagenFichero\" type=\"hidden\" value=\"/Imagenes/".$listado->Foto."\"/>";		
}
print "</div></div><div class='form-group'><label class='col-sm-2 control-label' for=\"FotoServidor\">".$lang["fotoServidor"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"FotoServidor\" name=\"FotoServidor\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
if ($listado->Icono!="") 
{	
	print "<img src=\"/Imagenes/".$listado->Icono."\" width=\"50px\">";
	print "<input id=\"BorrarIcono\" name=\"BorrarIcono\" type=\"checkbox\" value=\"1\" style=\"display:inline-block;\"> ".$lang["borrarIcono"];
	print "<input id=\"BorrarIconoFichero\" name=\"BorrarIconoFichero\" type=\"hidden\" value=\"/Imagenes/".$listado->Icono."\"/>";
}
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Icono\">".$lang["icono"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Icono\" name=\"Icono\" type=\"file\"></div></div>";
$redireccionar = explode("|",$listado->Redireccionar);	
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Redireccionar\">".$lang["redireccionar"]."</label><div class='col-sm-7'><input class=\"form-control\" id=\"Redireccionar\" name=\"Redireccionar\" type=\"text\" value=\"".$redireccionar[0]."\" size=\"50\" maxlength=\"255\"></div>";
print "<div class='col-sm-3'><div class='i-checks'><label><input id=\"RedireccionarAparte\" name=\"RedireccionarAparte\" type=\"checkbox\" value=\"1\"";
if ($redireccionar[1]=="_blank") print " checked";
print "><i></i> ".$lang["ventanaAparte"];
print "</label></div></div></div>";	


print "<div id=\"buscadores\"><div class=\"hr-line-dashed\"></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloBuscadores\">".$lang["tituloBuscadores"]."</label><div class='col-sm-10'><textarea id=\"TituloBuscadores\" name=\"TituloBuscadores\" cols=\"60\" rows=\"3\" class=\"form contador_SEOTit\" wrap=\"VIRTUAL\">".$listado->TituloBuscadores."</textarea><div id=\"longitud_contador_SEOTit\"></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"DescripcionBuscadores\">".$lang["descripcionBuscadores"]."</label><div class='col-sm-10'><textarea id=\"DescripcionBuscadores\" name=\"DescripcionBuscadores\" cols=\"60\" rows=\"10\" class=\"form contador_SEODesc\" wrap=\"VIRTUAL\">".$listado->DescripcionBuscadores."</textarea><div id=\"longitud_contador_SEODesc\"></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"URLAmigable\">".$lang["urlamigable"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"URLAmigable\" name=\"URLAmigable\" type=\"text\" value=\"".$listado->URLAmigable."\" size=\"50\" maxlength=\"255\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Keywords\">".$lang["keywords"]."</label><div class='col-sm-10'><textarea id=\"Keywords\" name=\"Keywords\" cols=\"60\" rows=\"3\" class=\"form contador_SEOKey\" wrap=\"VIRTUAL\">".$listado->Keywords."</textarea><div id=\"longitud_contador_SEOKey\"></div></div></div>";	
print "</div>";

print "<div id=\"presentacion\"><div class=\"hr-line-dashed\"></div>";
print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($rutaOrigen!="") print "<input name=\"rutaOrigen\" type=\"hidden\" value=\"".$rutaOrigen."\">";
if ($seccionOrigen!="") print "<input name=\"seccionOrigen\" type=\"hidden\" value=\"".$seccionOrigen."\">";	
print "<input name=\"IdContenido\" type=\"hidden\" value=\"".$contenido."\">";
print "<input name=\"antigua\" type=\"hidden\" value=\"".$listado->NomFich."\">";
//Veo el tipo de contenido para mostrarlo al final
$requete = "SELECT * FROM `TipoContenidos` WHERE `Id`='".$listado->IdTipoContenido."';";
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);
$TipoContenido = $listado->Tipo;

$requete = "SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$seccion."' AND `IdContenido`='".$contenido."';";
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);	
$fecha_inicio_array = explode(" ",$listado->FechaComienzo);
$fecha_fin_array = explode(" ",$listado->FechaFin);
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaComienzo']."</label><div class='col-sm-5'><input type='text' value='".$fecha_inicio_array[0]."' name='fechaComienzo' id='fechaComienzo' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaComienzoHoras' value='".$fecha_inicio_array[1]."' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaFin']."</label><div class='col-sm-5'><input type='text' value='".$fecha_fin_array[0]."' name='fechaFin' id='fechaFin' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaFinHoras' value='".$fecha_fin_array[1]."' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Notas\">".$lang["notas"]."</label><div class='col-sm-10'><textarea id=\"Notas\" name=\"Notas\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\">".$listado->Notas."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Comportamiento\">".$lang["comportamiento"]."</label><div class='col-sm-10'><div class='i-checks'><label><input type=\"radio\" value=\"Plantilla\" name=\"Comportamiento\"";
if (($listado->Plantilla!="") && ($listado->Comportamiento=="")) print " checked";
print "/><i></i> ".$lang["pulsarYPlantilla"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"EmergenteImagen\" name=\"Comportamiento\"";
if ($listado->Comportamiento=="EmergenteImagen") print " checked";
print "/><i></i> ".$lang["pulsarYImagen"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"EmergenteEnlace\" name=\"Comportamiento\"";
if ($listado->Comportamiento=="EmergenteEnlace") print " checked";
print "/><i></i> ".$lang["pulsarYEnlace"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"EmergenteContenido\" name=\"Comportamiento\"";
if ($listado->Comportamiento=="EmergenteContenido") print " checked";
print "/><i></i> ".$lang["pulsarYContenido"]."</label></div>";
print "<div class='i-checks'><label><input type=\"radio\" value=\"NoPulsable\" name=\"Comportamiento\"";
if ($listado->Comportamiento=="NoPulsable") print " checked";
print "/><i></i> ".$lang["noPulsable"]."</label></div></div></div>";
$plantillaAntigua = $listado->Plantilla;
print "<input name=\"PlantillaAntigua\" type=\"hidden\" value=\"".$plantillaAntigua."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Plantilla\">".$lang["plantilla"]."(".$listado->Plantilla.")</label><div class='col-sm-10'><select required class=\"form-control\" id=\"Plantilla\" name=\"Plantilla\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='contenido';";
$result = mysqli_query($db,$requete);
// Listamos las plantillas existentes
if (($result) && (mysqli_num_rows($result)>0))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($listado->Nombre==$plantillaAntigua) print "<option selected value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
		else print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
	}
}
print "</select></div></div>";
// Hacemos una consulta para ver los distintos tipos de contenido
$requete2 = "SELECT * FROM `TipoContenidos`;";
$result2 = mysqli_query($db,$requete2);
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TipoContenido\">".$lang["tipo"]." (".$TipoContenido.")</label><div class='col-sm-10'><select class=\"form-control\" id=\"TipoContenido\" name=\"TipoContenido\">";
print "<option value=\"\"></option>";
// Listamos los tipos de contenido existentes
if (($result2) && (mysqli_num_rows($result2)>0))
{
	while($listado2 = mysqli_fetch_object($result2))
	{	  
		if ($TipoContenido==$listado2->Tipo) print "<option selected value=\"".$listado2->Id."\">".$listado2->Tipo."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Tipo."</option>";
	}
}
print "</select></div></div>";	
print "</div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>