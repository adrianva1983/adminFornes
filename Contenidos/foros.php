<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Contenidos/idiomas/foros-".$_SESSION['idioma'].".conf");

print "<h1>".$lang["listado"]."</h1>";

//Miramos el número de Contenidos por Página configurado
$requete = "SELECT * FROM `Servidor` WHERE `Campo` LIKE '%Secciones - Contenidos por Página%'";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	$num_contenidos_pagina = $listado->Valor;
	if (!isset($pagina)) $pagina = 0;
	$requete = "SELECT `ForoConfiguracion`.Id,`ForoConfiguracion`.SumaVotos,`ForoConfiguracion`.NumeroVotos,`Contenidos`.IdPadre FROM `ForoConfiguracion`,`Contenidos` WHERE `ForoConfiguracion`.Id = `Contenidos`.Id";
	
	$total_contenidos_pagina = mysqli_num_rows($result);		
}
 if (isset($num_contenidos_pagina))
 {
   //PAGINACIÓN
	 print "<p><strong>".$lang["numero"].":</strong> ".$total_contenidos_pagina."</p>";
	 print "<p>";
	 $max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
	 for ($i=0;($i<($max_pagina + 1));$i++)
	 {
		 if ($pagina == $i) print "<strong>".$i."</strong> - ";		 
		 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=foros&pagina=".$i."\">".$i."</a> - ";
	 }
	 print "</p>";
 }
print "<hr>";
// Hacemos una consulta para sacar los foros en el sistema
$requete = "SELECT `ForoConfiguracion`.Id,`ForoConfiguracion`.SumaVotos,`ForoConfiguracion`.NumeroVotos,`Contenidos`.IdPadre FROM `ForoConfiguracion`,`Contenidos` WHERE `ForoConfiguracion`.Id = `Contenidos`.Id ORDER BY `Id` LIMIT ".($pagina * $num_contenidos_pagina).",".$num_contenidos_pagina;

if ($result = mysqli_query($db, $requete))
{
 	print "<hr><ul>";
	while($listado = mysqli_fetch_object($result))
	{
		print "<li>";
		$requete2 = "SELECT `Titulo` FROM `Contenidos` WHERE `Id`='".$listado->IdPadre."'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{
			$listado2 = mysqli_fetch_object($result2);
			print $listado2->Titulo." - ";
		}
		$requete2 = "SELECT `Id` FROM `ForoMensajes` WHERE `IdForo` = '".$listado->Id."'";
		
		if ($result2 = mysqli_query($db, $requete2))
		{			
			print "<a href=\"herramienta.php?modulo=Carpetas&herramienta=foro_editar_mensajes&pagina=".$pagina."&IdForo=".$listado->Id."\">".mysqli_num_rows($result2)." ".$lang["mensajes"]." <img src=\"/administra/Imagenes/editar_mensajes_foro.png\"/ title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a>";
		}
		print "</li>";
	}
	print "</ul>";
}
if (isset($num_contenidos_pagina))
{
  //PAGINACIÓN
 print "<p><strong>".$lang["numero"].":</strong> ".$total_contenidos_pagina."</p>";
 print "<p>";
 $max_pagina = intval($total_contenidos_pagina/$num_contenidos_pagina);
 for ($i=0;($i<($max_pagina + 1));$i++)
 {
	 if ($pagina == $i) print "<strong>".$i."</strong> - ";
	 else print "<a href=\"/administra/Interface/herramienta.php?modulo=Contenidos&herramienta=foros&pagina=".$i."\">".$i."</a> - ";
 }
 print "</p>";
}
if ($result) 
{
 mysql_free_result($result);
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
