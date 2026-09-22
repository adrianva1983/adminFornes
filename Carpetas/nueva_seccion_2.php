<?php
//VERSIÓN: v1.0 2015-06-09
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Titulo = $_POST["Titulo"];
$TituloBuscadores =  $_POST["TituloBuscadores"];
$DescripcionBuscadores = $_POST["DescripcionBuscadores"];
$Texto = $_POST["Texto"];
$Miniweb = $_POST["Miniweb"];
$Foto = $_FILES["Foto"];
$RetocarFoto = $_POST["RetocarFoto"];
$AnchoFoto=$_POST["AnchoFoto"];
$AltoFoto=$_POST["AltoFoto"];
$Icono = $_FILES["Icono"];
$RetocarIcono = $_POST["RetocarIcono"];
$seccion = $_POST["seccion"];
$Dominio = $_POST["Dominio"];
$Plantilla = $_POST["Plantilla"];
$URLSeccion=$_POST["URLSeccion"];
$URLContenido=$_POST["URLContenido"];
$ColorMiniweb=$_POST["ColorMiniweb"];
$LogoMiniweb=$_POST["LogoMiniweb"];
$Redireccionar = $_POST["Redireccionar"];
$ListadoFotoAncho=$_POST["ListadoFotoAncho"];
$ListadoFotoAlto=$_POST["ListadoFotoAlto"];
$Analytics=$_POST["Analytics"];
$Keywords=$_POST["Keywords"];
$URLAmigable=$_POST["URLAmigable"];
$Prioridad=$_POST["Prioridad"];
$Indexable=$_POST["Indexable"];
$NumXPag=$_POST["NumXPag"];
$IdEntorno=$_POST["IdEntorno"];
$referenciaIdioma=$_POST["referenciaIdioma"];
$codigoIdioma=$_POST["codigoIdioma"];
$ruta=$_POST["ruta"];
$herramientaOrigen=$_POST["herramientaOrigen"];
$seccionOrigen=$_POST["seccionOrigen"];
$rutaOrigen=$_POST["rutaOrigen"];
$Visibilidad = $_POST["Visibilidad"];
$NumColumnas = $_POST["NumColumnas"];
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
$traduccion = array("ç" => "z", "Ç" => "Z", "&" => "and", " " => "-", "á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ã" => "a", "ã" => "a", "Ã" => "A", "ä" => "a", "ë" => "e", "ï" => "i", "ö" =>"o", "ü" => "u", "/" => "-", ":" => "", "*" => "", "¿" =>"", "?" => "", ">" => "-", "<" => "-", "\"" => "", "|" => "", "\'" =>"", "`" =>"", "´"=>"", "ñ" => "n", "Ñ" => "N", "Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U", "Ä" => "A", "Ë"=> "E", "Ï" => "I", "Ö" => "O", "Ü" => "U","à"=>"a","è"=>"e","ì" =>"i","ò"=>"o","ù"=>"u","À"=>"A","È"=>"E","Ì"=>"I","Ò"=>"O","Ù"=>"U", "â" => "a", "ê" => "e", "î" => "i", "ô" => "o", "û" => "u", "Â" => "A", "Ê" => "E", "Î" => "I", "Ô" => "O", "Û" => "U", "(" =>"-", ")" => "-");
$NomFich = strtr(str_replace(" ","-",$Titulo), $traduccion);
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
//Miramos el idioma principal
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";
$result = mysqli_query($db, $requete);
if (($result) && (mysqli_num_rows($result)>0))
{
	$listado = mysqli_fetch_object($result);
	$idiomaPrincipal = $listado->Codigo;
}

$existeFoto="no";
if (file_exists($Foto['tmp_name'])&&($Miniweb==""))
{
	if ($RetocarFoto=="no")
	{
		$NomFicherin = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
		$filenameFoto = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		$i="";
		for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameFoto));$j++)
		{
			$i = $j;
		}
		$filenameFoto = $i.$filenameFoto;
		list($width, $height) = getimagesize($Foto['tmp_name']);
		$AnchoFoto = $width;
		$AltoFoto = $height;
		$AnchoFoto2 = "";
		$AltoFoto2 = "";
	}
	else
	{
		$existeFoto="si";
		$imagen = imagecreatefromjpeg($Foto['tmp_name']);
		list($width, $height) = getimagesize($Foto['tmp_name']);
		// Evitamos que la foto no se distorsione si fijamos o ancho o alto
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
		$filenameFoto = strtr(str_replace(" ","_",$Foto['name']), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		$i="";
		for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameFoto));$j++)
		{
			$i = $j;
		}
		$filenameFoto = $i.$filenameFoto;		
	}
}
$existeIcono="no";
if (file_exists($Icono['tmp_name'])&&($Miniweb==""))
{
	if ($RetocarIcono=="no")
	{
		$NomFicherin = strtr(str_replace(" ","_",$Icono['name']), $traduccion);
		$filenameIcono = strtr(str_replace(" ","_",$Icono['name']), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		$i="";
		for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameIcono));$j++)
		{
			$i = $j;
		}
		$filenameIcono = $i.$filenameIcono;
	}
	else
	{
		$existeIcono="si";
		switch ($Icono['type']) 
		{
			case "image/pjpeg":
			case "image/jpeg":
				$imagen = imagecreatefromjpeg($Icono['tmp_name']);
				$extension = "jpg";
				break;
			case "image/gif":
				$imagen = imagecreatefromgif($Icono['tmp_name']);
				$extension = "gif";
				break;
			case "x-png":
			case "image/x-png":
			case "image/png":
				$imagen = imagecreatefrompng($Icono['tmp_name']);
				$extension = "png";
				break;
		}	
		$filenameIcono = strtr(str_replace(" ","_",$Icono['name']), $traduccion);
		//Si el fichero existe le añadimos un valor numérico hasta que no exista
		$i="";
		for ($j=0;(file_exists ($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$i.$filenameIcono));$j++)
		{
			$i = $j;
		}
		$filenameIcono = $i.$filenameIcono;	
	}
}


//Buscamos el máximo Orden para introducir este elemento en último lugar
if ($seccion!="")
{
	$requete = "SELECT MAX(Orden) FROM Secciones WHERE IdPadre='".$seccion."';";
}
else
{
	$requete = "SELECT MAX(Orden) FROM Secciones WHERE IdPadre=NULL;";
}
$result = mysqli_query($db, $requete);
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

//Creamos el ENTORNO SI NOS LO HAN RELLENADO
if ($Dominio!="")
{
	$requete = "INSERT INTO `Entornos` (`Dominio`";
	if ($URLSeccion!="") $requete.=", `URLSeccion`";
	if ($URLContenido!="") $requete.=", `URLContenido`";
	if ($ColorMiniweb!="") $requete.=", `ColorMiniweb`";
	if ($LogoMiniweb!="") $requete.=", `LogoMiniweb`";
	if ($ListadoFotoAncho!="") $requete.=", `ListadoFotoAncho`";
	if ($ListadoFotoAlto!="") $requete.=", `ListadoFotoAlto`";
	if ($Analytics!="") $requete.=", `Analytics`";
	$requete .=") VALUES ('".$Dominio."'";
	if ($URLSeccion!="") $requete.=", '".$URLSeccion."'";
	if ($URLContenido!="") $requete.=", '".$URLContenido."'";
	if ($ColorMiniweb!="") $requete.=", '".$ColorMiniweb."'";
	if ($LogoMiniweb!="") $requete.=", '".$LogoMiniweb."'";
	if ($ListadoFotoAncho!="") $requete.=", '".$ListadoFotoAncho."'";
	if ($ListadoFotoAlto!="") $requete.=", '".$ListadoFotoAlto."'";
	if ($Analytics!="") $requete.=", '".$Analytics."'";	
	$requete.=");";
	mysqli_query($db,$requete);
	$IdEntorno = mysqli_insert_id($db);
}

//IdPadre? Redireccionar?
$requete = "INSERT INTO `Secciones` (`Orden`, `FechaComienzo`,`FechaFin`,`Titulo`, `Texto`, `AnchoFoto`, `AltoFoto`, `NomFich`, `Path`, `Visibilidad`, `Plantilla`, `Redireccionar`,`IdUsuario`";
if ($TituloBuscadores!="") $requete .= ", `TituloBuscadores`";
if ($DescripcionBuscadores!="") $requete .= ", `DescripcionBuscadores`";
if ($Keywords!="") $requete .= ", `Keywords`";
if ($URLAmigable!="") $requete .= ", `URLAmigable`";
if (($Prioridad!="")||($Indexable=="no")) $requete .= ", `Prioridad`";
if ($NumXPag!="") $requete .= ", `NumXPag`";
if ($NumColumnas!="") $requete .= ", `NumColumnas`";
if ($seccion!="") $requete .= ", `IdPadre`";
if ($IdEntorno!="") $requete .= ", `IdEntorno`";
if ($referenciaIdioma!="") $requete .= ", `RelacionIdioma`";
$requete .= ", `Idioma`";
if (file_exists($Foto['tmp_name'])) $requete .= ",`Foto`";
if (file_exists($Icono['tmp_name'])) $requete .= ",`Icono`";
$requete .=") VALUES ('".$Orden."'";
if ($fechaComienzo!='') $requete.=",'".$fechaComienzo." ".$fechaComienzoHoras."'";
else $requete.=", '".date("Y-m-d H:i:s")."'";
if ($fechaFin!='') $requete.=",'".$fechaFin." ".$fechaFinHoras."'";
else $requete.=", NULL";
$requete.=", '".$Titulo."', '".$Texto."', '".$AnchoFoto."', '".$AltoFoto."', '".$NomFich."', '".$ruta."'";
//SI EL PADRE ES PRIVADO, LO CREAMOS SIEMPRE PRIVADO TAMBIÉN PARA QUE NO PUEDAN ENTRAR POR DENTRO
$requete2 = "SELECT * FROM Secciones WHERE Id=".$seccion." AND Visibilidad='privado'";
$result2 = mysqli_query($db, $requete2);
if (($result2) && (mysqli_num_rows($result2)>0)) $requete.=", 'privado'";
else $requete.=", '".$Visibilidad."'";
$requete.=", '".$Plantilla."', '".$Redireccionar."', '".$_SESSION['usuario_id']."'";
if ($TituloBuscadores!="") $requete .= ", '".$TituloBuscadores."'";
if ($DescripcionBuscadores!="") $requete .= ", '".$DescripcionBuscadores."'";
if ($Keywords!="") $requete .= ", '".$Keywords."'"; 
if ($URLAmigable!="") $requete .= ", '".$URLAmigable."'";
if ($Indexable=="no") $requete .= ", '-1'";
else
{
	if ($Prioridad!="") $requete .= ",  '".$Prioridad."'";
}
if ($NumXPag!="") $requete .= ", '".$NumXPag."'";
if ($NumColumnas!="") $requete .= ", '".$NumColumnas."'";
if ($seccion!="") $requete .= ", '".$seccion."'";
if ($IdEntorno!="") $requete .= ", '".$IdEntorno."'";
if ($referenciaIdioma!="") $requete .= ", '".$referenciaIdioma."'";
if ($codigoIdioma!="") $requete .= ", '".$codigoIdioma."'";
else $requete .= ", '".$idiomaPrincipal."'";
if (file_exists($Foto['tmp_name'])) $requete.=", '".$filenameFoto."'";
if (file_exists($Icono['tmp_name'])) $requete.=", '".$filenameIcono."'";
$requete.=");";
//echo "<li>$requete</li>";
mysqli_query($db,$requete);
$ID_NUEVA_SECCION = mysqli_insert_id($db);
//Creamos la carpeta y el fichero siempre que no expecifiquemos lo contrario (ejemplo minisites)
if ($Miniweb=="")
{
	if ($ruta!="")
	{
		mkdir($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$NomFich, 0755);
		//Cargamos el fichero en el ftp
		if ($Plantilla!="") // Si se aplica plantilla, guardamos el fichero con su plantilla.
		{
			$handle=fopen($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$NomFich."/index.php",'x');
			$requete = "SELECT * FROM `Plantillas` WHERE `Nombre`='".$Plantilla."'";
			mysqli_query($db,$requete);
			$listado = mysqli_fetch_object($result);			
			fwrite($handle,"<?php\n\$Id=".$ID_NUEVA_SECCION.";\n require(\$_SERVER['DOCUMENT_ROOT'].\"/Plantillas/".$listado->NomFich."\");\n?>");
			fclose($handle);
			chmod($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$NomFich."/index.php",0755);
		}
	}
	else
	{
		mkdir($_SERVER['DOCUMENT_ROOT']."/Secciones/".$NomFich, 0755);
		//Cargamos el fichero en el ftp
		if ($Plantilla!="") // Si se aplica plantilla, guardamos el fichero con su plantilla.
		{
			$handle=fopen($_SERVER['DOCUMENT_ROOT']."/Secciones/".$NomFich."/index.php",'x');
			$requete = "SELECT * FROM `Plantillas` WHERE `Nombre`='".$Plantilla."'";
			mysqli_query($db,$requete);
			$listado = mysqli_fetch_object($result);
			fwrite($handle,"<?php\n\$Id=".$ID_NUEVA_SECCION.";\n require(\$_SERVER['DOCUMENT_ROOT'].\"/Plantillas/".$listado->NomFich."\");\n?>");
			fclose($handle);
			chmod($_SERVER['DOCUMENT_ROOT']."/Secciones/".$NomFich."/index.php",0755);
		}
	}
}
if ((file_exists($Foto['tmp_name']))&&($Miniweb==""))
{
	if ($RetocarFoto=="no")
	{
		//Subimos el Fichero
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameFoto, "x");
		$localfile = file_get_contents($Foto['tmp_name']);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
	}
	else
	{
		//Subimos la foto
		imagejpeg($imagen2,$_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameFoto,100); // 100 es máxima calidad
	}
}
if ((file_exists($Icono['tmp_name']))&&($Miniweb==""))
{
	if ($RetocarIcono=="no")
	{
		//Subimos el Fichero
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameIcono, "x");
		$localfile = file_get_contents($Icono['tmp_name']);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
	}
	else
	{
		//Subir el icono
		if ($extension=="jpg")
		{
			imagejpeg($imagen,$_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameIcono,100);// 100 es máxima calidad
		}
		if ($extension=="gif")
		{
			imagegif($imagen,$_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameIcono);
		}
		if ($extension=="png")
		{
			imagepng($imagen,$_SERVER['DOCUMENT_ROOT']."/Imagenes/Secciones/".$filenameIcono);
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
if (($seccion!="")&&($codigoIdioma=="")){
	header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
}
else {
	if ($herramientaOrigen=="raiz") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	else 
	{
		if ($codigoIdioma!="") header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccionOrigen."&ruta=".$rutaOrigen);
		else header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}
}
?>