<?php
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/reasignar_contacto-".$_SESSION['idioma'].".conf");
print "<h1>".$lang["buscarCliente"]."</h1>";
echo "<form name=\"ubusca\" action=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=reasignar_contacto\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input type=\"hidden\" name=\"IdContacto\" value=\"".$IdContacto."\">";
echo "<input class=\"boton\" name=\"buscar\" type=\"submit\" value=\"".$lang["buscar"]."\">";
echo "<table>";
echo "<tr><td><label for=\"Id\">Id:</label><br/><input class=\"ancho100\" name=\"Id\" value=\"\"></td><td><label for\"DenominacionSocial\">".$lang["denominacionSocial"].":</label><br/><input class=\"ancho100\" name=\"DenominacionSocial\"></td><td><label for=\"CIF\">".$lang["CIF"].":</label><br/><input class=\"ancho100\" name=\"CIF\" value=\"\"></td></tr>";
echo "<tr><td><label for=\"Provincia\">".$lang["provincia"].":</label><br/><input class=\"ancho100\" name=\"Provincia\" value=\"\"></td><td><label for=\"Municipio\">".$lang["municipio"].":</label><br/><input class=\"ancho100\" name=\"Municipio\" value=\"\"></td><td><label for=\"CP\">".$lang["cp"].":</label><br/><input class=\"ancho100\" name=CP value=\"$CP\"></td></tr>";
echo "<tr><td><label for=\"Poblacion\">".$lang["ciudad"].":</label><br/><input class=\"ancho100\" name=\"Poblacion\" value=\"\"></td><td><label for=\"Telefono\">".$lang["telefono"].":</label><br/><input class=\"ancho100\" name=\"Telefono\" value=\"\"></td><td><label for=\"Web\">".$lang["web"].":</label><br/><input class=\"ancho100\" name=\"Web\" value=\"\"></td></tr>";
print "</table>";
echo "<input class=\"boton\" name=\"buscar\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
if ($buscar!="")
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	print "<h1>".$lang["seleccionaCliente"]."</h1>";
	$requete = "SELECT * FROM `Clientes` WHERE ";
	$primero = true;
	if ($Id!="") 
	{
		if ($primero)
		{
			$requete.="`Id`=".$Id;
			$primero = false;
		}
		else $requete.=" AND `Id`=".$Id;	
	}
	if ($DenominacionSocial!="") 
	{
		if ($primero)
		{
			$requete.="`DenominacionSocial` LIKE '%".$DenominacionSocial."%'";
			$primero = false;
		}
		else $requete.=" AND `DenominacionSocial` LIKE '%".$DenominacionSocial."%'";
	}
	if ($CIF!="") 
	{
		if ($primero)
		{
			$requete.="`CIF` LIKE '%".$CIF."%'";
			$primero = false;
		}
		else $requete.=" AND `CIF` LIKE '%".$CIF."%'";
	}
	if ($Provincia!="") 
	{
		if ($primero)
		{
			$requete.="`Provincia`LIKE '%".$Provincia."%'";
			$primero = false;
		}
		else $requete.=" AND `Provincia` LIKE '%".$Provincia."%'";
	}
	if ($Municipio!="") 
	{
		if ($primero)
		{
			$requete.="`Municipio` LIKE '%".$Municipio."%'";
			$primero = false;
		}
		else $requete.=" AND `Municipio` LIKE '%".$Municipio."%'";
	}
	if ($CP!="") 
	{
		if ($primero)
		{
			$requete.="`CP` LIKE '%".$CP."%'";
			$primero = false;
		}
		else $requete.=" AND `CP` LIKE '%".$CP."%'";
	}
	if ($Poblacion!="") 
	{
		if ($primero)
		{
			$requete.="`Poblacion` LIKE '%".$Poblacion."%'";
			$primero = false;
		}
		else $requete.=" AND `Poblacion` LIKE '%".$Poblacion."%'";
	}
	if ($Web!="") 
	{
		if ($primero)
		{
			$requete.="`Web`LIKE '%".$Web."%'";
			$primero = false;
		}
		else $requete.=" AND `Web` LIKE '%".$Web."%'";
	}
	$requete .= " ORDER BY `Id`";
	
	if ($result = mysqli_query($db, $requete))
	{
		print "<ul>";
		while ($listado = mysqli_fetch_object($result))
		{
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=reasignar_contacto2&IdCliente=".$listado->Id."&IdContacto=".$IdContacto."\">".$listado->DenominacionSocial."</a></li>";
		}
		print "</ul>";
	}
	else
	{
		print "<p class=\"mensajeKO\">".$lang["noResultados"]."</p>";
	}
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
}
?>