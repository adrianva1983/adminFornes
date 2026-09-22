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
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
 $item="Contenidos";
 $requete = "SELECT `Id`, `Titulo`, `NomFich`, `Idioma` FROM `".$item."` WHERE `Titulo` like '%".$busca."%' AND `Tipo`='contenido'";
 //echo $requete;
 $result = mysql_query($requete);
 if (!$result) exit;
 if(mysqli_num_rows($result)==0)
 {
  echo "<li>Lo siento, no hay nada parecido en la Base de Datos</li>";
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
 
 echo "<ul>";
 foreach ($a as $d) 
 {
  $id=$d['Id'];
  $titulo=$d['Titulo'];
  $nomfich=$d['NomFich'];
  $idiomas=$d['Idioma'];
  print "<li><strong>".$titulo."</strong>";
  print "<ul>";
  $requete = "SELECT * FROM `Publicaciones` WHERE IdContenido = ".$id;
	
	if ($result = mysqli_query($db, $requete))
	{
		while ($listado = mysqli_fetch_object($result)) 
 		{
			$requete2 = "SELECT `Titulo`,`Path`,`NomFich`, `Idioma` FROM `Secciones` WHERE Id = ".$listado->IdSeccion;
			
			$listado2 = mysqli_fetch_object($result2);			
			$ruta = $listado2->Path;
			if ($ruta!="") $ruta = $ruta."/".$listado2->NomFich;
			else $ruta = $listado2->NomFich;
			$seccion = $listado->IdSeccion;
			$tituloSeccion=$listado2->Titulo;
			$requete2 = "SELECT `IdTipoContenido` FROM `Contenidos` WHERE Id = ".$id;
			
			$listado2 = mysqli_fetch_object($result2);
			$idTipoContenido = $listado2->IdTipoContenido;
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Comercio&herramienta=nueva_reserva1&contenido=".$id."&ruta=".$ruta."&seccion=".$tituloSeccion."&titulocontenido=".$titulo."\"><img src=\"/administra/Imagenes/".$idiomas.".png\"> ".$tituloSeccion."</a></li>";
		}
	}
  print "</ul>";
  print "</li>";
 }
 echo "</ul>";
 mysql_free_result($result);
 mysql_free_result($result2);
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
