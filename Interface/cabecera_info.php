<div id="cabecera_info">
<?php
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/idiomas/cabecera_info-".$_SESSION['idioma'].".conf");
$requete = "SELECT * FROM Mensajeria WHERE IdUsuarioDestino=".$_SESSION['usuario_id']." AND Visto = NULL;";

print "<ul>";
print "<li><a href=\"?desconectar=si\">".$langCabecera["desconectar"]."</a></li>";
print "<li><strong>(".mysqli_num_rows($result).")</strong> ".$langCabecera["mensajes"]."</li>";
print "</ul>";
?>
</div>