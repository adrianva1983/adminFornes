<?php
//VERSIÓN: v1.1 2013-12-19
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET['pagina'];
$modelo = $_GET['modelo'];
$ano = $_GET['ano'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO CORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/clientes-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
if ($modelo!="")
{
	if ($ano == "") $ano = date("Y")-1;
	$requere = "SELECT * FROM Clientes ORDER BY `Id`";
	print $requete;
	
	if ($result = mysqli_query($db, $requete))
	{
		print "<table>";
		print "<tr><th>Id</th><th>".$lang["cliente"]."</th><th>T1</th><th>T2</th><th>T3</th><th>T4</th><th>".$lang["facturado"]."</th></tr>";
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
			//TRIMESTRE 1
			$total_anual = 0;
			print "<td>";
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-1-1' AND `Facturas`.Fecha<'".$ano."-4-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);			
				$total = $listado2->suma;
			}
			if ($total=="") $total = 0;
			print $total." €</td>";
			$total_anual += $total;
			//TRIMESTRE 2
			print "<td>";
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-4-1' AND `Facturas`.Fecha<'".$ano."-7-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);			
				$total = $listado2->suma;
			}
			if ($total=="") $total = 0;
			print $total." €</td>";
			$total_anual += $total;
			//TRIMESTRE 3
			print "<td>";
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-7-1' AND `Facturas`.Fecha<'".$ano."-10-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);			
				$total = $listado2->suma;
			}
			if ($total=="") $total = 0;
			print $total." €</td>";
			$total_anual += $total;			
			//TRIMESTRE 3
			print "<td>";
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-10-1' AND `Facturas`.Fecha<'".($ano+1)."-1-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);			
				$total = $listado2->suma;
			}
			if ($total=="") $total = 0;
			print $total." €</td>";
			$total_anual += $total;			
			print "<td>".$total_anual." €</td>";
		}
	}
}
else
{
	$requete = "SELECT Id FROM `Clientes`";
	
	$total_contenidos_pagina = mysqli_num_rows($result);
	$requete = "SELECT * FROM `Clientes`";
	$requete .= "ORDER BY `Id` LIMIT ".$intervalo_inicial.",".$Num_Pagina;
	
	print "<div id=\"instrucciones\">";
	print "<img src=\"/administra/Imagenes/user_suit_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
	print "<img src=\"/administra/Imagenes/user_delete.png\" title=\"".$lang["eliminar"]."\" alt=\"".$lang["eliminar"]."\"> :: ".$lang["eliminar"]."<br/>";
	print "<img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturas"]."\" alt=\"".$lang["facturas"]."\"> :: ".$lang["facturas"]."<br/>";
	print "<img src=\"/administra/Imagenes/nuevo_grupo.png\" title=\"".$lang["nuevo_contacto"]."\" alt=\"".$lang["nuevo_contacto"]."\"> :: ".$lang["nuevo_contacto"]."<br/>";
	print "<img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestos"]."\" alt=\"".$lang["presupuestos"]."\"> :: ".$lang["presupuestos"]."<br/>";
	print "<img src=\"/administra/Imagenes/calculator_add.png\" title=\"".$lang["nuevoPresupuesto"]."\" alt=\"".$lang["nuevoPresupuesto"]."\"> :: ".$lang["nuevoPresupuesto"]."<br/>";
	print "<img src=\"/administra/Imagenes/application_add.png\" title=\"".$lang["nuevo_proyecto"]."\" alt=\"".$lang["nuevo_proyecto"]."\"> :: ".$lang["nuevo_proyecto"]."<br/>";
	print "</div>";
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN
		print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
		print "<p>";
		$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print "<strong>".$i."</strong> - ";
			else print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$i."\">".$i."</a> - ";
		}	
		print "</p>";
	}
	if ($result = mysqli_query($db, $requete))
	{
		print "<table>";
		print "<tr><th>Id</th><th>".$lang["acciones"]."</th><th>".$lang["cliente"]."</th><th>".$lang["contactos"]."</th><th>".$lang["representante"]."</th><th>".$lang["facturado12"]."</th><th>".$lang["facturado"]."</th></tr>";
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
			if ($listado->Referencia!="") print "<td>".$listado->Referencia."</td>";
			else print "<td>".$listado->Id."</td>";
			print "<td>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/user_suit_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a> ";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=eliminar_cliente&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/user_delete.png\" title=\"".$lang["eliminar"]."\" alt=\"".$lang["eliminar"]."\"></a> ";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas_presupuesto?IdCliente=".$listado->Id."\"><img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturas"]."\" alt=\"".$lang["facturas"]."\"></a> ";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_contacto&Id=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/nuevo_grupo.png\" title=\"".$lang["nuevo_contacto"]."\" alt=\"".$lang["nuevo_contacto"]."\"></a> ";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_presupuesto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/calculator_add.png\" title=\"".$lang["nuevoPresupuesto"]."\" alt=\"".$lang["nuevoPresupuesto"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestos"]."\" alt=\"".$lang["presupuestos"]."\"></a>";
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_proyecto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/application_add.png\" title=\"".$lang["nuevo_proyecto"]."\" alt=\"".$lang["nuevo_proyecto"]."\"></a> ";
			print "</td>";
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id=".$listado->Id."\">";
			print $listado->DenominacionSocial;
			print "</a></td>";
			$requete2 = "SELECT * FROM `Contactos` WHERE `IdCliente`=".$listado->Id;
			
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=contactos_cliente&Id=".$listado->Id."\">".mysqli_num_rows($result2)."</a></td>";
			print "<td>";
			if ($listado->IdRepresentante!="")
			{
				$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdRepresentante;
				
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);				
					print $listado2->Nombre." ".$listado2->Apellidos;
				}			
			}
			else print "-";
			print "</td>";
			print "<td>";
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".(date("Y")-1)."-".date("m")."-".date("d")."' AND `Facturas`.Fecha<'".date("Y-m-d")."'";		
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);			
				$total_ano = $listado2->suma;
			}
			if ($total_ano=="") $total_ano = 0;
			print $total_ano." €</td>";
			print "<td>";		
			$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS suma FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id."";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				$listado2 = mysqli_fetch_object($result2);
				$total_facturado = $listado2->suma;
			}
			if ($total_facturado=="") $total_facturado= 0;
			print "<strong>".$total_facturado." €</strong></td>";
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
				else print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$i."\">".$i."</a> - ";
			}	
			print "</p>";
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
