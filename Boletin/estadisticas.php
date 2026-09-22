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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Boletin/idiomas/estadisticas-".$_SESSION['idioma'].".conf");
print "<h1>".$lang["estadisticas"]."</h1>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$requete = "SELECT * FROM `BoletinConfiguracion` ORDER BY `FechaEnvio`";

$NumeroEnviadosTotal = 0;
$NumeroVistosTotal = 0;
$NumeroClicksTotal = 0;

if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr>";	
	print "<th>".$lang["nombre"]."</th><th>".$lang["fechaEnvio"]."</th><th>".$lang["enviados"]."</th><th>".$lang["vistas"]."</th><th>".$lang["clicks"]."</th></tr>";
	$paridad = true;
	while($listado = mysqli_fetch_object($result))
	{		
		if (($_SESSION['usuario_nivel']<2)||(($_SESSION['usuario_nivel']==2)&&($listado->IdPropietario==$_SESSION['usuario_id'])))
		{
			if ($paridad) $paridad = false;
			else $paridad = true;
			if ($paridad) print "<tr id=\"par\">";
			else print "<tr>";
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$listado->Id."\">";
			print $listado->Asunto."</a></td>";			
			print "<td>".$listado->FechaEnvio."</td>";
			$requete2="SELECT sum(Clicks) AS suma FROM `BoletinUsuarios` WHERE `IdBoletin`='".$listado->Id."'";
			
			$listado2 = mysqli_fetch_object($result2);
			$clicks = $listado2->suma;
			$NumeroClicksTotal = $NumeroClicksTotal + $clicks;
			$requete2="SELECT sum(Vistas) AS suma FROM `BoletinUsuarios` WHERE `IdBoletin`='".$listado->Id."'";
			
			$listado2 = mysqli_fetch_object($result2);
			$vistas = $listado2->suma;
			$NumeroVistosTotal = $NumeroVistosTotal + $vistas; 
			if (($listado->NumeroEnviados==0)&&(($vistas!=0)||$clicks!=0)) 
			{
				$requete2="SELECT count(*) AS suma FROM `BoletinUsuarios` WHERE `IdBoletin`='".$listado->Id."' AND `Estado`<>'Pendiente'";
				
				$listado2 = mysqli_fetch_object($result2);
				print "<td>".$listado2->suma."</td>";
			}
			else print "<td>".$listado->NumeroEnviados."</td>";
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$listado->Id."&orden_listado=visualizaciones\">";
			print $vistas."</td>";
			print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=boletin_estadisticas&idboletin=".$listado->Id."&orden_listado=clicks\">";
			print $clicks."</td>";
			$NumeroEnviadosTotal=$NumeroEnviadosTotal+$listado->NumeroEnviados;
			print "</tr>";
		}		
	}
}
print "</table>";

print "<strong>".$lang["enviadosTotal"].":</strong> ".$NumeroEnviadosTotal."<br>";
print "<strong>".$lang["vistasTotal"].":</strong> ".$NumeroVistosTotal."<br>";
print "<strong>".$lang["clicksTotal"].":</strong> ".$NumeroClicksTotal."<br>";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>