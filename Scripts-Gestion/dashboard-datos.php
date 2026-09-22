<?php
$resultados = array(); 
/* Extrae los valores enviados desde la aplicacion movil */
$id_usuario = $_GET['id_usuario'];
$hash = $_GET['hash'];

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$loginOK = true;
//Nos aseguramos de que no nos suplantan al usuario
$requete = "SELECT * FROM `Usuarios` WHERE `Id`= ".$id_usuario;	

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if (md5($listado->FechaUltimoAcceso.$id_usuario)!=$hash)
	{
		$resultados["mensaje"] = "Fallo sistema seguridad";
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
}
else 
{
	$resultados["mensaje"] = "Usuario inexistente";
	$resultados["validacion"] = "error";
	$loginOK = false;
}
if ($loginOK)
{
	$usuario_consulta = mysql_query("SELECT Usuarios.NivelAcceso,Usuarios.Nombre,Usuarios.Apellidos,Usuarios.Foto FROM Usuarios WHERE Usuarios.Id=".$id_usuario);
	if (mysqli_num_rows($usuario_consulta) > 0) 
	{
		$i = 0;
		// almacenamos datos del Usuario en un array para empezar a chequear.
		$usuario_datos = mysql_fetch_array($usuario_consulta);
		$resultados["Nombre"] = utf8_encode($usuario_datos["Nombre"]);
		$resultados["Apellidos"] = utf8_encode($usuario_datos["Apellidos"]);
		if ($usuario_datos["Foto"]!="") $resultados["Foto"] = $usuario_datos["Foto"];
		$resultados["NivelAcceso"] = $usuario_datos["NivelAcceso"];
		if ($usuario_datos["NivelAcceso"]<=1)
		{
			$mes = date("m");			
			$ano = date("Y");
			if ($mes == 12)
			{
				$mes2 = 1;
				$ano2=$ano+1;
			}
			else
			{
				$mes2= $mes+1;
				$ano2 = $ano;
			}
			$consulta = mysql_query("SELECT Count(*) AS num FROM Presupuestos WHERE Fecha>='".$ano."-".$mes."-1' AND Fecha<'".$ano2."-".$mes2."-1';");			
			$datos = mysql_fetch_array($consulta);
			$resultados["NumeroPresupuestos"] = $datos["num"];
			$consulta = mysql_query("SELECT SUM(PresupuestosLineas.BaseImponible) AS suma FROM Presupuestos,PresupuestosLineas WHERE PresupuestosLineas.IdPresupuesto = Presupuestos.Id AND Presupuestos.Fecha>='".$ano."-".$mes."-1' AND Presupuestos.Fecha<'".$ano2."-".$mes2."-1';");
			$datos = mysql_fetch_array($consulta);
			$resultados["ImportePresupuestos"] = number_format($datos["suma"],2,',','.')."&euro;";
			$consulta = mysql_query("SELECT SUM(FacturasLineas.BaseImponible) AS suma FROM Facturas,FacturasLineas WHERE Facturas.IdEstado=1 AND FacturasLineas.IdFactura = Facturas.Id AND Facturas.Fecha>='".$ano."-".$mes."-1' AND Facturas.Fecha<'".$ano2."-".$mes2."-1' AND `Rectificativa`<>1;");
			$datos = mysql_fetch_array($consulta);
			$resultados["ImporteFacturas"] = number_format($datos["suma"],2,',','.')."&euro;";
			$consulta = mysql_query("SELECT SUM(FacturasLineas.BaseImponible) AS suma FROM Facturas,FacturasLineas WHERE Facturas.IdEstado=2 AND FacturasLineas.IdFactura = Facturas.Id  AND `Rectificativa`<>1;");
			$datos = mysql_fetch_array($consulta);
			$resultados["ImporteFacturasFacturable"] = number_format($datos["suma"],2,',','.')."&euro;";
			$consulta = mysql_query("SELECT SUM(FacturasLineas.BaseImponible) AS suma FROM Facturas,FacturasLineas WHERE Facturas.IdEstado=1 AND FacturasLineas.IdFactura = Facturas.Id AND Facturas.Saldada<>1  AND `Rectificativa`<>1;");
			$datos = mysql_fetch_array($consulta);
			$resultados["ImporteFacturasPendientesPago"] = number_format($datos["suma"],2,',','.')."&euro;";
			$consulta = mysql_query("SELECT SUM(FacturasLineas.BaseImponible) AS suma FROM Facturas,FacturasLineas WHERE Facturas.IdEstado=3 AND FacturasLineas.IdFactura = Facturas.Id;");
			$datos = mysql_fetch_array($consulta);
			$resultados["ImporteFacturasPrevisto"] = number_format($datos["suma"],2,',','.')."&euro;";
		}
	}
	else
	{
		$resultados["mensaje"] = "No hay resultados";
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
}
/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>