<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/suscripcion_usuarios_grupos_GRUPOS-".$_SESSION['idioma'].".conf");

print "<form action=\"/administra/Carpetas/suscripcion_usuarios_grupos_2.php?ruta=".$ruta."&seccion=".$seccion."\" enctype=\"multipart/form-data\" method=\"POST\">";

//SUSCRIPCIÓN DE GRUPOS
$requete = "SELECT * FROM `Grupos` ORDER BY `Nombre`";

print $lang["nota"];
print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
 print "<li>";
 $requete2 = "SELECT * FROM `Permisos` WHERE `IdGrupoSuscrito`='".$listado->Id."' AND `IdSeccion`= '".$seccion."'";
 
 if ($result2 = mysqli_query($db, $requete2))
 //Si está suscrito al apartado le marcamos el check y le fijamos la fecha
 {
   $listado2 = mysqli_fetch_object($result2);
   print "<input class=\"suscripcion\" name=\"grupos/".$listado->Id."\" type=\"checkbox\" value=\"".$listado->Id."\" checked>";
   print "<img src=\"/administra/Imagenes/grupos.png\">";
   print $listado->Nombre."<br/>";
   print $lang["fechaFin"].": <input class=\"suscripcion\" name=\"FechaGrupo/".$listado->Id."\" type=\"text\" value=\"".$listado2->FinSuscripcion."\"> YYYY-MM-DD";
 }
 else
 {
   print "<input class=\"suscripcion\" name=\"grupos/".$listado->Id."\" type=\"checkbox\" value=\"".$listado->Id."\">";
   print "<img src=\"/administra/Imagenes/grupos.png\">";
   print $listado->Nombre."<br/>";
   print $lang["fechaFin"].": <input class=\"suscripcion\" name=\"FechaGrupo/".$listado->Id."\" type=\"text\" value=\"\"> YYYY-MM-DD"; 
 }
 print "</li>";
 }
}
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["suscribir"]."\">";
print "</form>";

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>