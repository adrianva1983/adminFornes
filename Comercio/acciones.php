<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Comercio/idiomas/acciones-".$_SESSION['idioma'].".conf");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='17'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$textos[$listado->IdHerramienta]=$listado->Nombre;
	}
}
//Sacamos las herramientas de los grupos a los que pertenezca
$herramientas = array();
$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdUsuario` = '".$_SESSION['usuario_id']."'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='17'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			while($listado2 = mysqli_fetch_object($result2))
			{
				$herramientas[$listado2->IdHerramienta]="si";
			}
		}
	}
}
//Sacamos las herramientas que tiene disponibles el usuario
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='17'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$herramientas[$listado->IdHerramienta]="si";
	}
}
switch ($herramienta) {
  case "reservas":
	// ACCIONES EN RESERVAS
	// --------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[78]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Comercio&herramienta=buscar_reserva\"><img src=\"/administra/Imagenes/buscar_texto.png\"> ".$textos[78]."</a></li>";
		}
	}
	if (($herramientas[79]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Comercio&herramienta=nueva_reserva\"><img src=\"/administra/Imagenes/nueva_reserva.png\"> ".$textos[79]."</a></li>";
		}
	}
	if (($herramientas[80]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Comercio&herramienta=reservas_telefono\"><img src=\"/administra/Imagenes/telefono.png\"> ".$textos[80]."</a></li>";
		}
	}
	if (($herramientas[81]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Comercio&herramienta=alerta_reserva_admin\"><img src=\"/administra/Imagenes/phone_envio.png\"> ".$textos[81]."</a></li>";
		}
	}
	print "</ul>";
	break;
  case "referidos":
	// ACCIONES EN REFERIDOS
	// --------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[82]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Comercio&herramienta=referidos_reserva\"><img src=\"/administra/Imagenes/reservas.png\"> ".$textos[82]."</a></li>";
		}
	}
	if (($herramientas[83]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"#\"><img src=\"/administra/Imagenes/cart.png\"> ".$textos[83]."</a></li>";
		}
	}
	break;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");