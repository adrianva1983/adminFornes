<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$item="Secciones";
$requete = "SELECT `Id`, `Titulo`, `NomFich`,`Path`, `Idioma`  FROM `".$item."` WHERE `Titulo` like '%".$busca."%'";
$result = mysqli_query($db,$requete);
if (!$result) exit;
if(mysqli_num_rows($result)==0)
{
	echo "<li>Lo siento, no hay nada parecido en la Base de Datos</li>";
	mysqli_free_result($result);
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	exit;
}
$a=array();
$padres=array();
while ($row = mysqli_fetch_assoc($result)) 
{
	if ($_SESSION['usuario_nivel']<=1)
	{
		//EL USUARIO ES AL MENOS ADMINISTRADOR, MOSTRAMOS LOS RESULTADOS AL COMPLETO
		$id=$row['Id'];
		$titulo=$row['Titulo'];
		$nomfich=$row['NomFich'];
		$idiomas=$row['Idioma'];
		$path=$row['Path'];
		$b[]=array();
		$b["Id"]=$id;
		$b["Titulo"]=$titulo;
		$b["NomFich"]=$nomfich;
		$b["Idioma"]=$idiomas;
		if ($path=="") 
		{
			$b["Ruta"]=$nomfich;
		}
		else
		{
			$b["Ruta"]=$path."/".$nomfich;
		}
		$a[]=$b;
	}
	else
	{
		//ALMACENAMOS LOS IDs SOBRE LOS QUE HAY QUE MIRAR SI TENEMOS PERMISOS. Id Actual y todos sus padres
		$jj=0;
		$padres[$jj]=$id;
		$requete = "SELECT `IdPadre` FROM `Secciones` WHERE `Id`='".$row['Id']."'";
		$result = mysqli_query($db,$requete);
		$listado = mysqli_fetch_object($result);
		while($listado->IdPadre)
		{
			$jj++;
			$padres[$jj]=$listado->IdPadre;
			$requete = "SELECT `IdPadre` FROM `Secciones` WHERE `Id`='".$listado->IdPadre."'";      
			$result = mysqli_query($db,$requete);
			$listado = mysqli_fetch_object($result);
		}   
		//COMPROBAMOS SI EL USUARIO TIENE PERMISOS SOBRE EL RESULTADO
		$jj=0;
		$acceso = false;     
		while ($jj<count($padres))
		{
			$requete="SELECT * FROM `Permisos` WHERE `IdUsuarioSuscrito` ='".$_SESSION['usuario_id']."' AND (`FinSuscripcion`>'".date("Y-m-d H:i:s")."' OR `FinSuscripcion` IS NULL) AND (`InicioSuscripcion`<'".date("Y-m-d H:i:s")."' OR `InicioSuscripcion` IS NULL) AND `IdSeccion`='".$padres[$jj]."';";    
			$result = mysqli_query($db,$requete);
			if (($result) && (mysqli_num_rows($result)>0))
			{
				while($listado = mysqli_fetch_object($result))
				{
					if ($listado->NivelAcceso<4) $acceso=true;
				}
			}
			$jj++;
		}
		if ($acceso)
		{
			//EL USUARIO TIENE PERMISOS SOBRE EL APARTADO RESULTANTE
			$id=$row['Id'];
			$titulo=$row['Titulo'];
			$nomfich=$row['NomFich'];
			$idiomas=$row['Idioma'];
			$path=$row['Path'];
			$b[]=array();
			$b["Id"]=$id;
			$b["Titulo"]=$titulo;
			$b["NomFich"]=$nomfich;
			$b["Idioma"]=$idiomas;
			if ($path=="") 
			{
				$b["Ruta"]=$nomfich;
			}
			else
			{
				$b["Ruta"]=substr($path,1)."/".$nomfich;
			}
			$a[]=$b;
		}
	}
}
mysqli_free_result($result);
foreach ($a as $d) 
{
	$id=$d['Id'];
	$titulo=$d['Titulo'];
	$nomfich=$d['NomFich'];
	$idiomas=$d['Idioma'];
	$enruta=$d['Ruta'];
	echo "<div class=\"hr-line-dashed\"></div><div class=\"search-result\"><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=$id&ruta=$enruta\"><i class=\"fa fa-folder\"></i> <img src=\"/administra/Imagenes/".$idiomas.".png\"> $titulo</a> ($enruta)</div>";
}
?>