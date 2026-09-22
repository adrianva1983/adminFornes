<?php
$resultados = array(); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
/* Extrae los valores enviados desde la aplicacion movil */
$usuarioEnviado = $_GET['usuario'];
$passwordEnviado = $_GET['password'];

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$usuario_consulta = mysqli_query($db,"SELECT Id,Email,Passmd5,NivelAcceso,RedirigirLogin FROM Usuarios WHERE Email='".$usuarioEnviado."'");
$loginOK = true;
if (mysqli_num_rows($usuario_consulta) != 0) 
{		
	// almacenamos datos del Usuario en un array para empezar a chequear.
	$usuario_datos = mysqli_fetch_array($usuario_consulta);
	// liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
	mysqli_free_result($usuario_consulta);
	if ($usuarioEnviado != $usuario_datos['Email']) 
	{						
		$resultados["mensaje"] = "Usuario incorrecto";
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
	if (md5($passwordEnviado) != $usuario_datos['Passmd5'])
	{
		$resultados["mensaje"] = utf8_encode("Contraseña incorrecta");
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
}
else
{
	$resultados["mensaje"] = "Usuario incorrecto";
	$resultados["validacion"] = "error";
	$loginOK = false;
}
if ($loginOK)
{
	$fecha_ult_acceso = date("Y-m-d h:i:s");
	$requete = "UPDATE `Usuarios` SET `FechaUltimoAcceso` = '".$fecha_ult_acceso."' WHERE `Id`=".$usuario_datos['Id'].";";
	mysqli_query($db,$requete);
	$resultados["mensaje"] = "Validacion Correcta";
	$resultados["validacion"] = "ok";
	$resultados["usuario_id"] = $usuario_datos['Id'];
	$resultados["NivelAcceso"] = $usuario_datos['NivelAcceso'];	
	$resultados["pass"] = $usuario_datos['Passmd5'];
	$resultados["url-privada"] = $usuario_datos['RedirigirLogin'];
	$resultados["hash"] = md5($fecha_ult_acceso.$usuario_datos['Id']);
}

/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $resultadosJson;
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>