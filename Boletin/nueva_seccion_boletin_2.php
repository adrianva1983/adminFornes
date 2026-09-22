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

$campos=$_POST;
$titulos = array_keys($_POST);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

for ($i=0;$i<count($campos);$i++)
{
   $tipodecampo = explode("/",$titulos[$i]);
   switch ($tipodecampo[0])
   {
     case "secciones":
	$requete = "SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$tipodecampo[1]."'";
	$result2 = mysqli_query($db,$requete);
	if ($result2 = mysqli_query($db, $requete2))
	{
	  while($listado2 = mysqli_fetch_object($result2))
	  {
	    $requete = "DELETE FROM `BoletinContenidos` WHERE `IdBoletin`='".$idboletin."' AND `IdContenido`='".$listado2->IdContenido."';";
	    mysqli_query($db,$requete);
	    $requete = "SELECT MAX(Orden) FROM BoletinContenidos WHERE IdBoletin='".$idboletin."';";
	    $result3 = mysqli_query($db,$requete);
	    if ($result3 = mysqli_query($db, $requete3))
	    {
	    $row = mysql_fetch_row($result3);
	    $Orden=$row[0];
	    }
	    else
	    {
	    $Orden=0;
	    }
	    $Orden++;
	    $requete ="INSERT INTO `BoletinContenidos` (`IdBoletin`, `IdContenido`, `Orden`) VALUES ('".$idboletin."', '".$listado2->IdContenido."', '".$Orden."');";
	    mysqli_query($db,$requete);
	  }
	}
	break;
   }
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin);?>