<?php

//Esta funcion repara el Path de todos los hijos de una sección y les pone su raiz
//$Id el Id del padre, 
//$familia la tabla ejemplo Secciones, 
//$accion si cierto modifica si falso muestra solo los cambios
function repara_path($Id,$familia,$accion)
{
 global $db;
 
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
 
 $requete="SELECT `Id`, `NomFich`, `Path` FROM `$familia` WHERE `Id` = '$Id'";
 //echo $requete;

 
 $row = mysql_fetch_assoc($result);
 if (!$row) 
 {
  return false;
 }

 $path=$row["Path"];
 $path="$path/".$row["NomFich"];
 
 $requete="SELECT `Id`, `Titulo`, `IdPadre`, `NomFich`, `Path` FROM `$familia` WHERE `IdPadre` = '$Id' 
 ORDER BY `Orden`;";
 
 $result = mysql_query($requete);
 //echo $requete;
  
 $a=array();
 $filas=0;
 while ($row = mysql_fetch_assoc($result)) 
 {
   $filas++;
   $hid=$row['Id'];
   $hpadre=$row['IdPadre'];
   $hnomfich=$row['NomFich'];
   $hpath=$row['Path'];
   $htitulo=$row['Titulo'];
   $b[]=array();
   $b["Id"]=$hid;
   $b["IdPadre"]=$hpadre;
   $b["Path"]=$hpath;
   $b["NomFich"]=$hnomfich;
   $b["Titulo"]=$htitulo;
   $a[]=$b;
 }
 mysql_free_result($result);
 require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
 
 if ($filas==0) return false;
 if (!$accion) echo "<hr><b><li>$path</li><li>$Id</li></b>";
 if (!$accion) echo "<ul>";
 foreach ($a as $d) 
 {
  $id=$d['Id'];
  $padre=$d['IdPadre'];
  $ruta=$d['Path'];
  $nomfich=$d['NomFich'];
  $titulo=$d['Titulo'];
  if ($ruta==$path) $estilo="STYLE='background: #3deb3d'";
  else $estilo="STYLE='background: #ff8080'";
  if ($accion)
  {
   require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
   $requete="UPDATE `$familia` SET `Path` = '$path' WHERE `Id` = '$id';";
   //echo "<li>$requete</li>";
   $result = mysql_query($requete);
   require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
  }
 
 
  if (!$accion) echo "
  <TABLE WIDTH=100% >
	<TR VALIGN=TOP >
		<TD WIDTH=5%>
			$id
			
		</TD>
		<TD WIDTH=5%>
			$padre
			
		</TD>
		<TD WIDTH=20% $estilo>
			$ruta
			
		</TD>
		<TD WIDTH=20%>
			$nomfich
			
		</TD>
                <TD WIDTH=50%>
			$Titulo
			
		</TD>
	</TR>
</TABLE>
";
 }
 if (!$accion) echo "</ul>";
 foreach ($a as $d) 
 {
  $p=$d['Id'];
  repara_path($p,$familia,$accion);
 }
}

if ($_GET["pass"]=="caspita1") repara_path(1,"Secciones",false);
if ($_GET["pass"]=="caspita2") repara_path(1,"Secciones",true);
if ($_GET["pass"]=="caspita2") repara_path(1,"Secciones",false);
//
//echo "<hr><hr>MODIFICA<hr>";
//repara_path(1,"Secciones",true);
//echo "<hr><hr>VER como QUEDA<hr>";
//repara_path(1,"Secciones",false);