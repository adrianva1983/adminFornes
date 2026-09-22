<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
 $item="Contenidos";
 $requete = "SELECT `Id`, `Titulo`, `NomFich`, `Idioma` FROM `".$item."` WHERE `Titulo` like '%".$busca."%' AND `Tipo`='contenido'";
 //echo $requete;
 $result = mysql_query($requete);
 if (!$result) exit;
 if(mysqli_num_rows($result)==0)
 {
  echo "<p class=\"mensajeKO\">".$lang["noResultados"]."</p>";
  mysql_free_result($result);
  require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
  exit;
 }
 $a=array();
 $padres=array();
 while ($row = mysql_fetch_assoc($result)) 
 {
   //EL USUARIO ES AL MENOS ADMINISTRADOR, MOSTRAMOS LOS RESULTADOS AL COMPLETO
   $id=$row['Id'];
   $titulo=$row['Titulo'];
   $nomfich=$row['NomFich'];
   $idiomas=$row['Idioma'];
   $b[]=array();
   $b["Id"]=$id;
   $b["Titulo"]=$titulo;
   $b["NomFich"]=$nomfich;
   $b["Idioma"]=$idiomas;
   $a[]=$b;
 }
 mysql_free_result($result);
 
  foreach ($a as $d) 
 {
  $id=$d['Id'];
  $titulo=$d['Titulo'];
  $nomfich=$d['NomFich'];
  $idiomas=$d['Idioma'];
  print '<div class="hr-line-dashed"></div>';
  print "<div class=\"search-result row\"><div class=\"col-md-6\"><i class=\fa fa-file\"></i> <h3>".$titulo."</h3></div>";
  print "<div class=\"col-md-6\">";
  $requete = "SELECT * FROM `Publicaciones` WHERE IdContenido = ".$id;
	
	if ($result = mysqli_query($db, $requete))
	{
		while ($listado = mysqli_fetch_object($result)) 
 		{
		  $requete2 = "SELECT `Titulo`,`Path`,`NomFich`,`Idioma` FROM `Secciones` WHERE Id = ".$listado->IdSeccion;
			
			$listado2 = mysqli_fetch_object($result2);			
			$ruta = $listado2->Path;
			if ($ruta!="") $ruta = $ruta."/".$listado2->NomFich;
			else $ruta = $listado2->NomFich;
			$seccion = $listado->IdSeccion;
			$tituloSeccion=$listado2->Titulo;
			$requete2 = "SELECT `IdTipoContenido` FROM `Contenidos` WHERE Id = ".$id;
			
			$listado2 = mysqli_fetch_object($result2);
			$idTipoContenido = $listado2->IdTipoContenido;
			print "<p><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=contenidos&contenido=".$id."&ruta=".$ruta."&seccion=".$seccion."&tipocontenido=".$idTipoContenido."\"><i class=\"fa fa-folder\"></i> <img src=\"/administra/Imagenes/".$idiomas.".png\"> ".$tituloSeccion."</a></p>";
		}
	}
  print "</div>";
  echo "</div>";
 } 
 mysql_free_result($result);
 mysql_free_result($result2);
?>
