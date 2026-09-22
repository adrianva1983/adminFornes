<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/SMSVer-".$_SESSION['idioma'].".conf");
// Mostramos el título del boletín en curso
$requete = "SELECT * FROM `SMSContenido` WHERE `IdSMS`=".$idSMS;

$listado = mysqli_fetch_object($result);
//Si es coordinador y no es propietario de este boletín, no puede seleccionarlo
if (($_SESSION['usuario_nivel']==2)&&($_SESSION['usuario_id']!=$listado->IdPropietario))
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

print "<h1>".$listado->Titulo."</h1>";
print "<ul>";
print "<li><strong>".$lang["texto"].":</strong> ".$listado->Texto."</li>";
print "<li><strong>".$lang["remitente"].":</strong> ".$listado->Remitente."</li>";
print "</ul>";
print "<hr>";
//USUARIOS INCLUIDOS
//------------------
$requete = "SELECT * FROM `SMSUsuarios`,`Usuarios` WHERE IdSMS=".$idSMS." AND IdUsuario=`Usuarios`.Id;";


print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 {
  print "<li><a href=\"/administra/Boletin/funciones/borrar_usuario_SMS.php?IdSMS=".$idSMS."&idusuario=".$listado->Id."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["borrarUsuario"]."\" alt=\"".$lang["borrarUsuario"]."\"></a><img src=\"/administra/Imagenes/usuario.png\">".$listado->Nombre.", ".$listado->Apellidos.", ".$listado->NombreEmpresa."</li>";
 }
}
else
{
  print $lang["noUsuario"].".";
}
print "</ul>";
//USUARIOS FIN
//------------
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>