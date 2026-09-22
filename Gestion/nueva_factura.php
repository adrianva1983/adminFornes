<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdSerie = $_GET["IdSerie"];

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_factura-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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
if ($IdSerie!="")
{
	$requete_serie = "SELECT * FROM `Facturas` WHERE `Id`=".$IdSerie;
	if ($result_serie = mysqli_query($db,$requete_serie))	
	{
		$listado_serie = mysqli_fetch_object($result_serie);
		$requete_serie2 = "SELECT * FROM `Facturas` WHERE `IdSerie`=".$IdSerie." ORDER BY `Id` DESC";
		$result_serie2 = mysqli_query($db,$requete_serie2);
		if (mysqli_num_rows($result_serie2)>0)
		{
			$listado_serie = mysqli_fetch_object($result_serie2);//Reemplazo con la última factura de la serie
		}
	}
}
$javascript_onready .= "
		\$(function() {
			\$( \"#Vencimiento\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#Fecha\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
if ($IdSerie!=""&&$listado_serie->FacturaGasto==1) print "<style>.campo_ingreso{display:none;}</style>";
else print "<style>.campo_gasto{display:none;}</style>";
print "<script>function tipoFacturaGasto(tipo){
			if (tipo.value=='1'){
				\$('.campo_ingreso').fadeOut();
				\$('.campo_gasto').fadeIn();
			}
			else{
				\$('.campo_ingreso').fadeIn();
				\$('.campo_gasto').fadeOut();
			}
		}</script>";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nueva_factura'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/nueva_factura_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FacturaGasto\">".$lang["tipoFacturaGasto"]."</label><div class='col-sm-10'><select class=\"form-control\" id='FacturaGasto' name='FacturaGasto' onchange=\"tipoFacturaGasto(this);return false;\">";
	print "<option value='0'";
	if ($IdSerie!="" && $listado_serie->FacturaGasto!="") print " selected";
	print ">".$lang['facturaIngreso']."</option><option value='1'";
	if ($IdSerie!="" && $listado_serie->FacturaGasto==1) print " selected";
	print ">".$lang['facturaGasto']."</option></select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEmpresa\">".$lang["empresa"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEmpresa\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `FacturasEmpresas` ORDER BY `NombreComercial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option value=\"".$listado2->IdEmpresa."\"";
		if ($IdSerie!=""&&$listado_serie->IdEmpresa==$listado2->IdEmpresa) print " selected";
		print ">".$listado2->NombreComercial." (".$listado2->DenominacionSocial." ".$listado2->CIF.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"SerieFactura\">".$lang["serieFactura"]."</label>";
print "<div class='col-sm-10'><input class=\"form-control\" name=\"SerieFactura\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
//Miramos cual es la última factura
$requete2 = "SELECT * FROM `Facturas` ORDER BY `NumeroFactura` DESC;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	$listado2 = mysqli_fetch_object($result2);
	$ultima_factura = $listado2->NumeroFactura;
}
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"NumeroFactura\">".$lang["numeroFactura"]."</label>";
if ($ultima_factura!="") print " (".$lang["ultimaFactura"].". ".$ultima_factura.")";
print "<div class='col-sm-10'><input class=\"form-control\" name=\"NumeroFactura\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
if ($IdSerie!="") print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"IdSerie\">".$lang["serie"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"IdSerie\" type=\"text\" value=\"".$IdSerie."\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group campo_gasto'><label class='col-sm-2 control-label' for=\"CodigoFacturaGasto\">".$lang["numeroFactura"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CodigoFacturaGasto\" type=\"text\" size=\"20\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Titulo\" type=\"text\" value=\"";
if ($IdSerie!="") print $listado_serie->Titulo;
print "\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fecha"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Fecha\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\" id=\"Fecha\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Vencimiento\">".$lang["vencimiento"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Vencimiento\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\" id=\"Vencimiento\"></div></div>";
if ($IdSerie=="")
{
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdPeriodicidad\">".$lang["periodicidad"].":</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdPeriodicidad\">";
	print "<option value=\"\">".$lang["sinValor"]."</option>";
	$requete2 = "SELECT * FROM `FacturasPeriodicidades` WHERE `Idioma` = '".$_SESSION['idioma']."';";
	
	// Listamos los representantes existentes
	if ($result2 = mysqli_query($db, $requete2))
	{
		while($listado2 = mysqli_fetch_object($result2))
		{		
			print "<option value=\"".$listado2->Id."\">".$listado2->Cantidad." ".$listado2->UnidadCadencia."</option>";
		}
	}
	print "</select></div></div>";	
}
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"].":</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdCliente\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `Clientes` WHERE `Proveedor`=0 OR `Proveedor` IS NULL ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado_serie->IdCliente) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_gasto'><label class='col-sm-2 control-label' for=\"IdProveedor\">".$lang["proveedor"].":</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdProveedor\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `Clientes` WHERE `Proveedor`=1 ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado_serie->IdProveedor) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"IdEstado\">".$lang["estado"].":</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEstado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos estados posibles sobre un presupuesto
$requete2 = "SELECT * FROM `FacturasEstados` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Titulo`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{		
		print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FormaDePago\">".$lang["formaPago"].":</label><div class='col-sm-10'><select class=\"form-control\" name=\"FormaDePago\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
print "<option value=\"1\"";
if ($IdSerie!=""&&$listado_serie->FormaDePago==1) print " selected";
print ">".$lang["transferencia"]."</option>";
print "<option value=\"2\"";
if ($IdSerie!=""&&$listado_serie->FormaDePago==2) print " selected";
print ">".$lang["reciboDomiciliado"]."</option>";
print "</select></div></div>";
echo"
<script>
 var par=false;
 function anadir_linea_factu()
 {
	var codigo = \"\";
	if (par) 
	{
		codigo = codigo + '<tr class=\"par\">';
		par = false;
	}
	else 
	{
		codigo = codigo + '<tr>';
		par = true;
	}
	codigo = codigo + '<td><input name=\"TextoLinea[]\" type=\"text\" value=\"\" size=\"80\" maxlength=\"200\"></td>';
	codigo = codigo + '<td><input name=\"BaseImponible[]\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></td>';
	codigo = codigo + '<td><select name=\"IVA[]\">';
	codigo = codigo + '<option value=\"\">";
print $lang["sinValor"];
print "</option>';";	
$requete = "SELECT * FROM `PresupuestosImpuestos` ORDER BY `Valor`;";

// Listamos los impuestos disponibles
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "codigo = codigo + '<option value=\"".$listado->Valor."\">".$listado->Titulo."</option>';";
	}
}
print "codigo = codigo + '</select></td>';";
print "codigo = codigo + '</tr>';";		
print "\$('.lineasFactura').append(codigo);";
echo "
 }
    </script>";

print "<table class=\"lineasFactura table table-bordered table-stripped\"><thead>";
$par = true;
print "<tr><th>".$lang["textoLinea"]."</th><th>".$lang["BaseImponible"]."</td><th>".$lang["impuestos"]."</th></tr></thead><tbody>";
if ($IdSerie!="")
{
	$requete_lineas = "SELECT * FROM `FacturasLineas` WHERE `IdFactura`=".$listado_serie->Id;
	$result_lineas = mysqli_query($db,$requete_lineas);
	if (mysqli_num_rows($result_lineas)>0)
	{
		$par = false;
		while($listado_lineas = mysqli_fetch_object($result_lineas))
		{
			print "<tr";
			if ($par) 
			{
				print " class=\"par\"";	
				$par = false;
			}
			else $par = true;
			print ">";
			print "<td><input name=\"TextoLinea[]\" type=\"text\" value=\"".$listado_lineas->Texto."\" size=\"80\" maxlength=\"200\"></td>";
			print "<td><input name=\"BaseImponible[]\" type=\"text\" value=\"".$listado_lineas->BaseImponible."\" size=\"12\" maxlength=\"12\"></td>";
			print "<td><select name=\"IVA[]\">";
			print "<option value=\"\">".$lang["sinValor"]."</option>";
			// Hacemos una consulta para ver los distintos tipos de impuestos
			$requete3 = "SELECT * FROM `PresupuestosImpuestos` ORDER BY `Valor`;";			
			// Listamos los impuestos disponibles
			if ($result3 = mysqli_query($db, $requete3))
			{
				while($listado3 = mysqli_fetch_object($result3))
				{
					if ($listado_lineas->Impuesto==$listado3->Valor) print "<option selected value=\"".$listado3->Valor."\">".$listado3->Titulo."</option>";
					else print "<option value=\"".$listado3->Valor."\">".$listado3->Titulo."</option>";
				}
			}
			print "</select></td>";
			print "</tr>";
		}
	}	
}
if ($IdSerie=="")
{
	print "<tr class=\"par\">";
	print "<td><input name=\"TextoLinea[]\" type=\"text\" value=\"".$listado2->Texto."\" size=\"80\" maxlength=\"200\"></td>";
	print "<td><input name=\"BaseImponible[]\" type=\"text\" value=\"".$listado2->BaseImponible."\" size=\"12\" maxlength=\"12\"></td>";
	print "<td><select name=\"IVA[]\">";
	print "<option value=\"\">".$lang["sinValor"]."</option>";
	// Hacemos una consulta para ver los distintos tipos de impuestos
	$requete3 = "SELECT * FROM `PresupuestosImpuestos` ORDER BY `Valor`;";	
	// Listamos los impuestos disponibles
	if ($result3 = mysqli_query($db, $requete3))
	{
		while($listado3 = mysqli_fetch_object($result3))
		{
			if ($listado2->Impuesto==$listado3->Valor) print "<option selected value=\"".$listado3->Valor."\">".$listado3->Titulo."</option>";
			else print "<option value=\"".$listado3->Valor."\">".$listado3->Titulo."</option>";
		}
	}
	print "</select></td>";
	print "</tr>";	
}
print "</tbody>";
print "<tfoot><tr><td colspan=\"3\">";
print "<a href=\"#\" class=\"btn btn-primary pull-right\" onclick=\"anadir_linea_factu();return false;\"><i class=\"fa fa-plus\"></i> ".$lang["anadirLinea"]."</a>";
print "</td></tr></tfoot></table>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>