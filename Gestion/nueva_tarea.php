<?php
//VERSIÓN: v1.0 2014-3-31
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$IdProyecto = $_GET["IdProyecto"];

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
$javascript_onready .= "
		\$(function() {
			\$( \"#FechaInicio\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#FechaFin\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nueva_tarea-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nueva_tarea'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/nueva_tarea_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Proyecto\">".$lang["proyecto"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Proyecto\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Proyectos` WHERE `Activo` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($IdProyecto==$listado->Id) print "<option value=\"".$listado->Id."\" selected>".$listado->Nombre."</option>";
		else print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombre"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Nombre\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
if ($_GET['to_do']!=1) print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Descripcion\">".$lang["descripcion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\"></textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tiempo\">".$lang["tiempo_dedicado"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Tiempo\" type=\"text\" value=\"0.5\" size=\"100\" maxlength=\"200\"></div></div>";
if ($_GET['to_do']!=1) 
{
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Asignado\">".$lang["AsignadoA"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Asignado\">";	
	print "<option value=\"\">".$lang["sinValor"]."</option>";
	$requete = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";
	
	// Listamos los representantes existentes
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
		}
	}
	print "</select></div></div>";
}
else print '<input type="hidden" name="Asignado" value="'.$_SESSION['usuario_id'].'"/>';
if ($_GET['to_do']!=1) 
{
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Responsable\">".$lang["responsable"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Responsable\">";
	print "<option value=\"\">".$lang["sinValor"]."</option>";
	$requete = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";
	
	// Listamos los representantes existentes
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Id."\">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
		}
	}
	print "</select></div></div>";	
}
else print '<input type="hidden" name="Responsable" value="'.$_SESSION['usuario_id'].'"/>';
if ($_GET['to_do']!=1) 
{
	// SELECCIÓN DE TIPO DE TAREA
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Tipo\">";
	// Hacemos una consulta para ver las distintas plantillas a aplicar
	$requete = "SELECT * FROM `TareasTipos` WHERE `Idioma`='".$_SESSION['idioma']."';";
	
	// Listamos las plantillas existentes
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
		}
	}
	print "</select></div></div>";	
}
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"publico\">".$lang["publico"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"publico\">";
print "<option value=\"si\">".$lang["si"]."</option>";
print "<option value=\"no\">".$lang["no"]."</option>";
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaInicio\">".$lang["fechaComienzo"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaInicio\" id=\"FechaInicio\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaFin\">".$lang["fechaFin"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaFin\" id=\"FechaFin\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"></div></div>";
if ($_GET['to_do']!=1) 
{
	print '<div class="hr-line-dashed"></div>';
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionCreacion\">".$lang["notificacionCreacion"]."</label>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCreacion[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
	print '<div class="hr-line-dashed"></div>';
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionModificacion\">".$lang["notificacionModificacion"]."</label>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionModificacion[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
	print '<div class="hr-line-dashed"></div>';
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionCierre\">".$lang["notificacionCierre"]."</label>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionCierre[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
	print '<div class="hr-line-dashed"></div>';
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NotificacionPasadoFecha\">".$lang["notificacionPasadoFecha"]."</label>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"R\"><i></i> ".$lang["responsable"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"A\"><i></i> ".$lang["asignado"]."</label></div></div>";
	print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"NotificacionPasadoFecha[]\" type=\"checkbox\" value=\"C\"><i></i> ".$lang["cliente"]."</label></div></div></div>";
}
print '<div class="hr-line-dashed"></div>';
if ($_GET['to_do']==1) print '<input type="hidden" name="ToDo" value="1"/>';
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>