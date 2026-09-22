<?php
//VERSIÓN: v1.0 2014-4-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id= $_GET["Id"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/nuevo_cliente-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
$requete = "SELECT * FROM `Clientes` WHERE `Id`='".$Id."';";

//Sacamos los datos del cliente
if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_editar_cliente'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/editar_cliente_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<input type=\"hidden\" name=\"Id\" value=\"".$Id."\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdRepresentante\">".$lang["representante"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdRepresentante\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `RepresentantePresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdRepresentante) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdFamilia\">".$lang["familia"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdFamilia\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `FamiliaPresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		if ($listado2->Id==$listado->IdFamilia) print "<option value=\"".$listado2->Id."\" selected>".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
		else print "<option value=\"".$listado2->Id."\">".$listado2->Nombre." ".$listado2->Apellidos." (".$listado2->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Referencia\">".$lang["referencia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Referencia\" type=\"text\" value=\"".$listado->Referencia."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"DenominacionSocial\">".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"DenominacionSocial\" type=\"text\" value=\"".$listado->DenominacionSocial."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NombreComercial\">".$lang["nombreComercial"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"NombreComercial\" type=\"text\" value=\"".$listado->NombreComercial."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CIF\">".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CIF\" type=\"text\" value=\"".$listado->CIF."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Poblacion\">".$lang["poblacion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Poblacion\" type=\"text\" value=\"".$listado->Poblacion."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Municipio\">".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Municipio\" type=\"text\" value=\"".$listado->Municipio."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Provincia\">".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Provincia\" type=\"text\" value=\"".$listado->Provincia."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CP\">".$lang["CP"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CP\" type=\"text\" value=\"".$listado->CP."\" size=\"5\" maxlength=\"5\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Direccion\">".$lang["direccion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"3\" cols=\"60\" name=\"Direccion\" id=\"Direccion\">".$listado->Direccion."</textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"".$listado->Telefono."\" size=\"10\" maxlength=\"10\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email\">".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Email\" type=\"text\" value=\"".$listado->Email."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Web\">".$lang["web"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Web\" type=\"text\" value=\"".$listado->Web."\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Estado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Estado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete2 = "SELECT * FROM `Estados` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Tabla`='Clientes';";

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<option ";
		if ($listado->IdEstado==$listado2->Id) print "selected ";
		print "value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
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
		print "<option ";
		if ($listado->IdTipo==$listado2->Id) print "selected ";
		print "value=\"".$listado2->Id."\">".$listado2->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Notas\">".$lang["notas"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Notas\" id=\"Notas\">".$listado->Notas."</textarea></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["datosLogin"]."</h2>";
$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdUsuario;

// Listamos los representantes existentes
if ($result2 = mysqli_query($db, $requete2))
{
	$listado2 = mysqli_fetch_object($result2);
	$Login = $listado2->Email;
	$Pass = $listado2->Password;
}
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Login\">".$lang["login"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Login\" type=\"text\" value=\"".$Login."\" size=\"40\" maxlength=\"100\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Pass\">".$lang["pass"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Pass\" type=\"password\" value=\"".$Pass."\" size=\"20\" maxlength=\"100\"></div></div>";
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["datosBancarios"]."</h2>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IBAN\">".$lang["IBAN"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"IBAN\" type=\"text\" value=\"".$listado->IBAN."\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaBanco\">".$lang["cuentaBanco"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaBanco\" type=\"text\" value=\"".$listado->CuentaBanco."\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaSucursal\">".$lang["cuentaSucursal"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaSucursal\" type=\"text\" value=\"".$listado->CuentaSucursal."\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaDigitoControl\">".$lang["cuentaDigitoControl"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaDigitoControl\" type=\"text\" value=\"".$listado->CuentaDigitoControl."\" size=\"2\" maxlength=\"2\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaNumero\">".$lang["numeroCuenta"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaNumero\" type=\"text\" value=\"".$listado->CuentaNumero."\" size=\"10\" maxlength=\"10\"></div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>