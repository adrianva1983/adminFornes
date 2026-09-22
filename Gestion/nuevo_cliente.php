<?php
//VERSIÓN: v1.0 2014-4-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES

//SIN VARIABLES

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
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['titulo_nuevo_cliente'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Gestion/nuevo_cliente_2.php\" enctype=\"multipart/form-data\" method=\"POST\" class=\"form-horizontal\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Proveedor\">".$lang["clienteOProveedor"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Proveedor\">";
print "<option value=0 selected>".$lang['cliente']."</option>";
print "<option value=1>".$lang['proveedor']."</option>";
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdRepresentante\">".$lang["representante"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdRepresentante\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Usuarios` WHERE `RepresentantePresupuestos` = 1 ORDER BY `Nombre`;";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\"";
		if ($_SESSION['usuario_nivel']>=2 && $_SESSION['usuario_id']==$listado->Id) print " selected";
		print ">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
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
		print "<option value=\"".$listado->Id."\"";
		if ($_SESSION['usuario_nivel']>=2 && $_SESSION['usuario_id']==$listado->Id) print " selected";
		print ">".$listado->Nombre." ".$listado->Apellidos." (".$listado->NombreEmpresa.")</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Referencia\">".$lang["referencia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Referencia\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"DenominacionSocial\">".$lang["nombreEmpresa"]."</label><div class='col-sm-10'><input required class=\"form-control\" name=\"DenominacionSocial\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"NombreComercial\">".$lang["nombreComercial"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"nombreComercial\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CIF\">".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CIF\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Poblacion\">".$lang["poblacion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Poblacion\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Municipio\">".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Municipio\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Provincia\">".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Provincia\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CP\">".$lang["CP"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CP\" type=\"text\" value=\"\" size=\"5\" maxlength=\"5\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Direccion\">".$lang["direccion"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"3\" cols=\"60\" name=\"Direccion\" id=\"Direccion\"></textarea></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Telefono\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email\">".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Email\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Web\">".$lang["web"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Web\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Estado\">".$lang["estado"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Estado\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Estados` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Tabla`='Clientes';";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Id."\">".$listado->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Tipo\">".$lang["tipo"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"Tipo\">";
print "<option value=\"\">".$lang["sinValor"]."</option>";
$requete = "SELECT * FROM `Tipos` WHERE `Idioma` = '".$_SESSION['idioma']."' AND `Tabla`='Clientes';";

// Listamos los representantes existentes
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option ";
		if ($_GET['Tipo']==$listado->Id) print "selected ";
		print "value=\"".$listado->Id."\">".$listado->Titulo."</option>";
	}
}
print "</select></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Notas\">".$lang["notas"]."</label><div class='col-sm-10'><textarea wrap=\"VIRTUAL\" class=\"form ancho100\" rows=\"10\" cols=\"60\" name=\"Notas\" id=\"Notas\"></textarea></div></div>";

if ($_GET["IdUsuario"]!="")
{
	print '<div class="hr-line-dashed"></div>';
	print "<h2>".$lang["datosLogin"]."</h2>";	
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdUsuario\">".$lang["idusuario"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"IdUsuario\" type=\"text\" value=\"".$_GET["IdUsuario"]."\" size=\"40\" maxlength=\"100\"></div></div>";	
}
else
{
	print '<div class="hr-line-dashed"></div>';
	print "<h2>".$lang["datosLogin"]."</h2>";	
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Login\">".$lang["login"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Login\" type=\"text\" value=\"\" size=\"40\" maxlength=\"100\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Pass\">".$lang["pass"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Pass\" type=\"password\" value=\"\" size=\"20\" maxlength=\"100\"></div></div>";
}
print '<div class="hr-line-dashed"></div>';
print "<h2>".$lang["datosBancarios"]."</h2>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IBAN\">".$lang["IBAN"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"IBAN\" type=\"text\" value=\"\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaBanco\">".$lang["cuentaBanco"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaBanco\" type=\"text\" value=\"\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaSucursal\">".$lang["cuentaSucursal"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaSucursal\" type=\"text\" value=\"\" size=\"4\" maxlength=\"4\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaDigitoControl\">".$lang["cuentaDigitoControl"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaDigitoControl\" type=\"text\" value=\"\" size=\"2\" maxlength=\"2\"></div></div>";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"CuentaNumero\">".$lang["numeroCuenta"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"CuentaNumero\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></div></div>";
if ($_GET["IdUsuario"]!="")
{}
else
{
	print '<div class="hr-line-dashed"></div>';
	print "<h2>".$lang["datosContactos"]."</h2>";	
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Nombre0\">".$lang["nombreContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Nombre0\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Apellidos0\">".$lang["apellidosContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Apellidos0\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Cargo0\">".$lang["cargo"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Cargo0\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email0\">".$lang["emailContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Email0\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Movil0\">".$lang["movilContacto"]."</label><div class='col-sm-10'><input class=\"form-control\" name=\"Movil0\" type=\"text\" value=\"\" size=\"100\" maxlength=\"200\"></div></div>";
	print "<div class='form-group'><label class='col-sm-2 control-label' for=\"IdRepresentante0\">".$lang["representante"]."</label><div class='col-sm-10'><select class=\"form-control\" name=\"IdRepresentante0\">";
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
}
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["guardar"]."\">";
print "</form>";
print '</div></div></div></div>';
?>