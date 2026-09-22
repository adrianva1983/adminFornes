<?php
//VERSIÓN: v1.0 2014-03-10
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$idampliacion =$_POST["idampliacion"];
$ampliacion = $_POST["ampliacion"];
$contenido = $_POST["contenido"];
$Titulo = $_POST["Titulo"];
$Texto = $_POST["Texto"];
$seccion = $_POST["seccion"];
$ruta = $_POST["ruta"];
$Foto = $_FILES["Foto"];
$Alternativo = $_POST["Alternativo"];
$alineacion = $_POST["alineacion"];
$Retocar = $_POST["Retocar"];
$AnchoFoto = $_POST["AnchoFoto"];
$AnchoFoto2 = $_POST["AnchoFoto2"];
$AltoFoto = $_POST["AltoFoto"];
$AltoFoto2 = $_POST["AltoFoto2"];
$Redireccionar = $_POST["Redireccionar"];
$Fichero = $_FILES["Fichero"];
$TipoReproduccion = $_POST["TipoReproduccion"];
$VideoId = $_POST["VideoId"];
$FicheroServidor = $_FILES["FicheroServidor"];
$Latitud = $_POST["Latitud"];
$Longitud = $_POST["Longitud"];
$Zoom = $_POST["Zoom"];
$URLAmigable = $_POST["URLAmigable"];
$Validacion = $_POST["Validacion"];
$Aviso = $_POST["Aviso"];
$AvisoEmail = $_POST["AvisoEmail"];
$Votacion = $_POST["Votacion"];
$Busqueda = $_POST["Busqueda"];
$Respuestas = $_POST["Respuestas"];
$Max1Mensaje = $_POST["Max1Mensaje"];
$Anonimo = $_POST["Anonimo"];
$LecturaAnonimo = $_POST["LecturaAnonimo"];
$NivelEscritura = $_POST["NivelEscritura"];
$NivelLectura = $_POST["NivelLectura"];
$Enlace = $_POST["Enlace"];
$Tipo = $_POST["Tipo"];
$fechaComienzo = $_POST['fechaComienzo'];
$fechaFin = $_POST['fechaFin'];
$fechaComienzoHoras = $_POST['fechaComienzoHoras'];
$fechaFinHoras = $_POST['fechaFinHoras'];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT MAX(Orden) FROM Publicaciones WHERE IdAmpliacion='".$idampliacion."';";

if ($result = mysqli_query($db, $requete))
{
 $row = mysqli_fetch_row($result);
 $Orden=$row[0];
}
else
{
  $Orden=0;
}
$Orden++;
switch ($ampliacion) 
{
	case "texto":	
		$traduccion_texto = array("\"" => "\\\"");
		$Texto = strtr($Texto, $traduccion_texto);
		$requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\", `Breve` =\"".$Texto."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;		
		mysqli_query($db,$requete);
		break;
	case "imagen":
		//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen
		//SI HAY NUEVA IMAGEN		
		if ($Foto["name"]!="")
		{
			$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");		
			if ($Retocar=="no")
			{			
				$NomFicherin = strtr(str_replace(" ","_",$Foto["name"]), $traduccion);
				//Si el fichero existe le añadimos un valor numérico hasta que no exista
				if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFicherin))
				{
					$i="";
					for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$i.$NomFicherin));$j++)
					{
						$i = $j;
					}
					$NomFicherin = $i.$NomFicherin;
				}
				//Subimos el Fichero
				$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFicherin, "x");
				$localfile = file_get_contents($Foto["tmp_name"]);
				fwrite($ficherin, $localfile);
				fclose($ficherin);
				list($width, $height) = getimagesize($Foto["tmp_name"]);
				$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Foto` ='".$NomFicherin."', `AnchoFoto`='".$width."', `Redireccionar`='".$Redireccionar."', `AltoFoto`='".$height."', `Alternativo`='".$Alternativo."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' ";
				if ($alineacion!="") $requete .= ",`Breve` = '".$alineacion."' ";
				$requete.="WHERE `Id`=".$idampliacion;
				mysqli_query($db,$requete);
			}			
			else
			{
				$NomFoto = strtr(str_replace(" ","_",$Foto["name"]), $traduccion);
				switch ($Foto_type) 
				{
					case "image/jpeg":
					case "image/pjpeg":
						$imagen = imagecreatefromjpeg($Foto["tmp_name"]);
						$extension = "jpg";
					break;	
					case "image/gif":
						$imagen = imagecreatefromgif($Foto["tmp_name"]);
						$extension = "gif";
					break;
					case "x-png":
					case "image/x-png":
					case "image/png":
						$imagen = imagecreatefrompng($Foto["tmp_name"]);
						$extension = "png";
					break;
				}
				$NomFoto = str_replace(".".$extension, "", $NomFoto);
				$NomFoto = substr($NomFoto,0,120);
				$NomFoto = $idampliacion."-".$NomFoto;
				list($width, $height) = getimagesize($Foto["tmp_name"]);
				// Evitamos que la foto no se distorsione si fijamos o ancho o alto
				if (($AnchoFoto!="")&&($AltoFoto==""))
				{
					$proporcion = $width / $height;
					$AltoFoto = $AnchoFoto / $proporcion;
				}
				if (($AnchoFoto=="")&&($AltoFoto!=""))
				{
					$proporcion = $width / $height;
					$AnchoFoto = $AltoFoto * $proporcion;
				}
				if (($AnchoFoto2!="")&&($AltoFoto2==""))
				{
					$proporcion = $width / $height;
					$AltoFoto2 = $AnchoFoto2 / $proporcion;
				}
				if (($AnchoFoto2=="")&&($AltoFoto2!=""))
				{
					$proporcion = $width / $height;
					$AnchoFoto2 = $AltoFoto2 * $proporcion;
				}
				$imagen2 = imagecreatetruecolor($AnchoFoto,$AltoFoto);
				imagecopyresampled ($imagen2, $imagen, 0, 0, 0, 0, $AnchoFoto, $AltoFoto, $width, $height);
				if (($AnchoFoto2!="")&&(AltoFoto2!="")) 
				{
					$imagen3 = imagecreatetruecolor($AnchoFoto2,$AltoFoto2);
					imagecopyresampled ($imagen3, $imagen, 0, 0, 0, 0, $AnchoFoto2, $AltoFoto2, $width, $height);
				}
				//Si el fichero existe le añadimos un valor numérico hasta que no exista
				if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."_p.".$extension))
				{		
					$i="";
					for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.$i."_p.".$extension));$j++)
					{
						$i=$j;
					}
					$NomFoto = $NomFoto.$i;
				}             
				//Subimos la foto en la carpeta de fotos de la Web
				$NomFoto = $NomFoto."_p.".$extension;
				$NomFoto2 = $NomFoto.".".$extension;
				switch ($Foto["type"]) 
				{
					case "image/jpeg":
					case "image/pjpeg":
						imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
						if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagejpeg($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
						break;
					case "x-png":
					case "image/x-png":
					case "image/png":
						imagepng($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
						if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagepng($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
						break;
					case "image/gif":
						imagegif($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
						if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagegif($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
						break;
				}
				$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Foto` ='".$NomFoto."', `AnchoFoto`='".$AnchoFoto."', `AltoFoto`='".$AltoFoto."', `Redireccionar`='".$Redireccionar."',`Alternativo`='".$Alternativo."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' ";
				if ($AnchoFoto2!="") $requete .= "`Foto2` = '".$NomFoto2."', `AnchoFoto2` = '".$AnchoFoto2."', `AltoFoto2` ='".$AltoFoto2."' ";
				if ($alineacion!="") $requete .= ",`Breve` = '".$alineacion."' ";
				$requete.="WHERE `Id`=".$idampliacion;
				mysqli_query($db,$requete);
			}
		}
		else
		{
			$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Alternativo` = '".$Alternativo."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' ";
			if ($alineacion!="") $requete .= ",`Breve` = '".$alineacion."' ";
			$requete .= "WHERE `Id`=".$idampliacion;
			mysqli_query($db,$requete);
		}
		break;
   case "enlace":
     $requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\", `Redireccionar` =\"".$Redireccionar."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;
     mysqli_query($db,$requete);
   break;
   case "fichero":
		//SI HAY NUEVO FICHERO
		if ($Fichero["name"]!="")
		{
			$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");
			$NomFicherin = strtr(str_replace(" ","_",$Fichero["name"]), $traduccion);
			//Si el fichero existe le añadimos un valor numérico hasta que no exista
			if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin))
			{
				$i="";
				for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$i.$NomFicherin));$j++)
				{
					$i = $j;
				}
				$NomFicherin = $i.$NomFicherin;
			}       
			//Subimos el Fichero
			$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin, "x");
			$localfile = file_get_contents($Fichero["tmp_name"]);
			fwrite($ficherin, $localfile);
			fclose($ficherin);
			//Actualizamos la base de datos con la ampliación y la publicación
			$requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\", `Recurso` =\"".$NomFicherin."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;
			mysqli_query($db,$requete);
		}
		else
		{
			$requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;
			mysqli_query($db,$requete);
		}
		break;
   case "video":
     //SI HAY NUEVO VIDEO
	if (($Fichero!="")&&($TipoReproduccion=="servidor"))
	{
		$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");
		$NomFicherin = strtr(str_replace(" ","_",$Fichero["name"]), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin))
		{
			$i="";
			for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$i.$NomFicherin));$j++)
			{
				$i = $j;
			}
			$NomFicherin = $i.$NomFicherin;
		}
		//Subimos el Fichero
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin, "x");
		$localfile = file_get_contents($Fichero["tmp_name"]);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
		//Actualizamos la base de datos con la ampliación y la publicación
		$requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\", `Recurso` =\"".$NomFicherin."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;
		mysqli_query($db,$requete);
	}
	else
	{
		$requete = "UPDATE `Contenidos`  SET `Titulo` = \"".$Titulo."\",`Redireccionar` = \"".$TipoReproduccion."::".$VideoId."\", `AnchoFoto` = \"".$AnchoFoto."\", `AltoFoto` = \"".$AltoFoto."\", `IdPropietario` = \"".$_SESSION['usuario_id']."\", `FechaModificacion` = \"".date("Y-m-d h:i:s")."\" WHERE `Id`=".$idampliacion;
		mysqli_query($db,$requete);      
	}
	break;
   case "mapa":
	$requete = "UPDATE `Contenidos`  SET `Tipo`='mapa', `Titulo` = '".$Titulo."', `AnchoFoto2` ='".$Latitud."', `AltoFoto2` ='".$Longitud."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$idampliacion;
	mysqli_query($db,$requete);	
	break;
   case "ruta":
	//SI HAY NUEVO FICHERO
	if ($Fichero!="")
	{
		$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");
		$NomFicherin = strtr(str_replace(" ","_",$Fichero["name"]), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin))
		{
			$i="";
			for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Recursos/".$i.$NomFicherin));$j++)
			{
				$i = $j;
			}
			$NomFicherin = $i.$NomFicherin;
		}       
		//Subimos el Fichero
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Recursos/".$NomFicherin, "x");
		$localfile = file_get_contents($Fichero["tmp_name"]);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
		//Actualizamos la base de datos con la ampliación y la publicación	
		$requete = "UPDATE `Contenidos`  SET `Titulo`='".$Titulo."',`Recurso`='".$NomFicherin."',`AnchoFoto2`=".$Latitud.",`AltoFoto2`='".$Longitud."',`Breve`='".$Zoom."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$idampliacion;
		mysqli_query($db,$requete);
	}
	else
	{
		$requete = "UPDATE `Contenidos`  SET `Titulo`='".$Titulo."',`AnchoFoto2`=".$Latitud.",`AltoFoto2`='".$Longitud."',`Breve`='".$Zoom."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$idampliacion;
		mysqli_query($db,$requete);
	}
	break;   
	case "foro":
		$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Breve` ='".$Texto."', `FechaModificacion` = '".date("Y-m-d h:i:s")."', `URLAmigable` = '".$URLAmigable."' WHERE `Id`=".$idampliacion;
		mysqli_query($db,$requete);
		if ($Validacion) $requete = "UPDATE `ForoConfiguracion` SET `Validacion`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `Validacion`='no' WHERE `Id`='".$idampliacion."'";	
		mysqli_query($db,$requete);
		if ($Aviso) $requete = "UPDATE `ForoConfiguracion` SET `EnvioAviso`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `EnvioAviso`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($AvisoEmail) $requete = "UPDATE `ForoConfiguracion` SET `CorreoAviso`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `CorreoAviso`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($Votacion) $requete = "UPDATE `ForoConfiguracion` SET `Votacion`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `Votacion`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($Busqueda) $requete = "UPDATE `ForoConfiguracion` SET `BusquedaMensajes`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `BusquedaMensajes`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($Respuestas) $requete = "UPDATE `ForoConfiguracion` SET `PermitirRespuestas`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `PermitirRespuestas`='no' WHERE `Id`='".$idampliacion."'";
		if ($Max1Mensaje) $requete = "UPDATE `ForoConfiguracion` SET `Max1Mensaje`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `Max1Mensaje`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($Anonimo) $requete = "UPDATE `ForoConfiguracion` SET `ForoAnonimoEscritura`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `ForoAnonimoEscritura`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($LecturaAnonimo) $requete = "UPDATE `ForoConfiguracion` SET `ForoAnonimoLectura`='si' WHERE `Id`='".$idampliacion."'";
		else $requete = "UPDATE `ForoConfiguracion` SET `ForoAnonimoLectura`='no' WHERE `Id`='".$idampliacion."'";
		mysqli_query($db,$requete);
		if ($NivelLectura!="")
		{
			$requete = "UPDATE `ForoConfiguracion` SET `NivelLectura`='".$NivelLectura."' WHERE `Id`='".$idampliacion."'";
			mysqli_query($db,$requete);
		}
		if ($NivelEscritura!="")
		{
			$requete = "UPDATE `ForoConfiguracion` SET `NivelEscritura`='".$NivelEscritura."' WHERE `Id`='".$idampliacion."'";
			mysqli_query($db,$requete);
		}
	break;
	case "bloque":
		//SI HAY NUEVA IMAGEN
		if ($Foto!="")
		{
			$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");
			$NomFoto = strtr(str_replace(" ","_",$Foto["name"]), $traduccion);
			switch ($Foto["type"]) 
			{
				case "image/jpeg":
				case "image/pjpeg":
					$imagen = imagecreatefromjpeg($Foto["tmp_name"]);
					$extension = "jpg";
				break;	
				case "image/gif":
					$imagen = imagecreatefromgif($Foto["tmp_name"]);
					$extension = "gif";
				break;
				case "x-png":
				case "image/x-png":
				case "image/png":
					$imagen = imagecreatefrompng($Foto["tmp_name"]);
					$extension = "png";
				break;
			}
			$NomFoto = str_replace(".".$extension, "", $NomFoto);
			$NomFoto = substr($NomFoto,0,120);
			$NomFoto = $idampliacion."-".$NomFoto;
			list($width, $height) = getimagesize($Foto["tmp_name"]);
			// Evitamos que la foto no se distorsione si fijamos o ancho o alto
			if (($AnchoFoto!="")&&($AltoFoto==""))
			{
				$proporcion = $width / $height;
				$AltoFoto = $AnchoFoto / $proporcion;
			}
			if (($AnchoFoto=="")&&($AltoFoto!=""))
			{
				$proporcion = $width / $height;
				$AnchoFoto = $AltoFoto * $proporcion;
			}
			$imagen2 = imagecreatetruecolor($AnchoFoto,$AltoFoto);
			imagecopyresampled ($imagen2, $imagen, 0, 0, 0, 0, $AnchoFoto, $AltoFoto, $width, $height);
			//Si el fichero existe le añadimos un valor numérico hasta que no exista
			if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."_p.".$extension))
			{
				$i="";
				for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.$i."_p.".$extension));$j++)
				{
					$i=$j;
				}
				$NomFoto = $NomFoto.$i;
			}
			//Subimos la foto en la carpeta de fotos de la Web
			$NomFoto = $NomFoto."_p.".$extension;
			switch ($Foto["type"])
			{
				case "image/jpeg":
				case "image/pjpeg":
					imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
					break;
				case "x-png":
				case "image/x-png":
				case "image/png":
					imagepng($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
					break;
				case "image/gif":
					imagegif($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
				break;
			}
			$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Breve` ='".$Texto."', `Redireccionar` = '".$Enlace."', `Foto` ='".$NomFoto."', `AnchoFoto`='".$AnchoFoto."', `AltoFoto`='".$AltoFoto."', `Alternativo`='".$Tipo."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$idampliacion;
			mysqli_query($db,$requete);
		}
		else
		{
			$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Titulo."', `Breve` ='".$Texto."', `Redireccionar` = '".$Enlace."', `Alternativo`='".$Tipo."', `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$idampliacion;
			mysqli_query($db,$requete);
		}     
	break;   
}
//Actualizamos la fecha de modificación del contenido
$requete = "UPDATE `Contenidos`  SET `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$contenido;
mysqli_query($db,$requete);
if ($fechaComienzo!=''||$fechaFin!='')
{
	$requete = "UPDATE `Publicaciones` SET `FechaComienzo`=";
	if ($fechaComienzo!='') $requete.="'".$fechaComienzo." ".$fechaComienzoHoras."'";
	$requete.= ",`FechaFin`=";
	if ($fechaFin!='') $requete.="'".$fechaFin." ".$fechaFinHoras."'";
	$requete.=" WHERE `IdContenido`=".$idampliacion." AND `IdAmpliacion`=".$contenido;
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta);
?>