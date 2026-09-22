<?php
//VERSIÓN: v1.0 2014-03-14
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$tipocontenido = $_GET["tipocontenido"];
$contenido = $_GET["contenido"];
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/editar_campos_adicionales-".$_SESSION['idioma'].".conf");
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
if ((!isset($tipocontenido)||$tipocontenido=="")&&(isset($contenido)&&$contenido!=""))
{
	$requete = "SELECT * FROM Contenidos WHERE Id=".$contenido;
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$tipocontenido = $listado->IdTipoContenido;
	}
}
$requete = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido` IS NULL AND `IdTipoContenido`='".$tipocontenido."' ORDER BY `Orden`";

$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."'";


print "<form action=\"/administra/Carpetas/editar_campos_adicionales_2.php?contenido=".$contenido."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$tipocontenido."\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<ul>";
// Listamos los campos existentes
$listado2 = mysqli_fetch_object($result2);
$encontrado = false;
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		$tipodecampo = explode("/",$listado->Tipo);	
		switch ($tipodecampo[0]) 
		{
			case "TEXTO":
				if ($listado->Repeticion!="no")
				{
					$repeticiones = $listado->Repeticion;
					print "<div class=\Ampliacion_Repeticion\>";
					for ($i=1;($i<$repeticiones);$i++)
					{
						$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."/".$i."'";
						
						if ($result2 = mysqli_query($db, $requete2))
						{
							$listado2 = mysqli_fetch_object($result2);
							$campo = explode("/",$listado2->TituloCampo);
							if (isset($tipodecampo[1]))
							{
								print $listado->TituloCampo.$i.": <input name=\"".$listado->TituloCampo."/".$i."\" type=\"text\" value=\"".$listado2->Valor."\" size=\"50\" maxlength=\"".$tipodecampo[1]."\">";
							}
							else
							{
								print $listado->TituloCampo.$i.": <textarea name=\"".$listado->TituloCampo."/".$i."\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\">".$listado2->Valor."</textarea>";
							}
						}
						else
						{
							if (isset($tipodecampo[1]))
							{
								print $listado->TituloCampo.$i.": <input name=\"".$listado->TituloCampo."/".$i."\" type=\"text\" value=\"\" size=\"50\" maxlength=\"".$tipodecampo[1]."\">";
							}
							else
							{
								print $listado->TituloCampo.$i.": <textarea name=\"".$listado->TituloCampo."/".$i."\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\"></textarea>";
							}
						}
					}
					print "</div>";
				}
				else
				{
					if (isset($tipodecampo[1]))
					{
						$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."'";
						
						if ($result2 = mysqli_query($db, $requete2))
						{
							$listado2 = mysqli_fetch_object($result2);
							print $listado->TituloCampo.": <input name=\"".$listado->TituloCampo."\" type=\"text\" value=\"".$listado2->Valor."\" size=\"50\" maxlength=\"".$tipodecampo[1]."\">";
						}
						else
						{
							print $listado->TituloCampo.": <input name=\"".$listado->TituloCampo."\" type=\"text\" value=\"\" size=\"50\" maxlength=\"".$tipodecampo[1]."\">";
						}
					}
					else
					{
						$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."'";
						
						if ($result2 = mysqli_query($db, $requete2))
						{
							$listado2 = mysqli_fetch_object($result2);
							print $listado->TituloCampo.": <textarea name=\"".$listado->TituloCampo."\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\">".$listado2->Valor."</textarea>";
						}
					else
					{
						print $listado->TituloCampo.": <textarea name=\"".$listado->TituloCampo."\" cols=\"60\" rows=\"10\" class=\"form\" wrap=\"VIRTUAL\"></textarea>";
					}
				}
			}
			break;
		case "NUMERO":
			if ($listado->Repeticion=="no")
			{
				$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."'";
				
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);
					print $listado->TituloCampo.": <input name=\"".$listado->TituloCampo."\" type=\"text\" value=\"".$listado2->Valor."\" size=\"".$tipodecampo[1]."\" maxlength=\"".$tipodecampo[1]."\">";
				}
				else
				{
					print $listado->TituloCampo.": <input name=\"".$listado->TituloCampo."\" type=\"text\" value=\"\" size=\"".$tipodecampo[1]."\" maxlength=\"".$tipodecampo[1]."\">";
				}
			}
			else
			{
				print "<div class=\Ampliacion_Repeticion\>";
				for ($i=1;($i<($listado->Repeticion + 1));$i++)
				{
					$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."/".$i."'";
					
					if ($result2 = mysqli_query($db, $requete2))
					{
						$listado2 = mysqli_fetch_object($result2);
						print $listado->TituloCampo."(".$i."): <input name=\"".$listado->TituloCampo."/".$i."\" type=\"text\" value=\"".$listado2->Valor."\" size=\"".$tipodecampo[1]."\" maxlength=\"".$tipodecampo[1]."\">";
					}
					else
					{
						print $listado->TituloCampo."(".$i."): <input name=\"".$listado->TituloCampo."/".$i."\" type=\"text\" value=\"\" size=\"".$tipodecampo[1]."\" maxlength=\"".$tipodecampo[1]."\">";
					}
				}
			}			    
			break;
		case "CHECK":			
			$requete2 = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`='".$tipodecampo[1]."' ORDER BY `Orden`";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				print $listado->TituloCampo.":";
				print "<ul style=\"clear:both;width:100%;float:left;\">";
				while($listado2 = mysqli_fetch_object($result2))
				{
					print "<li style=\"background:#EEE;float:left;margin-right:10px;\">";
					$requete3 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo` LIKE '%".$listado->TituloCampo."%' AND `Valor` = '".$listado2->Titulo."'";
					
					if ($result3 = mysqli_query($db, $requete3)) print $listado2->Titulo.": <input style=\"display:inline;\" name=\"".$listado->TituloCampo."[]\" type=\"checkbox\" value=\"".$listado2->Titulo."\" checked>";
					else print $listado2->Titulo.": <input style=\"display:inline;\" name=\"".$listado->TituloCampo."[]\" type=\"checkbox\" value=\"".$listado2->Titulo."\">";
					if (isset($listado2->Imagen)) print "<img src=\"http://".$_SERVER['SERVER_NAME'].$listado2->Imagen."\" height=\"12px\">";
					print "</li>";
				}
				print "</ul>";
			}
			break;
		case "RADIO":
			$requete2 = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`='".$tipodecampo[1]."' ORDER BY `Orden`";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$i=1;
				print $listado->TituloCampo.":";
				print "<ul style=\"clear:both;width:100%;float:left;\">";
				while($listado2 = mysqli_fetch_object($result2))
				{			  
					print "<li style=\"background:#EEE;float:left;margin-right:10px;\">";
					$requete3 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo` LIKE '%".$listado->TituloCampo."%' AND `Valor` = '".$listado2->Titulo."'";
					
					if ($result3 = mysqli_query($db, $requete3)) print $listado2->Titulo.": <input style=\"display:inline;\" name=\"".$listado->TituloCampo."\" type=\"radio\" value=\"".$listado2->Titulo."\" checked>";
					else print $listado2->Titulo.": <input style=\"display:inline;\" name=\"".$listado->TituloCampo."\" type=\"radio\" value=\"".$listado2->Titulo."\">";
					if (isset($listado2->Imagen)) print "<img src=\"http://".$_SERVER['SERVER_NAME'].$listado2->Imagen."\" height=\"12px\">";
					$i++;
					print "</li>";
				}
				print "</ul>";
			}
			break;
		case "GRUPO":
			if ($listado->Repeticion!="no")
			{
				$repeticiones = $listado->Repeticion;
				print "<div class=\Ampliacion_Repeticion\>";			  
				for ($i=1;($i<$repeticiones);$i++)
				{
					$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."/".$i."'";
					
					$listado2 = mysqli_fetch_object($result2);
					$requete3 = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`= '".$tipodecampo[1]."' ORDER BY `Orden`";
					
					if ($result3 = mysqli_query($db, $requete3))
					{
						print $listado->TituloCampo.$i.": ";
						print "<select name=\"".$listado->TituloCampo."/".$i."\">";
						print "<option value=\"\"></option>";
						while($listado3 = mysqli_fetch_object($result3))
						{
							if ($listado2->Valor!=$listado3->Titulo) print "<option value=\"".$listado3->Titulo."\">".$listado3->Titulo."</option>";
							else print "<option value=\"".$listado3->Titulo."\" selected>".$listado3->Titulo."</option>";
						}
						print "</select>";
					}
				}
				print "</div>";
			}
			else
			{
				$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."'";
				
				$listado2 = mysqli_fetch_object($result2);
				$requete3 = "SELECT * FROM `CamposAdicionalesGrupos` WHERE `Tipo`= '".$tipodecampo[1]."' ORDER BY `Orden`";				
				
				if ($result3 = mysqli_query($db, $requete3))
				{					
					print $listado->TituloCampo.": ";
					print "<select name=\"".$listado->TituloCampo."\">";
					print "<option value=\"\"></option>";
					while($listado3 = mysqli_fetch_object($result3))
					{
						if ($listado2->Valor!=$listado3->Titulo) print "<option value=\"".$listado3->Titulo."\">".$listado3->Titulo."</option>";
						else print "<option value=\"".$listado3->Titulo."\" selected>".$listado3->Titulo."</option>";
					}
					print "</select>";
				}			
			}
			break;
		case "FICHERO":
				$requete2 = "SELECT * FROM `CamposAdicionales` WHERE `IdContenido`='".$contenido."' AND `IdTipoContenido`='".$tipocontenido."' AND `TituloCampo`='".$listado->TituloCampo."'";
				
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);
					print $listado->TituloCampo.":";
					if ($listado2->Valor!="") 
					{
						print " (<a href=\"/Recursos/".$listado2->Valor."\" target=\"_blank\">".$listado2->Valor."</a>) ";
						print "<input style=\"display:inline;\"  type=\"checkbox\" name=\"borrar_ficheros[]\" value=\"".$listado2->Valor."\"> ".$lang["borrarFichero"];
					}
					print "<input name=\"".$listado->TituloCampo."\" type=\"file\"/>";
				}
				else
				{
					print $listado->TituloCampo.": <input name=\"".$listado->TituloCampo."\" type=\"file\"/>";
				}
			break;
		}
		print "</li>";
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
print "</ul>";
print "<input class=\"boton\" type=\"submit\" value=\"".$lang["enviar"]."\">";
print "</form>";
?>
