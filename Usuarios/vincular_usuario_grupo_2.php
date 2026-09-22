<?php
if ($_POST["grupo"]!="") $grupo = $_POST["grupo"];
else $grupo = $_GET["grupo"];
if ($_POST['origen']!="") $origen = $_POST['origen'];
else $origen =$_GET["origen"];
if ($_POST['Num_Pagina']!='') $Num_Pagina = $_POST['Num_Pagina'];
else $Num_Pagina = $_GET["Num_Pagina"];
if ($_POST['CamposMostrar']!='') $CamposMostrar = $_POST['CamposMostrar'];
else $CamposMostrar = $_GET["CamposMostrar"];
if ($_POST['pagina']!='') $pagina = $_POST['pagina'];
else $pagina = $_GET["pagina"];
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
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
$usuarios = array();

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$tipodecampo = explode("/",$titulos[0]);
for ($i=0;$i<count($campos);$i++)
{
	$tipodecampo = explode("/",$titulos[$i]);
	if ($tipodecampo[0] == "usuarios")
	{
		$requete = "INSERT INTO `PertenenciaGrupos` (`IdUsuario`,`IdGrupo`) VALUES ('".$tipodecampo[1]."', '".$grupo."');";
		$usuarios[$i]=$tipodecampo[1];
		mysqli_query($db,$requete);
	}
}
for ($i=0;$i<count($campos);$i++)
{
	$tipodecampo = explode("/",$titulos[$i]);
	if ($tipodecampo[0] == "permisos")
	{
		for ($j=0;$j<count($usuarios);$j++)
		{
			$requete = "SELECT * FROM `Permisos` WHERE `IdUsuarioSuscrito`='".$usuarios[$j]."' AND `IdSeccion`='".$tipodecampo[1]."'";			
			
			if ($result = mysqli_query($db, $requete))
			{
				$listado = mysqli_fetch_object($result);
				if ($tipodecampo[2]<$listado->Nivel)
				{
					$requete= "UPDATE `Permisos` SET `Nivel` = '".$tipodecampo[2]."' WHERE `IdUsuarioSuscrito`='".$usuarios[$j]."' AND `IdSeccion`='".$listado->IdSeccion."'";					
					mysqli_query($db,$requete);
				}
			}
			else
			{
				$requete = "INSERT INTO `Permisos` (`IdSeccion`,`Nivel`,`IdUsuarioSuscrito`,`InicioSuscripcion`";
				if ($tipodecampo[3]!="") $requete.= ",`FinSuscripcion`";
				$requete.= ") VALUES ('".$tipodecampo[1]."', '".$tipodecampo[2]."', '".$usuarios[$j]."', '".date("Y-m-d H:i:s")."'";
				if ($tipodecampo[3]!="") $requete.= ",'".$tipodecampo[3]."'";
				$requete.= ");";				
				mysqli_query($db,$requete);
			}
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
//Recargamos
$CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
header("Location:/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina);
?>