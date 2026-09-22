<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Usuarios` WHERE `Id`='".$usuario."'";

$listado = mysqli_fetch_object($result);
if (($listado->NivelAcceso<=$_SESSION['usuario_nivel'])AND($_SESSION['usuario_id']!=$usuario))
{
	die ("No tiene suficientes permisos para ver los contenidos de este usuario");
	exit;
}
$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdPropietario = '".$usuario."' AND IdContenido = `Contenidos`.Id";

if ($result = mysqli_query($db, $requete))
{
 print "<ul>";
 while($listado = mysqli_fetch_object($result))
 {
	print "<li>";
	//PERMISOS MÍNIMOS PARA EDITAR UN CONTENIDO COORDINADOR O PROPIETARIO DE CONTENIDO
        if ($_SESSION['usuario_id']==$listado->IdPropietario)
	{
	//Hago una consulta para buscar la ruta del contenido
	$requete2 = "SELECT * FROM `Secciones` WHERE `Id`='".$listado->IdSeccion."'";
	
	$listado2 = mysqli_fetch_object($result2);
	//Enlazo para poder editar el contenido directamente
	print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$listado->IdContenido."&ruta=".substr($listado2->Path,1)."/".$listado2->NomFich."&seccion=".$listado->IdSeccion."&tipocontenido=".$listado->IdTipoContenido."\"><img src=\"/administra/Imagenes/contenido.png\">";
	print $listado->Titulo;
	print "</a>";
	}
	else
	{
	print $listado->Titulo;
	}
	print "</li>";
 }
 print "</ul>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

?>