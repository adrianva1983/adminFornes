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
function cambiaf_a_normal($fecha){
 preg_match( "/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}/)", $fecha, $mifecha);
 $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]." ".$mifecha[4].":".$mifecha[5];
 return $lafecha;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/reservas-".$_SESSION['idioma'].".conf");
$Num_Pagina = 40;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `guiarestaurantes_reservas` WHERE `Estado`<>'Borrado'";
if ($Nombre!="") $requete .= " AND `Nombre` LIKE '%".$Nombre."%'";
if ($Telefono!="") $requete .= " AND `Telefono` = '".$Telefono."'";
if ($IdReserva!="") $requete .= " AND `Id` = '".$IdReserva."'";
if ($IdUsuario!="") $requete .= " AND `IdUsuario` = '".$IdUsuario."'";
if ($Estado!="") $requete .= " AND `Estado` = '".$Estado."'";
if ($IdRestaurante!="") $requete .= " AND `IdRestaurante` = '".$IdRestaurante."'";

$total = mysqli_num_rows($result);
$requete .= " ORDER BY `FechaInserccion` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/tick.png\" alt=\"".$lang["confirmada"]."\"> :: ".$lang["confirmada"]."<br/>";
print "<img src=\"/administra/Imagenes/cross.png\" alt=\"".$lang["nodisponible"]."\"> :: ".$lang["nodisponible"]."<br/>";
print "<img src=\"/administra/Imagenes/pendiente.png\" alt=\"".$lang["pendiente"]."\"> :: ".$lang["pendiente"]."<br/>";
print "<img src=\"/administra/Imagenes/borrar.png\" alt=\"".$lang["rechazada"]."\"> :: ".$lang["rechazada"]."<br/>";
print "<img src=\"/administra/Imagenes/alerta.png\" alt=\"".$lang["fraudulenta"]."\"> :: ".$lang["fraudulenta"]."<br/>";
print "<img src=\"/administra/Imagenes/clock.png\" alt=\"".$lang["EnTramite"]."\"> :: ".$lang["EnTramite"]."<br/>";
print "<hr/>";
print "<img src=\"/administra/Imagenes/usuario.png\" alt=\"".$lang["ficha"]."\"> :: ".$lang["ficha"]."<br/>";
print "<img src=\"/administra/Imagenes/cambiar_estado_reserva.png\" alt=\"".$lang["cambiarEstado"]."\"> :: ".$lang["cambiarEstado"]."<br/>";
print "<img src=\"/administra/Imagenes/borrar_reserva.png\" alt=\"".$lang["borrarReserva"]."\"> :: ".$lang["borrarReserva"]."<br/>";
print "<img src=\"/administra/Imagenes/bullet_orange.png\" title=\"".$lang["leidoRestaurante"]."\" alt=\"".$lang["leidoRestaurante"]."\"><img src=\"/administra/Imagenes/bullet_orange.png\" title=\"".$lang["gestionadoRestaurante"]."\" alt=\"".$lang["gestionadoRestaurante"]."\"><img src=\"/administra/Imagenes/bullet_orange.png\" title=\"".$lang["comunicadoUsuario"]."\" alt=\"".$lang["comunicadoUsuario"]."\"><img src=\"/administra/Imagenes/bullet_orange.png\" alt=\"".$lang["enviadoSeguimiento"]."\"><img src=\"/administra/Imagenes/bullet_orange.png\" alt=\"".$lang["leidoSeguimiento"]."\"> ".$lang["leidoRestaurante"]." | ".$lang["gestionadoRestaurante"]." | ".$lang["comunicadoUsuario"]." | ".$lang["enviadoSeguimiento"]." | ".$lang["leidoSeguimiento"]."<br/>";
print "</div>";
print "<p><strong>".$lang["del"]." ".$intervalo_inicial." ".$lang["al"]." ".($intervalo_inicial + $Num_Pagina)." ".$lang["deUnTotal"]." ".$total."</strong></p>";
print "<table>";
print "<tr>";
if ($_SESSION['usuario_nivel']<1) print "<th>CU</th>";
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
		if ($_SESSION['usuario_nivel']<1) print "<td rowspan=\"2\"><a target=\"_blank\" href=\"/administra/Comercio/CU.php?idreserva=".$listado->Id."&movil=".$listado->Telefono."&idrestaurante=".$listado->IdRestaurante."&idusuario=".$listado->IdUsuario."&fecha=".$listado->FechaInserccion."&email=".$listado->Email."&nombre=".$listado->Nombre."\">CU</a></td>";
		print "<td rowspan=\"2\"><strong>".$listado->Id."</strong><br/>".$listado->IntroducidoPor;
		print "</td>";
		print "<td>";
		print "<a href=\"/administra/Comercio/funciones/borrar_reserva.php?origen=reservas&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/borrar_reserva.png\" title=\"".$lang["borrarReserva"]."\" alt=\"".$lang["borrarReserva"]."\"></a></td>";
		if (($listado->IdUsuario!="")&&($listado->IdUsuario!="0"))
		{
			$total_reservas_usuario = 0;
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$listado->IdUsuario."\"><img src=\"/administra/Imagenes/usuario.png\" title=\"".$lang["verFicha"]."\" alt=\"".$lang["verFicha"]."\"> ".htmlentities($listado->Nombre)."</a>";
			$requete2 = "SELECT * FROM `guiarestaurantes_reservas` WHERE `Estado`<>'Borrado' AND `IdUsuario`='".$listado->IdUsuario."'";
			
			$total_reservas_usuario = mysqli_num_rows($result2);
			print " <a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=reservas&IdUsuario=".$listado->IdUsuario."\">(".$total_reservas_usuario.")</a></td>";
		}
		else print "<td><a href=\"#\"><img src=\"/administra/Imagenes/usuarios_gris.png\" title=\"".$lang["verFichaNO"]."\" alt=\"".$lang["verFichaNO"]."\"></a> ".htmlentities($listado->Nombre)."</td>";
		$codigo = md5($listado->Id.$listado->Nombre."GUIA RESTAURANTES".$listado->Fecha.$listado->Email);
		$requete2 = "SELECT * FROM `Contenidos` WHERE `Id`='".$listado->IdRestaurante."'";
		
		$listado2 = mysqli_fetch_object($result2);
		print "<td>".htmlentities($listado2->Titulo);
                $requete2 = "SELECT * FROM `guiarestaurantes_reservas` WHERE `Estado`<>'Borrado' AND `IdRestaurante`='".$listado->IdRestaurante."'";
		
                $total_reservas_restaurante = mysqli_num_rows($result2);
                print " <a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=reservas&IdRestaurante=".$listado->IdRestaurante."\">(".$total_reservas_restaurante.")</a></td>";
		$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `TituloCampo` = 'Teléfono/1' AND `IdContenido`='".$listado2->Id."'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print "<td>".$listado2->Valor."</td>";
		}
		else print "<td>N/D</td>";		
		print "<td>";
		if (($listado->AbrioEmail!="0000-00-00 00:00:00")&&($listado->AbrioEmail!=NULL)) print "<img src=\"/administra/Imagenes/bullet_green.png\" title=\"".$lang["leidoRestaurante"]." ".$listado->AbrioEmail."\" alt=\"".$lang["leidoRestaurante"]." ".$listado->AbrioEmail."\">";
		else print "<img src=\"/administra/Imagenes/bullet_red.png\" title=\"".$lang["leidoRestauranteNO"]."\" alt=\"".$lang["leidoRestauranteNO"]."\">";
		if (($listado->PinchoEnlace!="0000-00-00 00:00:00")&& ($listado->PinchoEnlace!=NULL)) print "<img src=\"/administra/Imagenes/bullet_green.png\" title=\"".$lang["gestionadoRestaurante"]." ".$listado->PinchoEnlace."\" alt=\"".$lang["gestionadoRestaurante"]." ".$listado->PinchoEnlace."\">";
		else print "<img src=\"/administra/Imagenes/bullet_red.png\" title=\"".$lang["gestionadoRestauranteNO"]."\" alt=\"".$lang["gestionadoRestauranteNO"]."\">";
		if (($listado->AbrioEmailUsuario!="0000-00-00 00:00:00")&& ($listado->AbrioEmailUsuario!=NULL)) print "<img src=\"/administra/Imagenes/bullet_green.png\" title=\"".$lang["comunicadoUsuario"]." ".$listado->AbrioEmailUsuario."\" alt=\"".$lang["comunicadoUsuario"]." ".$listado->AbrioEmailUsuario."\">";
		else print "<img src=\"/administra/Imagenes/bullet_red.png\" title=\"".$lang["comunicadoUsuarioNO"]."\" alt=\"".$lang["comunicadoUsuarioNO"]."\">";
		if (($listado->EnviadaSatisfaccion!="0000-00-00 00:00:00")&& ($listado->EnviadaSatisfaccion!=NULL)) print "<img src=\"/administra/Imagenes/bullet_green.png\" title=\"".$lang["enviadoSeguimiento"]." ".$listado->EnviadaSatisfaccion."\" alt=\"".$lang["enviadoSeguimiento"]." ".$listado->EnviadaSatisfaccion."\">";
		else print "<img src=\"/administra/Imagenes/bullet_orange.png\" title=\"".$lang["enviadoSeguimiento"]."\" alt=\"".$lang["enviadoSeguimiento"]."\">";
		print "<img src=\"/administra/Imagenes/bullet_orange.png\" title=\"".$lang["leidoSeguimiento"]."\" alt=\"".$lang["leidoSeguimiento"]."\">";
		print "</td>";		
		if ($paridad) print "<tr id=\"par\">";
		else print "<tr>";
		print "<td><a href=\"/codigos/tramitar_reserva.php?Id=".$listado->Id."&codigo=".$codigo."&admin=si&idusuario=".$_SESSION['usuario_id']."\" target=\"_blank\"><img src=\"/administra/Imagenes/cambiar_estado_reserva.png\" alt=\"".$lang["cambiarEstado"]."\" title=\"".$lang["cambiarEstado"]."\"></a>";
		print "</td>";	
		print "<td>";
		switch ($listado->TipoAlerta)
		{
			case "SMS":
	  		print "<a href=\"#\"><img src=\"/administra/Imagenes/phone.png\" title=\"".$lang["alertaSMS"]."\" alt=\"".$lang["alertaSMS"]."\"></a> ".$lang["alertaSMS"];
  		break;
  		case "mail":
	  		print "<a href=\"#\"><img src=\"/administra/Imagenes/boletines.png\" title=\"".$lang["alertaMail"]."\" alt=\"".$lang["alertaMail"]."\"></a> ".$lang["alertaMail"];
  		break;
  		case "Llamada":
	  		print "<a href=\"#\"><img src=\"/administra/Imagenes/telefono.png\" title=\"".$lang["alertaLlamada"]."\" alt=\"".$lang["alertaLlamada"]."\"></a> ".$lang["alertaLlamada"];
  		break;
		}
		print "</td>";
		print "<td>".$lang["comensales"].": ".htmlentities($listado->Comensales)."</td>";
		$fecha_mostrar = cambiaf_a_normal($listado->Fecha);
		print "<td>".$fecha_mostrar."</td>";
		switch ($listado->Estado)
		{
		case "EnTramite":		
			print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/clock.png\" alt=\"".$lang["EnTramite"]."\" title=\"".$lang["EnTramite"]."\"></td>";
			break;
		case "Pendiente":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/pendiente.png\" title=\"".$lang["pendiente"]."\" alt=\"".$lang["pendiente"]."\"></td>";
	  		break;
	  	case "Confirmada":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["confirmada"]."\" alt=\"".$lang["confirmada"]."\"></td>";
	  		break;
	  	case "NoDisponibilidad":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/cross.png\" alt=\"".$lang["nodisponible"]."\" title=\"".$lang["nodisponible"]."\"></td>";
	  		break;
	  	case "Rechazado":
	  		print "<td>".$lang["estado"].": <img src=\"/administra/Imagenes/borrar.png\" alt=\"".$lang["rechazada"]."\" title=\"".$lang["rechazada"]."\"></td>";
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
		print "<a href=\"".$_SERVER['SCRIPT_NAME']."?modulo=Comercio&herramienta=reservas&Num_Pagina=".$Num_Pagina."&pagina=".$i;
		if ($IdReserva!="") print "&IdReserva=".$IdReserva;
		if ($IdRestaurante!="") print "&IdRestaurante=".$IdRestaurante;
		if ($Nombre!="") print "&Nombre=".$Nombre;
		if ($Telefono!="") print "&Telefono=".$Telefono;
		if ($Estado!="") print "&Estado=".$Estado;		
		print "\">".$i."</a> - ";
	}
	else print "<strong>".$i."</strong> - ";
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
