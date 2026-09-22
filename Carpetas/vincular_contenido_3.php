<?php
$contenido = $_GET['contenido'];
$seccion = $_GET['seccion'];
$ruta = $_GET['ruta'];
$tipocontenido = $_GET['tipocontenido'];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &áacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

$campos=$_POST;
$titulos = array_keys($_POST);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Contenidos` WHERE `Id`='".$contenido."'";

$listado = mysqli_fetch_object($result);

for ($i=1;$i<count($campos);$i++)
{
	if ($campos[$titulos[$i]]!="")
	{
		$tipodecampo = explode("/",$titulos[$i]);
		switch ($tipodecampo[0])
		{
			case "secciones":
				//Consultamos la ruta
				$requete = "SELECT * FROM `Secciones` WHERE `Id`='".$tipodecampo[1]."'";
				$result2 = mysqli_query($db,$requete);
				$listado2 = mysqli_fetch_object($result2);
				//Buscamos el minimo Orden para introducir este elemento en primer lugar
				$requete = "SELECT MIN(Orden) FROM Publicaciones WHERE IdSeccion='".$tipodecampo[1]."';";
				$result2 = mysqli_query($db,$requete);
				if ($result2 = mysqli_query($db, $requete2))
				{
					$row = mysql_fetch_row($result2);
					$Orden=$row[0];
				}
				else
				{
					$Orden=0;
				}
				$Orden--;
				break;
		}
		// PUBLICAMOS EN LA BASE DE DATOS
		$requete = "INSERT INTO `Publicaciones` (`IdContenido`,`IdSeccion`,`Plantilla`,`Orden`,`FechaComienzo`) VALUES ('".$contenido."', '".$tipodecampo[1]."', '".$Plantilla."', '".$Orden."','".date("Y-m-d")."');";
		mysqli_query($db,$requete);
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido);
?>