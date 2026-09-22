<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
function cambiaf_a_normal($fecha)
{
	$mifecha = explode(" ",$fecha);
	$mifecha2 = explode("-",$mifecha[0]);
	$lafecha=$mifecha2[2]."/".$mifecha2[1]."/".$mifecha2[0]." ".$mifecha[1];
	return $lafecha;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/referidos_reserva-".$_SESSION['idioma'].".conf");
$Num_Pagina = 40;
$intervalo_inicial= $pagina * $Num_Pagina;
if ($_SESSION['usuario_nivel']<2) $requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `Estado`<>'Borrado' AND `IdReferido` IS NOT NULL";
else $requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `Estado`<>'Borrado' AND `IdReferido`='".$_SESSION['usuario_id']."'";

$total = mysqli_num_rows($result);
$requete .= " ORDER BY `FechaInserccion` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

print "<img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["Confirmada"]."\" alt=\"".$lang["Confirmada"]."\"> :: ".$lang["confirmada"]."<br/>";
print "<img src=\"/administra/Imagenes/cross.png\" title=\"".$lang["nodisponible"]."\" alt=\"".$lang["nodisponible"]."\"> :: ".$lang["nodisponible"]."<br/>";
print "<img src=\"/administra/Imagenes/pendiente.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"> :: ".$lang["pendiente"]."<br/>";
print "<img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["rechazada"]."\" alt=\"".$lang["rechazada"]."\"> :: ".$lang["rechazada"]."<br/>";
print "<img src=\"/administra/Imagenes/alerta.png\" title=\"".$lang["fraudulenta"]."\" alt=\"".$lang["fraudulenta"]."\"> :: ".$lang["fraudulenta"]."<br/>";
print "<hr/>";
print "<p><strong>".$lang["del"]." ".$intervalo_inicial." ".$lang["al"]." ".($intervalo_inicial + $Num_Pagina)." de un total de ".$total."</strong></p>";
print "<table>";
print "<tr>";
print "<th>ID</th>";
print "<th colspan=\"2\">".$lang["acciones"]."</th><th colspan=\"3\">".$lang["informacion"]."</th></tr>";
$paridad = true;
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		if ($paridad) $paridad = false;
		else $paridad = true;
		if ($paridad) print "<tr id=\"par\">";
		else print "<tr>";
		print "<td><strong>".$listado->Id."</strong>";
		print "</td>";
		if ($_SESSION['usuario_nivel']<2)
		{
			if (($listado->IdReferido!="")&&($listado->IdReferido!="0")) print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$listado->IdReferido."\"><img src=\"/administra/Imagenes/usuario.png\" title=\"".$lang["verFicha"]."\" alt=\"".$lang["verFicha"]."\"> ".$IdReferido."</td>";
			else print "<td><a href=\"#\"><img src=\"/administra/Imagenes/usuarios_gris.png\" title=\"".$lang["verFichaNO"]."\" alt=\"".$lang["verFichaNO"]."\"></a> ".$IdReferido."</td>";
		}
		$requete2 = "SELECT * FROM `Contenidos` WHERE `Id`='".$listado->IdRestaurante."'";
		
		$listado2 = mysqli_fetch_object($result2);
		print "<td>".htmlentities($listado2->Titulo)."</td>";
		$fecha_mostrar = cambiaf_a_normal($listado->Fecha);
		print "<td>".$fecha_mostrar."</td>";
		switch ($listado->Estado)
		{
			case "Pendiente":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/pendiente.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"></td>";
	  		break;
	  	case "Confirmada":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["confirmada"]."\" alt=\"".$lang["confirmada"]."\"></td>";
	  		break;
	  	case "NoDisponibilidad":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/cross.png\" title=\"".$lang["nodisponible"]."\" alt=\"".$lang["nodisponible"]."\"></td>";
	  		break;
	  	case "Rechazado":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["rechazada"]."\" alt=\"".$lang["rechazada"]."\"></td>";
	  		break;
	  	case "Fraude":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/alerta.png\" title=\"".$lang["fraudulenta"]."\" alt=\"".$lang["fraudulenta"]."\"></td>";
	  		break;
	  }
		print "</tr>";		
	}
}
print "</table>";

//Imprimimos la paginación
$max =intval($total/$Num_Pagina);

print $lang["paginas"].": ";
for ($i=0;($i<$max+1);$i++)
{
	if ($i!=$pagina)
	{			
		print "<a href=\"".$_SERVER['SCRIPT_NAME']."?modulo=Comercio&herramienta=reservas&Num_Pagina=".$Num_Pagina."&pagina=".$i."\">".$i."</a> - ";
	}
	else print "<strong>".$i."</strong> - ";
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
