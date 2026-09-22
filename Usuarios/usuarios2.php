<?php
//VERSIÓN: v1.0 2014-03-18
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$origen = $_GET["origen"];
$Num_Pagina = $_GET["Num_Pagina"];
$CamposMostrar = $_GET["CamposMostrar"];
$usuario = $_GET["usuario"];
$pagina = $_GET["pagina"];
$accion = $_GET["accion"];

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
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/usuarios2-".$_SESSION['idioma'].".conf");

if ((isset($pagina)&&$pagina!="")||$accion==1) $CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Usuarios` ORDER BY `Apellidos`";
$result = mysqli_query($db,$requete);
$total = mysqli_num_rows($result);
$total_contenidos_pagina = $total;
$requete = "SELECT * FROM `Usuarios` ORDER BY `Apellidos`  LIMIT ".$intervalo_inicial.",".$Num_Pagina;

print "<p><strong>".$lang["paginacionDel"]." ".$intervalo_inicial." ".$lang["paginacionAl"]." ".($intervalo_inicial + $Num_Pagina)." ".$lang["paginacionTotal"]." ".$total."</strong></p>";
print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['titulo_usuarios'].'</h5>';
print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
print '<div class="ibox-content"><div class="row"><div>
							<table class="table table-striped">
								<thead>';
print "<th>".$lang["acciones"]."</th><th></th>";
for ($i=0;($i<count($CamposMostrar));$i++)
{
	switch ($CamposMostrar[$i]) 
	{
		case "Nombre":
			print "<th>".$lang["nombre"]."</th>";
			break;
		case "Apellidos":
			print "<th>".$lang["apellidos"]."</th>";
			break;
		case "Email":
			print "<th>".$lang["email"]."</th>";
			break;
		case "Provincia":
			print "<th>".$lang["provincia"]."</th>";
			break;
		case "Ciudad":
			print "<th>".$lang["ciudad"]."</th>";
			break;
		case "Telefono":
			print "<th>".$lang["telefono"]."</th>";
			break;
		case "Movil":
			print "<th>".$lang["movil"]."</th>";
			break;					
		case "NombreEmpresa":
			print "<th>".$lang["empresa"]."</th>";
			break;
	}
}
print "</tr>";
print '</thead><tbody>';
if ($result = mysqli_query($db, $requete))
{	
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";		
		print "<td>";
		print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
		print '<ul class="dropdown-menu">';
		//Si el usuario que consulta tiene menor nivel que el que se lista, no puede ver su ficha ni sus contenidos ni editarlo
		if ($listado->NivelAcceso<$_SESSION['usuario_nivel'])
		{   		
			print "<li><a href=\"mailto:".$listado->Email."\"><i class='fa fa-envelope'></i> ".$lang["mandar"]."</a></li>"; 
		}
 		else
		{
			if ($listado->Activado=='si')
			{     		
				print "<li><a href=\"/administra/Usuarios/funciones/activacion.php?pasada=no&Id=".$listado->Id."&herramienta=usuarios2&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><i class='fa fa-pause'></i> ".$lang["desactivar"]."</a></li>";
			}
			else
			{
				print "<li><a href=\"/administra/Usuarios/funciones/activacion.php?pasada=si&Id=".$listado->Id."&herramienta=usuarios2&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><i class='fa fa-play'></i> ".$lang["activar"]."</a></li>";
			}
			print "<li><a href=\"mailto:".$listado->Email."\"><i class='fa fa-envelope'></i> ".$lang["mandar"]."</a></li>";
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&amp;usuario=".$listado->Id."\"><i class='fa fa-book'></i> ".$lang["ficha"]."</a></li>";			
			//Número de contenidos generados por él
			$requete2 = "SELECT * FROM `Contenidos` WHERE `IdPropietario`='".$listado->Id."'";
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=contenidos_usuario&usuario=".$listado->Id."\"><i class='fa fa-file'></i> ".$lang["contenido"]."(".mysqli_num_rows($result2).")</a></li>";
			}     	
			print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=editar_usuario&usuario=".$listado->Id."&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><i class='fa fa-edit'></i> ".$lang["editar"]."</a></li>";
			if ($_SESSION['usuario_nivel']<2) print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=borrar&amp;usuario=".$listado->Id."&amp;referencia=usuarios2&amp;origen=usuarios&amp;Num_Pagina=".$Num_Pagina."&amp;CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><i class='fa fa-remove'></i> ".$lang["borrar"]."</a></li>";
		}
		print "</td>";
		print "<td>";
		if ($listado->Foto!='') print '<img alt="image" class="img-circle" style="width:28px;" src="/Imagenes/Perfiles/'.$listado->Foto.'">';
		print "</td>";
		for ($i=0;($i<count($CamposMostrar));$i++)
		{
			switch ($CamposMostrar[$i]) 
			{
				case "Nombre":
					print "<td>".$listado->Nombre."</td>";
					break;
				case "Apellidos":
					print "<td>".$listado->Apellidos."</td>";
					break;
				case "Email":
					print "<td>".$listado->Email."</td>";
					break;
				case "Provincia":
					print "<td>".$listado->Provincia."</td>";
					break;
				case "Ciudad":
					print "<td>".$listado->Ciudad."</td>";
					break;
				case "Telefono":
					print "<td>".$listado->Telefono."</td>";
					break;
				case "Movil":
					print "<td>".$listado->Movil."</td>";
					break;					
				case "NombreEmpresa":
					print "<td>".$listado->NombreEmpresa."</td>";
					break;
			}
		}		
		print "</tr>";
	}
}
print "</tbody><tfoot><tr><td colspan=\"9\"><strong>".$lang["numeroResultados"].":</strong> ".$total_contenidos_pagina."</td></tr></tfoot></table></div>";


//Imprimimos la paginación
$max =intval($total/$Num_Pagina);


//Paginación
if (isset($Num_Pagina))
{
	//PAGINACIÓN		
	print '<ul class="pagination">';
	$max = intval($total_contenidos_pagina/$Num_Pagina);
	if ($total_contenidos_pagina%$Num_Pagina==0) $max--;
	for ($i=0;($i<($max+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else 
		{
			switch ($origen) 
			{
				case "usuarios":
					print "<li class=\"paginate_button\"><a href=\"".$_SERVER['SCRIPT_NAME']."?modulo=Usuarios&herramienta=usuarios2&origen=".$origen."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$i."\">".$i."</a></li>";
					break;
			}
		}
	}	
	print "</ul>";
}	
print '</div></div></div>';
?>