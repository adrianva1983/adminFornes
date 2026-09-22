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
?>
<?php
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/nueva_reserva1-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Comercio/nueva_reserva_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
print "<li><label for=\"Restaurante\"><strong>".$lang["restaurante"].":</strong> </label><input id=\"Restaurante\" name=\"Restaurante\" type=\"text\" value=\"".$titulocontenido."/".$contenido."/".$seccion."\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Nombre\"><strong>".$lang["nombre"].":</strong> </label><input id=\"Nombre\" name=\"Nombre\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Telefono\"><strong>".$lang["telefono"].":</strong> </label><input id=\"Telefono\" name=\"Telefono\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Email\"><strong>".$lang["email"].":</strong> </label><input id=\"Email\" name=\"Email\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"></li>";
print "<li><label for=\"Dia\"><strong>".$lang["dia"].":</strong> </label><input id=\"Dia\" name=\"Dia\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"> ".$lang["formatoDia"]."</li>";
print "<li><label for=\"Hora\"><strong>".$lang["hora"].":</strong> </label><input id=\"Hora\" name=\"Hora\" type=\"text\" value=\"\" size=\"50\" maxlength=\"255\"> ".$lang["formatoHora"]."</li>";
print "<li><label for=\"Comensales\"><strong>".$lang["comensales"].":</strong> </label><input id=\"Comensales\" name=\"Comensales\" type=\"text\" value=\"\" size=\"3\" maxlength=\"3\"></li>";
print "<li><label for=\"Alerta\"><strong>".$lang["alerta"].":</strong> </label><br/>".$lang["SMS"]." <input id=\"Alerta\" name=\"Alerta\" type=\"radio\" value=\"SMS\"> ".$lang["llamada"]." <input id=\"Alerta\" name=\"Alerta\" type=\"radio\" value=\"Llamada\"> ".$lang["mail"]." <input id=\"Alerta\" name=\"Alerta\" type=\"radio\" value=\"Mail\" checked></li>";
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>