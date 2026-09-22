<?php
//VERSIÓN: v1.0 2013-12-11
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$contenido = $_GET["contenido"];
$tipocontenido = $_GET["tipocontenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/campos_adicionales-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
$ficheros = $_FILES;
$ficheros_titulos = array_keys($_FILES);
$campos=$_POST;
$titulos = array_keys($_POST);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
for ($i=0;$i<count($campos);$i++)
{
	$titulo = str_replace("_"," ",$titulos[$i]);
	if ($campos[$titulos[$i]]!="")
	{
		if (is_array($campos[$titulos[$i]])) //Estamos en un checkbox
		{ 		
			for ($jj=0;$jj<count($campos[$titulos[$i]]);$jj++)
			{ 			
				$requete ="INSERT INTO `CamposAdicionales` (`IdContenido`, `IdTipoContenido`, `TituloCampo`, `Valor`) VALUES ('".$contenido."', '".$tipocontenido."', '".$titulo."', '".$campos[$titulos[$i]][$jj]."');";
				mysqli_query($db,$requete); 			
			}
		}
		else
		{
			$requete ="INSERT INTO `CamposAdicionales` (`IdContenido`, `IdTipoContenido`, `TituloCampo`, `Valor`) VALUES ('".$contenido."', '".$tipocontenido."', '".$titulo."', '".$campos[$titulos[$i]]."');";
			mysqli_query($db,$requete);
		}
	}
}
for ($i=0;$i<count($ficheros);$i++)
{
	$titulo = str_replace("_", " ",$ficheros_titulos[$i]);
	if ($ficheros[$ficheros_titulos[$i]]["tmp_name"]!="")
	{
		$nombreFichero = rand(0,100000)."-".$ficheros[$ficheros_titulos[$i]]["name"];
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Recursos/".$nombreFichero, "x");
		$localfile = file_get_contents($ficheros[$ficheros_titulos[$i]]["tmp_name"]);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
		$requete ="INSERT INTO `CamposAdicionales` (`IdContenido`, `IdTipoContenido`, `TituloCampo`, `Valor`) VALUES ('".$contenido."', '".$tipocontenido."', '".$titulo."', '".$nombreFichero."');";
		mysqli_query($db,$requete);
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta);
?>