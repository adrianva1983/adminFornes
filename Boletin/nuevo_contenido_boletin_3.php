<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

$campos=$_GET;
$titulos = array_keys($_GET);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
for ($i=0;$i<count($campos);$i++)
{
   $tipodecampo = explode("/",$titulos[$i]);
   switch ($tipodecampo[0]) 
   {
     case "contenido":
	//Buscamos el máximo Orden para introducir este elemento en último lugar
	$requete = "DELETE FROM `BoletinContenidos` WHERE `IdBoletin`='".$idboletin."' AND `IdContenido`='".$campos[$titulos[$i]]."';";
	mysqli_query($db,$requete);
	$requete = "SELECT MAX(Orden) FROM BoletinContenidos WHERE IdBoletin='".$idboletin."';";
	
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
	$requete ="INSERT INTO `BoletinContenidos` (`IdBoletin`, `IdContenido`, `Orden`) VALUES ('".$idboletin."', '".$campos[$titulos[$i]]."', '".$Orden."');";
	mysqli_query($db,$requete);
	break;
   }
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin);
?>