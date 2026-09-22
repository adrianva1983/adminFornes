<?php
global $db;
function conecta()
{
 global $db;
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
}
function desconecta()
{
 global $db;
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
}


function ordena($familia,$item,$iid,$accion,$elemento)
{
 global $test;
 if ($test) echo "<li>familia,item,iid,accion,elemento</li><li>$familia,$item,$iid,$accion,$elemento</li>";
 if ($iid=="NULL") $loc="`$item` IS NULL";
 else $loc="`$item` = $iid";
 if ($accion==2) $ordenacion="DESC";
 else $ordenacion="ASC";
 $requete="SELECT `Id`, `Orden`, `$item` FROM `$familia` WHERE $loc ORDER BY `Orden` $ordenacion";
 //echo $requete;
 if ($test) echo "<li>$requete</li>";
 conecta();
 $result = mysql_query($requete);
 if ($test) echo "<li>$requete</li>";
 $a=array();
 $i=1;
 $E=-1;
 $elementos;
 //if (!$result) exit;
 while ($row = mysql_fetch_assoc($result)) 
 {
   $id=$row['Id'];
   $orden=$row['Orden'];
   $b[]=array();
   $b["Id"]=$id;
   $b["Orden"]=$orden;
   $a[]=$b;
   if ($orden==$elemento) $E=$i;
   if ($accion>2) if ($test) echo "<li>$i,$E,$id,$orden";
   $i++;
 }
 $elementos=$i-1;
 mysql_free_result($result);   
 $contador=0;
 $i=1;
 foreach($a as $ii)
 {
  if ($test) echo "<li>".$i["Orden"]." <b>".$ii["Id"]."</b></li>";
  if (($accion==1) or ($accion==2))
  {
   $contador++;
   $_i=$ii["Id"];
   $requete="UPDATE `$familia` SET `Orden` = $contador WHERE `Id` = $_i;";
   if ($test) echo "<li>$requete</li>";
   $result = mysql_query($requete);
  }
  if (($accion==3) and ($E>0))
  {
   $contador++;
   if ($E-1>$i) $C=$contador;
   if ($E-1==$i) $C=$contador+1;
   if ($E==$i) $C=$contador-1;
   if ($E<$i) $C=$contador;
   $_i=$ii["Id"];
   $requete="UPDATE `$familia` SET `Orden` = $C WHERE `Id` = $_i;";
   if ($test) echo "<li>$requete</li>";
   $result = mysql_query($requete);
  }
  if (($accion==4) and ($E>0))
  {
   $contador++;
   if ($E>$i) $C=$contador;
   if ($E==$i) $C=$contador+1;
   if ($E+1==$i) $C=$contador-1;
   if ($E+1<$i) $C=$contador;
   $_i=$ii["Id"];
   $requete="UPDATE `$familia` SET `Orden` = $C WHERE `Id` = $_i;";
   if ($test) echo "<li>$requete</li>";
   $result = mysql_query($requete);
  }
  if (($accion==5) and ($E>0))
  {
   $contador++;
   if ($E>$i) $C=$contador+1;
   if ($E==$i) $C=1;
   if ($E<$i) $C=$contador;
   $_i=$ii["Id"];
   $requete="UPDATE `$familia` SET `Orden` = $C WHERE `Id` = $_i;";
   if ($test) echo "<li>$requete</li>";
   $result = mysql_query($requete);
  }
  if (($accion==6) and ($E>0))
  {
   $contador++;
   if ($E>$i) $C=$contador;
   if ($E==$i) $C=$elementos;
   if ($E<$i) $C=$contador-1;
   $_i=$ii["Id"];
   $requete="UPDATE `$familia` SET `Orden` = $C WHERE `Id` = $_i;";
   if ($test) echo "<li>$requete</li>";
   $result = mysql_query($requete);
  }
  $i++;//!
 }
 desconecta(); 
}

function ordenaTotal($familia,$item,$accion)
{
 global $test;
 $requete="SELECT `$item` FROM `$familia` GROUP BY `$item` ORDER BY `$item` ASC ";
 if ($test) echo "<li>$requete</li>";
 conecta();
 $result = mysql_query($requete);
 if (!$result) exit;
 $a[]=array();
 $c=0;
 while ($row = mysql_fetch_assoc($result)) 
 {
   $_item=$row["$item"];
   $b[]=array();
   $b["item"]=$_item;
   $a[$c]=$b;
   if ($test) echo "<li>LOCALIZO:".$a[$c]["item"]."</li>";
   $c++;
 }
 mysql_free_result($result);
 desconecta(); 
 foreach($a as $d)
 {
  $_i=$d["item"];
  if ($test) echo "<li>ORDENO:".$_i."</li>";
  if (($_i=="") or ($_i==null)) $_i="NULL";
  ordena($familia,$item,$_i,$accion,"");
 }
}
?>