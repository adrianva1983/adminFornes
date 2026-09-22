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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/boletin-".$_SESSION['idioma'].".conf");
// Mostramos el título del boletín en curso
$requete = "SELECT * FROM `BoletinConfiguracion` WHERE `Id`=".$idboletin;

$listado = mysqli_fetch_object($result);
$zonas = $listado->Zonas;
//Si es coordinador y no es propietario de este boletín, no puede seleccionarlo
if (($_SESSION['usuario_nivel']==2)&&($_SESSION['usuario_id']!=$listado->IdPropietario))
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/page_link.png\" title=\"".$lang["resumen"]."\" alt=\"".$lang["resumen"]."\"/> :: ".$lang["resumen"]."<br/>";
print "<img src=\"/administra/Imagenes/contenido.png\" title=\"".$lang["desplegado"]."\" alt=\"".$lang["desplegado"]."\"/> :: ".$lang["desplegado"]."<br/>";
print "</div>";
print "<h1>".$listado->Asunto."</h1>";
// Hacemos una consulta para ver los contenidos seleccionados para este boletín
$requete = "SELECT `BoletinContenidos`.FormaVisualizacion,`BoletinContenidos`.Orden Orden,`Contenidos`.Tipo Tipo,`BoletinContenidos`.Id Id,`Contenidos`.Titulo Titulo, `Contenidos`.Id Idd, `BoletinContenidos`.Zona Zona FROM `BoletinContenidos`, `Contenidos` WHERE IdBoletin = ".$idboletin." AND `BoletinContenidos`.IdContenido = `Contenidos`.Id ORDER BY `BoletinContenidos`.Orden ";

// Listamos los contenidos existentes
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{		
		$orden=$listado->Orden;
		print "<li>";
		if ($zonas>0)
		{        	
			switch ($listado->Zona)
			{
				case "0":
					print "<span style=\"background:#FF0000;color:#FFFFFF\">".$lang["zona"]." ".$listado->Zona."</span> ";
					break;
				case "1":
					print "<span style=\"background:#00FF00;color:#FFFFFF\">".$lang["zona"]." ".$listado->Zona."</span> ";
					break;
				case "2":
					print "<span style=\"background:#0000FF;color:#FFFFFF;\">".$lang["zona"]." ".$listado->Zona."</span> ";
					break;
				case "3":
					print "<span style=\"background:#000000;color:#FFFFFF;\">".$lang["zona"]." ".$listado->Zona."</span> ";
					break;
			}
		}
		print "<a href=\"/administra/Boletin/funciones/borrar_contenido.php?idboletin=".$idboletin."&contenido=".$listado->Idd."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrar"]."\" alt=\"".$lang["borrar"]."\"></a>";		
/*
ordenacion.php?
ordena=ordena&
familia=Secciones&
item=IdPadre&
iid=".$seccion."&
elemento=".$orden."&
accion=3&
ruta=".$ruta."&
seccion=".$seccion
*/        
		$TT="top_arriba";
		$accion=5;
		print "<a href=\"/administra/Boletin/funciones/ordenacion.php?idboletin=".$idboletin."&ordena=ordena&familia=BoletinContenidos&item=IdBoletin&iid=$idboletin&elemento=$orden&accion=$accion\"><img src=\"/administra/Imagenes/$TT.png\" title=\"".$lang["toparriba"]."\" alt=\"".$lang["toparriba"]."\"></a>";

		$accion=3;
		$TT="arriba";
		print "<a href=\"/administra/Boletin/funciones/ordenacion.php?idboletin=".$idboletin."&ordena=ordena&familia=BoletinContenidos&item=IdBoletin&iid=$idboletin&elemento=$orden&accion=$accion\"><img src=\"/administra/Imagenes/$TT.png\" title=\"".$lang["arriba"]."\" alt=\"".$lang["arriba"]."\"></a>";

		$accion=4;
		$TT="abajo";
		print "<a href=\"/administra/Boletin/funciones/ordenacion.php?idboletin=".$idboletin."&ordena=ordena&familia=BoletinContenidos&item=IdBoletin&iid=$idboletin&elemento=$orden&accion=$accion\"><img src=\"/administra/Imagenes/$TT.png\" title=\"".$lang["abajo"]."\" alt=\"".$lang["abajo"]."\"></a>";

		$accion=6;
		$TT="top_abajo";
		print "<a href=\"/administra/Boletin/funciones/ordenacion.php?idboletin=".$idboletin."&ordena=ordena&familia=BoletinContenidos&item=IdBoletin&iid=$idboletin&elemento=$orden&accion=$accion\"><img src=\"/administra/Imagenes/$TT.png\" title=\"".$lang["topabajo"]."\" alt=\"".$lang["topabajo"]."\"></a>";
		if (($listado->Tipo)=="contenido")
		{
		
			if ($listado->FormaVisualizacion == "desplegado") 
			{
				print "<img src=\"/administra/Imagenes/contenido.png\" title=\"".$lang["desplegado"]."\" alt=\"".$lang["desplegado"]."\"/> ";
				print "<a href=\"/administra/Boletin/funciones/visualizacion_contenido.php?idboletin=".$idboletin."&contenido=".$listado->Idd."&visualizacion=resumen\">(".$lang["visualizacion"].")</a> ";
			}
			else 
			{
				print "<img src=\"/administra/Imagenes/page_link.png\" title=\"".$lang["resumen"]."\" alt=\"".$lang["resumen"]."\"/> ";
				print "<a href=\"/administra/Boletin/funciones/visualizacion_contenido.php?idboletin=".$idboletin."&contenido=".$listado->Idd."&visualizacion=desplegado\">(".$lang["visualizacion"].")</a> ";
			}
			print $listado->Titulo;
		}
		print "</li>";
	}
}
else print $lang["NOcontenidos"].".";
print "</ul>";
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
		 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
	 }	
	 print "</p>";
 }
print "<hr>";
//USUARIOS INCLUIDOS
//------------------
$requete = "SELECT * FROM `BoletinUsuarios`,`Usuarios` WHERE IdBoletin=".$idboletin." AND IdUsuario=`Usuarios`.Id LIMIT ".($pagina * $num_usuarios_pagina).",".$num_usuarios_pagina;

print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 print "<table>";
 print "<tr><th>&nbsp;</th><th>".$lang["Nombre"]."</th><th>".$lang["Empresa"]."</th><th>".$lang["Provincia"]."</th><th>".$lang["Pais"]."</th><th>".$lang["Email"]."</th></tr>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		print "<td><a href=\"/administra/Boletin/funciones/borrar_usuario.php?idboletin=".$idboletin."&idusuario=".$listado->IdUsuario."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrarUsuario"]."\" alt=\"".$lang["borrarUsuario"]."\"></a></td>";
		print "<td>".$listado->Nombre."</td>";		
		print "<td>".$listado->NombreEmpresa."</td>";		
		print "<td>".$listado->Provincia."</td>";
		print "<td>".$listado->Pais."</td>";
		print "<td>".$listado->Email."</td>";			
		print "</tr>";
	}
 print "</table>";
}
else
{
  print $lang["NOusuarios"].".";
}
print "</ul>";
				
		while($listado = mysqli_fetch_object($result))
		{
			print "<tr>";
			$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`='".$listado->IdUsuario."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{							
				$listado2 = mysqli_fetch_object($result2);
			}
			print "<td>".$listado2->Nombre."</td>";
			print "<td>".$listado2->Apellidos."</td>";
			print "<td>".$listado2->NombreEmpresa."</td>";
			print "<td>".$listado2->Municipio."</td>";
			print "<td>".$listado2->Provincia."</td>";
			print "<td>".$listado2->Email."</td>";
			print "<td>".$listado->Vistas."</td>";
			print "<td>".$listado->Clicks."</td>";
			print "</tr>";
		}
		print "</table>";
 if (isset($num_usuarios_pagina))
 {
   //PAGINACIÓN
	 print "<p><strong>".$lang["numeroResultados"].":</strong> ".$total_usuarios_pagina."</p>";
	 print "<p>";
	 $max_pagina = intval($total_usuarios_pagina/$num_usuarios_pagina);	 
	 for ($i=0;($i<($max_pagina + 1));$i++)
	 {
		 if ($pagina == $i) print "<strong>".$i."</strong> - ";
		 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin."&orden_listado=".$orden_listado."&pagina=".$i."\">".$i."</a> - ";
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