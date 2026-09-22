<?php
//VERSIÓN: v1.0 2014-02-11
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
 //$accion:
 //0-Solo listar la lista de orden
 //1-Ordena ascendiendo
 //2-Ordena descendiendo
 //3-Ordena subiendo $elemento
 //4-Ordena bajando $elemento
 //5-Primero el $elemnto
 //6-Ultimo el $elemento

//No uses el include al menos que quieras hacer pruebas
//Si quieres probarlo todo cambia test_ordenacion.php y descomenta estas dos lineas
//include "test_ordenacion.php";
//die;
//Para ver lo que hace usa esta url:http://www.guiarestaurantes.org/administra/Carpetas/funciones/ordenacion.php?test=s

//Esto funciona si se pasa por argumentos GET a la URL
$_ordena=$_GET["ordena"];
$_familia=$_GET["familia"];
$_item=$_GET["item"];
$_iid=$_GET["iid"];
$_accion=$_GET["accion"];
$_elemento=$_GET["elemento"];
$ampliacion = $_GET["ampliacion"];
$test=$_GET["test"];
$test=false;
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
require("ordenacion_2.php");

if ($_ordena=="ordena") 
{
 //ordenaTotal($_familia,$_item,$_accion);
 ordena($_familia,$_item,$_iid,$_accion,$_elemento,$db);
}
if ($_ordena=="total") ordenaTotal($_familia,$_item,$_accion);
//Recargamos el directorio en curso o contenido en curso
if ($_familia=="Publicaciones")
{
	if ($ampliacion=="si")
	{	
		header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&seccion=".$seccion."&ruta=".$ruta."&contenido=".$_iid);
	}
	else
	{
		switch ($accion) 
		{
			case "5":
				header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&pagina=0");
				break;
			case "6":
				require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
				//Miramos el número de Contenidos por Página configurado
				$requete = "SELECT * FROM `Servidor` WHERE `Campo` LIKE '%Secciones - Contenidos por Página%'";
				$result = mysqli_query($db,$requete);
				if (($result) && (mysqli_num_rows($result)>0))				
				{
					$listado = mysqli_fetch_object($result);
					$num_contenidos_pagina = $listado->Valor;
					if (!isset($pagina)) $pagina = 0;
					$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdSeccion = ".$seccion." AND IdContenido = `Contenidos`.Id AND `Contenidos`.Tipo='contenido'";
					$result = mysqli_query($db,$requete);
					$total_contenidos_pagina = mysqli_num_rows($result);		
				}
				$max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
				require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");				
				header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&pagina=".$max_pagina);
			break;
			default:				
				header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta."&pagina=".$pagina);
			break;
		}
	}
}
else
{
	if ($seccion!="NULL")
	{
		header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
	else 
	{
		header("Location:../../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}	
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>