<?php
  // No almacenar en el cache del navegador esta p?gina.
header('Content-Type: text/html; charset=iso-8859-1');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             		// Expira en fecha pasada
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// Siempre p?gina modificada
		header("Cache-Control: no-cache, must-revalidate");           		// HTTP/1.1
		header("Pragma: no-cache");                                   		// HTTP/1.0
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
$requete = "SELECT * FROM `Servidor` WHERE `Tipo`='Estilos'";
$estilos = array();
if ($result = mysqli_query($db, $requete))
{
	while ($listado = mysqli_fetch_object($result))
	{
		$estilos[$listado->Campo]=$listado->Valor;
	}
}
if (isset($_SESSION['usuario_login'])&&$_SESSION['usuario_login']!="") session_destroy();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Zona Privada</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
	<!-- Sweet Alert -->
    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name"><img alt="Logo" src="../estilos/images/logo.png"></h1>
            </div>
			<p>&nbsp;</p>
            <h3>Bienvenido al panel de control</h3>			
            <p>Panel de control para portales web dise?ado y desarrollado por &copy; Semilla Proyectos Internet.</p>
            <p>Introduce tu usuario y contrase?a.</p>
            <form class="m-t" method="post" role="form" action="Interface/administra.php">
                <div class="form-group">
                    <input type="text" name="user" class="form-control" placeholder="Usuario" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="pass" class="form-control" placeholder="******" required="">
                </div>
				<?php
				$requete = "SELECT * FROM `Idiomas` WHERE `Activado-Administracion`='si' ORDER BY `Orden`";
				
				if ($result = mysqli_query($db, $requete))
				{
					print '<div class="form-group">';
					print "<select id=\"idioma\" name=\"idioma\">";
					while ($listado = mysqli_fetch_object($result))
					{
						print "<option value=\"".$listado->Codigo."\">".$listado->Descripcion."</option>";		
					}
					print "</select></div>";
				}
				require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
				?>
                <button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>

                <a href="/administra/Interface/recuperar_pass.php"><small>Olvidaste tu contrase?a?</small></a>
            </form>
            <p class="m-t"> <small>&copy; Semilla Proyectos Internet</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<!-- Sweet alert -->
	<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
	<script type='text/javascript'>	
	$(document).ready(function(){		
	<?php
	require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_mensaje_error.inc.php");
	if ($_GET["error_login"]!="") $errores = $error_login_ms[$_GET["error_login"]];
	if ($errores!="")
	{
		echo '
				swal({
					title: "Fallo de acceso",
					text: "'.$errores.'",
					html: true,
					type: "error"
				});	';
	}
	?>
	});
	</script>	
</body>

</html>