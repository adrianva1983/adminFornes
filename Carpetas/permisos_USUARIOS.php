<?php
//VERSIÓN: v1.0 2014-01-03
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$ruta = $_GET["ruta"];
$seccion = $_GET["seccion"];
$Nombre = $_GET["Nombre"];
$Apellidos = $_GET["Apellidos"];
$Email = $_GET["Email"];
$Ciudad = $_GET["Ciudad"];
$Municipio = $_GET["Municipio"];
$Provincia = $_GET["Provincia"];
$Pais = $_GET["Pais"];
$NombreEmpresa = $_GET["NombreEmpresa"];
$Enviar = $_GET["Enviar"];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/permisos_USUARIOS-".$_SESSION['idioma'].".conf");
print "<form action=\"/administra/Interface/herramienta.php\" enctype=\"multipart/form-data\" method=\"GET\">";
print "<input type=\"hidden\" name=\"modulo\" value=\"Carpetas\">";
print "<input type=\"hidden\" name=\"herramienta\" value=\"permisos_USUARIOS\">";
print "<input type=\"hidden\" name=\"ruta\" value=\"".$ruta."\">";
print "<input type=\"hidden\" name=\"seccion\" value=\"".$seccion."\">";
echo "<table>";
echo "<tr><td>".$lang["nombre"]." </td><td><input name=Nombre value=\"$Nombre\"></td></tr>";
echo "<tr><td>".$lang["apellidos"]." </td><td><input name=Apellidos value=\"$Apellidos\"></td></tr>";
echo "<tr><td>".$lang["email"]." </td><td><input name=Email value=\"$Email\"></td></tr>";
echo "<tr><td>".$lang["ciudad"]." </td><td><input name=Ciudad value=\"$Ciudad\"></td></tr>";
echo "<tr><td>".$lang["municipio"]." </td><td><input name=Municipio value=\"$Municipio\"></td></tr>";	
echo "<tr><td>".$lang["provincia"]." </td><td><input name=Provincia value=\"$Provincia\"></td></tr>";
echo "<tr><td>".$lang["pais"]." </td><td><input name=Pais value=\"$Pais\"></td></tr>";
echo "<tr><td>".$lang["empresa"]." </td><td><input name=NombreEmpresa value=\"$NombreEmpresa\"></td></tr>";	
echo "</table>";
print "<input class=\"boton\" name=\"Enviar\" type=\"submit\" value=\"".$lang["buscar"]."\">";
print "</form>";
if ($Enviar!="")
{
	print "<form name=\"ubusca\" id=\"ubusca\" action=\"/administra/Carpetas/permisos_2.php\" enctype=\"multipart/form-data\" method=\"GET\">";	
	print "<input type=\"hidden\" name=\"ruta\" value=\"".$ruta."\">";
	print "<input type=\"hidden\" name=\"seccion\" value=\"".$seccion."\">";
	$item="Usuarios";
	$requete = "SELECT `Id`, `Nombre`, `Apellidos`, `Foto`, `Email` FROM `".$item."` WHERE `NivelAcceso`>='".$_SESSION['usuario_nivel']."'";
	if ($Nombre!="") $requete.= " AND `Nombre` like '%".$Nombre."%'";
	if ($Apellidos!="") $requete.= " AND `Apellidos` like '%".$Apellidos."%'";
	if ($Email!="") $requete.= " AND `Email` like '%".$Email."%'";
	if ($Ciudad!="") $requete.= " AND `Ciudad` like '%".$Ciudad."%'";
	if ($Provincia!="") $requete.= " AND `Provincia` like '%".$Provincia."%'";
	if ($Pais!="") $requete.= " AND `Pais` like '%".$Pais."%'";
	if ($NombreEmpresa!="") $requete.= " AND `NombreEmpresa` like '%".$NombreEmpresa."%'";
	$result = mysql_query($requete);	
	$numeroResultados = mysqli_num_rows($result);
	if($numeroResultados==0)
	{
		print "<p class=\"mensajeKO\">".$lang["sinResultados"]."</p>";
		$requete2 = "SELECT * FROM `Permisos`,`Usuarios` WHERE `Permisos`.IdUsuarioSuscrito IS NOT NULL AND `Permisos`.IdSeccion= '".$seccion."' AND `Permisos`.IdUsuarioSuscrito = `Usuarios`.Id";
		
		if ($result2 = mysqli_query($db, $requete2))
		//Si tiene permisos en el apartado le informamos de sus permisos y permitimos cambiarlos
		{
			print "<ul>";
			while($listado2 = mysqli_fetch_object($result2))
			{
				$requete3 = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
				
				print "<li>";
				print "<select class=\"suscripcion\" name=\"CambioNivelUsuario/".$listado2->IdUsuarioSuscrito."\">";
				print "<option value=\"".$lang["quitarPermisos"]."\">".$lang["quitarPermisos"]."</option>";	
				while($listado3 = mysqli_fetch_object($result3))
				{
					if ($listado3->Nivel>$_SESSION['usuario_nivel'])
					{
						if ($listado3->Nivel == $listado2->Nivel) print "<option selected value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
						else print "<option value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
					}
				}
				print "</select>";
				print "<img src=\"/administra/Imagenes/usuario.png\">";
				print $listado2->Nombre."</li>";
			}
			print "</ul>";
		}
		print "<input class=\"boton\" type=\"submit\" value=\"".$lang["aceptar"]."\">";
		print "</form>";
		mysql_free_result($result);
	}
	else
	{
		$requete2 = "SELECT * FROM `Permisos`,`Usuarios` WHERE `Permisos`.IdUsuarioSuscrito IS NOT NULL AND `Permisos`.IdSeccion= '".$seccion."' AND `Permisos`.IdUsuarioSuscrito = `Usuarios`.Id";
		
		if ($result2 = mysqli_query($db, $requete2))
		//Si tiene permisos en el apartado le informamos de sus permisos y permitimos cambiarlos
		{
			print "<ul>";
			while($listado2 = mysqli_fetch_object($result2))
			{
				$requete3 = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
				
				print "<li>";
				print "<select class=\"suscripcion\" name=\"CambioNivelUsuario/".$listado2->IdUsuarioSuscrito."\">";
				print "<option value=\"".$lang["quitarPermisos"]."\">".$lang["quitarPermisos"]."</option>";	
				while($listado3 = mysqli_fetch_object($result3))
				{
					if ($listado3->Nivel>$_SESSION['usuario_nivel'])
					{
						if ($listado3->Nivel == $listado2->Nivel) print "<option selected value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
						else print "<option value=\"".$listado3->Nivel."\">".$listado3->Nombre."</option>";
					}
				}
				print "</select>";
				print "<img src=\"/administra/Imagenes/usuario.png\">";
				print $listado2->Nombre."</li>";
			}
			print "</ul>";
		}
		print "<script>";
		print "function seleccionar_elem(){\n";
		print "elementos=document.ubusca.length\n";
		print "for(i=0;i<elementos;i++)\n";
		print "document.ubusca.elements[i].checked=true\n";
		print "}\n";
		print "function desactivar_elem()\n";
		print "{\n";
		print "elementos=document.ubusca.length\n";
		print "for(i=0;i<elementos;i++)\n";
		print "document.ubusca.elements[i].checked=false\n";
		print "}\n";
		print "</script>";
		print "<strong>".$lang["numeroEncontrados"].":</strong> ".$numeroResultados."<br/>";
		print "<p><a class=\"boton_linea\" href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a> ";
		print "<a class=\"boton_linea\" href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a></p>";
		print "<ul>";
		while ($listado = mysqli_fetch_object($result)) 
		{
			$requete2 = "SELECT * FROM `Permisos` WHERE `IdusuarioSuscrito`='".$listado->Id."' AND `IdSeccion`= '".$seccion."'";
			
			if ($result2 = mysqli_query($db, $requete2))			
			{
				//Si tiene permisos en el apartado no lo pintamos aquí porque se gestiona fuera
			}
			else
			{
				print "<li><input class=\"suscripcion\" name=\"usuarios[]\" type=\"checkbox\" value=\"".$listado->Id."\"><img src=\"/administra/Imagenes/usuario.png\"><strong>".$listado->Nombre.", ".$listado->Apellidos.". ".$listado->Email."</strong><br/></li>";
			}								
		}
		mysql_free_result($result);
		print  "</ul>";
		print "<p><a class=\"boton_linea\" href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a> - ";
		print "<a class=\"boton_linea\" href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a></p>";
		$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
		
		print "<select name=\"NivelAcceso\">";
		while($listado = mysqli_fetch_object($result))
		{
			if ($listado->Nivel>$_SESSION['usuario_nivel'])
			{
				print "<option value=\"".$listado->Nivel."\">".$listado->Nombre."</option>";		    
			}
		}
		print "</select>";	
		print "<input class=\"boton\" type=\"submit\" value=\"".$lang["aceptar"]."\">";
		print "</form>";
	} 
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>