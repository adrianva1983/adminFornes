<?php
//CONEXION A BASE DE DATOS
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php"); 
$requete = "SELECT `IdSeccion`,`NomFich` FROM `Publicaciones`, `Contenidos` WHERE (`Visibilidad`='Visible' OR `Visibilidad`='Pendiente') AND `Contenidos`.Id = `Publicaciones`.IdContenido AND `IdAmpliacion` IS NULL AND `Contenidos`.Tipo = 'contenido' AND `Idioma`='ES-ES' ORDER BY `Publicaciones`.FechaComienzo";

print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
print "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		$requete2 = "SELECT `Path`,`NomFich` FROM `Secciones` WHERE `Id` = '".$listado->IdSeccion."'";
		
		$listado2 = mysqli_fetch_object($result2);
		if ($listado->NomFich!="")
		{
			print "<url>\n";
			if ($listado2->Path!="") print "<loc>/Secciones/".$listado2->Path."/".$listado2->NomFich."/".$listado->NomFich.".php</loc>\n";
			else print "<loc>/Secciones/".$listado2->NomFich."/".$listado->NomFich.".php</loc>\n";
			print "</url>\n";
		}
	}
}
print "</urlset>\n";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>