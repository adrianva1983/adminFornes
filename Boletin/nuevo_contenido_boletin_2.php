<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
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
 $requete = "SELECT `Id`, `Titulo`, `Foto`, `Breve`  FROM `".$item."` WHERE (`Titulo` like '%".$busca."%' OR `Breve` like '%".$busca."%') AND `IdPadre` is NULL";
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
     $id=$row['Id'];
     $titulo=$row['Titulo'];
     $foto=$row['Foto'];
     $breve=$row['Breve'];
     $b[]=array();
     $b["Id"]=$id;
     $b["Titulo"]=$titulo;
     $b["Foto"]=$foto;
     $b["Breve"]=$breve;
     $a[]=$b;
 }
 mysql_free_result($result);
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
 
 echo "<ul>";
 foreach ($a as $d) 
 {
  $id=$d['Id'];
  $titulo=$d['Titulo'];
  $foto=$d['Foto'];
  $breve=$d['Breve'];
  if ($foto) echo "<li><input class=\"suscripcion\" name=\"contenido/".$id."\" type=\"checkbox\" value=\"".$id."\"><img src=\"/administra/Imagenes/contenido.png\"><img src=\"/Imagenes/".$foto."\"><strong>".$titulo."</strong><br/>".$breve."</li>";
  else echo "<li><input class=\"suscripcion\" name=\"contenido/".$id."\" type=\"checkbox\" value=\"".$id."\"><img src=\"/administra/Imagenes/contenido.png\"><strong>".$titulo."</strong><br/>".$breve."</li>";
 }
 echo "</ul>";
?>
