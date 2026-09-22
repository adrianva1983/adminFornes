<?php
$contenido = $_GET['contenido'];
$seccion = $_GET['seccion'];
$ruta = $_GET['ruta'];
$tipocontenido = $_GET['tipocontenido'];

//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=3; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &áacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Carpetas/idiomas/vincular_contenido_arbol-".$_SESSION['idioma'].".conf");
print '<div class="row"><div class="col-lg-12"><div class="ibox float-e-margins"><div class="ibox-title"><h5>'.$lang['vincular_arbol'].'</h5><div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div><div class="ibox-content">';
print "<form action=\"/administra/Carpetas/vincular_contenido_3.php?contenido=".$contenido."&seccion=".$seccion."&ruta=".$ruta."&tipocontenido=".$tipocontenido."\" enctype=\"multipart/form-data\" method=\"POST\">";
print "<div class='form-group'><label class='col-sm-2 control-label' for=\"Titulo\">".$lang["plantilla"]."</label><div class='col-sm-10'>";
print "<select class=\"form-control\" name=\"Plantilla\">";
// Hacemos una consulta para ver las distintas plantillas a aplicar
$requete = "SELECT * FROM `Plantillas` WHERE `Tipo`='contenido';";
$result = mysqli_query($db,$requete);	
// Listamos las plantillas existentes
if (($result) && (mysqli_num_rows($result)>0))
{
	while($listado = mysqli_fetch_object($result))
	{
		print "<option value=\"".$listado->Nombre."\">".$listado->Nombre."</option>";
	}
}
print "</select>";
print "</div></div>";
print "<hr>";

print "<div class='form-group'><div class='col-sm-12'>";
//ARBOL DE LA WEB SOBRE EL QUE SELECCIONAREMOS EL RESTO
//-----------------------------------------------------
//FUNCIONES USADAS
$chartab="_";
function db_arbol($tabas,$item,$padre,$ruta,$conte,$db)
{		
	if ($padre=="") {exit;}
	if ($padre=="NULL") 
	{
		$requete="SELECT `Id`, `Titulo`, `NomFich` FROM `$item` WHERE `IdPadre` is NULL ORDER BY `Orden`";
	}
	else 
	{
		$requete = "SELECT `Id`, `Titulo`, `NomFich`  FROM `$item` WHERE `IdPadre`='".$padre."' ORDER BY `Orden`";
	} 
	if ($item=="") {exit;}
	$result = mysqli_query($db,$requete);		
	$a=array();
	if (!$result) exit;
	while ($row = mysqli_fetch_assoc($result)) 
	{
		$requete2="SELECT * FROM `Publicaciones` WHERE `IdSeccion`='".$row['Id']."' AND `IdContenido`='".$conte."'";
		$result2 = mysqli_query($db,$requete2);
		if (($result2) && (mysqli_num_rows($result2)>0))	
		{
			$publicado = true;
		}
		else
		{
			$publicado = false;
		}
		$id=$row['Id'];
		$titulo=$row['Titulo'];
		$nomfich=$row['NomFich'];
		$b[]=array();
		$b["Id"]=$id;
		$b["Titulo"]=$titulo;
		$b["NomFich"]=$nomfich;
		if ($ruta=="") 
		{
			$b["Ruta"]=$nomfich;
		}
		else
		{
			$b["Ruta"]=$ruta."/".$nomfich;
		}
		$b["Publicado"]=$publicado;
		$a[]=$b;
		mysqli_free_result($result2);
	}
	mysqli_free_result($result);
	echo "<ul>";
	foreach ($a as $d) 
	{
		$id=$d['Id'];
		$titulo=$d['Titulo'];
		$nomfich=$d['NomFich'];
		$enruta=$d['Ruta'];
		$publicado=$d['Publicado'];
		if ($publicado)
		{
			print "<li><img src=\"/administra/Imagenes/tick.png\" title=\"".$lang["vinculado"]." alt=\"".$lang["vinculado"]."\"> ";
		}
		else
		{
			print "<li><input class=\"suscripcion\" name=\"secciones/".$id."\" type=\"checkbox\" value=\"".$id."\">";
		}
		print "<img src=\"/administra/Imagenes/secciones.png\">$titulo</a></li>";
		$tabas=$tabas+1;
		db_arbol("$tabas","$item","$id","$enruta","$conte",$db);
	}
	echo "</ul>";
}
//RESTO DEL PROGRAMA
db_arbol(0,"Secciones","NULL","",$contenido,$db);
print "</div></div>";
print "<input class=\"btn btn-primary\" type=\"submit\" value=\"".$lang["vincular"]."\">";
?>
</form>
</div></div></div></div>