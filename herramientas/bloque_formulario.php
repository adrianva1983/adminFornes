<?php
//NECESITA:
// - Conexión a base de datos
// - $IdContenido: Id del formulario a visualizar
// - $pagina_formulario: Muestra en que página de la recogida se está
// - $_SESSION['usuario_id']: Variable de sessión del id del usuario conectado
// - $_SESSION['usuario_login']: Variable de sessión del email del usuario conectado
//PRODUCE:
// - Devuelve por pantalla el formulario por páginas
$url = $_SERVER['SCRIPT_NAME'];
if (count($_SERVER['argv'])>0)
{	  
	for ($i=0; $i<count($_SERVER['argv']); $i++)
	{
		if ($i==0) $url = $url."?".$_SERVER['argv'][$i];
		else $url = $url."&".$_SERVER['argv'][$i];
	}	
}
$requete_bloque_formulario = "SELECT * FROM `Contenidos` WHERE Id = ".$IdContenido;
$result_bloque_formulario = mysql_query($requete_bloque_formulario,$db);
$listado_bloque_formulario = mysql_fetch_object($result_bloque_formulario);
$requete_bloque_formulario2 =	"SELECT * FROM `Formularios` WHERE Tipo='TITULO' AND Id='".$listado_bloque_formulario->IdTipoContenido."' ORDER BY `Orden`";
$result_bloque_formulario2 = mysql_query($requete_bloque_formulario2,$db);
if (($result_bloque_formulario2) && (mysqli_num_rows($result_bloque_formulario2)>0))
{
	$i = 1;	
	while($listado_bloque_formulario2 = mysql_fetch_object($result_bloque_formulario2))
	{		
		$i++;
	}	
}
print "<form id=\"myForm\" name=\"myForm\" action=\"/herramientas/enviar_formulario.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input id=\"EmailFormulario\" name=\"EmailFormulario\" type=\"hidden\" value=\"".$listado_bloque_formulario->Breve."\"/>";
print "<input id=\"EmailFormulario\" name=\"TituloFormulario\" type=\"hidden\" value=\"".$listado_bloque_formulario->Titulo."\"/>";
$requete_bloque_formulario = "SELECT * FROM `Formularios` WHERE Id='".$listado_bloque_formulario->IdTipoContenido."' AND `Pagina`= '".($pagina_formulario+2)."' ORDER BY `Orden`";
$result_bloque_formulario = mysql_query($requete_bloque_formulario,$db);
if (mysqli_num_rows($result_bloque_formulario2)<=0) $ultima_pagina_formulario=true;
else $ultima_pagina_formulario=false;
$requete_bloque_formulario = "SELECT * FROM `Formularios` WHERE Id='".$listado_bloque_formulario->IdTipoContenido."' AND `Pagina`= '".$pagina_formulario."' ORDER BY `Orden`";
$result_bloque_formulario = mysql_query($requete_bloque_formulario,$db);
$pagina_formulario++;
print "<input id=\"pagina_formulario\" type=\"hidden\" value=\"".$pagina_formulario."\" name=\"pagina_formulario\"/>";
$i = 0;
while ($listado_bloque_formulario = mysql_fetch_object($result_bloque_formulario))
{
	$tipodecampo = explode("/",$listado_bloque_formulario->Tipo);
	switch ($tipodecampo[0])
	{
		case "usuario_id":
			print "<input id=\"Usuario\" name=\"Usuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\"/>";
		break;
		case "usuario_login":
			print "<input id=\"EmailUsuario\" name=\"EmailUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_login']."\"/>";
		break;
		case "TITULO":
		break;
		case "FINALIZACION":
			print "<p>".htmlentities($listado_bloque_formulario->Valor)."</p>";
		break;
		case "TEXTO":
			print "<label for=\"".$listado_bloque_formulario->Titulo."\">".$listado_bloque_formulario->Titulo.":</label><br/>";
			if ($tipodecampo[1]=="")
			{
				print "<script language=\"javascript\" type=\"text/javascript\" src=\"/herramientas/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>";
				print "<script language=\"javascript\" type=\"text/javascript\">\n";
				print "tinyMCE.init({\n";
				print "	mode : \"textareas\",\n";
				print "	theme : \"advanced\",\n";
				print "	theme_advanced_buttons1 : \"bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink\",\n";
				print "	theme_advanced_buttons2 : \"\",";
				print "	theme_advanced_buttons3 : \"\",";
				print "	theme_advanced_toolbar_location : \"top\",";
				print "	theme_advanced_toolbar_align : \"left\",";
				print "	theme_advanced_statusbar_location : \"bottom\",";
				print "	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\"";
				print "});";
				print "</script>";
				print "<textarea id=\"".$listado_bloque_formulario->Titulo."\" wrap=\"VIRTUAL\" rows=\"10\" cols=\"60\" name=\"".$listado_bloque_formulario->Titulo."\"/></textarea><br/>";
			}
			else
			{
				if ($tipodecampo[1]=="SIN")
			 	{
					print "<textarea id=\"".$listado_bloque_formulario->Titulo."\" wrap=\"VIRTUAL\" rows=\"10\" cols=\"60\" name=\"".$listado_bloque_formulario->Titulo."\"/></textarea><br/>";
				}
				else
				{
					print "<input id=\"".$listado_bloque_formulario->Titulo."\" type=\"text\" maxlength=\"255\" size=\"".$tipodecampo[1]."\" value=\"\" name=\"".$listado_bloque_formulario->Titulo."\"/><br/>";
				}						
			}
			print "<br/>";
		break;
		case "GPS":					
			print "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAuUlnxTuU7cO8H80UxM5mbRSOjNSTg7GlX2ew7mNC1Sg5RTXAoRS5Ve_vH_pDRSgqeZZjxn79X9SdTw\" type=\"text/javascript\"></script>\n";
			print "<script type=\"text/javascript\">\n";
			print "//<![CDATA[\n";
			print "function load() {\n";
			print "if (GBrowserIsCompatible()) {\n";
			print "var map = new GMap2(document.getElementById(\"map\"));\n";
			print "map.setCenter(new GLatLng(40,-4),3);\n";	
			print "map.addControl(new GLargeMapControl());\n";
			print "map.setMapType(G_NORMAL_MAP);\n";
			print "var point = new GPoint (-4,40);\n";
			print "var marker = new GMarker(point);\n";
			print "map.addOverlay(marker);\n";		
			print "GEvent.addListener(map, \"click\", function (overlay,point){\n";
			print "if (point){\n";
			print "marker.setPoint(point);\n";
			print "document.myForm.Longitud.value=point.x\n";					
			print "document.myForm.Latitud.value=point.y\n";
			print "}\n";
			print "});\n";
			print "}\n";
			print "}\n";
			print "window.onload=load\n";
			print "//]]>\n";
			print "</script>\n";
			print "<label>".$listado_bloque_formulario->Titulo.":</label><br/>";
			print "<p><div id=\"map\" style=\"width: 534px; height: 278px\"></div></p>";
			print "Latitud: <input type=\"text\" name=\"Latitud\" id=\"Latitud\" value=\"\"/>";
			print "Longitud: <input type=\"text\" name=\"Longitud\" id=\"Longitud\" value=\"\"/><br/>";
		break;
		case "RADIO":
			print "<label for=\"".$listado_bloque_formulario->Titulo."\">".$listado_bloque_formulario->Titulo.":</label><br/>";
			$requete2_bloque_formulario = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`='".$tipodecampo[1]."' ORDER BY `Orden`";
			$result2_bloque_formulario = mysql_query($requete2_bloque_formulario,$db);
			if (($result2_bloque_formulario) && (mysqli_num_rows($result2_bloque_formulario)>0))
			{
				while($listado2_bloque_formulario = mysql_fetch_object($result2_bloque_formulario))
				{
					print $listado2_bloque_formulario->Titulo.": <input style=\"display:inline;\" name=\"".$listado_bloque_contenido->TituloCampo."\" type=\"radio\" value=\"".$listado2_bloque_formulario->Titulo."\">";
					if (isset($listado2_bloque_formulario->Imagen)) print "<img src=\"http://".$_SERVER['SERVER_NAME'].$listado2_bloque_formulario->Imagen."\">";
					print "<br/>";			  			
				}
			}
		break;
		case "FILES":
			//Includes necesarios para las barras de subida de archivos con AJAX
			print "<script src=\"/herramientas/Scripts/Swiff.Base.js\" type=\"text/javascript\"></script>";
			print "<script src=\"/herramientas/Scripts/Swiff.Uploader.js\" type=\"text/javascript\"></script>";
			print "<script src=\"/herramientas/Scripts/FancyUpload.js\" type=\"text/javascript\"></script>";
			//Estilos de las barras de subida de archivos con AJAX
			print "<style type=\"text/css\">";
			print ".photoupload-queue{list-style: none;}";
			print ".photoupload-queue li{background: url(/administra/Imagenes/ampliar_imagen.png) no-repeat 0 5px;padding: 5px 0 5px 22px;}";
			print ".photoupload-queue .queue-file{font-weight: bold;}";
			print ".photoupload-queue .queue-size{color: #aaa;margin-left: 1em;font-size: 0.9em;}";
			print ".photoupload-queue .queue-loader{position: relative;margin: 3px 15px;font-size: 0.9em;background-color: #ddd;color: #fff;border: 1px inset #ddd;}";
			print ".photoupload-queue .queue-subloader{text-align: center;position: absolute;background-color: #E46E2E;height: 100%;width: 0%;left: 0;top: 0;}";
			print ".photoupload-queue .input-delete{width: 16px;height: 16px;background: url(/administra/Imagenes/borrar.png) no-repeat 0 0;text-decoration: none;border: none;float: right;}";
			print "</style>";
			//Script de las barras de subida de archivos con AJAX
			print "<script type=\"text/javascript\">\n";
			print "//<![CDATA[\n";
			print "window.addEvent('load', function()\n";
			print "{\n";
 			print "var input = \$('photoupload-filedata-1');\n";
 			print "var uplooad = new FancyUpload(input, {\n";
 			print "swf: '/herramientas/Scripts/Swiff.Uploader.swf',\n";
 			print "queueList: 'photoupload-queue',\n";
 			print "container: \$E('h1')\n";
			print "});\n";
 			print "\$('photoupload-status').adopt(new Element('a', {\n";
 			print "href: 'javascript:void(null);',\n";
 			print "events: {\n";
 			print "click: uplooad.clearList.bind(uplooad, [false])\n";
 			print	"}\n";
 			print "}).setHTML('Clear Completed'));\n";
			print "var uplooad2 = new FancyUpload(\$('photoupload2-filedata-1'), {\n";
 			print "swf: '/herramientas/Scripts/Swiff.Uploader.swf',\n";
 			print "queueList: 'photoupload-queue-2'\n";
 			print "});\n";
 			print "});\n";
 			print "//]]>\n";
			print "</script>\n";
			print "<div class=\"halfsize\">";					
			print "<label>".$listado_bloque_formulario->Titulo.":</label><br/>";
			print $listado_bloque_formulario->Valor;
			print "<div class=\"label emph\">";
			print "<label for=\"photoupload-filedata-1\">Subir Ficheros:</label><br/>";
			print "<input type=\"file\" name=\"Filedata\" id=\"photoupload-filedata-1\" />";
			print	"</div>";
			print "</div>";
			print "<div class=\"halfsize\">";
			print "<fieldset>";
			print "<legend>Cola de ficheros a subir</legend>";
			print "<div class=\"note\" id=\"photoupload-status\">Selecciona los ficheros a subir.</div>";
			print "<ul class=\"photoupload-queue\" id=\"photoupload-queue\"><li style=\"display: none\" /></ul>";
			print "</fieldset>";
			print "</div>";
			print "<div class=\"clear\"></div>";
		break;
		case "CHECK":
			print "<label>".$listado_bloque_formulario->Titulo.":</label><br/>";
			$requete2_bloque_formulario = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`='".$tipodecampo[1]."' ORDER BY `Orden`";
			$result2_bloque_formulario = mysql_query($requete2_bloque_formulario,$db);
			if (($result2_bloque_formulario) && (mysqli_num_rows($result2_bloque_formulario)>0))
			{
				$i=1;
				$j=1;
				while($listado2_bloque_formulario = mysql_fetch_object($result2_bloque_formulario))
				{
					print $listado2_bloque_formulario->Titulo.": <input style=\"display:inline;\" name=\"".$listado_bloque_contenido->TituloCampo."/".$i."\" type=\"checkbox\" value=\"".$listado2_bloque_formulario->Titulo."\"> ";
					if (isset($listado2_bloque_formulario->Imagen)) print "<img src=\"http://".$_SERVER['SERVER_NAME'].$listado2_bloque_formulario->Imagen."\"> ";
					if ($j == $tipodecampo[2]) //PONEMOS SALTOS DE LINEA EN FUNCIÓN DEL NÚMERO DE ELEMENTOS POR LÍNEA ESPECIFICADOS
					{
						$j=0;
						print "<br/>";
					}
					$i++;
					$j++;
				}
			}
			print "<br/>";
		break;
		case "GRUPO":
			print "<label for=\"".$listado_bloque_formulario->Titulo."\">".$listado_bloque_formulario->Titulo.":</label><br/>";
			$requete2_bloque_formulario = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`= '".$tipodecampo[1]."' ORDER BY `Orden`";
			$result2_bloque_formulario = mysql_query($requete2_bloque_formulario,$db);
			if (($result2_bloque_formulario) && (mysqli_num_rows($result2_bloque_formulario)>0))
			{			    
				print "<select name=\"".$listado_bloque_formulario->TituloCampo."\">";
				print "<option value=\"\"></option>";
				while($listado2_bloque_formulario = mysql_fetch_object($result2_bloque_formulario))
				{
					print "<option value=\"".$listado2_bloque_formulario->Titulo."\">".$listado2_bloque_formulario->Titulo."</option>";
				}
				print "</select>";
			}
		break;
	}
	$i++;
}
if ($ultima_pagina_formulario==true)
{
	print "<br/><input type=\"submit\" name=\"button\" class=\"Bfr Be1\" id=\"submitter\" value=\"Enviar\"/>";
}
else
{
	if (($i-1) > $pagina_formulario)
	{
		print "<br/><input type=\"submit\" name=\"button\" class=\"Bfr Be1\" id=\"submitter\" value=\"Siguiente\"/>";
	}
	else
	{
		if ($i > $pagina_formulario)
		{
			print "<br/><input type=\"submit\" name=\"button\" class=\"Bfr Be1\" id=\"submitter\" value=\"Finalizar\"/>";
		}
	}
}
print "</form>";
?>
