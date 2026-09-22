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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nuevo_grupo_boletin-".$_SESSION['idioma'].".conf");

$requete = "SELECT * FROM `Grupos`";


print "<form action=\"/administra/Boletin/nuevo_grupo_boletin_2.php?idboletin=".$idboletin."\" enctype=\"multipart/form-data\" method=\"POST\">";

print "<ul>";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2="SELECT `Id` FROM `PertenenciaGrupos` WHERE `IdGrupo`='".$listado->Id."'";
		$result2 = mysql_query($requete2);
		$usuarios=mysqli_num_rows($result2);
		print "<li>";
		print "<img src=\"/administra/Imagenes/grupos.png\">";
		print "<input class=\"suscripcion\" name=\"grupos/".$listado->Id."\" type=\"checkbox\" value=\"".$listado->Id."\">";
		print $listado->Nombre." (".$usuarios." ".$lang["usuarios"].")";
		print "</li>";
	}
}
print "</ul>";
print "<ul>";
print "<li><label for=\"numeroEltos\">".$lang["importarN"].":</label><select name=\"numeroEltos\"><option value=\"ALL\" selected>".$lang["todos"]."</option><option value=\"10000\">10000</option><option value=\"20000\">20000</option><option value=\"30000\">30000</option><option value=\"40000\">40000</option></select></li>";
print "<li><label for=\"tramo\">".$lang["tramo"].":</label><select name=\"tramo\"><option value=\"1\" selected>1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option></select></li>";
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["anadir"]."\">";
print "</form>";
?>