<?php
//VERSIÓN: v1.0 2015-06-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Titulo = $_POST["Titulo"];
$NomFich = $_POST["NomFich"];
$TituloBuscadores = $_POST["TituloBuscadores"];
$DescripcionBuscadores = $_POST["DescripcionBuscadores"];
$Texto = $_POST["Texto"];
$antigua = $_POST["antigua"];
$ruta = $_POST["ruta"];
$seccion = $_POST["seccion"];
$Foto = $_FILES["Foto"];
$Icono = $_FILES["Icono"];
$AnchoFoto = $_POST["AnchoFoto"];
$AltoFoto = $_POST["AltoFoto"];
$antiguaPlantilla = $_POST["antiguaPlantilla"];
$Plantilla = $_POST["Plantilla"];
$Keywords = $_POST["Keywords"];
$Redireccionar = $_POST["Redireccionar"];
$NumXPag = $_POST["NumXPag"];
$Indexable = $_POST["Indexable"];
$Prioridad = $_POST["Prioridad"];
$URLAmigable = $_POST["URLAmigable"];
$codigoIdioma = $_POST["codigoIdioma"];
$herramientaOrigen = $_POST["herramientaOrigen"];
$seccionOrigen = $_POST["seccionOrigen"];
$rutaOrigen = $_POST["rutaOrigen"];
$NumColumnas = $_POST["NumColumnas"];
$Visibilidad = $_POST["Visibilidad"];
$fechaComienzo = $_POST['fechaComienzo'];
$fechaFin = $_POST['fechaFin'];
$fechaComienzoHoras = $_POST['fechaComienzoHoras'];
$fechaFinHoras = $_POST['fechaFinHoras'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nueva_seccion-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}

//Calculamos el nombre de la carpeta que corresponde a la sección
$traduccion = array("&nbsp;" => "-", "ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u",  "ã" => "a", "Ã" => "A", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "'"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
$NomFich = strtr($Titulo, $traduccion);
$NomFich = substr($NomFich,0,50);

//Problemas con caracteres especiales
$traduccionCaracteres = array("'"=> "\'", "\""=> "\\\"");
$TituloBuscadores = strtr($TituloBuscadores, $traduccionCaracteres);
$DescripcionBuscadores = strtr($DescripcionBuscadores, $traduccionCaracteres);
$Titulo = strtr($Titulo, $traduccionCaracteres);
$Texto = strtr($Texto, $traduccionCaracteres);

if (strlen($NomFich)==0) 
{
	print "<p>Necesito un Título para crear la sección</p>";
	print "<p>Variable: Titulo vacia,  [".$_PUSH["Titulo"]."]</p>";
	die;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

if (file_exists($Foto['tmp_name']))
{	
	switch ($Foto['type'])
	{
		case "image/pjpeg":
		case "image/jpeg":
		case "jpeg":
			$imagen = imagecreatefromjpeg($Foto['tmp_name']);
			break;
		case "image/gif":
			$imagen = imagecreatefromgif($Foto['tmp_name']);	  			
			break;			
		case "x-png":
		case "image/x-png":
		case "image/png":
			$imagen = imagecreatefrompng($Foto['tmp_name']);	  			
			break;
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
	if (($AnchoFoto=="")&&($AltoFoto==""))
	{
		$AnchoFoto = $width;
		$AltoFoto = $height;
	}
	$imagen2 = imagecreatetruecolor($AnchoFoto,$AltoFoto);
	imagecopyresampled ($imagen2, $imagen, 0, 0, 0, 0, $AnchoFoto, $AltoFoto, $width, $height);
	$filenameFoto = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
	//SUBIMOS LA FOTO
	$i="";
	for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameFoto));$j++)
	{
		$i = $j;
	}
	$filenameFoto = $i.$filenameFoto;	
	imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameFoto,100);// 100 es máxima calidad
}
$existeIcono="no";
if (file_exists($Icono['tmp_name']))
{	
	$existeIcono="si";
	$filenameIcono = strtr(str_replace(" ","_",$Icono['name']), $traduccion);	
	//Subir el icono
	$i="";
	for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameIcono));$j++)
	{
		$i = $j;
	}
	$filenameIcono = $i.$filenameIcono;
	//Subimos el Fichero
	$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameIcono, "x");
	$localfile = file_get_contents($Icono['tmp_name']);
	fwrite($ficherin, $localfile);
	fclose($ficherin);
}
//Cargamos el contenido en la base de datos
$requete = "UPDATE `Secciones` SET `Titulo` = \"".$Titulo."\", `Texto` =\"".$Texto."\", `Keywords` = \"".$Keywords."\", `NomFich` = '".$NomFich."', `Plantilla` = '".$Plantilla."', `Redireccionar`='".$Redireccionar."', `IdUsuarioModifica`='".$_SESSION['usuario_id']."',`Visibilidad`='".$Visibilidad."'";
if (file_exists($Icono['tmp_name'])) $requete.= ",`Icono`='".$filenameIcono."'";
if (file_exists($Foto['tmp_name'])) $requete.= ",`Foto`='".$filenameFoto."'";
if ($NumXPag!="") $requete .= ",`NumXPag`='".$NumXPag."'";
if ($NumColumnas!="") $requete .= ",`NumColumnas`='".$NumColumnas."'";
if ($Indexable=="no") $requete .= ", `Prioridad` = '-1'";
else
{
	if ($Prioridad!="") $requete .= ", `Prioridad` = '".$Prioridad."'";
	else $requete .= ", `Prioridad` = NULL";
}
$requete .= ", `TituloBuscadores` = \"".$TituloBuscadores."\"";
$requete .= ", `DescripcionBuscadores` = \"".$DescripcionBuscadores."\"";
$requete .= ", `URLAmigable` = '".$URLAmigable."'";
$requete .= ",`FechaComienzo`=";
if ($fechaComienzo!='') $requete.="'".$fechaComienzo." ".$fechaComienzoHoras."'";
else $requete.="'".date('Y-m-d H:i:s')."'";
$requete.= ",`FechaFin`=";
if ($fechaFin!='') $requete.="'".$fechaFin." ".$fechaFinHoras."'";
else $requete.="NULL";
$requete .= " WHERE `Id` =$seccion;";
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

require $_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/funciones/repara_path.php";
//Si cambiamos la ruta debemos cambiar el Path en todos los hijos de acuerdo al cambio en NomFich
$id=($_POST["seccion"])?$_POST["seccion"]:$_GET["seccion"];
$vid=intval($id);

repara_path($vid,"Secciones",true);
//Recargamos el directorio en curso
if (($seccion!="")&&(!isset($codigoIdioma)||$codigoIdioma==""||$codigoIdioma==null))
{
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);	
}
else 
{
	if ($herramientaOrigen=="raiz") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	else 
	{
		if ($codigoIdioma!="") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccionOrigen."&ruta=".$rutaOrigen);
		else header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
}
?>
