<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "DELETE FROM `PertenenciaGrupos` WHERE `IdUsuario` = '".$Id."' AND `IdGrupo` = '".$grupo."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
switch ($herramienta) 
{
	case "usuarios_grupos2":
		$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
		header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina);
		break;
}

?>