<?php
if (!headers_sent()) header('Content-Type: text/html; charset=iso-8859-1');
if (isset($_GET["desconectar"])) $desconectar = $_GET["desconectar"];
else $desconectar = "";
// Motor autentificación usuarios.

// Cargar datos conexion.
if (!$db) require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");


// chequear página que lo llama para devolver errores a dicha página.

$url = explode("?",$_SERVER['HTTP_REFERER']);
$pag_referida=$url[0];
$redir=$pag_referida;
// chequear si se llama directo al script.
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}


// Chequeamos si se está autentificandose un usuario por medio del formulario
if (isset($_POST['user']) && isset($_POST['pass'])) {

// realizamos la consulta a la BD para chequear datos del Usuario.
$usuario_consulta = mysqli_query($db,"SELECT Id,Email,Passmd5,NivelAcceso FROM Usuarios WHERE Email='".$_POST['user']."'") or die(header ("Location:  $redir?error_login=1"));

 // miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
 if (mysqli_num_rows($usuario_consulta) != 0) {

    // eliminamos barras invertidas y dobles en sencillas
    $login = stripslashes($_POST['user']);
    // encriptamos el password en formato md5 irreversible.
    $password = md5($_POST['pass']);

    // almacenamos datos del Usuario en un array para empezar a chequear.
 	$usuario_datos = mysqli_fetch_array($usuario_consulta);

    // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
    mysqli_free_result($usuario_consulta);

    // chequeamos el nombre del usuario otra vez contrastandolo con la BD
    // esta vez sin barras invertidas, etc ...
    // si no es correcto, salimos del script con error 4 y redireccionamos a la
    // página de error.
    if ($login != $usuario_datos['Email']) {
       	Header ("Location: $redir?error_login=4");
		exit;}

    // si el password no es correcto ..
    // salimos del script con error 3 y redireccinamos hacia la página de error
    if ($password != $usuario_datos['Passmd5']) {
        Header ("Location: $redir?error_login=3");
	    exit;}

    // Actualizamos el último acceso en el sistema.
	$requete = "UPDATE `Usuarios` SET `FechaUltimoAcceso` = '".date("Y-m-d")."' WHERE `Email` ='".$login."' AND `Passmd5`='".$password."';";
	mysqli_query($requete,$db);
    // Paranoia: destruimos las variables login y password usadas
    unset($login);
    unset ($password);

    // En este punto, el usuario ya esta validado.
    // Grabamos los datos del usuario en una sesion.

     // le damos un mobre a la sesion.
    session_name("Autenticador");
     // incia sessiones
    session_start();

    // Paranoia: decimos al navegador que no "cachee" esta página.
    session_cache_limiter('nocache,private');

    // Asignamos variables de sesión con datos del Usuario para el uso en el
    // resto de páginas autentificadas.

    // definimos usuarios_id como IDentificador del usuario en nuestra BD de usuarios
    $_SESSION['usuario_id']=$usuario_datos['Id'];

    // definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    $_SESSION['usuario_nivel']=$usuario_datos['NivelAcceso'];

    //definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    $_SESSION['usuario_login']=$usuario_datos['Email'];

    //definimos usuario_password con el password del usuario de la sesión actual (formato md5 encriptado)
    $_SESSION['usuario_password']=$usuario_datos['Passmd5'];
    
    //definimos el idioma elegido por el usuario
    if (($_SESSION['idioma']=="")|| $_POST['idioma']!="") $_SESSION['idioma'] = $_POST['idioma'];

	if (isset($_COOKIE['usuario_admin_dejavu'])&&$_COOKIE['usuario_admin_dejavu']!='')
	{
		$caducidad_cookie = time() + (30*24*60*60); //Le aplico a la cookie una caducidad de una semana
		$FechaCaducidadCookie = date('Y-m-d H:i:s',$caducidad_cookie);
		$fecha_cookie=mktime(0,0,0,date('d',$caducidad_cookie),date('m',$caducidad_cookie),date('Y',$caducidad_cookie));
		setcookie('usuario_admin_dejavu',$_SESSION['usuario_id'],$caducidad_cookie,'/',' ',0);	
	}
	else
	{
		$caducidad_cookie = time() + (30*24*60*60); //Le aplico a la cookie una caducidad de una semana
		$FechaCaducidadCookie = date('Y-m-d H:i:s',$caducidad_cookie);
		$fecha_cookie=mktime(0,0,0,date('d',$caducidad_cookie),date('m',$caducidad_cookie),date('Y',$caducidad_cookie));
		setcookie('usuario_admin_dejavu',$_SESSION['usuario_id'],$caducidad_cookie,'/',' ',0);	
	}    

    // Hacemos una llamada a si mismo (scritp) para que queden disponibles
    // las variables de session en el array asociado $HTTP_...
    $pag=$_SERVER['PHP_SELF'];
    Header ("Location: $pag?");
    exit;

   } else {
      // si no esta el nombre de usuario en la BD o el password ..
      // se devuelve a pagina q lo llamo con error
      Header ("Location: $redir?error_login=2");
      exit;}
} else {

// -------- Chequear sesión existe -------

// usamos la sesion de nombre definido.
session_name("Autenticador");
// Iniciamos el uso de sesiones
if(!isset($_SESSION)) 
{ 
        session_start(); 
}
// Chequeamos si estan creadas las variables de sesión de identificación del usuario,
// El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
// con el navegador.

if (!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
// Borramos la sesion creada por el inicio de session anterior
session_destroy();
die ("Error cod.: 2 - Acceso incorrecto!");
exit;
}
}
if (isset($desconectar)&&($desconectar=="si"))
{
session_name("Autenticador");
session_destroy();
header("Location:/administra/");
exit;
}
?>
