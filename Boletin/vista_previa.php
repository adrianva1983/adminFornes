<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
require($_SERVER['DOCUMENT_ROOT']."/Plantillas/boletin_electronico.php");
print $mensaje;
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>