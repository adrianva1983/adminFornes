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

$campos=$_POST;
$titulos = array_keys($_POST);

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

for ($i=0;$i<count($campos);$i++)
{
	$tipodecampo = explode("/",$titulos[$i]);
	switch ($tipodecampo[0])
	{
		case "grupos":
		$requete = "SELECT * FROM `PertenenciaGrupos`,`Usuarios` WHERE `Usuarios`.Id = `PertenenciaGrupos`.IdUsuario AND `Usuarios`.AltaBoletin='si' AND `PertenenciaGrupos`.IdGrupo='".$tipodecampo[1]."'";
		$result2 = mysqli_query($db,$requete);
		if ($result2 = mysqli_query($db, $requete2))
		{
			while($listado2 = mysqli_fetch_object($result2))
			{
				$requete3 = "SELECT * FROM `BoletinUsuarios` WHERE `IdBoletin`='".$idboletin."' AND `IdUsuario`='".$listado2->IdUsuario."';";
				
				if ($result3 = mysqli_query($db, $requete3))
				{
				}
				else
				{
					$requete ="INSERT INTO `BoletinUsuarios` (`IdBoletin`, `IdUsuario`) VALUES ('".$idboletin."', '".$listado2->IdUsuario."');";
					mysqli_query($db,$requete);
				}
			}
		}
		break;
	}
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos el directorio en curso
header("Location:../Interface/herramienta.php?modulo=Boletin&herramienta=boletin&idboletin=".$idboletin);?>