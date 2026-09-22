<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
require($_SERVER['DOCUMENT_ROOT']."/herramientas/verifica_usuarios.php");
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print "<title>Semilla Proyectos Internet - M&oacute;vil</title>";
?>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<?php
print "<meta content=\"text/html; charset=iso-8859-1\" http-equiv=\"Content-Type\">";
 print "<link rel=\"stylesheet\" media=\"screen,handheld\" type=\"text/css\" href=\"css_proyectos.css\" />";
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34061918-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body id="index">
	<?php
print "<h1 id=\"pageTitle\"><img alt=\"Semilla Proyectos Internet\" src=\"/Mail/logo.jpg\"></h1>";
if (isset($_SESSION['usuario_login']) && isset($_SESSION['usuario_password']))
{//HA HECHO LOGIN
	if ($idcliente!="")
	{
		$requete = "SELECT * FROM Proyectos WHERE IdCliente=".$idcliente." AND Activo=1";
		
		if ($result = mysqli_query($db, $requete))
		{
			print "<h2>Proyectos del cliente</h2>";
			print "<ol>";
			while ($listado = mysqli_fetch_object($result))
			{
				print "<li><a href=\"?idproyecto=".$listado->Id."\">".$listado->Nombre;
				$requete2 = "SELECT * FROM Tareas WHERE IdProyecto=".$listado->Id." AND `Publica`='si'";
																				
				$total_tareas_cerradas = mysqli_num_rows($result2);
				print " (".mysqli_num_rows($result2).")<br/>";
				$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NOT NULL AND `Publica`='si'";
				
				$total_tareas_cerradas = mysqli_num_rows($result2);	
				$requete2 = "SELECT * FROM `Tareas` WHERE `IdProyecto`=".$listado->Id." AND `FechaCierre` IS NULL AND `Publica`='si'";
				
				$total_tareas_abiertas = mysqli_num_rows($result2);		
				if (($total_tareas_cerradas + $total_tareas_abiertas)>0) $porcentajeEjecucion = ($total_tareas_cerradas*100) / ($total_tareas_cerradas + $total_tareas_abiertas);
				else $porcentajeEjecucion = 0;
				if (($porcentajeEjecucion) >= 10) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 20) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 30) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 40) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 50) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 60) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 70) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 80) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 90) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				print " (".$porcentajeEjecucion."%)</a>";
				print "</li>";
			}
			print "</ol>";
			print "<p>Nota: (n&uacute;mero hitos)</p>";
		}		
	}
	if ($idproyecto!="")
	{
		$requete = "SELECT * FROM Tareas WHERE IdProyecto=".$idproyecto." AND Publica='si'";
		
		if ($result = mysqli_query($db, $requete))
		{
			print "<h2>Hitos del proyecto</h2>";
			print "<ol>";
			while ($listado = mysqli_fetch_object($result))
			{			
				print "<li><a href=\"?idhito=".$listado->Id."\">".$listado->Nombre."<br/>";
				$porcentajeEjecucion = $listado->PorcentajeEjecucion;
				if (($porcentajeEjecucion) >= 10) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 20) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 30) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 40) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 50) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 60) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 70) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 80) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				if (($porcentajeEjecucion) >= 90) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
				else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
				print " (".$listado->PorcentajeEjecucion."%)</a>";
				print "</li>";
			}
			print "</ol>";
			print "<p>Nota: (porcentaje ejecuci&oacute;n)</p>";
		}		

	}
	if ($idhito!="")
	{
		$requete = "SELECT * FROM Tareas WHERE Id=".$idhito;
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado = mysqli_fetch_object($result);
			print "<h2>".$listado->Nombre."</h2>";
			$porcentajeEjecucion = $listado->PorcentajeEjecucion;
			if (($porcentajeEjecucion) >= 10) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 20) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 30) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 40) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 50) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 60) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 70) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 80) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			if (($porcentajeEjecucion) >= 90) print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#000000;margin:0px 1px;\">&nbsp;</span>";
			else print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			print "<span style=\"padding:0px;display:inline;width:2px;height:4px;background:#CCCCCC;margin:0px 1px;\">&nbsp;</span>";
			print " (".$listado->PorcentajeEjecucion."%)<br/>";
			if ($listado->IdResponsable!="")
			{
				$requete = "SELECT * FROM Usuarios WHERE Id=".$listado->IdResponsable;
				
				if ($result = mysqli_query($db, $requete))
				{
					$listado = mysqli_fetch_object($result);
					print "<p><strong>Responsable:</strong>".$listado->Nombre." ".$listado->Apellidos."</p>";
				}
			}
		}
	}
	$requete = "SELECT * FROM Usuarios WHERE Id=".$_SESSION['usuario_id'];
	
	if ($result = mysqli_query($db, $requete))
	{ 
		$listado = mysqli_fetch_object($result);
		if ($listado->FamiliaPresupuestos==1) //Es un colaborador
		{
			$requete = "SELECT * FROM Clientes WHERE IdFamilia=".$_SESSION['usuario_id'];
			
			if ($result = mysqli_query($db, $requete))
			{
				print "<h2>Clientes</h2>";
				print "<ol>";
				while ($listado = mysqli_fetch_object($result))
				{
					print "<li><a href=\"?idcliente=".$listado->Id."\">".$listado->DenominacionSocial;
					$requete2 = "SELECT * FROM Proyectos WHERE IdCliente=".$listado->Id." AND Activo=1";
					
					print " (".mysqli_num_rows($result2).")</a>";
					print "</li>";
				}
				print "</ol>";
				print "<p>Nota: (n&uacute;mero proyectos abiertos)</p>";
			}
		}
	}	
}
else
{
	print "<form style=\"padding:10%;\" action=\"".$url_login."\" method=\"post\">";
	print "<label for=\"user\">Email:</label><br/>";
	print "<input style=\"width:80%;\" type=\"text\" value=\"\" maxlength=\"75\" size=\"12\" name=\"user\"/><br/>";
	print "<label for=\"pass\">Password:</label><br/>";
	print "<input style=\"width:80%;\" type=\"password\" value=\"\" maxlength=\"75\" size=\"12\" name=\"pass\"/><br/>";
	print "<input type=\"submit\" value=\"Entrar\"/>";	
	print "</form>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>	
</body>
</html>