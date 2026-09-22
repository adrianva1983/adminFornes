<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/foro_editar_mensajes-".$_SESSION['idioma'].".conf");
print "<table>";
print "<tr><th width=\"60%\">".$lang["titulo"]."</th><th width=\"20%\">".$lang["valoracionFecha"]."</th><th width=\"20%\">".$lang["acciones"]."</th></tr>";
$paridad = true;
$requete = "SELECT * FROM `ForoMensajes` WHERE `IdForo`=".$IdForo." ORDER BY `Fecha`";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($paridad) $paridad = false;
		else $paridad = true;
		if ($paridad) print "<tr id=\"par\">";
		else print "<tr>";
		print "<td>".$listado->Titulo."</td>";
		print "<td>";
		switch ($listado->Votacion) 
		{
   		case "0":
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			break;
   		case "1":
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			break;
   		case "2":
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			break;
   		case "3":
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			break;
   		case "4":
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_vacia.gif\" title=\"".$lang["estrellaVacia"]."\" alt=\"".$lang["estrellaVacia"]."\">";
   			break;
   		case "5":
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			print "<img src=\"/administra/Imagenes/estrella_llena.gif\" title=\"".$lang["estrellaLlena"]."\" alt=\"".$lang["estrellaLlena"]."\">";
   			break;
   	}
   	print "</td>";   	
   	print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=editar_mensaje_foro&mensaje=".$listado->Id."&origen=foro_editar_mensajes&pagina=".$pagina."&ruta=".$ruta."&seccion=".$seccion."&IdForo=".$IdForo."\"><img src=\"/administra/Imagenes/editar_mensajes_foro.png\"> ".$lang["editar"]."</a></td></tr>";
		if ($paridad) print "<tr id=\"par\"><td rowspan=\"2\">".$listado->Mensaje."</td>";
		else print "<tr><td rowspan=\"2\">".$listado->Mensaje."</td>";
		print "<td><img src=\"/administra/Imagenes/calendario.png\" title=\"".$lang["fecha"]."\" alt=\"".$lang["fecha"]."\"> ".$listado->Fecha."</td>";
		print "<td><a href=\"/administra/Contenidos/funciones/borrar_mensaje_foros.php?mensaje=".$listado->Id."&origen=foro_editar_mensajes&pagina=".$pagina."&ruta=".$ruta."&seccion=".$seccion."&IdForo=".$IdForo."\"><img src=\"/administra/Imagenes/borrar.png\"> ".$lang["borrar"]."</a></td>";
		print "</tr>";
		if ($paridad) print "<tr id=\"par\">";
		else print "<tr>";
		if ($listado->Validado=="no") print "<td><a href=\"/administra/Contenidos/funciones/validar_mensaje_foros.php?mensaje=".$listado->Id."&accion=OK&origen=foro_editar_mensajes&pagina=".$pagina."&ruta=".$ruta."&seccion=".$seccion."&IdForo=".$IdForo."\"><img src=\"/administra/Imagenes/OK.png\"> ".$lang["validar"]."</a></td>";
   	else print "<td><img src=\"/administra/Imagenes/tick.png\"> ".$lang["validado"]."</td>";
		if (($listado->IdAutor!="")&&($listado->IdAutor!="0")) print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$listado->IdAutor."\"><img src=\"/administra/Imagenes/usuario.png\" title=\"".$lang["ficha"]."\" alt=\"".$lang["ficha"]."\"> ".$lang["usuario"]."</td>";
		else print "<td><a href=\"#\"><img src=\"/administra/Imagenes/usuarios_gris.png\" alt=\"".$lang["fichaNO"]."\"> ".$lang["usuario"]."</a></td>";
		print "</tr>";		
	}
}
else print "<tr><td colspan=\"3\"> ".$lang["msgNO"]." </td></tr>";
print "</table>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>