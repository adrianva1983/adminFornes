<?php
$herramienta = $_GET["herramienta"];
$modulo = $_GET["modulo"];
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
$URL = $_SERVER['SCRIPT_URI']."?";
for ($i=0;($i<$_SERVER['argc']);$i++)
{
 $URL = $URL.$_SERVER['argv'][$i];
}

if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
?>
<!DOCTYPE html>
<html>

<head>

	<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración</title>

    <link href="/administra/css/bootstrap.min.css" rel="stylesheet">
    <link href="/administra/font-awesome/css/font-awesome.css" rel="stylesheet">

	<link href="/administra/css/plugins/iCheck/custom.css" rel="stylesheet">
	<link href="/administra/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

	<!-- Sweet Alert -->
    <link href="/administra/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Toastr style -->
    <link href="/administra/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <link href="/administra/css/animate.css" rel="stylesheet">
    <link href="/administra/css/style.css" rel="stylesheet">	
	<link href="/administra/css/custom.css" rel="stylesheet">

</head>
<body>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
<?php
	$requete = "SELECT * FROM `Usuarios` WHERE `Id`=".$_SESSION['usuario_id'];
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		if ($listado->Foto!='') print '<img alt="image" class="img-circle" style="width:48px;" src="/Imagenes/Perfiles/'.$listado->Foto.'"/>';
		else print '<img style="width:48px;" alt="image" class="img-circle" src="/Mail/logo.jpg"/>';
		print '</span><a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">'.$listado->Nombre.' '.$listado->Apellidos.'</strong>';
		print '</span> <span class="text-muted text-xs block">'.$listado->Cargo.' <b class="caret"></b></span> </span> </a>';
		print '<ul class="dropdown-menu animated fadeInRight m-t-xs">
							<li><a href="/administra/Interface/administra.php">Home</a></li>  
		                    <li><a href="/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=editar_usuario&usuario'.$_SESSION['usuario_id'].'">Perfil</a></li>                            
                            <li class="divider"></li>
                            <li><a href="/administra">Salir</a></li>';
	}
?>
                        </ul>
                    </div>
                    <div class="logo-element">
                        M+
                    </div>
                </li>
				
				<?php require("menumodulos.php");?>
            </ul>

        </div>
    </nav>


	<div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">

        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
		</div>
<?php 	
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");	
	require("../".$modulo."/"."acciones.php");		
	require("cabecera.php");	
?>		
		</nav>
		</div>
		<div class="wrapper wrapper-content">
		<?php	
		$acciones_disponibles = false;					
		require("../".$modulo."/".$herramienta.".php");			
		?>
		</div>
		<div class="footer">
		<?php require("pie.php");?>
        </div>
	</div>
    <!-- Mainly scripts -->
    <script src="/administra/js/jquery-2.1.1.js"></script>
    <script src="/administra/js/bootstrap.min.js"></script>
    <script src="/administra/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/administra/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="/administra/js/plugins/flot/jquery.flot.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="/administra/js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="/administra/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/administra/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/administra/js/inspinia.js"></script>
    <script src="/administra/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/administra/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <script src="/administra/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/administra/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

	<!-- Chartjs -->
	<script src="/administra/js/plugins/chartJs/Chart.min.js"></script>
	
    <!-- EayPIE -->
    <script src="/administra/js/plugins/easypiechart/jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <script src="/administra/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/administra/js/demo/sparkline-demo.js"></script>
	
	<!-- iCheck -->
    <script src="/administra/js/plugins/iCheck/icheck.min.js"></script>	
	
	<!-- Nestable List -->
    <script src="/administra/js/plugins/nestable/jquery.nestable.js"></script>
	
	<!-- TINY -->
	<script src="/administra/js/plugins/tinymce/tinymce.min.js"></script>
	
	<!-- Data picker -->
	<script src="/administra/js/plugins/datapicker/bootstrap-datepicker.js"></script>
	
	<!-- Sweet alert -->
	<script src="/administra/js/plugins/sweetalert/sweetalert.min.js"></script>
	
    <!-- Toastr script -->
    <script src="/administra/js/plugins/toastr/toastr.min.js"></script>

	
	<script>
        $(document).ready(function() {				
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
				<?php
				if ($mensaje!="")
				{
					echo '
							swal({
								title: "Operación realizada con éxito",
								text: "'.$mensaje.'",
								html: true,
								type: "success"
							});	';
				}
				if ($errores!="")
				{
					echo '
							swal({
								title: "Hemos encontrado algún problema",
								text: "'.$errores.'",
								html: true,
								type: "error"
							});	';
				}
				if ($javascript_onready != "") print $javascript_onready;
				?>
		});
		
	</script>
</body>
</html>
<?php
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>