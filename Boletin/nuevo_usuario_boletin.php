<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_usuario_boletin-".$_SESSION['idioma'].".conf");

print "<form name=\"fbusca\" action=\"/administra/Interface/herramienta.php\">";
print "<input name=\"modulo\" type=\"hidden\" value=\"".$modulo."\">";
print "<input name=\"herramienta\" type=\"hidden\" value=\"".$herramienta."\">";
print "<input name=\"idboletin\" type=\"hidden\" value=\"".$idboletin."\">";
echo "<table>";
echo "<tr><td>".$lang["nombre"]." </td><td><input name=Nombre value=\"$Nombre\"></td></tr>";
echo "<tr><td>".$lang["apellidos"]." </td><td><input name=Apellidos value=\"$Apellidos\"></td></tr>";
echo "<tr><td>".$lang["ciudad"]." </td><td><input name=Ciudad value=\"$Ciudad\"></td></tr>";
echo "<tr><td>".$lang["municipio"]." </td><td><input name=Municipio value=\"$Municipio\"></td></tr>";	
echo "<tr><td>".$lang["provincia"]." </td><td><input name=Provincia value=\"$Provincia\"></td></tr>";
echo "<tr><td>".$lang["pais"]." </td><td><input name=Pais value=\"$Pais\"></td></tr>";
echo "<tr><td>".$lang["empresa"]." </td><td><input name=NombreEmpresa value=\"$NombreEmpresa\"></td></tr>";	
echo "</table>";
print "<input class=\"boton\" name=\"Enviar\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
print "<hr/>";
if ($Enviar!="")
{
	print "<form name=\"ubusca\" id=\"ubusca\" action=\"/administra/Boletin/nuevo_usuario_boletin_3.php\" method=\"post\">";
	print "<input name=\"idboletin\" type=\"hidden\" value=\"".$idboletin."\">";
	include "nuevo_usuario_boletin_2.php";
	print "<input class=\"boton\" type=\"submit\" value=\"".$lang["anadir"]."\">";
	print "</form>";
}
?>