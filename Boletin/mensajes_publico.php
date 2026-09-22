<?php
// NECESITA-------------------------------------------------------------------
// Acceso a la base de datos.
// IdUsuario -> Usuario que manda el boletín
//
//MÓDULO HOME
global $db;
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0];	
	return $lafecha;
}
function home($variables)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	//RESUMEN MENSAJES DE ESTE USUARIO
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'";
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	$TotalMensajesRecibidos =mysqli_num_rows($result_mensajeria);
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioOrigen = '".$_SESSION['usuario_id']."'";
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	$TotalMensajesEnviados =mysqli_num_rows($result_mensajeria);
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."' AND Visto IS NULL";
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	$TotalMensajesNoLeidos =mysqli_num_rows($result_mensajeria);
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."' AND EstadoDestino='favorito'";
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	$TotalMensajesFavoritos =mysqli_num_rows($result_mensajeria);
	print "<p>";
	print "<img src=\"/administra/Imagenes/boletines.png\" alt=\"Mensajes Recibidos\"> ".$TotalMensajesRecibidos." Mensajes Recibidos. ";
	if ($TotalMensajesNoLeidos>0) print "<strong>".$TotalMensajesNoLeidos." nuevos sin leer.</strong>";
	print "<br/>";
	print "<img src=\"/administra/Imagenes/email_go.png\" alt=\"Mensajes Enviados\"> ".$TotalMensajesEnviados." Mensajes Enviados<br/>";
	print "<img src=\"/administra/Imagenes/star.png\" alt=\"Mensajes Favoritos\"> ".$TotalMensajesFavoritos." Mensajes Favoritos<br/>";
	print "</p>";
}
//MÓDULO AÑADIR USUARIOS
function usuarios_anadir($variables,$tipo,$campos)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	print "<p>";
	if (($tipo=="")&($campos["submitImport"]==""))
	{
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=1a1\"><img src=\"/administra/Imagenes/usuarios-menu-anadir.png\" alt=\"A&ntilde;adir 1 a 1\"> A&ntilde;adir Contactos <strong>1 a 1</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=hotmail\"> <img src=\"/administra/Imagenes/msn_status_up.png\" alt=\"A&ntilde;adir desde MSN / Hotmail\" title=\"A&ntilde;adir desde MSN / Hotmail\"> A&ntilde;adir Contactos desde cuenta <strong>Hotmail / MSN</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=hotmail\"> <img src=\"/administra/Imagenes/hotmail_status_up.png\" alt=\"A&ntilde;adir desde Windows Live\" title=\"A&ntilde;adir desde Windows Live\"> A&ntilde;adir Contactos desde cuenta <strong>Windows Live</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=gmail\"> <img src=\"/administra/Imagenes/gmail_status_up.png\" alt=\"A&ntilde;adir desde GMail\" title=\"A&ntilde;adir desde GMail\"> A&ntilde;adir Contactos desde cuenta <strong>GMail</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=flickr\"> <img src=\"/administra/Imagenes/flickr_status_up.png\" alt=\"A&ntilde;adir desde Flickr\" title=\"A&ntilde;adir desde Flickr\"> A&ntilde;adir Contactos desde cuenta <strong>Flickr</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=hi5\"> <img src=\"/administra/Imagenes/hi5_status_up.png\" alt=\"A&ntilde;adir desde Hi5\" title=\"A&ntilde;adir desde Hi5\"> A&ntilde;adir Contactos desde cuenta <strong>Hi5</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=linkedin\"> <img src=\"/administra/Imagenes/linkedin_status_up.png\" alt=\"A&ntilde;adir desde LinkedIn\" title=\"A&ntilde;adir desde LinkedIn\"> A&ntilde;adir Contactos desde cuenta <strong>LinkedIn</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=terra\"> <img src=\"/administra/Imagenes/terra_status_up.png\" alt=\"A&ntilde;adir desde Terra\" title=\"A&ntilde;adir desde Terra\"> A&ntilde;adir Contactos desde cuenta <strong>Terra</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=twitter\"> <img src=\"/administra/Imagenes/twitter_status_up.png\" alt=\"A&ntilde;adir desde Twitter\" title=\"A&ntilde;adir desde Twitter\"> A&ntilde;adir Contactos desde cuenta <strong>Twitter</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=xing\"> <img src=\"/administra/Imagenes/xing_status_up.png\" alt=\"A&ntilde;adir desde Xing\" title=\"A&ntilde;adir desde Xing\"> A&ntilde;adir Contactos desde cuenta <strong>Xing</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=yahoo\"> <img src=\"/administra/Imagenes/yahoo_status_up.png\" alt=\"A&ntilde;adir desde Yahoo\" title=\"A&ntilde;adir desde Yahoo\"> A&ntilde;adir Contactos desde cuenta <strong>Yahoo</strong></a><br/>";
		print "<a href=\"".$variables."modulo=usuarios_anadir&tipo=youtube\"> <img src=\"/administra/Imagenes/youtube_status_up.png\" alt=\"A&ntilde;adir desde Youtube\" title=\"A&ntilde;adir desde Youtube\"> A&ntilde;adir Contactos desde cuenta <strong>Youtube</strong></a><br/>";
		//print "<a href=\"#\"> <img src=\"/administra/Imagenes/facebook_status_down.png\" alt=\"A&ntilde;adir desde Facebook\" title=\"A&ntilde;adir desde Facebook\"> A&ntilde;adir Contactos desde cuenta <strong>Facebook</strong></a><br/>";
	}	
	$formulario_correcto = false;
	if ($tipo=="1a1")
	{
		if (($campos["submit"]=="Guardar")&&($campos["Nombre"]!="") && (($campos["Email"]!="")||($campos["Movil"]!="")))
		{			
			if (($campos["Email"]=="") && ($campos["Movil"]=="")) print "<p class=\"accionError\">Es necesario o un Email o un M&oacute;vil.</p>";
			else
			{
				$idusuario = "";
				//Miramos si el usuario existe en nuestros sistemas
				if ($campos["Email"]!="")
				{
					$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Email = '".$campos["Email"]."' AND Email<>''";
					$result_mensajeria = mysql_query($requete_mensajeria,$db);
					if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
					{
						$listado_mensajeria = mysql_fetch_object($result_mensajeria);
						$idusuario = $listado_mensajeria->Id;
					}
				}
				else
				{
					if ($campos["Movil"]!="")
					{
						$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Movil = '".$campos["Movil"]."' AND Movil<>''";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							$idusuario = $listado_mensajeria->Id;
						}					
					}
				}
				//Guardamos o actualizamos el contacto
				if ($idusuario!="")
				{
					$requete_mensajeria = "SELECT * FROM `Contenidos` WHERE IdPropietario = '".$_SESSION['usuario_id']."'";
					$result_mensajeria = mysql_query($requete_mensajeria,$db);
					if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
					{
						$listado_mensajeria = mysql_fetch_object($result_mensajeria);
						if ($IdTipoContenido=="1") $restaurante = $listado_mensajeria->Titulo;
					}
					if ($restaurante!="") $requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."','Restaurante ".$restaurante." quiere ser tu contacto');";
					else
					{
						$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Id = '".$_SESSION['usuario_id']."'";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							$nombre = $listado_mensajeria->Nombre." ".$listado_mensajeria->Apellidos;
						}
						$requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."', '".$nombre." quiere ser tu contacto');";
					}					
					mysql_query($requete_mensajeria,$db);
					print "<p class=\"accionOK\">Usuario correctamente a&ntilde;adido a nuestros sistemas.</p>";		
				}
				else
				{
					$requete_mensajeria = "INSERT INTO `Usuarios` (`NivelAcceso`,`FechaCreacion`,`ExclusivoMailing`";
					if ($campos["Email"]!="") $requete_mensajeria.= ",`Email`,`AltaBoletin`";
					else $requete_mensajeria.= ",`AltaBoletin`";
					if ($campos["Movil"]!="") $requete_mensajeria.= ",`Movil`,`AltaSMS`";
					else $requete_mensajeria.= ",`AltaSMS`";
					if ($campos["Nombre"]!="") $requete_mensajeria.= ",`Nombre`";
					if ($campos["Apellidos"]!="") $requete_mensajeria.= ",`Apellidos`";
					$requete_mensajeria.= ") VALUES (";
					$requete_mensajeria.= "'6','".date("Y-m-d")."','si'";
					if ($campos["Email"]!="") $requete_mensajeria.= ",'".$campos["Email"]."','si'";
					else $requete_mensajeria.=",'no'";
					if ($campos["Movil"]!="") $requete_mensajeria.= ",'".$campos["Movil"]."','si'";
					else $requete_mensajeria.=",'no'";
					if ($campos["Nombre"]!="") $requete_mensajeria.= ",'".$campos["Nombre"]."'";
					if ($campos["Apellidos"]!="") $requete_mensajeria.= ",'".$campos["Apellidos"]."'";
					$requete_mensajeria.=");";					
					mysql_query($requete_mensajeria,$db);
					$idusuario = mysqli_insert_id($db);
					$requete_mensajeria = "SELECT * FROM `Contenidos` WHERE IdPropietario = '".$_SESSION['usuario_id']."'";
					$result_mensajeria = mysql_query($requete_mensajeria,$db);
					if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
					{
						$listado_mensajeria = mysql_fetch_object($result_mensajeria);
						if ($IdTipoContenido=="1") $restaurante = $listado_mensajeria->Titulo;
					}
					if ($restaurante!="") $requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."','Restaurante ".$restaurante." quiere ser tu contacto');";
					else
					{
						$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Id = '".$_SESSION['usuario_id']."'";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							$nombre = $listado_mensajeria->Nombre." ".$listado_mensajeria->Apellidos;
						}
						$requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."', '".$nombre." quiere ser tu contacto');";
					}
					mysql_query($requete_mensajeria,$db);
					print "<p class=\"accionOK\">Usuario correctamente a&ntilde;adido a nuestros sistemas.</p>";					
				}
			}
		}
		else
		{
			print "<form name=\"ubusca\" action=\"".$variables."&modulo=usuarios_anadir&tipo=1a1\" method=\"post\">";
			if (($campos["submit"]=="Guardar")&&($campos["Nombre"]=="")) print "<label for=\"Nombre\" class=\"Titulo-Contenido-mal\">Nombre (CAMPO OBLIGATORIO)</label><input class=\"campo-contenido\" name=\"Nombre\" type=\"text\" size=\"50\" value=\"".$campos["Nombre"]."\"><br/>";
			else print "<label for=\"Nombre\">Nombre</label><input class=\"campo-contenido\" name=\"Nombre\" type=\"text\" size=\"50\" value=\"".$campos["Nombre"]."\"><br/>";
			print "<label for=\"Apellidos\">Apellidos</label><input class=\"campo-contenido\" name=\"Apellidos\" type=\"text\" size=\"50\" value=\"".$campos["Apellidos"]."\"><br/>";
			if (($campos["submit"]=="Guardar")&&(($campos["Email"]=="")&&($campos["Movil"]==""))) print "<label for=\"Email\" class=\"Titulo-Contenido-mal\">Email (MAIL O TEL&Eacute;FONO M&Oacute;VIL OBLIGATORIO)</label><input class=\"campo-contenido\" name=\"Email\" type=\"text\" size=\"50\" value=\"".$campos["Email"]."\"><br/>";
			else print "<label for=\"Email\">Email</label><input class=\"campo-contenido\" name=\"Email\" type=\"text\" size=\"50\" value=\"".$campos["Email"]."\"><br/>";
			if (($campos["submit"]=="Guardar")&&(($campos["Email"]=="")&&($campos["Movil"]==""))) print "<label for=\"Movil\" class=\"Titulo-Contenido-mal\">M&oacute;vil (MAIL O TEL&Eacute;FONO M&Oacute;VIL OBLIGATORIO)</label><input class=\"campo-contenido\" name=\"Movil\" type=\"text\" size=\"50\" value=\"".$campos["Movil"]."\"><br/>";
			else print "<label for=\"Movil\">M&oacute;vil</label><input class=\"campo-contenido\" type=\"text\" name=\"Movil\" size=\"50\" value=\"".$campos["Movil"]."\"><br/>";
			print "<br/><input class=\"campo-contenido\" type=\"submit\" name=\"submit\" id=\"Guardar\" value=\"Guardar\">";
			print "</form>";
		}
	}
	else
	{		
		if ($tipo!="")
		{		
		$formulario_correcto = (($campos["Usuario"]!="")&&($campos["Contrasena"]!=""));
		if ($formulario_correcto)
		{
			include($_SERVER['DOCUMENT_ROOT']."/herramientas/contactos_externos.php");
			$contactos = contactos_externos($tipo,$campos["Usuario"],$campos["Contrasena"]);
			if ($contactos!=NULL)
			{
				require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
				print "<script>";
				print "function seleccionar_elem(){\n";
				print "elementos=document.ubusca.length\n";
				print "for(i=0;i<elementos;i++)\n";
				print "document.ubusca.elements[i].checked=true\n";
				print "}\n";
				print "function desactivar_elem()\n";
				print "{\n";
				print "elementos=document.ubusca.length\n";
				print "for(i=0;i<elementos;i++)\n";
				print "document.ubusca.elements[i].checked=false\n";
				print "}\n";
				print "</script>";
				print "<a href=\"javascript:desactivar_elem()\">Desactivar todos</a> - ";
				print "<a href=\"javascript:seleccionar_elem()\">Seleccionar todos</a><br/>";
				print "<form name=\"ubusca\" action=\"".$variables."&modulo=usuarios_anadir\" method=\"post\">";
				print "<input class=\"campo-contenido\" type=\"submit\" name=\"submitImport\" id=\"submitImport\" value=\"A&ntilde;adir Contactos a su Sistema\"><br/>";
				foreach ($contactos as $email => $name) {
					$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Email = '".$email."' AND Email<>''";
					$result_mensajeria = mysql_query($requete_mensajeria,$db);
					if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
					{
						$listado_mensajeria = mysql_fetch_object($result_mensajeria);
						print "<input style=\"display:inline;\" class=\"campo-contenido\" type=\"checkbox\" name=\"contacto[]\" value=\"".$email."\" checked> <img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/usuario.png\" title=\"Este usuario ya existe en nuestros sistemas\" alt=\"Este usuario ya existe en nuestros sistemas\"> ";
						if ($listado_mensajeria->Nombre!="") print $listado_mensajeria->Nombre."<br/>";
						else print $email."<br/>";
					}
					else print "<input style=\"display:inline;\" class=\"campo-contenido\" type=\"checkbox\" name=\"contacto[]\" value=\"".$email."\" checked> ".$name."<br/>";
				}
				print "<br/><input class=\"campo-contenido\" type=\"submit\" name=\"submitImport\" id=\"submitImport\" value=\"A&ntilde;adir Contactos a su Sistema\">";
				print "</form>";
				print "<br/><a href=\"javascript:desactivar_elem()\">Desactivar todos</a> - ";
				print "<a href=\"javascript:seleccionar_elem()\">Seleccionar todos</a>";				
			}
			else print "<p class=\"accionError\">No encontrados contactos.</p>";
		}
		else
		{
			print "<form name=\"ubusca\" action=\"".$variables."&modulo=usuarios_anadir&tipo=".$tipo."\" method=\"post\">";
			if (($campos["submit"]=="Extraer Contactos")&&($campos["Usuario"]=="")) print "<label for=\"Usuario\" class=\"Titulo-Contenido-mal\">Usuario (CAMPO OBLIGATORIO): </label><input type=\"text\" class=\"campo-contenido\" name=\"Usuario\" size=\"50\" value=\"".$campos["Usuario"]."\">";
			else print "<label for=\"Usuario\">Usuario: </label><input class=\"campo-contenido\" type=\"text\" name=\"Usuario\" value=\"".$campos["Usuario"]."\">";
			if (($campos["submit"]=="Extraer Contactos")&&($campos["Contrasena"]=="")) print "<br/><label for=\"Contrasena\" class=\"Titulo-Contenido-mal\">Contrase&ntilde;a (CAMPO OBLIGATORIO): </label><input class=\"campo-contenido\" type=\"password\" name=\"Contrasena\" size=\"10\" value=\"".$campos["Contrasena"]."\">";
			else print "<br/><label for=\"Contrasena\">Contrase&ntilde;a: </label><input class=\"campo-contenido\" type=\"password\" name=\"Contrasena\" size=\"10\" value=\"".$campos["Contrasena"]."\">";
			print "<br/><input class=\"campo-contenido\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"Extraer Contactos\">";
			print "</form>";
		}
		}
		else
		{			
			if($campos["submitImport"]!="")
			{
				for ($i=0;($i<count($campos["contacto"]));$i++)
				{				
					$idusuario = "";
					//Miramos si el usuario existe en nuestros sistemas
					if ($campos["contacto"][$i]!="")
					{
						$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Email = '".$campos["contacto"][$i]."' AND Email<>''";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							$idusuario = $listado_mensajeria->Id;
						}
					}
					//Guardamos o actualizamos el contacto
					if ($idusuario!="")
					{
						$requete_mensajeria = "SELECT * FROM `Contenidos` WHERE IdPropietario = '".$_SESSION['usuario_id']."'";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							if ($IdTipoContenido=="1") $restaurante = $listado_mensajeria->Titulo;
						}
						if ($restaurante!="") $requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."','Restaurante ".$restaurante." quiere ser tu contacto');";
						else
						{
							$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Id = '".$_SESSION['usuario_id']."'";
							$result_mensajeria = mysql_query($requete_mensajeria,$db);
							if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
							{
								$listado_mensajeria = mysql_fetch_object($result_mensajeria);
								$nombre = $listado_mensajeria->Nombre." ".$listado_mensajeria->Apellidos;
							}
							$requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."', '".$nombre." quiere ser tu contacto');";
						}
						mysql_query($requete_mensajeria,$db);						
					}
					else
					{
						$requete = "INSERT INTO `Usuarios` ('NivelAcceso','FechaCreacion','ExclusivoMailing'";
						$requete.= ",`Email`,`AltaBoletin`";						
						$requete.= ") VALUES (";
						$requete.= "'6','".date("Y-m-d")."','si'";
						$requete.= ",'".$campos["contacto"][$i].",'si'";
						$requete.=");";
						mysql_query($requete_mensajeria,$db);
						$idusuario = mysqli_insert_id($db);
						$requete_mensajeria = "SELECT * FROM `Contenidos` WHERE IdPropietario = '".$_SESSION['usuario_id']."'";
						$result_mensajeria = mysql_query($requete_mensajeria,$db);
						if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
						{
							$listado_mensajeria = mysql_fetch_object($result_mensajeria);
							if ($IdTipoContenido=="1") $restaurante = $listado_mensajeria->Titulo;
						}
						if ($restaurante!="") $requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."','Restaurante ".$restaurante." quiere ser tu contacto');";
						else
						{
							$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Id = '".$_SESSION['usuario_id']."'";
							$result_mensajeria = mysql_query($requete_mensajeria,$db);
							if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
							{
								$listado_mensajeria = mysql_fetch_object($result_mensajeria);
								$nombre = $listado_mensajeria->Nombre." ".$listado_mensajeria->Apellidos;
							}
							$requete_mensajeria = "INSERT INTO `RelacionUsuario` (`IdUsuario1`,`IdUsuario2`,`ConfirmadaRelacion1`,`ConfirmadaRelacion2`,`FechaInicioRelacion`,`Mensaje`) VALUES ('".$_SESSION['usuario_id']."', '".$idusuario."', 'si', 'no','".date("Y-m-d")."', '".$nombre." quiere ser tu contacto');";
						}
						mysql_query($requete_mensajeria,$db);						
					}		
				}
				print "<p class=\"accionOK\">Usuarios correctamente a&ntilde;adidos a nuestros sistemas.</p>";
			}
		}
	}
	print "</p>";
}
//MÓDULO ENVIO
function envio($variables,$usuario,$idmensaje,$campos)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
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
	if (($usuario!="")||$idmensaje!="")
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
		print "<form action=\"".$variables."modulo=enviando\" enctype=\"multipart/form-data\" method=\"POST\">";
		print "<input type=\"hidden\" id=\"para\" name=\"para\" value=\"".$usuario."\">";
		$requete = "SELECT * FROM `Usuarios` WHERE Id = '".$usuario."';";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
		}		
		print "<p>Enviando mensaje a ".htmlentities($listado->Nombre);
		if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
		print "</p>";
		if ($idmensaje!="") print "<label for=\"TituloMensaje\">Asunto: </label><input class=\"campo-contenido\" id=\"TituloMensaje\" name=\"TituloMensaje\" type=\"text\" value=\"RE: ".$listado_mensaje->Titulo."\" size=\"50\" maxlength=\"255\"><br/>";
		else print "<label for=\"TituloMensaje\">Asunto: </label><input class=\"campo-contenido\" id=\"TituloMensaje\" name=\"TituloMensaje\" type=\"text\" value=\"".$listado_mensaje->Titulo."\" size=\"50\" maxlength=\"255\"><br/>";
		print "<label for=\"mensaje\">Mensaje: </label><br/>";
		if ($idmensaje!="") print "<textarea class=\"campo-contenido\" id=\"mensaje\" name=\"mensaje\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\"><p><strong>Mesaje original:</strong></p><hr>".$listado_mensaje->Mensaje."</textarea>";
		else print "<textarea class=\"campo-contenido\" id=\"mensaje\" name=\"mensaje\" cols=\"70\" rows=\"10\" wrap=\"VIRTUAL\">".$listado_mensaje->Mensaje."</textarea>";
		print "<br/><input type=\"submit\" name=\"button\" class=\"boton\" id=\"submitter\" value=\"Enviar\"/>";
		print "</form>";
	}
	else
	{
		if ($campos["Buscar"]!="")
		{
			$condicion1 = "1=0";
			$condicion2 = "1=0";
			$condicion3 = "1=0";
			if ($campos["Nombre"]!="") $condicion1 = "`Nombre` like '%".$campos["Nombre"]."%'";
			if ($campos["Apellidos"] != "") $condicion2 = "`Apellidos` like '%".$campos["Apellidos"]."%'";
			if ($campos["Email"] != "") $condicion3 = "`Email` like '%".$campos["Email"]."%'";
			$requete = "SELECT `Id`, `Nombre`, `NombreEmpresa`, `Apellidos`, `Direccion`, `Ciudad`, `CP`, `Pais`, `Email`, `Telefono`, `Movil`, `Activado`, `Password`, `Passmd5`, `NivelAcceso`, `FechaCreacion`, `FechaCaducidad`, `FechaUltimoAcceso`  FROM `Usuarios` WHERE $condicion1 or $condicion2 or $condicion3;";
			
			if ($result = mysqli_query($db, $requete))
			{
				print "<ul>";
				while ($listado = mysqli_fetch_object($result))
				{
					print "<li>";
					print "<a href=\"".$variables."modulo=envio&usuario=".$listado->Id."\">".htmlentities($listado->Nombre);
					if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
					print "</a></li>";
				}
				print "</ul>";
			}
			else
			{
				print "<p class=\"accionError\">No se han entontrado usuarios con esas condiciones de b&uacute;squeda.</p>";
			}
		}
		else
		{
			print "<p>Buscando usuarios en el sistema.</p>";
			print "<form name=\"ubusca\" action=\"".$variables."&modulo=envio\" method=\"post\">";
			print "<table>";		
			print "<tr><td><label for=\"Nombre\">Nombre:</label></td><td><input class=\"campo-contenido\" name=\"Nombre\" value=\"".$Nombre."\"></td></tr>";
			print "<tr><td><label for=\"Apellidos\">Apellidos:</label></td><td><input class=\"campo-contenido\" name=\"Apellidos\" value=\"".$Apellidos."\"></td></tr>";
			print "<tr><td><label for=\"Email\">Email:</label></td><td><input class=\"campo-contenido\" name=\"Email\" value=\"".$Email."\"></td></tr>";
			print "</table>";
			print "<input class=\"campo-contenido\" type=\"submit\" name=\"Buscar\" id=\"Buscar\" value=\"Buscar\">";
			print "</form>";
		}
	}
}
//MÓDULO ENVIANDO
function enviando($campos)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete = "INSERT INTO `Mensajeria` (`Titulo`,`Mensaje`,`IdUsuarioDestino`,`IdUsuarioOrigen`,`Fecha`) VALUES ('".$campos["TituloMensaje"]."', '".$campos["mensaje"]."', '".$campos["para"]."', '".$_SESSION['usuario_id']."','".date("Y-m-d")."');";	
	mysqli_query($db,$requete);
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
	$msg = "Ha recibido un mensaje desde su zona privada de <strong>".$_SERVER['SERVER_NAME']."</strong>.";
	include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");
	if (mail($UsuarioEmail, 'Mensaje recibido en su zona personal', $mensaje, $headers)) print "<p class=\"accionOK\"><img src=\"/administra/Imagenes/tick.png\"/> Mensaje enviado con exito ...</p>";
	else print "<p class=\"accionError\"><img src=\"/administra/Imagenes/alerta.png\"/> Mensaje no enviado correctamente ...</p>";
}
//MÓDULO BORRAR
function borrar($idmensaje)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE Id = '".$idmensaje."'";
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) $listado_mensajeria = mysql_fetch_object($result_mensajeria);
	if ($listado_mensajeria->EstadoOrigen =="borrado") 
	{
		$requete = "DELETE FROM `Mensajeria`  WHERE `Id`=".$idmensaje;    
	}
	else
	{
		$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = 'borrado' WHERE `Id`=".$idmensaje;
	}
	mysqli_query($db,$requete);
	print "<p class=\"accionOK\">Mensaje borrado correctamente.</p>";
}
//MÓDULO FAVORITOS
function favoritos($idmensaje,$variables)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	if ($idmensaje!="")
	{
		$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE Id = '".$idmensaje."'";
		$result_mensajeria = mysql_query($requete_mensajeria,$db);
		if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) $listado_mensajeria = mysql_fetch_object($result_mensajeria);
		if ($listado_mensajeria->EstadoDestino =="favorito") 
		{
			$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = NULL WHERE `Id`=".$idmensaje;
			print "<p class=\"accionOK\">Mensaje quitado de favoritos.</p>";
		}
		else 
		{
			$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = 'favorito' WHERE `Id`=".$idmensaje;
			print "<p class=\"accionOK\">Mensaje a&ntilde;adido a favoritos.</p>";
		}
		mysqli_query($db,$requete);
	}
	else
	{
		//Preparamos paginación
		if (!isset($pagina)) $pagina = 0;
		$num_mensajes_pagina = 20;
		$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'";		
		$result_mensajeria = mysql_query($requete_mensajeria,$db);
		$total_mensajes_pagina = mysqli_num_rows($result_mensajeria);
		print "<p><img src=\"/administra/Imagenes/boletin_enviar.png\" title=\"Responder mensaje\" alt=\"Responder mensaje\"> :: Responder mensaje.";
		print "<img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar como favorito\" alt=\"Borrar como favorito\"> :: Borrar mensaje.";
		print "<img src=\"/administra/Imagenes/star.png\" title=\"Borrar como favorito\" alt=\"Borrar como favorito\"> :: Borrar como favorito.<br/></p>";
		//Consultamos el listado de Mensajes pertenecientes a este usuario y favoritos
		$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'  AND `EstadoDestino`='favorito' ORDER BY `Fecha` DESC LIMIT ".($pagina * $num_mensajes_pagina).",".$num_mensajes_pagina;
		$result_mensajeria = mysql_query($requete_mensajeria,$db);
		if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0))
		{
			print "<p>Listado de mensajes favoritos.</p>";
			print "<table name=\"mensajes\" id=\"mensajes\"><tr><th colspan=\"3\">Acciones / Estado</th><th>Asunto</th><th>Mensaje De</th><th>Fecha de Env&iacute;o</th></tr>";
			$par = false;
			while($listado_mensajeria = mysql_fetch_object($result_mensajeria))
			{
				if ($par) 
				{
					print "<tr id=\"par\">";
					$par = false;
				}
				else
				{
					print "<tr>";
					$par = true;
				}
				$requete2_mensajeria = "SELECT `Id`, `Nombre`,`Apellidos`,`NombreEmpresa` FROM `Usuarios` WHERE Id = '".$listado_mensajeria->IdUsuarioOrigen."'";	  
				$result2_mensajeria = mysql_query($requete2_mensajeria,$db);
				$listado2_mensajeria = mysql_fetch_object($result2_mensajeria);
				print "<td class=\"acciones\"><a href=\"".$variables."modulo=envio&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Responder mensaje\" alt=\"Responder mensaje\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
				print "<td class=\"acciones\"><a href=\"".$variables."modulo=borrar&idmensaje=".$listado_mensajeria->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"></a></td>";
				print "<td class=\"acciones\"><a href=\"".$variables."modulo=favoritos&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Borrar como favorito\" alt=\"Borrar como favorito\" src=\"/administra/Imagenes/star.png\"/></a></td>";
				print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado_mensajeria->Titulo)."</strong></a></td>";
				if ($listado2_mensajeria->Empresa!="") print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)." (".htmlentities($listado2_mensajeria->NombreEmpresa).")</strong></a></td>";
				else print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)."</strong></a></td>";
				print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".cambiaf_a_normal($listado_mensajeria->Fecha)."</strong></a></td>";				
				print "</tr>";
			}
			print "</table>";
		}
		else print "<p class=\"accionError\">No tiene mensajes marcados como favoritos.</p>";
  }
}
//MÓDULO USUARIOS
function usuarios($variables,$letra,$grupo)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	//Miro el primer grupo en el que se encuentra
	$requete = "SELECT `PertenenciaGrupos`.IdGrupo, `Grupos`.Nombre FROM `PertenenciaGrupos`, `Grupos` WHERE `PertenenciaGrupos`.IdUsuario='".$_SESSION['usuario_id']."' AND `PertenenciaGrupos`.IdGrupo = `Grupos`.Id AND `Grupos`.VisiblesGrupo='si';";
	
	if ($result = mysqli_query($db, $requete))
	{
		if ($grupo=="") print "<p>Seleccione el grupo del cual quiere mostrar sus contactos:</p>";
		print "<p>";
		while ($listado = mysqli_fetch_object($result))
		{
			print "<a href=\"".$variables."modulo=usuarios&grupo=".$listado->IdGrupo."\">".$listado->Nombre."</a><br/>"; 
		}
		print "<a href=\"".$variables."modulo=usuarios&grupo=directos\">Contactos directos</a><br/>"; 
		print "</p>";
	}
	if ($grupo!="")
	{
		print "<p class=\"letras\">";
		print "<a href=\"".$variables."modulo=usuarios&letra=A&grupo=".$grupo."\">A</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=B&grupo=".$grupo."\">B</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=C&grupo=".$grupo."\">C</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=D&grupo=".$grupo."\">D</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=E&grupo=".$grupo."\">E</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=F&grupo=".$grupo."\">F</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=G&grupo=".$grupo."\">G</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=H&grupo=".$grupo."\">H</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=I&grupo=".$grupo."\">I</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=J&grupo=".$grupo."\">J</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=K&grupo=".$grupo."\">K</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=L&grupo=".$grupo."\">L</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=M&grupo=".$grupo."\">M</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=N&grupo=".$grupo."\">N</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=Ñ&grupo=".$grupo."\">Ñ</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=O&grupo=".$grupo."\">O</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=P&grupo=".$grupo."\">P</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=Q&grupo=".$grupo."\">Q</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=R&grupo=".$grupo."\">R</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=S&grupo=".$grupo."\">S</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=T&grupo=".$grupo."\">T</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=U&grupo=".$grupo."\">U</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=V&grupo=".$grupo."\">V</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=W&grupo=".$grupo."\">W</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=X&grupo=".$grupo."\">X</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=Y&grupo=".$grupo."\">Y</a> |";
		print "<a href=\"".$variables."modulo=usuarios&letra=Z&grupo=".$grupo."\">Z</a>";
		print "</p>";
		//Compruebo si se ha consultado una letra o no
		If ($letra!="")
		{
			print "<p>Pulse sobre el contacto al que desea mandar un mensaje.</p>";
			if ($grupo!="directos")
			{
				$requete = "SELECT `Usuarios`.Id,`Usuarios`.Nombre,`Usuarios`.NombreEmpresa,`Usuarios`.Nick, `Usuarios`.NivelAcceso FROM `Usuarios`,`PertenenciaGrupos` WHERE IdUsuario = `Usuarios`.Id AND (`IdGrupo`='".$grupo."' OR `Usuarios`.NivelAcceso='1') AND `Usuarios`.NombreEmpresa LIKE \"".$letra."%\"";
				
				if ($result = mysqli_query($db, $requete))
				{
					print "<ul>";
					while($listado = mysqli_fetch_object($result))
					{		
						print "<li>";
						print "<a href=\"".$variables."modulo=envio&usuario=".$listado->Id."\">".htmlentities($listado->Nombre);
						if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
						print "</a></li>";
					}
					print "</ul>";
				}
			}
			else
			{
				$requete = "SELECT `Usuarios`.Id,`Usuarios`.Nombre,`Usuarios`.NombreEmpresa,`Usuarios`.Nick, `Usuarios`.NivelAcceso FROM `Usuarios`,`RelacionUsuario` WHERE ((`IdUsuario1`=' ".$_SESSION['usuario_id']."' AND `Usuarios`.Id = `RelacionUsuario`.IdUsuario2 AND `RelacionUsuario`.ConfirmadaRelacion2='si') OR (`IdUsuario2`=' ".$_SESSION['usuario_id']."' AND `Usuarios`.Id = `RelacionUsuario`.IdUsuario1 AND `RelacionUsuario`.ConfirmadaRelacion1='si')) AND (`Usuarios`.NombreEmpresa LIKE \"".$letra."%\" OR `Usuarios`.Nombre LIKE \"".$letra."%\")";
				
				if ($result = mysqli_query($db, $requete))
				{
					print "<ul>";
					while($listado = mysqli_fetch_object($result))
					{		
						print "<li>";
						print "<a href=\"".$variables."modulo=envio&usuario=".$listado->Id."\">".htmlentities($listado->Nombre);
						if ($listado->NombreEmpresa!="") print " (".htmlentities($listado->NombreEmpresa).")";
						print "</a></li>";
					}
					print "</ul>";				
				}
			}
		}
		else
		{
			print "<p>Seleccione la letra para ver contactos.</p>";
		}
	}
}
//MÓDULO MENSAJES
function mensajes($idmensaje,$variables)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	//Preparamos paginación
	if (!isset($pagina)) $pagina = 0;
	$num_mensajes_pagina = 20;
	$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'";		
	$result_mensajeria = mysql_query($requete_mensajeria,$db);
	$total_mensajes_pagina = mysqli_num_rows($result_mensajeria);
	if ($idmensaje=="")
	{
		print "<p><img src=\"/administra/Imagenes/email.png\" alt=\"Mensaje sin abrir\" title=\"Mensaje sin abrir\"> :: Mensaje sin abrir. ";
		print "<img src=\"/administra/Imagenes/email_abierto.png\" title=\"Mensaje abierto\" alt=\"Mensaje abierto\"> :: Mensaje abierto. ";
		print "<img src=\"/administra/Imagenes/boletin_enviar.png\" alt=\"Responder mensaje\" title=\"Responder mensaje\"> :: Responder mensaje.<br/>";
		print "<img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"> :: Borrar mensaje.";
		print "<img src=\"/administra/Imagenes/star.png\" alt=\"Marcar como favorito\" title=\"Marcar como favorito\"> :: Marcar como favorito.<br/></p>";
		//Consultamos el listado de Mensajes pertenecientes a este usuario
		$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'  AND (`EstadoDestino`<>'borrado' || `EstadoDestino` IS NULL) ORDER BY `Fecha` DESC LIMIT ".($pagina * $num_mensajes_pagina).",".$num_mensajes_pagina;
		$result_mensajeria = mysql_query($requete_mensajeria,$db);
		if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0))
		{
			print "<p>Listado de mensajes recibidos.</p>";
			print "<table name=\"mensajes\" id=\"mensajes\"><tr><th colspan=\"4\">Acciones / Estado</th><th>Asunto</th><th>Mensaje De</th><th>Fecha de Env&iacute;o</th></tr>";
			$par = false;
			while($listado_mensajeria = mysql_fetch_object($result_mensajeria))
			{
				if ($par) 
				{
					print "<tr id=\"par\">";
					$par = false;
				}
				else
				{
					print "<tr>";
					$par = true;
				}
				$requete2_mensajeria = "SELECT `Id`, `Nombre`,`Apellidos`,`NombreEmpresa` FROM `Usuarios` WHERE Id = '".$listado_mensajeria->IdUsuarioOrigen."'";	  
				$result2_mensajeria = mysql_query($requete2_mensajeria,$db);
				$listado2_mensajeria = mysql_fetch_object($result2_mensajeria);
				if ($listado_mensajeria->Visto=="")
				{
					print "<td class=\"acciones\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Mensaje sin abrir\" alt=\"Mensaje sin abrir\" src=\"/administra/Imagenes/email.png\"/></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=envio&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" alt=\"Responder mensaje\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=borrar&idmensaje=".$listado_mensajeria->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"></a></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=favoritos&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Marcar como favorito\" alt=\"Marcar como favorito\" src=\"/administra/Imagenes/star.png\"/></a></td>";
					print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado_mensajeria->Titulo)."</strong></a></td>";
					if ($listado2_mensajeria->Empresa!="") print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)." (".htmlentities($listado2_mensajeria->NombreEmpresa).")</strong></a></td>";
					else print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)."</strong></a></td>";
					print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\"><strong>".cambiaf_a_normal($listado_mensajeria->Fecha)."</strong></a></td>";
				}
				else
				{
					print "<td class=\"acciones\"><img title=\"Mensaje abierto\" alt=\"Mensaje abierto\" src=\"/administra/Imagenes/email_abierto.png\"/></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=envio&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Responder mensaje\" alt=\"Responder mensaje\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=borrar&idmensaje=".$listado_mensajeria->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"></a></td>";
					print "<td class=\"acciones\"><a href=\"".$variables."modulo=favoritos&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Marcar como favorito\" alt=\"Marcar como favorito\" src=\"/administra/Imagenes/star.png\"/></a></td>";
					print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\">".htmlentities($listado_mensajeria->Titulo)."</a></td>";
					if ($listado2_mensajeria->Empresa!="") print "<td><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\">".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)." (".htmlentities($listado2_mensajeria->NombreEmpresa).")</a></td>";
					else print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\">".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)."</a></td>";
					print "<td class=\"mensajes\"><a href=\"".$variables."modulo=mensajes&idmensaje=".$listado_mensajeria->Id."\">".cambiaf_a_normal($listado_mensajeria->Fecha)."</a></td>";
				}
				print "</tr>";
			}
			print "</table>";
		}
		else print "<p class=\"accionError\">No tiene mensajes en la bandeja de entrada.</p>";
	}
	else
	{  
		$requete_mensajeria = "SELECT * FROM `Mensajeria` WHERE Id = '".$idmensaje."'";
		$result_mensajeria = mysql_query($requete_mensajeria,$db);
		if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) $listado_mensajeria = mysql_fetch_object($result_mensajeria);
		if ($listado_mensajeria->Visto =="") 
		{
			$requete = "UPDATE `Mensajeria`  SET `Visto` = '".date("Y-m-d")."' WHERE `Id`=".$idmensaje;
			mysqli_query($db,$requete);
		}
 		print "<p><img src=\"/administra/Imagenes/email.png\" title=\"Mensaje sin abrir\" alt=\"Mensaje sin abrir\"> :: Mensaje sin abrir. ";
		print "<img src=\"/administra/Imagenes/email_abierto.png\" title=\"Mensaje abierto\" alt=\"Mensaje abierto\"> :: Mensaje abierto. ";
		print "<img src=\"/administra/Imagenes/boletin_enviar.png\" title=\"Responder mensaje\" alt=\"Responder mensaje\"> :: Responder mensaje.<br/>";
		print "<img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"> :: Borrar mensaje.";
		print "<img src=\"/administra/Imagenes/star.png\" title=\"Marcar como favorito\" alt=\"Marcar como favorito\"> :: Marcar como favorito.<br/></p>";
		$requete2_mensajeria = "SELECT `Id`, `Nombre`,`Apellidos`,`NombreEmpresa` FROM `Usuarios` WHERE Id = '".$listado_mensajeria->IdUsuarioOrigen."'";	  
		$result2_mensajeria = mysql_query($requete2_mensajeria,$db);
		$listado2_mensajeria = mysql_fetch_object($result2_mensajeria);
		print "<table><tr><th colspan=\"4\">Acciones / Estado</th><th>Asunto</th><th>Mensaje De</th><th>Fecha de Env&iacute;o</th></tr><tr>";
		print "<td class=\"acciones\"><img id=\"imagen".$listado_mensajeria->Id."\" alt=\"Mensaje sin abrir\" title=\"Mensaje sin abrir\" src=\"/administra/Imagenes/email.png\"/></td>";
		print "<td class=\"acciones\"><a href=\"".$variables."modulo=envio&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" alt=\"Responder mensaje\" title=\"Responder mensaje\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
		print "<td class=\"acciones\"><a href=\"".$variables."modulo=borrar&idmensaje=".$listado_mensajeria->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"Borrar mensaje\" alt=\"Borrar mensaje\"></a></td>";
		print "<td class=\"acciones\"><a href=\"".$variables."modulo=favoritos&idmensaje=".$listado_mensajeria->Id."\"><img id=\"imagen".$listado_mensajeria->Id."\" title=\"Marcar como favorito\" alt=\"Marcar como favorito\" src=\"/administra/Imagenes/star.png\"/></a></td>";
		print "<td class=\"mensajes\"><strong>".htmlentities($listado_mensajeria->Titulo)."</strong></td>";
		if ($listado2_mensajeria->Empresa!="") print "<td class=\"mensajes\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)." (".htmlentities($listado2_mensajeria->NombreEmpresa).")</strong></td>";
		else print "<td class=\"mensajes\"><strong>".htmlentities($listado2_mensajeria->Nombre)." ".htmlentities($listado2_mensajeria->Apellidos)."</strong></td>";
		print "<td class=\"mensajes\"><strong>".cambiaf_a_normal($listado_mensajeria->Fecha)."</strong></td>";
		print "</tr></table>";
		print "<p><strong>Cuerpo del mensaje:</strong></p>";
		print $listado_mensajeria->Mensaje;
	}
}
// Navegación
if ($area!="") $variables = "?area=".$area."&";
else $variables = "?";
//SE HACEN LAS COMPROBACIONES DE SEGURIDAD, SI HAY SESIÓN CONTINÚA
if (!empty($_SESSION['usuario_id']))
{
	//PINTO LA BARRA DE SUBHERRAMIENTAS	
	print "<table cellpading=0 cellspacing=0 id=\"menuBoletin\">";
	print "<tr><td class=\"iconos\"><a href=\"".$variables."modulo=usuarios\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/usuarios-menu.png\"/><br/>Contactos</a></td>";
	print "<td class=\"iconos\"><a href=\"".$variables."modulo=mensajes\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/mail-box.png\"/><br/>Entrantes</a></td>";
	print "<td class=\"iconos\"><a href=\"".$variables."modulo=envio\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/nuevo-mail.png\"/><br/>Enviar</a></td>";
	print "<td class=\"iconos\"><a href=\"".$variables."modulo=favoritos\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/favoritos.png\"/><br/>Favoritos</a></td>";	
	print "</tr></table>";	
	switch ($modulo)
	{
		case "borrar":
			borrar($idmensaje);
		break;
		case "favoritos":
			favoritos($idmensaje,$variables);
		break;
		case "usuarios_anadir":
			usuarios_anadir($variables,$tipo,$_POST);
		break;
		case "usuarios":
			//subherramientas de Usuarios			
			print "<p><a href=\"".$variables."modulo=usuarios_anadir\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/nuevo_usuario.png\"/> A&ntilde;adir nuevos usuarios</a></p>";			
			usuarios($variables,$letra,$grupo);
		break;
		case "mensajes":
			mensajes($idmensaje,$variables);
		break;
		case "envio":
			envio($variables,$usuario,$idmensaje,$_POST);
		break;
		case "enviando":
			enviando($_POST,$tipo);
		break;
		default:
			home($variables);
		break;
	}
}
else //SI NO HAY SESIÓN
{
	$codigos_body.= "
		<p class=\"accionError\">NO TIENE PERMISOS.</p>"; 
}
print $codigos_body;
?>
