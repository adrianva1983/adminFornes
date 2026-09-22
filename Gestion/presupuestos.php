<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$acciones_disponibles = true;
$pagina = $_GET["pagina"];
$IdCliente = $_GET["IdCliente"];

//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
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
//Accedemos a la base de datos
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/presupuestos-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Presupuestos`";
$requete .= "WHERE 1=1";
if ($IdCliente!="") $requete.=" AND IdCliente=".$IdCliente;
if ($_SESSION['usuario_nivel']>=2)//Si es coordinador o menos, que solo vea los suyos
{
	$requete.= " AND (`IdFamilia`= ".$_SESSION['usuario_id']." OR IdRepresentante = ".$_SESSION['usuario_id'].")";
}
if ($_GET['Buscar']!='') $requete.=" AND (`TituloSolicitud` LIKE '%".$_GET['Buscar']."%' OR `TituloEnvio` LIKE '%".$_GET['Buscar']."%' OR `NombreEmpresa` LIKE '%".$_GET['Buscar']."%')";
$result = mysqli_query($db, $requete);
$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `Fecha` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;
if (isset($Num_Pagina))
{	
	//PAGINACIÓN	
	print '<ul class="pagination">';
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&pagina=".$i."\">".$i."</a></li>";
	}	
	print "</ul>";
}
$result = mysqli_query($db, $requete);
if ($result && mysqli_num_rows($result)>0)
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['titulo-presupuestos'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content">';
	print '<div class="row"><div class="col-md-12">
						<form method="get">
						<div class="input-group">	
							<input type="hidden" name="modulo" value="Gestion"/>
							<input type="hidden" name="herramienta" value="presupuestos"/>
							<input type="hidden" name="pagina" value="'.$_GET['pagina'].'"/>
							<input type="text" name="Buscar" placeholder="'.$lang['buscar'].'" class="input form-control" name="Buscar">
							<span class="input-group-btn">
									<button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i></button>
							</span>
						</div>
						</form></div></div>';
	print '<div class="row"><div class="">
                                <table class="table table-striped">
                                    <thead>';
	print "<tr><th>".$lang["acciones"]."</th><th>Id</th><th>".$lang["fecha"]."</th><th>".$lang["envio"]."</th><th>".$lang["cliente"]."</th><th>".$lang["total"]."</th><th>".$lang["estado"]."</th><th>".$lang["representante"]."</th></tr>";
	print '</thead><tbody>';
	while ($listado = mysqli_fetch_object($result))
	{
		print "<tr>";	
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_presupuesto&Id=".$listado->Id."\"><i class='fa fa-edit'></i> ".$lang["editar"]."</a><li>";
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturar_presupuesto&Id=".$listado->Id."\"><i class='fa fa-calculator'></i> ".$lang["facturar"]."</a><li>";		
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas&IdPresupuesto=".$listado->Id."\"><i class='fa fa-link'></i> ".$lang["facturas"]."</a></li>";
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_accion_crm&IdPresupuesto=".$listado->Id."\"><i class='fa fa-phone'></i> ".$lang["planificar_seguimiento_comercial"]."</a></li>";
		print "<ul></div></td>";
		print "<td>".$listado->Id."</td>";
		print "<td>";
		print $listado->Fecha;
		print "</td>";		
		print "<td>".$listado->FechaEnvio."</td>";
		$requete2 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado->IdCliente."'";
		$result2 = mysqli_query($db, $requete2);
		print "<td>";
		if ($result2 && mysqli_num_rows($result2))
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
		print "<td>";
		$requete2 = "SELECT * FROM `PresupuestosLineas` WHERE `IdPresupuesto` = '".$listado->Id."'";
		
		$total = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			while ($listado2 = mysqli_fetch_object($result2))
			{
				$total += $listado2->BaseImponible;
			}
		}
		print $total."€</td>";
		$requete2 = "SELECT * FROM `PresupuestosEstados` WHERE `Id` = '".$listado->IdEstado."' AND `Idioma`='".$_SESSION['idioma']."'";
		
		print "<td>";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->Titulo;
		}
		else
		{
			print "-";
		}
		print "</td>";
		print "<td>";
		if ($listado->IdRepresentante!="")
		{
			$requete2 = "SELECT * FROM `Usuarios` WHERE `Id` = '".$listado->IdRepresentante."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				print $listado2->Nombre." ".$listado2->Apellidos;
			}
			else
			{
				print "-";
			}
		}
		print "</td>";
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
			else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&pagina=".$i."\">".$i."</a></li>";
		}	
		print "</ul>";
	}
}
//PINTAMOS GRÁFICOS
$requete = "SELECT * FROM PresupuestosEstados WHERE Idioma='".$_SESSION['idioma']."'";

if ($result = mysqli_query($db, $requete))
{
	print "<script type=\"text/javascript\">";
	print "google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});";
	print "google.setOnLoadCallback(drawChart);";
	print "function drawChart() {";
	//Datos para Gráfico Trimestral de quesos
	print "var data = google.visualization.arrayToDataTable([";	
	print "['".$lang["presupuestos"]."', '".$lang["estado"]."']";
	$ano = date("Y");
	$mes = date("m");
	$dia = date("d");
	$mesAnteriorTrimestre = $mes-3;
	$anoAnteriorTrimestre = $ano;
	$diaAnteriorTrimestre = $dia;
	$mesAnteriorAnual = $mes;
	$anoAnteriorAnual = $ano-1;
	$diaAnteriorAnual = $dia;
	if ($mes<=3)
	{
		$mesAnteriorTrimestre = 12 - (3 - $mes); 
	}
	if ($dia>=28)
	{
		if ($dia>30) $diaAnteriorTrimestre = 30;
		if ($mesAnteriorTrimestre == 2) $diaAnteriorTrimestre = 28;
		if ($mes==2) $diaAnteriorAnual = 28;
	}		
	while ($listado = mysqli_fetch_object($result))
	{
		$requete2="SELECT Id FROM Presupuestos WHERE IdEstado=".$listado->Id." AND Fecha>='".$anoAnteriorTrimestre."-".$mesAnteriorTrimestre."-".$diaAnteriorTrimestre."'";
		
		if ($result2 = mysqli_query($db, $requete2)) $numero = mysqli_num_rows($result2);
		else $numero = 0;
		print ",['".$listado->Titulo."',".$numero."]";
	}
	print "]);";
        print "var options = {";
	print "title: '".$lang["tituloGrafico1"]."',";	
	print "legend:{position: 'bottom'}";
        print "};";
	//Datos para gráfico anual de quesos	
	print "var data2 = google.visualization.arrayToDataTable([";	
	print "['".$lang["presupuestos"]."', '".$lang["estado"]."']";
	$requete = "SELECT * FROM PresupuestosEstados WHERE Idioma='".$_SESSION['idioma']."'";
	$result = mysqli_query($db, $requete);	
	$i=0;
	while ($listado = mysqli_fetch_object($result))
	{
		$tipos[$i]=$listado->Id;
		$tiposNombre[$i]=$listado->Titulo;
		$requete2="SELECT Id FROM Presupuestos WHERE IdEstado=".$listado->Id." AND Fecha>='".$anoAnteriorAnual."-".$mesAnteriorAnual."-".$diaAnteriorAnual."'";
		
		if ($result2 = mysqli_query($db, $requete2)) $numero = mysqli_num_rows($result2);
		else $numero = 0;
		print ",['".$listado->Titulo."',".$numero."]";
		$i++;
	}
	print "]);";
        print "var options2 = {";
	print "title: '".$lang["tituloGrafico2"]."',";
	print "legend:{position: 'bottom'}";
        print "};";
	print "var data3= google.visualization.arrayToDataTable([";
	print "['".$lang["mes"]."'";
	for ($i=0;$i<count($tipos);$i++)
	{
		print ",'".$tiposNombre[$i]."'";
	}
	print "]";
	for ($j=1;$j<=12;$j++)
	{
		//Pinto meses
		print ",['".$j."/".$ano."'";		
		for ($i=0;$i<count($tipos);$i++)
		{
			$anoTMP = $ano;
			$mesTMP = $j;
			$diaTMP = 1;
			if (($j==1)||($j==3)||($j==5)||($j==7)||($j==8)||($j==10)||($j==12)) $diaTMP2 = 30;
			else $diaTMP2 = 30;
			if ($j==2) $diaTMP2 = 28;			
			$requete2="SELECT Id FROM Presupuestos WHERE IdEstado=".$tipos[$i]." AND Fecha>='".$anoTMP."-".$mesTMP."-".$diaTMP."' AND Fecha<='".$anoTMP."-".$mesTMP."-".$diaTMP2."'";
			
			if ($result2 = mysqli_query($db, $requete2)) $numero = mysqli_num_rows($result2);
			else $numero = 0;
			print ",".$numero;
		}
		print "]";
	}
	print "]);";
	print "var options3 = {";
	print "title: '".$lang["tituloGrafico3"]."',";
	print "legend:{position: 'bottom'}";
	print "};";
}
print "var chart = new google.visualization.PieChart(document.getElementById('chart_div'));";
print "chart.draw(data, options);";
print "var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));";
print "chart2.draw(data2, options2);";
print "var chart3 = new google.visualization.LineChart(document.getElementById('chart_div3'));";
print "chart3.draw(data3, options3);";
print "}";
print "</script>";
print  "<div class=\"row\"><div class=\"col-md-6\"><div class=\"ibox\"><div class=\"ibox-content\"><div id=\"chart_div\" style=\"width: 450px; height: 350px;float:left;\"></div></div></div></div>";
print  "<div class=\"col-md-6\"><div class=\"ibox\"><div class=\"ibox-content\"><div id=\"chart_div2\" style=\"width: 450px; height: 350px;float:left;\"></div></div></div></div></div>";
print  "<div class=\"row\"><div class=\"col-md-12\"><div class=\"ibox\"><div class=\"ibox-content\"><div id=\"chart_div3\" style=\"width: 900px; height: 350px;float:left;\"></div></div></div></div></div>";
?>
