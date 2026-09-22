<?php
$acciones_disponibles = true;
$ano = $_GET["ano"];
$empresa = $_GET["empresa"];
//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/balance-".$_SESSION['idioma'].".conf");
print "<div id=\"botonera_cabecera\">";
$requete = "SELECT * FROM `FacturasEmpresas`";

if ($result = mysqli_query($db, $requete))
{	
	while($listado = mysqli_fetch_object($result))
	{
		print "<li><a ";
		if ($empresa == $listado->IdEmpresa) print "class=\"activo\" ";
		print "href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=balance&amp;empresa=".$listado->IdEmpresa;
		if ($ano!="") print "&amp;ano=".$ano;
		print "\">".$listado->DenominacionSocial."</a></li>";
	}
}
print "<ul>";
if ($ano =="") $ano = date("Y");
for ($i=0;$i<5;$i++)
{
	print "<li><a ";
	if ($ano==date("Y")-$i) print "class=\"activo\" ";
	print "href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=balance&amp;ano=".($ano-$i);
	if ($empresa!="") print "&amp;empresa=".$empresa;
	print "\">".($ano-$i)."</a></li>";	
}
print "</ul></div>";
for ($i=1;$i<5;$i++)
{
	print "<h2>".$i." ".$lang["trimestre"]."</h2>";
	print "<table>";
	print "<tr><th>".$lang["numero"]."</th><th>".$lang["fecha"]."</th><th>".$lang["cif"]."</th><th>".$lang["denominacionsocial"]."</th><th>".$lang["concepto"]."</th><th>".$lang["baseImponible"]."</th><th>".$lang["impuesto"]."</th></tr>";
	$requete = "SELECT * FROM `Facturas` WHERE `IdEstado`=1 AND ";
	if ($i==1) $requete .= "(`Fecha`>='".$ano."-01-01' AND `Fecha`<='".$ano."-03-31')";
	if ($i==2) $requete .= "(`Fecha`>='".$ano."-04-01' AND `Fecha`<='".$ano."-06-30')";
	if ($i==3) $requete .= "(`Fecha`>='".$ano."-07-01' AND `Fecha`<='".$ano."-09-30')";
	if ($i==4) $requete .= "(`Fecha`>='".$ano."-10-01' AND `Fecha`<='".$ano."-12-31')";
	if ($empresa!="") $requete.= " AND `IdEmpresa`=".$empresa;
	
	$total_bi = 0;
	$total_tax = 0;
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			print "<tr>";
			print "<td>";
			if ($listado->SerieFactura!="") print $listado->SerieFactura."-";
			print $listado->NumeroFactura."</td>";
			print "<td>".$listado->Fecha."</td>";
			$requete2 = "SELECT * FROM `Clientes`WHERE `Id` = ".$listado->IdCliente;
						
			if ($result2 = mysqli_query($db, $requete2))
			{	
				$listado2 = mysqli_fetch_object($result2);
			}
			print "<td>".$listado2->CIF."</td>";
			print "<td>".$listado2->DenominacionSocial."</td>";
			print "<td>".$listado->Titulo."</td>";
			$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura`=".$listado->Id;
						
			$bi = 0;
			$tax = 0;
			if ($result2 = mysqli_query($db, $requete2))
			{	
				while($listado2 = mysqli_fetch_object($result2))
				{
					$bi += $listado2->BaseImponible;					
					$tax+= $listado2->BaseImponible * $listado2->Impuesto;
				}
			}
			print "<td>".number_format($bi,2,",","");
			if ($listado->Moneda !="") print " ".$listado->Moneda;
			else print " &euro;";
			print "</td>";
			print "<td>".number_format($tax,2,",","");
			if ($listado->Moneda !="") print " ".$listado->Moneda;
			else print " &euro;";
			print "</td>";
			$total_bi += $bi;
			$total_tax += $tax;
			print "</tr>";
		}
	}
	print "<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>".$lang["total"]."</th><th>".number_format($total_bi,2,",","")."</th><th>".number_format($total_tax,2,",","")."</th></tr>";
	print "</table>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
