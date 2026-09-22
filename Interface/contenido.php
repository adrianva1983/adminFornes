<?php
date_default_timezone_set("Europe/Madrid");
//Comprobamos el acceso
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=4; // Nivel de acceso para esta página.
if ($nivel_acceso < $_SESSION['usuario_nivel'])
{
 header ("Location: $redir?error_login=5");
 exit;
}
if ($_SERVER['HTTP_REFERER'] == "")
{
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/idiomas/contenido-".$_SESSION['idioma'].".conf");
?>
<script>
function cambiar_estado(id_tarea)
{
	$.ajax({
		type:'GET',
		timeout: 3000,
		url:'/administra/Interface/funciones/cambiar_estado_tarea.php?id_tarea='+id_tarea+'&key=gfdhg8423gjr90d9093hklfds7g2ls',
		success:function(res, textStatus, XMLHttpRequest) {
			res = JSON.parse(res);
			if (res.validacion == "ok") 
			{
				toastr.success(res.mensaje);
				toastr.options = {
				  "closeButton": true,
				  "debug": false,
				  "progressBar": true,
				  "preventDuplicates": false,
				  "positionClass": "toast-top-right",
				  "onclick": null,
				  "showDuration": "10000",
				  "hideDuration": "1000",
				  "timeOut": "500",
				  "extendedTimeOut": "1000",
				  "showEasing": "swing",
				  "hideEasing": "linear",
				  "showMethod": "fadeIn",
				  "hideMethod": "fadeOut"
				}
			} 
			else 
			{
				swal({
					title: "Hemos encontrado algún problema",
					text: res.mensaje,
					html: true,
					type: "error"
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {				
			swal({
				title: "Hemos encontrado algún problema",
				text: "No se ha podido cambiar el estado",
				html: true,
				type: "error"
			});
		}
	});		
}
</script>
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5><?php print $lang['fichar']?></h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
										<div class="row">
							<?php
							if ($_GET['fichar']==-1)
							{
								$requete = "SELECT * FROM `TareasFichar` WHERE `IdUsuario`=".$_SESSION['usuario_id']." AND `FechaEntrada`>='".date('Y-m-d')." 00:00:00' AND `FechaSalida` IS NULL";
								$entrada_salida = true;
								if ($result = mysqli_query($db, $requete))
								{
									$requete = "INSERT INTO `TareasFichar` (`IdUsuario`,`FechaEntrada`,`IpEntrada`) VALUES (".$_SESSION['usuario_id'].",'".date('Y-m-d H:i:s')."','".$_SERVER['REMOTE_ADDR']."');";								
									if (mysqli_query($db,$requete)) $mensaje.=$lang['mensaje_fichar_entrar']."<br/>";
									else $errores.=$lang['problemas_fichar']."<br/>";									
								}
								else
								{									
									$errores.=$lang['problemas_fichar']."<br/>";
								}
							}
							if ($_GET['fichar']>0)
							{
								$requete = "UPDATE `TareasFichar` SET `FechaSalida`='".date('Y-m-d H:i:s')."',`IpSalida`='".$_SERVER['REMOTE_ADDR']."' WHERE `Id`=".$_GET['fichar'];
								if (mysqli_query($db,$requete)) $mensaje.=$lang['mensaje_fichar_salir']."<br/>";
								else $errores.=$lang['problemas_fichar']."<br/>";
							}
							$requete = "SELECT * FROM `TareasFichar` WHERE `IdUsuario`=".$_SESSION['usuario_id']." AND `FechaEntrada`>='".date('Y-m-d')." 00:00:00'";
							
							$entrada_salida = true;
							$id_tarea_salir = '';
							if ($result = mysqli_query($db, $requete))
							{								
								while ($listado = mysqli_fetch_object($result))
								{									
									if ($listado->FechaSalida=='') 
									{
										$id_tarea_salir = $listado->Id;
										$entrada_salida = false;
									}
									else $entrada_salida = true;
									print '<span class="label label-primary">'.date('d/m/Y H:i:s',strtotime($listado->FechaEntrada));
									if ($listado->FechaSalida!='') print ' - '.date('d/m/Y H:i:s',strtotime($listado->FechaSalida));
									print '</span> ';
								}
							}
							if ($entrada_salida) print '<a href="?fichar=-1" class="btn btn-primary pull-right">'.$lang['fichar_entrar'].'</a>';
							else print '<a href="?fichar='.$id_tarea_salir.'" class="btn btn-primary pull-right">'.$lang['fichar_salir'].'</a>';
							if ($_SESSION['nivel_acceso']<1)
							{
								$time = 0;
								$requete = "SELECT * FROM `TareasFichar` WHERE `IdUsuario`=".$_SESSION['usuario_id']." AND `FechaEntrada`>='".date('Y-m-d')." 00:00:00'";								
								$id_tarea_salir = '';
								if ($result = mysqli_query($db, $requete))
								{								
									while ($listado = mysqli_fetch_object($result))
									{
										$time_entrada = strtotime($listado->FechaEntrada);
										if ($listado->FechaSalida!='') $time_salida = strtotime($listado->FechaSalida);
										else $time_salida = time();
										$time = $time + ($time_salida-$time_entrada);
									}
								}
								$time_horas = floor($time/(60*60));
								$time_minutos = floor($time/60)-($time_horas*60);
								if ($time_horas<8) print '<span class="label label-danger pull-right">'.$time_horas.'h '.$time_minutos.'m</span>';
								else if ($time_horas<9) print '<span class="label label-warning pull-right">'.$time_horas.'h '.$time_minutos.'m</span>';
								else print '<span class="label label-success pull-right">'.$time_horas.'h '.$time_minutos.'m</span>';
							}
							?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">						
							<?php
							$requete = "SELECT * FROM Usuarios WHERE Id=".$_SESSION['usuario_id'].";";							
							$result = mysqli_query($db,$requete);
							$listado = mysqli_fetch_object($result);
							if ($listado->FamiliaPresupuestos==1||$listado->RepresentantePresupuestos==1) 
							{
								$tiene_CRM = 1;
								print '<div class="col-lg-6">';
							}
							else 
							{
								$tiene_CRM = 0;
								print '<div class="col-lg-12">';
							}
							?>                            
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5><?php print $lang['para_hacer']?></h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">																			
									<?php print '<a class="btn btn-primary btn-block" href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_tarea&to_do=1"><i class="fa fa-plus"></i> '.$lang['nueva_para_hacer'].'</a>';?>
                                        <ul class="todo-list m-t small-list">
										<?php
											$requete = "SELECT * FROM `Tareas` WHERE (`Borrada`=0 OR `Borrada` IS NULL) AND `IdUsuarioAsignado`=".$_SESSION['usuario_id']." AND `ToDo`=1 AND `FechaCierre` IS NULL";																						
											if ($result = mysqli_query($db, $requete))
											{
												while ($listado = mysqli_fetch_object($result))
												{
													print '<li><a onclick="cambiar_estado('.$listado->Id.');" href="#" class="check-link"><i class="fa fa-square-o"></i> </a>';
													print '<a href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_tarea&origen=home&Id='.$listado->Id.'&pagina=&IdProyecto="><span class="m-l-xs">'.$listado->Nombre.'</span></a></li>';
												}												
											}
										?>
                                        </ul>
                                    </div>									
                                </div>
                            </div>
							
						<?php
						if ($tiene_CRM==1)
						{
						?>
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><?php print $lang['acciones_crm']?></h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content ibox-heading">
                                    <h3><?php print $lang['animo_vender'];?></h3>                                    
									<?php print '<a class="btn btn-primary btn-block" href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_accion_crm&resumida=1"><i class="fa fa-plus"></i> '.$lang['planificar_relacion_cliente'].'</a>';?>
                                </div>
                                <div class="ibox-content inspinia-timeline">
                                    
									<?php
									$requete = "SELECT * FROM `ClientesCRM` WHERE `FechaRealizada` IS NULL AND IdUsuario=".$_SESSION['usuario_id']." AND `FechaPlanificada`<='".date('Y-m-d H:i:s')."'";																		
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
											if (date('H:i:s',strtotime($listado->FechaPlanificada))!='00:00:00') print date('d/m/Y H:i:s',strtotime($listado->FechaPlanificada));
											else print date('d/m/Y',strtotime($listado->FechaPlanificada));
											if ($listado->FechaPlanificada>date('Y-m-d',strtotime('+1 day'))) print '<small class="text-navy">'.$lang['en_plazo'].'</small>';
											print '<br/><small class="text-danger">'.$lang['fuera_plazo'].'</small></div>';
											print '<div class="col-xs-7 content no-top-border">';
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
											print '<div class="col-xs-2 content no-top-border">';
											print '<a class="btn btn-sm btn-white" href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_accion_crm&Id='.$listado->Id.'" title="'.$lang['registrar_resultado'].'"><i class="fa fa-check"></i></a>';
											print '</div>';
											print '</div></div>';
										}
									}
									?>											
								</div>	
                                </div>
                            </div>							
                        </div>
						<?php
						}//TIENE CRM
						?>
						
        <div class="row">

        <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5><?php print $lang['tareas_involucracion'];?></h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-9 m-b-xs">
                    <div class="btn-group">
					<?php
						if ($_GET['asignadas']==1) print '<a class="btn btn-sm btn-primary" href="?asignadas=1">'.$lang['tareas_asignadas'].'</a>';
						else print '<a class="btn btn-sm btn-white" href="?asignadas=1">'.$lang['tareas_asignadas'].'</a>';
                        if ($_GET['responsable']==1) print '<a class="btn btn-sm btn-primary" href="?responsable=1">'.$lang['tareas_responsable'].'</a>';
						else print '<a class="btn btn-sm btn-white" href="?responsable=1">'.$lang['tareas_responsable'].'</a>';
                    ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group"><span class="input-group-btn"><a class="btn btn-sm btn-primary pull-right" href="/administra/Interface/herramienta.php?modulo=Gestion&herramienta=nueva_tarea"><?php print $lang['crear_tarea'];?></a>
                    </span></div>
                </div>
            </div>
            <div class="">
                <table class="table table-striped">
 					<?php 
					print "<tr><th>".$lang["acciones"]."</th><th>Id</th><th>".$lang["nombre"]."</th><th>".$lang["proyecto"]."</th><th>".$lang["fecha"]."</th><th>".$lang["fechaFin"]."</th><th>".$lang["creador"]."</th><th>".$lang["asignado"]."</th><th>".$lang["horas"]."</th><th>".$lang["ejecucion"]."</th></tr></thead>";?>
                    <tbody>
					<?php
					$requete = "SELECT * FROM `Tareas` WHERE `FechaCierre` IS NULL AND (`Borrada`=0 OR `Borrada` IS NULL)";
					if ($_GET['responsable']==1) $requete.= " AND `IdUsuarioCreador`=".$_SESSION['usuario_id'];
					else if ($_GET['asignado']==1) $requete.= " AND `IdUsuarioAsignado`=".$_SESSION['usuario_id'];
					else $requete.= " AND (`IdUsuarioCreador`=".$_SESSION['usuario_id']." OR `IdUsuarioAsignado`=".$_SESSION['usuario_id'].")";										
					if ($result = mysqli_query($db, $requete))
					{
						while ($listado = mysqli_fetch_object($result))
						{
							if ($listado->Pendiente==1) print "<tr class='warning'>";
							else print "<tr>";
							print "<td>";
							print '<div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cogs"></i></a>';
							print '<ul class="dropdown-menu">';
							if ($listado->FechaCierre=="") 
							{
								print "<li><a href=\"/administra/Gestion/funciones/hecho.php?Id=".$listado->Id."&IdProyectoReferencia=".$IdProyecto."\"><i class='fa fa-check-square-o'></i> ".$lang["hecho"]."</a></li>";
								print "<li><a href=\"/administra/Gestion/funciones/trabajar.php?Id=".$listado->Id."&IdProyectoReferencia=".$IdProyecto."&anticache=".time()."\">";
								if ($listado->EmpezadoTrabajar!=NULL) print "<i class='fa fa-clock-o'></i> ".$lang["parar"]."</a></li>";
								else print "<i class='fa fa-clock-o'></i> ".$lang["entrar"]."</a></li>";
							}
							print "<li><a href=\"/administra/Interface/herramienta.php?modulo=Gestion&herramienta=editar_tarea&origen=home&Id=".$listado->Id."&pagina=".$pagina."&IdProyecto=".$IdProyecto."\"><i class='fa fa-edit'></i> ".$lang["editar"]."</a></li>";
							print "<ul></div></td>";
							print "<td>".$listado->Id."</td>";							
							print "<td>";
							if ($listado->EmpezadoTrabajar!=NULL) print "<strong>";
							print $listado->Nombre;
							if ($listado->EmpezadoTrabajar!=NULL) print "</strong>";
							print "</td>";
							print "<td>";
							$requete2 = "SELECT * FROM `Proyectos` WHERE `Id`=".$listado->IdProyecto;							
							if ($result2 = mysqli_query($db, $requete2))
							{
								$listado2 = mysqli_fetch_object($result2);
								print $listado2->Nombre;
							}
							print "</td>";
							$tmp = explode(" ",$listado->Fecha);
							$tmp = explode("-",$tmp[0]);
							print "<td>".$tmp[2]."/".$tmp[1]."/".$tmp[0]."</td>";
							if ($listado->FechaFin!="")
							{
								$tmp = explode(" ",$listado->FechaFin);
								$tmp = explode("-",$tmp[0]);
								print "<td>".$tmp[2]."/".$tmp[1]."/".$tmp[0]."</td>";
							}
							else print "<td>".$lang["noAplica"]."</td>";
							print "<td>";
							$requete2 = "SELECT * FROM `Usuarios` WHERE `Id`=".$listado->IdUsuarioCreador;
							$result2 = mysqli_query($db, $requete2);
							$listado2 = mysqli_fetch_object($result2);
							if ($listado2->Foto!='') print '<img alt="image" width="38px" class="img-circle" alt="'.$listado2->Nombre.' '.$listado2->Apellidos.'" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
							else print $listado2->Nombre." ".$listado2->Apellidos;
							print "</td>";
							$requete2 = "SELECT * FROM `Usuarios` WHERE `Id` = '".$listado->IdUsuarioAsignado."'";
							$result2 = mysqli_query($db, $requete2);
							$listado2 = mysqli_fetch_object($result2);
							print "<td>";
							if ($listado2->Foto!='') print '<img alt="image" width="38px" class="img-circle" alt="'.$listado2->Nombre.' '.$listado2->Apellidos.'" src="/Imagenes/Perfiles/'.$listado2->Foto.'">';
							else print $listado2->Nombre." ".$listado2->Apellidos;
							print "</td>";
							print "<td>";
							printf ("%.2f", (($listado->TiempoDedicado)/60)/60);
							print "</td>";
							print "<td>";
							print '<span class="pie">'.$listado->PorcentajeEjecucion.'/100</span> '.number_format($listado->PorcentajeEjecucion,0,'','')."%";
							print "</td>";
							print "</tr>";
						}
					}
					?>
                    </tbody>
                </table>
            </div>

        </div>
        </div>        

						
						
						
						
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
			<?php
			$requete = "SELECT * FROM Usuarios WHERE Id=".$_SESSION['usuario_id'].";";
			$result = mysqli_query($db, $requete);
			$listado = mysqli_fetch_object($result);
			print "<h5><i class='fa fa-user'></i> ".$lang["titulo"]." - ".$listado->Nombre." ".$listado->Apellidos."</h5>";
			?>				
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">																														

<?php
$requete2 = "SELECT * FROM NivelesPermisos WHERE Nivel=".$listado->NivelAcceso.";";

if ($result2 = mysqli_query($db, $requete2))
{	
	$listado2 = mysqli_fetch_object($result2);
	print $lang["nivel"].": ".$listado2->Nombre."<br>";
	if ($listado->FechaCaducidad!="")
	{
		print $lang["caducidad"].": ".$listado->FechaCaducidad."<br>";
	}
}
// PERTENENCIA A GRUPOS

$requete2 = "SELECT * FROM PertenenciaGrupos,Grupos WHERE PertenenciaGrupos.IdUsuario=".$listado->Id." AND Grupos.Id=PertenenciaGrupos.IdGrupo;"; 
if ($result2 = mysqli_query($db, $requete2))
{
	print $lang["grupos"].": ";
	while($listado2 = mysqli_fetch_object($result2))
	{
		print "<img src=\"/administra/Imagenes/grupos.png\">".$listado2->Nombre."<br>";
	}
}

// RESPONSABILIDAD SOBRE SECCIONES POR USUARIO
$requete2 = "SELECT Secciones.IdPadre,Secciones.Id,Secciones.Titulo,Secciones.Path,Secciones.NomFich,Permisos.* FROM Permisos,Secciones WHERE IdUsuarioSuscrito=".$listado->Id." AND Secciones.Id=Permisos.IdSeccion;";
if ($result2 = mysqli_query($db, $requete2))
{
	print $lang["suscrito"].": <ul>";
	while($listado2 = mysqli_fetch_object($result2))
	{
		$requete3 = "SELECT Id,Titulo FROM Secciones WHERE Secciones.Id=".$listado2->IdPadre.";";	
		print "<li>";
		if ($listado2->Path=="") $enruta = $listado2->NomFich;
		else $enruta = $listado2->Path."/".$listado2->NomFich;
		print "<img src=\"/administra/Imagenes/secciones.png\"> <a href=\"/administra/Interface/herramienta.php?modulo=Carpetas&herramienta=secciones&seccion=".$listado2->IdSeccion."&ruta=".$enruta."\">".$listado2->Titulo."</a>";
		if ($result3 = mysqli_query($db, $requete3))
		{
			$listado3 = mysqli_fetch_object($result3);
			print " en ".$listado3->Titulo;
		}
		print "</li>";
	}
	print "</ul>";
}
?>
			</div>			
		</div>
	</div>
</div>
</div>