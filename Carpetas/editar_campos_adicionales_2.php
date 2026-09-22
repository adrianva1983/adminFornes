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
// NIVEL MÍNIMO RESPONSABLE
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_campos_adicionales-".$_SESSION['idioma'].".conf");
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

$campos=$_POST;
$titulos = array_keys($_POST);
$ficheros = $_FILES;
$ficheros_titulos = array_keys($_FILES);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
for ($i=0;$i<count($_POST["borrar_ficheros"]);$i++)
{
	$requete ="DELETE FROM `CamposAdicionales` WHERE `Valor`='".$_POST["borrar_ficheros"][$i]."' AND `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."';";
	mysqli_query($db,$requete);
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/Recursos/".$_POST["borrar_ficheros"][$i])) unlink($_SERVER['DOCUMENT_ROOT']."/Recursos/".$_POST["borrar_ficheros"][$i]);
}
for ($i=0;$i<count($campos);$i++)
{
	$titulo = str_replace("_"," ",$titulos[$i]);
	$requete = "SELECT * FROM `CamposAdicionales` WHERE `TituloCampo` LIKE '%".$titulo."%' AND `IdContenido`='$contenido'";	
	
	if ($campos[$titulos[$i]]!="")
	{
		if ($result = mysqli_query($db, $requete))
		{
			if (is_array($campos[$titulos[$i]])) //Estamos en un checkbox
			{
				$kk = 1;
				while ($listado = mysqli_fetch_object($result))
				{
					$valoresKK[$kk] = $listado->Valor; //VALORES DEL CHECKBOX EN BASE DE DATOS					
					$kk++; 	 		
				}
				for ($jj=0;$jj<=count($campos[$titulos[$i]]);$jj++)
				{					
					if ($encontrado = array_search($campos[$titulos[$i]][$jj], $valoresKK))
					{
						$valoresKK[$encontrado]=NULL; //Está en la base de datos, por, lo que marcamos que sigue en el sistema para no borrarlo al terminar.
					}
					else
					{
						$requete ="INSERT INTO `CamposAdicionales` (`IdContenido`, `IdTipoContenido`, `TituloCampo`, `Valor`) VALUES ('".$contenido."', '".$tipocontenido."', '".$titulo."', '".$campos[$titulos[$i]][$jj]."');";   									
						mysqli_query($db,$requete);
					}					
				}				
				for ($jj=0;$jj<=count($valoresKK);$jj++) //Borramos los valores que están en base de datos y no están marcados.
				{					
					if ($valoresKK[$jj])
					{
						$requete ="DELETE FROM `CamposAdicionales` WHERE `Valor`='".$valoresKK[$jj]."' AND `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo` LIKE '%".$titulo."%';";
						mysqli_query($db,$requete);						
					}					
				}
			}
			else
			{
				$listado = mysqli_fetch_object($result);
				$requete ="UPDATE `CamposAdicionales` SET `Valor` = '".$campos[$titulos[$i]]."' WHERE `Id`='".$listado->Id."';"; 				
				mysqli_query($db,$requete);				
			}
		}
		else
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
	else
	{
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			$requete ="DELETE FROM `CamposAdicionales` WHERE `Id`='".$listado->Id."';";   			
			mysqli_query($db,$requete);			
		}
		else
		{
			$campo = explode("/",$titulos[$i]);
			if ($campo[1])
			{
				$requete = "SELECT * FROM `CamposAdicionales` WHERE `TituloCampo` = '".$campo[1]."' AND `IdContenido`='$contenido'";				
				
				if ($result = mysqli_query($db, $requete))
				{
					$listado = mysqli_fetch_object($result);
					$requete ="DELETE FROM `CamposAdicionales` WHERE `Id`='".$listado->Id."';";       					
					mysqli_query($db,$requete);					
				}
			}
		}
	}
}
for ($i=0;$i<count($ficheros);$i++)
{
	$titulo = str_replace("_", " ",$ficheros_titulos[$i]);
	$requete = "SELECT * FROM `CamposAdicionales WHERE `TituloCampo = '".$titulo."' AND `IdContenido`=".$contenido;
		
	if ($ficheros[$ficheros_titulos[$i]]["tmp_name"]!="")
	{
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/Recursos/".$listado->Valor)) unlink($_SERVER['DOCUMENT_ROOT']."/Recursos/".$listado->Valor);
			$requete ="DELETE FROM `CamposAdicionales` WHERE `Id`='".$listado->Id."';"; 
			mysqli_query($db,$requete);
		}
		$nombreFichero = rand(0,100000)."-".$ficheros[$ficheros_titulos[$i]]["name"];
		$ficherin = fopen($_SERVER['DOCUMENT_ROOT']."/Recursos/".$nombreFichero, "x");
		$localfile = file_get_contents($ficheros[$ficheros_titulos[$i]]["tmp_name"]);
		fwrite($ficherin, $localfile);
		fclose($ficherin);
		$requete ="INSERT INTO `CamposAdicionales` (`IdContenido`, `IdTipoContenido`, `TituloCampo`, `Valor`) VALUES ('".$contenido."', '".$tipocontenido."', '".$titulo."', '".$nombreFichero."');";
		mysqli_query($db,$requete);
	}
}
//Actualizamos la fecha de modificación del contenido
$requete = "UPDATE `Contenidos`  SET `FechaModificacion` = '".date("Y-m-d")."' WHERE `Id`=".$contenido;
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
?>