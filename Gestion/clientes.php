<?php
//VERSIÓN: v1.1 2013-12-19
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET['pagina'];
$modelo = $_GET['modelo'];
$ano = $_GET['ano'];
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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/clientes-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
if ($modelo!="")
{
	if ($ano == "") $ano = date("Y")-1;
	$requete = "SELECT * FROM Clientes WHERE 1=1";
	if ($_SESSION['usuario_nivel']>=2) $requete.= " AND (`IdRepresentante`=".$_SESSION['usuario_id']." OR `IdFamilia`=".$_SESSION['usuario_id'].")";
	$requete.= " ORDER BY `Id`";	
	
	if ($result = mysqli_query($db, $requete))
	{
		print "<table>";
		print "<tr><th>Id</th><th>".$lang["cliente"]."</th><th>T1</th><th>T2</th><th>T3</th><th>T4</th><th>".$lang["facturado"]."</th></tr>";
		$par = false;
		while ($listado = mysqli_fetch_object($result))
		{	
			$tmp_string = "";
			if ($par)
			{	
				$tmp_string.= "<tr class=\"par\">";
				$par = false;
			}
			else
			{
				$tmp_string.= "<tr>";
				$par = true;
			}			
			$tmp_string.= "<td>".$listado->Id."</td>";
			$tmp_string.= "<td>".$listado->DenominacionSocial."</td>";
			//TRIMESTRE 1
			$total_anual = 0;
			$total = 0;
			$tmp_string.= "<td>";
			$requete2 = "SELECT * FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.NumeroFactura<>'' AND `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-1-1' AND `Facturas`.Fecha<'".$ano."-4-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				while ($listado2 = mysqli_fetch_object($result2))
				{
					$total += $listado2->BaseImponible + ($listado2->BaseImponible*$listado2->Impuesto);
				}				
			}
			if ($total=="") $total = 0;
			$tmp_string.= $total." €</td>";
			$total_anual += $total;
			//TRIMESTRE 2
			$total = 0;
			$tmp_string.= "<td>";
			$requete2 = "SELECT * FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.NumeroFactura<>'' AND  `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-4-1' AND `Facturas`.Fecha<'".$ano."-7-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				while ($listado2 = mysqli_fetch_object($result2))
				{
					$total += $listado2->BaseImponible + ($listado2->BaseImponible*$listado2->Impuesto);
				}				
			}
			if ($total=="") $total = 0;
			$tmp_string.= $total." €</td>";
			$total_anual += $total;
			//TRIMESTRE 3
			$total = 0;
			$tmp_string.= "<td>";
			$requete2 = "SELECT * FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.NumeroFactura<>'' AND  `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-7-1' AND `Facturas`.Fecha<'".$ano."-10-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				while ($listado2 = mysqli_fetch_object($result2))
				{
					$total += $listado2->BaseImponible + ($listado2->BaseImponible*$listado2->Impuesto);
				}			
			}
			if ($total=="") $total = 0;
			$tmp_string.= $total." €</td>";
			$total_anual += $total;			
			//TRIMESTRE 4
			$total = 0;
			$tmp_string.= "<td>";
			$requete2 = "SELECT * FROM `FacturasLineas`, `Facturas` WHERE `Facturas`.NumeroFactura<>'' AND  `Facturas`.Id = `FacturasLineas`.IdFactura AND `Facturas`.IdEstado = 1 AND `Facturas`.IdCliente=".$listado->Id." AND `Facturas`.Fecha>='".$ano."-10-1' AND `Facturas`.Fecha<'".($ano+1)."-1-1'";
			
			if ($result2 = mysqli_query($db, $requete2))		
			{
				while ($listado2 = mysqli_fetch_object($result2))
				{
					$total+= $listado2->BaseImponible + ($listado2->BaseImponible*$listado2->Impuesto);
				}			
			}
			if ($total=="") $total = 0;
			$tmp_string.= $total." €</td>";
			$total_anual += $total;			
			$tmp_string.= "<td>".$total_anual." €</td>";
			$tmp_string.="</tr>";
			if ($total_anual>=3006) print $tmp_string;
			else
			{
				$par = !$par;
			}
		}
	}
}
else
{
	$requete = "SELECT Id FROM `Clientes` WHERE 1=1";	
	if ($_SESSION['usuario_nivel']>=2) $requete.= " AND (`IdRepresentante`=".$_SESSION['usuario_id']." OR `IdFamilia`=".$_SESSION['usuario_id'].")";
	if ($_POST['Id']!='') $requete.=" AND `Id`=".$_POST['Id'];
	if ($_POST['DenominacionSocial']!='') $requete.=" AND `DenominacionSocial` LIKE '%".$_POST['DenominacionSocial']."%'";
	if ($_POST['CIF']!='') $requete.=" AND `CIF`='".$_POST['CIF']."'";
	if ($_POST['Provincia']!='') $requete.=" AND `Provincia` LIKE '%".$_POST['Provincia']."%'";
	if ($_POST['Municipio']!='') $requete.=" AND `Municipio` LIKE '%".$_POST['Municipio']."%'";
	if ($_POST['CP']!='') $requete.=" AND `CP`='".$_POST['CP']."'";
	if ($_POST['Poblacion']!='') $requete.=" AND `Poblacion` LIKE '%".$_POST['Poblacion']."%'";
	if ($_POST['Web']!='') $requete.=" AND `Web` LIKE '%".$_POST['Web']."%'";
	if ($_POST['IdRepresentante']!='') $requete.=" AND `IdRepresentante`='".$_POST['IdRepresentante']."'";
	if ($_POST['IdFamilia']!='') $requete.=" AND `IdFamilia`='".$_POST['IdFamilia']."'";
	if ($_POST['Tipo']!='') $requete.=" AND `IdTipo`='".$_POST['Tipo']."'";
	if ($_POST['Estado']!='') $requete.=" AND `Estado`='".$_POST['Estado']."'";
	
	$total_contenidos_pagina = mysqli_num_rows($result);
	$requete = "SELECT * FROM `Clientes` WHERE 1=1";
	if ($_SESSION['usuario_nivel']>=2) $requete.= " AND (`IdRepresentante`=".$_SESSION['usuario_id']." OR `IdFamilia`=".$_SESSION['usuario_id'].")";
	if ($_POST['Id']!='') $requete.=" AND `Id`=".$_POST['Id'];
	if ($_POST['DenominacionSocial']!='') $requete.=" AND `DenominacionSocial` LIKE '%".$_POST['DenominacionSocial']."%'";
	if ($_POST['CIF']!='') $requete.=" AND `CIF`='".$_POST['CIF']."'";
	if ($_POST['Provincia']!='') $requete.=" AND `Provincia` LIKE '%".$_POST['Provincia']."%'";
	if ($_POST['Municipio']!='') $requete.=" AND `Municipio` LIKE '%".$_POST['Municipio']."%'";
	if ($_POST['CP']!='') $requete.=" AND `CP`='".$_POST['CP']."'";
	if ($_POST['Poblacion']!='') $requete.=" AND `Poblacion` LIKE '%".$_POST['Poblacion']."%'";
	if ($_POST['Web']!='') $requete.=" AND `Web` LIKE '%".$_POST['Web']."%'";
	if ($_POST['IdRepresentante']!='') $requete.=" AND `IdRepresentante`='".$_POST['IdRepresentante']."'";
	if ($_POST['IdFamilia']!='') $requete.=" AND `IdFamilia`='".$_POST['IdFamilia']."'";
	if ($_POST['Tipo']!='') $requete.=" AND `IdTipo`='".$_POST['Tipo']."'";
	if ($_POST['Estado']!='') $requete.=" AND `Estado`='".$_POST['Estado']."'";
	$requete .= " ORDER BY `Id` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;	
	
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN	
		print '<ul class="pagination">';
		if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
		else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
			else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$i."\">".$i."</a></li>";
		}	
		print "</ul>";
	}
	if ($result = mysqli_query($db, $requete))
	{
		print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['titulo-clientes'].'</h5>';
		print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
		print '<div class="ibox-content"><div class="row"><div class="">
									<table class="table table-striped">
										<thead>';
		print "<tr><th>".$lang["acciones"]."</th><th>Id</th><th>".$lang["cliente"]."</th><th>".$lang["contactos"]."</th><th>".$lang["representante"]."</th><th>".$lang["facturado12"]."</th><th>".$lang["facturado"]."</th></tr>";		
		print '</thead><tbody>';
		while ($listado = mysqli_fetch_object($result))
		{
			print "<tr>";	
			print "<td>";	
			print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
			print '<ul class="dropdown-menu">';
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id=".$listado->Id."\"><i class=\"fa fa-edit\"></i> ".$lang["editar"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=eliminar_cliente&Id=".$listado->Id."\"><i class=\"fa fa-remove\"></i> ".$lang["eliminar"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas&IdCliente=".$listado->Id."\"><i class=\"fa fa-calculator\"></i> ".$lang["facturas"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_contacto&Id=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><i class=\"fa fa-plus\"></i> ".$lang["nuevo_contacto"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_presupuesto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><i class=\"fa fa-briefcase\"></i> ".$lang["nuevoPresupuesto"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><i class=\"fa fa-briefcase\"></i> ".$lang["presupuestos"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_proyecto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><i class=\"fa fa-archive\"></i> ".$lang["nuevo_proyecto"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=crm&IdCliente=".$listado->Id."\"><i class=\"fa fa-phone\"></i> ".$lang["acciones_comerciales"]."</a></li>";
			print "<ul></div></td>";
			if ($listado->Referencia!="") print "<td>".$listado->Referencia."</td>";
			else print "<td>".$listado->Id."</td>";			
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
		print "</tbody><tfoot><tr><td colspan=\"5\"><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</td></tr></tfoot></table></div></div></div>";
		if (isset($Num_Pagina))
		{
			//PAGINACIÓN
			print '<ul class="pagination">';
			if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
			else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
			for ($i=0;($i<($max_pagina+1));$i++)
			{
				if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
				else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=clientes&pagina=".$i."\">".$i."</a></li>";
			}	
			print "</ul>";
		}
	}
}
?>
