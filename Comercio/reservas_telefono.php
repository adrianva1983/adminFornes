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
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/reservas_telefono-".$_SESSION['idioma'].".conf");
print "<p>".$lang["administrar"].":</p>";
print "<ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Servidor` WHERE `Campo` = 'Telefono Reservas'";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$valor = explode("/",$listado->Valor);
		print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=reservas_telefono2&provincia=".$valor[0]."\">".$valor[0]."</a></li>";
	}
}
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>