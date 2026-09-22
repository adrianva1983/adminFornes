<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//NO HAY VARIABLES

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_proyecto-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nuevo_proyecto'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/nuevo_proyecto_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombre"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nombre\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Descripcion\">".$lang["descripcion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\"></textarea></div></div>";
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
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Plantilla\">".$lang["plantilla"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Plantilla\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo` = 'proyecto' ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Mantenimiento\">".$lang["mantenimiento"]."</label><div class='col-sm-10'>";
print "<div class=\"i-checks\"><label><input type=\"checkbox\" value='1' name='Mantenimiento'><i></i> </label></div></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>