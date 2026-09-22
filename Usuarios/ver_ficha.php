<?php
//VERSIÓN: v1.0 2014-01-17
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$usuario = $_GET["usuario"];
 //Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/ver_ficha-".$_SESSION['idioma'].".conf");
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">".$lang["errorPermisos"]."</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("<p class=\"mensajeKO\">".$lang["accesoIncorrecto"]."</p>");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

$requete = "SELECT * FROM `Usuarios` WHERE `Id`='".$usuario."'";


print "<div id=\"instrucciones\">";
print "<img src=\"/administra/Imagenes/email.png\" alt=\"".$lang["mandar"]."\" title=\"".$lang["mandar"]."\"> :: ".$lang["mandar"]."<br/>";
print "</div>";

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	//Comprobamos que el usuario que consulta la ficha tenga más permisos que el usuario consultado. Sino abortamos.
	if ($listado->NivelAcceso<$_SESSION['usuario_nivel'])
	{
		die ($lang["errorNivel"]);
		exit;
	}
	print "<ul>";
	print "<li><strong>".$lang["nombre"].":</strong> ".$listado->Nombre."</li>";
	print "<li><strong>".$lang["apellidos"].":</strong> ".$listado->Apellidos."</li>";
	print "<li><strong>".$lang["nick"].":</strong> ".$listado->Nick."</li>";
	print "<li><strong>".$lang["direccion"].":</strong> ".$listado->Direccion."</li>";
	print "<li><strong>".$lang["ciudad"].":</strong> ".$listado->Ciudad."</li>";
	print "<li><strong>".$lang["municipio"].":</strong> ".$listado->Municipio."</li>";
	print "<li><strong>".$lang["provincia"].":</strong> ".$listado->Provincia."</li>";
	print "<li><strong>".$lang["cp"].":</strong> ".$listado->CP."</li>";
	print "<li><strong>".$lang["pais"].":</strong> ".$listado->Direccion."</li>";
	print "<li><strong>".$lang["idioma"].":</strong> ".$listado->Idioma." <img src=\"/administra/Imagenes/".$listado->Idioma.".png\"/></li>";
	print "<li><strong>".$lang["telefono"].":</strong> ".$listado->Telefono."</li>";
	print "<li><strong>".$lang["movil"].":</strong> ".$listado->Movil."</li>";
	print "<li><strong>".$lang["email"].":</strong> <a href=\"mailto:".$listado->Email."\"><img src=\"/administra/Imagenes/email.png\" title=\"".$lang["mandar"]."\" alt=\"".$lang["mandar"]."\">".$listado->Email."</a></li>";
	print "</ul>";
	print "<h2>".$lang["empresa"]."</h2>";
	print "<ul>";
	print "<li><strong>".$lang["nombreEmpresa"].":</strong> ".$listado->NombreEmpresa."</li>";
	print "<li><strong>".$lang["CIF"].":</strong> ".$listado->CIF."</li>";
	print "</ul>";
	print "<h2>".$lang["vigencia"]."</h2>";
	print "<ul>";
	print "<li>".$lang["creacion"].": ".$listado->FechaCreacion."</li>";
	print "<li>".$lang["caducidad"].": ".$listado->FechaCaducidad."</li>";
	print "<li>".$lang["ultimo"].": ".$listado->FechaUltimoAcceso."</li>";
	print "</ul>";
	print "<h2>".$lang["origen"]."</h2>";
	print "<ul>";
	print "<li>".$lang["referencia"].": ".$listado->Referido."</li>";
	print "<li>".$lang["referenciaWeb"].": ".$listado->Referido_web."</li>";
	print "</ul>";
	print "<h2>".$lang["otrosDatos"]."</h2>";
	print "<p>".$listado->OtrosDatos."</p>";
	print "<ul>";
	print "<li>".$lang["redirigirLogin"].": ".$listado->RedirigirLogin."</li>";	
	print "</ul>";
}
//Listo los permisos que tiene específicos para cada nivel de usuario
$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";

if ($result = mysqli_query($db, $requete))
{
	while($listado = mysqli_fetch_object($result))
	{
		//Niveles para el usuario específico
		$requete = "SELECT `Secciones`.Titulo,`Secciones`.Id,`Permisos`.IdSeccion,`Permisos`.Nivel,`Permisos`.IdUsuarioSuscrito,`Permisos`.InicioSuscripcion,`Permisos`.FinSuscripcion FROM `Permisos`,`Secciones` WHERE `Secciones`.Id=`Permisos`.IdSeccion AND `Permisos`.IdUsuarioSuscrito='".$usuario."' AND `Permisos`.Nivel='".$listado->Nivel."'";
		$result2 = mysqli_query($db,$requete);
		if ($result2 = mysqli_query($db, $requete2))
		{
			print "<h2><img src=\"/administra/Imagenes/usuario.png\">".$listado->Nombre.":</h2>";
			$listado2 = mysqli_fetch_object($result2);
			print "<img src=\"/administra/Imagenes/secciones.png\"><strong>".$listado2->Titulo."</strong> - ".$lang["inicio"].":".$listado2->InicioSuscripcion." - ".$lang["fin"].":".$listado2->FinSuscripcion;
		}
	}
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>