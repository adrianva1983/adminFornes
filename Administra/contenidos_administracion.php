<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

//Consultamos el listado de Contenidos de Administración
$requete = "SELECT * FROM `Contenidos` WHERE Tipo = 'administracion'";


if ($result = mysqli_query($db, $requete))
{
 print "<hr><ul>";
 while($listado = mysqli_fetch_object($result))
 {
	print "<li>";
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Administra&herramienta=editar_contenido_administracion&contenido=".$listado->Id."\"><img src=\"/administra/Imagenes/contenidos_administracion.png\">";
	print $listado->Titulo;
	print "</a>";
	print "</li>";
 }
 print "</ul>";
}
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
