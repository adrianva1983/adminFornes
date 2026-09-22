<?php

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "INSERT INTO `Grupos` (`Nombre`,`Activado`";
if ($NivelAcceso!="") $requete.=", `NivelAcceso`";
if ($grp!="") $requete.=", `IdPadre`";
$requete.=") VALUES ('".$Nombre."','si'";
if ($NivelAcceso!="") $requete.=", '".$NivelAcceso."'";
if ($grp!="") $requete.=", '".$grp."'";
$requete.=");";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
header("Location:../Interface/herramienta.php?modulo=Usuarios&herramienta=grupos");
?>