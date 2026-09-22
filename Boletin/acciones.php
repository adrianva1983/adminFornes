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

if ($herramienta=="estadisticas") {exit;}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/acciones-".$_SESSION['idioma'].".conf");
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='13'";

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
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `UsuariosHerramientas`.Activo='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='13'";
		
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
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='13'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$herramientas[$listado->IdHerramienta]="si";
	}
}
print '<div class="top-navigation">';
print '<ul class="nav navbar-nav white-bg">';

switch ($herramienta) {
   case "notificaciones":
	break;
   case "boletines":
	// ACCIONES CON BOLETINES
	// -----------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[32]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_boletin\"><img src=\"/administra/Imagenes/boletin_nuevo.png\"> ".$textos[32]."</a></li>";
		}
	}
	print "</ul>";
	break;
   case "mensajeria":
	// ACCIONES CON MENSAJERIA
	// -----------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[113]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje\"><img src=\"/administra/Imagenes/boletin_enviar.png\"> ".$textos[113]."</a></li>";
		}
	}
	if (($herramientas[114]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=mensajeria&favoritos=si\"><img src=\"/administra/Imagenes/star.png\"> ".$textos[114]."</a></li>";
		}
	}
	if (($herramientas[115]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=papelera_mensajes\"><img src=\"/administra/Imagenes/papelera.png\"> ".$textos[115]."</a></li>";
		}
	}
	print "</ul>";
	break;
   case "boletin":
	// ACCIONES CON UN BOLETIN CONCRETO
	// --------------------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[33]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_contenido_boletin&idboletin=".$idboletin."\"><img src=\"/administra/Imagenes/boletin_contenido.png\"> ".$textos[33]."</a></li>";
		}
	}
	if (($herramientas[34]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nueva_seccion_boletin&idboletin=".$idboletin."\"><img src=\"/administra/Imagenes/boletin_seccion.png\"> ".$textos[34]."</a></li>";
		}
	}
	if (($herramientas[35]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)	
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_usuario_boletin&idboletin=".$idboletin."\"><img src=\"/administra/Imagenes/boletin_usuario.png\"> ".$textos[35]."</a></li>";
		}
	}
	if (($herramientas[36]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_grupo_boletin&idboletin=".$idboletin."\"><img src=\"/administra/Imagenes/boletin_grupo.png\"> ".$textos[36]."</a></li>";
		}
	}
	if (($herramientas[37]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=boletin_prueba&idboletin=".$idboletin."\"><img src=\"/administra/Imagenes/boletin_enviar.png\"> ".$textos[37]."</a></li>";
		}
	}
	if (($herramientas[38]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a target=\"_blank\" href=\"/administra/Boletin/vista_previa.php?Id=".$idboletin."\"><img src=\"/administra/Imagenes/ver.png\"> ".$textos[38]."</a></li>";
		}
	}
	print "</ul>";
	break;
	case "SMSVer":
	// ACCIONES CON UN SMS CONCRETO
	// --------------------------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[39]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=editar_SMSBloque&idSMS=".$idSMS."\"><img src=\"/administra/Imagenes/phone_editar.png\"> ".$textos[39]."</a></li>";
		}
	}
	if (($herramientas[40]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_usuario_SMS&idSMS=".$idSMS."\"><img src=\"/administra/Imagenes/phone_usuarios.png\"> ".$textos[40]."</a></li>";
		}
	}
	if (($herramientas[41]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_grupo_SMS&idSMS=".$idSMS."\"><img src=\"/administra/Imagenes/phone_grupos.png\"> ".$textos[41]."</a></li>";
		}
	}
	if (($herramientas[42]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=SMS_prueba&idSMS=".$idSMS."\"><img src=\"/administra/Imagenes/phone_envio.png\"> ".$textos[42]."</a></li>";
		}
	}
	print "</ul>";
	break;
	case "SMS":
	// ACCIONES CON SMSs
	// -----------------
	print "<h1>".$lang["acciones"]."</h1>";
	print "<ul>";
	if (($herramientas[43]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=nuevo_SMS\"><img src=\"/administra/Imagenes/phone_add.png\"> ".$textos[43]."</a></li>";
		}
	}
	if (($herramientas[44]=="si")||($_SESSION['usuario_nivel']<1)) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Boletin&herramienta=envio1SMS\"><img src=\"/administra/Imagenes/phone_envio.png\"> ".$textos[44]."</a></li>";
		}
	}
	print "</ul>";
	break;
}
print '</ul>';
print '</div>';