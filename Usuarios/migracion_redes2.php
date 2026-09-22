<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
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
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/migracion_redes2-".$_SESSION['idioma'].".conf");
if ($submitImport=="")
{
	include($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/contactos_externos.php");	
	$contactos = contactos_externos($tipo,$Usuario,$Contrasena);
	if ($contactos!=NULL)
	{
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
		print "<div id=\"instrucciones\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/usuario.png\" title=\"".$lang["yaExiste"]."\" alt=\"".$lang["yaExiste"]."\"> :: ".$lang["yaExiste"]."<br/></div>";
		print "<p>";
		print "<a class=\"boton_linea\" href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a> - ";
		print "<a class=\"boton_linea\" href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a><br/>";
		print "</p>";
		print "<form name=\"ubusca\" id=\"ubusca\" action=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=migracion_redes2\" enctype=\"multipart/form-data\" method=\"POST\">";
		print "<input class=\"boton\" type=\"submit\" name=\"submitImport\" id=\"submitImport\" value=\"".$lang["anadirContactos"]."\">";
		print "<ul>";
		foreach ($contactos as $email => $name) 
		{
			print "<li>";
			$requete_mensajeria = "SELECT * FROM `Usuarios` WHERE Email = '".$email."' AND Email<>''";
			$result_mensajeria = mysql_query($requete_mensajeria,$db);
			if (($result_mensajeria) && (mysqli_num_rows($result_mensajeria)>0)) 
			{
				$listado_mensajeria = mysql_fetch_object($result_mensajeria);
				print "<input type=\"checkbox\" style=\"display:inline;\" name=\"contacto[]\" value=\"".$email.";".$name."\" checked> <img src=\"http://".$_SERVER["SERVER_NAME"]."/administra/Imagenes/usuario.png\" title=\"".$lang["yaExiste"]."\" alt=\"".$lang["yaExiste"]."\"> ";
				if ($listado_mensajeria->Nombre!="") print $listado_mensajeria->Nombre;
				else print $email;
			}
			else print "<input type=\"checkbox\" style=\"display:inline;\" name=\"contacto[]\" value=\"".$email.";".$name."\" checked> ".$name;
			print "</li>";
		}
		print "</ul>";
		$requete = "SELECT * FROM `Grupos` ORDER BY `Orden`";		
		
		print "<ul><li><label for=\"Grupo\">".$lang["enGrupo"].":</label><br/><select name=\"Grupo\">";
		print "<option value=\"\" SELECTED></option>";
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";		
		}
		print "</select></li></ul>";
		print "<input class=\"boton\" type=\"submit\" name=\"submitImport\" id=\"submitImport\" value=\"".$lang["anadirContactos"]."\">";
		print "</form>";
		print "<br/><a class=\"boton_linea\" href=\"javascript:desactivar_elem()\">".$lang["desactivar"]."</a> - ";
		print "<a class=\"boton_linea\" href=\"javascript:seleccionar_elem()\">".$lang["seleccionar"]."</a>";				
	}
	else print "<p>".$lang["noEncontrados."]."</p>";
}
else
{
	for ($i=0;($i<count($contacto));$i++)
	{
		$datos = explode(";", $contacto[$i]);
		//Miramos a ver si ya existe o no
		$requete = "SELECT * FROM `Usuarios` WHERE Email = '".$datos[0]."' AND Email<>''"; 
		
		if ($result = mysqli_query($db, $requete)) 
		{
			$listado = mysqli_fetch_object($result);
			//Actualizamos el contacto metiéndolo en el grupo si es que no estaba ya y siempre que se haya seleccionado un grupo
			if ($Grupo!="")
			{
				$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdGrupo`='".$Grupo."' AND `IdUsuario`='".$listado->Id."'"; 
				
				if ($result = mysqli_query($db, $requete)) 
				{
					//Estaba en el grupo, por lo que no hago nada
				}
				else
				{
					$requete = "INSERT INTO `PertenenciaGrupos` (`IdGrupo`, `IdUsuario`) VALUES ('".$Grupo."', '".$listado->Id."');";
					mysqli_query($db,$requete);
					//Agregado el usuario al grupo
				}
			}
		}
		else
		{
			//No existe el usuario por lo que hay que crearlo
			$requete = "INSERT INTO `Usuarios` (`Email`) VALUES ('".$datos[0]."');";
			mysqli_query($db,$requete);
			$idUsuario = mysqli_insert_id($db);
			if ($Grupo!="")
			{
				$requete = "INSERT INTO `PertenenciaGrupos` (`IdGrupo`, `IdUsuario`) VALUES ('".$Grupo."', '".$idUsuario."');";				
				mysqli_query($db,$requete);
			}
		}
	}
	print "<p>".$lang["numImportados"].": ".$i." ".$lang["contactos"]."</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>