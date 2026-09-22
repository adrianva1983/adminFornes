<?php
//VERSIÓN: v1.0 2013-10-28
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$Idioma_Pasado = $_GET["Idioma_Pasado"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
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

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/raiz-".$_SESSION['idioma'].".conf");
//Miramos los idiomas activados en la parte externa
$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Exterior` ='si' ORDER BY `Orden`";
$result = mysqli_query($db,$requete);
if (($result) && (mysqli_num_rows($result)>0))	
{
	$i=0;
	while($listado = mysqli_fetch_object($result))
	{
		$idiomas[$i]=$listado->Codigo;
		$descripcionIdioma[$i]=$listado->Descripcion;
		$i++;
	}
}
if (2 > $_SESSION['usuario_nivel'])
{
	// Herramientas superiores
	print '<div class="row">';
	print "<div class=\"btn-group col-md-12\">";		
	for ($i=1;($i<count($idiomas));$i++)
	{
		print "<a class=\"btn btn-sm btn-white";
		print "\" href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz&Idioma_Pasado=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"></a>";		
	}
	print "</div></div>";
	//Imprimimos el listado de carpetas
	if ($Idioma_Pasado!="") $requete = "SELECT * FROM `Secciones` WHERE `IdPadre` IS NULL AND `Idioma`='".$Idioma_Pasado."' ORDER BY `Orden`";
	else $requete = "SELECT * FROM `Secciones` WHERE `IdPadre` IS NULL AND `Idioma`='".$idiomas[0]."' ORDER BY `Orden`";
	
	print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['secciones_raiz'].'</h5>';
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content"><div class="row"><table class="table table-striped">
                                    <thead>';
	$result = mysqli_query($db,$requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		while($listado = mysqli_fetch_object($result))
		{	
			print "<tr>";
			print "<td>";	
			print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
			print '<ul class="dropdown-menu">';
			if (($herramientas[61]=="si")||($_SESSION['usuario_nivel']<1)||($_SESSION['usuario_nivel']<2&&$herramienta_activada[61]!="")) //SI SE PERMITE LA HERRAMIENTA EN EL SISTEMA Y EN EL USUARIO
			{
				if ($_SESSION['usuario_nivel']<=2)
				{ //NIVEL MÍNIMO COORDINADOR
					print '<li><a href="herramienta.php?modulo=Carpetas&amp;herramienta=editar_seccion&amp;seccion='.$listado->Id.'"><i class=\"fa fa-edit\"></i> '.$lang['editar_seccion'].'</a></li>';
				}
			}
			switch ($listado->Visibilidad)
			{
				case "visible":
					//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
					if ($nivel<3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=oculto&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-pause\"></i> ".$lang["desactivar"]."</a></li>";
					break;
				case "oculto":
					//PERMISOS NECESARIOS PARA PAUSAR/PLAY DE COORDINADOR
					if ($nivel<3) print "<li><a href=\"/administra/Carpetas/funciones/visibilidad_seccion.php?pasada=visible&Id=".$listado->Id."&ruta=".$ruta."&seccion=".$seccion."\"><i class=\"fa fa-play\"></i> ".$lang["activar"]."</a></li>";
					break;
				case "privado":
					print "<li><i class=\"fa fa-key\"></i>".$lang["privado"]."</li>";
					break;
			}
			$orden=$listado->Orden;
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=NULL&elemento=".$orden."&accion=5&ruta=".$ruta."&seccion=NULL\"><img src=\"/administra/Imagenes/top_arriba.png\" alt=\"".$lang["toparriba"]."\" title=\"".$lang["toparriba"]."\"> ".$lang["toparriba"]."</a></li>";
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=NULL&elemento=".$orden."&accion=3&ruta=".$ruta."&seccion=NULL\"><img src=\"/administra/Imagenes/arriba.png\" alt=\"".$lang["arriba"]."\" title=\"".$lang["arriba"]."\"> ".$lang["arriba"]."</a></li>";
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=NULL&elemento=".$orden."&accion=4&ruta=".$ruta."&seccion=NULL\"><img src=\"/administra/Imagenes/abajo.png\" alt=\"".$lang["abajo"]."\" title=\"".$lang["abajo"]."\"> ".$lang["abajo"]."</a></li>";
			print "<li><a href=\"/administra/Carpetas/funciones/ordenacion.php?ordena=ordena&familia=Secciones&item=IdPadre&iid=NULL&elemento=".$orden."&accion=6&ruta=".$ruta."&seccion=NULL\"><img src=\"/administra/Imagenes/top_abajo.png\" alt=\"".$lang["topabajo"]."\" title=\"".$lang["topabajo"]."\"> ".$lang["topabajo"]."</a></li>";
			if ($Idioma_Pasado=="")
			{
				for ($i=1;($i<=(count($idiomas)-1));$i++)
				{
					$requete2 = "SELECT * FROM `Secciones` WHERE `RelacionIdioma` = '".$listado->Id."' AND `Idioma`='".$idiomas[$i]."'";
					$result2 = mysqli_query($db,$requete2);
					if (($result2) && (mysqli_num_rows($result2)>0))					
					{
						$listado2 = mysqli_fetch_object($result2);			
						print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=editar_seccion&seccion=".$listado2->Id."&ruta=".$listado2->NomFich."&codigoIdioma=".$idiomas[$i]."&herramientaOrigen=raiz&codigoIdioma=".$idiomas[$i]."\"><img src=\"/administra/Imagenes/".$idiomas[$i].".png\" alt=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["editarIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
					}
					else
					{		
						print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=nueva_seccion&seccion=".$listado->IdPadre."&ruta=".$listado->Path."&referenciaIdioma=".$listado->Id."&codigoIdioma=".$idiomas[$i]."&herramientaOrigen=raiz\"><img src=\"/administra/Imagenes/".$idiomas[$i]."_OFF.png\" alt=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\" title=\"".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."\"> ".$lang["anadirIdioma"]." ".$descripcionIdioma[$i]."</a></li>";
					}
				}
			}
			print "</ul></div></td>";
			print "<td>";		
			$carpeta=$listado->NomFich;
			print "<a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado->Id."&ruta=".$carpeta."\"><i class=\"fa fa-folder\"></i> ".$listado->Titulo."</a>";
			print "</td></tr>";
		}	
	}
	print '</tbody></table>';	
	print "</div></div>";
	print "</div></div></div>";
}
else
{//MOSTRAMOS LAS CARPETAS DE LAS QUE ES RESPONSABLE / COORDINADOR
	// RESPONSABILIDAD SOBRE SECCIONES POR USUARIO
	$requete = "SELECT Secciones.Id,Secciones.Titulo,Secciones.Path,Secciones.NomFich,Permisos.* FROM Permisos,Secciones WHERE IdUsuarioSuscrito=".$_SESSION['usuario_id']." AND Secciones.Id=Permisos.IdSeccion;";
	$result = mysqli_query($db,$requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang["suscrito"].'</h5>';
		print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
		print '<div class="ibox-content"><div class="row"><table class="table table-striped">
										<thead>';
		while($listado = mysqli_fetch_object($result))
		{
			print "<tr><td><i class=\"fa fa-folder\"></i> <a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado->IdSeccion."&ruta=".$listado->Path."/".$listado->NomFich."\">".$listado->Titulo."</a></td></tr>";
		}
		print '</tbody></table>';	
		print "</div></div>";
		print "</div></div></div>";
	}
	// RESPONSABILIDAD SOBRE SECCIONES POR  GRUPO
	
	$requete = "SELECT * FROM Grupos,PertenenciaGrupos WHERE `PertenenciaGrupos`.IdGrupo = `Grupos`.Id AND `PertenenciaGrupos`.IdUsuario = ".$_SESSION['usuario_id'];	
	$result = mysqli_query($db,$requete);
	if (($result) && (mysqli_num_rows($result)>0))	
	{
		print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang["suscrito"].'</h5>';
		print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
		print '<div class="ibox-content"><div class="row"><table class="table table-striped">
										<thead>';
		while($listado = mysqli_fetch_object($result))
		{			
			$requete2 = "SELECT Secciones.Id,Secciones.Titulo,Secciones.Path,Secciones.NomFich,Permisos.* FROM Permisos,Secciones WHERE IdGrupoSuscrito=".$listado->IdGrupo." AND Secciones.Id=Permisos.IdSeccion;";			
			$result2 = mysqli_query($db,$requete2);
			if (($result2) && (mysqli_num_rows($result2)>0))	
			{
				print $lang["suscrito"]." (".$listado->Nombre."): <ul>";
				while($listado2 = mysqli_fetch_object($result2))
				{
					print "<tr><td><i class=\"fa fa-folder\"></i> <a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado2->IdSeccion."&ruta=".$listado2->Path."/".$listado2->NomFich."\">".$listado2->Titulo."</a></td></tr>";
				}				
			}
		}
		print '</tbody></table>';	
		print "</div></div>";
		print "</div></div></div>";
	}
}
if (isset($result)) mysqli_free_result($result);
?>
