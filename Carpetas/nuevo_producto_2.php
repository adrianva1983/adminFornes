<?php
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
$IdUsuario = $_POST['IdUsuario'];
$URLAmigable = $_POST['URLAmigable'];
$ruta = $_POST['ruta'];
$Notas = $_POST['Notas'];
$seccionOrigen = $_POST['seccionOrigen'];
$rutaOrigen = $_POST['rutaOrigen'];
$PrecioBI = $_POST['PrecioBI'];
$PrecioTAX = $_POST['PrecioTAX'];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/nuevo_producto-".$_SESSION['idioma'].".conf");
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Secciones` WHERE `Id` = '".$seccion."'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$IdEntorno = $listado->IdEntorno;
}

//Calculamos el nombre del fichero que corresponde y el nombre del fichero de imagen
$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" => "", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"","'" => "","`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U",  "ã" => "a", "Ã" => "A","Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
$NomFich = strtr(str_replace(" ","-",$Titulo), $traduccion);
$NomFich = substr($NomFich,0,100);

//Problemas con caracteres especiales
$traduccionCaracteres = array("'"=> "\'", "\""=> "\\\"");
$TituloBuscadores = strtr($TituloBuscadores, $traduccionCaracteres);
$DescripcionBuscadores = strtr($DescripcionBuscadores, $traduccionCaracteres);
$Titulo = strtr($Titulo, $traduccionCaracteres);
$Breve = strtr($Breve, $traduccionCaracteres);
if (file_exists($Foto['tmp_name']))
{
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
	$NomFoto = strtr(str_replace(" ","-",$Foto['name']), $traduccion);
	$NomFoto = str_ireplace(".".$extension, "", $NomFoto);
	$NomFoto = substr($NomFoto,0,120);
	$NomFoto = "N".rand(1, 10000)."-".$NomFoto;
	
	list($width, $height) = getimagesize($Foto['tmp_name']);
	$AnchoFoto=150;
	// Evitamos que la foto no se distorsione si fijamos o ancho o alto	
	// Hacemos una consulta para saber el tamaño por defecto de las limágenes en listados del SERVIDOR O DEL ENTORNO
	if ($IdEntorno!="")
	{
		$requete = "SELECT * FROM `Entornos` WHERE Id = '".$IdEntorno."'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			if ($listado->ListadoFotoAncho!="") $AnchoFoto = $listado->ListadoFotoAncho;
			if ($listado->ListadoFotoAlto!="") $AltoFoto = $listado->ListadoFotoAlto;			
		}
	}
	else
	{
		$requete = "SELECT * FROM `Servidor` WHERE Campo = 'LISTADO-FOTO-ANCHO'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$AnchoFoto = $listado->Valor;		
		}
		$requete = "SELECT * FROM `Servidor` WHERE Campo = 'LISTADO-FOTO-ALTO'";
					
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$AltoFoto = $listado->Valor;
		}
	}
	if (($AnchoFoto!="")&&($AltoFoto=="")){
		$proporcion = $width / $height;
		$AltoFoto = $AnchoFoto / $proporcion;
	}
	if (($AnchoFoto=="")&&($AltoFoto!="")){
		$proporcion = $width / $height;
		$AnchoFoto = $AltoFoto * $proporcion;
	}
	$imagen2 = imagecreatetruecolor($AnchoFoto,$AltoFoto);
	imagecopyresampled ($imagen2, $imagen, 0, 0, 0, 0, $AnchoFoto, $AltoFoto, $width, $height);
	//Si el fichero existe le añadimos un valor numérico hasta que no exista
	if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."_p.".$extension))
	{
		for ($i=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto.$i."_p.".$extension));$i++)
		{}
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
			imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto, 100); // 100 es máxima calidad
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
if (file_exists($Icono['tmp_name']))
{
	switch ($Icono['type']) 
	{
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
	$NomIcono = strtr(str_replace(" ","-",$Icono['name']), $traduccion);
	$NomIcono = str_ireplace(".".$extension, "", $NomIcono);
	$NomIcono = substr($NomIcono,0,120);
	if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomFoto."_p.".$extension))
	{
		for ($i=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomIcono.$i.".".$extension));$i++)
		{}
		$NomIcono = $NomIcono.$i;
	}
	//Subimos la foto en la carpeta de fotos de la Web
	$NomIcono = $NomIcono.".".$extension;	

	//Subimos el Fichero
	$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/".$NomIcono, "x");
	$localfile = file_get_contents($Icono['tmp_name']);
	fwrite($ficherin, $localfile);
	fclose($ficherin);
	list($width, $height) = getimagesize($Icono['tmp_name']);
	$AnchoIcono=$width;
	$AltoIcono=$height;
}
//Introducimos el Contenido en la base de datos
//IdPadre? Redireccionar?
$requete = "SELECT Idioma FROM Secciones WHERE Id='".$seccion."';";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if ($codigoIdioma=="") $codigoIdioma= $listado->Idioma;
}
else $codigoIdioma= "ES-ES";
$requete = "INSERT INTO `Contenidos` (`FechaComienzo`, `Titulo`, `Breve`, `Keywords`, `NomFich`, `IdPropietario`, `Tipo`,`TituloBuscadores`,`DescripcionBuscadores`,`URLAmigable`,`PrecioBI`,`PrecioTAX`";
if ($Foto!="") $requete.= ", `Foto`, `AnchoFoto`, `AltoFoto`";
if ($Icono!="") $requete.= ", `Icono`";
if ($referenciaIdioma!="") $requete .= ", `RelacionIdioma`";
if ($codigoIdioma!="") $requete .= ", `Idioma`";
if ($Fecha!="") $requete .= ", `Fecha`";
$requete .=") VALUES ('".date("Y-m-d h:i:s")."', '".$Titulo."', '".$Breve."', '".$Keywords."', '".$NomFich."'";
$requete.=",'".$IdUsuario."', 'producto','".$TituloBuscadores."','".$DescripcionBuscadores."','".$URLAmigable."','".$PrecioBI."','".$PrecioTAX."'";
if ($Foto!="") $requete.= ", '".$NomFoto."', '".$AnchoFoto."', '".$AltoFoto."'";
if ($Icono!="") $requete.= ", '".$NomIcono."'";
if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
if ($codigoIdioma!="") $requete .= ", '".$codigoIdioma."'";
if ($Fecha!="") $requete .= ", '".$Fecha."'";
$requete.=");";
mysqli_query($db,$requete);

//Introducimos la publicación del Contenido en la sección
$IdContenido = mysqli_insert_id($db);

//Buscamos el minimo Orden para introducir este elemento en primer lugar
$requete = "SELECT MIN(Orden) FROM Publicaciones WHERE IdSeccion='".$seccion."';";

if ($result = mysqli_query($db, $requete))
{
	$row = mysql_fetch_row($result);
	if ($row[0]!="" && isset($row[0])&&$row[0]!=null) $Orden=$row[0];
	else $Orden = 0;
}
else
{
	$Orden=0;
}
$Orden--;

$requete = "INSERT INTO `Publicaciones` (`IdContenido`,`IdSeccion`,`Plantilla`,`Comportamiento`,`Orden`,`FechaComienzo`";
if ($Notas!="") $requete.= ", `Notas`";
$requete.= ") VALUES ('".$IdContenido."', '".$seccion."', ";
$requete.="NULL";
$requete.=",NULL";
$requete.=", '".$Orden."','".date("Y-m-d h:i:s")."'";
if ($Notas!="") $requete.= ", '".$Notas."'";
$requete.= ");";
mysqli_query($db,$requete);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el directorio en curso
if (($seccion!="")&&($referenciaIdioma=="")){
	header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else {
	if ($referenciaIdioma!="") header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccionOrigen."&ruta=".$rutaOrigen);
	else header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
}
?>