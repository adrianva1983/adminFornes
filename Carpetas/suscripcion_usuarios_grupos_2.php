<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

$campos=$_POST;
$titulos = array_keys($_POST);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//BORRAMOS TODAS LAS SUSCRIPCIONES PARA LUEGO CREAR SOLO LAS QUE PERMANEZCAN ACTIVAS Y LAS NUEVAS
$requete = "DELETE FROM `Permisos` WHERE `IdSeccion`='".$seccion."' AND `Nivel`='4';";   
mysqli_query($db,$requete);
for ($i=0;$i<count($campos);$i++)
{
 if ($campos[$titulos[$i]]!="")
 {
   $tipodecampo = explode("/",$titulos[$i]);
   switch ($tipodecampo[0]) 
   {
     case "grupos":
	if ($campos[$titulos[$i+1]]!="")
        { //Tenemos Fecha fin Suscripción
        $requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdGrupoSuscrito`, `InicioSuscripcion`, `FinSuscripcion`) VALUES ('".$seccion."', '4', '".$campos[$titulos[$i]]."', '".date("Y-m-d")."', '".$campos[$titulos[$i+1]]."');";
	}
	else
	{//No hay Fecha fin Suscripción
        $requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdGrupoSuscrito`, `InicioSuscripcion`) VALUES ('".$seccion."', '4', '".$campos[$titulos[$i]]."', '".date("Y-m-d")."');";
	}
        break;
     case "usuarios":
	if ($campos[$titulos[$i+1]]!="")
        { //Tenemos Fecha fin Suscripción
        $requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdUsuarioSuscrito`, `InicioSuscripcion`, `FinSuscripcion`) VALUES ('".$seccion."', '4', '".$campos[$titulos[$i]]."', '".date("Y-m-d")."', '".$campos[$titulos[$i+1]]."');";
	}
	else
	{//No hay Fecha fin Suscripción
        $requete ="INSERT INTO `Permisos` (`IdSeccion`, `Nivel`, `IdUsuarioSuscrito`, `InicioSuscripcion`) VALUES ('".$seccion."', '4', '".$campos[$titulos[$i]]."', '".date("Y-m-d")."');";
	}
        break;
   }
   mysqli_query($db,$requete);
   $i++;
 }
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$seccion."&ruta=".$ruta);
?>