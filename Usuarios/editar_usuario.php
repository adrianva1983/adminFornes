<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$origen = $_GET["origen"];
$Num_Pagina = $_GET["Num_Pagina"];
$grupo = $_GET["grupo"];
$CamposMostrar = $_GET["CamposMostrar"];
$pagina = $_GET["pagina"];
$usuario = $_GET["usuario"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/nuevo_usuario-".$_SESSION['idioma'].".conf");

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
			\$( \"#FechaNacimiento\" ).datepicker({format: 'yyyy-mm-dd'});
			});
			\$(function() {
			\$( \"#FechaCaducidad\" ).datepicker({format: 'yyyy-mm-dd'});
			});";
$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang["editarUsuario"].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Usuarios/editar_usuario_2.php?origen=".$origen."&Num_Pagina=".$Num_Pagina."&grupo=".$grupo."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
$requete = "SELECT * FROM `Usuarios` WHERE `Id`='".$usuario."'";
$result = mysqli_query($db,$requete);
$listado = mysqli_fetch_object($result);
print "<h2>".$lang["personales"]."</h2>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Nick'>".$lang["nick"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nick\" type=\"text\" value=\"".$listado->Nick."\" size=\"40\" maxlength=\"40\"/></div></div>";
if ($listado->Foto!="") 
{
	print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='BorrarImagen'>".$lang["borrarImagen"]."</label><div class='col-sm-10'>";
	print "<img src=\"/Imagenes/Perfiles/".$listado->Foto."\" height=\"60px\">";
	print "<div class=\"i-checks\"><label><input id=\"BorrarImagen\" name=\"BorrarImagen\" type=\"checkbox\" value=\"1\"><i></i> </label></div></div></div>";
}
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Foto'>".$lang["imagenEditar"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Foto\" type=\"file\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Nombre'>".$lang["nombre"]."</label><div class='col-sm-10'><input required class=\"form-control\" name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Apellidos'>".$lang["apellidos"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Apellidos\" type=\"text\" value=\"".$listado->Apellidos."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Direccion'>".$lang["direccion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Direccion\" type=\"text\" value=\"".$listado->Direccion."\" size=\"40\" maxlength=\"120\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Ciudad'>".$lang["ciudad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Ciudad\" type=\"text\" value=\"".$listado->Ciudad."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Municipio'>".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Municipio\" type=\"text\" value=\"".$listado->Municipio."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='CP'>".$lang["cp"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CP\" type=\"text\" value=\"".$listado->CP."\" size=\"9\" maxlength=\"9\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Provincia'>".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Provincia\" type=\"text\" value=\"".$listado->Provincia."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Pais'>".$lang["pais"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Pais\" type=\"text\" value=\"".$listado->Pais."\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Idioma'>".$lang["idioma"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Idioma\" type=\"text\" value=\"".$listado->Idioma."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Email'>".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" required name=\"Email\" type=\"text\" value=\"".$listado->Email."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Telefono'>".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"".$listado->Telefono."\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Movil'>".$lang["movil"]."</label><div class='col-sm-10'><input name=\"Movil\" class=\"form-control\" type=\"text\" value=\"".$listado->Movil."\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='FechaNacimiento'>".$lang["fechaNacimiento"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaNacimiento\" type=\"text\" value=\"".$listado->FechaNacimiento."\" id=\"FechaNacimiento\" size=\"10\" maxlength=\"10\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Password'>".$lang["password"]."</label><div class='col-sm-10'><input name=\"Password\" class=\"form-control\" type=\"password\" value=\"\" size=\"20\" maxlength=\"20\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='NivelAcceso'>".$lang["nivel"]."</label><div class='col-sm-10'>";	
$requete2 = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
$result2 = mysqli_query($db,$requete2);
print "<select name=\"NivelAcceso\" class=\"form-control\">";
print "<option value=\"\" SELECTED></option>";
while($listado2 = mysqli_fetch_object($result2))
{
	if ($listado2->Nivel>=$_SESSION['usuario_nivel'])
	{
		print "<option value=\"".$listado2->Nivel."\"";
		if ($listado->NivelAcceso==$listado2->Nivel) print " selected";
		print ">".$listado2->Nombre."</option>";
	}
}
print "</select>";
print "<input type=\"hidden\" name=\"referencia\" value=\"".$referencia."\">";
print "</div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='RedirigirLogin'>".$lang["redirigirLogin"]."</label><div class='col-sm-10'>";
print "<select name=\"RedirigirLogin\" class=\"form-control\">";
$requete2 = "SELECT * FROM `Secciones` WHERE `Visibilidad`='privado'";
if ($result2 = mysqli_query($db, $requete2))
{
	print "<option value=\"\"></option>";
	while($listado2 = mysqli_fetch_object($result2))
	{
		$ruta = "/Secciones";
		if ((substr($listado2->Path,0,1)!="/")&&$listado2->Path!="") $ruta.="/".$listado2->Path;
		else $ruta.=substr($listado2->Path,1);
		$ruta.="/".$listado2->NomFich;
		print "<option value=\"".$ruta."\"";
		if ($listado->RedirigirLogin==$ruta) print " selected";
		print ">".$listado2->Titulo." (".$listado2->Idioma.")</option>";
	}
}
print "</select>";
print "</div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='FechaCaducidad'>".$lang["caducidad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"FechaCaducidad\" id=\"FechaCaducidad\" type=\"text\" value=\"".$listado->FechaCaducidad."\" size=\"10\" maxlength=\"10\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='Activado'>".$lang['estado']."</label><div class='col-sm-10'>";
print "<div class=\"i-checks\"><label><input ";
if ($listado->Activado=="si") print "checked ";
print "name=\"Activado\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["activado"]."</label></div></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label'>".$lang['comunicaciones']."</label><div class='col-sm-3'><div class=\"i-checks\"><label><input ";
if ($listado->AltaBoletin=="si") print "checked ";
print "name=\"AltaBoletin\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["boletin"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input ";
if ($listado->AltaSMS=="si") print "checked ";
print "name=\"AltaSMS\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["SMS"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input ";
if ($listado->ExclusivoMailing=="si") print "checked ";
print "name=\"ExclusivoMailing\" type=\"checkbox\" value=\"si\"/><i></i> ".$lang["exclusivo"]."</label></div></div><div class='col-sm-1'></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["empresariales"]."</h2>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='NombreEmpresa'>".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NombreEmpresa\" type=\"text\" value=\"".$listado->NombreEmpresa."\" size=\"40\" maxlength=\"40\"/></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for='CIF'>".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CIF\" type=\"text\" value=\"".$listado->CIF."\" size=\"10\" maxlength=\"10\"/></div></div>";
print "<input type=\"hidden\" name=\"usuario\" value=\"".$usuario."\">";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>