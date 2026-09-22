<?php
// PRODUCE:
// Visualiza un listado con las secciones (de la base de datos) donde el campo título tiene coincidencia con el campo de texto que se introduce.
// El listado mostrará el Título del contenido y un listado de publicaciones con enlace a la edición del contenido en esa zona.

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
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/nueva_reserva-".$_SESSION['idioma'].".conf");
function server_url()
{  
   $proto = "http" .
       ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
   $server = isset($_SERVER['HTTP_HOST']) ?
       $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
   return $proto . $server;
}
   
function redirect_rel($relative_url)
{
   $url = server_url() . dirname($_SERVER['PHP_SELF']) . "/" . $relative_url;
   if (!headers_sent())
   {
       header("Location: $url");
   }
   else
   {
       echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">\r\n";
   }
}
$busca=$_GET["busca"];
$buscar=$_GET["buscar"];
$submit=$_GET["submit"];
$modulo=$_GET["modulo"];
$herramienta=$_GET["herramienta"];

print "<form name=\"fbusca\" action=\"/administra/Interface/herramienta.php\">";
print "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
print "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";
print "<p><input name=\"busca\" value=\"".$buscar."\">";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["buscar"]."\"></p>";
print "</form>";

$busca=trim($busca);

// Habrá dos ficheros:
// ---- busca.php: Será un formulario con un campo de texto que será la búsqueda que haga el usuario. Enlazará pasando ese parámetro a busca_2.php
// ---- busca_2.php: Muestra las secciones de la base de datos donde el campo título tiene coincidencia con el campo de texto que se introdujo en el form
if ($busca!="")
{
 //echo "<li>busca=$busca buscar=$buscar </li>";
 include "buscar_nuevareserva.php";
}
?>