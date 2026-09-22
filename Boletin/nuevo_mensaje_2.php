<?php
// Hay que hacer lo mismo que la de buscar carpetas, pero para usuarios
// La diferencia es que la búsqueda básica será por nombre y/o apellido
// Abrá posibilidad de búsqueda avanzada que permitirá buscar por cualquier campo de la tabla de usuarios menos por la contraseña
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
	print "<p class=\"mensajeKO\">No tiene permisos para acceder a este &aacute;rea</p>";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
function busca_usuario($que,$t)
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	//Cargamos el idioma
	require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/buscar_2-".$_SESSION['idioma'].".conf");
	$item="Secciones";
	$requete = "SELECT `Id`, `Nombre`, `Apellidos`, `Direccion`, `Ciudad`, `Municipio`,`Provincia`,`NombreEmpresa`,`CIF`,`CP`, `Pais`, `Email`, `Telefono`, `Movil`, `Activado`, `Password`, `Passmd5`, `NivelAcceso`, `FechaCreacion`, `FechaCaducidad`, `FechaUltimoAcceso`  FROM `Usuarios` WHERE $que;";
	//echo $requete;
	$result = mysql_query($requete);
	if(mysqli_num_rows($result)==0)
	{
		echo "<p class=\"mensajeKO\">".$lang["nada"]."</p>"; //Lo siento, no hay nada parecido en la Base de Datos
		mysql_free_result($result);
		require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
		exit;
	}
	else
	{
		echo "<table>";
		if ($row['NivelAcceso']<$_SESSION['usuario_nivel'])
		{
			echo "<tr><th colspan=\"4\">".$lang["acciones"]."</th><th>".$lang["nombre"]."</th></tr>";
		}
		else
		{
			echo "<tr><th colspan=\"2\">".$lang["acciones"]."</th><th>".$lang["nombre"]."</th></tr>";
		}
		$a=array(); 
		$par = false;
		while ($row = mysql_fetch_assoc($result)) 
		{
			if ($t==0)
			{
				if ($par) 
				{
					print "<tr id=\"par\">";
					$par = false;
				}
				else
				{
					print "<tr>";
					$par = true;
				}
				//Si el usuario que consulta tiene menor nivel que el que se lista, no puede ver su ficha ni sus contenidos ni editarlo
				if ($row['NivelAcceso']<$_SESSION['usuario_nivel'])
				{
					echo "<td class=\"acciones\"><img src=\"/administra/Imagenes/usuario.png\"></td>";
					echo "<td class=\"acciones\"><a href=\"mailto:".$row['Email']."\"><img src=\"/administra/Imagenes/email.png\" alt=\"".$lang["mandar"]."\" title=\"".$lang["mandar"]."\"></a></td>";
					echo "<td>".$row['Apellidos'].", ".$row['Nombre']."</td>";
				}
				else
				{
					echo "<td class=\"acciones\"><img src=\"/administra/Imagenes/usuario.png\"></td>";
					echo "<td class=\"acciones\"><a href=\"mailto:".$row['Email']."\"><img src=\"/administra/Imagenes/email.png\" alt=\"".$lang["mandar"]."\" title=\"".$lang["mandar"]."\"></a></td>";
					echo "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje&idusuario=".$row['Id']."\"><img alt=\"".$lang["mensaje"]."\" title=\"".$lang["mensaje"]."\" src=\"/administra/Imagenes/boletin_enviar.png\"/></a></td>";
					echo "<td class=\"acciones\"><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$row['Id']."\"><img src=\"/administra/Imagenes/vcard.png\" title=\"".$lang["ficha"]."\" alt=\"".$lang["ficha"]."\"></a></td>";
					echo "<td>".$row['Apellidos'].", ".$row['Nombre']."</a></td>";
				}
				echo "</tr>";
			}
		}
		print "</table>";
		mysql_free_result($result);
		require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	}
}

$key = "Por razones de seguridad esta clave no debe de salir de este fichero KEY=18765325562356436789";
 
//Encrypt Function
function encrypt($encrypt) 
{
	global $key;
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
	$encode = base64_encode($passcrypt);
	return $encode;
}
 
//Decrypt Function
function decrypt($decrypt) 
{
	global $key;
	$decoded = base64_decode($decrypt);
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv);
	return $decrypted;
}
?>