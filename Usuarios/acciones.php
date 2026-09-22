<?php
//VERSIÓN: v1.0 2013-12-13
//COMPATIBLE PHP 5.5
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/acciones-".$_SESSION['idioma'].".conf");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='1'";

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
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `UsuariosHerramientas`.Activo='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='1'";
		
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
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='1'";

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
   case "usuarios":
   case "usuarios2":
   case "usuarios_grupos":
   case "usuarios_grupos2":
	// ACCIONES CON USUARIOS
	// -----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[117]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[117]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO		
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			if ($grp=="") print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=editar_grupo&grupo=".$grupo."\"><i class=\"fa fa-edit\"></i> ".$textos[117]."</a></li>";
			else print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=editar_grupo&grp=".$grp."&grupo=".$grupo."\"><i class=\"fa fa-edit\"></i> ".$textos[117]."</a></li>";
		} //FIN NIVEL
	}
	if (($herramientas[111]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[111]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=usuarios_listar";
			print "\"><i class=\"fa fa-user\"></i> ".$textos[111]."</a></li>";
		} //FIN NIVEL
	}
	if (($herramientas[102]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[102]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=nuevo_usuario";
			switch ($herramienta) 
			{
				case "usuarios":
					print "&referencia=usuarios";					
					break;
				case "usuarios2":
					print "&referencia=usuarios2";
					break;
				case "usuarios_grupos":
					print "&referencia=usuarios_grupos";
					if ($grupo!="") print "&grupo=".$grupo;
					break;
				case "usuarios_grupos2":
					print "&referencia=usuarios_grupos2";
					if ($grupo!="") print "&grupo=".$grupo;
					break;
			}			
			print "\"><i class=\"fa fa-user-plus\"></i> ".$textos[102]."</a></li>";
		} //FIN NIVEL
	}
	if (($herramientas[104]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[104]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			if ($grupo=="") print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=nuevo_grupo\"><i class=\"fa fa-group\"></i> ".$textos[104]."</a></li>";
			else print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=nuevo_grupo&grp=".$grupo."\"><i class=\"fa fa-group\"></i> ".$textos[104]."</a></li>";
		} //FIN NIVEL
	}
	if (($herramientas[103]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[103]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			if (isset($grupo))
			{
				print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=vincular_usuario_grupo&grupo=".$grupo."&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><i class=\"fa fa-external-link-square\"></i> ".$textos[103]."</a></li>";
			}
		} //FIN NIVEL
	}
	print "</ul>";
	print '</li>';	
	break;
   case "grupos":
	// ACCIONES CON GRUPOS
	// ----------------------	
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';		
	print '<ul class="dropdown-menu">';
	if (($herramientas[104]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[104]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			if ($grp=="") print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=nuevo_grupo\"><i class=\"fa fa-group\"></i> ".$textos[104]."</a></li>";
			else print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=nuevo_grupo&grp=".$grp."\"><i class=\"fa fa-group\"></i> ".$textos[104]."</a></li>";
		} //FIN NIVEL
	}
	print "</ul>";
	print '</li>';	
	break;
   case "migracion":
	// ACCIONES CON MIGRACIÓN
	// ----------------------	
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';		
	print '<ul class="dropdown-menu">';
	if (($herramientas[105]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[105]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=migracion_redes\"><i class=\"fa fa-magnet\"></i> ".$textos[105]."</a></li>";	
		} //FIN NIVEL
	}
	if (($herramientas[106]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[106]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=importar_csv\"><i class=\"fa fa-database\"></i> ".$textos[106]."</a></li>";	
		} //FIN NIVEL
	}
	if (($herramientas[107]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[107]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Usuarios&herramienta=exportar_csv\"><i class=\"fa fa-database\"></i> ".$textos[107]."</a></li>";
		} //FIN NIVEL
	}		
	print "</ul>";
	print "</li>";
	break;
}
print '</ul>';
print '</div>';
?>