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
$item="Usuarios";
$requete = "SELECT `Id`, `Nombre`, `Apellidos`, `Foto` FROM `".$item."` WHERE `AltaBoletin`='si' AND `NivelAcceso`>='".$_SESSION['usuario_nivel']."'";
if ($Nombre!="") $requete.= " AND `Nombre` like '%".$Nombre."%'";
if ($Apellidos!="") $requete.= " AND `Apellidos` like '%".$Apellidos."%'";
if ($Ciudad!="") $requete.= " AND `Ciudad` like '%".$Ciudad."%'";
if ($Provincia!="") $requete.= " AND `Provincia` like '%".$Provincia."%'";
if ($Pais!="") $requete.= " AND `Pais` like '%".$Pais."%'";
if ($NombreEmpresa!="") $requete.= " AND `NombreEmpresa` like '%".$NombreEmpresa."%'";
$result = mysql_query($requete);
if (!$result) exit;
$numeroResultados = mysqli_num_rows($result);
if($numeroResultados==0)
{
	print "<li>".$lang["sinResultados"]."</li>";
	mysql_free_result($result);    
}
else
{
	print "<script>";
	print "function seleccionar_elem(){\n";
	print "elementos=document.ubusca.length\n";
	print "for(i=0;i<elementos;i++)\n";
	print "document.ubusca.elements[i].checked=true\n";
	print "}\n";
	print "function desactivar_elem()\n";
	print "{\n";
	print "elementos=document.ubusca.length\n";
	print "for(i=0;i<elementos;i++)\n";
	print "document.ubusca.elements[i].checked=false\n";
	print "}\n";
	print "</script>";
	print "<strong>".$lang["numeroEncontrados"].":</strong> ".$numeroResultados."<br/>";
	print "<div id=\"botonera_cabecera\"><ul><li><a href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a></li>";
	print "<li><a href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a></li></ul></div>";
	print "<ul>";
	while ($listado = mysqli_fetch_object($result)) 
	{		
		print "<li><input class=\"suscripcion\" name=\"usuario[]\" type=\"checkbox\" value=\"".$listado->Id."\"><img src=\"/administra/Imagenes/usuario.png\"><strong>".$listado->Nombre.", ".$listado->Apellidos."</strong><br/></li>";
	}
	mysql_free_result($result);
	print  "</ul>";
	print "<div id=\"botonera_cabecera\"><ul><li><a href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a></li>";
	print "<li><a href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a></li></ul></div>";
}  
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
