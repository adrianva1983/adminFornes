<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/servidor-".$_SESSION['idioma'].".conf");
print "<img src=\"/administra/Imagenes/brick_go.png\" title=\"".$lang["actualizar"]."\" alt=\"".$lang["actualizar"]."\"> :: ".$lang["actualizar"]."<br/>";
print "<img src=\"/administra/Imagenes/computer_edit.png\" title=\"".$lang["editarConfiguracion"]."\" alt=\"".$lang["editarConfiguracion"]."\"> :: ".$lang["editarConfiguracion"]."<br/>";
print "<img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["ver"]."\" alt=\"".$lang["ver"]."\"> :: ".$lang["ver"]."<br/>";
print "<img src=\"/administra/Imagenes/lock.png\" title=\"".$lang["admin"]."\" alt=\"".$lang["admin"]."\"> :: ".$lang["admin"]."<br/>";
print "<hr/>";
print "<img src=\"/administra/Imagenes/bullet_star.png\" title=\"".$lang["ok"]."\" alt=\"".$lang["ok"]."\"> :: ".$lang["ok"]."<br/>";
print "<img src=\"/administra/Imagenes/bullet_error.png\" title=\"".$lang["ko"]."\" alt=\"".$lang["ko"]."\"> :: ".$lang["ko"]."<br/>";
$requete = "SELECT * FROM `Servidor_webs`";

print "<table>";
print "<tr>";
print "<th>".$lang["acciones"]."</th>";
print "<th>".$lang["dominio"]."</th>";
print "<th>".$lang["version"]."</th>";
print "<th>&nbsp;</th>";
print "</tr>";
if ($result = mysqli_query($db, $requete))
{
	$paridad = true;
	while ($listado = mysqli_fetch_object($result))
	{
		if ($paridad) 
		{
			$paridad = false;
			print "<tr>";
		}
		else 
		{
			$paridad = true;
			print "<tr id=\"par\">";
		}
		print "<td>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Administra&herramienta=editar_web_servidor&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/computer_edit.png\" title=\"".$lang["editarConfiguracion"]."\" alt=\"".$lang["editarConfiguracion"]."\"></a>";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Administra&herramienta=actualizar_web&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/brick_go.png\" title=\"".$lang["actualizar"]."\" alt=\"".$lang["actualizar"]."\"></a>";
		print "<a href=\"http://".$listado->Dominio."\" target=\"_blank\"><img src=\"/administra/Imagenes/ver.png\" title=\"".$lang["ver"]."\" alt=\"".$lang["ver"]."\"></a>";
		print "<a href=\"http://".$listado->Dominio."/administra\" target=\"_blank\"><img src=\"/administra/Imagenes/lock.png\" title=\"".$lang["admin"]."\" alt=\"".$lang["admin"]."\"></a>";
		print "</td>";
		print "<td>".$listado->Dominio."</td>";
		print "<td>";
		if ($listado->Estado=="ko") print "<span style=\"color:#FF0000\">";
		print $listado->Version.".".$listado->Subversion;
		if ($listado->Estado=="ko") print "</span>";
		print "</td>";
		if ($listado->Estado=="ko") print "<td><img src=\"/administra/Imagenes/bullet_error.png\" title=\"".$lang["ko"]."\" alt=\"".$lang["ko"]."\"></td>";
		else print "<td><img src=\"/administra/Imagenes/bullet_star.png\" title=\"".$lang["ok"]."\" alt=\"".$lang["ok"]."\"></td>";
		print "</tr>";
	}
}
print "</table>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
