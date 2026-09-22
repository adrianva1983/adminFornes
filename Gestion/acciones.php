<?php
$seccion = $_GET['seccion'];
$contenido = $_GET['contenido'];
$ruta = $_GET['ruta'];
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
if ($herramienta=="arbol") {exit;}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/acciones-".$_SESSION['idioma'].".conf");
//Sacamos los textos
$textos = array();
$requete = "SELECT * FROM `Herramientas` WHERE `Idioma`='".$_SESSION['idioma']."' AND `Accion`='si'  AND `Herramientas`.IdPadre='119'";

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
		$requete2 = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdGrupo='".$listado->IdGrupo."' AND `Herramientas`.IdPadre='119'";
		
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
$requete = "SELECT * FROM `UsuariosHerramientas`,`Herramientas` WHERE `UsuariosHerramientas`.IdHerramienta=`Herramientas`.IdHerramienta AND `Herramientas`.Accion='si' AND `Herramientas`.Activado='si' AND `UsuariosHerramientas`.Activo='si' AND `UsuariosHerramientas`.IdUsuario='".$_SESSION['usuario_id']."' AND `Herramientas`.IdPadre='119'";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$herramientas[$listado->IdHerramienta]="si";
	}
}
print '<div class="top-navigation">';
print '<ul class="nav navbar-nav">';

switch ($herramienta) {
   case "clientes":
   case "buscar_clientes_2":
	// ACCIONES POR CLIENTES
	// -----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[123]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[123]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nuevo_cliente\"><i class='fa fa-user-plus'></i> ".$textos[123]."</a></li>";
		}
	}
	if (($herramientas[128]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[128]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=buscar_clientes\"><i class='fa fa-search'></i> ".$textos[128]."</a></li>";
		}
	}	
	if (($herramientas[137]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[137]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=clientes&modelo=347\"><i class='fa fa-legal'></i> ".$textos[137]."</a></li>";
		}
	}	
	print "</ul></li>";
	break;
   case "presupuestos":
	// ACCIONES POR PRESUPUESTOS
	// ----------------------
	print '<li class="dropdown"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[124]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[124]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO COORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nuevo_presupuesto\"><i class='fa fa-briefcast'></i> ".$textos[124]."</a></li>";
		}
	}
	print "</ul></li>";
	break;
   case "facturas":
	// ACCIONES POR FACTURAS
	// ----------------------
	print '<li><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[125]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[125]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nueva_factura\"><i class='fa fa-calculator'></i> ".$textos[125]."</a></li>";
		}
	}
	if (($herramientas[132]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[132]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=facturas_periodicas\"><i class='fa fa-calendar'></i> ".$textos[132]."</a></li>";
		}
	}
	if (($herramientas[134]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[134]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=1)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=facturas_buscar\"><i class='fa fa-search'></i> ".$textos[134]."</a></li>";
		}
	}	
	print "</ul></li>";
	break;
  case "tareas":
	// ACCIONES CON TAREAS
	// -----------------------
	print '<li><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[110]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[110]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=4)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nueva_tarea\"><i class='fa fa-tasks'></i> ".$textos[110]."</a></li>";
		}
	}
	if (($herramientas[109]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[109]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=4)
		{ //NIVEL MÍNIMO RESPONSABLE
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=historico_tareas\"><i class='fa fa-history'></i> ".$textos[109]."</a></li>";
		}
	}
	print "</ul></li>";	
	break;
  case "proyectos":
	// ACCIONES CON PROYECTOS
	// ----------------------------------
	print '<li><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[130]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[130]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO CORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nuevo_proyecto\"><i class='fa fa-archive'></i> ".$textos[130]."</a></li>";
		}
	}
	if (($herramientas[131]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[131]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=3)
		{ //NIVEL MÍNIMO CORDINADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=buscar_proyecto\"><i class='fa fa-search'></i> ".$textos[131]."</a></li>";
		}
	}
	print "</ul></li>";	
	break;
  case "contabilidad":
	// ACCIONES CON PROYECTOS
	// ----------------------------------
	print '<li><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-label">'.$lang["acciones"].'</span> <span class="caret"></span></a>';	
	print '<ul class="dropdown-menu">';
	if (($herramientas[140]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[140]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
	{
		if ($_SESSION['usuario_nivel']<=2)
		{ //NIVEL MÍNIMO ADMINISTRADOR
			print "<li><a href=\"herramienta.php?modulo=Gestion&herramienta=nuevo_apunte\"><i class='fa fa-money'></i> ".$textos[140]."</a></li>";
		}
	}
	print "</ul></li>";	
	break;
}
print '</ul>';
print '</div>';