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
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/colores_aplicacion-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Servidor` WHERE `Tipo`='Estilos'";

$estilos = array();
$i = 0;
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$estilos[$i]["Campo"]=$listado->Campo;
		$estilos[$i]["Valor"]=$listado->Valor;		
		$i++;
	}
}
print "<form action=\"/administra/Administra/colores_aplicacion_2.php\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
for ($j=0;$j<$i;$j++)
{
	print "<li>";
	print "<span style=\"width:10px;height:10px;background:".$estilos[$j]["Valor"].";\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <strong>".$estilos[$j]["Campo"].": </strong>";
	print $estilos[$j]["Valor"]." ";	
	print "<input id=\"".$estilos[$j]["Campo"]."\" name=\"".$estilos[$j]["Campo"]."\" type=\"text\" value=\"".$estilos[$j]["Valor"]."\" size=\"50\" maxlength=\"255\">";
	print "</li>";
}
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
