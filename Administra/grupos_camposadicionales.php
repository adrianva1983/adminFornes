<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$i=0;
$requete = "SELECT * FROM `CamposAdicionalesGrupos` WHERE Tipo = '".$i."'";

if ($result = mysqli_query($db, $requete))
{
	print "<hr><ul>";
	while ($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Administra&herramienta=grupo_camposadicionales&tipo=".$i."\"><img src=\"/administra/Imagenes/grupo_adicionales.png\"> Tipo ".$i." (".mysqli_num_rows($result)." campos) ".$listado->Titulo." ...</a>";
		print "</li>";
		$i++;
		$requete = "SELECT * FROM `CamposAdicionalesGrupos` WHERE Tipo = '".$i."'";
		
	}
	print "</ul>";
}
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
