<?php
$Nombre = $_POST["Nombre"];
$Apellidos = $_POST["Apellidos"];
$Ciudad = $_POST["Ciudad"];
$Municipio = $_POST["Municipio"];
$Provincia = $_POST["Provincia"];
$Pais = $_POST["Pais"];
$NombreEmpresa = $_POST["NombreEmpresa"];
$Enviar = $_POST["Enviar"];
if ($_POST["origen"]!="") $origen = $_POST["origen"];
else $origen = $_GET["origen"];
if ($_POST["Num_Pagina"]!="") $Num_Pagina = $_POST["Num_Pagina"];
else $Num_Pagina = $_GET["Num_Pagina"];
if ($_POST["CamposMostrar"]!="") $CamposMostrar = $_POST["CamposMostrar"];
else $CamposMostrar = $_GET["CamposMostrar"];
if ($_POST["pagina"]!="") $pagina = $_POST["pagina"];
else $pagina = $_GET["pagina"];
if ($_POST["grupo"]!="") $grupo = $_POST["grupo"];
else $grupo = $_GET["grupo"];
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/vincular_usuario_grupo-".$_SESSION['idioma'].".conf");

if ($Enviar!="")
{
	$requete = "SELECT * FROM `Usuarios` WHERE `NivelAcceso`>='".$_SESSION['usuario_nivel']."'";
	$primero = true;
	if ($Nombre!="") $requete.= " AND `Nombre` LIKE '%".$Nombre."%'";
	if ($Apellidos!="") $requete.= "AND `Apellidos` LIKE '%".$Apellidos."%'";
	if ($Ciudad!="") $requete.= "AND `Ciudad` LIKE '%".$Ciudad."%'";
	if ($Municipio!="") $requete.= "AND `Municipio` LIKE '%".$Municipio."%'";
	if ($Provincia!="") $requete.= "AND `Provincia` LIKE '%".$Provincia."%'";
	if ($Pais!="") $requete.= "AND `Pais` LIKE '%".$Pais."%'";
	if ($NombreEmpresa!="") $requete.= "AND `NombreEmpresa` LIKE '%".$NombreEmpresa."%'";	
	
	if ($result = mysqli_query($db, $requete))
	{
		$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
		print "<form action=\"/administra/Usuarios/vincular_usuario_grupo_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
		print "<input type='hidden' name='origen' value='".$origen."'>";
		print "<input type='hidden' name='Num_Pagina' value='".$Num_Pagina."'>";
		print "<input type='hidden' name='CamposMostrar' value='".urlencode(serialize($CamposMostrar))."'>";
		print "<input type='hidden' name='pagina' value='".$pagina."'>";
		print "<input type='hidden' name='grupo' value='".$grupo."'>";
		print "<ul>";
		while($listado = mysqli_fetch_object($result))
		{
			$requete = "SELECT * FROM `Usuarios`,`PertenenciaGrupos` WHERE `Usuarios`.Id=`PertenenciaGrupos`.IdUsuario AND `PertenenciaGrupos`.IdGrupo ='".$grupo."' AND `Usuarios`.Id='".$listado->Id."'";
			$result2 = mysqli_query($db,$requete);
			if ($result2 = mysqli_query($db, $requete2))
			{
				print "<li><img src=\"/administra/Imagenes/tick.png\" alt=\"Vinculado\"><img src=\"/administra/Imagenes/usuario.png\">".$listado->Nombre.", ".$listado->Apellidos."</li>";
			}
			else
			{
				print "<li><input class=\"suscripcion\" name=\"usuarios/".$listado->Id."\" type=\"checkbox\" value=\"".$listado->Id."\"><img src=\"/administra/Imagenes/usuario.png\">".$listado->Nombre.", ".$listado->Apellidos."</li>";
			}		
		}
		print "</ul>";
	}
	else
	{
		print $lang["noEncontrados"];
	}
	print "<hr>";
	print "".$lang["heredados"].":";
	$requete = "SELECT * FROM `Permisos` WHERE `IdGrupoSuscrito`='".$grupo."'";	
	
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			$requete = "SELECT `Titulo` FROM `Secciones` WHERE `Id`='".$listado->IdSeccion."'";
			$result2 = mysqli_query($db,$requete);
			$listado2 = mysqli_fetch_object($result2);
			print "<li><input class=\"suscripcion\" name=\"permisos/".$listado->IdSeccion."/".$listado->Nivel."/".$listado->FinSuscripcion."\" type=\"checkbox\" value=\"".$listado->IdSeccion."\" checked><img src=\"/administra/Imagenes/secciones.png\">".$listado2->Titulo."</li>";
		}
	}
	print "<input type=\"hidden\" name=\"grupo\" value=\"".$grupo."\">";
	print "<input class=\"boton\" type=\"submit\" value=\"".$lang["vincular"]."\">";
	print "</form>";
}
else
{
	$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
	print "<form action=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=vincular_usuario_grupo\" enctype=\"multipart/form-data\" method=\"POST\">";
	print "<input type='hidden' name='origen' value='".$origen."'>";
	print "<input type='hidden' name='Num_Pagina' value='".$Num_Pagina."'>";
	print "<input type='hidden' name='CamposMostrar' value='".urlencode(serialize($CamposMostrar))."'>";
	print "<input type='hidden' name='pagina' value='".$pagina."'>";
	print "<input type='hidden' name='grupo' value='".$grupo."'>";
	echo "<table>";
	echo "<tr><td>".$lang["nombre"]." </td><td><input name=Nombre value=\"$Nombre\"></td></tr>";
	echo "<tr><td>".$lang["apellidos"]." </td><td><input name=Apellidos value=\"$Apellidos\"></td></tr>";
	echo "<tr><td>".$lang["ciudad"]." </td><td><input name=Ciudad value=\"$Ciudad\"></td></tr>";
	echo "<tr><td>".$lang["municipio"]." </td><td><input name=Municipio value=\"$Municipio\"></td></tr>";	
	echo "<tr><td>".$lang["provincia"]." </td><td><input name=Provincia value=\"$Provincia\"></td></tr>";
	echo "<tr><td>".$lang["pais"]." </td><td><input name=Pais value=\"$Pais\"></td></tr>";
	echo "<tr><td>".$lang["empresa"]." </td><td><input name=NombreEmpresa value=\"$NombreEmpresa\"></td></tr>";
	echo "</table>";
	print "<input class=\"boton\" name=\"Enviar\" type=\"submit\" value=\"".$lang["buscar"]."\">";
	print "</form>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>