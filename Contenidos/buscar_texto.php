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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/buscar_texto-".$_SESSION['idioma'].".conf");
print '
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">';


print '<div class="search-form">';
print "<form name=\"fbusca\" action=\"/administra/Interface/herramienta.php\">";
print "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
print "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";
print "<div class=\"input-group\"><input type=\"text\" class=\"form-control input-lg\" name=\"busca\" value=\"".$buscar."\">";
print "<div class=\"input-group-btn\"><input class=\"btn btn-lg btn-primary\" type=\"submit\" value=\"".$lang["buscar"]."\"></div></div>";
print "</form>";
print '</div>';

$busca=trim($busca);

// Habrá dos ficheros:
// ---- busca.php: Será un formulario con un campo de texto que será la búsqueda que haga el usuario. Enlazará pasando ese parámetro a busca_2.php
// ---- busca_2.php: Muestra las secciones de la base de datos donde el campo título tiene coincidencia con el campo de texto que se introdujo en el form
if ($busca!="")
{
 //echo "<li>busca=$busca buscar=$buscar </li>";
 include "buscar_texto2.php";
}
print '</div></div></div></div></div>';
?>