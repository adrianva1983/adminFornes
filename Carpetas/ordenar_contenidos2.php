<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
switch ($tipo)
{
  case "alfabetico":
  	$requete = "SELECT * FROM `Contenidos`, `Publicaciones` WHERE `Publicaciones`.IdContenido = `Contenidos`.Id AND `Publicaciones`.IdSeccion=".$seccion." ORDER BY `Titulo`"; 
  break;
}

$orden = 0;
if ($result = mysqli_query($db, $requete))
{ 
	while ($listado = mysqli_fetch_object($result))
	{
		$requete2 = "UPDATE `Publicaciones` SET `Orden` = '".$orden."' WHERE `IdContenido`='".$listado->IdContenido."' AND `IdSeccion`='".$listado->IdSeccion."'";
		mysql_query($requete2,$db);
		$orden++;
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$TipoContenido);
?>
