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
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/acciones-".$_SESSION['idioma'].".conf");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='9'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$textos[$listado->IdHerramienta]=$listado->Nombre;
		if ($listado->Activado=="si") $herramienta_activada[$listado->IdHerramienta]="si";		
	}
}
//Sacamos las herramientas de los grupos a los que pertenezca
$herramientas = array();
$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdUsuario` = '".$_SESSION['usuario_id']."'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='9'";
		
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
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='9'";

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
  case "buscar":
	// ACCIONES EN BÚSQUEDA
	// --------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[84]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[84]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Contenidos&herramienta=buscar_texto\"><i class=\"fa fa-search\"></i> ".$textos[84]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';	
	break;
  case "tipo_contenidos":
	// ACCIONES EN ETIQUETADOS
	// --------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[134]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[134]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Contenidos&herramienta=anadir_tag\"><i class=\"fa fa-tag\"></i> ".$textos[134]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';	
	break;
  case "glosario":
	// ACCIONES EN FOROS
	// --------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[135]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[135]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{//NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Contenidos&herramienta=glosario_anadir_termino\"><i class=\"fa fa-th-list\"></i> ".$textos[135]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';	
	break;
  case "foros":
	// ACCIONES EN FOROS
	// --------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[85]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[85]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{//NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Contenidos&herramienta=ultimos_mensajes_foros\"><i class=\"fa fa-comments\"></i> ".$textos[85]."</a></li>";
		}
	}
	if (($herramientas[86]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[86]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{//NIVEL MÍNIMO RESPONSABLE			
			print "<li><a href=\"herramienta.php?modulo=Contenidos&herramienta=validar_mensajes_foros\"><i class=\"fa fa-check\"></i> ".$textos[86]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';	
	break;
}
print '</ul>';
print '</div>';
?>