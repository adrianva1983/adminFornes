<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/ultimos_mensajes_foros-".$_SESSION['idioma'].".conf");

print "<h1>".$lang["ultimos"].".</h1>";

print "<hr>";
// Hacemos una consulta para sacar los mensajes en el sistema

if (!isset($intervalo_elementos)) $intervalo_elementos = 10;
$intervalo_inicial= $pagina * $intervalo_elementos;
$requete = "SELECT * FROM `ForoMensajes` ORDER BY `Fecha` DESC LIMIT ".$intervalo_inicial.",".$intervalo_elementos;

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{		
		print "<p><table>";
		$requete2 = "SELECT `IdPadre` FROM `Contenidos` WHERE `Id`='".$listado->IdForo."'";
		
		if ($result2 = mysqli_query($db, $requete2)) $listado2 = mysqli_fetch_object($result2);
		$requete2 = "SELECT `IdSeccion` FROM `Publicaciones` WHERE `IdContenido` = ".$listado2->IdPadre;
		$IdPadre = $listado2->IdPadre;
				
		if ($result2 = mysqli_query($db, $requete2)) $listado2 = mysqli_fetch_object($result2);		
		$requete2 = "SELECT `Path`,`NomFich` FROM `Secciones` WHERE `Id` = ".$listado2->IdSeccion;		
		
		if ($result2 = mysqli_query($db, $requete2)) $listado2 = mysqli_fetch_object($result2);
		$ruta = $listado2->Path."/".$listado2->NomFich;
		$requete2 = "SELECT `Titulo`,`NomFich` FROM `Contenidos` WHERE `Id`='".$IdPadre."'";
		
		if ($result2 = mysqli_query($db, $requete2)) $listado2 = mysqli_fetch_object($result2);		
		print "<tr><th>".$lang["contenido"]."</th><th>".$lang["votacion"]."</th><th>".$lang["vistas"]."</th><th>".$lang["fecha"]."</th><th>".$lang["autor"]."</th><th>".$lang["acciones"]."</th></tr>";
		print "<tr><td><a href=\"/Secciones/".$ruta."/".$listado2->NomFich.".php\" target=\"_blank\">".$listado2->Titulo."</a></td>";
		print "<td>".$listado->Votacion."</td><td>".$listado->Vistas."</td><td>".$listado->Fecha."</td><td>".$listado->IdAutor."</td>";
		print "<td>";
		print "<a href=\"/administra/Contenidos/funciones/borrar_mensaje_foros.php?mensaje=".$listado->Id."&origen=ultimos_mensajes_foros&pagina=".$pagina."\"><img src=\"/administra/Imagenes/borrar.png\"/ title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";		
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=editar_mensaje_foro&mensaje=".$listado->Id."&origen=ultimos_mensajes_foros&pagina=".$pagina."\"><img src=\"/administra/Imagenes/pencil.png\"/ title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
		print $listado->Validado;
		print "</td></tr>";
		print "<tr><td colspan=\"6\">".$listado->Titulo."</td></tr>";
		print "<tr><td colspan=\"6\">".$listado->Mensaje."</td></tr>";
		print "</table></p>";
	}
}
print "<hr>";
if (!isset($pagina)||($pagina==0)) print "<a href=\"herramienta.php?modulo=Contenidos&herramienta=ultimos_mensajes_foros&pagina=1\">".$lang["siguiente"]."</a>";
else
{
	$siguiente = $pagina+1;
	$anterior = $pagina-1;
	print "<a href=\"herramienta.php?modulo=Contenidos&herramienta=ultimos_mensajes_foros&pagina=".$anterior."\">".$lang["anterior"]."</a> | <a href=\"herramienta.php?modulo=Contenidos&herramienta=ultimos_mensajes_foros&pagina=".$siguiente."\">".$lang["siguiente"]."</a>";
}
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
