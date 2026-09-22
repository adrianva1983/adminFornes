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
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/contactos_cliente-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM `Contactos` WHERE `IdCliente`=".$Id;
$requete .= " ORDER BY `Id`";

print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/user_go.png\" title=\"".$lang["reasignar"]."\" alt=\"".$lang["reasignar"]."\"> :: ".$lang["reasignar"]."<br/>";
print "<img src=\"/administra/Imagenes/user_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"> :: ".$lang["editar"]."<br/>";
print "<img src=\"/administra/Imagenes/email.png\" title=\"".$lang["mandarMail"]."\" alt=\"".$lang["mandarMail"]."\"> :: ".$lang["mandarMail"]."<br/>";
print "</div>";
if ($result = mysqli_query($db, $requete))
{
	print "<table>";
	print "<tr><th>Id</th><th>".$lang["acciones"]."</th><th>".$lang["nombre"]."</th><th>".$lang["apellidos"]."</th><th>".$lang["cargo"]."</th><th>".$lang["movil"]."</th><th>".$lang["email"]."</th><th>".$lang["representante"]."</tr>";
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
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=reasignar_contacto&Id=".$listado->Id."\"><img src=\"/administra/Imagenes/user_go.png\" title=\"".$lang["reasignar"]."\" alt=\"".$lang["reasignar"]."\"></a> ";
		print "<a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_contacto&Id=".$Id."&IdContacto=".$listado->Id."\"><img src=\"/administra/Imagenes/user_edit.png\" title=\"".$lang["editar"]."\" alt=\"".$lang["editar"]."\"></a> ";
		print "<a href=\"mailto:".$listado->Email."\"><img src=\"/administra/Imagenes/email.png\" title=\"".$lang["mandarMail"]."\" alt=\"".$lang["mandarMail"]."\"></a> ";
		print "</td>";
		print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_contacto&Id=".$Id."&IdContacto=".$listado->Id."\">";
		print $listado->Nombre;
		print "</a></td>";
		print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_contacto&Id=".$Id."&IdContacto=".$listado->Id."\">";
		print $listado->Apellidos;
		print "</a></td>";
		print "<td>".$listado->Cargo."</td>";
		print "<td>".$listado->Movil."</td>";
		print "<td>".$listado->Email."</td>";
		print "<td>";
		if ($listado->IdRepresentante!="")
		{
			$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdRepresentante;
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);
				print $listado2->Nombre." ".$listado2->Apellidos;
			}			
		}
		else print "-";
		print "</td>";
		print "</tr>";
	}
	print "</table>";
}
else
{
	print "<p class=\"mensajeKO\">".$lang["noResultados"]."</p>";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>
