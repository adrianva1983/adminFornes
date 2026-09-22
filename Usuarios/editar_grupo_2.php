<?php

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `Grupos` SET `Nombre`='".$Nombre."'";
if ($NivelAcceso!="") $requete.=",`NivelAcceso`='".$NivelAcceso."'";
$requete.= "WHERE `Id`='".$grupo."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
if ($grp!="") header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=grupos&grp=".$grp);
else header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=grupos");
?>