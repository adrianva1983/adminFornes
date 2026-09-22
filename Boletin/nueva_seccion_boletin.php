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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/nueva_seccion_boletin-".$_SESSION['idioma'].".conf");


print "<form action=\"/administra/Boletin/nueva_seccion_boletin_2.php?idboletin=".$idboletin."\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";

//ARBOL DE LA WEB SOBRE EL QUE SELECCIONAREMOS EL RESTO
//-----------------------------------------------------
//FUNCIONES USADAS
$chartab="_";
function db_arbol($tabas,$item,$padre,$ruta)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	if ($padre=="") {exit;}
	if ($padre=="NULL") 
	{
		$requete="SELECT `Id`, `Titulo` FROM `$item` WHERE `IdPadre` is NULL ORDER BY `Orden`";
	}
	else
	{
		$requete = "SELECT `Id`, `Titulo` FROM `$item` WHERE `IdPadre`='".$padre."' ORDER BY `Orden`";
	} 
	if ($item=="") {exit;}
	$result = mysql_query($requete);
	$a=array();
	if (!$result) exit;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$requete2="SELECT `Id` FROM `Publicaciones` WHERE `IdSeccion`='".$row['Id']."'";
		$result2 = mysql_query($requete2);
		$id=$row['Id'];
		$titulo=$row['Titulo'];
		$contenidos=mysqli_num_rows($result2);
		$b[]=array();
		$b["Id"]=$id;
		$b["Titulo"]=$titulo;
		$b["Contenidos"]=$contenidos;
		$a[]=$b;
		mysql_free_result($result2);
	}
	mysql_free_result($result);
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	echo "<ul>";
	foreach ($a as $d) 
	{
		$id=$d['Id'];
		$titulo=$d['Titulo'];
		$contenidos=$d['Contenidos'];
		print "<li><input class=\"suscripcion\" name=\"secciones/".$id."\" type=\"checkbox\" value=\"".$id."\">";
		print "<img src=\"/administra/Imagenes/secciones.png\">$titulo (".$contenidos." ".$lang["contenidos"].")</li>";
		$tabas=$tabas+1;
		db_arbol("$tabas","$item","$id","$enruta");
	}
	echo "</ul>";
}
//RESTO DEL PROGRAMA
db_arbol(0,"Secciones","NULL","");
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["anadir"]."\">";
print "</form>";
?>