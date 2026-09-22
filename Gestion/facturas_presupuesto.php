<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];

//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/facturas_presupuesto-".$_SESSION['idioma'].".conf");
print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/page_white_acrobat.png\" title=\"".$lang["descargar"]."\" alt=\"".$lang["descargar"]."\"> :: ".$lang["descargar"]."<br/>";
print "<img src=\"/administra/Imagenes/invoice_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "<img src=\"/administra/Imagenes/coins_add.png\" title=\"".$lang["insertarPago"]."\" alt=\"".$lang["insertarPago"]."\"> :: ".$lang["insertarPago"]."<br/>";
print "<img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestoRelacionado"]."\" alt=\"".$lang["presupuestoRelacionado"]."\"> :: ".$lang["presupuestoRelacionado"]."<br/>";
print "<img src=\"/administra/Imagenes/printer.png\" title=\"".$lang["generarFacturaImprimible"]."\" alt=\"".$lang["generarFacturaImprimible"]."\"> :: ".$lang["generarFacturaImprimible"]."<br/>";
print "</div>";
$requete = "SELECT * FROM `Facturas` WHERE IdPresupuesto=".$Id;
$requete .= " ORDER BY `Fecha`";

$totalFactura = 0;
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>".$lang["saldado"]."</th><th>".$lang["numeroFactura"]."</th><th>".$lang["acciones"]."</th><th>".$lang["vencimiento"]."</th><th>".$lang["titulo"]."</th><th>".$lang["cliente"]."</th><th>".$lang["baseImponible"]."</th><th>".$lang["estado"]."</th></tr>";
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
		print "<td>";
		if ($listado->Rectificativa==1) print "<img src=\"/administra/Imagenes/bullet_error.png\" title=\"".$lang["rectificativa"]."\" alt=\"".$lang["rectificativa"]."\">";
		else
		{
			if ($listado->Saldada==1) print "<img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["saldado"]."\" alt=\"".$lang["saldado"]."\">";
			else print "<img src=\"/administra/Imagenes/cross.png\" title=\"".$lang["noSaldado"]."\" alt=\"".$lang["noSaldado"]."\">";
		}
		print "</td>";
		print "<td>";
		if ($listado->SerieFactura!="") print $listado->SerieFactura."-";
		print $listado->NumeroFactura."</td>";
		print "<td>";
		print "<a href=\"/administra/Gestion/factura_imprimible.php?Id=".$listado->Id."&pdf=si\" target=\"_blank\"><img src=\"/administra/Imagenes/page_white_acrobat.png\" title=\"".$lang["descargar"]."\" alt=\"".$lang["descargar"]."\"></a>";				
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_factura&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/invoice_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=insertar_pago&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/coins_add.png\" title=\"".$lang["insertarPago"]."\" alt=\"".$lang["insertarPago"]."\"></a>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_presupuesto&Id=".$listado->IdPresupuesto."\"><img src=\"/administra/Imagenes/calculator.png\" title=\"".$lang["presupuestoRelacionado"]."\" alt=\"".$lang["presupuestoRelacionado"]."\"></a>";
		print "</td>";
		print "<td>".$listado->Vencimiento."</td>";
		print "<td>".$listado->Titulo."</td>";
		$requete2 = "SELECT * FROM `Clientes` WHERE `Id` = '".$listado->IdCliente."'";
		
		print "<td>";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->DenominacionSocial;
		}
		else
		{
			if ($listado->NombreEmpresa!="") print $listado->NombreEmpresa;
			else print "-";
		}
		print "</td>";
		print "<td>";
		$requete2 = "SELECT * FROM `FacturasLineas` WHERE `IdFactura` = '".$listado->Id."'";
		
		$total = 0;
		if ($result2 = mysqli_query($db, $requete2))
		{
			while ($listado2 = mysqli_fetch_object($result2))
			{
				$total += $listado2->BaseImponible;
			}
		}
		print $total."€</td>";
		$requete2 = "SELECT * FROM `Facturas` WHERE `IdFacturaRelacionada` = '".$listado->Id."' AND `Rectificativa`=1";
		
		if ($result2 = mysqli_query($db, $requete2)) //EXCLUYO DEL ACUMULADO LAS RECTIFICATIVAS
		{
		}
		else
		{
			if ($listado->Rectificativa!=1) $totalFactura+=$total;
		}
		$requete2 = "SELECT * FROM `FacturasEstados` WHERE `Id` = '".$listado->IdEstado."' AND `Idioma`='".$_SESSION['idioma']."'";
		
		print "<td>";
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->Titulo;
		}
		else
		{
			print "-";
		}
		print "</td>";
		print "</tr>";
	}
	print "</table>";
	print "<p><strong>".$lang["total"].": ".$totalFactura." €</strong><p>";
}
else
{
	print "<p class=\"mensajeKO\">".$lang["noFacturas"]."</p>";
}
print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=facturar_presupuesto&Id=".$Id."\"><img src=\"/administra/Imagenes/accept.png\" title=\"".$lang["facturar"]."\" alt=\"".$lang["facturar"]."\"> ".$lang["facturar"]."</a></p>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
