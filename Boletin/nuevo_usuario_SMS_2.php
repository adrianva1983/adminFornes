<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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
 $item="Usuarios";
 $requete = "SELECT `Id`, `Nombre`, `Apellidos`, `Foto` FROM `".$item."` WHERE `AltaSMS`='si' AND `Activado`='si' AND `NivelAcceso`>='".$_SESSION['usuario_nivel']."' AND (`Nombre` like '%".$busca."%' OR `Apellidos` like '%".$busca."%' OR `Ciudad` like '%".$busca."%' OR `Direccion` like '%".$busca."%' OR `CP` like '%".$busca."%' OR `Pais` like '%".$busca."%' OR `Telefono` like '%".$busca."%' OR `Movil` like '%".$busca."%')";
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
     $nombre=$row['Nombre'];
     $apellidos=$row['Apellidos'];
     $foto=$row['Foto'];
     $b[]=array();
     $b["Id"]=$id;
     $b["Nombre"]=$nombre;
     $b["apellidos"]=$apellidos;
     $b["foto"]=$foto;
     $a[]=$b;
 }
 mysql_free_result($result);
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
 
 echo "<ul>";
 foreach ($a as $d)
 {
  $id=$d['Id'];
  $nombre=$d['Nombre'];
  $apellidos=$d['apellidos'];
  if ($foto) echo "<li><input class=\"suscripcion\" name=\"contenido/".$id."\" type=\"checkbox\" value=\"".$id."\"><img src=\"/administra/Imagenes/usuario.png\"><img src=\"/Imagenes/".$foto."\"><strong>".$nombre.", ".$apellidos."</strong><br/></li>";
  else echo "<li><input class=\"suscripcion\" name=\"usuario/".$id."\" type=\"checkbox\" value=\"".$id."\"><img src=\"/administra/Imagenes/usuario.png\"><strong>".$nombre.", ".$apellidos."</strong><br/></li>";
 }
 echo "</ul>";
?>
