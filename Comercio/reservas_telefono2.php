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
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/reservas_telefono2-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Comercio/reservas_telefono3.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<input style=\"border:0px;\" type=\"hidden\" name=\"provincia\" value=\"".$provincia."\">";
print "<ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Servidor` WHERE `Campo` = 'Telefono Reservas' AND `Valor` LIKE '%".$provincia."%'";

if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$valor = explode("/",$listado->Valor);
		print "<li><br/><h3>".$valor[0]."</h3></li>";
		print "<li><label for=\"Telefono\"><strong>".$lang["telefono"].":</strong> </label><input id=\"Telefono\" name=\"Telefono\" type=\"text\" value=\"".$valor[1]."\" size=\"10\" maxlength=\"10\"></li>";
		print "<li><label for=\"Reserva\"></strong>".$lang["tipo"].":<strong> </label><br/>";
		if ($valor[2] == "todos") print $lang["todos"]." <input id=\"Reserva\" name=\"Reserva\" type=\"radio\" value=\"todos\" checked> ";
		else print $lang["todos"]." <input id=\"Reserva\" name=\"Reserva\" type=\"radio\" value=\"todos\"> ";
		if ($valor[2] == "online") print $lang["online"]." <input id=\"Reserva\" name=\"Reserva\" type=\"radio\" value=\"online\" checked> ";
		else print $lang["online"]." <input id=\"Reserva\" name=\"Reserva\" type=\"radio\" value=\"online\"> ";
		if ($valor[2] == "ninguno") print $lang["ninguno"]." <input id=\"Reserva/\" name=\"Reserva\" type=\"radio\" value=\"ninguno\" checked></li>";
		else print $lang["ninguno"]." <input id=\"Reserva\" name=\"Reserva\" type=\"radio\" value=\"ninguno\"></li>";
	}
}
print "</ul>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "<input type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>