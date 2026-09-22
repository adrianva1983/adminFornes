<?php
//VERSIÓN: v1.0 2013-10-28
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ampliacion = $_GET["ampliacion"];
$contenido = $_GET["contenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$tipocontenido=$_GET["tipocontenido"];
$referenciaIdioma = $_GET["referenciaIdioma"];
$codigoIdioma = $_GET["codigoIdioma"];
$contenidoOrigen = $_GET["contenidoOrigen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
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

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/amplia_contenido-".$_SESSION['idioma'].".conf");
// CARGA DE EDITOR DE TEXTO
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

print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_amplia_contenido'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form id=\"formulario_amplia_contenido\" action=\"/administra/Carpetas/amplia_contenido_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
if ($referenciaIdioma!="") print "<input name=\"referenciaIdioma\" type=\"hidden\" value=\"".$referenciaIdioma."\">";
if ($codigoIdioma!="") print "<input name=\"codigoIdioma\" type=\"hidden\" value=\"".$codigoIdioma."\">";
if ($contenidoOrigen!="") print "<input name=\"contenidoOrigen\" type=\"hidden\" value=\"".$contenidoOrigen."\">";
if ($tipocontenido!="") print "<input name=\"tipocontenido\" type=\"hidden\" value=\"".$tipocontenido."\">";
if ($ampliacion!="") print "<input name=\"ampliacion\" type=\"hidden\" value=\"".$ampliacion."\">";
if ($contenido!="") print "<input name=\"contenido\" type=\"hidden\" value=\"".$contenido."\">";
if (isset($seccion)) print "<input name=\"seccion\" type=\"hidden\" value=\"".$seccion."\">";
if (isset($ruta)) print "<input name=\"ruta\" type=\"hidden\" value=\"".$ruta."\">";
switch ($ampliacion) 
{
	case "texto":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Texto\">".$lang["texto"]."</label><div class='col-sm-10'><textarea id=\"Texto\" name=\"Texto\" cols=\"94\" rows=\"30\" wrap=\"VIRTUAL\"></textarea></div></div>";		
		break;
	case "imagen":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"].": </label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Foto\">".$lang["imagen"].": </label><div class='col-sm-10'><input class=\"form-control\" required id=\"Foto\" name=\"Foto\" type=\"file\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Alternativo\">".$lang["alternativo"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Alternativo\" name=\"Alternativo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Redireccionar\">".$lang["enlace"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Redireccionar\" name=\"Redireccionar\" type=\"text\" value=\"http://\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"alineacion\">".$lang["alineacion"]."</label><div class='col-sm-10'>";
		print "<div class='i-checks'><label><input type=\"radio\" name=\"alineacion\" value=\"izquierda\"><i></i> ".$lang["izquierda"]." <img style=\"vertical-align:middle;\" src=\"../Imagenes/image-izq.png\"/></label></div>";
		print "<div class='i-checks'><label><input type=\"radio\" name=\"alineacion\" value=\"centrado\"><i></i> ".$lang["centrado"]." <img style=\"vertical-align:middle;\" src=\"../Imagenes/image-cen.png\"/></label></div>";
		print "<div class='i-checks'><label><input type=\"radio\" name=\"alineacion\" value=\"derecha\"><i></i> ".$lang["derecha"]." <img style=\"vertical-align:middle;\" src=\"../Imagenes/image-der.png\"/></label></div></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Retocar\">".$lang["retocar"]."</label><div class='col-sm-10'><div class='i-checks'><label><input type=\"radio\" value=\"si\" name=\"Retocar\"><i></i> ".$lang["si"]."</label></div>";
		print "<div class='i-checks'><label><input type=\"radio\" value=\"no\" name=\"Retocar\" checked><i></i> ".$lang["no"]."</label></div></div></div>";		
		print "<div id=\"retocarImagen\">";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto\">".$lang["anchoimagen"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\"></div><label class='col-sm-2 control-label' for=\"AltoFoto\">".$lang["altoimagen"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto2\">".$lang["anchoampliado"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AnchoFoto2\" name=\"AnchoFoto2\" size=\"4\"></div><label class='col-sm-2 control-label' for=\"AltoFoto2\">".$lang["altoampliado"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AltoFoto2\" name=\"AltoFoto2\" size=\"4\"></div></div>";
		print "</div>";     
		break;
	case "fichero":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fichero\">".$lang["fichero"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Fichero\" name=\"Fichero\" type=\"file\"></div></div>";
		break;
	case "video":
		print "<script>
		function mostrar()
		{
			elemento = \$('#TipoReproduccion').val();
			if (elemento=='servidor')
			{
				\$('#videoExterno').fadeOut();
				\$('#videoServidor1').fadeIn();
				\$('#videoServidor2').fadeIn();
			}
			if ((elemento=='youtube')||(elemento=='vimeo'))
			{
				\$('#videoExterno').fadeIn();
				\$('#videoServidor1').fadeOut();
				\$('#videoServidor2').fadeOut();
			}
		}</script>";		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TipoRepredoccion\">".$lang["tipoReproduccion"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"TipoReproduccion\" id=\"TipoReproduccion\" onChange=\"mostrar();\"><option value=\"youtube\" selected>".$lang["youtube"]."</option><option value=\"vimeo\">".$lang["vimeo"]."</option><option value=\"servidor\">".$lang["servidor"]."</option></select></div></div>";
		print "<div id=\"videoExterno\"><div class='form-group'><label class='col-sm-2 control-label' for=\"VideoId\">".$lang["idVideoExterno"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"VideoId\" name=\"VideoId\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div></div>";     
		print "<div style=\"display:none;\" id=\"videoServidor1\"><div class='form-group'><label class='col-sm-2 control-label' for=\"FicheroServidor\">".$lang["video"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"FicheroServidor\" name=\"FicheroServidor\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div></div>";
		print "<div style=\"display:none;\" id=\"videoServidor2\"><div class='form-group'><label class='col-sm-2 control-label' for=\"Fichero\">".$lang["videofichero"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Fichero\" name=\"Fichero\" type=\"file\"></div></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto\">".$lang["anchovideo"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\"></div><label class='col-sm-2 control-label' for=\"AltoFoto\"> ".$lang["altovideo"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\"></div></div>";
		break;
	case "enlace":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Redireccionar\">".$lang["enlace"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Redireccionar\" name=\"Redireccionar\" type=\"text\" value=\"http://\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<input name=\"tipocontenido\" type=\"hidden\" value=\"".$tipocontenido."\">";		
		break;
	case "mapa":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Titulo\" required name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Latitud\">".$lang["latitud"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"Latitud\" name=\"Latitud\" size=\"8\"></div><label class='col-sm-2 control-label' for=\"Longitud\"> ".$lang["longitud"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"Longitud\" name=\"Longitud\" size=\"8\"></div></div>";
		break;
	case "ruta":
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input id=\"Titulo\" required class=\"form-control\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang["centro"]."</label><div class='col-sm-5'><input class=\"form-control\" id=\"Latitud\" placeholder=\"".$lang["latitud"]."\" name=\"Latitud\" size=\"8\"></div><div class='col-sm-5'><input class=\"form-control\" id=\"Longitud\" placeholder=\"".$lang["longitud"]."\" name=\"Longitud\" size=\"8\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Zoom\">".$lang["zoom"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Zoom\" name=\"Zoom\" size=\"2\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fichero\">".$lang["kml"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Fichero\" name=\"Fichero\" type=\"file\"></div></div>";
		break;
	case "foro":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Texto\">".$lang["texto"]." </label><div class='col-sm-10'><textarea id=\"Texto\" name=\"Texto\" cols=\"60\" rows=\"10\" wrap=\"VIRTUAL\"></textarea></div></div>";
		//print "<li><label for=\"URLAmigable\">".$lang["urlamigable"].": </label><input id=\"URLAmigable\" name=\"URLAmigable\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";		
		print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang["configuraciones_foro"]."</label><div class='col-sm-10'>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Validacion\" name=\"Validacion\" type=\"checkbox\" value=\"Validacion\" checked><i></i> ".$lang["exigirvalidar"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Aviso\" name=\"Aviso\" type=\"checkbox\" value=\"Aviso\"><i></i> ".$lang["enviaraviso"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"AvisoEmail\" name=\"AvisoEmail\" type=\"checkbox\" value=\"AvisoEmail\" checked><i></i> ".$lang["enviaraviso2"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Votacion\" name=\"Votacion\" type=\"checkbox\" value=\"Votacion\"><i></i> ".$lang["votacion"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Busqueda\" name=\"Busqueda\" type=\"checkbox\" value=\"Busqueda\"><i></i> ".$lang["busqueda"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Respuestas\" name=\"Respuestas\" type=\"checkbox\" value=\"Respuestas\" checked><i></i> ".$lang["respuestas"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Max1Mensaje\" name=\"Max1Mensaje\" type=\"checkbox\" value=\"Max1Mensaje\"><i></i> ".$lang["maximo1"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"Anonimo\" name=\"Anonimo\" type=\"checkbox\" value=\"Anonimo\"><i></i> ".$lang["anonimoescritura"]."</label></div></div>";
		print "<div class='col-sm-4'><div class='i-checks'><label><input id=\"LecturaAnonimo\" name=\"LecturaAnonimo\" type=\"checkbox\" value=\"Anonimo\" checked><i></i> ".$lang["anonimolectura"]."</label></div></div>";
		print "</div></div>";
		$requete = "SELECT * FROM `NivelesPermisos` WHERE Idioma='".$_SESSION['idioma']."' ORDER BY `Nivel`";
		$result2 = mysqli_query($db,$requete);
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NivelEscritura\">".$lang["nivelescritura"].":</label>";
		print "<div class='col-sm-10'><select class=\"form-control\" id=\"NivelEscritura\" name=\"NivelEscritura\">";
		while($listado2 = mysqli_fetch_object($result2))
		{
			if ($listado2->Nivel>=$_SESSION['usuario_nivel'])
			{
				print "<option value=\"".$listado2->Nivel."\">".$listado2->Nombre."</option>";
			}
		}
		print "</select></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NivelLectura\">".$lang["nivellectura"].":</label>";
		print "<div class='col-sm-10'><select class=\"form-control\" id=\"NivelLectura\" name=\"NivelLectura\">";
		$result2 = mysqli_query($db,$requete);
		while($listado2 = mysqli_fetch_object($result2))
		{
			if ($listado2->Nivel>=$_SESSION['usuario_nivel'])
			{
				print "<option value=\"".$listado2->Nivel."\">".$listado2->Nombre."</option>";
			}
		}	
		print "</select></div></div>";		
		break;
	case "bloque":		
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Titulo\" name=\"Titulo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Texto\">".$lang["texto"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Texto\" name=\"Texto\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Foto\">".$lang["imagen"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Foto\" name=\"Foto\" type=\"file\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"AnchoFoto\">".$lang["anchoimagen"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AnchoFoto\" name=\"AnchoFoto\" size=\"4\"></div><label class='col-sm-2 control-label' for=\"AltoFoto\">".$lang["altoimagen"]."</label><div class='col-sm-4'><input class=\"form-control\" id=\"AltoFoto\" name=\"AltoFoto\" size=\"4\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Enlace\">".$lang["enlace"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Enlace\" name=\"Enlace\" type=\"text\" value=\"http://\" size=\"50\" maxlength=\"255\"></div></div>";
		print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><input class=\"form-control\"  id=\"Tipo\" name=\"Tipo\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></div></div>";		
		break;
}
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaComienzo']."</label><div class='col-sm-5'><input type='text' value='' name='fechaComienzo' id='fechaComienzo' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaComienzoHoras' value='' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label'>".$lang['fechaFin']."</label><div class='col-sm-5'><input type='text' value='' name='fechaFin' id='fechaFin' placeholder='yyyy-mm-dd' class='form-control'></div><div class='col-sm-5'><input type='text' name='fechaFinHoras' value='' placeholder='hh:mm:ss' class='form-control'></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>