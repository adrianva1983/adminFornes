<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Gastos = $_GET["Gastos"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/facturas_periodicas-".$_SESSION['idioma'].".conf");
// Herramientas superiores
print '<div class="row"><div class="col-md-12">';
print "<div class=\"btn-group\">";
print "<a class=\"btn";
if ($Gastos !=1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas_periodicas".$params."\">".$lang["facturasIngreso"]."</a>";
print "<a class=\"btn";
if ($Gastos ==1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas_periodicas&amp;Gastos=1".$params."\">".$lang["facturasGasto"]."</a>";
print "</div></div></div>";

$requete = "SELECT * FROM FacturasPeriodicidades WHERE Idioma='".$_SESSION['idioma']."'";

$totalGeneral = 0;
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang["periodicidad"]." ".$listado->Cantidad." ".$listado->UnidadCadencia.'</h5>';
		print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
		print '<div class="ibox-content"><div class="row">';		
		$requete2 = "SELECT * FROM `Facturas` WHERE IdPeriodicidad=".$listado->Id." AND `IdSerie` IS NULL";
		if ($Gastos==1) $requete2.=" AND `FacturaGasto`=1";
		else $requete2.=" AND (`FacturaGasto` IS NULL OR `FacturaGasto`<>1)";
		
		$totalPeriodicidad = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			print "<table class=\"table table-striped\">";
			print "<thead><tr><th>".$lang["acciones"]."</th><th>".$lang["ultima"]."</th><th>".$lang["titulo"]."</th><th>".$lang["cliente"]."</th><th>".$lang["baseImponible"]."</th><th>".$lang["facturas"]."</th><th>".$lang["acumulado"]."</th></tr></thead></tbody>";
			$par = false;
			while ($listado2 = mysqli_fetch_object($result2))
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
				print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
				print '<ul class="dropdown-menu">';				
				print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_factura&IdSerie=".$listado2->Id."\"><img src=\"/administra/Imagenes/invoice_add.png\" title=\"".$lang["generarSiguiente"]."\" alt=\"".$lang["generarSiguiente"]."\"> ".$lang["generarSiguiente"]."</a></di>";
				print "<ul></div></td>";	
				$requete3 = "SELECT * FROM `Facturas` WHERE IdSerie=".$listado2->Id." ORDER BY Fecha ASC";
				$total_facturas_serie = 0;
				$total_facturas_serie_importe=0;
				$result3 = mysqli_query($db, $requete3);
				if ($result3 && mysqli_num_rows($result3))
				{
					$titulo_ultima_factura = '';
					$fecha_ultima_factura = '';
					$total_facturas_serie = mysqli_num_rows($result3);
					while ($listado3 = mysqli_fetch_object($result3))
					{
						$requete4 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado3->Id."'";						
						$total_facturas_serie_importe = 0;
						$result4 = mysqli_query($db, $requete4);
						if ($result4 && mysqli_num_rows($result4))
						{
							while ($listado4 = mysqli_fetch_object($result4))
							{
								$total_facturas_serie_importe += $listado4->BaseImponible;
							}
						}
						$titulo_ultima_factura = $listado3->Titulo;
						$fecha_ultima_factura = $listado3->Fecha;
					}
					print "<td>".$fecha_ultima_factura."</td>";
					print "<td>".$titulo_ultima_factura."</td>";
				}
				else 
				{
					print "<td>".$listado2->Fecha."</td>";
					print "<td>".$listado2->Titulo."</td>";
				}				
				$requete3 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado2->IdCliente."'";
				
				print "<td>";
				if ($result3 = mysqli_query($db, $requete3))
				{
					$listado3 = mysqli_fetch_object($result3);
					print $listado3->DenominacionSocial;
				}
				else
				{
					if ($listado2->NombreEmpresa!="") print $listado2->NombreEmpresa;
					else print "-";
				}
				print "</td>";
				print "<td>";
				$requete3 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado2->Id."'";
				
				$total = 0;
				if ($result3 = mysqli_query($db, $requete3))
				{
					while ($listado3 = mysqli_fetch_object($result3))
					{
						$total += $listado3->BaseImponible;
					}
				}
				if ($total_facturas_serie_importe!=0)
				{
					print $total_facturas_serie_importe."€</td>";
					$totalPeriodicidad += $total_facturas_serie_importe;
				}
				else
				{
					print $total."€</td>";
					$totalPeriodicidad += $total;	
				}
				if ($listado2->Fecha>=date("Y")."-01-01")
				{					
					$total_facturas_serie ++;
					$total_facturas_serie_importe +=$total;
				}
				print "<td>".$total_facturas_serie."</td>";
				print "<td>".$total_facturas_serie_importe."</td>";				
				print "</tr>";
			}
			print "</tbody></table>";			
		}		
		print "<h3><span class=\"label label-info\"><strong>".$lang["subtotal"].": ".$totalPeriodicidad." €</span> ";
		if ($listado->Id == 1)
		{ //Periodicidad cada 15 días
			print "<span class=\"label label-info\"><strong>".$lang["subtotalAnual"].": ".($totalPeriodicidad*24)." €</span> ";
			$totalGeneral += $totalPeriodicidad*24;
		}
		if ($listado->Id == 2)
		{ //Periodicidad cada 1 mes
			print "<span class=\"label label-info\"><strong>".$lang["subtotalAnual"].": ".($totalPeriodicidad*12)." €</span> ";
			$totalGeneral += $totalPeriodicidad*12;
		}
		if ($listado->Id == 3)
		{ //Periodicidad cada 3 meses
			print "<span class=\"label label-info\"><strong>".$lang["subtotalAnual"].": ".($totalPeriodicidad*4)." €</span> ";
			$totalGeneral += $totalPeriodicidad*4;
		}
		if ($listado->Id == 4)
		{ //Periodicidad cada 6 meses
			print "<span class=\"label label-info\"><strong>".$lang["subtotalAnual"].": ".($totalPeriodicidad*2)." €</span> ";
			$totalGeneral += $totalPeriodicidad*2;
		}
		if ($listado->Id == 5)
		{ //Periodicidad cada 1 año
			print "<span class=\"label label-info\"><strong>".$lang["subtotalAnual"].": ".$totalPeriodicidad." €</span> ";
			$totalGeneral += $totalPeriodicidad;
		}				
		print "</div></div></div></div></div>";
	}
}                        
print "<div class=\"row\"><div class=\"col-lg-3\"><div class=\"widget style1 lazur-bg\"><div class=\"row\"><div class=\"col-xs-4\"><i class=\"fa fa-euro fa-5x\"></i></div><div class=\"col-xs-8 text-right\"><span>".$lang["total"]."</span><h2 class=\"font-bold\">".$totalGeneral."</h2></div></div></div></div></div>";
?>