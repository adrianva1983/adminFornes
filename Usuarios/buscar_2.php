<?php
// Hay que hacer lo mismo que la de buscar carpetas, pero para usuarios
// La diferencia es que la búsqueda básica será por nombre y/o apellido
// Abrá posibilidad de búsqueda avanzada que permitirá buscar por cualquier campo de la tabla de usuarios menos por la contraseña
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO COORDINADOR
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
	$result = mysqli_query($db,$requete);
	if(mysqli_num_rows($result)==0)
	{
		echo "<p class=\"mensajeKO\">".$lang["nada"]."</p>"; //Lo siento, no hay nada parecido en la Base de Datos
		mysqli_free_result($result);
		require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
		exit;
	}
	else
	{
		print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['buscar'].'</h5>';
		print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
		print '<div class="ibox-content"><div class="row"><div>
                                <table class="table table-striped">
                                    <thead>';		
		if ($row['NivelAcceso']<$_SESSION['usuario_nivel'])
		{
			echo "<tr><th>".$lang["acciones"]."</th><th>".$lang["contenidos"]."</th><th>".$lang["nombre"]."</th></tr>";
		}
		else
		{
			echo "<tr><th>".$lang["acciones"]."</th><th>".$lang["nombre"]."</th></tr>";
		}
		print '</thead><tbody>';
		$a=array(); 
		$par = false;
		while ($row = mysqli_fetch_assoc($result)) 
		{
			if ($t==0)
			{
				print "<tr>";				
				//Si el usuario que consulta tiene menor nivel que el que se lista, no puede ver su ficha ni sus contenidos ni editarlo
				if ($row['NivelAcceso']<$_SESSION['usuario_nivel'])
				{					
					echo "<td>";
					print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
					print '<ul class="dropdown-menu">';
					print "<li><a href=\"mailto:".$row['Email']."\" title=\"".$lang["mandar"]."\"><i class='fa fa-envelope'></i> ".$lang["mandar"]."</a></li>";
					print "<ul></div></td>";
					echo "<td>".$row['Apellidos'].", ".$row['Nombre']."</td>";
				}
				else
				{
					echo "<td>";
					print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
					print '<ul class="dropdown-menu">';
					if ($row['Activado']=='si')
					{
						echo "<li><a href=\"/administra/Usuarios/funciones/activacion.php?pasada=no&Id=".$row['Id']."&Nombre=".$Nombre."&Apellidos=".$Apellidos."&Direccion=".$Direccion."&Ciudad=".$Ciudad."&Municipio=".$Municipio."&Provincia=".$Provincia."&NombreEmpresa=".$NombreEmpresa."&CIF=".$CIF."&CP=".$CP."&Pais=".$Pais."&Email=".$Email."&Telefono=".$Telefono."&Movil=".$Movil."&NivelAcceso=".$NivelAcceso."&FechaCreaccion=".$FechaCreacion."&FechaCaducidad=".$FechaCaducidad."&FechaUltimoAcceso=".$FechaUltimoAcceso."\" title=\"".$lang["desactivar"]."\"><i class='fa fa-pause'></i> ".$lang["desactivar"]."</a></li>";
					}
					else
					{
						echo "<li><a href=\"/administra/Usuarios/funciones/activacion.php?pasada=si&Id=".$row['Id']."&Nombre=".$Nombre."&Apellidos=".$Apellidos."&Direccion=".$Direccion."&Ciudad=".$Ciudad."&Municipio=".$Municipio."&Provincia=".$Provincia."&NombreEmpresa=".$NombreEmpresa."&CIF=".$CIF."&CP=".$CP."&Pais=".$Pais."&Email=".$Email."&Telefono=".$Telefono."&Movil=".$Movil."&NivelAcceso=".$NivelAcceso."&FechaCreaccion=".$FechaCreacion."&FechaCaducidad=".$FechaCaducidad."&FechaUltimoAcceso=".$FechaUltimoAcceso."\" title=\"".$lang["activar"]."\"><i class='fa fa-play'></i> ".$lang["activar"]."</a></li>";
					}					
					echo "<li><a href=\"mailto:".$row['Email']."\"><i class='fa fa-envelope'></i> ".$lang["mandar"]."</a></li>";
					echo "<li><a href=\"/administra/Interface/herramienta.php?modulo=Boletin&herramienta=nuevo_mensaje&idusuario=".$row['Id']."\"><i class='fa fa-envelope'></i> ".$lang["mensaje"]."\"</a></li>";
					echo "<li><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=ver_ficha&usuario=".$row['Id']."\"><i class='fa fa-book'></i> ".$lang["ficha"]."</a></li>";
					print "<ul></div></td>";
					//Número de contenidos generados por él
					$requete2 = "SELECT * FROM `Contenidos` WHERE `IdPropietario`='".$row['Id']."'";
					$result2 = mysqli_query($db, $requete2);
					if (($result2) && (mysqli_num_rows($result2)>0))					
					{
						print "<td><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=contenidos_usuario&usuario=".$row['Id']."\"><img src=\"/administra/Imagenes/contenido.png\" title=\"".$lang["contenidos"]."\" alt=\"".$lang["contenidos"]."\">(".mysqli_num_rows($result2).")</a></td>";
					}
					else print "<td>0</td>";
					echo "<td><a href=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=editar_usuario&usuario=".$row['Id']."\" title=\"".$lang["editar"]."\">".$row['Apellidos'].", ".$row['Nombre']."</a></td>";
				}
				echo "</tr>";
			}
		}
		print "</tbody></table></div>";
		print "</div></div>";
		print "</div></div></div>";
		mysqli_free_result($result);
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