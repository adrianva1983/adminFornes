<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/usuarios_listar-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang["campos"].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Interface/herramienta.php\" enctype=\"multipart/form-data\" method=\"GET\" class=\"form-horizontal\">";
print "<input name=\"herramienta\" type=\"hidden\" value=\"usuarios2\">";
print "<input name=\"modulo\" type=\"hidden\" value=\"Usuarios\">";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"Num_Pagina\">".$lang["numpagina"]."</label><div class='col-sm-10'><input class=\"form-control\" id=\"Num_Pagina\" name=\"Num_Pagina\" type=\"text\" value=\"10\" size=\"4\" maxlength=\"8\"></div></div>";
print "<div class='form-group campo_ingreso'><label class='col-sm-2 control-label' for=\"CamposMostrar\">".$lang["campos"]."</label>";
print "<div class='col-sm-10'><div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Nombre\" checked><i></i> ".$lang["nombre"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Apellidos\" checked><i></i> ".$lang["apellidos"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Email\" checked><i></i> ".$lang["email"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Provincia\"><i></i> ".$lang["provincia"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Ciudad\"><i></i> ".$lang["ciudad"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Telefono\"><i></i> ".$lang["telefono"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"Movil\"><i></i> ".$lang["movil"]."</label></div></div>";
print "<div class='col-sm-3'><div class=\"i-checks\"><label><input name=\"CamposMostrar[]\" type=\"checkbox\" value=\"NombreEmpresa\"><i></i> ".$lang["empresa"]."</label></div></div></div></div>";
print "<input name=\"origen\" type=\"hidden\" value=\"usuarios\">";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>