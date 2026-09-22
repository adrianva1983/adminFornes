<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/mensajeria-".$_SESSION['idioma'].".conf");

function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0];	
	return $lafecha;
}
function cambiaf_a_normal_conhoras($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0]." ".$mifecha[1];	
	return $lafecha;
}
print "<div id=\"instrucciones\"><img src=\"/administra/Imagenes/email.png\" alt=\"".$lang["sinAbrir"]."\" title=\"".$lang["sinAbrir"]."\"> :: ".$lang["sinAbrir"].". ";
print "<img src=\"/administra/Imagenes/email_abierto.png\" title=\"".$lang["abierto"]."\" alt=\"".$lang["abierto"]."\"> :: ".$lang["abierto"].". ";
print "<img src=\"/administra/Imagenes/boletin_enviar.png\" alt=\"".$lang["responder"]."\" title=\"".$lang["responder"]."\"> :: ".$lang["responder"].".<br/>";
print "<img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"> :: ".$lang["borrar"].".";
print "<img src=\"/administra/Imagenes/star.png\" alt=\"".$lang["marcar"]."\" title=\"".$lang["marcar"]."\"> :: ".$lang["marcar"].".<br/>";
print "<img src=\"/administra/Imagenes/bullet_orange.png\" alt=\"".$lang["comunicadoMail"]."\" title=\"".$lang["comunicadoMail"]."\"><img src=\"/administra/Imagenes/bullet_orange.png\" alt=\"".$lang["abiertoMensaje"]."\" title=\"".$lang["abiertoMensaje"]."\"> :: ".$lang["comunicadoMail"]." | ".$lang["abiertoMensaje"].".";
print "<br/></div>";

if ($favorito!="")
{
	$requete = "SELECT * FROM `Mensajeria` WHERE Id = '".$favorito."'";
	
	if ($result = mysqli_query($db, $requete)) $listado = mysqli_fetch_object($result);
	if ($listado->EstadoDestino =="favorito") 
	{
		$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = NULL WHERE `Id`=".$favorito;
		print "<p class=\"mensajeOK\">".$lang["quitadoFavoritos"]."</p>";
	}
	else 
	{
		$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = 'favorito' WHERE `Id`=".$favorito;
		print "<p class=\"mensajeOK\">".$lang["anadidoFavoritos"]."</p>";
	}	
	mysqli_query($db,$requete);
}
if ($borrar!="")
{
	$requete = "SELECT * FROM `Mensajeria` WHERE `Id` = '".$borrar."' AND `IdUsuarioDestino`='".$_SESSION['usuario_id']."'";
	
	if ($result = mysqli_query($db, $requete)) 
	{
		$listado = mysqli_fetch_object($result);		
		if ($listado->EstadoOrigen =="borrado") 
		{
			$requete = "DELETE FROM `Mensajeria`  WHERE `Id`=".$borrar;
		}
		else
		{
			$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = 'borrado' WHERE `Id`=".$borrar;
		}		
		mysqli_query($db,$requete);
		print "<p class=\"mensajeOK\">".$lang["borrado"]."</p>";
	}
	else print "<p class=\"mensajeKO\">".$lang["noBorrado"]."</p>";
}
//Preparamos paginación
if (!isset($pagina)) $pagina = 0;
$num_mensajes_pagina = 20;
$requete = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'";
if ($favoritos=="si") $requete.=" AND `EstadoDestino` = 'favorito'";

if ($result = mysqli_query($db, $requete))
{
	$total_mensajes_pagina = mysqli_num_rows($result);	
}
else $total_mensajes_pagina = 0;
if ($idmensaje=="")
{
	//Consultamos el listado de Mensajes pertenecientes a este usuario
	$requete = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'  AND (`EstadoDestino`<>'borrado' || `EstadoDestino` IS NULL)";
	if ($favoritos=="si") $requete.= " AND `EstadoDestino` = 'favorito'";
	$requete.= " ORDER BY `Fecha` DESC LIMIT ".($pagina * $num_mensajes_pagina).",".$num_mensajes_pagina;
	
	if ($result = mysqli_query($db, $requete))
	{
		if ($favoritos=="si") print "<p>".$lang["listadoFavoritos"]."</p>";
		else print "<p>".$lang["listado"]."</p>";
		print "<table name=\"mensajes\" id=\"mensajes\"><tr><th colspan=\"4\">".$lang["acciones"]."</th><th>".$lang["asunto"]."</th><th>".$lang["de"]."</th><th>".$lang["fechaEnvio"]."</th><th>".$lang["estado"]."</th></tr>";
		$par = false;
		while($listado = mysqli_fetch_object($result))
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
			$requete2 = "SELECT `Id`, `Nombre`,`Apellidos`,`NombreEmpresa` FROM `Usuarios` WHERE Id = '".$listado->IdUsuarioOrigen."'";
			
			$listado2 = mysqli_fetch_object($result2);
			if ($listado->Visto=="")
			{
				print "<td class=\"acciones\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["sinAbrir"]."\" alt=\"".$lang["sinAbrir"]."\" src=\"/administra/Imagenes/email.png\"/></td>";
				print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" alt=\"".$lang["responder"]."\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
				print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&borrar=".$listado->Id."&favoritos=".$favoritos."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a></td>";
				if ($listado->EstadoDestino=="favorito") print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["desMarcar"]."\" alt=\"".$lang["desMarcar"]."\" src=\"/administra/Imagenes/star.png\"/></a></td>";
				else print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["marcar"]."\" alt=\"".$lang["marcar"]."\" src=\"/administra/Imagenes/star_no.png\"/></a></td>";
				print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><strong>".htmlentities($listado->Titulo)."</strong></a></td>";
				if ($listado2->Empresa!="") print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)." (".htmlentities($listado2->NombreEmpresa).")</strong></a></td>";
				else print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)."</strong></a></td>";
				print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><strong>".cambiaf_a_normal($listado->Fecha)."</strong></a></td>";
			}
			else
			{
				print "<td class=\"acciones\"><img title=\"".$lang["abierto"]." ".cambiaf_a_normal_conhoras($listado->Visto)."\" alt=\"".$lang["abierto"]."  ".cambiaf_a_normal_conhoras($listado->Visto)."\" src=\"/administra/Imagenes/email_abierto.png\"/></td>";
				print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["responder"]."\" alt=\"".$lang["responder"]."\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
				print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&borrar=".$listado->Id."&favoritos=".$favoritos."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a></td>";
				if ($listado->EstadoDestino=="favorito") print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["desMarcar"]."\" alt=\"".$lang["desMarcar"]."\" src=\"/administra/Imagenes/star.png\"/></a></td>";
				else print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."&favoritos=".$favoritos."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["marcar"]."\" alt=\"".$lang["marcar"]."\" src=\"/administra/Imagenes/star_no.png\"/></a></td>";
				print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\">".htmlentities($listado->Titulo)."</a></td>";
				if ($listado2->Empresa!="") print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\">".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)." (".htmlentities($listado2->NombreEmpresa).")</a></td>";
				else print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\">".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)."</a></td>";
				print "<td class=\"mensajes\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&idmensaje=".$listado->Id."&favoritos=".$favoritos."\"><strong>".cambiaf_a_normal($listado->Fecha)."</strong></a></td>";
			}
			print "<td class=\"mensajes\">";
			if ($listado->AbiertaAlertaMail!="") print "<img src=\"/administra/Imagenes/bullet_green.png\" alt=\"".$lang["comunicadoMail"]." ".$listado->AbiertaAlertaMail."\" title=\"".$lang["comunicadoMail"]." ".$listado->AbiertaAlertaMail."\">";
			else print "<img src=\"/administra/Imagenes/bullet_red.png\" alt=\"".$lang["noComunicadoMail"]."\" title=\"".$lang["noComunicadoMail"]."\">";
			if ($listado->Visto!="") print "<img src=\"/administra/Imagenes/bullet_green.png\" alt=\"".$lang["abiertoMensaje"]." ".$listado->Visto."\" title=\"".$lang["abiertoMensaje"]." ".$listado->Visto."\">";
			else print "<img src=\"/administra/Imagenes/bullet_red.png\" alt=\"".$lang["noAbiertoMensaje"]."\" title=\"".$lang["noAbiertoMensaje"]."\">";
			print "</td>";
			print "</tr>";
		}
		print "</table>";
	}
	else
	{
		if ($favoritos=="si") print "<p class=\"mensajeKO\">".$lang["sinMensajesFavoritos"]."</p>";
		else print "<p class=\"mensajeKO\">".$lang["sinMensajes"]."</p>";
	}
}
else
{
	$requete = "SELECT * FROM `Mensajeria` WHERE Id = '".$idmensaje."'";
	
	if ($result = mysqli_query($db, $requete)) $listado = mysqli_fetch_object($result);
	if ($listado->Visto =="")
	{
		$requete2 = "UPDATE `Mensajeria`  SET `Visto` = '".date("Y-m-d H:i:s")."' WHERE `Id`=".$idmensaje;
		mysql_query($requete2,$db);
	}
	$requete2 = "SELECT `Id`, `Nombre`,`Apellidos`,`NombreEmpresa` FROM `Usuarios` WHERE Id = '".$listado->IdUsuarioOrigen."'";
	
	$listado2 = mysqli_fetch_object($result2);
	print "<table><tr><th colspan=\"4\">".$lang["acciones"]."</th><th>".$lang["asunto"]."</th><th>".$lang["de"]."</th><th>".$lang["fechaEnvio"]."</th><th>".$lang["estado"]."</th></tr><tr>";
	print "<td class=\"acciones\"><img id=\"imagen".$listado->Id."\" alt=\"".$lang["sinAbrir"]."\" title=\"".$lang["sinAbrir"]."\" src=\"/administra/Imagenes/email.png\"/></td>";
	print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje&idmensaje=".$listado->Id."\"><img id=\"imagen".$listado->Id."\" alt=\"".$lang["responder"]."\" title=\"".$lang["responder"]."\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
	print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&borrar=".$listado->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a></td>";
	if ($listado->EstadoDestino=="favorito") print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["desMarcar"]."\" alt=\"".$lang["desMarcar"]."\" src=\"/administra/Imagenes/star.png\"/></a></td>";
	else print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria&favorito=".$listado->Id."\"><img id=\"imagen".$listado->Id."\" title=\"".$lang["marcar"]."\" alt=\"".$lang["marcar"]."\" src=\"/administra/Imagenes/star_no.png\"/></a></td>";
	print "<td class=\"mensajes\"><strong>".htmlentities($listado->Titulo)."</strong></td>";
	if ($listado2->Empresa!="") print "<td class=\"mensajes\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)." (".htmlentities($listado2->NombreEmpresa).")</strong></td>";
	else print "<td class=\"mensajes\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)."</strong></td>";
	print "<td class=\"mensajes\"><strong>".cambiaf_a_normal($listado->Fecha)."</strong></td>";
	print "<td class=\"mensajes\">";
	if ($listado->AbiertaAlertaMail!="") print "<img src=\"/administra/Imagenes/bullet_green.png\" alt=\"".$lang["comunicadoMail"]." ".$listado->AbiertaAlertaMail."\" title=\"".$lang["comunicadoMail"]." ".$listado->AbiertaAlertaMail."\">";
	else print "<img src=\"/administra/Imagenes/bullet_red.png\" alt=\"".$lang["noComunicadoMail"]."\" title=\"".$lang["noComunicadoMail"]."\">";
	if ($listado->Visto!="") print "<img src=\"/administra/Imagenes/bullet_green.png\" alt=\"".$lang["abiertoMensaje"]." ".$listado->Visto."\" title=\"".$lang["abiertoMensaje"]." ".$listado->Visto."\">";
	else print "<img src=\"/administra/Imagenes/bullet_red.png\" alt=\"".$lang["noAbiertoMensaje"]."\" title=\"".$lang["noAbiertoMensaje"]."\">";
	print "</td>";
	print "</tr></table>";
	print "<p><strong>".$lang["cuerpo"].":</strong></p>";
	print $listado->Mensaje;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>