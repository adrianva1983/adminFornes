<?php
//VERSIÓN: v1.0 2013-1-20
//COMPATIBLE PHP 5.5
//DEFINICIÓN VARIABLES
$pagina = $_GET["pagina"];
$IdCliente = $_GET["IdCliente"];
$IdPresupuesto = $_GET["IdPresupuesto"];
$Estado = $_GET['Estado'];
if ($Estado == '') $Estado = 0;

//CARGAMOS JAVASCRIPT PARA GRÁFICO
print "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
// NIVEL MÍNIMO RESPONSABLE
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
 Print "No tiene permisos para acceder a este &aacute;rea";
 exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Accedemos a la base de datos
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Gestion/idiomas/crm-".$_SESSION['idioma'].".conf");
$Num_Pagina = 20;
$intervalo_inicial= $pagina * $Num_Pagina;
$requete = "SELECT * FROM `ClientesCRM` ";
$requete .= "WHERE 1=1";
$params = '';
if ($IdCliente!="") 
{
	$requete.=" AND IdCliente=".$IdCliente;
	$params.="&amp;IdCliente=".$IdCliente;	
}
if ($IdPresupuesto!="") 
{
	$requete.= " AND `IdPrepsupuesto`=".$IdPresupuesto;	
	$params.="&amp;IdPresupuesto=".$IdPresupuesto;	
}
if ($Estado==1)
{
	$requete.= " AND `FechaRealizada` IS NOT NULL";	
	$params.="&amp;Estado=".$Estado;	
}
else
{	
	$requete.= " AND `FechaRealizada` IS NULL";		
}
if ($_SESSION['usuario_nivel']>=2)//Si es coordinador o menos, que solo vea los suyos
{
	$requete.= " AND `IdUsuario`= ".$_SESSION['usuario_id'];	
}

$total_contenidos_pagina = mysqli_num_rows($result);
$requete .= " ORDER BY `FechaPlanificada` DESC LIMIT ".$intervalo_inicial.",".$Num_Pagina;

//Herramientas superiores
print '<div class="row">';
print "<div class=\"btn-group col-md-3\">";
print "<a class=\"btn btn-sm";
if ($Estado === 0) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=crm\">".$lang["pendientes"]."</a>";
print "<a class=\"btn btn-sm";
if ($Estado == 1) print " btn-primary";
else print " btn-white";
print "\" href=\"/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=crm&amp;Estado=1\">".$lang["ejecutadas"]."</a>";
print "</div>";
print "</div>";//row

if (isset($Num_Pagina))
{
	//PAGINACIÓN	
	print '<ul class="pagination">';
	if ($total_contenidos_pagina%$Num_Pagina==0) $max_pagina = intval($total_contenidos_pagina/$Num_Pagina)-1;
	else $max_pagina = intval($total_contenidos_pagina/$Num_Pagina);
	for ($i=0;($i<($max_pagina+1));$i++)
	{
		if ($pagina == $i) print '<li class="paginate_button active"><a href="#">'.$i.'</a></li>';
		else print "<li class=\"paginate_button\"><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=crm&pagina=".$i."\">".$i."</a></li>";
	}	
	print "</ul>";
	print '<a class="btn btn-primary btn-block" href="/administra/Interface/herramienta.php?modulo=Gestion&amp;herramienta=nueva_accion_crm"><i class="fa fa-plus"></i> '.$lang['planificar_crm'].'</a>';
}
print '<div class="row"><div class="col-lg-12"><div class="ibox"><div class="ibox-title"><h5>'.$lang['acciones_crm'].'</h5>';
if ($result = mysqli_query($db, $requete))
{	
	print '<div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div></div>';
	print '<div class="ibox-content inspinia-timeline">';									
	if ($result = mysqli_query($db, $requete))
	{
		while ($listado = mysqli_fetch_object($result))
		{
			print '<div class="timeline-item"><div class="row">';
			print '<div class="col-xs-3 date">';
			switch ($listado->Tipo)
			{
				case 1: //Reunion
					print '<i class="fa fa-brefcase"></i>';
				break;
				case 2: //Café
					print '<i class="fa fa-cofee"></i>';
				break;
				case 3: //Llamada
					print '<i class="fa fa-phone"></i>';
				break;
			}
			if ($Estado==1)
			{
				if (date('H:i:s',strtotime($listado->FechaRealizada))!='00:00:00') print date('d/m/Y H:i:s',strtotime($listado->FechaRealizada));
				else print date('d/m/Y',strtotime($listado->FechaRealizada));
			}
			else
			{
				if (date('H:i:s',strtotime($listado->FechaPlanificada))!='00:00:00') print date('d/m/Y H:i:s',strtotime($listado->FechaPlanificada));
				else print date('d/m/Y',strtotime($listado->FechaPlanificada));
				if ($listado->FechaPlanificada>date('Y-m-d',strtotime('+1 day'))) print '<br/><small class="text-navy">'.$lang['en_plazo'].'</small>';
				else print '<br/><small class="text-danger">'.$lang['fuera_plazo'].'</small>';
			}
			print '</div>';
			print '<div class="col-xs-4 content">';
			print '<p class="m-b-xs"><strong>'.$listado->Titulo.'</strong></p>';
			if ($listado->IdPresupuesto!='')
			{
				$requete2 = "SELECT * FROM `Presupuestos` WHERE `Id`=".$listado->IdPresupuesto;
				
				print '<p>';
				if ($result2 = mysqli_query($db, $requete2))
				{
					$listado2 = mysqli_fetch_object($result2);
					if ($listado2->TituloSolicitud!='') print $listado2->TituloSolicitud.". ";
					else if ($listado2->TituloEnvio!='') print $listado2->TituloEnvio.". ";
				}
				print '<br/><a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_presupuesto&Id='.$listado->IdPresupuesto.'">'.$lang['ver_editar_presupuesto'].'</a>';
				if ($listado2->Telefono!=''||$listado2->Email!='')
				{
					print '<div class="btn-group col-md-12">';
					if ($listado2->Telefono!='') print '<a class="btn btn-sm btn-white" href="tel:'.$listado2->Telefono.'">'.$listado2->Telefono.'</a>';
					if ($listado2->Email!='') print ' <a class="btn btn-sm btn-white" href="mailto:'.$listado2->Email.'">'.$listado2->Email.'</a>';
					print '</div>';
				}
				print '</p>';
			}
			if ($listado->IdCliente!='') print '<p><a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_cliente&Id='.$listado->IdCliente.'">'.$lang['ver_editar_cliente'].'</a></p>';
			print '</div>';
			print '<div class="col-xs-5 content">';
				print $listado->Notas;
			print '</div>';
			print '<div class="col-xs-1">';
			print '<a class="btn btn-sm btn-white" href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_accion_crm&Id='.$listado->Id.'" title="'.$lang['registrar_resultado'].'"><i class="fa fa-check"></i></a>';
			print '</div>';
			$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdUsuario;
			
			if ($result2 = mysqli_query($db, $requete2))
			{
				$listado2 = mysqli_fetch_object($result2);			
				if ($listado2->Foto!='')
				{
					print '<div class="pull-right">';
					print '<img alt="image" width="38px" class="img-circle" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
					print '</div>';
				}
			}
			print '</div></div>';
		}
	}
	print '</div>';
}
print  "</div></div></div>";
?>
