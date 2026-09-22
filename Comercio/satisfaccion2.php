<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
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
function cambiaf_a_normal($fecha){
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0]." ".$mifecha[1];	
	return $lafecha;
}
function mandarMail($EmailUsuario,$IdUsuario,$IdRestaurante,$IdReserva,$dia,$db)
{
	//Sacamos el email del entorno
	$requete_enviar_encuesta2 = "SELECT `Valor` FROM `Servidor` WHERE `Campo` = 'Mail Entorno'";
	$result_enviar_encuesta2 = mysql_query($requete_enviar_encuesta2,$db);
	if (($result_enviar_encuesta2) && (mysqli_num_rows($result_enviar_encuesta2)>0))
	{
		$listado_enviar_encuesta2 = mysql_fetch_object($result_enviar_encuesta2);
		$mail_entorno = $listado_enviar_encuesta2->Valor;
	}
	//Cargamos los encabezados del mail
	$dominio = $_SERVER['SERVER_NAME'];
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: $dominio <$mail_entorno>\r\n";
	//Cargamos la información del restaurante
	$requete_enviar_encuesta2 = "SELECT * FROM `Contenidos` WHERE `Id` = ".$IdRestaurante;
	$result_enviar_encuesta2 = mysql_query($requete_enviar_encuesta2,$db);
	if (($result_enviar_encuesta2) && (mysqli_num_rows($result_enviar_encuesta2)>0))
	{		
		$listado_enviar_encuesta2 = mysql_fetch_object($result_enviar_encuesta2);
		require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/satisfaccion2-".$listado_enviar_encuesta2->Idioma.".conf");
		$msg = "<table border=\"0px\" cellpading=\"5px\" cellspacing=\"5px\"><tr><td><img src=\"/herramientas/estadisticas_reservas.php?imagen=/Plantillas/Imagenes/opiniones-satisfaccion.jpg&idreserva=".$IdReserva."&tipo=encuestaRestaurante\"/></td><td>";
		//Enviamos la  solicitud de encuesta
		$msg .= "<p>".$lang["textoMail1"]." <strong>".$listado_enviar_encuesta2->Titulo."</strong> ".$lang["textoMail2"]." ".$dia."</p>";
		$msg .="<p>".$lang["textoMail3"]."</p>";
		$requete_enviar_encuesta3 = "SELECT * FROM `Contenidos` WHERE `IdPadre` = ".$IdRestaurante." AND `Tipo`='foro'";
		$result_enviar_encuesta3 = mysql_query($requete_enviar_encuesta3,$db);
		if (($result_enviar_encuesta3) && (mysqli_num_rows($result_enviar_encuesta3)>0))
		{
			$listado_enviar_encuesta3 = mysql_fetch_object($result_enviar_encuesta3);
			if ($listado_enviar_encuesta3->URLAmigable!="") $msg .="<p><strong><a href=\"http://".$_SERVER['SERVER_NAME'].$listado_enviar_encuesta3->URLAmigable."?idusuario=".$IdUsuario."#comentarios\" target=\"_blank\">".$lang["textoEnlace"]."</a></strong></p>";
			else $msg .="<p><strong><a href=\"/Plantillas/opiniones.php?Id=".$listado_enviar_encuesta3->Id."&idusuario=".$IdUsuario."#comentarios\" target=\"_blank\">".$lang["textoEnlace"]."</a></strong></p>";
			$msg .= "</td></tr></table>";
			include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");
			mail($EmailUsuario, $lang["tituloMail"]." ".$listado_enviar_encuesta2->Titulo, $mensaje, $headers);
		}
	}
}
$requete = "SELECT * FROM `".$TipoActividad."` WHERE";
if ($TipoActividad=="guiarestaurantes_reservas")
{
	$ahora = time();
	$FechaAhora = date('Y-m-d H:i:s',$ahora);
	$requete.=" (`Email`<>'' AND `Email` IS NOT NULL) AND (`IdUsuario`!=0 AND `IdUsuario` IS NOT NULL) AND `Estado`='Confirmada' AND `EnviadaSatisfaccion` IS NULL AND `Fecha`<'".$FechaAhora."'";
	if ($Fecha!="")
	{
		$requete.=" AND `Fecha`>'".$Fecha." 00:00:00'";		
	}	
	
	if ($result = mysqli_query($db, $requete))
	{		
		while ($listado = mysqli_fetch_object($result))
		{			
			//Miro el máximo de encuestas por usuario
			$requete2 = "SELECT `Id` FROM `guiarestaurantes_reservas` WHERE `IdUsuario`=".$listado->IdUsuario." AND `IdRestaurante`=".$listado->IdRestaurante;
			
			if (($result2) && (mysqli_num_rows($result2)>0) &&(mysqli_num_rows($result2)>=$MaxPorUsuario))
			{
				$test = true;
				if ($test)
				{
					$test = false;
					mandarMail($listado->Email,$listado->IdUsuario,$listado->IdRestaurante,$listado->Id,cambiaf_a_normal($listado->Fecha),$db);					
					$ahora = time();
					$FechaEncuesta = date('Y-m-d H:i:s',$ahora);
					$requete2 = "UPDATE `guiarestaurantes_reservas`  SET `EnviadaSatisfaccion` = '".$FechaEncuesta."' WHERE `Id`= '".$listado->Id."'";
					mysql_query($requete2,$db);
				}
			}
		}
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Comercio&herramienta=reservas");
?>