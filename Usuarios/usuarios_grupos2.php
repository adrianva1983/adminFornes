<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$origen = $_GET["origen"];
$grupo = $_GET["grupo"];
$CamposMostrar = $_GET["CamposMostrar"];
$Num_Pagina = $_GET["Num_Pagina"];


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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/usuarios_grupos2-".$_SESSION['idioma'].".conf");

if (isset($pagina)&&$pagina>0) $CamposMostrar = unserialize(urldecode(stripslashes($CamposMostrar)));
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `Usuarios`,`PertenenciaGrupos` WHERE `Usuarios`.Id=`PertenenciaGrupos`.IdUsuario AND `PertenenciaGrupos`.IdGrupo ='".$grupo."' ORDER BY `Apellidos`";

$total = mysqli_num_rows($result);
$requete = "SELECT * FROM `Usuarios`,`PertenenciaGrupos` WHERE `Usuarios`.Id=`PertenenciaGrupos`.IdUsuario AND `PertenenciaGrupos`.IdGrupo ='".$grupo."' ORDER BY `Apellidos`  LIMIT ".$intervalo_inicial.",".$Num_Pagina;

print "<img src=\"/administra/Imagenes/vcard.png\" title=\"".$lang["ficha"]."\" alt=\"".$lang["ficha"]."\"> :: ".$lang["ficha"]."<br/>";
print "<img src=\"/administra/Imagenes/email.png\" title=\"".$lang["mandar"]."\" alt=\"".$lang["mandar"]."\"> :: ".$lang["mandar"]."<br/>";
print "<img src=\"/administra/Imagenes/user_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "<img src=\"/administra/Imagenes/borrar.png\" alt=\"".$lang["desvincular"]."\"> :: ".$lang["desvincular"]."<br/>";
print "<p><strong>".$lang["paginacionDel"]." ".$intervalo_inicial." ".$lang["paginacionAl"]." ".($intervalo_inicial + $Num_Pagina)." ".$lang["paginacionTotal"]." ".$total."</strong></p>";
print "<table>";
print "<tr>";
print "<th>".$lang["acciones"]."</th>";
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
if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<tr>";
		print "<td>";
		//Si el usuario que consulta tiene menor nivel que el que se lista, no puede ver su ficha ni sus contenidos ni editarlo
		if ($listado->NivelAcceso<$_SESSION['usuario_nivel'])
   	{   		
   		print "<a href=\"mailto:".$listado->Email."\"><img src=\"/administra/Imagenes/email.png\" title=\"".$lang["mandar"]."\" alt=\"".$lang["mandar"]."\"></a>";
   	}
 		else
   	{
   		if ($listado->Activado=='si')
     	{     		
      	print "<a href=\"/administra/Usuarios/funciones/activacion.php?pasada=no&Id=".$listado->IdUsuario."&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/pausa.png\" alt=\"".$lang["desactivar"]."\" title=\"".$lang["desactivar"]."\"></a>";
     	}
     	else
     	{
     		print "<a href=\"/administra/Usuarios/funciones/activacion.php?pasada=si&Id=".$listado->IdUsuario."&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/play.png\" alt=\"".$lang["activar"]."\" title=\"".$lang["activar"]."\"></a>";
     	}   		
   		print "<a href=\"/administra/Usuarios/funciones/desvincula_usuario_grupo.php?pasada=si&Id=".$listado->IdUsuario."&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/borrar.png\" title=\"".$lang["desvincular"]."\" alt=\"".$lang["desvincular"]."\"></a>";
   		print "<a href=\"mailto:".$listado->Email."\"><img src=\"/administra/Imagenes/email.png\" alt=\"".$lang["mandar"]."\" title=\"".$lang["mandar"]."\"></a>";
   		print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$listado->IdUsuario."&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/vcard.png\" title=\"".$lang["ficha"]."\" alt=\"".$lang["ficha"]."\"></a>";
   		//Número de contenidos generados por él
   		$requete2 = "SELECT * FROM `Contenidos` WHERE `IdPropietario`='".$listado->IdUsuario."'";
   		
   		if ($result2 = mysqli_query($db, $requete2))
     	{
     		print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=contenidos_usuario&usuario=".$listado->IdUsuario."&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/contenido.png\" title=\"".$lang["contenido"]."\" alt=\"".$lang["contenido"]."\">(".mysqli_num_rows($result2).")</a>";
     	}
   		print "<a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=editar_usuario&usuario=".$listado->IdUsuario."&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$pagina."\"><img src=\"/administra/Imagenes/user_edit.png\" alt=\"".$lang["editar"]."\" title=\"".$lang["editar"]."\"></a>";
   	}
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

print "</table>";

//Imprimimos la paginación
$max =intval($total/$Num_Pagina);

print "".$lang["paginas"].": ";
for ($i=0;($i<$max+1);$i++)
{
	if ($i!=$pagina)
	{
		print "<a href=\"".$_SERVER['SCRIPT_NAME']."?modulo=Usuarios&herramienta=usuarios_grupos2&origen=".$origen."&grupo=".$grupo."&Num_Pagina=".$Num_Pagina."&CamposMostrar=".urlencode(serialize($CamposMostrar))."&pagina=".$i."\">".$i."</a> - ";
	}
	else print "<strong>".$i."</strong> - ";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>