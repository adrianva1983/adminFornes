<?php
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
$requete = "SELECT MAX(Orden) FROM Publicaciones WHERE IdAmpliacion='".$idencuesta."';";

if ($result = mysqli_query($db, $requete))
{
 $row = mysql_fetch_row($result);
 $Orden=$row[0];
}
else
{
  $Orden=0;
}
$Orden++;

$requete = "UPDATE `Contenidos`  SET `Titulo` = '".$Opcion."', `AnchoFoto`='".$AnchoFoto."', `AltoFoto`='".$AltoFoto."', `Alternativo`='".$Alternativo."' WHERE `Id`=".$idencuesta;
mysqli_query($db,$requete);

//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen
//SI HAY NUEVA IMAGEN
if ($Foto!="")
{
	$traduccion = array(" " => "_", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "/" => "", ":" => "", "*" => "", "?" => "", ">" => "", "<" => "", "\"" => "", "|" => "");
	$NomFoto = strtr(str_replace(" ","_",$Foto_name), $traduccion);
	switch ($Foto_type) 
	{
	case "image/pjpeg":
	  $imagen = imagecreatefromjpeg($Foto);
	  $extension = "jpg";
	  break;
	case "image/gif":
	  $imagen = imagecreatefromgif($Foto);
	  $extension = "gif";
	  break;
	case "x-png":
	  $imagen = imagecreatefrompng($Foto);
	  $extension = "png";
	  break;
	}
	$NomFoto = str_replace(".".$extension, "", $NomFoto);
	$NomFoto = substr($NomFoto,0,20);
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
	if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."p.".$extension))
	{
		$i=0;
		while (file_exists($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."p.".$extension))
		{
		 $NomFoto = $i.$NomFoto;
		 $i++;
		}
	}
	//Subimos la foto en la carpeta de fotos de la Web
	$NomFoto = $NomFoto."_p.".$extension;
	$NomFoto2 = $NomFoto.".".$extension;
	if ($Foto_type=="image/pjpeg")
	{
	  imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
	}
	if ($Foto_type=="image/gif")
	{
	  imagegif($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
	}
	if ($Foto_type=="x-png")
	{
	  imagepng($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto);
	}
	$requete = "UPDATE `Contenidos`  SET `Foto` ='".$NomFoto."' WHERE `Id`=".$idencuesta;
	mysqli_query($db,$requete);

}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos la encuesta en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=encuestas&encuesta=".$encuesta."&seccion=".$seccion."&ruta=".$ruta);
?>