<?php

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/boletines-".$_SESSION['idioma'].".conf");

if (!isset($pagina)) $pagina = 0;
$num_contenidos_pagina = 20;
if ($queBoletines=="mios") $requete = "SELECT * FROM `BoletinConfiguracion` WHERE `IdPropietario`= ".$_SESSION['usuario_id'];
else $requete = "SELECT * FROM `BoletinConfiguracion`";

$total_contenidos_pagina = mysqli_num_rows($result);
if ($queBoletines=="mios") $requete = "SELECT * FROM `BoletinConfiguracion` WHERE `IdPropietario`= ".$_SESSION['usuario_id']." ORDER BY `FechaEnvio` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;
else $requete = "SELECT * FROM `BoletinConfiguracion` ORDER BY `FechaEnvio` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;

print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"> :: ".$lang["borrar"]."<br/>";
print "<img src=\"/administra/Imagenes/boletin_enviar.png\" title=\"".$lang["enviar"]."\" alt=\"".$lang["enviar"]."\"> :: ".$lang["enviar"]."<br/>";
print "<img src=\"/administra/Imagenes/boletin_configuracion.png\" title=\"".$lang["configuracion"]."\" alt=\"".$lang["configuracion"]."\"> :: ".$lang["configuracion"]."<br/>";
print "<img src=\"/administra/Imagenes/boletin_editar.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "</div>";
// Herramientas superiores
print "<div id=\"botonera_cabecera\"><ul>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletines\">".$lang["todosBoletines"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletines&queBoletines=mios\">".$lang["misBoletines"]."</a></li>";
print "</ul></div>";
print "<table>";
print "<tr><th>".$lang["acciones"]."</th><th>".$lang["asunto"]."</th><th>".$lang["fecha"]."</th><th>".$lang["estado"]."</th><th>".$lang["pendientesEnvio"]."</th></tr>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		if (($_SESSION['usuario_nivel']<2)||(($_SESSION['usuario_nivel']==2)&&($listado->IdPropietario==$_SESSION['usuario_id'])))
		{
			print "<td>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=borrar_boletin&idboletin=".$listado->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=enviar_boletin&idboletin=".$listado->Id."\"><img src=\"/administra/Imagenes/boletin_enviar.png\" title=\"".$lang["enviar"]."\" alt=\"".$lang["enviar"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=editar_boletin&idboletin=".$listado->Id."\"><img src=\"/administra/Imagenes/boletin_configuracion.png\" title=\"".$lang["configuracion"]."\" alt=\"".$lang["configuracion"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$listado->Id."\"><img src=\"/administra/Imagenes/boletin_editar.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
			print "</td>";
			print "<td>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$listado->Id."\">".$listado->Asunto."</a>";
			print "</td>";
			print "<td>";
			if ($listado->FechaEnvio!="") print $listado->FechaEnvio;
			print "</td>";
			//Miramos si quedan mensajes por mandar
			print "<td>";
			$requete2 = "SELECT * FROM `BoletinUsuarios` WHERE `IdBoletin`='".$listado->Id."' AND `Estado` = 'Pendiente'";
			
			$pendientes = 0;
			if ($result2 = mysqli_query($db, $requete2))
			{
				$pendientes = mysqli_num_rows($result2);
				$requete2 = "SELECT * FROM `BoletinUsuarios` WHERE `IdBoletin`='".$listado->Id."' AND `Estado` = 'Enviado'";
				
				if ($result2 = mysqli_query($db, $requete2)) print " <span style=\"background:#FFFF00;color:#000000;font-weight:bold;\"> ".$lang["parcialmente"]." </span>";
				else print " <span style=\"background:#FF0000;color:#FFFFFF;font-weight:bold;\"> ".$lang["pendiente"]." </span>";
			}
			else if ($listado->FechaEnvio!="") print " <span style=\"background:#00FF00;color:#000000;font-weight:bold;\"> ".$lang["totalmente"]." </span>";
			print "</td>";
			print "<td>".$pendientes."</td>";
		}
		print "</tr>";
	}
}
print "</table>";
if (isset($num_contenidos_pagina))
{
	//PAGINACIÓN
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<p>";
	$max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
	for ($i=0;($i<($max_pagina + 1));$i++)
	{
		if ($pagina == $i) print "<strong>".$i."</strong> - ";
		else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletines&queBoletines=".$queBoletines."&pagina=".$i."\">".$i."</a> - ";
	}	
	print "</p>";
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
