<?php
//VERSIÓN: v1.0 2013-12-13
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$seccion= $_POST["seccion"];
$ruta= $_POST["ruta"];
$cancelar=$_POST["cancelar"];
$borrar=$_POST["borrar"];
print_r($_POST);
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}

if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
if ($cancelar)
{
	if ($seccion!=""){
	   header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
	}
	else {
	   header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");
	}
	exit;
}
$depura=false;

function ftp_rmdir_recursiva($path, $handle)
//Borra arbol de directorio de forma recursiva
{
  if (!(@ftp_rmdir($handle, $path) || @ftp_delete($handle, $path)))
  {
   $list = ftp_nlist($handle, $path);
   if (!empty($list))
   {
    foreach($list as $value)
    ftp_rmdir_recursiva($value, $handle);
   }
   @ftp_rmdir($handle, $path);
  }
}

function delDir($dirName) 
{
  if(empty($dirName)) 
  {
    return;
  }
  if(file_exists($dirName)) 
  {
    $dir = dir($dirName);
    while($file = $dir->read()) 
    {
      if($file != '.' && $file != '..') 
      {
        if(is_dir($dirName.'/'.$file)) 
        {
          delDir($dirName.'/'.$file);
        } 
        else 
        {
          if ($depura) echo "<li>Fich. borrar:<b>".$dirName.'/'.$file."</b>";
          @unlink($dirName.'/'.$file) or die('<li> El fichero <b>'.$dirName.'/'.$file.'</b> no se puede borrar</li>');
        }
      }
    }
    if ($depura) echo "<li>Dir. borrar:<b>".$dirName.'/'.$file."</b>";
    @rmdir($dirName.'/'.$file) or die('<li>El directorio <b>'.$dirName.'/'.$file.'</b> no se puede borrar</li>');
  } else 
  {
    if ($depura) echo '<li>El directorio <b>'.$dirName.'</b>" no existe</li>';
  }
}

function borrar_hijos($padre)
{
 //NECESITA: Se le pasa el Id del padre
 //MODIFICA: Elimina todas los hijos de la base de datos
 require("../Interface/conexion.php");

 $requete = "SELECT * FROM `Secciones` WHERE `IdPadre`='".$padre."';";
 $result = mysqli_query($db,$requete);
 $a=array();
 if (($result) && (mysqli_num_rows($result)>0))
 {
	while($listado = mysqli_fetch_object($result))
	{
		//Añadimos el elemento a la lista de secciones a borrar
		$a["id"]=$listado->Id;
	}
	mysqli_free_result($result);
 }
 
 $requete = "DELETE FROM `Secciones` WHERE `Id` = '".$padre."';";
 mysqli_query($db,$requete);
 //Eliminamos todas las publicaciones en esta sección
 $requete = "DELETE FROM `Publicaciones` WHERE `IdSeccion` = '".$padre."';";
 mysqli_query($db,$requete);
 require("../Interface/cierre.php");	 
	
 foreach ($a as $id) 
 {
  require("../Interface/conexion.php");
  //Borramos la sección de la base de datos
  borrar_hijos($id);
 }
}

//----------------------------------------------------------------------------------------

//Borramos la carpeta y todo su contenido
///?POR?? ftp_rmdir_recursiva("public_html/Secciones/".$ruta."/".$NomFich, $connection);

require("../Interface/conexion.php");
$requete = "SELECT `Path`, `NomFich`, `IdPadre` FROM `Secciones` WHERE `Id` = '".$seccion."'";
if ($depura) echo "<li>$requete</li>";
mysqli_query($db,$requete);
$result = mysqli_query($db,$requete);
$row=mysqli_fetch_object($result);
if ($row->IdPadre)
{
	$requete2 = "SELECT `Path`, `NomFich`, `Id` FROM `Secciones` WHERE `Id`='".$row->IdPadre."'";
	$result2 = mysqli_query($db,$requete2);
	$row2=mysqli_fetch_object($result2);
	$conPadre = true;
}
else $conPadre = false;
if (strlen($ruta)>0) $ruta =$row->Path."/".$row->NomFich;
else $ruta =$row->NomFich;

require("../Interface/cierre.php");	 

if (strlen($ruta)>0)
{
 $realdir=$_SERVER['DOCUMENT_ROOT']."/Secciones/$ruta";
 if (strstr($realdir,".."))
 {
  echo "No permitido eliminar rutas con [.] por seguridad";
 }
 else
 {
  delDir($realdir);
 }
}
else
{
 echo "<li>No permitido rutas no registradas por seguridad</li>";
}
//Buscamos secciones hijo de la sección que borramos para eliminarlas también
borrar_hijos($seccion);
//Redirige al padre para ver como queda. Si no hay padre redirige a la herramienta raiz

if ($conPadre) header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$row2->Id."&ruta=".$row2->Path."/".$row2->NomFich);
else header("Location:/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=raiz");

?>