<?php
//FUNCIONES USADAS
$chartab="_";
function db_arbol($tabas,$item,$padre,$ruta,$db)
{
 if ($padre=="") {exit;}
 if ($padre=="NULL") 
 {
  $requete="SELECT `Id`, `Titulo`, `NomFich` FROM `$item` WHERE `IdPadre` is NULL ORDER BY `Orden`";
 }
 else 
 {
  $requete = "SELECT `Id`, `Titulo`, `NomFich`  FROM `$item` WHERE `IdPadre`='".$padre."' ORDER BY `Orden`";
 } 
 if ($item=="") {exit;}
 $result = mysqli_query($db,$requete);
 $a=array();
 if (!$result) exit;
 while ($row = mysqli_fetch_assoc($result)) 
 {
   $id=$row['Id'];
   $titulo=$row['Titulo'];
   $nomfich=$row['NomFich'];
   $b[]=array();
   $b["Id"]=$id;
   $b["Titulo"]=$titulo;
   $b["NomFich"]=$nomfich;
   if ($ruta=="") 
   {
     $b["Ruta"]=$nomfich;
   }
   else
   {
     $b["Ruta"]=$ruta."/".$nomfich;
   }
   $a[]=$b;
 }
 mysqli_free_result($result); 
 
 echo "<ul>";
 foreach ($a as $d) 
 {
  $id=$d['Id'];
  $titulo=$d['Titulo'];
  $nomfich=$d['NomFich'];
  $enruta=$d['Ruta'];

  echo "<li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=$id&ruta=$enruta\"><i class=\"fa fa-folder\"></i> $titulo</a></li>";
  $tabas=$tabas+1;
  db_arbol("$tabas","$item","$id","$enruta",$db);
 }
 echo "</ul>";
}

//RESTO DEL PROGRAMA
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
if ($_SESSION['usuario_nivel']<=1)
{
 //EL USUARIO ES AL MENOS ADMINISTRADOR, MOSTRAMOS EL ARBOL COMPLETO
 db_arbol(0,"Secciones","NULL","",$db);
}
else
{  
   $secciones=array();
   $titulos=array();
   $permisos=array();
   $i=0;
   $requete="SELECT * FROM `Permisos` WHERE `IdUsuarioSuscrito` ='".$_SESSION['usuario_id']."' AND (`FinSuscripcion`>'".date("Y-m-d H:i:s")."' OR `FinSuscripcion` IS NULL) AND (`InicioSuscripcion`<'".date("Y-m-d H:i:s")."' OR `InicioSuscripcion` IS NULL);";
   
   //LISTAMOS LOS DISTINTOS PERMISOS DEL USUARIO ACTUAL
   if ($result = mysqli_query($db, $requete))
   {
     while($listado = mysqli_fetch_object($result))
     {
     $requete2="SELECT * FROM `Secciones` WHERE `Id` ='".$listado->IdSeccion."';";
     
     $listado2 = mysqli_fetch_object($result2);     
     $secciones[$i]=$listado->IdSeccion;
     $permisos[$i]=$listado->Nivel;
     $titulos[$i]=$listado2->Titulo;     
     $i++;
     }
   }

   if ($result) mysqli_free_result($result);     
   if ($result2) mysqli_free_result($result2);
   if ($result3) mysqli_free_result($result3);
   
   //Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/arbol-".$_SESSION['idioma'].".conf");
   //Listamos las secciones de las que se tiene permisos
   $i=0;
   print "<h1><i class=\"fa fa-folder\"></i> ".$lang["estructura"]."</h1>";
   while ($secciones[$i]) 
   {
   //Imprimo el padre
   print "<ul><li><a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$secciones[$i]."&ruta=$enruta\"><img src=\"/administra/Imagenes/secciones.png\">".$titulos[$i]."</a>(".$permisos[$i].")</li>";
   //Imprimo el arbol por debajo
   db_arbol(0,"Secciones",$secciones[$i],"",$db);
   print "</ul><hr>";
   $i++;
   }
}
?>
