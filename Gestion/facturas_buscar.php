<?php
//VERSIÓN: v1.0 2014-7-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/facturas_buscar-".$_SESSION['idioma'].".conf");
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
$javascript_onready .= "\$(function() {
		\$( \"#Fecha1\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#Fecha2\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_facturas_buscar'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
echo "<form name=\"ubusca\" action=\"/administra/Interface/herramienta.php\" enctype=\"multipart/form-data\" method=\"GET\" class=\"form-horizontal\">";
echo "<input type=\"hidden\" name=\"modulo\" value=\"Gestion\">";
echo "<input type=\"hidden\" name=\"herramienta\" value=\"facturas\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdFactura\">Id</label><div class='col-sm-10'><input class=\"form-control\" name=\"IdFactura\" value=\"\"></div></div>";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Fecha1\">".$lang["fecha1"]."</label><div class='col-sm-4'><input class=\"form-control\" type=\"text\" name=\"Fecha1\" id=\"Fecha1\"/></div>";
print "<label class='col-sm-2 control-label' for=\"Fecha2\">".$lang["fecha2"].":</label><div class='col-sm-4'><input class=\"form-control\" type=\"text\" name=\"Fecha2\" id=\"Fecha2\"/></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>