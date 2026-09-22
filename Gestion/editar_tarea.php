<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$pagina = $_GET["pagina"];
$IdProyecto = $_GET["IdProyecto"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
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
$javascript_onready .= "
	tinyMCE.init({
		mode : 'exact',
		width : '100%',
		elements : 'Descripcion',";
if ($_SESSION['idioma'] == "ES-ES") $javascript_onready.="		language: 'es',";
$javascript_onready.="
		plugins: [
			'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
			'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
			'save table contextmenu directionality emoticons template paste textcolor'			
			],
		toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
		
	});";
$javascript_onready .= "\$(function() {
		\$( \"#FechaInicio\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
		\$( \"#FechaFin\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
$requete = "SELECT * FROM `Tareas` WHERE `Id`='".$Id."';";

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_tarea'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_tarea_2.php?pagina=".$pagina."\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input name=\"Id\" type=\"hidden\" value=\"".$Id."\">";
print "<input name=\"origen\" type=\"hidden\" value=\"".$_GET['origen']."\">";
if ($IdProyecto != "") print "<input name=\"IdProyecto\" type=\"hidden\" value=\"".$IdProyecto."\">";
if ($pagina != "") print "<input name=\"pagina\" type=\"hidden\" value=\"".$pagina."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Proyecto\">".$lang["proyecto"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Proyecto\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Proyectos` ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdProyecto) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombre"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Nombre\" type=\"text\" size=\"100\" maxlength=\"200\" value=\"".$listado->Nombre."\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Descripcion\">".$lang["descripcion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\">".$listado->Descripcion."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Asignado\">".$lang["AsignadoA"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Asignado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdUsuarioAsignado) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Responsable\">".$lang["responsable"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Responsable\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdResponsable) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"PorcentajeEjecucion\">".$lang["porcentaje"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"PorcentajeEjecucion\" type=\"text\" size=\"10\" maxlength=\"10\" value=\"".$listado->PorcentajeEjecucion."\"></div></div>";
// SELECCIÓN DE PLANTILLAS
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Tipo\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete2 = "SELECT * FROM `TareasTipos` WHERE `Idioma`='".$_SESSION['idioma']."';";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->Tipo==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"publico\">".$lang["publico"]."</label><div class='col-sm-4'><select class=\"form-control\" name=\"publico\">";
if ($listado->Publica=="si") print "<option value=\"si\" selected>".$lang["si"]."</option>";
else print "<option value=\"si\">".$lang["si"]."</option>";
if ($listado->Publica=="no") print "<option value=\"no\" selected>".$lang["no"]."</option>";
else print "<option value=\"no\">".$lang["no"]."</option>";
print "</select></div>";
print "<label class='col-sm-2 control-label' for=\"confirmar\">".$lang["confirmada"]."</label><div class='col-sm-4'><div class=\"i-checks\"><label><input";
if ($listado->Pendiente=="1") {}
else print " checked";
print " name=\"confirmar\" type=\"checkbox\" value=\"1\"><i></i> ".$lang["marcar_confirmada"]."</label></div></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaInicio\">".$lang["fechaComienzo"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaInicio\" id=\"FechaInicio\" type=\"text\" value=\"".$listado->Fecha."\" size=\"20\" maxlength=\"20\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaFin\">".$lang["fechaFin"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaFin\" id=\"FechaFin\" type=\"text\" value=\"".$listado->FechaFin."\" size=\"20\" maxlength=\"20\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionCreacion\">".$lang["notificacionCreacion"]."</label>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCreacion,"R")!==false) print " checked";
print " name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCreacion,"A")!==false) print " checked";
print " name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCreacion,"C")!==false) print " checked";
print " name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionModificacion\">".$lang["notificacionModificacion"]."</label>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionModificacion,"R")!==false) print " checked";
print " name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionModificacion,"A")!==false) print " checked";
print " name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionModificacion,"C")!==false) print " checked";
print " name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionCierre\">".$lang["notificacionCierre"]."</label>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCierre,"R")!==false) print " checked";
print " name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCierre,"A")!==false) print " checked";
print " name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionCierre,"C")!==false) print " checked";
print " name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionPasadoFecha\">".$lang["notificacionPasadoFecha"]."</label>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionPasadoFecha,"R")!==false) print " checked";
print " name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionPasadoFecha,"A")!==false) print " checked";
print " name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input";
if (strpos($listado->NotificacionPasadoFecha,"C")!==false) print " checked";
print " name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
print '<div class="hr-line-dashed"></div>';
if ($listado->FechaCierre!="") print "<input class=\"btn btn-primary\" type=\"submit\" name=\"ReAbrir\" value=\"".$lang["reabrirTarea"]."\">";
print '<div class="form-group">';
print "<div class='col-sm-6'><input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\"></div>";
print "<div class='col-sm-6'><input class=\"btn btn-danger\" name=\"eliminar_tarea\" type=\"submit\" value=\"".$lang["eliminar_tarea"]."\"></div>";
print '</div>';
print '</div></div></div></div>';
print "</form>";
?>