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
	if ($listado2->Path!="")
	{
	$enruta=$listado2->Path."/".$listado2->NomFich;
	}
	else
	{
	$enruta=$listado2->NomFich;
	}
	//Cargamos el fichero en el ftp
	if ($Plantilla!="Contenido desplegado") // Si se aplica plantilla, guardamos el fichero con su plantilla.
	{
		$handle=fopen($_SERVER['DOCUMENT_ROOT']."/Secciones/".$enruta."/".$listado->NomFich.".php",'x');
		$requete = "SELECT * FROM `Plantillas` WHERE `Nombre`='".$Plantilla."'";
		$result2 = mysqli_query($db,$requete);
		$listado2 = mysqli_fetch_object($result2);
		fwrite($handle,"<?php\n\$Id=".$contenido.";\nrequire(\$_SERVER['DOCUMENT_ROOT'].\"/Plantillas/".$listado2->NomFich."\");\n?>");
		fclose($handle);
		chmod($_SERVER['DOCUMENT_ROOT']."/Secciones/".$enruta."/".$listado->NomFich.".php",0755);
		//Miramos a ver si tiene icono, si es así, lo copiamos
		if ($listado->Icono!="")
		{			
			copy ($_SERVER['DOCUMENT_ROOT']."/Secciones/".$ruta."/".$listado->Icono,$_SERVER['DOCUMENT_ROOT']."/Secciones/".$enruta."/".$listado->Icono);
		}
	}
	else
	{
		if (file_exists ($_SERVER['DOCUMENT_ROOT']."/Secciones/".$enruta."/".$listado->NomFich.".php")) unlink($_SERVER['DOCUMENT_ROOT']."/Secciones/".$enruta."/".$listado->NomFich.".php");
	}
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
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$TipoContenido);
?>