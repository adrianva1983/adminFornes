<?php
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
?>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message"><?php print $lang['bienvenido_texto'];?></span>
                </li>
				<li class="dropdown">				                    
						<?php
						$requete_msg = "SELECT * FROM `Mensajeria` WHERE `IdUsuarioDestino`=".$_SESSION['usuario_id']." AND (`EstadoDestino` IS NULL OR `EstadoDestino`<>'borrado') AND `Visto` IS NULL AND `UsuariosConzerto`=1";						
						if ($result_msg = mysqli_query($db, $requete_msg))						
						{
							print '<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-envelope"></i>';
							print ' <span class="label label-warning">'.mysqli_num_rows($result_msg).'</span></a>';
							print '<ul class="dropdown-menu dropdown-messages">';
							$primero = true;
							while ($listado_msg = mysqli_fetch_object($result_msg))
							{
								if ($primero) $primero = false;
								else print '<li class="divider"></li>';
								print '<li><div class="dropdown-messages-box">';
								$requete_msg2 = "SELECT * FROM `Usuarios` WHERE `id`=".$listado_msg->IdUsuarioOrigen;								
								$result_msg2 = mysqli_query($requete_msg2,$db);
								if ($result_msg2 = mysqli_query($db, $requete_msg2))								
								{
									$listado_msg2 = mysqli_fetch_object($result_msg2);
									if ($listado_msg2->Foto!='') print '<a class="pull-left"><img alt="'.$listado_msg2->Nombre.'" class="img-circle" src="/upload/'.$listado_msg->IdUsuarioOrigen.'/thumb_'.$listado_msg2->Foto.'"></a>';
								}
								$tmp_fecha = explode("-",$listado_msg->Fecha);
								$tmp_fecha_texto = $tmp_fecha[2]."/".$tmp_fecha[1]."/".$tmp_fecha[0];
								print '<div class="media-body">';
                                print '<strong>'.$listado_msg2->nombre.'</strong><br>'.$listado_msg->Titulo.'<br><small class="text-muted">'.$tmp_fecha_texto.'</small>';
                                print '</div></div></li>';                        
							}
							print '<li class="divider"></li><li><div class="text-center link-block"><a href="/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria"><i class="fa fa-envelope"></i> <strong>'.$lang['leer_todos_mensajes'].'</strong></a></div></li>';
							print '</ul>';
						}
						else print '<a class="count-info" href="/administra/Interface/herramienta.php?modulo=Boletin&herramienta=mensajeria"><i class="fa fa-envelope"></i> <span class="label label-warning">0</span></a>';						
                    ?>
                </li>
                
				<li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">0</span>
                    </a>
				</li>				
				<li>
                    <a href="/administra/index.php">
                        <i class="fa fa-sign-out"></i> <?php print $lang['desconectarse'];?>
                    </a>
                </li>
			</ul>
