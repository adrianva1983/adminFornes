<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
function cambiaf_a_normal($fecha){
	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
	return $lafecha;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Especifico/idiomas/instalaciones-".$_SESSION['idioma'].".conf");
// Hacemos una consulta para ver cuantas webs están instaladas
$requete = "SELECT * FROM `Servidor_webs` ORDER BY `Dominio`";

// Listamos las secciones existentes
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>&nbsp;</th><th>".$lang["logo"]."</th><th>".$lang["dominio"]."</th><th>".$lang["version"]."</th><th>".$lang["herramientas"]."</th><th>".$lang["acciones"]."</th><th>".$lang["estado"]."</th></tr>";
	$par = false;
	while($listado = mysqli_fetch_object($result))
	{
		if ($par) 
		{
			print "<tr id=\"par\">";
			$par = false;
		}
		else
		{
			print "<tr>";
			$par = true;
		}
		//PERMISOS NECESARIOS PARA ORDENAR DE RESPONSABLE
		print "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Especifico&herramienta=versiones&id=".$listado->Id."\"><img title=\"".$lang["versiones"]."\" alt=\"".$lang["versiones"]."\" src=\"../Imagenes/brick.png\"/></a></td>";
		print "<td><img src=\"http://".$listado->Dominio."/estilos/images/logo.png\" style=\"width:80px;\"/></td>";
		print "<td>".$listado->Dominio."</td>";
		print "<td>".$listado->Version.".".$listado->Subversion."</td>";
		$dbN= mysql_connect("localhost", $listado->UsuarioBD, $listado->PassBD);
		mysql_select_db($listado->BaseDatosBD);
		$requeteN = "SELECT * FROM `Herramientas` WHERE `Activado`='si' AND `Idioma`= 'ES-ES' AND`Accion`='no' AND `IdPadre` IS NOT NULL";
		$resultN = mysql_query($requeteN,$dbN);
		$total_resultados = 0;
		if (mysqli_num_rows($resultN)>0) $total_resultados = mysqli_num_rows($resultN);
		print "<td>".$total_resultados."</td>";
		$requeteN = "SELECT * FROM `Herramientas` WHERE `Activado`='si' AND `Idioma`= 'ES-ES' AND `Accion`='si' AND `IdPadre` IS NOT NULL";		
		$resultN = mysql_query($requeteN,$dbN);
		$total_resultados = 0;
		if (mysqli_num_rows($resultN)>0) $total_resultados = mysqli_num_rows($resultN);
		print "<td>".$total_resultados."</td>";
		$dbN = mysql_close($dbN);
		print "<td>".$listado->Estado."</td>";
		print "</tr>";
	}
	print "</table>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
