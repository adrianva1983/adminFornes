<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/permisos_GRUPOS-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Carpetas/permisos_2.php\" enctype=\"multipart/form-data\" method=\"GET\">";
print "<input type=\"hidden\" name=\"ruta\" value=\"".$ruta."\">";
print "<input type=\"hidden\" name=\"seccion\" value=\"".$seccion."\">";

//SUSCRIPCIÓN DE GRUPOS
$requete = "SELECT * FROM `Grupos` ORDER BY `Nombre`";

print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		$requete2 = "SELECT * FROM `Permisos` WHERE `IdGrupoSuscrito`='".$listado->Id."' AND `IdSeccion`= '".$seccion."'";
		
		if ($result2 = mysqli_query($db, $requete2))
		//Si tiene permisos en el apartado le informamos de sus permisos y permitimos cambiarlos
		{
			$listado2 = mysqli_fetch_object($result2);
			$requete3 = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
			
			print "<select class=\"suscripcion\" name=\"CambioNivelGrupo/".$listado->Id."\">";
			print "<option value=\"".$lang["quitarPermisos"]."\">".$lang["quitarPermisos"]."</option>";
			while($listado3 = mysqli_fetch_object($result3))
			{
				if ($listado3->Nivel>$_SESSION['usuario_nivel'])
				{
					if ($listado3->Nivel == $listado2->Nivel) print "<option selected value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
					else print "<option value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
				}
			}
			print "</select>";
			print "<img src=\"/administra/Imagenes/grupos.png\">";
			print $listado->Nombre."<br/>";
		}
		else
		{
			print "<input class=\"suscripcion\" name=\"grupos[]\" type=\"checkbox\" value=\"".$listado->Id."\">";
			print "<img src=\"/administra/Imagenes/grupos.png\">";
			print $listado->Nombre."<br/>";
		}
		print "</li>";
	}
}
print "</ul>";
print "<p>".$lang["aLaSeleccion"];
$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";

print "<select class=\"suscripcion\" name=\"NivelAcceso\">";
while($listado = mysqli_fetch_object($result))
{
	if ($listado->Nivel>$_SESSION['usuario_nivel'])
	{
		print "<option value=\"".$listado->Nivel."\">".$listado->Nombre."</option>";		    
	}
}
print "</select></p>";

print "<input class=\"boton\" type=\"submit\" value=\"".$lang["aceptar"]."\">";
print "</form>";

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>