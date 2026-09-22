<?php
//NECESITA:
// - Conexión a base de datos
// - $IdContenido: Id del contenido a visualizar
// - $TipoContenido: Tipo de contenido: codigo, contenido
// - $PlantillaContenido: Muestra si es contenido desplegado o no
// - $IdSeccion: Id de la seccion donde esta para enlazar a ver el contenido desplegado (Solo necesario en Contenido sin deplegar)
//PRODUCE:
// - Devuelve por pantalla el contenido a visualizar
if ($PlantillaContenido=="Contenido desplegado")
{
switch ($TipoContenido)
{
	case "codigo":
		$requete_bloque_contenido = "SELECT `Breve` FROM `Contenidos` WHERE Id = ".$IdContenido;
		$result_bloque_contenido = mysql_query($requete_bloque_contenido,$db);
		$listado_bloque_contenido = mysql_fetch_object($result_bloque_contenido);
		if ($listado_bloque_contenido->NomFich!="") require($_SERVER['DOCUMENT_ROOT']."/codigos/".$listado_bloque_contenido->NomFich);
		else eval($listado_bloque_contenido->Breve);
		break;
	case "formulario":
		print "<h3>".$listado->Titulo."</h3>";
		if (!isset($pagina_formulario)) $pagina_formulario = 0;
		require($_SERVER['DOCUMENT_ROOT']."/herramientas/bloque_formulario.php");
		break;
	case "contenido":	
		// Hacemos una consulta para ver cuantas ampliaciones de contenido tiene el contenido actual
		$requete_bloque_contenido = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdPadre = ".$IdContenido." AND IdContenido = `Contenidos`.Id AND `Visibilidad`='visible' ORDER BY `Orden`";
		$result_bloque_contenido = mysql_query($requete_bloque_contenido,$db);
		// Listamos las ampliaciones de contenido existentes
		if (($result_bloque_contenido) && (mysqli_num_rows($result_bloque_contenido)>0))
		{
			while($listado_bloque_contenido = mysql_fetch_object($result_bloque_contenido))
			{
				$imagenTratada= false;
				switch ($listado_bloque_contenido->Tipo) 
				{
					case "texto":
						print "<h3>".$listado_bloque_contenido->Titulo."</h3>";
						//Miramos si el siguiente bloque es una imagen para ponerlo en relación a la imagen
						$requete_bloque_contenido2 = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdPadre = ".$IdContenido." AND IdContenido = `Contenidos`.Id AND `Visibilidad`='visible' AND `Orden`=".($listado_bloque_contenido->Orden+1)." AND `Tipo`='imagen' AND `Breve`<>'' AND `Breve`<>'centrado' ORDER BY `Orden`";
						$result_bloque_contenido2 = mysql_query($requete_bloque_contenido2,$db);
						if (($result_bloque_contenido2) && (mysqli_num_rows($result_bloque_contenido2)>0))
						{
							//Ignoraremos la siguiente imagen
							$imagenTratada= true;
							//Cargamos el texto
							if ($listado_bloque_contenido->Breve!="")
							{
								print "<div class=\"textoContenidoFoto\">";
								print $listado_bloque_contenido->Breve;
								print "</div>";
							}							
							//El siguiente bloque es una imagen y se ha fijado alineación con respecto al texto
							//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen	
							switch ($listado_bloque_contenido2->Breve) 
							{
								case "centrado":
									print "<div class=\"imagenContenidoCentrado\">";
									break;
								case "izquierda":
									print "<div class=\"imagenContenidoIzquierda\">";
									break;
								case "derecha":
									print "<div class=\"imagenContenidoDerecha\">";
									break;
								default:
									print "<div class=\"imagenContenido\">";
									break;
							}
							if (($listado_bloque_contenido2->Redireccionar!="")&&($listado_bloque_contenido2->Redireccionar!="http://")) print "<a href=\"".$listado_bloque_contenido2->Redireccionar."\" target=\"_blank\">";
							if ($listado_bloque_contenido2->AnchoFoto2!="") print "<a href=\"/Imagenes/".$listado_bloque_contenido2->Foto2."\" target=\"_blank\">";
							print "<img src=\"/Imagenes/".$listado_bloque_contenido2->Foto."\" alt=\"".$listado_bloque_contenido2->Alternativo."\"/>";
							if ($listado_bloque_contenido2->AnchoFoto2!="") print "</a>";
							if ($listado_bloque_contenido2->Redireccionar!="") print "</a>";
							print "</div>";
						}
						else
						{
							//El siguiente bloque no es una imagen, por lo que se pone como debe ir por defecto
							if ($listado_bloque_contenido->Breve!="") 
							{
								print "<div class=\"textoContenido\">";
								print $listado_bloque_contenido->Breve;
								print "</div>";
							}					
						}
					break;
					case "imagen":
						//Si la imagen se trató con el texto se ignora
						if ($imagenTratada) $imagenTratada=false;
						else
						{
							//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen	
							switch ($listado_bloque_contenido->Breve) 
							{
								case "centrado":
									print "<div class=\"imagenContenidoCentrado\">";
									break;
								case "izquierda":
									print "<div class=\"imagenContenidoIzquierda\">";
									break;
								case "derecha":
									print "<div class=\"imagenContenidoDerecha\">";
									break;
								default:
									print "<div class=\"imagenContenido\">";
									break;
							}
							if (($listado_bloque_contenido->Redireccionar!="")&&($listado_bloque_contenido->Redireccionar!="http://")) print "<a href=\"".$listado_bloque_contenido->Redireccionar."\" target=\"_blank\">";
							if ($listado_bloque_contenido->AnchoFoto2!="") print "<a href=\"/Imagenes/".$listado_bloque_contenido->Foto2."\" target=\"_blank\">";
							print "<img src=\"/Imagenes/".$listado_bloque_contenido->Foto."\" alt=\"".$listado_bloque_contenido->Alternativo."\"/>";
							if ($listado_bloque_contenido->AnchoFoto2!="") print "</a>";
							if ($listado_bloque_contenido->Redireccionar!="") print "</a>";
							print "</div>";
						}
					break;
					case "enlace":
						print "<p class=\"enlaceContenido\">";
						if (strstr($listado_bloque_contenido->Redireccionar,$_SERVER['SERVER_NAME'])) print "<a href=\"".$listado_bloque_contenido->Redireccionar."\" class=\"EnlaceContenidoAmpliado\">".$listado_bloque_contenido->Titulo."</a>";
						else print "<a class=\"EnlaceContenidoAmpliado\" href=\"".$listado_bloque_contenido->Redireccionar."\" target=\"_blank\">".$listado_bloque_contenido->Titulo."</a>";
						print "</p>";
					break;
					case "formulario":
						print "<h3>".$listado->Titulo."</h3>";
						if (!isset($pagina_formulario)) $pagina_formulario = 0;
						require($_SERVER['DOCUMENT_ROOT']."/herramientas/bloque_formulario.php");
					break;
					case "fichero":
						print "<p class=\"ficheroContenido\">";
						print "<a href=\"/Recursos/".$listado_bloque_contenido->Recurso."\">".$listado_bloque_contenido->Titulo."</a>";						
						print "</p>";
					break;
					case "video":
						if ($listado_bloque_contenido->Redireccionar=="")
						{ // Si es VIDEO PROPIO
							print "<script type=\"text/javascript\" src=\"/herramientas/Scripts/swfobject.js\"></script>";
							print "<div id=\"flowplayerholder\" align=\"center\"></div>";
							print "<script type=\"text/javascript\">\n";
							print "var fo = new SWFObject(\"/herramientas/FlowPlayer.swf\", \"FlowPlayer\", \"".$listado_bloque_contenido->AnchoFoto."\", \"".$listado_bloque_contenido->AnchoFoto."\", \"7\", \"#ffffff\", true);\n";
							print "fo.addVariable(\"config\", \"{ showPlayListButtons: true, playList: [ {overlayId: 'play' }, {url:'/Recursos/".$listado_bloque_contenido->Recurso."' } ], initialScale: 'fit' }\");\n";
							print "fo.write(\"flowplayerholder\");\n";
							print "// ]]>\n";
							print "</script>\n";
						}
						else
						{ //SI ES VIDEO YOUTUBE
							print "<div align=\"center\">";
							print "<object width=\"".$listado_bloque_contenido->AnchoFoto."\" height=\"".$listado_bloque_contenido->AltoFoto."\"><param name=\"movie\" value=\"".$listado_bloque_contenido->Redireccionar."\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"".$listado_bloque_contenido->Redireccionar."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$listado_bloque_contenido->AnchoFoto."\" height=\"".$listado_bloque_contenido->AltoFoto."\"></embed></object>";
							print "</div>";
						}
					break;
					case "mapa":	
					break;
					case "foro":
						function cambiaf_a_normal($fecha)
						{
							ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
							$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
							return $lafecha;
						} 
						$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdPadre = ".$IdContenido." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='foro' ORDER BY `Orden`";
						
						$listado = mysqli_fetch_object($result);
						print "<h3>".htmlentities($listado->Titulo)."</h3>";
						print $listado->Breve;
						if (!isset($comentar))
						{
							if (!isset($vermensaje))
							{
								$foro = $listado->Id;
								print "<div id=\"foro\">";
								$requete2 = "SELECT * FROM `ForoConfiguracion` WHERE `Id` = '".$foro."'";
								
								$listado2 = mysqli_fetch_object($result2);
								$foro_Validacion = $listado2->Validacion; //Exigir que se valide el mensaje del foro antes de visualizarse
								$foro_EnvioAviso = $listado2->EnvioAviso; //Enviar aviso al usuario que metió un comentario si le responden un mensaje
								$foro_CorreoAviso = $listado2->EnvioAviso; //Enviar aviso por email al responsable /es del foro						
								$foro_Votacion = $listado2->Votacion;  //Permitir votación en los mensajes
								$foro_PermitirRespuestas = $listado2->PermitirRespuestas;  //Permitir respuestas a los mensajes
								$foro_ForoAnonimoEscritura = $listado2->ForoAnonimoEscritura; //Permitir escribir a anónimos
								$foro_NivelEscritura = $listado2->NivelEscritura; //Nivel de usuario exigido para escribir
								$foro_Max1Mensaje = $listado2->Max1Mensaje; // Permitir o no escribir más de un mensaje en el foro
								if ($foro_Validacion=="si") $condiciones = "AND `Validado` = 'si'";
								$requete2 = "SELECT * FROM `ForoMensajes` WHERE `IdForo` = '".$foro."' AND `IdPadre` IS NULL ".$condiciones." ORDER BY `Fecha` LIMIT 0,15";
								
								if ($result2 = mysqli_query($db, $requete2))
								{
									print "<table><tr><th width=\"50%\">Titulo</th><th>Usuario</th><th>Vistas</th>";
									if ($foro_PermitirRespuestas=="si") print "<th>Respuestas</th>";
									//print "<th>Fecha</th>"; DE MOMENTO NO MOSTRAR FECHA
									print "</tr>";
									while($listado2 = mysqli_fetch_object($result2))
									{
										print "<tr>";
										$url = $_SERVER['SCRIPT_NAME'];
										if (count($_SERVER['argv'])>0)
										{
											//Ponemos los enlaces a ver el mensaje con sus argumentos.
											for ($i=0; $i<count($_SERVER['argv']); $i++)
											{
												if ($i==0) $url = $url."?".$_SERVER['argv'][$i];
												else $url = $url."&".$_SERVER['argv'][$i];
											}
											print "<td><a href=\"".$url."&vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado2->Titulo)."</a></td>";
											$requete3 = "SELECT * FROM `Usuarios` WHERE Id = '".$listado2->IdAutor."'";
											
											$listado3 = mysqli_fetch_object($result3);
											if ($listado3->Nick!="") print "<td><a href=\"".$url."&vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nick)."</a></td>";
											else
											{
												if ($listado3->Empresa!="") print "<td><a href=\"".$url."&vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)." (".htmlentities($listado3->NombreEmpresa).")</a></td>";
												else print "<td><a href=\"".$url."&vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)."</a></td>";
											}
											print "<td><a href=\"".$url."&vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".$listado2->Vistas."</a></td>";
										}
										else
										{
											//Ponemos los enlaces a ver el mensaje sin argumentos ya que no tiene.
											print "<td><a href=\"".$url."?vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado2->Titulo)."</a></td>";
											$requete3 = "SELECT * FROM `Usuarios` WHERE Id = '".$listado2->IdAutor."'";
											
											$listado3 = mysqli_fetch_object($result3);
											if ($listado3->Nick!="") print "<td><a href=\"".$url."?vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nick)."</a></td>";
											else
											{
												if ($listado3->Empresa!="") print "<td><a href=\"".$url."?vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)." (".htmlentities($listado3->NombreEmpresa).")</a></td>";
												else print "<td><a href=\"".$url."?vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)."</a></td>";
											}
											print "<td><a href=\"".$url."?vermensaje=".$listado2->Id."\" title=\"Enlace a ver mensaje\">".$listado2->Vistas."</a></td>";
										}
										//Calculamos las respuestas
										$requete3 = "SELECT * FROM `ForoMensajes` WHERE IdForo = ".$foro." AND IdPadre='".$listado2->Id."'";
										
										$total = mysqli_num_rows($result3);
										if ($foro_PermitirRespuestas=="si") print "<td>".$total."</td>";
										//print "<td>".cambiaf_a_normal($listado2->Fecha)."</td>"; DE MOMENTO NO MOSTRAMOS LA FECHA
										if ($foro_Votacion=="si")
										{
											print "<span class=\"comentarioValoracion\">";
											print "VALORACI&Oacute;N:";
											if ($listado2->Votacion>=1) print "<img src=\"/Plantillas/Imagenes/estrella_llena.gif\" alt=\"Valoraci&oacute;n una estrella llena\">";
											else print "<img src=\"/Plantillas/Imagenes/estrella_vacia.gif\" alt=\"Valoraci&oacute;n una estrella vacia\">";
											if ($listado2->Votacion>=2) print "<img src=\"/Plantillas/Imagenes/estrella_llena.gif\" alt=\"Valoraci&oacute;n una estrella llena\">";
											else print "<img src=\"/Plantillas/Imagenes/estrella_vacia.gif\" alt=\"Valoraci&oacute;n una estrella vacia\">";
											if ($listado2->Votacion>=3) print "<img src=\"/Plantillas/Imagenes/estrella_llena.gif\" alt=\"Valoraci&oacute;n una estrella llena\">";
											else print "<img src=\"/Plantillas/Imagenes/estrella_vacia.gif\" alt=\"Valoraci&oacute;n una estrella vacia\">";
											if ($listado2->Votacion>=4) print "<img src=\"/Plantillas/Imagenes/estrella_llena.gif\" alt=\"Valoraci&oacute;n una estrella llena\">";
											else print "<img src=\"/Plantillas/Imagenes/estrella_vacia.gif\" alt=\"Valoraci&oacute;n una estrella vacia\">";
											if ($listado2->Votacion>=5) print "<img src=\"/Plantillas/Imagenes/estrella_llena.gif\" alt=\"Valoraci&oacute;n una estrella llena\">";
											else print "<img src=\"/Plantillas/Imagenes/estrella_vacia.gif\" alt=\"Valoraci&oacute;n una estrella vacia\">";
											print "</span>";
										}
										print "</tr>";
									}
									print "</table>";
								}
								else 
								{
									print "<p>No hay mensajes.</p>";
								}
								$url = $_SERVER['SCRIPT_NAME'];
								if (count($_SERVER['argv'])>0)
								{	  
									for ($i=0; $i<count($_SERVER['argv']); $i++)
									{
										if ($i==0) $url = $url."?".$_SERVER['argv'][$i];
										else $url = $url."&".$_SERVER['argv'][$i];
									}
									print "<a title=\"Ver m&aacute;s Mensajes\" class=\"boton2\" href=\"".$url."&mas=1\">Ver M&aacute;s</a>";
									print "<a title=\"Comentar\" class=\"boton2\" href=\"".$url."&comentar=".$foro."\">Comentar</a>";
								}
								else 
								{
									print "<a title=\"Ver m&aacute;s Mensajes\" class=\"boton2\" href=\"".$url."?mas=1\">Ver M&aacute;s</a>";
									print "<a title=\"Comentar\" class=\"boton2\" href=\"".$url."?comentar=".$foro."\">Comentar</a>";
								}
								print "</div>";
							}
							else
							{
								$foro = $listado->Id;
								print "<div id=\"foroRespuestas\">";
								$requete2 = "SELECT * FROM `ForoConfiguracion` WHERE `Id` = '".$foro."'";
								
								$listado2 = mysqli_fetch_object($result2);
								$foro_Validacion = $listado2->Validacion; //Exigir que se valide el mensaje del foro antes de visualizarse
								$foro_EnvioAviso = $listado2->EnvioAviso; //Enviar aviso al usuario que metió un comentario si le responden un mensaje
								$foro_CorreoAviso = $listado2->EnvioAviso; //Enviar aviso por email al responsable /es del foro						
								$foro_Votacion = $listado2->Votacion;  //Permitir votación en los mensajes
								$foro_PermitirRespuestas = $listado2->PermitirRespuestas;  //Permitir respuestas a los mensajes
								$foro_ForoAnonimoEscritura = $listado2->ForoAnonimoEscritura; //Permitir escribir a anónimos
								$foro_NivelEscritura = $listado2->NivelEscritura; //Nivel de usuario exigido para escribir
								$foro_Max1Mensaje = $listado2->Max1Mensaje; // Permitir o no escribir más de un mensaje en el foro
								//Actualizamos las Vistas
								$requete2 = "SELECT * FROM `ForoMensajes` WHERE `Id` = '".$vermensaje."'";
								
								$listado2 = mysqli_fetch_object($result2);
								//Actualizamos las Vistas
								$vistas = $listado2->Vistas;
								$vistas++;
								$requete3 = "UPDATE `ForoMensajes` SET `Vistas` = '".$vistas."' WHERE `Id`='".$vermensaje."'";
								mysql_query($requete3,$db);
								print "<h4>".htmlentities($listado2->Titulo)."</h4>";
								print "<p><strong>".cambiaf_a_normal($listado2->Fecha)."</strong></p>";
								$requete3 = "SELECT * FROM `Usuarios` WHERE Id = '".$listado2->IdAutor."'";
								
								$listado3 = mysqli_fetch_object($result3);
								if ($listado3->Nick!="") print "<img src=\"/administra/Imagenes/usuario_profesional.png\"/>".htmlentities($listado3->Nick)."<br/>";
								else
								{
									if ($listado3->Empresa!="") print "<img src=\"/administra/Imagenes/usuario_profesional.png\"/>".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)." (".htmlentities($listado3->NombreEmpresa).")<br/>";
									else print "<img src=\"/administra/Imagenes/usuario.png\"/>".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)."<br/>";
								}
								print "<p>".htmlentities($listado2->Mensaje)."</p>";
								print "<hr/>";
								$requete2 = "SELECT * FROM `ForoMensajes` WHERE `IdPadre` = '".$listado2->Id."' ORDER BY `Fecha`";
								
								if ($result2 = mysqli_query($db, $requete2))
								{
									print "<h4>Respuestas:</h4>";
									while($listado2 = mysqli_fetch_object($result2))
									{
										print "<h5><table><tr><th>T&iacute;tulo</th><th>Usuario</th><th>Fecha Env&iacute;o</th></tr><tr><td>".$listado2->Titulo."</td>";
										$requete3 = "SELECT * FROM `Usuarios` WHERE Id = '".$listado2->IdAutor."'";
										
										$listado3 = mysqli_fetch_object($result3);
										if  ($listado3->Nick!="") print "<td><img src=\"/administra/Imagenes/usuario_profesional.png\"/>".htmlentities($listado3->Nick)."</td>";
										else
										{
											if ($listado3->Empresa!="") print "<td><img src=\"/administra/Imagenes/usuario_profesional.png\"/>".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)." (".htmlentities($listado3->NombreEmpresa).")</td>";
											else print "<td><img src=\"/administra/Imagenes/usuario.png\"/>".htmlentities($listado3->Nombre)." ".htmlentities($listado3->Apellidos)."</td>";
										}
										print "<td><strong>".cambiaf_a_normal($listado2->Fecha)."</strong></td></tr></table><br/></h5>";
										print "<p><strong>Mensaje:</strong><br/>".htmlentities($listado2->Mensaje)."</p>";
										print "<hr/>";
									}
								}
								print "<a class=\"boton2\" href=\"".$_SERVER['HTTP_REFERER']."\" title=\"Volver\">Volver</a>";
								// Pintamos el botón de responder
								$url = $_SERVER['SCRIPT_NAME'];
								if (count($_SERVER['argv'])>0)
								{	  
									for ($i=0; $i<count($_SERVER['argv']); $i++)
									{
										if ($i==0) $url = $url."?".$_SERVER['argv'][$i];
										else $url = $url."&".$_SERVER['argv'][$i];
									}
									print "<a title=\"Comentar\" class=\"boton2\" href=\"".$url."&comentar=".$foro."&respuesta=".$listado2->Id."\">Responder</a>";
								}
								else
								{									
									print "<a title=\"Comentar\" class=\"boton2\" href=\"".$url."?comentar=".$foro."&respuesta=".$listado2->Id."\">Responder</a>";
								}
								print "</div>";
							}
						}
						else
						{
							$foro = $listado->Id;
							print "<div class=\"bloque\">";
							require($_SERVER['DOCUMENT_ROOT']."/herramientas/foro_comentar.php");
							print "</div>";
						}
					break;
				}
			}
		}
		break;
}
}
else
{
	switch ($TipoContenido)
	{
	case "codigo":
		$requete_bloque_contenido = "SELECT `Breve`,`NomFich` FROM `Contenidos` WHERE Id = ".$IdContenido;
		$result_bloque_contenido = mysql_query($requete_bloque_contenido,$db);
		$listado_bloque_contenido = mysql_fetch_object($result_bloque_contenido);
		if ($listado_bloque_contenido->NomFich!="") require($_SERVER['DOCUMENT_ROOT']."/codigos/".$listado_bloque_contenido->NomFich);
		else eval($listado_bloque_contenido->Breve);
		break;
	case "formulario":
		print "<h3>".$listado->Titulo."</h3>";
		if (!isset($pagina_formulario)) $pagina_formulario = 0;
		require($_SERVER['DOCUMENT_ROOT']."/herramientas/bloque_formulario.php");
		break;
	case "contenido":	
		// Hacemos una consulta para ver los datos del contenido
		$requete_bloque_contenido = "SELECT * FROM `Contenidos` WHERE Id = ".$IdContenido;
		$result_bloque_contenido = mysql_query($requete_bloque_contenido,$db);
		// Listamos las ampliaciones de contenido existentes
		if (($result_bloque_contenido) && (mysqli_num_rows($result_bloque_contenido)>0))
		{
			$listado_bloque_contenido = mysql_fetch_object($result_bloque_contenido);
			//Obtenemos la ruta
			$requete_bloque_contenido2 = "SELECT * FROM `Secciones` WHERE Id = ".$IdSeccion;
			$result_bloque_contenido2 = mysql_query($requete_bloque_contenido2,$db);
			if (($result_bloque_contenido2) && (mysqli_num_rows($result_bloque_contenido2)>0))
			{
				$listado_bloque_contenido2 = mysql_fetch_object($result_bloque_contenido2);
			}
			//pintamos los datos generales del bloque
			$ruta_bloque_contenido = "/Secciones/";		
			print "<div class=\"bloqueContenido\">";
			if ($listado_bloque_contenido2->Path!="") $ruta_bloque_contenido = $ruta_bloque_contenido.$listado_bloque_contenido2->Path."/".$listado_bloque_contenido2->NomFich."/";
			else $ruta_bloque_contenido = $ruta_bloque_contenido.$listado_bloque_contenido2->NomFich."/";
			if ($listado_bloque_contenido->URLAmigable!="") $enlace_contenido = $listado->URLAmigable;
			else $enlace_contenido = $ruta_bloque_contenido.$listado_bloque_contenido->NomFich.".php";
			if ($listado_bloque_contenido->Redireccionar!="") $enlace_contenido = $listado_bloque_contenido->Redireccionar;
			if ($listado_bloque_contenido->Foto!="")
			{
				print "<div class=\"imagen\"><a title=\"Enlace a ".htmlentities($listado_bloque_contenido->Titulo)."\" href=\"".$enlace_contenido."\">";
				print "<img src=\"/Imagenes/".$listado_bloque_contenido->Foto."\"/>";			
				print "</div>";
			}
			else
			{
				if ($listado_bloque_contenido->Icono!="")
				{
					print "<div class=\"imagen\"><a title=\"Enlace a ".htmlentities($listado_bloque_contenido->Titulo)."\" href=\"".$enlace_contenido."\">";
					print "<img src=\"/herramientas/mostrar_imagen.php?imagen=".$ruta_bloque_contenido.$listado_bloque_contenido->Icono."&ancho=90&alto=63\"/>";
					print "</div>";				
				}
			}
			print "<div class=\"texto\">";
			print "<a href=\"".$enlace_contenido."\" title=\"Enlace a ".$listado_bloque_contenido->Titulo."\"><span class=\"textoTitulo\">".$listado_bloque_contenido->Titulo."</span></a>";		
			if ($listado_bloque_contenido->Breve!="") print "<p><a href=\"".$enlace_contenido."\" title=\"Enlace a ".$listado_bloque_contenido->Titulo."\">".$listado_bloque_contenido->Breve."</a></p>";
			print "</div>";
			print "</div>";
		}
		break;
	}
}
?>