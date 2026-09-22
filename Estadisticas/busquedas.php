<?php
//VERSIÓN: v1.0 2014-4-1
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET["pagina"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Estadisticas/idiomas/busquedas-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `EstadisticasBusqueda` WHERE 1=1";

$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `Numero` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

/*
print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["hecho"]."\" alt=\"".$lang["hecho"]."\"> :: ".$lang["hecho"]."<br/>";
print "<img src=\"/administra/Imagenes/note_go.png\" title=\"".$lang["entrar"]."\" alt=\"".$lang["entrar"]."\"> :: ".$lang["entrar"]."<br/>";
print "<img src=\"/administra/Imagenes/note_delete.png\" alt=\"".$lang["parar"]."\"> :: ".$lang["parar"]."<br/>";
print "<img src=\"/administra/Imagenes/note_edit.png\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "</div>";
*/
print "<h1>".$lang["busquedas"]."</h1>";
/*
print "<div id=\"botonera_cabecera\">";
print "<ul>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=si\">".$lang["tareasAbiertas"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=no\">".$lang["tareasCerradas"]."</a></li>";
print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=tareas&IdProyecto=".$IdProyecto."&IdUsuario=".$IdUsuario."&pagina=".$pagina."&activas=todas\">".$lang["tareasTodas"]."</a></li>";
print "</ul>";
print "</div>";
*/
if (isset($Num_Pagina))
{
	//PAGINACIÓN
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<p>";
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print "<strong>".$i."</strong> - ";
		else print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=busquedas&pagina=".$i."\">".$i."</a> - ";
	}	
	print "</p>";
}
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>".$lang["fechaInicio"]."</th><th>".$lang["fechaFin"]."</th><th>".$lang["busqueda"]."</th><th>".$lang["repeticiones"]."</th><th>".$lang["resultados"]."</th></tr>";
	$par = false;
	while ($listado = mysqli_fetch_object($result))
	{
		if ($par) 
		{			
			print "<tr class=\"par\">";
			$par = false;
		}
		else
		{
			print "<tr>";
			$par = true;
		}
		print "<td>".$listado->FechaInicio."</td>";
		print "<td>".$listado->FechaFin."</td>";
		print "<td>";
		$tmp = explode(",",$listado->Busqueda);
		for ($i=0;$i<count($tmp);$i++)
		{
			$tmp2 = explode(":",$tmp[$i]);
			if ($tmp2[1]!="") print "<span style=\"padding:5px 10px;border-radius:5px;margin-right:10px;background:#333;color:#FFF;\">".$tmp[$i]."</span>";
		}
		print "</td>";
		print "<td>".$listado->Numero."</td>";
		print "<td>N/A</td>";
		print "</tr>";
	}
	print "</table>";
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN
		print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
		print "<p>";
		if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
		else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print "<strong>".$i."</strong> - ";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Estadisticas&herramienta=busquedas&pagina=".$i."\">".$i."</a> - ";
		}	
		print "</p>";
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
