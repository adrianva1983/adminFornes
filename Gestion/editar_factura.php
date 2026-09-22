<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$pagina = $_GET["pagina"];
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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_factura-".$_SESSION['idioma'].".conf");
$javascript_onready .= "\$(function() {
			\$( \"#Vencimiento\" ).datepicker({format: 'yyyy-mm-dd'});
			});
			\$(function() {
				\$( \"#Fecha\" ).datepicker({format: 'yyyy-mm-dd'});
			});
			\$(function() {
				\$( \"#FechaSaldada\" ).datepicker({format: 'yyyy-mm-dd'});
			});
			function tipoFacturaGasto(tipo){
				if (tipo.value=='1'){
					\$('.campo_ingreso').fadeOut();
					\$('.campo_gasto').fadeIn();
				}
				else{
					\$('.campo_ingreso').fadeIn();
					\$('.campo_gasto').fadeOut();
				}
			}";
$requete = "SELECT * FROM `Facturas` WHERE `Id`='".$Id."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
if ($listado->FacturaGasto==1) print "<style>.campo_ingreso{display:none;}</style>";
else print "<style>.campo_gasto{display:none;}</style>";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_factura'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_factura_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input type=\"hidden\" name=\"Id\" value=\"".$Id."\">";
print "<input type=\"hidden\" name=\"pagina\" value=\"".$pagina."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEmpresa\">".$lang["empresa"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEmpresa\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `FacturasEmpresas` ORDER BY `NombreComercial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{		
		if ($listado->IdEmpresa==$listado2->IdEmpresa) print "<option value=\"".$listado2->IdEmpresa."\" selected>".$listado2->NombreComercial." (".$listado2->DenominacionSocial." ".$listado2->CIF.")</option>";
		else print "<option value=\"".$listado2->IdEmpresa."\">".$listado2->NombreComercial." (".$listado2->DenominacionSocial." ".$listado2->CIF.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"SerieFactura\">".$lang["serieFactura"]."</label>";
print "<div class='col-sm-10'><input class=\"form-control\" name=\"SerieFactura\" type=\"text\" value=\"".$listado->SerieFactura."\" size=\"12\" maxlength=\"12\"></div></div>";
//Miramos cual es la última factura
$requete2 = "SELECT * FROM `Facturas` ORDER BY `Fecha` DESC;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	$listado2 = mysqli_fetch_object($result2);
	$ultima_factura = $listado2->NumeroFactura;
}
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"NumeroFactura\">".$lang["numeroFactura"]."</label><div class='col-sm-10'>";
if ($ultima_factura!="") print " (".$lang["ultimaFactura"].". ".$ultima_factura.")";
print "<input class=\"form-control\" name=\"NumeroFactura\" type=\"text\" value=\"".$listado->NumeroFactura."\" size=\"12\" maxlength=\"12\"></div></div>";
if ($listado->IdSerie!="") print "<div class='form-group'><label class='col-sm-2 control-label' for='IdSerie'>".$lang["serie"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"IdSerie\" type=\"text\" value=\"".$listado->IdSerie."\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group campo_gasto'><label class='col-sm-2 control-label' for=\"CodigoFacturaGasto\">".$lang["numeroFactura"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CodigoFacturaGasto\" value=\"".$listado->CodigoFacturaGasto."\" type=\"text\" size=\"20\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fecha"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Fecha\" id=\"Fecha\" type=\"text\" value=\"".$listado->Fecha."\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Vencimiento\">".$lang["vencimiento"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Vencimiento\" id=\"Vencimiento\" type=\"text\" value=\"".$listado->Vencimiento."\" size=\"12\" maxlength=\"12\"></div></div>";
if ($listado->IdSerie=="")
{
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdPeriodicidad\">".$lang["periodicidad"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdPeriodicidad\">";
	print "<option value=\"\">".$lang["sinValor"]."</option>";
	$requete2 = "SELECT * FROM `FacturasPeriodicidades` WHERE `Idioma` = '".$_SESSION['idioma']."';";
	
	// Listamos los representantes existentes
	if ($result2 = mysqli_query($db, $requete2))
	{
		while($listado2 = mysqli_fetch_object($result2))
		{
			if ($listado->IdPeriodicidad==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Cantidad." ".$listado2->UnidadCadencia."</option>";
			else print "<option value=\"".$listado2->Id."\">".$listado2->Cantidad." ".$listado2->UnidadCadencia."</option>";
		}
	}
	print "</select></div></div>";	
}
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdCliente\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `Clientes` ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdCliente) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_gasto'><label class='col-sm-2 control-label' for=\"IdProveedor\">".$lang["proveedor"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdProveedor\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `Clientes` WHERE `Proveedor`=1 ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdProveedor) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"IdEstado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEstado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos estados posibles sobre un presupuesto
$requete2 = "SELECT * FROM `FacturasEstados` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Titulo`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->IdEstado==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Titulo."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Saldada\">".$lang["saldada"]."</label><div class='col-sm-4'><select class=\"form-control\" name=\"Saldada\">";
print "<option value=\"0\">".$lang["no"]."</option>";
if (isset($listado->Saldada)&&($listado->Saldada==1)) 
{
	print "<option value=\"1\" selected>".$lang["si"]."</option>";
}
else print "<option value=\"1\">".$lang["si"]."</option>";
print "</select></div>";
print "<label class='col-sm-2 control-label' for=\"Saldada\">".$lang["fechaSaldada"]."</label><div class='col-sm-4'><input class=\"form-control\" type=\"text\" name=\"FechaSaldada\" id=\"FechaSaldada\">";
$requete2 = "SELECT * FROM `Contabilidad` WHERE `IdFactura`=".$listado->Id;

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print $listado2->Importe." (".$listado2->Fecha.")<br/>";
	}
}
print "</div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"SaldadaComision\">".$lang["saldadaComision"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"SaldadaComision\">";
print "<option value=\"0\">".$lang["no"]."</option>";
if (isset($listado->SaldadaComision)&&($listado->SaldadaComision==1)) print "<option value=\"1\" selected>".$lang["si"]."</option>";
else print "<option value=\"1\">".$lang["si"]."</option>";
print "</select>";
print "</div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Rectificativa\">".$lang["rectificativa"]."</label><div class='col-sm-4'><select class=\"form-control\" name=\"Rectificativa\">";
print "<option value=\"0\">".$lang["no"]."</option>";
if (isset($listado->Rectificativa)&&($listado->Rectificativa==1)) print "<option value=\"1\" selected>".$lang["si"]."</option>";
else print "<option value=\"1\">".$lang["si"]."</option>";
print "</select></div>";
print "<label class='col-sm-2 control-label' for=\"IdFacturaRelacionada\">".$lang["relacionada"]."</label><div class='col-sm-4'><input class=\"form-control\" name=\"IdFacturaRelacionada\" type=\"text\" value=\"".$listado->IdFacturaRelacionada."\" size=\"12\" maxlength=\"12\">";
print "</div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FormaDePago\">".$lang["formaPago"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"FormaDePago\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
print "<option value=\"1\"";
if ($listado->FormaDePago==1) print " selected ";
print ">".$lang["transferencia"]."</option>";
print "<option value=\"2\"";
if ($listado->FormaDePago==2) print " selected ";
print ">".$lang["reciboDomiciliado"]."</option>";
print "</select></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>