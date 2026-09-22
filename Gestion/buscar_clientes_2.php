<?php
$Id = $_POST["Id"];
$DenominacionSocial = $_POST["DenominacionSocial"];
$CIF = $_POST["CIF"];
$Provincia = $_POST["Provincia"];
$Municipio = $_POST["Municipio"];
$CP = $_POST["CP"];
$Poblacion = $_POST["Poblacion"];
$Web = $_POST["Web"];
$IdRepresentante = $_POST["IdRepresentante"];
$IdFamilia = $_POST["IdFamilia"];
$Tipo = $_POST["Tipo"];
$Estado = $_POST["Estado"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/buscar_clientes_2-".$_SESSION['idioma'].".conf");
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
if ($IdRepresentante!="") 
{
	if ($primero)
	{
		$requete.="`IdRepresentante`=".$IdRepresentante;
		$primero = false;
	}
	else $requete.=" AND `IdRepresentante` =".$IdRepresentante;
}
if ($IdFamilia!="") 
{
	if ($primero)
	{
		$requete.="`IdFamilia`=".$IdFamilia;
		$primero = false;
	}
	else $requete.=" AND `IdFamilia` =".$IdFamilia;
}
if ($Tipo!="") 
{
	if ($primero)
	{
		$requete.="`IdTipo`=".$Tipo;
		$primero = false;
	}
	else $requete.=" AND `IdTipo` =".$Tipo;
}
if ($Estado!="") 
{
	if ($primero)
	{
		$requete.="`IdEstado`=".$Estado;
		$primero = false;
	}
	else $requete.=" AND `IdEstado` =".$Estado;
}
$requete .= " ORDER BY `Id`";

print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/user_suit_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "<img src=\"/administra/Imagenes/user_delete.png\" title=\"".$lang["eliminar"]."\" alt=\"".$lang["eliminar"]."\"> :: ".$lang["eliminar"]."<br/>";
print "<img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturas"]."\" alt=\"".$lang["facturas"]."\"> :: ".$lang["facturas"]."<br/>";
print "<img src=\"/administra/Imagenes/nuevo_grupo.png\" title=\"".$lang["nuevo_contacto"]."\" alt=\"".$lang["nuevo_contacto"]."\"> :: ".$lang["nuevo_contacto"]."<br/>";
print "<img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestos"]."\" alt=\"".$lang["presupuestos"]."\"> :: ".$lang["presupuestos"]."<br/>";
print "<img src=\"/administra/Imagenes/calculator_add.png\" title=\"".$lang["nuevoPresupuesto"]."\" alt=\"".$lang["nuevoPresupuesto"]."\"> :: ".$lang["nuevoPresupuesto"]."<br/>";
print "<img src=\"/administra/Imagenes/application_add.png\" title=\"".$lang["nuevo_proyecto"]."\" alt=\"".$lang["nuevo_proyecto"]."\"> :: ".$lang["nuevo_proyecto"]."<br/>";
print "</div>";
if ($result = mysqli_query($db, $requete))
{
	$total_contenidos_pagina = mysqli_num_rows($result);
	print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</p>";
	print "<table>";
	print "<tr><th>Id</th><th>".$lang["acciones"]."</th><th>".$lang["cliente"]."</th><th>".$lang["contactos"]."</th><th>".$lang["representante"]."</th><th>".$lang["facturado12"]."</th><th>".$lang["facturado"]."</th></tr>";
	$par = false;
	while ($listado = mysqli_fetch_object($result))
	{
		if ($par)
		{	
			print "<tr id=\"par\">";
			$par = false;
		}
		else
		{
			print "<tr>";
			$par = true;
		}
		print "<td>".$listado->Id."</td>";
		print "<td>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/user_suit_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a> ";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=eliminar_cliente&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/user_delete.png\" title=\"".$lang["eliminar"]."\" alt=\"".$lang["eliminar"]."\"></a> ";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturas&IdCliente=".$listado->Id."\"><img src=\"/administra/Imagenes/invoice.png\" title=\"".$lang["facturas"]."\" alt=\"".$lang["facturas"]."\"></a> ";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_contacto&Id=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/nuevo_grupo.png\" title=\"".$lang["nuevo_contacto"]."\" alt=\"".$lang["nuevo_contacto"]."\"></a> ";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=presupuestos&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestos"]."\" alt=\"".$lang["presupuestos"]."\"></a>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_presupuesto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/calculator_add.png\" title=\"".$lang["nuevoPresupuesto"]."\" alt=\"".$lang["nuevoPresupuesto"]."\"></a>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nuevo_proyecto&IdCliente=".$listado->Id."&origen=clientes&pagina=".$pagina."\"><img src=\"/administra/Imagenes/application_add.png\" title=\"".$lang["nuevo_proyecto"]."\" alt=\"".$lang["nuevo_proyecto"]."\"></a>";
		print "</td>";
		print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id=".$listado->Id."\">";
		print $listado->DenominacionSocial;
		print "</a></td>";
		$requete2 = "SELECT * FROM `Contactos` WHERE `IdCliente`=".$listado->Id;
		
		print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=contactos_cliente&Id=".$listado->Id."\">".mysqli_num_rows($result2)."</a></td>";
		print "<td>";
		if ($listado->IdRepresentante!="")
		{
			$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdRepresentante;
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				print $listado2->Nombre." ".$listado2->Apellidos;
			}			
		}
		else print "-";
		print "</td>";
		print "<td>";
		print "0€</td>";
		print "<td>";		
		print "<strong>0€</strong></td>";
		print "</tr>";
	}
	print "</table>";
}
else print "<p class=\"mensajeKO\">".$lang["noResultados"]."</p>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
