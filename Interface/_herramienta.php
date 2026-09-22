<?php
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
$URL = $_SERVER['SCRIPT_URI']."?";
for ($i=0;($i<$_SERVER['argc']);$i++)
{
 $URL = $URL.$_SERVER['argv'][$i];
}

if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
<title>Administración</title>
<link href="../../estilos/css-administra.php" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../herramientas/Scripts/mootools12.js"></script>
</head>
<body>
<div id="maincontainer">

<div id="topsection">
<?php
require("cabecera.php");
?>
</div>

<div id="contentwrapper">
<div id="contentcolumn">
<?php
	require("../".$modulo."/".$herramienta.".php");	
?>
</div>
</div>

<div id="leftcolumn">
<?php
require("menumodulos.php");
?>
</div>

<div id="rightcolumn">
<?php
require("../".$modulo."/"."acciones.php");
?>
</div>

<div id="footer">
<?php
require("pie.php");
?>
</div>
</div>
</body>
</html>
