<?php
//FUNCIONES USADAS
$chartab="_";
function db_arbol($tabas,$item,$padre,$ruta)
{
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
 if ($padre=="") {exit;}
 if ($padre=="NULL") 
 {
  $requete="SELECT `Id`, `Titulo`, `NomFich`, `Path`, `Plantilla` FROM `$item` WHERE `IdPadre` is NULL AND `Visibilidad`='visible' AND `Idioma`='ES-ES' ORDER BY `Orden`";
 }
 else 
 {
  $requete = "SELECT `Id`, `Titulo`, `NomFich`, `Path`, `Plantilla`  FROM `$item` WHERE `IdPadre`='".$padre."' AND `Visibilidad`='visible' AND `Idioma`='ES-ES' ORDER BY `Orden`";
 } 
 if ($item=="") {exit;}
 $result = mysql_query($requete);
 $a=array();
 if (!$result) exit;
 while ($row = mysql_fetch_assoc($result)) 
 {
   $id=$row['Id'];
   $titulo=$row['Titulo'];
   $nomfich=$row['NomFich'];
   $plantilla=$row['Plantilla'];
   $b[]=array();
   $b["Id"]=$id;
   $b["Titulo"]=$titulo;
   $b["NomFich"]=$nomfich;
   $b["Path"]=$path;
   $b["Plantilla"]=$plantilla;
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
 mysql_free_result($result);
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
 

 foreach ($a as $d) 
 {
  $id=$d['Id'];
  $titulo=$d['Titulo'];
  $nomfich=$d['NomFich'];
  $enruta=$d['Ruta'];
	$plantilla=$d['Plantilla'];
	if ($plantilla!="")
	{
  	echo "<url>\n";
  	echo "<loc>/Secciones/".$enruta."/index.php</loc>\n";
  	echo "</url>\n";
  }
  $tabas=$tabas+1;
  db_arbol("$tabas","$item","$id","$enruta");
 }
}

//RESTO DEL PROGRAMA
//Comprobamos el acceso
 echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
 echo "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">";
db_arbol(0,"Secciones","NULL","");
 echo "</urlset>";
?>
