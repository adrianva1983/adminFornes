<?php
function obtener_publicaciones($id_contenido,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_contenido del que sacar las publicaciones
	//db con conexión a la base de datos
	//PRODUCE: array con las publicaciones del contenido
	$requete = "SELECT * FROM `Contenidos`,`Publicaciones` WHERE `Contenidos`.Id = ".$id_contenido;	
	$requete.= " AND `Contenidos`.Id=`Publicaciones`.IdContenido ORDER BY `Publicaciones`.Orden";
		
	$array_resultado = array();
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			$array_resultado_tmp["Plantilla"] = $listado->Plantilla;
			$array_resultado_tmp["IdSeccion"] = $listado->IdSeccion;
			$array_resultado_tmp["Visibilidad"] = $listado->Visibilidad;
			$array_resultado_tmp["CanonicalPrincipal"] = $listado->CanonicalPrincipal;
			array_push($array_resultado,$array_resultado_tmp);
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_ampliaciones($id_contenido,$tipo,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_contenido del que sacar las ampliaciones
	//tipo para sacar las ampliaciones de un tipo en concreto
	//db con conexión a la base de datos
	//PRODUCE: array con las ampliaciones del contenido
	$array_resultado = array();
	$requete = "SELECT * FROM `Contenidos`,`Publicaciones` WHERE `Contenidos`.IdPadre = ".$id_contenido;
	if ($tipo!="") $requete.=" AND `Contenidos`.Tipo='".$tipo."'";
	$requete.= " AND `Contenidos`.Id=`Publicaciones`.IdContenido ORDER BY `Publicaciones`.Orden";
		
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			$array_resultado_tmp["Id"] = $listado->Id;
			$array_resultado_tmp["IdPadre"] = $listado->IdPadre;
			$array_resultado_tmp["Titulo"] = $listado->Titulo;
			$array_resultado_tmp["Breve"] = $listado->Breve;
			$array_resultado_tmp["Foto"] = $listado->Foto;
			$array_resultado_tmp["Alternativo"] = $listado->Alternativo;
			$array_resultado_tmp["AnchoFoto"] = $listado->AnchoFoto;
			$array_resultado_tmp["AnchoFoto2"] = $listado->AnchoFoto2;
			$array_resultado_tmp["AltoFoto"] = $listado->AltoFoto;
			$array_resultado_tmp["AltoFoto2"] = $listado->AltoFoto2;
			$array_resultado_tmp["Redireccionar"] = $listado->Redireccionar;
			$array_resultado_tmp["Visibilidad"] = $listado->Visibilidad;
			array_push($array_resultado,$array_resultado_tmp);
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_seccion($id_seccion,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_seccion de la que obtener información
	//db con conexión a la base de datos
	//PRODUCE: array con la información de la sección
	$requete = "SELECT  * FROM Secciones WHERE Id=".$id_seccion;
	
	$enlaceTMPSeccion = "";
	$array_resultado = array();
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$array_resultado["Titulo"] = $listado->Titulo;
		if ($listado->Redireccionar!="") $enlaceTMPSeccion = $listado->Redireccionar;
		else
		{
			if ($listado->URLAmigable!="") $enlaceTMPSeccion = $listado->URLAmigable;
			else 
			{
				$enlaceTMPSeccion = "/Secciones";
				if (substr($listado->Path,0,1)!="/"&&$listado->Path!="") $enlaceTMPSeccion .= "/";
				$enlaceTMPSeccion .= $listado->Path."/".$listado->NomFich;				
			}
		}
		$array_resultado["Enlace"] = $enlaceTMPSeccion;
		$array_resultado["Id"] = $listado->Id;
		$array_resultado["IdPadre"] = $listado->IdPadre;
		$array_resultado["Texto"] = $listado->Texto;
		$array_resultado["Foto"] = $listado->Foto;
		$array_resultado["Icono"] = $listado->Icono;
		$array_resultado["Keywords"] = $listado->Keywords;
		$array_resultado["Visibilidad"] = $listado->Visibilidad;
		$array_resultado["Idioma"] = $listado->Idioma;
		if ($listado->TituloBuscadores!="") $array_resultado["TituloBuscadores"] = $listado->TituloBuscadores;
		else
		{
			$subcadena = substr(strip_tags($listado->Titulo),0,60);
			$indiceUltimoEspacio = strpos($subcadena, " ");
			$array_resultado["TituloBuscadores"] = substr(strip_tags($listado->Titulo),0,$indiceUltimoEspacio);
		}	
		if ($listado->DescripcionBuscadores != "") $array_resultado["DescripcionBuscadores"] = $listado->DescripcionBuscadores;
		else 
		{
			$subcadena = substr(strip_tags($listado->Titulo),0,180);
			$indiceUltimoEspacio = strpos($subcadena, " ");
			$array_resultado["DescripcionBuscadores"] = substr(strip_tags($listado->Titulo),0,$indiceUltimoEspacio);
		}
		$array_resultado["URLAmigable"] = $listado->URLAmigable;
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_secciones($id_padre,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_seccion de la que obtener información
	//db con conexión a la base de datos
	//PRODUCE: array con la información de la sección
	$requete = "SELECT * FROM Secciones WHERE IdPadre";
	if ($id_padre!="") $requete.= "=".$id_padre;
	else $requete.= " IS NULL";
	$requete.=" ORDER BY `Orden`";	
		
	$array_resultado = array();
	if ($result = mysqli_query($db, $requete))
	{
		while($listado = mysqli_fetch_object($result))
		{
			$tmp = obtener_seccion($listado->Id,$db);		
			array_push($array_resultado,$tmp);
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_contenido($id_seccion,$id_contenido,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_seccion en la que está publicado
	//id_contenido del contenido del que queramos sacar información
	//db con conexión a la base de datos
	//PRODUCE: array con la información del contenido
	$requete = "SELECT * FROM Contenidos WHERE Id=".$id_contenido;
	
	$array_resultado = array();
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$array_resultado["Id"] = $listado->Id;
		$array_resultado["Titulo"] = $listado->Titulo;
		if ($listado->Redireccionar !="")
		{
			$tmp = explode("|",$listado->Redireccionar);
			$array_resultado["Enlace"] = $tmp[0];
			$array_resultado["ComportamientoEnlace"] = $tmp[1];
		}
		else
		{
			if ($listado->URLAmigable !="") 
			{
				if (substr($listado->URLAmigable,0,1)=="/") $array_resultado["Enlace"] = $listado->URLAmigable;
				else $array_resultado["Enlace"] = $listado->Id."-".$listado->URLAmigable;
			}
			else
			{
				if ($id_seccion!="")
				{
					$tmp = obtener_seccion($id_seccion,$db);
					$array_resultado["Enlace"] = $tmp["Enlace"];
					if (substr($tmp["Enlace"],(strlen($tmp["Enlace"])-1),1)!="/") $array_resultado["Enlace"] .= "/";
					$array_resultado["Enlace"] .= $listado->NomFich.".php";
				}
				else
				{
					$tmp = obtener_publicaciones($id_contenido,$db);
					for ($i=0;$i<count($tmp);$i++)
					{
						if ($tmp[$i]["CanonicalPrincipal"]==1||$i==0)
						{
							$seccion = obtener_seccion($tmp[$i]["IdSeccion"],$db);
							$array_resultado["IdSeccion"] = $tmp[$i]["IdSeccion"];
							$array_resultado["Comportamiento"] = $tmp[$i]["Comportamiento"];
							$array_resultado["Plantilla"] = $tmp[$i]["Plantilla"];
							$array_resultado["Enlace"] = $seccion["Enlace"];
							if (substr($seccion["Enlace"],(strlen($seccion["Enlace"])-1),1)!="/") $array_resultado["Enlace"] .= "/";
							$array_resultado["Enlace"] .= $listado->NomFich.".php";
						}
					}
				}
			}
		}
		$array_resultado["Breve"] = $listado->Breve;
		$array_resultado["Tipo"] = $listado->Tipo;
		$array_resultado["Foto"] = $listado->Foto;
		$array_resultado["Fecha"] = $listado->Fecha;
		$array_resultado["Icono"] = $listado->Icono;
		$array_resultado["Keywords"] = $listado->Keywords;
		$array_resultado["TituloBuscadores"] = $listado->TituloBuscadores;
		$array_resultado["DescripcionBuscadores"] = $listado->DescripcionBuscadores;
		$array_resultado["Idioma"] = $listado->Idioma;
		if ($listado->PrecioBI!="") 
		{
			$array_resultado["PrecioBI"] = $listado->PrecioBI;
			$array_resultado["PrecioFinal"] = number_format($listado->PrecioBI+($listado->PrecioBI*$listado->PrecioTAX/100),2,',','.');
		}
		if ($listado->PrecioTAX!="") $array_resultado["PrecioTAX"] = $listado->PrecioTAX;
		
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_num_contenidos($id_seccion,$inicio,$fin,$rand,$db)
{
	// V1.0
	//2014-06-07
	//NECESITA: 
	//id_seccion de la que obtener contenidos
	//db con conexión a la base de datos
	//PRODUCE: array con los contenidos
	$array_resultado = array();
	$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdContenido = `Contenidos`.Id AND `Publicaciones`.IdSeccion = ".$id_seccion;
	$requete.= " ORDER BY ";
	if ($rand) $requete.="rand()";
	else $requete.="`Publicaciones`.Orden";
	$requete.=" LIMIT ".$inicio.",".$fin;
	
	if ($result = mysqli_query($db, $requete))
	{	
		while($listado = mysqli_fetch_object($result))
		{
			$tmp = obtener_contenido($id_seccion,$listado->IdContenido,$db);		
			$tmp["Plantilla"] = $listado->Plantilla;
			$tmp["Visibilidad"] = $listado->Visibilidad;
			$tmp["Comportamiento"] = $listado->Comportamiento;
			$tmp["Notas"] = $listado->Notas;
			array_push($array_resultado,$tmp);
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_contenidos($id_seccion,$db)
{
	// V1.0
	//2014-03-07
	//NECESITA: 
	//id_seccion de la que obtener contenidos
	//db con conexión a la base de datos
	//PRODUCE: array con los contenidos
	$array_resultado = array();
	$requete = "SELECT * FROM `Publicaciones`, `Contenidos` WHERE IdContenido = `Contenidos`.Id AND `Publicaciones`.IdSeccion = ".$id_seccion." ORDER BY `Publicaciones`.Orden";
	
	if ($result = mysqli_query($db, $requete))
	{	
		while($listado = mysqli_fetch_object($result))
		{
			$tmp = obtener_contenido($id_seccion,$listado->IdContenido,$db);		
			$tmp["Plantilla"] = $listado->Plantilla;
			$tmp["Visibilidad"] = $listado->Visibilidad;
			$tmp["Comportamiento"] = $listado->Comportamiento;
			$tmp["Notas"] = $listado->Notas;
			array_push($array_resultado,$tmp);
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
function obtener_campos_adicionales($id_contenido,$db)
{
	// V1.0
	//2014-07-31
	//NECESITA: 
	//id_contenido del que obtener los campos adicionales
	//db con conexión a la base de datos
	//PRODUCE: array con los campos adicionales
	$array_resultado = array();
	$requete = "SELECT * FROM `CamposAdicionales` WHERE IdContenido = ".$id_contenido;
	
	if ($result = mysqli_query($db, $requete))
	{	
		while($listado = mysqli_fetch_object($result))
		{			
			$array_resultado[$listado->TituloCampo] = $listado->Valor;			
		}
	}
	else $array_resultado = null;
	return $array_resultado;
}
?>