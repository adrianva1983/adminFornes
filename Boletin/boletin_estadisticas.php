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

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Miramos el número de Usuarios por Página configurado
$requete = "SELECT * FROM `Servidor` WHERE `Campo` LIKE '%Secciones - Contenidos por Página%'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$num_usuarios_pagina = $listado->Valor;
	if (!isset($pagina)) $pagina = 0;
}
// Mostramos el título del boletín en curso
$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `Id`=".$idboletin;

$listado = mysqli_fetch_object($result);
//Si es coordinador y no es propietario de este boletín, no puede seleccionarlo
if (($_SESSION['usuario_nivel']==2)&&($_SESSION['usuario_id']!=$listado->IdPropietario))
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/boletin_estadisticas-".$_SESSION['idioma'].".conf");
print "<h1>".$listado->Asunto."</h1>";

// Hacemos una consulta para ver los contenidos seleccionados para este boletín
$requete = "SELECT `Contenidos`.Titulo Titulo,`Contenidos`.Tipo Tipo,`BoletinContenidos`.Clicks Clicks FROM `BoletinContenidos`, `Contenidos` WHERE IdBoletin = ".$idboletin." AND `BoletinContenidos`.IdContenido = `Contenidos`.Id ORDER BY `BoletinContenidos`.Orden";

// Listamos los contenidos existentes
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		//AMPLIACION DE TIPO TEXTO	
		print "<strong>".$listado->Titulo."</strong>";	
		print " (".$listado->Clicks." clicks)";
		print "</li>";
	}
}
else print $lang["noContenidos"].".";
print "</ul>";
print "<hr>";
$requete = "SELECT * FROM `BoletinUsuarios`,`Usuarios` WHERE IdBoletin=".$idboletin." AND IdUsuario=`Usuarios`.Id";

$total_usuarios_pagina = mysqli_num_rows($result);
 if (isset($num_usuarios_pagina))
 {
   //PAGINACIÓN
	 print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_usuarios_pagina."</p>";
	 print "<p>";	 
	 $max_pagina = intval($total_usuarios_pagina/$num_usuarios_pagina);	 
	 for ($i=0;($i<($max_pagina + 1));$i++)
	 {
		 if ($pagina == $i) print "<strong>".$i."</strong> - ";
		 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
	 }	 
	 print "</p>";
 }
print "<hr>";
//USUARIOS INCLUIDOS
//------------------
$requete = "SELECT * FROM `BoletinUsuarios`,`Usuarios` WHERE IdBoletin=".$idboletin." AND IdUsuario=`Usuarios`.Id";
switch ($orden_listado)
{
	case "visualizaciones":
			$requete.= " ORDER BY `BoletinUsuarios`.Vistas DESC";
		break;
	case "clicks":
			$requete.= " ORDER BY `BoletinUsuarios`.Clicks DESC";
		break;
}
$requete.=  " LIMIT ".($pagina * $num_usuarios_pagina).",".$num_usuarios_pagina;

if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>".$lang["Nombre"]."</th><th>".$lang["Provincia"]."</th><th>".$lang["Pais"]."</th><th>".$lang["Email"]."</th><th><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."&orden_listado=visualizaciones&pagina=".$pagina."\">".$lang["Visualizaciones"]."</a></th><th><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."&orden_listado=clicks&pagina=".$pagina."\">".$lang["Clicks"]."</a></th><th>&nbsp;</th></tr>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		print "<td>".$listado->Nombre."</td>";				
		print "<td>".$listado->Provincia."</td>";		
		print "<td>".$listado->Pais."</td>";
		print "<td>".$listado->Email."</td>";
		print "<td>".$listado->Vistas."</td>";
		print "<td>".$listado->Clicks."</td>";
		print "<td>";
		switch ($listado->Estado) 
		{
			case "Enviado":
				if ((($listado->Vistas)>0)||(($listado->Clicks)>0)) print "<img src=\"/administra/Imagenes/bullet_green.png\" alt=\"".$lang["Enviado"]."\" title=\"".$lang["Enviado"]."\" />";
				else print "<img src=\"/administra/Imagenes/bullet_orange.png\" alt=\"".$lang["EnviadoPendiente"]."\" title=\"".$lang["EnviadoPendiente"]."\" />";
				break;
			case "Fallido":
				print "<img src=\"/administra/Imagenes/bullet_red.png\" alt=\"".$lang["Fallido"]."\" title=\"".$lang["Fallido"]."\" />";
				break;
			case "Pendiente":
				print "<img src=\"/administra/Imagenes/bullet_white.png\" alt=\"".$lang["Pendiente"]."\" title=\"".$lang["Pendiente"]."\" />";
				break;
		}
		print "</td>";
		print "</tr>";
	}
	print "</table>";
}
else
{
  print $lang["noUsuarios"].".";
}
 if (isset($num_usuarios_pagina))
 {
   //PAGINACIÓN
	 print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_usuarios_pagina."</p>";
	 print "<p>";
	 $max_pagina = intval($total_usuarios_pagina/$num_usuarios_pagina);	 
	 for ($i=0;($i<($max_pagina + 1));$i++)
	 {
		 if ($pagina == $i) print "<strong>".$i."</strong> - ";
		 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$idboletin."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
	 }	
	 print "</p>";
 }
//USUARIOS FIN
//------------
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>