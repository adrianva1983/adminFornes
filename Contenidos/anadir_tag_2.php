<?php
//VERSIÓN: v1.0 2014-5-7
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$id = $_POST["id"];
$origen = $_POST["origen"];
$Idioma = $_POST["Idioma"];
$IdPadre = $_POST["IdPadre"];
$Nombre = $_POST["Nombre"];
$Sinonimos = $_POST["Sinonimos"];
$URL = $_POST["URL"];
$Imagen = $_FILES["Imagen"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/anadir_tag-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print $lang["errorPermisos"];
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ($lang["accesoIncorrecto"]);
	exit;
}
if ($Imagen['tmp_name']!="")
{
	$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
	$NomFoto = strtr(str_replace(" ","_",$Imagen['name']), $traduccion);
	switch ($Imagen['type']) 
	{
		case "image/pjpeg":
		case "image/jpeg":
			$imagen = imagecreatefromjpeg($Imagen['tmp_name']);
			$extension = "jpg";
			break;
		case "image/gif":
			$imagen = imagecreatefromgif($Imagen['tmp_name']);
			$extension = "gif";
			break;
		case "x-png":
		case "image/x-png":
		case "image/png":
			$imagen = imagecreatefrompng($Imagen['tmp_name']);
			$extension = "png";
			break;
	}
	$NomFoto = str_replace(".".$extension, "", $NomFoto);
	$NomFoto = substr($NomFoto,0,120);
	$NomFoto = "NA".rand(1, 10000)."-".$NomFoto;
	$AnchoFoto = 150;
	list($width, $height) = getimagesize($Imagen['tmp_name']);
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
	if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Etiquetas/".$NomFoto.".".$extension))
	{		
		$i="";
		for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Etiquetas/".$NomFoto.$i.".".$extension));$j++)
		{
			$i=$j;
		}
		$NomFoto = $NomFoto.$i;
	}
	$NomFoto = "/Imagenes/Etiquetas/".$NomFoto.".".$extension;
	switch ($Imagen['type']) 
	{
		case "image/pjpeg":
		case "image/jpeg":
			imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT'].$NomFoto);			
			break;
		case "image/gif":
			imagegif($imagen2,$_SERVER['DOCUMENT_ROOT'].$NomFoto);			
			break;
		case "x-png":
		case "image/x-png":
		case "image/png":
			imagepng($imagen2,$_SERVER['DOCUMENT_ROOT'].$NomFoto);			
			break;
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "INSERT INTO `Tags` (`Nombre`";
if ($Sinonimos!="") $requete.=",`Sinonimos`";
if ($IdPadre!="") $requete.=",`IdPadre`";
if ($URL!="") $requete.=",`URL`";
if ($NomFoto!="") $requete.=",`Imagen`";
$requete.= ") VALUES ('".$Nombre."'";
if ($Sinonimos!="") $requete.=",'".$Sinonimos."'";
if ($IdPadre!="") $requete.=",".$IdPadre;
if ($URL!="") $requete.=",'".$URL."'";
if ($NomFoto!="") $requete.=",'".$NomFoto."'";
$requete.=");";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Contenidos&herramienta=tipo_contenidos");
?>