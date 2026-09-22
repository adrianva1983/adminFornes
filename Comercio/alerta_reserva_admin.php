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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/alerta_reserva_admin-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Servidor` WHERE `Campo` = 'Reservas - Alerta SMS'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
}
print "<p>".$lang["instrucciones"].".</p>";
print "<form action=\"/administra/Comercio/alerta_reserva_admin_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";	
print "<li>".$lang["movil"].": <input name=\"movil\" type=\"text\" value=\"".$listado->Valor."\" size=\"10\" maxlength=\"10\"></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>