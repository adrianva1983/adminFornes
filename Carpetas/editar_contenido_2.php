<?php
date_default_timezone_set('Europe/Madrid');
//VERSIÓN: v1.1 2014-03-19
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion = $_POST["seccion"];
$Titulo = $_POST["Titulo"];
$TituloBuscadores = $_POST["TituloBuscadores"];
$DescripcionBuscadores = $_POST["DescripcionBuscadores"];
$Breve = $_POST["Breve"];
$Foto = $_FILES["Foto"];
$Icono = $_FILES["Icono"];
$referenciaIdioma = $_POST["referenciaIdioma"];
$codigoIdioma = $_POST["codigoIdioma"];
$Fecha = $_POST["Fecha"];
$Keywords = $_POST['Keywords'];
$Redireccionar = $_POST['Redireccionar'];
$TipoContenido = $_POST['TipoContenido'];
$IdContenido = $_POST['IdContenido'];
$IdUsuario = $_POST['IdUsuario'];
$URLAmigable = $_POST['URLAmigable'];
$ruta = $_POST['ruta'];
$Plantilla = $_POST['Plantilla'];
$Notas = $_POST['Notas'];
$Comportamiento = $_POST['Comportamiento'];	
$seccionOrigen = $_POST['seccionOrigen'];
$rutaOrigen = $_POST['rutaOrigen'];
$RedireccionarAparte = $_POST['RedireccionarAparte'];
$antigua = $_POST['antigua'];
$PlantillaAntigua = $_POST['PlantillaAntigua'];
$FotoServidor = $_POST['FotoServidor'];
$BorrarIcono = $_POST['BorrarIcono'];
$BorrarIconoFichero = $_POST['BorrarIconoFichero'];
$BorrarImagen = $_POST['BorrarImagen'];
$BorrarImagenFichero = $_POST['BorrarImagenFichero'];
$fechaComienzo = $_POST['fechaComienzo'];
$fechaFin = $_POST['fechaFin'];
$fechaComienzoHoras = $_POST['fechaComienzoHoras'];
$fechaFinHoras = $_POST['fechaFinHoras'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_contenido-".$_SESSION['idioma'].".conf");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//Calculamos el nombre de la carpeta que corresponde a la sección
$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "ã" => "a", "Ã" => "A", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" =>"", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
$NomFich = strtr(str_replace(" ","-",$Titulo), $traduccion);
$NomFich = substr($NomFich,0,100);
$NomFoto = strtr(str_replace(" ","-",$Foto['name']), $traduccion);

//Problemas con caracteres especiales
$traduccionCaracteres = array("'"=> "\'", "\""=> "\\\"");
$TituloBuscadores = strtr($TituloBuscadores, $traduccionCaracteres);
$DescripcionBuscadores = strtr($DescripcionBuscadores, $traduccionCaracteres);
$Titulo = strtr($Titulo, $traduccionCaracteres);
$Breve = strtr($Breve, $traduccionCaracteres);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Secciones` WHERE `Id` = '".$seccion."'";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	$listado = mysqli_fetch_object($result);
	$IdEntorno = $listado->IdEntorno;
}

switch ($Foto['type']) 
{
	case "image/pjpeg":
	case "image/jpeg":
	case "jpeg":
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
$NomFoto =$IdContenido."-".$NomFoto;

if (strlen($NomFich)==0) 
{
	print "<p>Necesito un Título para crear el contenido</p>";
	print "<p>Variable: Titulo vacia,  [".$_PUSH["Titulo"]."]</p>";
	die;
}
print "Titulo:".$Titulo."<br/>";
print "IDENTORNO:".$IdEntorno."<br/>";
print "antigua:".$antigua."<br/>";
print "NomFich:".$NomFich."<br/>";
$existeFoto="no";
if ((file_exists($Foto['tmp_name']))||($FotoServidor!=""))
{	
	if (isset($FotoServicor)&&$FotoServidor!="")
	{
		$NomFoto = $FotoServidor;
	}
	else
	{	
		$existeFoto="si";
		// Hacemos una consulta para saber el tamaño por defecto de las limágenes en listados del SERVIDOR O DEL ENTORNO
		if ($IdEntorno!="")
		{		
			$requete = "SELECT * FROM `Entornos` WHERE Id = '".$IdEntorno."'";		
			$result = mysqli_query($db,$requete);
			if (($result) && (mysqli_num_rows($result)>0))			
			{
				$listado = mysqli_fetch_object($result);
				if ($listado->ListadoFotoAncho!="") $AnchoFoto = $listado->ListadoFotoAncho;
				if ($listado->ListadoFotoAlto!="") $AltoFoto = $listado->ListadoFotoAlto;
			}
		}
		else
		{
			$requete = "SELECT * FROM `Servidor` WHERE Campo = 'LISTADO-FOTO-ANCHO'";
			$result = mysqli_query($db,$requete);
			if (($result) && (mysqli_num_rows($result)>0))
			{
				$listado = mysqli_fetch_object($result);
				$AnchoFoto = $listado->Valor;		
			}
			$requete = "SELECT * FROM `Servidor` WHERE Campo = 'LISTADO-FOTO-ALTO'";
			$result = mysqli_query($db,$requete);
			if (($result) && (mysqli_num_rows($result)>0))
			{
				$listado = mysqli_fetch_object($result);
				$AltoFoto = $listado->Valor;
			}
		}	
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
		if ($AnchoFoto==''&&$AltoFoto=='')
		{
			$AnchoFoto = $width;
			$AltoFoto = $height;
		}
		$imagen2 = imagecreatetruecolor($AnchoFoto,$AltoFoto);
		imagecopyresampled ($imagen2, $imagen, 0, 0, 0, 0, $AnchoFoto, $AltoFoto, $width, $height);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."p.".$extension))
		{
			for ($i=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.$i."p.".$extension));$i++)
			{
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
			case "jpeg":
				imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto,100);// 100 es máxima calidad
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
	}
}
else $NomFoto="";
if (file_exists($Icono['tmp_name']))
{	
	switch ($Icono['type']) 
	{
		case "image/svg+xml":
			$extension = "svg";
			break;
		case "image/pjpeg":
		case "image/jpeg":
		case "jpeg":			
			$extension = "jpg";
			break;
		case "image/gif":			
			$extension = "gif";
		break;
		case "x-png":
		case "image/x-png":
		case "image/png":			
			$extension = "png";
		break;
	}
	$IconoRuta =$_SERVER['DOCUMENT_ROOT']."/Imagenes/";
	$NomIcono = strtr(str_replace(" ","-",$Icono['name']), $traduccion);
	$NomIcono = str_ireplace(".".$extension, "", $NomIcono);
	$NomIcono = substr($NomIcono,0,20);
	if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."_p.".$extension))
	{
		for ($i=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomIcono.$i.".".$extension));$i++)
		{}
		$NomIcono = $NomIcono.$i;
	}
	//Subimos la foto en la carpeta de fotos de la Web
	$NomIcono = $NomIcono.".".$extension;	
	//Subimos el Fichero
	$ficherin = fopen($IconoRuta.$NomIcono, "x");
	$localfile = file_get_contents($Icono['tmp_name']);
	fwrite($ficherin, $localfile);
	fclose($ficherin);	
	list($width, $height) = getimagesize($Icono['tmp_name']);
	$AnchoIcono=$width;
	$AltoIcono=$height;
}
if (($PlantillaAntigua != $Plantilla)||($antigua!=$NomFich))
{
	//Actualizamos con la nueva plantilla en la publicación
	$requete ="UPDATE `Publicaciones` SET `Plantilla` = '".$Plantilla."' WHERE `IdSeccion` = '".$seccion."' AND `IdContenido` = '".$IdContenido."';";	
	mysqli_query($db,$requete);
}
//Actualizamos el comportamiento y fechas de publicación
$requete = "UPDATE `Publicaciones` SET `Comportamiento`=";
if ($Comportamiento=="Plantilla") $requete.="NULL";
else $requete.="'".$Comportamiento."'";
if ($fechaComienzo!='') $requete.=",`FechaComienzo`='".$fechaComienzo." ".$fechaComienzoHoras."'";
if ($fechaFin!='') $requete.=",`FechaFin`='".$fechaFin." ".$fechaFinHoras."'";
$requete.= " WHERE `IdSeccion`=".$seccion." AND `IdContenido`=".$IdContenido;
mysqli_query($db,$requete);
//Comprobamos si el contenido está publicado en más zonas y si se ha cambiado el nombre del fichero para cambiarlo en todas las publicaciones
// Si estamos en un ENTORNO no es necesario
if ($IdEntorno=="")
{
	$requete = "SELECT * FROM `Contenidos` WHERE `Id` = '".$IdContenido."';";
	$result = mysqli_query($db,$requete);
	if (($result) && (mysqli_num_rows($result)>0))		
	{
		$listado = mysqli_fetch_object($result);
		if ($listado->NomFich!=$NomFich)
		{
			$NomFichAntiguo = $listado->NomFich;
			//Miramos si hay más publicaciones del contenido ya que ha cambiado de nombre	
			$requete = "SELECT * FROM `Publicaciones` WHERE `IdContenido` = '".$IdContenido."';";
			$result = mysqli_query($db,$requete);
			if (($result) && (mysqli_num_rows($result)>1)) // No solo tenemos que ver que haya elementos sino que haya al menos 2
			{
				while ($listado = mysqli_fetch_object($result))
				{
					$requete2 = "SELECT * FROM `Secciones` WHERE `Id` = '".$listado->IdSeccion."';";
					$result2 = mysqli_query($db,$requete2);
					$listado2 = mysqli_fetch_object($result2);
					if ($Plantilla!="Contenido desplegado"&&($Comportamiento!="Plantilla")) // Si se aplica plantilla, guardamos el fichero con su plantilla.
					{
						if ($listado2->Path!="") 
						{
							$direccion = $_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado2->Path."/".$listado2->NomFich."/".$NomFichAntiguo.".php";
							$direccion2 = $_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado2->Path."/".$listado2->NomFich."/".$NomFich.".php";
						}
						else
						{	
							$direccion = $_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado2->NomFich."/".$NomFichAntiguo.".php";
							$direccion2 = $_SERVER['DOCUMENT_ROOT']."/Secciones/".$listado2->NomFich."/".$NomFich.".php";
						}
						if (!file_exists($direccion2)) rename($direccion,$direccion2);
					}
					else
					{
						if (file_exists ($direccion)) unlink($direccion);
					}
				}
			}
		}
	}
}
//Borramos icono y/o foto si corresponde
if (isset($BorrarImagen) && $BorrarImagen !="")
{	
	if ($BorrarImagenFichero!=""&&file_exists($_SERVER['DOCUMENT_ROOT'].$BorrarImagenFichero)) unlink($_SERVER['DOCUMENT_ROOT'].$BorrarImagenFichero);
}
if (isset($BorrarIcono) && $BorrarIcono !="")
{
	if ($BorrarIconoFichero!=""&&file_exists($_SERVER['DOCUMENT_ROOT'].$BorrarIconoFichero)) unlink($_SERVER['DOCUMENT_ROOT'].$BorrarIconoFichero);
}
//Cargamos el contenido en la base de datos
	$requete = "UPDATE `Contenidos` SET `Titulo` = '".$Titulo."', `Breve` ='".$Breve."', `Keywords` = '".$Keywords."', `NomFich` = '".$NomFich."', `Redireccionar` = '".$Redireccionar;
	if ($RedireccionarAparte) $requete.="|_blank";
	$requete.="', `IdTipoContenido`='".$TipoContenido."',`FechaModificacion`='".date("Y-m-d h:i:s")."',`TituloBuscadores`='".$TituloBuscadores."',`DescripcionBuscadores`='".$DescripcionBuscadores."',`URLAmigable`='".$URLAmigable."'";
	if ($NomFoto!="") $requete = $requete.", `Foto`='".$NomFoto."'";
	else
	{
		if (isset($BorrarImagen) && $BorrarImagen !="")
		{
			$requete.= ",`Foto` = NULL";
		}
	}
	if ($NomIcono!="") $requete = $requete.", `Icono`='".$NomIcono."'";
	if ($Fecha!="") $requete = $requete.", `Fecha`='".$Fecha."'";
	else $requete = $requete.", `Fecha`= NULL";
	$requete = $requete." WHERE `Id` ='".$IdContenido."';";		
	mysqli_query($db,$requete);	
//Actualización de campos de publicación
if ($Notas!="")
{
	$requete ="UPDATE `Publicaciones` SET `Notas` = '".$Notas."' WHERE `IdSeccion` = '".$seccion."' AND `IdContenido` = '".$IdContenido."';";
	mysqli_query($db,$requete);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el directorio en curso
if (($seccion!="")&&($codigoIdioma==""))
{
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$TipoContenido);
}
else 
{
	if ($codigoIdioma!="") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccionOrigen."&ruta=".$rutaOrigen);
	else header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>