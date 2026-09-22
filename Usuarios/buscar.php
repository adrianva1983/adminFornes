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
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/buscar-".$_SESSION['idioma'].".conf");

require ($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/buscar_2.php");
////require "var.php";

/***VARIABLES POR GET ***/
$numero = count($_GET);
$tags = array_keys($_GET);
// obtiene los nombres de las varibles
$valores = array_values($_GET);
// obtiene los valores de las varibles
// crea las variables y les asigna el valor
//echo "GET:<hr>";
$consulta=1;
$or="";
for($i=0;$i<$numero;$i++)
{
	$$tags[$i]=$valores[$i];
	if (($valores[$i]!="") && ($tags[$i]!="modulo") && ($tags[$i]!="herramienta") && ($consulta))
	{
		$buscar=$buscar." $or`".$tags[$i]."` like '%".$valores[$i]."%' ";
		$or=" or ";
	}  
	//echo "<li>$$tags[$i]<li>$valores[$i]</li></li>";
}
//echo "<hr>$buscar<hr>";
$que=$or;
if ($que=="")
{
	$javascript_onready .= "
		\$(function() {
			\$( \"#FechaCreacion\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#FechaUltimoAcceso\" ).datepicker({format: 'yyyy-mm-dd'});
		});
		\$(function() {
			\$( \"#FechaCaducidad\" ).datepicker({format: 'yyyy-mm-dd'});
		});";
	print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['buscar'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
	echo "<form name=\"ubusca\" action=\"/administra/Interface/herramienta.php\" class=\"form-horizontal\">";	
	echo "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
	echo "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";		
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Id\">Id</label><div class='col-sm-10'><input class=\"form-control\" name=Id val=\"$Id\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for\"Nombre\">".$lang["nombre"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Nombre value=\"$Nombre\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Apellidos\">".$lang["apellidos"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Apellidos value=\"$Apellidos\"></div></div>";	
	print '<div class="hr-line-dashed"></div>';
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Direccion\">".$lang["direccion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Direccion value=\"$Direccion\"></div></div>";	
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Provincia\">".$lang["provincia"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Provincia value=\"$Provincia\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Municipio\">".$lang["municipio"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Municipio value=\"$Municipio\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"CP\">".$lang["cp"]."</label><div class='col-sm-10'><input class=\"form-control\" name=CP value=\"$CP\"></div></div>";	
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Ciudad\">".$lang["ciudad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Ciudad value=\"$Ciudad\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Pais\">".$lang["pais"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Pais value=\"$Pais\"></div></div>";	
	print '<div class="hr-line-dashed"></div>';
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Email\">".$lang["email"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Email value=\"$Email\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Telefono\">".$lang["telefono"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Telefono value=\"$Telefono\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"Movil\">".$lang["movil"]."</label><div class='col-sm-10'><input class=\"form-control\" name=Movil value=\"$movil\"></div></div>";
	print '<div class="hr-line-dashed"></div>';
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"NombreEmpresa\">".$lang["empresa"]."</label><div class='col-sm-10'><input class=\"form-control\" name=NombreEmpresa value=\"$NombreEmpresa\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"CIF\">".$lang["CIF"]."</label><div class='col-sm-10'><input class=\"form-control\" name=CIF value=\"$CIF\"></div></div>";
	print '<div class="hr-line-dashed"></div>';
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaCreacion\">".$lang["creaccion"]."</label><div class='col-sm-10'><input class=\"form-control\" name=FechaCreaccion id=FechaCreaccion value=\"$FechaCreaccion\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaCaducidad\">".$lang["caducidad"]."</label><div class='col-sm-10'><input class=\"form-control\" name=FechaCaducidad id=FechaCaducidad value=\"$FechaCaducidad\"></div></div>";
	echo "<div class='form-group'><label class='col-sm-2 control-label' for=\"FechaUltimoAcceso\">".$lang["ultimo"]."</label><div class='col-sm-10'><input class=\"form-control\" name=FechaUltimoAcceso id=FechaUltimoAcceso value=\"$FechaUltimoAcceso\"></div></div>";	
	print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["buscar"]."\">";
	print "</form>";
	print '</div></div></div></div>';
}
else
{
	$que=$buscar;
	busca_usuario($que,0);
}

?>