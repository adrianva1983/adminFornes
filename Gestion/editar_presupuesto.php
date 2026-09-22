<?php
//VERSIÓN: v1.0 2014-5-21
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
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
$requete = "SELECT * FROM `Presupuestos` WHERE `Id`='".$Id."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/editar_presupuesto-".$_SESSION['idioma'].".conf");
$javascript_onready .= "\$(function() {
			\$( \"#FechaEnvio\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#Fecha\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_presupuesto'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_presupuesto_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input type=\"hidden\" name=\"Id\" value=\"".$Id."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdRepresentante\">".$lang["representante"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdRepresentante\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `RepresentantePresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdRepresentante) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdFamilia\">".$lang["familia"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdFamilia\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `FamiliaPresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdFamilia) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fechaCreacion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Fecha\" id=\"Fecha\" type=\"text\" value=\"".$listado->Fecha."\" size=\"12\" maxlength=\"12\"></div></div>";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NombreEmpresa\">".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NombreEmpresa\" type=\"text\" value=\"".$listado->NombreEmpresa."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombreContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email\">".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Email\" type=\"text\" value=\"".$listado->Email."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"".$listado->Telefono."\" size=\"10\" maxlength=\"10\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloSolicitud\">".$lang["tituloSolicitud"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"TituloSolicitud\" type=\"text\" value=\"".$listado->TituloSolicitud."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Solicitud\">".$lang["solicitud"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Solicitud\" id=\"Solicitud\">".$listado->Solicitud."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloEnvio\">".$lang["tituloEnvio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"TituloEnvio\" type=\"text\" value=\"".$listado->TituloEnvio."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TextoEnvio\">".$lang["descripcionEnvio"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form-control\" rows=\"10\" cols=\"60\" name=\"TextoEnvio\" id=\"TextoEnvio\">".$listado->TextoEnvio."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaEnvio\">".$lang["fechaEnvio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaEnvio\" id=\"FechaEnvio\" type=\"text\" value=\"".$listado->FechaEnvio."\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEstado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEstado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos estados posibles sobre un presupuesto
$requete2 = "SELECT * FROM `PresupuestosEstados` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Titulo`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdEstado) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Titulo."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Notas\">".$lang["notas"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" rows=\"10\" cols=\"60\" name=\"Notas\" id=\"Notas\">".$listado->Notas."</textarea></div></div>";
echo"
<script>
 var par=false;
 function anadir_linea_presu()
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
print "\$('.lineasPresupuesto').append(codigo);";
echo "
 }
    </script>";

print "<table class=\"lineasPresupuesto table table-bordered table-stripped\"><thead>";
//Consultamos las líneas de presupuesto
$requete2 = "SELECT * FROM `PresupuestosLineas` WHERE `IdPresupuesto`=".$Id;

$par = true;
if ($result2 = mysqli_query($db, $requete2))
{
	print "<tr><th>".$lang["textoLinea"]."</th><th>".$lang["BaseImponible"]."</td><th>".$lang["impuestos"]."</th></tr></thead><tbody>";
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
		print "<td><input type=\"hidden\" name=\"IdLinea[]\" value=\"".$listado2->Id."\"><input name=\"TextoLinea[]\" type=\"text\" value=\"".$listado2->Texto."\" size=\"80\" maxlength=\"200\"></td>";
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
print "</tbody><tfoot><tr><td colspan=\"3\">";
print "<a href=\"#\" class=\"btn btn-primary pull-right\" onclick=\"anadir_linea_presu();return false;\"><i class=\"fa fa-plus\"></i> ".$lang["anadirLinea"]."</a>";
print "</td></tr></tfoot></table>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>