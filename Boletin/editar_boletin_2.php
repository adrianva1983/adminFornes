<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "UPDATE `BoletinConfiguracion` SET `PlantillaCabecera`='".$PlantillaCabecera."', `PlantillaCuerpo`='".$PlantillaCuerpo."', `PlantillaPie`='".$PlantillaPie."', `Asunto`='".$Titulo."', `Remitente`='".$NombreRemitente."', `RemitenteMail`='".$EmailRemitente."', `MailPruebas`='".$MailPruebas."', `Zonas`='".$Zonas."' WHERE `Id`='".$idboletin."';";
mysqli_query($db,$requete);
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");

//Recargamos el contenido en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletines");
?>