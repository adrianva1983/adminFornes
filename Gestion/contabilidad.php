<?php
//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/contabilidad-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/coins_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "<img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturaRelacionada"]."\" alt=\"".$lang["facturaRelacionada"]."\"> :: ".$lang["facturaRelacionada"]."<br/>";
print "</div>";
$requete = "SELECT * FROM `Contabilidad` WHERE 1=1";
if ($_GET['IdCliente']!='') $requete.=" AND `IdCliente`=".$_GET['IdCliente'];
$requete .= " ORDER BY `Fecha` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

if ($result = mysqli_query($db, $requete)) $total_contenidos_pagina = 0;
else $total_contenidos_pagina = mysqli_num_rows($result);

//Paginación
if (isset($Num_Pagina))
{
	//PAGINACIÓN
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<p>";
	$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print "<strong>".$i."</strong> - ";
		else print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=contabilidad&pagina=".$i."\">".$i."</a> - ";
	}
	print "</p>";
}
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>".$lang["fecha"]."</th><th>".$lang["acciones"]."</th><th>".$lang["empresa"]."</th><th>".$lang["cliente"]."</th><th>".$lang["titulo"]."</th><th>".$lang["importe"]."</th></tr>";
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
		if ($listado->Importe<0) print "<span style=\"color:#FF0000;\">";
		else print "<span style=\"color:#00FF00;\">";
		print $listado->Fecha;
		print "</span>";
		print "</td>";		
		print "<td>";		
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_apunte&Id=".$listado->Id."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/coins_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";		
		if ($listado->IdFactura!="") print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_factura&Id=".$listado->IdFactura."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturaRelacionada"]."\" alt=\"".$lang["facturaRelacionada"]."\"></a>";		
		print "</td>";
		$requete2 = "SELECT * FROM `FacturasEmpresas` WHERE `IdEmpresa` = '".$listado->IdEmpresa."'";
		
		print "<td>";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->NombreComercial."(".$listado2->DenominacionSocial.")";
		}
		else
		{
			print "-";
		}
		print "</td>";
		$requete2 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado->IdCliente."'";
		
		print "<td>";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->DenominacionSocial;
		}
		else
		{
			if ($listado->NombreEmpresa!="") print $listado->NombreEmpresa;
			else print "-";
		}
		print "</td>";
		print "<td>".$listado->Titulo."</td>";
		print "<td>";
		if ($listado->Importe<0) print "<span style=\"color:#FF0000;\">";
		else print "<span style=\"color:#00FF00;\">";
		print $listado->Importe;
		print "</span>";
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
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print "<strong>".$i."</strong> - ";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=contabilidad&pagina=".$i."\">".$i."</a> - ";
		}
		print "</p>";
	}
	if ($_GET['IdCliente']!="")
	{
		print "<p class='mensaje'>";
		print "<strong>".$lang['saldoTotalizado'].":</strong> ";
		$requete2 = "SELECT SUM(`Importe`) AS Saldo FROM `Contabilidad` WHERE `IdCliente`=".$_GET['IdCliente'];
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->Saldo."€";
		}
		print "</p>";
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
