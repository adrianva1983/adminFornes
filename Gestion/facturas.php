<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pendientesPago = $_GET["pendientesPago"];
$estado = $_GET["estado"];
$IdCliente = $_GET["IdCliente"];
$pagina = $_GET["pagina"];
$Fecha1 = $_GET["Fecha1"];
$Fecha2 = $_GET["Fecha2"];
$IdFamilia = $_GET["IdFamilia"];
$Gastos = $_GET["Gastos"];
$Empresa = $_GET["Empresa"];
$IdRepresentante = $_GET["IdRepresentante"];
$IdFactura = $_GET["IdFactura"];
$IdPresupuesto = $_GET['IdPresupuesto'];
$Buscar = $_GET['Buscar'];
//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/facturas-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
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
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$params = "";
if ($IdRepresentante!="" || $IdFamilia !="") 
{
	$requete = "SELECT `Facturas`.IdEstado,`Facturas`.IdCliente,`Facturas`.Vencimiento,`Facturas`.IdPresupuesto,`Facturas`.Titulo,`Facturas`.Id,`Facturas`.Rectificativa,`Facturas`.Saldada,`Facturas`.SerieFactura,`Facturas`.NumeroFactura FROM `Facturas`,`Presupuestos` WHERE `Facturas`.IdPresupuesto = `Presupuestos`.Id";
	$requete2 = "SELECT SUM(`FacturasLineas`.BaseImponible) AS Suma FROM `Facturas`,`FacturasLineas`,`Presupuestos` WHERE `Facturas`.IdPresupuesto = `Presupuestos`.Id AND `Facturas`.Id=`FacturasLineas`.IdFactura";
}
else 
{
	$requete = "SELECT * FROM `Facturas` WHERE 1=1";
	$requete2 = "SELECT SUM(BaseImponible) AS Suma FROM `Facturas`,`FacturasLineas` WHERE `Facturas`.Id=`FacturasLineas`.IdFactura";
}
if ($Fecha1!="") 
{	
	$filtros.= " AND `Facturas`.Fecha>='".$Fecha1."'";
	$params.="&amp;Fecha1=".$Fecha1;
}
if ($Fecha2!="") 
{	
	$filtros.= " AND `Facturas`.Fecha<='".$Fecha2."'";
	$params.="&amp;Fecha2=".$Fecha2;
}
if ($Buscar!='')
{
	$filtros.= " AND (`Facturas`.Titulo LIKE '%".$Buscar."%' OR `Facturas`.NumeroFactura = '".$Buscar."')";
	$params.="&amp;Buscar=".$Buscar;
}
if ($IdPresupuesto!='')
{
	$filtros.= " AND `Facturas`.IdPresupuesto=".$IdPresupuesto;
	$params.="&amp;IdPresupuesto=".$IdPresupuesto;	
}
if ($IdFactura!="") 
{	
	$filtros.= " AND `Facturas`.NumeroFactura=".$IdFactura;
	$params.="&amp;IdFactura=".$IdFactura;
}
if ($IdRepresentante!="") 
{	
	$filtros.= " AND `Presupuestos`.IdRepresentante=".$IdRepresentante;
	$params.="&amp;IdRepresentante=".$IdRepresentante;
}
if ($IdFamilia!="") 
{	
	$filtros.= " AND `Presupuestos`.IdFamilia=".$IdFamilia;
	$params.="&amp;IdFamilia=".$IdFamilia;
}
if ($pendientesPago==1) 
{
	$filtros.=" AND (`Facturas`.Saldada IS NULL OR `Facturas`.Saldada=0) AND (`Facturas`.Rectificativa IS NULL OR `Facturas`.Rectificativa=0)";
	$params.="&amp;pendientesPago=1";
}
if ($Empresa!="") 
{	
	if ($Empresa==-1) $filtros.= " AND (`Facturas`.IdEmpresa IS NULL OR `Facturas`.IdEmpresa = '')";
	else $filtros.= " AND `Facturas`.IdEmpresa=".$Empresa;
	$params.="&amp;Empresa=".$Empresa;
}
if ($IdCliente!="") 
{	
	$filtros.= " AND `Facturas`.IdCliente=".$IdCliente;
	$params.="&amp;IdCliente=".$IdCliente;
}
if ($Gastos!="")
{
	$filtros.= " AND `Facturas`.FacturaGasto=1";
	$params.="&amp;Gastos=1";	
}
else
{
	$filtros.= " AND (`Facturas`.FacturaGasto<>1 OR `Facturas`.FacturaGasto IS NULL)";
}
if ($estado!="") 
{	
	$filtros.= " AND `Facturas`.IdEstado=".$estado;
	$params_sin_estado = $params;
	$params.="&amp;estado=".$estado;
}
$requete.=$filtros;
$requete2.=$filtros;
// Herramientas superiores
print '<div class="row">';
print "<div class=\"btn-group col-md-4\">";
print "<a class=\"btn btn-sm";
if ($Empresa =="") print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas".$params."\">".$lang['todas']."</a>";
print "<a class=\"btn btn-sm";
if ($Empresa ==-1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas&amp;Empresa=-1".$params."\">".$lang['ninguna']."</a>";
$requete_empresas = "SELECT * FROM `FacturasEmpresas`";
if ($result_empresas = mysqli_query($db, $requete_empresas))
{
	//Pinto las empresas
	while ($listado_empresas = mysqli_fetch_object($result_empresas))
	{
		print "<a class=\"btn btn-sm";
		if ($Empresa ==$listado_empresas->IdEmpresa) print " btn-primary";
		else print " btn-white";
		print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas&amp;Empresa=".$listado_empresas->IdEmpresa.$params."\">".$listado_empresas->NombreComercial."</a>";
	}
}
print "</div>";
print "<div class=\"btn-group col-md-3\">";
print "<a class=\"btn btn-sm";
if ($Gastos !=1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas".$params."\">".$lang["facturasIngreso"]."</a>";
print "<a class=\"btn btn-sm";
if ($Gastos ==1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas&amp;Gastos=1".$params."\">".$lang["facturasGasto"]."</a>";
print "</div>";
print "<div class=\"btn-group col-md-5\">";
print "<a class=\"btn btn-sm";
if ($pendientesPago==1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas&amp;pendientesPago=1".$params."\">".$lang["pendientesPago"]."</a>";
$requete_estados = "SELECT * From FacturasEstados WHERE Idioma='".$_SESSION['idioma']."' ORDER BY Id";
if ($result_estados = mysqli_query($db, $requete_estados))
{
	//Pinto los estados
	while ($listado_estados = mysqli_fetch_object($result_estados))
	{
		print "<a class=\"btn btn-sm";
		if ($estado==$listado_estados->Id) print " btn-primary";
		else print " btn-white";
		print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=facturas&amp;estado=".$listado_estados->Id.$params_sin_estado."\">".$listado_estados->Titulo."</a>";
	}	
}
print "</div></div>";
if ($result2 = mysqli_query($db, $requete2))
{
	$listado2 = mysqli_fetch_object($result2);
	$totalizado_resultado = $listado2->Suma;
}
$result = mysqli_query($db, $requete);
$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `Facturas`.Fecha DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;
//if ($Gastos==1) $requete .= " ORDER BY `Facturas`.Fecha DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;
//else $requete .= " ORDER BY `Facturas`.NumeroFactura DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

//Paginación
if (isset($Num_Pagina))
{
	//PAGINACIÓN	
	print '<ul class="pagination">';
	$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina--;
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas&pagina=".$i.$params."\">".$i."</a></li>";
	}	
	print "</ul>";
}
if ($result = mysqli_query($db, $requete))
{
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['titulo-facturas'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content">';
	print '
	<div class="row"><div class="col-md-12">
						<form method="get">
						<div class="input-group">	
							<input type="hidden" name="modulo" value="Gestion"/>
							<input type="hidden" name="herramienta" value="facturas"/>
							<input type="hidden" name="Empresa" value="'.$_GET['Empresa'].'"/>
							<input type="hidden" name="Gastos" value="'.$_GET['Gastos'].'"/>
							<input type="hidden" name="pendientesPago" value="'.$_GET['pendientesPago'].'"/>
							<input type="hidden" name="estado" value="'.$_GET['estado'].'"/>
							<input type="hidden" name="IdCliente" value="'.$_GET['IdCliente'].'"/>
							<input type="hidden" name="Fecha1" value="'.$_GET['Fecha1'].'"/>
							<input type="hidden" name="Fecha2" value="'.$_GET['Fecha2'].'"/>
							<input type="hidden" name="IdFamilia" value="'.$_GET['IdFamilia'].'"/>
							<input type="hidden" name="IdRepresentante" value="'.$_GET['IdRepresentante'].'"/>
							<input type="hidden" name="IdFactura" value="'.$_GET['IdFactura'].'"/>
							<input type="hidden" name="IdPresupuesto" value="'.$_GET['IdPresupuesto'].'"/>
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
	print "<tr><th>".$lang["acciones"]."</th><th>".$lang["saldado"]."</th><th>".$lang["numeroFactura"]."</th><th>".$lang["estado"]."</th><th>".$lang["fecha"]."</th><th>".$lang["titulo"]."</th><th>".$lang["cliente"]."</th><th>".$lang["baseImponible"]."</th><th>".$lang["IVAinc"]."</th></tr>";
	print '</thead><tbody>';	
	while ($listado = mysqli_fetch_object($result))
	{
		print "<tr>";		
		print "<td>";	
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		if($listado->FacturaGasto!=1) print "<li><a href=\"/administra/Gestion/factura_imprimible.php?Id=".$listado->Id."&pdf=si&seguridad=".md5($listado->Id.$listado->Fecha."SEMILLA123".$listado->Vencimiento)."&Idioma=".$_SESSION['idioma']."\" target=\"_blank\"><i class=\"fa fa-download\"></i> ".$lang["descargar"]."</a></li>";
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_factura&Id=".$listado->Id."&pagina=".$pagina."\"><i class=\"fa fa-edit\"></i> ".$lang["editar"]."</a></li>";
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=insertar_pago&Id=".$listado->Id."\"><i class=\"fa fa-retweet\"></i> ".$lang["insertarPago"]."</a></li>";
		if($listado->FacturaGasto==1&&$listado->IdPeriodicidad!="") print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_factura&IdSerie=".$listado->Id."\"><i class=\"fa fa-plus\"></i> ".$lang["siguienteFactura"]."</a></li>";
		if($listado->FacturaGasto!=1) print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_presupuesto&Id=".$listado->IdPresupuesto."\"><i class=\"fa fa-briefcase\"></i> ".$lang["presupuestoRelacionado"]."</a></li>";
		if($listado->FacturaGasto!=1) print "<li><a href=\"/administra/Gestion/enviar_mail_factura.php?Id=".$listado->Id."&Idioma=".$_SESSION['idioma']."\"><i class=\"fa fa-send\"></i> ".$lang["enviarPorMail"]."</a></li>";
		print "<ul></div></td>";
		print "<td>";
		if ($listado->Rectificativa==1) print "<img src=\"/administra/Imagenes/bullet_error.png\" title=\"".$lang["rectificativa"]."\" alt=\"".$lang["rectificativa"]."\">";
		else
		{
			if ($listado->Saldada==1) print "<span class=\"label label-primary\"title=\"".$lang["saldado"]."\" alt=\"".$lang["saldado"]."\"><i class=\"fa fa-check\"></i></span>";
			else print "<span class=\"label label-danger\" title=\"".$lang["noSaldado"]."\" alt=\"".$lang["noSaldado"]."\"><i class=\"fa fa-remove\"></i></span>";
		}
		print "</td>";
		print "<td>";
		if ($listado->FacturaGasto==1)
		{			
			print $listado->CodigoFacturaGasto."</td>";
		}
		else
		{
			if ($listado->SerieFactura!="") print $listado->SerieFactura."-";
			print $listado->NumeroFactura."</td>";	
		}
		$requete2 = "SELECT * FROM `FacturasEstados` WHERE `Id` = '".$listado->IdEstado."' AND `Idioma`='".$_SESSION['idioma']."'";
				
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
		if ($listado->FechaDescarga!="") print ' <i class="fa fa-download" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$lang["descargada"]." ".$listado->FechaDescarga.'"></i>';
		else if ($listado->FechaEnvio!="") print ' <i class="fa fa-send" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$lang["enviada"]." ".$listado->FechaEnvio.'"></i>';
		print "</td>";
		print "<td>".$listado->Fecha;
		if ($listado->Vencimiento!="") print "<br/>(".$listado->Vencimiento.")";
		print "</td>";
		print "<td>".$listado->Titulo."</td>";
		if($listado->FacturaGasto!=1) $requete2 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado->IdCliente."'";
		else $requete2 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado->IdProveedor."'";
		
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
		print "<td>";
		$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado->Id."'";
		
		$total = 0;
		$totalfinal = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			while ($listado2 = mysqli_fetch_object($result2))
			{
				$total += $listado2->BaseImponible;
				$totalfinal += $listado2->BaseImponible + ($listado2->BaseImponible*$listado2->Impuesto);
			}
		}
		print $total."€</td>";
		print "<td>".$totalfinal."€</td>";
		print "</tr>";
	}
	print "</tbody><tfoot><tr><td colspan=\"9\"><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</td></tr></tfoot></table></div>";
	if ($IdPresupuesto!='') 
	{
		print "<div class=\"btn-group pull-right\"><a class=\"btn btn-white\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_presupuesto&Id=".$IdPresupuesto."\"><img src=\"/administra/Imagenes/calculator_edit.png\" title=\"".$lang["editar_presupuesto_origen"]."\" alt=\"".$lang["editar_presupuesto_origen"]."\"> ".$lang["editar_presupuesto_origen"]."</a>";
		print "<a class=\"btn btn-white\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturar_presupuesto&Id=".$IdPresupuesto."\"><img src=\"/administra/Imagenes/accept.png\" title=\"".$lang["facturar_presupuesto"]."\" alt=\"".$lang["facturar_presupuesto"]."\"> ".$lang["facturar_presupuesto"]."</a></div>";	
	}
	print "</div></div>";
	//Paginación
	if (isset($Num_Pagina))
	{
		//PAGINACIÓN		
		print '<ul class="pagination">';
		$max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
		if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina--;
		for ($i=0;($i<($max_pagina+1));$i++)
		{
			if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
			else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas&pagina=".$i.$params."\">".$i."</a></li>";
		}	
		print "</ul>";
	}	
	print '</div></div></div>';
}
if ($totalizado_resultado>0) print "<p class=\"mensaje\"><strong>".$lang["totalizado"].":</strong> ".$totalizado_resultado."</p>";
//PINTAMOS GRÁFICOS
print '
	<div class="row">
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>'.$lang['facturadoAnoActual'].'</h5>
			</div>
			<div class="ibox-content">
				<div>
					<canvas id="barChart1" height="140"></canvas>
				</div>
			</div>
		</div>
	</div>';
print '	
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>'.$lang['facturadoAnoAnterior'].'</h5>
			</div>
			<div class="ibox-content">
				<div>
					<canvas id="barChart2" height="140"></canvas>
				</div>
			</div>
		</div>
	</div>
	</div>';
print '
	<div class="row">
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>'.$lang['riesgo'].'</h5>
			</div>
			<div class="ibox-content">
				<div>
					<canvas id="doughnut1" height="140"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>'.$lang['tituloGrafico3'].'</h5>
			</div>
			<div class="ibox-content">
				<div>
					<canvas id="doughnut2" height="140"></canvas>
				</div>
			</div>
		</div>
	</div>
	</div>';
$ano = date("Y");
$anoanterior = $ano-1;
$mes = date("m");
$facturasAnoAnteriorTotal=0;
$facturasAnoAnteriorTotalGastos=0;
$facturasAnoActualTotal=0;
$facturasAnoActualTotalGasto=0;
$facturadoPrevistoIngreso = array(0,0,0,0,0,0,0,0,0,0,0,0);
$facturadoPrevistoGasto = array(0,0,0,0,0,0,0,0,0,0,0,0);
$mes_actual = intval(date('m'))-1;
$dia_actual = intval(date('d'));
$ano_actual = intval(date('Y'));
$facturasPrevistas=0;
for ($i=1;$i<=12;$i++)
{
	$requete = "SELECT * FROM Facturas WHERE (Rectificativa IS NULL OR Rectificativa =0) AND Fecha>='".$anoanterior."-".$i."-01' AND Fecha<";
	if ($i==12) $requete.= "'".$ano."-01-01'";
	else $requete.= "'".$anoanterior."-".($i+1)."-01'";	
	if ($Empresa!="")
	{
		if ($Empresa == -1) $requete.= " AND (`IdEmpresa` IS NULL OR `IdEmpresa`='')";
		else $requete.= " AND `IdEmpresa` = ".$Empresa;
	}		
	$facturasMes=0;
	$facturasMesGastos=0;
	if ($result = mysqli_query($db, $requete))		
	{		
		while ($listado = mysqli_fetch_object($result))
		{
			$requete2 = "SELECT * FROM `Facturas` WHERE `IdFacturaRelacionada` = '".$listado->Id."' AND `Rectificativa`=1";			
			$result2 = mysqli_query($db, $requete2);
			if (mysqli_num_rows($result2)>0) //EXCLUYO DE GRÁFICOS LAS FACTURAS RECTIFICADAS
			{}
			else
			{				
				$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado->Id."'";
				if ($result2 = mysqli_query($db, $requete2))
				{
					while ($listado2 = mysqli_fetch_object($result2))
					{
						if ($listado->FacturaGasto==1) $facturasMesGastos += $listado2->BaseImponible;
						else $facturasMes += $listado2->BaseImponible;
					}
				}
			}
		}
	}
	$facturadoAnoAnterior[$i] = $facturasMes;
	$facturadoAnoAnteriorGastos[$i] = $facturasMesGastos;
	$facturasAnoAnteriorTotal+=$facturasMes;
	$facturasAnoAnteriorTotalGastos+=$facturasMesGastos;
}
for ($i=1;$i<=12;$i++)
{
	$requete = "SELECT * FROM Facturas WHERE (Rectificativa IS NULL OR Rectificativa =0) AND Fecha>='".$ano."-".$i."-01' AND Fecha<";
	if ($i==12) $requete.= "'".($ano+1)."-01-01'";
	else $requete.= "'".$ano."-".($i+1)."-01'";
	if ($Empresa!="")
	{
		if ($Empresa == -1) $requete.= " AND (`IdEmpresa` IS NULL OR `IdEmpresa`='')";
		else $requete.= " AND `IdEmpresa` = ".$Empresa;
	}		
	
	$facturasMes=0;
	$facturasMesGastos=0;
	$facturasMesActual=0;	
	$facturasMesActualGastos=0;	
	if ($result = mysqli_query($db, $requete))
	{
		while ($listado = mysqli_fetch_object($result))
		{
			$requete2 = "SELECT * FROM `Facturas` WHERE `IdFacturaRelacionada` = '".$listado->Id."' AND `Rectificativa`=1";
			$result2 = mysqli_query($db, $requete2);			
			if (mysqli_num_rows($result2)>0) //EXCLUYO DE GRÁFICOS LAS FACTURAS RECTIFICADAS
			{}
			else
			{
				$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado->Id."'";
							
				if ($result2 = mysqli_query($db, $requete2))
				{
					$importeFactura=0;
					while ($listado2 = mysqli_fetch_object($result2))
					{
						if ($listado->FacturaGasto==1)
						{
							$facturasMesGastos += $listado2->BaseImponible;							
							if ($i==date("m")) $facturasMesActualGastos += $listado2->BaseImponible;
						}
						else
						{
							$facturasMes += $listado2->BaseImponible;
							$importeFactura += $listado2->BaseImponible;
							if ($i==date("m")) $facturasMesActual += $listado2->BaseImponible;							
						}
					}
				}				
			}
		}	
	}
	$facturasAnoActualTotal+=$facturasMes;
	$facturasAnoActualTotalGastos+=$facturasMesGastos;
	$facturadoAnoActual[$i] = $facturasMes;
	$facturadoAnoActualGastos[$i] = $facturasMesGastos;
}
$requete = "SELECT * FROM Facturas WHERE (Rectificativa IS NULL OR Rectificativa =0) AND IdEstado<>1";		
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT * FROM `Facturas` WHERE `IdFacturaRelacionada` = '".$listado->Id."' AND `Rectificativa`=1";
		$result2 = mysqli_query($db, $requete2);
		if (mysqli_num_rows($result2)>0) //EXCLUYO DE GRÁFICOS LAS FACTURAS RECTIFICADAS
		{}
		else
		{
			$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado->Id."'";
						
			if ($result2 = mysqli_query($db, $requete2))
			{
				while ($listado2 = mysqli_fetch_object($result2))
				{
					$facturasPrevistas += $listado2->BaseImponible;
				}
			}
		}
	}
}
//Preparamos previsión de ingresos y gastos futura mes a mes
$requete = "SELECT *,Facturas.Id AS IdFactura FROM Facturas,FacturasPeriodicidades WHERE Facturas.IdPeriodicidad = FacturasPeriodicidades.Id AND Facturas.IdPeriodicidad IS NOT NULL";
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT * FROM Facturas WHERE IdSerie=".$listado->IdFactura." ORDER BY Fecha DESC";									
		$total_tmp = 0;
		if ($result2 = mysqli_query($db, $requete2)){}
		else
		{
			//Es la primera de la serie, por lo que recupero la original
			$requete2 = "SELECT * FROM Facturas WHERE Id=".$listado->IdFactura." ORDER BY Fecha DESC";				
		}
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			$ano_ultimo = intval(date('Y',strtotime($listado2->Fecha)));			
			$mes_ultimo = intval(date('m',strtotime($listado2->Fecha)))-1;			
			$dia_ultimo = intval(date('d',strtotime($listado2->Fecha)));
			$requete3 = "SELECT SUM(BaseImponible) AS Total FROM FacturasLineas WHERE IdFactura=".$listado2->Id;						
			if ($result3 = mysqli_query($db, $requete3))
			{
				$listado3 = mysqli_fetch_object($result3);
				$total_tmp += $listado3->Total;
			}			
		}
		$cantidad_cadencia = $listado->Cantidad;		
		if ($listado->UnidadCadencia=='Meses')
		{
			for ($i=$mes_actual;$i<12;$i++)
			{
				if ($i == ($mes_ultimo + $cantidad_cadencia))
				{
					if ($listado->FacturaGasto==1) $facturadoPrevistoGasto[$i] += $total_tmp;
					else $facturadoPrevistoIngreso[$i] += $total_tmp;	
					$mes_ultimo = $mes_ultimo + $cantidad_cadencia;
					$ano_ultimo = $ano_actual;
				}
				else
				{					
					if ($mes_ultimo>$mes_actual&&$ano_ultimo<$ano_actual)
					{						
						if ($listado->FacturaGasto==1) $facturadoPrevistoGasto[$i] += $total_tmp;
						else $facturadoPrevistoIngreso[$i] += $total_tmp;	
						$mes_ultimo = $mes_actual;						
						$ano_ultimo = $ano_actual;
					}					
				}
			}
		}
		else if ($listado->UnidadCadencia=='Años')
		{
			if ($mes_actual > $mes_ultimo)
			{
				if ($listado->FacturaGasto==1) $facturadoPrevistoGasto[$mes_ultimo+1] += $total_tmp;
				else $facturadoPrevistoIngreso[$mes_ultimo+1] += $total_tmp;
			}
		}
		else if ($listado->UnidadCadencia=='Días')
		{
			$multiplicador_tmp = $cantidad_cadencia/30;
			$total_tmp_mes = $total_tmp*$multiplcador_tmp;
			for ($i=($mes_actual-1);$i<12;$i++)
			{
				$total_tmp_mes_2 = $total_tmp_mes;
				if ($mes_actual == $mes_ultimo && ($dia_actual<$dia_ultimo)) $total_tmp_mes2 = $total_tmp_mes - $total_tmp;
				if ($listado->FacturaGasto==1) $facturadoPrevistoGasto[$i] += $total_tmp_mes_2;
				else $facturadoPrevistoIngreso[$i] += $total_tmp_mes_2;	
			}
		}
	}
}
//Datos para Gráfico Facturacion año actual
$javascript_onready.='var barData1 ={
	labels:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
	datasets:[
	{
		label:"'.$lang["facturado"].' '.$ano.'",
		fillColor: "rgba(92,184,92,0.5)",
		strokeColor: "rgba(92,184,92,0.8)",
		highlightFill: "rgba(92,184,92,0.75)",
		highlightStroke: "rgba(92,184,92,1)",
		data: [
	';
for ($i=1;$i<=12;$i++)
{
	if ($i>1) $javascript_onready.=',';
	if ($facturadoAnoActual[$i]=="") $facturadoAnoActual[$i] = 0;
	$javascript_onready.= $facturadoAnoActual[$i];
}
	$javascript_onready.=']
	},
	{
		label:"'.$lang["gastos"].' '.$ano.'",
		fillColor: "rgba(217,83,79,0.5)",
		strokeColor: "rgba(217,83,79,0.8)",
		highlightFill: "rgba(217,83,79,0.75)",
		highlightStroke: "rgba(217,83,79,1)",
		data: [
	';
for ($i=1;$i<=12;$i++)
{
	if ($i>1) $javascript_onready.=',';
	if ($facturadoAnoActualGastos[$i]=="") $facturadoAnoActualGastos[$i] = 0;
	$javascript_onready.= $facturadoAnoActualGastos[$i];
}
$javascript_onready.=']	
	},';
	$javascript_onready.= '{
		label:"'.$lang["ingresos_previstos"].' '.$ano.'",
		fillColor: "rgba(223,240,216,0.5)",
		strokeColor: "rgba(214,233,198,0.8)",
		highlightFill: "rgba(214,233,198,0.75)",
		highlightStroke: "rgba(214,233,198,1)",
		data: [
	';
for ($i=0;$i<12;$i++)
{
	if ($i>0) $javascript_onready.=',';
	if ($facturadoPrevistoIngreso[$i]=="") $facturadoPrevistoIngreso[$i] = 0;
	$javascript_onready.= number_format($facturadoPrevistoIngreso[$i],0,"","");
}
//#dff0d8
//#d6e9c6
$javascript_onready.=']
	},';
	$javascript_onready.= '{
		label:"'.$lang["gastos_previstos"].' '.$ano.'",
		fillColor: "rgba(242,222,222,0.5)",
		strokeColor: "rgba(235,204,209,0.8)",
		highlightFill: "rgba(235,204,209,0.75)",
		highlightStroke: "rgba(235,204,209,1)",
		data: [
	';
for ($i=0;$i<12;$i++)
{
	if ($i>0) $javascript_onready.=',';
	if ($facturadoPrevistoGasto[$i]=="") $facturadoPrevistoGasto[$i] = 0;
	$javascript_onready.= number_format($facturadoPrevistoGasto[$i],0,"","");
}
$javascript_onready.=']
	}
	]};';
	//#f2dede
	//#ebccd1
	$javascript_onready.='
	var barOptions = {
		scaleBeginAtZero: true,
		scaleShowGridLines: true,
		scaleGridLineColor: "rgba(0,0,0,.05)",
		scaleGridLineWidth: 1,
		barShowStroke: true,
		barStrokeWidth: 2,
		barValueSpacing: 5,
		barDatasetSpacing: 1,
		responsive: true
	}
	var ctx = document.getElementById("barChart1").getContext("2d");
	var myNewChart = new Chart(ctx).Bar(barData1, barOptions);
	';
//Datos para Gráfico Gastos
$javascript_onready.='var barData2 ={
	labels:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
	datasets:[
	{
		label:"'.$lang["facturado"].' '.$ano.'",
		fillColor: "rgba(92,184,92,0.5)",
		strokeColor: "rgba(92,184,92,0.8)",
		highlightFill: "rgba(92,184,92,0.75)",
		highlightStroke: "rgba(92,184,92,1)",
		data: [
	';
for ($i=1;$i<=12;$i++)
{
	if ($i>1) $javascript_onready.=',';
	if ($facturadoAnoAnterior[$i]=="") $facturadoAnoAnterior[$i] = 0;
	$javascript_onready.= $facturadoAnoAnterior[$i];
}
	$javascript_onready.=']
	},
	{
		label:"'.$lang["gastos"].' '.$ano.'",
		fillColor: "rgba(217,83,79,0.5)",
		strokeColor: "rgba(217,83,79,0.8)",
		highlightFill: "rgba(217,83,79,0.75)",
		highlightStroke: "rgba(217,83,79,1)",
		data: [
	';
for ($i=1;$i<=12;$i++)
{
	if ($i>1) $javascript_onready.=',';
	if ($facturadoAnoAnteriorGastos[$i]=="") $facturadoAnoAnteriorGastos[$i] = 0;
	$javascript_onready.= $facturadoAnoAnteriorGastos[$i];
}
$javascript_onready.=']
	}
	]
	};
	var ctx = document.getElementById("barChart2").getContext("2d");
	var myNewChart = new Chart(ctx).Bar(barData2, barOptions);
	';
//Datos para gráfico de gasolina
$javascript_onready.='var doughnutData1 = [{value:'.$facturasPrevistas.',color:"#5cb85c",highlight:"#1ab394",label:"'.$lang['facturadoPrevisto'].'"},{value:21000,color:"#d9534f",highlight:"#1ab394",label:"'.$lang['necesario3meses'].'"}];';
$javascript_onready.='var doughnutOptions = {
			segmentShowStroke: true,
			segmentStrokeColor: "#fff",
			segmentStrokeWidth: 2,
			percentageInnerCutout: 45, // This is 0 for Pie charts
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
			responsive: true,
		};
		var ctx = document.getElementById("doughnut1").getContext("2d");
		var myNewChart = new Chart(ctx).Doughnut(doughnutData1, doughnutOptions);';
//Datos para Gráfico Facturacion Actual
$javascript_onready.='var doughnutData2 = [{value:'.$facturasAnoActualTotal.',color:"#5cb85c",highlight:"#1ab394",label:"'.$lang["facturadoAnoActual"].'"},{value:'.$facturasPrevistas.',color:"#f0ad4e",highlight:"#1ab394",label:"'.$lang["facturadoPrevisto"].'"}];';
$javascript_onready.='var ctx = document.getElementById("doughnut2").getContext("2d");
		var myNewChart = new Chart(ctx).Doughnut(doughnutData2, doughnutOptions);';
print '<div class="row">
		<div class="col-lg-4">
			<div class="widget style1 navy-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-money fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">									
						<span> '.$lang['facturacionMediaMensual'].' '.$ano.'</span><h2 class="font-bold">'.number_format($facturasAnoActualTotal/($mes),2,',','.').'&euro;</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="widget style1 red-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-money fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">																		
						<span> '.$lang['gastosMediaMensual'].' '.$ano.'</span><h2 class="font-bold">'.number_format($facturasAnoActualTotalGastos/($mes),2,',','.').'&euro;</h2>									
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="widget style1 yellow-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-bar-chart fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">																		
						<span> '.$lang['balance'].' '.$ano.'</span><h2 class="font-bold">'.number_format(($facturasAnoActualTotal-$facturasAnoActualTotalGastos),2,',','.').'&euro;</h2>									
					</div>
				</div>
			</div>
		</div>
	</div>';
print '<div class="row">
		<div class="col-lg-4">
			<div class="widget style1 navy-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-money fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">									
						<span> '.$lang['facturacionMediaMensual'].' '.($ano-1).'</span><h2 class="font-bold">'.number_format($facturasAnoAnteriorTotal/12,2,',','.').'&euro;</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="widget style1 red-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-money fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">																		
						<span> '.$lang['gastosMediaMensual'].' '.($ano-1).'</span><h2 class="font-bold">'.number_format($facturasAnoAnteriorTotalGastos/12,2,',','.').'&euro;</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="widget style1 yellow-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-bar-chart fa-5x"></i>
					</div>
					<div class="col-xs-8 text-right">																		
						<span> '.$lang['balance'].' '.($ano-1).'</span><h2 class="font-bold">'.number_format(($facturasAnoAnteriorTotal-$facturasAnoAnteriorTotalGastos),2,',','.').'&euro;</h2>
					</div>
				</div>
			</div>
		</div>
	</div>';
?>
