<?php
session_start(); // necesario en cada peticion para acceder a datos de sesion
// $dato contiene algo qe quieres guardar en la sesion
$_SESSION["miDato"] = $dato;
?>