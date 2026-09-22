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
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_grupo_SMS-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Grupos`";


print "<form action=\"/administra/Boletin/nuevo_grupo_SMS_2.php?idSMS=".$idSMS."\" enctype=\"multipart/form-data\" method=\"POST\">";

print "<ul>";
if ($result = mysqli_query($db, $requete))
{
 while($listado = mysqli_fetch_object($result))
 { 	 
 $requete2="SELECT * FROM `PertenenciaGrupos`,`Usuarios` WHERE `PertenenciaGrupos`.IdUsuario = `Usuarios`.Id AND `IdGrupo`='".$listado->Id."' AND `Movil`<>'' AND `AltaSMS`='si'";
 $result2 = mysql_query($requete2);
 $usuarios=mysqli_num_rows($result2);
 print "<li>";
 print "<img src=\"/administra/Imagenes/grupos.png\">";
 print "<input class=\"suscripcion\" name=\"grupos/".$listado->Id."\" type=\"checkbox\" value=\"".$listado->Id."\">";
 print $listado->Nombre." (".$usuarios." ".$lang["alta"].")";
 print "</li>";
 }
}
print "</ul>";
print "<input type=\"submit\" value=\"".$lang["anadir"]."\">";
print "</form>";
?>