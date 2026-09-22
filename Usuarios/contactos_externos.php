<?php
//NECESITA:
//tipo: La red a la que se conecta.
//usuario: usuario de conexión a la red.
//password: contraseña de conexión a la red.
//PRODUCE: devuelve un array con los contactos. Nombre y Email
function contactos_externos($tipo,$usuario_contactos,$password_contactos){
	include($_SERVER['DOCUMENT_ROOT']."/herramientas/OpenInviter/openinviter.php");
	$inviter=new OpenInviter();
	$oi_services=$inviter->getPlugins();
	if ($tipo!="") 
	{
		if (($tipo=="hotmail")||($tipo=="terra")||($tipo=="yahoo")||($tipo=="youtube")) $plugType='email';
		else  $plugType='social';		
	}
	else $plugType = '';
	$step='get_contacts';
	$ers=array();$oks=array();$import_ok=false;$done=false;	
	if (($usuario_contactos!="")&&($password_contactos!=""))
	{	
		$inviter->startPlugin($tipo);
		$internal=$inviter->getInternalError();
		if ($internal) $ers['inviter']=$internal;
		elseif (!$inviter->login($usuario_contactos,$password_contactos))
		{
			$internal=$inviter->getInternalError();
			$ers['login']=($internal?$internal:"Fallo de acceso a su cuenta. Por favor, revise  su email y password e intentelo de nuevo más tarde !");
		}
		elseif (false===$contacts=$inviter->getMyContacts())
			$ers['contacts']="Imposible importar contactos !";
		else
		{
			$import_ok=true;
			$step='send_invites';
			$oi_session_id=$inviter->plugin->getSessionID();
			$message_box='';
		}
	return $contacts;
	}
}
?>