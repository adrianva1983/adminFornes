<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/glosario-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Glosario`";

$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `Termino` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

//Paginación
if (isset($Num_Pagina))
{
	//PAGINACIÓN
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<p>";
	$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina--;
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print "<strong>".$i."</strong> - ";
		else print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=glosario&pagina=".$i.$params."\">".$i."</a> - ";
	}	
	print "</p>";
}
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>".$lang["acciones"]."</th><th>".$lang["termino"]."</th><th>".$lang["descripcion"]."</th><th>".$lang["enlace"]."</th></tr>";
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
		print "<td>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=glosario_editar_termino&id=".$listado->Id."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/glosario_editar.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
		print "<a href=\"/administra/Contenidos/funciones/glosario_borrar_termino.php?id=".$listado->Id."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";
		print "</td>";
		print "<td>";
		print $listado->Termino;
		print "</td>";
		print "<td>";
		print substr($listado->Descripcion,0,50);
		print "</td>";
		print "<td>";
		if ($listado->Url!="") print "<a href=\"".$listado->Url."\" target=\"_blank\">".$lang["enlace"]."</a>";		
		print "</td>";
		print "</tr>";
	}
	print "</table>";
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN
		print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
		print "<p>";
		$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina--;
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print "<strong>".$i."</strong> - ";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=glosario&pagina=".$i.$params."\">".$i."</a> - ";
		}
		print "</p>";
	}
}
if ($totalizado_resultado>0) print "<p class=\"mensaje\"><strong>".$lang["totalizado"].":</strong> ".$totalizado_resultado."</p>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
