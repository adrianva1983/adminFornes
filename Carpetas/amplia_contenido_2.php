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
//DEFINICIÓN VARIABLES
$Foto = $_FILES["Foto"];
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
//Problemas con caracteres especiales
$traduccionCaracteres = array("'"=> "\'", "\""=> "\\\"");
$TituloBuscadores = strtr($TituloBuscadores, $traduccionCaracteres);
$DescripcionBuscadores = strtr($DescripcionBuscadores, $traduccionCaracteres);
$Titulo = strtr($Titulo, $traduccionCaracteres);
$Texto = strtr($Texto, $traduccionCaracteres);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT Idioma FROM Contenidos WHERE Id='".$contenido."';";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	$listado = mysqli_fetch_object($result);
	if ($codigoIdioma=="") $codigoIdioma= $listado->Idioma;
}


$requete = "SELECT MAX(Orden) FROM Publicaciones WHERE IdAmpliacion='".$contenido."';";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
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
		$traduccion_texto = array("\"" => "\"");
		$Texto = strtr($Texto, $traduccion_texto);
		$Titulo = strtr($Titulo, $traduccion_texto);
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Breve`,`Tipo`";		
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .= ") VALUES (\"".$contenido."\", \"".$Titulo."\", \"".$Texto."\", \"texto\"";
		if ($referenciaIdioma!="") $requete .= ", \"".$referenciaIdioma."\"";
		if ($codigoIdioma!="") $requete .= ",\"".$codigoIdioma."\"";     
		$requete .=");";		
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "imagen":		
		//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen
		$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
		if ($Retocar=="no")
		{			
			$NomFicherin = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
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
			$localfile = file_get_contents($Foto['tmp_name']);
			fwrite($ficherin, $localfile);
			fclose($ficherin);
			list($width, $height) = getimagesize($Foto['tmp_name']);
			$NomFoto = $NomFicherin;
			$AnchoFoto = $width;
			$AltoFoto = $height;
			$AnchoFoto2 = "";
			$AltoFoto2 = "";
		}
		else
		{
			$NomFoto = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
			switch ($Foto['type']) 
			{
				case "image/pjpeg":
				case "image/jpeg":
					$imagen = imagecreatefromjpeg($Foto['tmp_name']);
					$extension = "jpg";
					break;
				case "image/gif":
					$imagen = imagecreatefromgif($Foto['tmp_name']);
					$extension = "gif";
					break;
				case "x-png":
				case "image/x-png":
				case "image/png":
					$imagen = imagecreatefrompng($Foto['tmp_name']);
					$extension = "png";
					break;
			}
			$NomFoto = str_replace(".".$extension, "", $NomFoto);
			$NomFoto = substr($NomFoto,0,120);
			$NomFoto = "NA".rand(1, 10000)."-".$NomFoto;
			list($width, $height) = getimagesize($Foto['tmp_name']);
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
			//Si el fichero de foto ampliada existe le añadimos un valor numérico hasta que no exista
			if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.".".$extension))
			{
				$i="";
				for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.$i.".".$extension));$j++)
				{
					$i=$j;		
				}
				$NomFoto = $NomFoto.$i;
			}	
			//Subimos la foto en la carpeta de fotos de la Web
			$NomFoto = $NomFoto."_p.".$extension;
			$NomFoto2 = $NomFoto.".".$extension;
			switch ($Foto['type']) 
			{
				case "image/pjpeg":
				case "image/jpeg":
					imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
					if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagejpeg($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
					break;
				case "image/gif":
					imagegif($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
					if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagegif($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
					break;
				case "x-png":
				case "image/x-png":
				case "image/png":
					imagepng($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
					if (($AnchoFoto2!="")&&(AltoFoto2!="")) {imagepng($imagen3,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto2);}
					break;
			}
		}	
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Foto`, `AnchoFoto`, `AltoFoto`, `Foto2`, `AnchoFoto2`, `AltoFoto2`, `Alternativo`, `Redireccionar`, `Tipo`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		if ($alineacion!="") $requete .= ",`Breve`";
		$requete .= ") VALUES ('".$contenido."', '".$Titulo."', '".$NomFoto."', '".$AnchoFoto."', '".$AltoFoto."', '".$NomFoto2."', '".$AnchoFoto2."', '".$AltoFoto2."', '".$Alternativo."', '".$Redireccionar."', 'imagen'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		if ($alineacion!="") $requete .= ",'".$alineacion."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "enlace":
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Redireccionar`,`Tipo`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$Redireccionar."', 'enlace'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "fichero":
		$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
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
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Recurso`,`Tipo`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$NomFicherin."','fichero'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "video":
   		if (($FicheroServidor=="")&&($VideoId=="")&&($TipoReproduccion=="servidor")&&($Fichero!=NULL)&&($Fichero!=""))
		{
			$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
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
		}
		else $NomFicherin = $FicheroServidor;
		//Actualizamos la base de datos con la ampliación y la publicación
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Recurso`,`AnchoFoto`,`AltoFoto`,`Tipo`,`Redireccionar`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$NomFicherin."', '".$AnchoFoto."', '".$AltoFoto."', 'video','".$TipoReproduccion."::".$VideoId."'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "mapa":
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`AnchoFoto2`, `AltoFoto2`,`Tipo`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$Latitud."', '".$Longitud."', 'mapa'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "ruta":
		$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
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
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`AnchoFoto2`, `AltoFoto2`,`Tipo`,`Breve`,`Recurso`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$Latitud."', '".$Longitud."', 'ruta', '".$Zoom."','".$NomFicherin."'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		break;
	case "foro":
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Breve`,`Tipo`";
		if ($URLAmigable!="") $requete .= ", `URLAmigable`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$Texto."', 'foro'";
		if ($URLAmigable!="") $requete .= ", '".$URLAmigable."'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."');";
		mysqli_query($db,$requete);
		$resultados = ") VALUES (";
		if ($Validacion) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($Aviso) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($AvisoEmail) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($Votacion) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($Busqueda) $resultados = $resultados."'si".$Busqueda."', ";
		else $resultados = $resultados."'no', ";
		if ($Respuestas) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($Max1Mensaje) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($Anonimo) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		if ($LecturaAnonimo) $resultados = $resultados."'si', ";
		else $resultados = $resultados."'no', ";
		$requete = "INSERT INTO `ForoConfiguracion` (`Validacion`,`EnvioAviso`,`CorreoAviso`,`Votacion`,`BusquedaMensajes`,`PermitirRespuestas`,`Max1Mensaje`,`ForoAnonimoEscritura`,`ForoAnonimoLectura`,`NivelEscritura`,`NivelLectura`,`Id`".$resultados."'".$NivelEscritura."', '".$NivelLectura."', '".$IdAmpliacion."');";
		mysqli_query($db,$requete);
	break;
	case "bloque":
		//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen
		$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
		$NomFoto = strtr(str_replace(" ","_",$Foto["name"]), $traduccion);
		switch ($Foto["type"]) 
		{
			case "image/pjpeg":
			case "image/jpeg":
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
		$NomFoto = "NA".rand(1, 10000)."-".$NomFoto;
		list($width, $height) = getimagesize($Foto);
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
		$NomFoto2 = $NomFoto.".".$extension;
		switch ($Foto["type"]) 
		{
			case "image/pjpeg":
			case "image/jpeg":
				imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
				break;
			case "image/gif":
				imagegif($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
				break;
			case "x-png":
			case "image/x-png":
			case "image/png":
				imagepng($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
				break;
		}
		$requete = "INSERT INTO `Contenidos` (`IdPadre`,`Titulo`,`Breve`,`Foto`, `AnchoFoto`, `AltoFoto`, `Alternativo`, `Redireccionar`, `Tipo`";
		if ($referenciaIdioma!="") $requete .= ",`RelacionIdioma`";
		if ($codigoIdioma!="") $requete .= ",`Idioma`";
		$requete .=") VALUES ('".$contenido."', '".$Titulo."', '".$Texto."', '".$NomFoto."', '".$AnchoFoto."', '".$AltoFoto."', '".$Tipo."', '".$Enlace."', 'bloque'";
		if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
		if ($codigoIdioma!="") $requete .= ",'".$codigoIdioma."'"; 
		else $requete .= ",'ES-ES'";
		$requete .=");";
		mysqli_query($db,$requete);
		$IdAmpliacion = mysqli_insert_id($db);
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`, `IdAmpliacion`, `Orden`,`FechaComienzo`,`FechaFin`) VALUES ('".$IdAmpliacion."', '".$contenido."', '".$Orden."'";
		if ($fechaComienzo!='') $requete.=",'".$fechaComienzo." ".$fechaComienzoHoras."'";
		else $requete.=",NULL";
		if ($fechaFin!='') $requete.=",'".$fechaFin." ".$fechaFinHoras."'";
		else $requete.=",NULL";
		$requete.=");";
		mysqli_query($db,$requete);
		break;
}
//Actualizamos la fecha de modificación del contenido
$requete = "UPDATE `Contenidos`  SET `FechaModificacion` = '".date("Y-m-d h:i:s")."' WHERE `Id`=".$contenido;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el contenido en curso
if ($referenciaIdioma=="") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
else header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenidoOrigen."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
?>