<?php
//VERSIÓN: v1.0 2014-01-17
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$grupo = $_GET["grupo"];
$referencia = $_GET["referencia"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/nuevo_usuario-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO RESPONSABLE
$nivel_acceso=3; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
$javascript_onready .= " \$(function() {
		\$( \"#FechaNacimiento\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
		\$( \"#FechaCaducidad\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang["crearUsuario"].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"../Usuarios/nuevo_usuario_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
if ($grupo!="") print "<input name=\"grupo\" type=\"hidden\" value=\"".$grupo."\">";
if ($referencia!="") print "<input name=\"referencia\" type=\"hidden\" value=\"".$referencia."\">";
print "<input name=\"IdUsuario\" type=\"hidden\" value=\"".$_SESSION['usuario_id']."\">";
print "<h2>".$lang["personales"]."</h2>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Nick'>".$lang["nick"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nick\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Foto'>".$lang["foto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Foto\" type=\"file\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Nombre'>".$lang["nombre"]."</label><div class='col-sm-10'><input required class=\"form-control\" name=\"Nombre\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Apellidos'>".$lang["apellidos"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Apellidos\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Direccion'>".$lang["direccion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Direccion\" type=\"text\" value=\"\" size=\"40\" maxlength=\"120\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Ciudad'>".$lang["ciudad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Ciudad\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Municipio'>".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Municipio\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='CP'>".$lang["cp"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CP\" type=\"text\" value=\"\" size=\"9\" maxlength=\"9\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Provincia'>".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Provincia\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Pais'>".$lang["pais"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Pais\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Idioma'>".$lang["idioma"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Idioma\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Email'>".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Email\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Telefono'>".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Movil'>".$lang["movil"]."</label><div class='col-sm-10'><input name=\"Movil\" class=\"form-control\" type=\"text\" value=\"\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='FechaNacimiento'>".$lang["fechaNacimiento"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaNacimiento\" type=\"text\" value=\"\" id=\"FechaNacimiento\" size=\"10\" maxlength=\"10\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["configuracion"]."</h2>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Password'>".$lang["password"]."</label><div class='col-sm-10'><input name=\"Password\" class=\"form-control\" type=\"password\" value=\"\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='NivelAcceso'>".$lang["nivel"]."</label><div class='col-sm-10'>";	
$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
$result = mysqli_query($db,$requete);
print "<select name=\"NivelAcceso\" class=\"form-control\">";
print "<option value=\"\" SELECTED></option>";
while($listado = mysqli_fetch_object($result))
{
	if ($listado->Nivel>$_SESSION['usuario_nivel'])
	{
		print "<option value=\"".$listado->Nivel."\">".$listado->Nombre."</option>";
	}
}
print "</select>";
print "<input type=\"hidden\" name=\"referencia\" value=\"".$referencia."\">";
print "</div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='RedirigirLogin'>".$lang["redirigirLogin"]."</label><div class='col-sm-10'>";
print "<select name=\"RedirigirLogin\" class=\"form-control\">";
$requete = "SELECT * FROM `Secciones` WHERE `Visibilidad`='privado'";
$result2 = mysqli_query($db,$requete);
if ($result2 = mysqli_query($db, $requete2))
{
	print "<option value=\"\"></option>";
	while($listado2 = mysqli_fetch_object($result2))
	{
		$ruta = "/Secciones";
		if ((substr($listado2->Path,0,1)!="/")&&$listado2->Path!="") $ruta.="/".$listado2->Path;
		else $ruta.=substr($listado2->Path,1);
		$ruta.="/".$listado2->NomFich;
		print "<option value=\"".$ruta."\">".$listado2->Titulo." (".$listado2->Idioma.")</option>";
	}
}
print "</select>";
print "</div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='FechaCaducidad'>".$lang["caducidad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaCaducidad\" id=\"FechaCaducidad\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Activado'>".$lang['estado']."</label><div class='col-sm-10'>";
print "<div class=\"i-checks\"><label><input name=\"Activado\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["activado"]."</label></div></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label'>".$lang['comunicaciones']."</label><div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"AltaBoletin\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["boletin"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"AltaSMS\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["SMS"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"ExclusivoMailing\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["exclusivo"]."</label></div></div><div class='col-sm-1'></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label'>".$lang['gestiones']."</label><div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"TieneTareas\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["tiene_tareas"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"RepresentantePresupuestos\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["representante_presupuestos"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"FamiliaPresupuestos\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["familia_presupuestos"]."</label></div></div><div class='col-sm-1'></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["empresariales"]."</h2>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='NombreEmpresa'>".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NombreEmpresa\" type=\"text\" value=\"\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='CIF'>".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CIF\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"/></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>