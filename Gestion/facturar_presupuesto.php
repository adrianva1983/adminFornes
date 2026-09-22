<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
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
$requete = "SELECT * FROM `Presupuestos` WHERE `Id`='".$Id."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/facturar_presupuesto-".$_SESSION['idioma'].".conf");
$javascript_onready .= "\$(function() {
		\$( \"#Vencimiento\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$(\"#Fecha\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_facturar_presupuesto'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/facturar_presupuesto_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input type=\"hidden\" name=\"IdPresupuesto\" value=\"".$Id."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEmpresa\">".$lang["empresa"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEmpresa\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `FacturasEmpresas` ORDER BY `NombreComercial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{		
		print "<option value=\"".$listado2->Id."\">".$listado2->NombreComercial." (".$listado2->DenominacionSocial." ".$listado2->CIF.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NumeroFactura\">".$lang["numeroFactura"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NumeroFactura\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"SerieFactura\">".$lang["serieFactura"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"SerieFactura\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Titulo\" type=\"text\" value=\"".$listado->TituloSolicitud."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fecha"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Fecha\" id=\"Fecha\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Vencimiento\">".$lang["vencimiento"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Vencimiento\" id=\"Vencimiento\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdPeriodicidad\">".$lang["periodicidad"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdPeriodicidad\">";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdCliente\">";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEstado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEstado\">";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FormaDePago\">".$lang["formaPago"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"FormaDePago\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
print "<option value=\"1\">".$lang["transferencia"]."</option>";
print "<option value=\"2\">".$lang["reciboDomiciliado"]."</option>";
print "</select></div></div>";
echo"
<script>
 var par=false;
 function excluir_linea(elemento)
 {	
	padre = elemento.parent();
	abuelo = padre.parent();
	abuelo.animate({'opacity':0},500,function(){
		$(this).remove();
	});
 }
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
	codigo = codigo + '<td><div class=\"i-checks\"><label><input name=\"Incluir[]\" type=\"checkbox\" value=\"si\"><i></i></label></div></td>';
	codigo = codigo + '<td><input name=\"Porcentaje[]\" type=\"text\" value=\"100\" size=\"3\" maxlength=\"3\"></td>';	
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
//Consultamos las líneas de presupuesto
$requete2 = "SELECT * FROM `PresupuestosLineas` WHERE `IdPresupuesto`=".$Id;

$par = true;
print "<tr><th>&nbsp;</th><th>".$lang["porcentaje"]."</th><th>".$lang["textoLinea"]."</th><th>".$lang["BaseImponible"]."</td><th>".$lang["impuestos"]."</th></tr></thead></tbody>";
if ($result2 = mysqli_query($db, $requete2))
{	
	while($listado2 = mysqli_fetch_object($result2))
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
		print "<td><a href=\"#\" onclick=\"excluir_linea($(this));return false;\"><i class=\"fa fa-remove\"></i></a></td>";
		print "<td><input name=\"Porcentaje[]\" type=\"text\" value=\"100\" size=\"3\" maxlength=\"3\"></td>";
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
}
print "</tbody><tfoot><tr><td colspan=\"5\">";
print "<a href=\"#\" class=\"btn btn-primary pull-right\" onclick=\"anadir_linea_factu();return false;\"><i class=\"fa fa-plus\"></i> ".$lang["anadirLinea"]."</a>";
print "</td></tr></tfoot></table>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>