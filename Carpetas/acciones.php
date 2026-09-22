<?php
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/acciones-".$_SESSION['idioma'].".conf");
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}

if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='5'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$textos[$listado->IdHerramienta]=$listado->Nombre;
		if ($listado->Activado=="si") $herramienta_activada[$listado->IdHerramienta]="si";
	}
}
//Sacamos los textos de herramientas expecíficas
$textos_personalizadas = array();
$requete = "SELECT * FROM `HerramientasPersonalizadas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `HerramientasPersonalizadas`.IdPadreNoPersonalizada='5'";

if ($result = mysqli_query($db, $requete))
{	
	while($listado = mysqli_fetch_object($result))
	{
		if ($listado->Activado=="si")
		{			
			$tmp["Icono"] = $listado->URLIcono;
			$tmp["Fichero"] = $listado->FicheroPHP;
			$tmp["Nombre"] = $listado->Nombre;
			if (count($textos_personalizadas[$listado->NombreGrupoNopersonalizada])>0) array_push($textos_personalizadas[$listado->NombreGrupoNoPersonalizada],$tmp);
			else $textos_personalizadas[$listado->NombreGrupoNopersonalizada][0] = $tmp;
		}		
	}
}
//Sacamos las herramientas de los grupos a los que pertenezca
$herramientas = array();
$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdUsuario` = '".$_SESSION['usuario_id']."'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{	
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='5'";
		
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
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='5'";

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
   case "contenidos":
	// ACCIONES CON CONTENIDOS
	// -----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[45]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[45]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=vincular_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$_GET['contenido']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-link\"></i> ".$textos[45]."</a></li>";
		}
	}
	if (($herramientas[46]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[46]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=editar_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$_GET['contenido']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-edit\"></i> ".$textos[46]."</a></li>";
		}
	}
	if (($herramientas[47]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[47]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=desvincula_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$_GET['contenido']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-unlink\"></i> ".$textos[47]."</a></li>";
		}
	}
	if (($herramientas[48]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[48]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=borrar_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$_GET['contenido']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-remove\"></i> ".$textos[48]."</a></li>";
		}
	}
	print "</ul>";
	// ACCIONES DE CARGA DE INFORMACIÓN
	print '</li>';	
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["carga"].' <span class="caret"></span></a>';		
	print '<ul class="dropdown-menu">';
	if (($herramientas[49]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[49]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=texto&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-file-text-o\"></i> ".$textos[49]."</a></li>";
		}
	}
	if (($herramientas[50]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[50]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=imagen&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-file-image-o\"></i> ".$textos[50]."</a></li>";
		}
	}
	if (($herramientas[51]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[51]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=fichero&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-file-o\"></i> ".$textos[51]."</a></li>";
		}
	}
	if (($herramientas[52]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[52]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=enlace&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-link\"></i> ".$textos[52]."</a></li>";
		}
	}
	if (($herramientas[53]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[53]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=video&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-file-video-o\"></i> ".$textos[53]."</a></li>";
		}
	}
	if (($herramientas[54]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[54]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=mapa&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-map-marker\"></i> ".$textos[54]."</a></li>";
		}
	}
	if (($herramientas[55]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[55]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=ruta&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-map-o\"></i> ".$textos[55]."</a></li>";
		}
	}
	if (($herramientas[56]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[56]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=foro&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-comments-o\"></i> ".$textos[56]."</a></li>";
		}
	}
	if (($herramientas[57]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[57]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=amplia_contenido&ampliacion=bloque&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-magic\"></i> ".$textos[57]."</a></li>";
		}
	}
	if (($herramientas[58]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[58]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=campos_adicionales&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-check-square-o\"></i> ".$textos[58]."</a></li>";
		}
	}
	print "</ul>";
	print "</li>";
	
	//ACCIONES ESPECÍFICAS	
	if (count($textos_personalizadas["contenidos"])>0)	
	{
		print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["accionesEspecificas"].' <span class="caret"></span></a>';				
		print '<ul class="dropdown-menu">';
		for ($i=0;$i<count($textos_personalizadas["contenidos"]);$i++)
		{		
			$tmp = explode(".",$textos_personalizadas["contenidos"][$i]["Fichero"]);
			print "<li><a href=\"herramienta.php?modulo=Especifico&herramienta=".$tmp[0]."&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\">";
			print "<img src=\"".$textos_personalizadas["contenidos"][$i]["Icono"]."\"/>";
			print $textos_personalizadas["contenidos"][$i]["Nombre"]."</a></li>";
		}
		print "</ul>";
		print '</li>';
	}
	// HERRAMIENTAS GENERALES
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["herramientas"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[59]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[59]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Estadisticas&herramienta=estadisticas_contenido&contenido=".$_GET['contenido']."\"><i class=\"fa fa-bar-chart\"></i> ".$textos[59]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	break;
   case "editar_producto":
	// ACCIONES CON  PRODUCTOS
	// -----------------------------------	
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["herramientas"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[59]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[59]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE		
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=borrar_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$_GET['contenido']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-remove\"></i> ".$textos[48]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	break;

   case "secciones":
	// ACCIONES CON SECCIONES
	// ----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[60]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[60]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nueva_seccion&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-folder\"></i> ".$textos[60]."</a></li>";
		}
	}
	if (($herramientas[61]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[61]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=editar_seccion&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-edit\"></i> ".$textos[61]."</a></li>";
		}
	}
	if (($herramientas[62]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[62]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=borrar_seccion&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-remove\"></i> ".$textos[62]."</a></li>";
		}
	}
	if (($herramientas[63]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[63]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=asignar_buscable&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-search\"></i> ".$textos[63]."</a></li>";
		}
	}
	if (($herramientas[64]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[64]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=ordenar_secciones&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-sort-alpha-desc\"></i> ".$textos[64]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	// ACCIONES DE CARGA DE INFORMACIÓN
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["cargaInformacion"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[65]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[65]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-file\"></i> ".$textos[65]."</a></li>";
		}
	}
	if (($herramientas[138]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[138]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_producto&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-shopping-cart\"></i> ".$textos[138]."</a></li>";
		}
	}
	if (($herramientas[66]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[66]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nueva_encuesta&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><img src=\"/administra/Imagenes/seccion_encuesta.png\"> ".$textos[66]."</a></li>";
		}
	}
	if (($herramientas[67]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[67]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_formulario&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><img src=\"/administra/Imagenes/anadir_formulario.png\"> ".$textos[67]."</a></li>";
		}
	}
	if (($herramientas[68]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[68]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_codigo&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-code\"></i> ".$textos[68]."</a></li>";
		}
	}
	if (($herramientas[69]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[69]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_calendario&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-calendar\"></i> ".$textos[69]."</a></li>";
		}
	}
	if (($herramientas[70]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[70]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nuevo_webcam&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><img src=\"/administra/Imagenes/webcam.png\"> ".$textos[70]."</a></li>";
		}
	}
	if (($herramientas[116]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[116]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=anadir_fuentes_rss&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-rss\"></i> ".$textos[116]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	// HERRAMIENTAS GENERALES	
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["herramientas"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[72]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[72]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=permisos&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-key\"></i> ".$textos[72]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	//ACCIONES ESPECÍFICAS
	if (count($textos_personalizadas["secciones"])>0)
	{
		print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["accionesEspecificas"].' <span class="caret"></span></a>';
		print '<ul class="dropdown-menu">';
		for ($i=0;$i<count($textos_personalizadas["secciones"]);$i++)
		{
			$tmp = explode(".",$textos_personalizadas["contenidos"][$i]["Fichero"]);
			print "<li><a href=\"herramienta.php?modulo=Especifico&herramienta=".$tmp[0]."&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\">";
			print "<img src=\"".$textos_personalizadas["secciones"][$i]["Icono"]."\"/>";
			print $textos_personalizadas["secciones"][$i]["Nombre"]."</a></li>";
		}
		print "</ul>";
		print '</li>';
	}
	break;
   case "raiz":
	// ACCIONES DE RAIZ
	// ----------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[73]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[73]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=nueva_seccion&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."\"><i class=\"fa fa-folder\"></i> ".$textos[73]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	//ACCIONES ESPECÍFICAS
	if (count($textos_personalizadas["raiz"])>0)
	{
		print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["accionesEspecificas"].' <span class="caret"></span></a>';
		print '<ul class="dropdown-menu">';
		for ($i=0;$i<count($textos_personalizadas["raiz"]);$i++)
		{
			$tmp = explode(".",$textos_personalizadas["contenidos"][$i]["Fichero"]);
			print "<li><a href=\"herramienta.php?modulo=Especifico&herramienta=".$tmp[0]."&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\">";
			print "<img src=\"".$textos_personalizadas["raiz"][$i]["Icono"]."\"/>";
			print $textos_personalizadas["raiz"][$i]["Nombre"]."</a></li>";
		}
		print "</ul>";
		print '</li>';
	}
	break;
   case "encuestas":
	// ACCIONES CON CONTENIDOS
	// -----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["acciones"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[74]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[74]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=vincular_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$encuesta."&tipocontenido=".$tipocontenido."\"><img src=\"/administra/Imagenes/vincular_contenido.png\"> ".$textos[74]."</a></li>";
		}
	}
	if (($herramientas[75]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[75]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=editar_encuesta&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&encuesta=".$encuesta."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-edit\"></i>  ".$textos[75]."</a></li>";
		}
	}
	if (($herramientas[76]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[76]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=desvincula_contenido&seccion=".$_GET['seccion']."&ruta=".$_GET['ruta']."&contenido=".$encuesta."&tipocontenido=".$tipocontenido."\"><img src=\"/administra/Imagenes/desvincula_contenido.png\"> ".$textos[76]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	// ACCIONES DE CARGA DE INFORMACIÓN
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["carga"].' <span class="caret"></span></a>';
	print '<ul class="dropdown-menu">';
	if (($herramientas[77]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[77]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Carpetas&herramienta=encuesta_respuesta&encuesta=".$encuesta."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\"><i class=\"fa fa-bar-chart\"></i> ".$textos[77]."</a></li>";
		}
	}
	print "</ul>";
	print '</li>';
	//ACCIONES ESPECÍFICAS
	if (count($textos_personalizadas["encuestas"])>0)
	{
		print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown">'.$lang["accionesEspecificas"].' <span class="caret"></span></a>';
		print '<ul class="dropdown-menu">';
		for ($i=0;$i<count($textos_personalizadas["encuestas"]);$i++)
		{
			$tmp = explode(".",$textos_personalizadas["contenidos"][$i]["Fichero"]);
			print "<li><a href=\"herramienta.php?modulo=Especifico&herramienta=".$tmp[0]."&contenido=".$_GET['contenido']."&ruta=".$_GET['ruta']."&seccion=".$_GET['seccion']."&tipocontenido=".$tipocontenido."\">";
			print "<img src=\"".$textos_personalizadas["encuestas"][$i]["Icono"]."\"/>";
			print $textos_personalizadas["encuestas"][$i]["Nombre"]."</a></li>";
		}
		print "</ul>";
		print '</li>';
	}
	break;
}
print '</ul>';
print '</div>';
?>