<?php
//VERSIÓN: v1.0 2014-5-30
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
$Idioma = $_GET["Idioma"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Facturas` WHERE `Id`=".$Id;

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$requete2 = "SELECT * FROM `Clientes` WHERE `Id`=".$listado->IdCliente;
	
	if ($result2 = mysqli_query($db, $requete2))
	{
		$listado2 = mysqli_fetch_object($result2);
		if ($listado2->Email!="") 
		{
			$correos = explode(";",$listado2->Email);
			for ($indice_correos=0;$indice_correos<count($correos);$indice_correos++)
			{
				$msg = "Estimad@<br/>Le enviamos factura <strong>";
				$texto_factura = "";
				if ($listado->SerieFactura!="") $texto_factura.=$listado->SerieFactura."-";
				$texto_factura.=$listado->NumeroFactura;
				$msg.=$texto_factura."</strong><br/>";
				$msg.= "Puede descargarla pulsando el siguiente enlace: <a href='http://".$_SERVER["SERVER_NAME"]."/administra/Gestion/factura_imprimible.php?Id=".$Id."&pdf=si&descargada=1&seguridad=".md5($Id.$listado->Fecha."SEMILLA123".$listado->Vencimiento)."&Idioma=".$Idioma."'>Descargar factura</a><br/>";
				$msg.= "Si existe alg&uacute;n inconveniente, p&oacute;ngase en contacto con nosotros.<br/> Un cordial saludo.<br/>";			
				$dominio = $_SERVER['SERVER_NAME'];
				$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= "From: Admin ".$dominio." <info@".$dominio.">\r\n";
				include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");			
				if (mail($correos[$indice_correos], "Factura ".$texto_factura, $mensaje, $headers))
				{
					$requete = "UPDATE `Facturas` SET `FechaEnvio`='".date("Y-m-d h:i:s")."' WHERE `Id`=".$Id;
					mysqli_query($db,$requete);
				}
			}
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Gestion&herramienta=facturas");
?>