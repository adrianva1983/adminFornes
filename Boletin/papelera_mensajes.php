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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/papelera_mensajes-".$_SESSION['idioma'].".conf");

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
print "<div id=\"instrucciones\"><img src=\"/administra/Imagenes/tick.png\" alt=\"".$lang["restaurar"]."\" title=\"".$lang["restaurar"]."\"> :: ".$lang["restaurar"].".</div>";

if ($restaurar!="")
{
	$requete = "UPDATE `Mensajeria`  SET `EstadoDestino` = NULL WHERE `Id`=".$restaurar;
	print "<p class=\"mensajeOK\">".$lang["restaurado"]."</p>";
	mysqli_query($db,$requete);
}
//Preparamos paginación
if (!isset($pagina)) $pagina = 0;
$num_mensajes_pagina = 20;
$requete = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'";
$requete.=" AND `EstadoDestino` = 'borrado'";

$total_mensajes_pagina = mysqli_num_rows($result);
//Consultamos el listado de Mensajes pertenecientes a este usuario
$requete = "SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$_SESSION['usuario_id']."'  AND `EstadoDestino`='borrado'";	
$requete.= " ORDER BY `Fecha` DESC LIMIT ".($pagina * $num_mensajes_pagina).",".$num_mensajes_pagina;

if ($result = mysqli_query($db, $requete))
{
	print "<p>".$lang["listado"]."</p>";
	print "<table name=\"mensajes\" id=\"mensajes\"><tr><th>".$lang["acciones"]."</th><th>".$lang["asunto"]."</th><th>".$lang["de"]."</th><th>".$lang["fechaEnvio"]."</th></tr>";
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
		print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=papelera_mensajes&restaurar=".$listado->Id."&favoritos=".$favoritos."\"><img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["restaurar"]."\" alt=\"".$lang["restaurar"]."\"></a></td>";				
		print "<td class=\"mensajes\"><strong>".htmlentities($listado->Titulo)."</strong></td>";
		if ($listado2->Empresa!="") print "<td class=\"mensajes\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)." (".htmlentities($listado2->NombreEmpresa).")</strong></td>";
		else print "<td class=\"mensajes\"><strong>".htmlentities($listado2->Nombre)." ".htmlentities($listado2->Apellidos)."</strong></td>";
		print "<td class=\"mensajes\"><strong>".cambiaf_a_normal($listado->Fecha)."</strong></td>";			
		print "</tr>";
	}
	print "</table>";
}
else
{		
	print "<p class=\"mensajeKO\">".$lang["sinMensajesPapelera"]."</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>