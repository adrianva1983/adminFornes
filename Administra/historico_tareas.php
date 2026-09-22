<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=1; // Nivel de acceso para esta página.
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
//Accedemos a la base de datos
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Administra/idiomas/historico_tareas-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Tareas` WHERE `FechaCierre` IS NOT NULL ORDER BY `FechaCierre` LIMIT ".$intervalo_inicial.",".$Num_Pagina;

if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>Id</th><th>".$lang["nombre"]."</th><th>".$lang["fecha"]."</th><th>".$lang["creador"]."</th><th>".$lang["asignado"]."</th><th>".$lang["tipo"]."</th><th>".$lang["horas"]."</th></tr>";
	$par = false;
	while ($listado = mysqli_fetch_object($result))
	{
		if ($par) 
		{			
			print "<tr id=\"par\">";
			$par = false;
		}
		else
		{
			print "<tr>";
			$par = true;
		}
		print "<td>".$listado->Id."</td>";
		print "<td>";		
		print htmlentities($listado->Nombre);		
		print "</td>";
		print "<td>".$listado->Fecha." : ".$listado->FechaCierre."</td>";
		print "<td>".$listado->IdUsuarioCreador."</td>";
		print "<td>".$listado->IdUsuarioAsignado."</td>";
		print "<td>".$listado->Tipo."</td>";
		print "<td>";
		printf ("%.2f", (($listado->TiempoDedicado)/60)/60);
		print "</td>";
		print "</tr>";
	}
	print "</table>";
}
//Imprimimos la paginación
$max =intval($total/$Num_Pagina);
print $lang["paginas"].": ";
for ($i=0;($i<$max+1);$i++)
{
	if ($i!=$pagina)
	{			
		print "<a href=\"".$_SERVER['SCRIPT_NAME']."?modulo=Comercio&herramienta=reservas&Num_Pagina=".$Num_Pagina."&pagina=".$i."\">".$i."</a> - ";
	}
	else print "<strong>".$i."</strong> - ";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
