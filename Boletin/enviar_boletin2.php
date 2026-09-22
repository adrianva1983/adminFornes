<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/enviar_boletin2-".$_SESSION['idioma'].".conf");
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0];	
	return $lafecha;
}
$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `Id`='".$idboletin."';";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
$asunto = utf8_encode($listado->Asunto);
$email_remitente = $listado->RemitenteMail;
$remitente = $listado->Remitente;
//Cargamos los encabezados del mail
$dominio = $_SERVER['SERVER_NAME'];
$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= "From: $remitente <$email_remitente>\r\n";
//Miro el total de mensajes del boletín
$requete = "SELECT * FROM `BoletinUsuarios`,`Usuarios` WHERE IdBoletin=".$idboletin." AND IdUsuario=`Usuarios`.Id;";

$totalEnviar = mysqli_num_rows($result);
$requete = "SELECT * FROM `BoletinUsuarios`,`Usuarios` WHERE IdBoletin=".$idboletin." AND IdUsuario=`Usuarios`.Id AND `Estado`='Pendiente' LIMIT 0,".$numEnvios.";";

print "<p>".$lang["espere"].":</p>";
if ($result = mysqli_query($db, $requete))
{
	$totalPendientesEnviar = mysqli_num_rows($result);
	$cuantoVa = $totalEnviar - $totalPendientesEnviar;
	$totalBarras = 0;
	print "<p>".$lang["enviadoYa"]." <strong>".$cuantoVa."</strong> ".$lang["mensajes"]." ".$lang["deTotal"]." <strong>".$totalEnviar."</strong></p>";
	print "<div style=\"clear:both;width:720px;margin:10px 5px; padding:0px;background:url('/administra/Imagenes/barra-gris.gif') repeat-x scroll right center transparent;height:20px;\">";
	//Tiene que haber 40 barritas en ese tramo para llegar al 100%
	$unoPorCien = ceil($totalPendientesEnviar/40);
	if ($unoPorCien == 1) $pasosPorUno = ceil(1/($totalPendientesEnviar/40));			
	//Tiene que haber 40 barritas en ese tramo para llegar al 100%
	$requete2 = "SELECT * FROM `BoletinConfiguracion` WHERE Id=".$idboletin.";";
	
	$listado2 = mysqli_fetch_object($result2);
	$NumEnvios = 0;			
	flush();
	ob_flush();
	$unoPorCienVa = 1;
	while ($listado = mysqli_fetch_object($result))
	{
		if  ($unoPorCien==1) //Se pinta más de una barra por envío
		{
			for($i=1;($i<$pasosPorUno);$i++)
			{
				$totalBarras++;
				echo "<div style='float:left;margin:0px;padding:0px;width:18px;
				height:20px;'><img src=\"/administra/Imagenes/barra-roja.gif\"/></div>";
			}
			flush();
			ob_flush();
		}
		else //Se Pintan x barras por envío
		{
			if ($unoPorCienVa > $unoPorCien)
			{
				$unoPorCienVa=1;
				$totalBarras++;
				echo "<div style='float:left;margin:0px;padding:0px;width:18px;
				height:20px;'><img src=\"/administra/Imagenes/barra-roja.gif\"/></div>";
				flush();
				ob_flush();
			}
		}
		$unoPorCienVa++;
		$Id=$idboletin;
		$Usuario=$listado->IdUsuario;
		$Email =$listado->Email;
		include($_SERVER['DOCUMENT_ROOT']."/Plantillas/boletin_electronico.php");
		if (mail($Email, $asunto, $mensaje, $headers))
		{
			$requete2 ="UPDATE `BoletinUsuarios` SET `Estado` = 'Enviado' WHERE `IdUsuario` = '".$Usuario."' AND `IdBoletin` = '".$idboletin."'";
			$NumEnvios++;
		}
		else
		{
			$requete2 ="UPDATE `BoletinUsuarios` SET `Estado` = 'Fallido' WHERE `IdUsuario` = '".$Usuario."' AND `IdBoletin` = '".$idboletin."'";			
		}		
		mysql_query($requete2,$db);
	}	
	$requete2 ="UPDATE `BoletinConfiguracion` SET `FechaEnvio` = '".date("Y-m-d")."', `NumeroEnviados`='".$NumEnvios."' WHERE `Id` = '".$idboletin."';";
	mysql_query($requete2,$db);
}
else
{
	$cuantoVa = $totalEnviar - $totalPendientesEnviar;
	print "<p>".$lang["enviadoYa"]." <strong>".$cuantoVa."</strong> ".$lang["mensajes"]." ".$lang["deTotal"]." <strong>".$totalEnviar."</strong></p>";
	print "<div style=\"clear:both;width:720px;margin:10px 5px; padding:0px;background:url('/administra/Imagenes/barra-gris.gif') repeat-x scroll right center transparent;height:20px;\">";
}
for(;($totalBarras<40);$totalBarras++) //Pinto barras para completar el ancho entero
{
	echo "<div style='float:left;margin:0px;padding:0px;width:18px;
		height:20px;'><img src=\"/administra/Imagenes/barra-roja.gif\"/></div>";
}
print "</div><p>".$lang["correcto"].": ".$NumEnvios." ".$lang["enviados"].".</p>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el boletin contenido en curso
print "<a href=\"../Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."\">".$lang["estadisticas"]."</a> - <a href=\"../Interface/herramienta.php?modulo=Boletin&herramienta=boletines\">".$lang["boletines"]."</a>";
?>