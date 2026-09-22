<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$origen = $_GET["origen"];
if ($IdPresupuesto!='')
{
	$requete = "SELECT * FROM `Presupuestos` WHERE `Id`=".$IdPresupuesto;
	
	// Listamos los representantes existentes
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$IdCliente = $listado->IdCliente;
	}
}
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
$javascript_onready .= "
		\$(function() {
			\$( \"#FechaPlanificada\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#FechaRealizada\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_accion_crm-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['tituloEditarAccionCRM'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_accion_crm_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
$requete = "SELECT * FROM `ClientesCRM` WHERE `Id`=".$Id;

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
if ($listado->IdPresupuesto!='') print "<input type='hidden' name='IdPresupuesto' value='".$listado->IdPresupuesto."'>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdCliente\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Clientes` WHERE `Proveedor` = 0 ORDER BY `DenominacionSocial`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->IdCliente==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["titulo"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Titulo\" type=\"text\" value=\"".$listado->Titulo."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Tipo\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
if ($listado->Tipo==1) print "<option value=\"1\" selected>".$lang["reunion"]."</option>";
else print "<option value=\"1\">".$lang["reunion"]."</option>";
if ($listado->Tipo==2) print "<option value=\"2\" selected>".$lang["cafe"]."</option>";
print "<option value=\"2\">".$lang["cafe"]."</option>";
if ($listado->Tipo==3) print "<option value=\"3\" selected>".$lang["llamada"]."</option>";
else print "<option value=\"3\">".$lang["llamada"]."</option>";
print "</select></div></div>";	
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Descripcion\">".$lang["descripcion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\">".$listado->Notas."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Asignado\">".$lang["asignado"]."</label><div class='col-sm-10'><select required class=\"form-control\" name=\"Asignado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option value=\"".$listado2->Id."\"";
		if ($listado->IdUsuario==$listado2->Id) print " selected";
		print ">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";	
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaPlanificada\">".$lang["fechaPlanificada"]."</label><div class='col-sm-10'><input required class=\"form-control\" name=\"FechaPlanificada\" id=\"FechaPlanificada\" type=\"text\" value=\"".$listado->FechaPlanificada."\" size=\"20\" maxlength=\"20\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaRealizada\">".$lang["fechaRealizada"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaRealizada\" id=\"FechaRealizada\" type=\"text\" value=\"".$listado->FechaRealizada."\" size=\"20\" maxlength=\"20\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print '<input type="hidden" name="Id" value="'.$Id.'">';
print '<input type="hidden" name="origen" value="'.$origen.'">';
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>