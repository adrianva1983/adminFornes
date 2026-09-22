<?php
//NECESITA:
// - Conexión a base de datos
// - $alta_texto_campos: [ARRAY]Array con los textos asociados a los campos
// - $alta_titulos_campos: [ARRAY]Array con los titulos de los campos de usuario
// - $alta_valores_campos: [ARRAY] Array con los valores del usuario
// - $alta_valores_tipos: [ARRAY] Tipos de datos para los campos
// - $alta_obligatorios: [ARRAY]Array con los campos obligatorios. Tienen que estar en el mismo orden que los titulos y con el mismo valor
// - $alta_permisos: [NIVEL] nivel a asignar al usuario
// - $alta_activado: [si,no] estado inicial del usuario
// - $alta_alerta: [BOOLEANO] alerta de alta de usuario
//PRODUCE: Inserta el usuario en la base de datos y en el grupo que corresponda. Envía un correo electrónico al mismo informando de su solicitud de alta. Envía un correo a la cuenta de altas si se ha solicitado.
//
$dominio = $_SERVER['SERVER_NAME'];
$campos=$_POST;
$titulos = array_keys($_POST);
$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Usuarios` WHERE Id='".$Usuario."'";

$listado = mysqli_fetch_object($result);
if ($campos["Email"]!="") $headers .= "From: ".$campos["Nombre"]." <".$campos["Email"].">\r\n";
else 
{
	if ($EmailUsuario=="") $headers .= "From: SIN EMAIL <".$_POST['EmailFormulario'].">\r\n";
	else $headers .= "From: ".$listado->Nombre." <".$EmailUsuario.">\r\n";
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
$msg = "";
for ($i=0;$i<count($campos);$i++)
{
	if ($campos[$titulos[$i]]!="")
	{
		$msg .= "<strong>".$titulos[$i].":</strong> ".$campos[$titulos[$i]]."<br/>";
	}
}
include($_SERVER['DOCUMENT_ROOT']."/Plantillas/alerta_correo.php");			
mail($_POST['EmailFormulario'], utf8_encode($_POST['TituloFormulario']), $mensaje, $headers);
$url = $_SERVER['HTTP_REFERER'];
if ((count($_SERVER['argv'])>0)||(substr_count($_SERVER['HTTP_REFERER'], "?")))
{	  
	for ($i=0; $i<count($_SERVER['argv']); $i++)
	{
		if ($i==0) $url = $url."?".$_SERVER['argv'][$i];
		else $url = $url."&".$_SERVER['argv'][$i];
	}
	$url = $url."&pagina_formulario=".$pagina_formulario;
}
else $url = $url."?pagina_formulario=".$pagina_formulario;
//Recargamos el contenido en curso
header("Location:".$url);
?>