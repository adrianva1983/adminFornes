<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
print $_SERVER['SERVER_NAME'];
?>
 - DÉJÀ VU ®