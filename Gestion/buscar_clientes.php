<?php
// Hay que hacer lo mismo que la de buscar carpetas, pero para usuarios
// La diferencia es que la búsqueda básica será por nombre y/o apellido
// Abrá posibilidad de búsqueda avanzada que permitirá buscar por cualquier campo de la tabla de usuarios menos por la contraseña
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">Nivel Acceso:".$nivel_acceso."<br>Nivel Usuario:".$_SESSION['usuario_nivel']."<br>Puerta Lógica:".($nivel_acceso <= $_SESSION['usuario_nivel']);
	Print "No tiene permisos para acceder a este &aacute;rea</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/buscar_clientes-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_buscar_cliente'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
echo "<form name=\"ubusca\" action=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=clientes\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Id\">Id</label><div class='col-sm-10'><input class=\"form-control\" name=\"Id\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for\"DenominacionSocial\">".$lang["denominacionSocial"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"DenominacionSocial\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CIF\">".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CIF\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Provincia\">".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Provincia\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Municipio\">".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Municipio\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CP\">".$lang["cp"]."</label><div class='col-sm-10'><input class=\"form-control\" name=CP value=\"$CP\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Poblacion\">".$lang["ciudad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Poblacion\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Web\">".$lang["web"].":</label><div class='col-sm-10'><input class=\"form-control\" name=\"Web\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Referencia\">".$lang["referencia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Referencia\" value=\"\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Estado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Estado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Estados` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Tabla`='Clientes';";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Tipo\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Tipos` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Tabla`='Clientes';";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["datosRepresentacion"]."</h2>";
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
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>