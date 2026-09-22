<!DOCTYPE html>
<html>

<head>

    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">
    <title>RECUPERAR CONTRASEÑA</title>

    <link href="/administra/css/bootstrap.min.css" rel="stylesheet">
    <link href="/administra/font-awesome/css/font-awesome.css" rel="stylesheet">
	<!-- Sweet Alert -->
    <link href="/administra/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link href="/administra/css/animate.css" rel="stylesheet">
    <link href="/administra/css/style.css" rel="stylesheet">
	<link href="/administra/css/custom.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">¿Olvidaste la contraseña?</h2>

<?php
if (isset($_POST['email']))
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete ="SELECT * FROM `Usuarios` WHERE `Email` = '".$_POST['email']."'";	
	
	if ($result = mysqli_query($db, $requete))
	{
		$listado = mysqli_fetch_object($result);
		$Password = $listado->Password;
	}
	$mail_entorno ="info@semillaproyectos.com";
	//Cargamos los encabezados del mail
	$dominio = $_SERVER['SERVER_NAME'];
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: $dominio <$mail_entorno>\r\n";
	mysql_free_result($result);	
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	// ---- ENVÍO DE RECORDATORIO A USUARIO ------
	$msg = "<p>Ha solicitado recuperar su contrase&ntilde;a en ".$_SERVER['SERVER_NAME'].".</p><p> Su nombre de usuario es <strong>".$email."</strong> y su contrase&ntilde;a es <strong>".$Password."</strong></p>";
		$texto_mail = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Notificación mail</title>
    <style>
/* -------------------------------------
    GLOBAL
    A very basic CSS reset
------------------------------------- */
* {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    box-sizing: border-box;
    font-size: 14px;
}

img {
    max-width: 100%;
}

body {
    -webkit-font-smoothing: antialiased;
    -webkit-text-size-adjust: none;
    width: 100% !important;
    height: 100%;
    line-height: 1.6;
}

table td {
    vertical-align: top;
}

/* -------------------------------------
    BODY & CONTAINER
------------------------------------- */
body {
    background-color: #f6f6f6;
}

.body-wrap {
    background-color: #f6f6f6;
    width: 100%;
}

.container {
    display: block !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    /* makes it centered */
    clear: both !important;
}

.content {
    max-width: 600px;
    margin: 0 auto;
    display: block;
    padding: 20px;
}

/* -------------------------------------
    HEADER, FOOTER, MAIN
------------------------------------- */
.main {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 3px;
}

.content-wrap {
    padding: 20px;
}

.content-block {
    padding: 0 0 20px;
}

.header {
    width: 100%;
    margin-bottom: 20px;
}

.footer {
    width: 100%;
    clear: both;
    color: #999;
    padding: 20px;
}
.footer a {
    color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
    font-size: 12px;
}

/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #000;
    margin: 40px 0 0;
    line-height: 1.2;
    font-weight: 400;
}

h1 {
    font-size: 32px;
    font-weight: 500;
}

h2 {
    font-size: 24px;
}

h3 {
    font-size: 18px;
}

h4 {
    font-size: 14px;
    font-weight: 600;
}

p, ul, ol {
    margin-bottom: 10px;
    font-weight: normal;
}
p li, ul li, ol li {
    margin-left: 5px;
    list-style-position: inside;
}

/* -------------------------------------
    LINKS & BUTTONS
------------------------------------- */
a {
    color: #1ab394;
    text-decoration: underline;
}

.btn-primary {
    text-decoration: none;
    color: #FFF;
    background-color: #1ab394;
    border: solid #1ab394;
    border-width: 5px 10px;
    line-height: 2;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    display: inline-block;
    border-radius: 5px;
    text-transform: capitalize;
}

/* -------------------------------------
    OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
.last {
    margin-bottom: 0;
}

.first {
    margin-top: 0;
}

.aligncenter {
    text-align: center;
}

.alignright {
    text-align: right;
}

.alignleft {
    text-align: left;
}

.clear {
    clear: both;
}
/* PROPIOS */
	.tabla-pedido th {border-bottom: 1px solid #DDDDDD;}
	.tabla-pedido th, .tabla-pedido td {
		border-top: 1px solid #e7eaec;
		line-height: 1.42857;
		padding: 8px;
		vertical-align: top;
	}
	@media only screen and (max-width: 640px) {
		.no-phone{display:none;}
	}
/* -------------------------------------
    ALERTS
    Change the class depending on warning email, good email or bad email
------------------------------------- */
.alert {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    padding: 20px;
    text-align: center;
    border-radius: 3px 3px 0 0;
}
.alert a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
}
.alert.alert-warning {
    background: #f8ac59;
}
.alert.alert-bad {
    background: #ed5565;
}
.alert.alert-good {
    background: #1ab394;
}

/* -------------------------------------
    INVOICE
    Styles for the billing table
------------------------------------- */
.invoice {
    margin: 40px auto;
    text-align: left;
    width: 80%;
}
.invoice td {
    padding: 5px 0;
}
.invoice .invoice-items {
    width: 100%;
}
.invoice .invoice-items td {
    border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    font-weight: 700;
}

/* -------------------------------------
    RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
@media only screen and (max-width: 640px) {
    h1, h2, h3, h4 {
        font-weight: 600 !important;
        margin: 20px 0 5px !important;
    }

    h1 {
        font-size: 22px !important;
    }

    h2 {
        font-size: 18px !important;
    }

    h3 {
        font-size: 16px !important;
    }

    .container {
        width: 100% !important;
    }

    .content, .content-wrap {
        padding: 10px !important;
    }

    .invoice {
        width: 100% !important;
    }
}
}	
	</style>
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#4daaa3;padding:20px;"><h1 style="margin:0px;color:#FFF;text-align:center;">Solicitud recordatorio contrase&ntilde;a</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block" style="padding:20px;color:#333;">
	';
$texto_mail.= $msg;
$texto_mail.='</td></tr></table></td></tr></table></div></td></tr></table></body></html>';
	if ($Password!='') 
	{				
		mail($_POST['email'], utf8_encode('Solicitud Recordatorio Contraseña'), $texto_mail, $headers);
		print "<p><strong>Contrase&ntilde;a enviada a su correo electr&oacute;nico.</strong></p>";
	}
	else 
	{
		print "<p><strong>Correo electr&oacute;nico introducido no pertenece a ning&uacute;n Usuario.</strong></p>";		
	}
}
else 
{
	echo '          <p>
                        Introduce la dirección de correo electrónico y le mandaremos un mail con su contraseña.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="POST" action="recuperar_pass.php">
                                <div class="form-group">
                                    <input name="email" type="email" class="form-control" placeholder="Email" required="">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Enviar contraseña</button>

                            </form>
                        </div>
                    </div>';
}
?>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Semilla Proyectos Internet
            </div>
            <div class="col-md-6 text-right">
               <small>&copy; Dejavu</small>
            </div>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="/administra/js/jquery-2.1.1.js"></script>
    <script src="/administra/js/bootstrap.min.js"></script>
</body>

</html>