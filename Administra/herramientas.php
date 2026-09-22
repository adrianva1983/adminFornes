<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
// NIVEL MÍNIMO SUPERADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "<p class=\"mensajeKO\">No tiene permisos para acceder a este &aacute;rea</p>";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/herramientas-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Administra/herramientas_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
print "<ul>";
$requete = "SELECT * FROM `Herramientas` WHERE `IdPadre` IS NULL AND `Idioma`='".$_SESSION['idioma']."';";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		print "<input style=\"display:inline;\" name=\"herramientas[]\" type=\"checkbox\" value=\"".$listado->IdHerramienta."\"";
		if ($listado->Activado=="si") print " checked";
		print "> ";
		print $listado->Nombre;
		$requete2 = "SELECT * FROM `Herramientas` WHERE `IdPadre`=".$listado->IdHerramienta." AND `Idioma`='".$_SESSION['idioma']."' AND `Accion`='no';";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			print "<ul>";
			while($listado2 = mysqli_fetch_object($result2))
			{
				print "<li>";				
				print "<input style=\"display:inline;\" name=\"herramientas[]\" type=\"checkbox\" value=\"".$listado2->IdHerramienta."\"";
				if ($listado2->Activado=="si") print " checked";
				print "> ";
				print $listado2->Nombre;
				print "</li>";
			}
			print "</ul>";			
			$requete3 = "SELECT * FROM `Herramientas` WHERE `IdPadre`=".$listado->IdHerramienta." AND `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si';";
			
			if ($result3 = mysqli_query($db, $requete3))
			{				
				print "<ul style=\"border:1px solid #EEE;\">";
				while($listado3 = mysqli_fetch_object($result3))
				{
					print "<li>";
					print "<input style=\"display:inline;\" name=\"herramientas[]\" type=\"checkbox\" value=\"".$listado3->IdHerramienta."\"";
					if ($listado3->Activado=="si") print " checked";
					print "> ";
					print $listado3->Nombre;
					print "</li>";
				}
				print "</ul>";
			}			
		}
		print "</li>";
	}
}
print "<hr>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");	
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>