<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/buscar_reserva-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=reservas\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><br/><h1>".$lang["buscarReservas"]."</h1></li>";
print "<li><label for=\"IdReserva\"><strong>".$lang["ID"].":</strong> </label><input id=\"IdReserva\" name=\"IdReserva\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></li>";
print "<li><label for=\"IdRestaurante\"><strong>".$lang["IDRestaurante"].":</strong> </label><input id=\"IdRestaurante\" name=\"IdRestaurante\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></li>";
print "<li><label for=\"Nombre\"><strong>".$lang["nombre"].":</strong> </label><input id=\"Nombre\" name=\"Nombre\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></li>";
print "<li><label for=\"Telefono\"><strong>".$lang["telefono"].":</strong> </label><input id=\"Telefono\" name=\"Telefono\" type=\"text\" value=\"\" size=\"10\" maxlength=\"10\"></li>";
print "<li><label for=\"Estado\"><strong>".$lang["estado"].":</strong> </label><select id=\"Estado\" name=\"Estado\"><option value=\"\"></option><option value=\"Pendiente\">".$lang["pendiente"]."</option><option value=\"Rechazado\">".$lang["rechazada"]."</option><option value=\"NoDisponibilidad\">".$lang["nodisponible"]."</option><option value=\"Confirmada\">".$lang["confirmada"]."</option><option value=\"Fraude\">".$lang["fraudulenta"]."</option></select></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
?>