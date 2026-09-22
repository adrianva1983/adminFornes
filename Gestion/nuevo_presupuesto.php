<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdCliente = $_GET["IdCliente"];
$origen = $_GET["origen"];
$pagina = $_GET["pagina"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_presupuesto-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO CORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
$javascript_onready .= "\$(function() {
		\$( \"#FechaEnvio\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#Fecha\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nuevo_presupuesto'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/nuevo_presupuesto_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input type=\"hidden\" name=\"origen\" value=\"".$origen."\">";
print "<input type=\"hidden\" name=\"pagina\" value=\"".$pagina."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdRepresentante\">".$lang["representante"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdRepresentante\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Usuarios` WHERE `RepresentantePresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdFamilia\">".$lang["familia"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdFamilia\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Usuarios` WHERE `FamiliaPresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha\">".$lang["fechaCreacion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Fecha\" type=\"text\" value=\"".date("Y-m-d")."\" size=\"12\" maxlength=\"12\" id=\"Fecha\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdCliente\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete = "SELECT * FROM `Clientes` ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($IdCliente==$listado->Id) print "<option value=\"".$listado->Id."\" selected>".$listado->DenominacionSocial."</option>";
		else print "<option value=\"".$listado->Id."\">".$listado->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NombreEmpresa\">".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NombreEmpresa\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombreContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nombre\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email\">".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Email\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloSolicitud\">".$lang["tituloSolicitud"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"TituloSolicitud\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Solicitud\">".$lang["solicitud"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Solicitud\" id=\"Solicitud\"></textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TituloEnvio\">".$lang["tituloEnvio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"TituloEnvio\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"TextoEnvio\">".$lang["descripcionEnvio"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"TextoEnvio\" id=\"TextoEnvio\"></textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaEnvio\">".$lang["fechaEnvio"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"FechaEnvio\" name=\"FechaEnvio\" id=\"FechaEnvio\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdEstado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdEstado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos estados posibles sobre un presupuesto
$requete = "SELECT * FROM `PresupuestosEstados` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Titulo`;";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Notas\">".$lang["notas"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Notas\" id=\"Notas\"></textarea></div></div>";
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
	codigo = codigo + '<td><input name=\"Texto[]\" type=\"text\" value=\"\" size=\"80\" maxlength=\"200\"></td>';
	codigo = codigo + '<td><input name=\"BaseImponible[]\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></td>';
	codigo = codigo + '<td><select name=\"IVA[]\">';
	";
$requete = "SELECT * FROM `PresupuestosImpuestos` ORDER BY `Valor` DESC;";

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
print "<tr><th>".$lang["textoLinea"]."</th><th>".$lang["BaseImponible"]."</th><th>".$lang["impuestos"]."</th></tr></thead><tbody>";
print "<tr class=\"par\">";
print "<td><input name=\"Texto[]\" type=\"text\" value=\"\" size=\"80\" maxlength=\"200\"></td>";
print "<td><input name=\"BaseImponible[]\" type=\"text\" value=\"\" size=\"12\" maxlength=\"12\"></td>";
print "<td><select name=\"IVA[]\">";
// Hacemos una consulta para ver los distintos tipos de impuestos
$requete = "SELECT * FROM `PresupuestosImpuestos` ORDER BY `Valor` DESC;";

// Listamos los impuestos disponibles
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Valor."\">".$listado->Titulo."</option>";
	}
}
print "</select></td>";
print "</tr></tbody>";
print "<tfoot><tr><td colspan=\"3\"><a href=\"#\" class=\"btn btn-primary pull-right\" onclick=\"anadir_linea_presu();return false;\"><i class=\"fa fa-plus\"></i> ".$lang["anadirLinea"]."</a></td></tr></tfoot>";
print "</table>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>