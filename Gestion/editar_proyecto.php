<?php
//VERSIÓN: v1.0 2017-1-27
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ($_SESSION['usuario_nivel']>2)
{
	$requete = "SELECT * FROM `ProyectosTrabajadores` WHERE `IdProyecto`=".$Id." AND `IdUsuario`=".$_SESSION['usuario_id'];
	$result = mysqli_query($db, $requete);
	if ($result && mysqli_num_rows($result)>0)
	{}
	else
	{
		die ("Error cod.:1 - Acceso incorrecto!");
		exit;
	}
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_proyecto-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Proyectos` WHERE `Id`=".$Id;

// Listamos las plantillas existentes
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_proyecto'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_proyecto_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input name=\"Id\" type=\"hidden\" value=\"".$Id."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdCliente\">".$lang["cliente"].":</label><div class='col-sm-10'><select name=\"IdCliente\" class='form-control'>";
print "<option value=\"\">".$lang["sinValor"]."</option>";
// Hacemos una consulta para ver los distintos clientes disponibles
$requete2 = "SELECT * FROM `Clientes` ORDER BY `DenominacionSocial`;";

// Listamos las plantillas existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->IdCliente==$listado2->Id) print "<option value=\"".$listado2->Id."\" selected>".$listado2->DenominacionSocial."</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->DenominacionSocial."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre\">".$lang["nombre"].":</label><div class='col-sm-10'><input class='form-control' name=\"Nombre\" type=\"text\" value=\"".$listado->Nombre."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Descripcion\">".$lang["descripcion"].":</label><div class='col-sm-10'><textarea class='form-control' wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Descripcion\" id=\"Descripcion\">".$listado->Descripcion."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Responsable\">".$lang["responsable"].":</label><div class='col-sm-10'><select class='form-control' name=\"Responsable\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado->IdResponsable==$listado2->Id) print "<option selected value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"EquipoInvolucrado\">".$lang["equipo_involucrado"].":</label><div class='col-sm-4'>";
print "<select class='form-control' id='anadir_involucrado'>";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `TieneTareas` = 1 ORDER BY `Nombre`;";
// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{		
		print "<option value='<div class=\"label label-info pull-left\" onclick=\"$(this).remove();\"><i class=\"fa fa-times-circle\"></i> <img alt=\"image\" width=\"18px\" class=\"img-circle\" src=\"/Imagenes/Perfiles/".$listado2->Foto."\"> ".$listado2->Nombre." ".$listado2->Apellidos."<input type=\"hidden\" name=\"EquipoInvolucrado[]\" value=\"".$listado2->Id."\"/></div>'>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div><div class='col-sm-2'><a href='#' onclick='$(\"#bloque_equipo_involucrado\").append($(\"#anadir_involucrado\").val());return false;' class='btn btn-primary'><i class='fa fa-plus'></i></a></div>";
print "<div class='col-sm-4' id='bloque_equipo_involucrado'>";
$requete2 = "SELECT `Usuarios`.* FROM `Usuarios`,`ProyectosTrabajadores` WHERE `Usuarios`.Id = `ProyectosTrabajadores`.IdUsuario AND `ProyectosTrabajadores`.IdProyecto=".$Id;
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Foto!='') print '<div class="label label-info pull-left" onclick="$(this).remove();"><i class="fa fa-times-circle"></i> <img alt="image" width="18px" class="img-circle" src="/Imagenes/Perfiles/'.$listado2->Foto.'"> '.$listado2->Nombre.' '.$listado2->Apellidos.'<input type="hidden" name="EquipoInvolucrado[]" value="'.$listado2->Id.'"/></div>';
	}
}
print "</div></div>";
print '<div class="hr-line-dashed"></div>';
print "</ul>";
print '<div class="form-group">';
print "<div class='col-sm-6'><input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\"></div>";
print '</div>';
print '</div></div></div></div>';
print "</form>";

?>