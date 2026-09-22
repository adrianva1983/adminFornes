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
$item="Secciones";
$requete = "SELECT `Id`, `Titulo`, `NomFich`,`Path`  FROM `".$item."` WHERE `Titulo` like '%".$busca."%'";
//echo $requete;
$result = mysqli_query($db,$requete);
if (!$result) exit;
if(mysqli_num_rows($result)==0)
{
	echo "<li>Lo siento, no hay nada parecido en la Base de Datos</li>";
	mysqli_free_result($result);	
	exit;
}
$a=array();
$padres=array();
while ($row = mysqli_fetch_assoc($result)) 
{
	if ($_SESSION['usuario_nivel']<=1)
	{
		$requete2="SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$row['Id']."' AND `IdContenido`='".$contenido."'";
		$result2 = mysqli_query($db,$requete2);
		if (($result2) && (mysqli_num_rows($result2)>0))		
		{
			$publicado = true;
		}
		else
		{
			$publicado = false;
		}
		//EL USUARIO ES AL MENOS ADMINISTRADOR, MOSTRAMOS LOS RESULTADOS AL COMPLETO
		$id=$row['Id'];
		$titulo=$row['Titulo'];
		$nomfich=$row['NomFich'];
		$path=$row['Path'];
		$b[]=array();
		$b["Id"]=$id;
		$b["Titulo"]=$titulo;
		$b["NomFich"]=$nomfich;
		if ($path=="") 
		{
			$b["Ruta"]=$nomfich;
		}
		else
		{
			$b["Ruta"]=substr($path,1)."/".$nomfich;
		}
		$b["Publicado"]=$publicado;
		$a[]=$b;
	}
	else
	{
		//ALMACENAMOS LOS IDs SOBRE LOS QUE HAY QUE MIRAR SI TENEMOS PERMISOS. Id Actual y todos sus padres
		$jj=0;
		$padres[$jj]=$id;
		$requete = "SELECT `IdPadre` FROM `Secciones` WHERE `Id`='".$row['Id']."'";     
		$listado = mysqli_fetch_object($result);
		while($listado->IdPadre)
		{
			$jj++;
			$padres[$jj]=$listado->IdPadre;
			$requete = "SELECT `IdPadre` FROM `Secciones` WHERE `Id`='".$listado->IdPadre."'";            
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
			$requete2="SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$row['Id']."' AND `IdContenido`='".$contenido."'";
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))
			{
				$publicado = true;
			}
			else
			{
				$publicado = false;
			}
			$id=$row['Id'];
			$titulo=$row['Titulo'];
			$nomfich=$row['NomFich'];
			$path=$row['Path'];
			$b[]=array();
			$b["Id"]=$id;
			$b["Titulo"]=$titulo;
			$b["NomFich"]=$nomfich;
			if ($path=="") 
			{
				$b["Ruta"]=$nomfich;
			}
			else
			{
				$b["Ruta"]=substr($path,1)."/".$nomfich;
			}
			$b["Publicado"]=$publicado;
			$a[]=$b;
		}
	}
}
mysqli_free_result($result);  
echo "<ul>";
foreach ($a as $d) 
{
	$id=$d['Id'];
	$titulo=$d['Titulo'];
	$nomfich=$d['NomFich'];
	$enruta=$d['Ruta'];
	$publicado=$d['Publicado'];
	if ($publicado)
	{
		print "<li><img src=\"/administra/Imagenes/tick.png\" alt=\"Vinculado\"> ";
	}
	else
	{
		print "<li><input class=\"suscripcion\" name=\"secciones/".$id."\" type=\"checkbox\" value=\"".$id."\">";
	}
	print "<img src=\"/administra/Imagenes/secciones.png\">$titulo</li>";
}
echo "</ul>";
?>