<?php
//VERSIÓN: v1.1 2014-04-24
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Id = $_GET["Id"];
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/eliminar_cliente-".$_SESSION['idioma'].".conf");
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print $lang["errorPermisos"];
	exit;
}

if ($_SERVER['HTTP_REFERER'] == "")
{
	die ($lang["accesoIncorrecto"]);
	exit;
}
print "<form action=\"/administra/Gestion/eliminar_cliente_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$requete = "SELECT * FROM `Facturas` WHERE `IdCliente` = ".$Id;

//Sacamos los datos del cliente
if ($result = mysqli_query($db, $requete))
{
	print $lang["noPosibleFacturas"];	
	print $lang["volver"]." <input name=\"cancelar\" type=\"submit\" value=\"".$lang["volver"]."\">";
}
else
{
	print "<p>".$lang["seguro"]."</p>";
	print "<input name=\"Id\" type=\"hidden\" value=\"".$Id."\">";
	print "<input name=\"Idioma\" type=\"hidden\" value=\"".$_SESSION['idioma']."\">";
	print "<input class=\"boton_riesgo\" name=\"borrar\" type=\"submit\" value=\"".$lang["confirmar"]."\">";
	print "<input class=\"boton\" name=\"cancelar\" type=\"submit\" value=\"".$lang["cancelar"]."\">";
}
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>