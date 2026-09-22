<?php 
header('Content-Type: text/html; charset=iso-8859-1');
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php"); 
$consulta = "SELECT * from `EntornosPlantillasFicheros` WHERE `IdPlantilla`=".$_POST['id'];
$result = mysql_query($consulta,$db);
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		echo '<option value="'.$listado->IdPlantilla.'">'.$listado->Titulo.'</option>';
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php"); 
?>