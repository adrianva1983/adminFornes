<?php
// Hay que hacer lo mismo que la de buscar carpetas, pero para usuarios
// La diferencia es que la búsqueda básica será por nombre y/o apellido
// Abrá posibilidad de búsqueda avanzada que permitirá buscar por cualquier campo de la tabla de usuarios menos por la contraseña
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">Nivel Acceso:".$nivel_acceso."<br>Nivel Usuario:".$_SESSION['usuario_nivel']."<br>Puerta Lógica:".($nivel_acceso <= $_SESSION['usuario_nivel']);
	Print "No tiene permisos para acceder a este &aacute;rea</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_mensaje-".$_SESSION['idioma'].".conf");

require ($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/nuevo_mensaje_2.php");
////require "var.php";

/***VARIABLES POR GET ***/
$numero = count($_GET);
$tags = array_keys($_GET);
// obtiene los nombres de las varibles
$valores = array_values($_GET);
// obtiene los valores de las varibles
// crea las variables y les asigna el valor
//echo "GET:<hr>";
$consulta=1;
$or="";
for($i=0;$i<$numero;$i++)
{
	$$tags[$i]=$valores[$i];
	if (($valores[$i]!="") && ($tags[$i]!="modulo") && ($tags[$i]!="herramienta") && ($consulta))
	{
		$buscar=$buscar." $or`".$tags[$i]."` like '%".$valores[$i]."%' ";
		$or=" or ";
	}  
	//echo "<li>$$tags[$i]<li>$valores[$i]</li></li>";
}
//echo "<hr>$buscar<hr>";
$que=$or;
if (($idmensaje=="")&&($idusuario==""))
{
	if ($que=="")
	{
		echo "<form name=\"ubusca\" action=\"/administra/Interface/herramienta.php\">";
		echo "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
		echo "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";
		echo "<table>";
		echo "<tr><td><label for=\"Id\">Id:</label><br/><input class=\"ancho100\" name=Id val=\"$Id\"></td><td><label for\"Nombre\">".$lang["nombre"].":</label><br/><input class=\"ancho100\" name=Nombre value=\"$Nombre\"></td><td><label for=\"Apellidos\">".$lang["apellidos"].":</label><br/><input class=\"ancho100\" name=Apellidos value=\"$Apellidos\"></td></tr>";
		echo "<tr><td colspan=\"3\"><label for=\"Direccion\">".$lang["direccion"].":</label><br/><input class=\"ancho100\" name=Direccion value=\"$Direccion\"></td></tr>";
		echo "<tr><td><label for=\"Provincia\">".$lang["provincia"].":</label><br/><input class=\"ancho100\" name=Provincia value=\"$Provincia\"></td><td><label for=\"Municipio\">".$lang["municipio"].":</label><br/><input class=\"ancho100\" name=Municipio value=\"$Municipio\"></td><td><label for=\"CP\">".$lang["cp"].":</label><br/><input class=\"ancho100\" name=CP value=\"$CP\"></td></tr>";
		echo "<tr><td><label for=\"Ciudad\">".$lang["ciudad"].":</label><br/><input class=\"ancho100\" name=Ciudad value=\"$Ciudad\"></td><td colspan=\"2\"><label for=\"Pais\">".$lang["pais"].":</label><br/><input class=\"ancho100\" name=Pais value=\"$Pais\"></td></tr>";
		echo "<tr><td><label for=\"Email\">".$lang["email"]."</label><br/><input class=\"ancho100\" name=Email value=\"$Email\"></td><td><label for=\"Telefono\">".$lang["telefono"].":</label><br/><input class=\"ancho100\" name=Telefono value=\"$Telefono\"></td><td><label for=\"Movil\">".$lang["movil"].":</label><br/><input class=\"ancho100\" name=Movil value=\"$movil\"></td></tr>";
		echo "<tr><td colspan=\"2\"><label for=\"NombreEmpresa\">".$lang["empresa"].":</label><br/><input class=\"ancho100\" name=NombreEmpresa value=\"$NombreEmpresa\"></td><td><label for=\"CIF\">".$lang["CIF"].":</label><br/><input class=\"ancho100\" name=CIF value=\"$CIF\"></td></tr>";
		echo "<tr><td><label for=\"FechaCreacion\">".$lang["creaccion"].":</label><br/><input class=\"ancho100\" name=FechaCreaccion value=\"$FechaCreaccion\"></td><td><label for=\"FechaCaducidad\">".$lang["caducidad"].":</label><br/><input class=\"ancho100\" name=FechaCaducidad value=\"$FechaCaducidad\"></td><td><label for=\"FechaUltimoAcceso\">".$lang["ultimo"].":</label><br/><input class=\"ancho100\" name=FechaUltimoAcceso value=\"$FechaUltimoAcceso\"></td></tr>";
		echo "</table>";
		echo "<input class=\"boton\" type=\"submit\" value=\"".$lang["buscar"]."\">";
		echo "</form>";
	}
	else
	{
		$que=$buscar;
		busca_usuario($que,0);
	}
}
else
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	if ($submit=="")
	{
		print "<script language=\"javascript\" type=\"text/javascript\" src=\"/herramientas/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>";
		print "<script language=\"javascript\" type=\"text/javascript\">\n";
		print "tinyMCE.init({\n";
		print "	mode : \"textareas\",\n";
		print "	theme : \"advanced\",\n";
		print " plugins : \"\",\n";
		print "	theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,,outdent,indent\",\n";
		print "	theme_advanced_buttons2 : \"undo,redo,link,unlink,image,charmap,forecolor,backcolor\",";
		print "	theme_advanced_buttons3 : \"\",";
		print "	theme_advanced_toolbar_location : \"top\",";
		print "	theme_advanced_toolbar_align : \"left\",";
		print "	theme_advanced_statusbar_location : \"bottom\",";
		print "	extended_valid_elements : \"a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\"";
		print "});";
		print "</script>";
		if ($idmensaje!="")
		{
			//Capturamos datos del mensaje si es una respuesta
			$requete_mensaje = "SELECT * FROM `Mensajeria` WHERE Id = '".$idmensaje."';";
			$result_mensaje = mysql_query($requete_mensaje,$db);
			if (($result_mensaje) && (mysqli_num_rows($result_mensaje)>0))
			{
				$listado_mensaje = mysql_fetch_object($result_mensaje);
				if ($usuario =="") $usuario = $listado_mensaje->IdUsuarioOrigen;
			}
			//Pintamos el formulario para enviar mensaje
			print "<form action=\"#\" enctype=\"multipart/form-data\" method=\"POST\">";
			print "<input type=\"hidden\" id=\"para\" name=\"para\" value=\"".$usuario."\">";
			$requete = "SELECT * FROM `Usuarios` WHERE Id = '".$usuario."';";
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
			}
			print "<p class=\"mensaje\">".$lang["enviandoA"]." ".htmlentities($listado->Nombre);
			if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
			print "</p>";
			if ($idmensaje!="") print "<label for=\"TituloMensaje\">".$lang["asunto"].": </label><input class=\"campo-contenido\" id=\"TituloMensaje\" name=\"TituloMensaje\" type=\"text\" value=\"RE: ".$listado_mensaje->Titulo."\" size=\"50\" maxlength=\"255\"><br/>";
			else print "<label for=\"TituloMensaje\">".$lang["asunto"].": </label><input class=\"campo-contenido\" id=\"TituloMensaje\" name=\"TituloMensaje\" type=\"text\" value=\"".$listado_mensaje->Titulo."\" size=\"50\" maxlength=\"255\"><br/>";
			print "<label for=\"mensaje\">".$lang["msg"].": </label><br/>";
			if ($idmensaje!="") print "<textarea class=\"campo-contenido\" id=\"mensaje\" name=\"mensaje\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\"><p><strong>".$lang["msgOriginal"].":</strong></p><hr>".$listado_mensaje->Mensaje."</textarea>";
			else print "<textarea class=\"campo-contenido\" id=\"mensaje\" name=\"mensaje\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\">".$listado_mensaje->Mensaje."</textarea>";
			print "<br/><input class=\"boton\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"".$lang["enviar"]."\"/>";
			print "</form>";
		}
		if ($idusuario!="")
		{
			//Capturamos datos del mensaje si es una respuesta
			//Pintamos el formulario para enviar mensaje
			print "<form action=\"#\" enctype=\"multipart/form-data\" method=\"POST\">";
			print "<input type=\"hidden\" id=\"para\" name=\"para\" value=\"".$idusuario."\">";
			$requete = "SELECT * FROM `Usuarios` WHERE Id = '".$idusuario."';";
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
			}
			print "<p class=\"mensaje\">".$lang["enviandoA"]." ".htmlentities($listado->Nombre);
			if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
			print "</p>";			
			print "<label for=\"TituloMensaje\">".$lang["asunto"].": </label><input class=\"campo-contenido\" id=\"TituloMensaje\" name=\"TituloMensaje\" type=\"text\" value=\"".$listado_mensaje->Titulo."\" size=\"50\" maxlength=\"255\"><br/>";
			print "<label for=\"mensaje\">".$lang["msg"].": </label><br/>";
			print "<textarea class=\"campo-contenido\" id=\"mensaje\" name=\"mensaje\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\"></textarea>";
			print "<br/><input class=\"boton\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"".$lang["enviar"]."\"/>";
			print "</form>";
		}
	}
	else
	{
		$requete = "INSERT INTO `Mensajeria` (`Titulo`,`Mensaje`,`IdUsuarioDestino`,`IdUsuarioOrigen`,`Fecha`) VALUES ('".$TituloMensaje."', '".$mensaje."', '".$para."', '".$_SESSION['usuario_id']."','".date("Y-m-d")."');";
		mysqli_query($db,$requete);
		$Idmensaje = mysqli_insert_id($db);
		//Sacamos el email del entorno
		$requete = "SELECT `Valor` FROM `Servidor` WHERE `Campo` = 'Mail Entorno'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$mail_entorno = $listado->Valor;
		}
		//Cargamos los encabezados del mail
		$dominio = $_SERVER['SERVER_NAME'];
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= "From: $dominio <$mail_entorno>\r\n";
		$requete = "SELECT `Email` FROM `Usuarios` WHERE Id = '".$campos["para"]."'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$UsuarioEmail = $listado->Email;
		}
		mysql_free_result($result);
		$msg = $lang["textoMail"]." <strong>".$_SERVER['SERVER_NAME']."</strong>.";
		$msg.= "<img src=\"/herramientas/estadisticas_msg.php?idmensaje=".$Idmensaje."&imagen=/Plantillas/Imagenes/estadistica-img.png\"/>";
		include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");
		mail($UsuarioEmail, $lang["tituloMail"], $mensaje, $headers);
		print "<p class=\"mensajeOK\"><img src=\"/administra/Imagenes/tick.png\"/> ".$lang["enviadoOK"]."</p>";	
	}
}
?>