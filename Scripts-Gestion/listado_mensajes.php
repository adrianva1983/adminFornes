<?php
$resultados = array(); 
/* Extrae los valores enviados desde la aplicacion movil */
$id_usuario = $_GET['id_usuario'];
$hash = $_GET['hash'];
$inicio = $_GET["inicio"];
$fin = $_GET["fin"];
if ($inicio=="") $inicio = 0;
if ($fin=="") $fin=10;

require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$loginOK = true;
//Nos aseguramos de que no nos suplantan al usuario
$requete = "SELECT * FROM `Usuarios` WHERE `Id`= ".$id_usuario;	

if ($result = mysqli_query($db, $requete))
{
	$listado = mysqli_fetch_object($result);
	if (md5($listado->FechaUltimoAcceso.$id_usuario)!=$hash)
	{
		$resultados["mensaje"] = "Fallo sistema seguridad";
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
}
else 
{
	$resultados["mensaje"] = "Usuario inexistente";
	$resultados["validacion"] = "error";
	$loginOK = false;
}
if ($loginOK)
{
	$mensajes_consulta = mysql_query("SELECT * FROM `Mensajeria` WHERE IdUsuarioDestino = '".$id_usuario."'  AND (`EstadoDestino`<>'borrado' || `EstadoDestino` IS NULL) ORDER BY `Fecha` DESC LIMIT ".$inicio.",".$fin);
	if (mysqli_num_rows($mensajes_consulta) > 0) 
	{		
		// almacenamos datos del Usuario en un array para empezar a chequear.
		while($mensajes = mysql_fetch_array($mensajes_consulta))
		{			
			$tmp["Titulo"] = utf8_encode($mensajes["Titulo"]);
			$tmp["Mensaje"] = utf8_encode($mensajes["Mensaje"]);
			$tmp["IdUsuarioOrigen"] = $mensajes["IdUsuarioOrigen"];
			$tmp["Visto"] = $mensajes["Visto"];
			$tmp["Fecha"] = $mensajes["Fecha"];
			$resultados[$mensajes["Id"]] = $tmp;			
		}
	}
	else
	{
		$resultados["mensaje"] = "No hay resultados";
		$resultados["validacion"] = "error";
		$loginOK = false;
	}
}
/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
 
/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>