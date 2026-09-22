<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
function cambiaf_a_normal($fecha){
	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
	return $lafecha;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Especifico/idiomas/versiones-".$_SESSION['idioma'].".conf");
// Hacemos una consulta para ver cuantas webs están instaladas
$requete = "SELECT * FROM `Servidor_webs` WHERE `Id`='".$id."'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$dbN= mysql_connect("localhost", $listado->UsuarioBD, $listado->PassBD);
	mysql_select_db($listado->BaseDatosBD);
}
print "<h1>".$lang["herramientas"]."</h1>";
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdPadre` IS NULL";

if ($result = mysqli_query($db, $requete))
{	
	while($listado = mysqli_fetch_object($result))
	{		
		$requeteN = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdPadre` IS NULL AND `IdHerramienta`='".$listado->IdHerramienta."'";
		$resultN = mysql_query($requeteN,$dbN);
		print "<h2>";
		if (($resultN) && (mysqli_num_rows($resultN)>0))
		{
			$listadoN = mysql_fetch_object($resultN);
			if ($listadoN->Activado == "si") 
			{
				if ($listadoN->Version != $listado->Version) print "<img title=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" alt=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" src=\"../Imagenes/error.png\"/> ";
				else print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
			}
			else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
		}
		else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
		print $listado->Nombre."</h2>";
		$requete2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdPadre` ='".$listadoN->IdHerramienta."'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			print "<ul>";
			while($listado2 = mysqli_fetch_object($result2))
			{
				print "<li>";
				print "<h3>";
				$requeteN2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdHerramienta`='".$listado2->IdHerramienta."'";
				$resultN2 = mysql_query($requeteN2,$dbN);
				if (($resultN2) && (mysqli_num_rows($resultN2)>0))
				{
					$listadoN2 = mysql_fetch_object($resultN2);
					if ($listadoN2->Activado == "si") 
					{
						if ($listadoN2->Version != $listado2->Version) print "<img title=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" alt=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" src=\"../Imagenes/error.png\"/> ";
						else print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
					}
					else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
				}
				print $listadoN2->Nombre."</h3>";
				print "</li>";
			}
			print "</ul>";
			$requete2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='si' AND `IdPadre` ='".$listado->IdHerramienta."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				print "<div style=\"background:#CCCCCC;\"><strong>".$lang["acciones"].":</strong>";
				print "<ul>";
				while($listado2 = mysqli_fetch_object($result2))
				{
					print "<li>";
					$requeteN2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='si' AND `IdHerramienta`='".$listado2->IdHerramienta."'";
					$resultN2 = mysql_query($requeteN2,$dbN);
					if (($resultN2) && (mysqli_num_rows($resultN2)>0))
					{
						$listadoN2 = mysql_fetch_object($resultN2);
						if ($listadoN2->Activado == "si") 
						{
							if ($listadoN2->Version != $listado2->Version) print "<img title=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" alt=\"".$lang["version"]." ".$lang["enSistema"].": ".$listadoN->Version." - ".$lang["version"]." ".$lang["enCentral"].": ".$listado->Version."\" src=\"../Imagenes/error.png\"/> ";
							else print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
						}
						else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
					}
					print $listado2->Nombre."</li>";
				}
				print "</ul>";
				print "</div>";
			}						
		}
		print "<hr/>";
	}	
}
print "<h1>".$lang["herramientasPersonalizadas"]."</h1>";
$requeteN = "SELECT * FROM `HerramientasPersonalizadas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdPadre` IS NULL";
$resultN = mysql_query($requeteN,$dbN);
if (($resultN) && (mysqli_num_rows($resultN)>0))
{	
	while($listadoN = mysql_fetch_object($resultN))
	{
		print "<h2>";
		if ($listadoN->Activado == "si") 
		{			
			print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
		}
		else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";		
		print $listadoN->Nombre."</h2>";
		$requeteN2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='no' AND `IdPadre` ='".$listadoN->IdHerramienta."'";
		$resultN2 = mysql_query($requeteN2,$dbN);
		if (($resultN2) && (mysqli_num_rows($resultN2)>0))
		{
			print "<ul>";
			while($listadoN2 = mysql_fetch_object($resultN2))
			{
				print "<li>";
				print "<h3>";
				if ($listadoN2->Activado == "si") 
				{					
					print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
				}
				else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
				print $listadoN2->Nombre."</h3>";
				print "</li>";
			}
			print "</ul>";
			$requeteN2 = "SELECT * FROM `Herramientas` WHERE `Idioma`= '".$_SESSION['idioma']."' AND`Accion`='si' AND `IdPadre` ='".$listadoN->IdHerramienta."'";
			$resultN2 = mysql_query($requeteN2,$dbN);
			if (($resultN2) && (mysqli_num_rows($resultN2)>0))
			{
				print "<div style=\"background:#CCCCCC;\"><strong>".$lang["acciones"].":</strong>";
				print "<ul>";
				while($listadoN2 = mysql_fetch_object($resultN2))
				{
					print "<li>";
					if ($listadoN2->Activado == "si") 
					{						
						print "<img title=\"".$lang["activada"]."\" alt=\"".$lang["activada"]."\" src=\"../Imagenes/tick.png\"/> ";
					}
					else print "<img title=\"".$lang["desactivada"]."\" alt=\"".$lang["desactivada"]."\" src=\"../Imagenes/cross.png\"/> ";
					print $listadoN2->Nombre."</li>";
				}
				print "</ul>";
				print "</div>";
			}		
		}
		print "<hr/>";
	}
}
$dbN = mysql_close($dbN);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
