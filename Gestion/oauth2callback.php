<?php	
//set_include_path($_SERVER['DOCUMENT_ROOT'].'/administra/herramientas/google-api-php-client/src'); //librería adjunta
	set_include_path($_SERVER['DOCUMENT_ROOT'].'/administra/herramientas/google-api-php-client-services-master/src');	
	require_once $_SERVER['DOCUMENT_ROOT'].'/administra/herramientas/google-api-php-client-services-master/vendor/autoload.php';
	//require_once 'Google/autoload.php';
	//require_once $_SERVER['DOCUMENT_ROOT'].'/administra/herramientas/google-api-php-client/src/Google/Client.php';
	require_once 'Google/Service/Calendar.php';

//session_start();

$client = new Google_Client();
//$client->setAuthConfigFile('client_secret_103616175411004764065.json');
//$client->setAuthConfigFile($_SERVER['DOCUMENT_ROOT'].'/administra/Gestion/client_secret_597669233835-0bbt2419pkratvmp4o845v70tjl5a3qt.apps.googleusercontent.com.json');
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'].'/administra/Gestion/client_secret_1025172920718-p9d82cpv7vk5g828456rqss444obqdns.apps.googleusercontent.com.json');
$client->setRedirectUri('https://www.semillaproyectos.com/administra/Gestion/oauth2callback.php');//poner ruta correcta
$client->addScope(Google_Service_Calendar::CALENDAR);

//authorization code
//access token
//handleAuthorizeRequest
//validateAuthorizeRequest

//verifyResourceRequest
//getAccessTokenData

//grantAccessToken
//handleTokenRequest
if (!isset($_GET['code'])) 
{
	//NO NOS HAN DADO PERMISO
	
  //$auth_url = $client->createAuthUrl();  
  //header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));  
} 
else 
{
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");
	$requete = "UPDATE `Usuarios` SET `CodeTokenGoogleService`='".$_GET['code']."' WHERE `Id`=".$_GET['state'];	
	mysqli_query($db,$requete);
	require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
	$resultado = $client->authenticate($_GET['code']);	
	$_SESSION['access_token'] = $client->getAccessToken(); 
	$redirect_uri = '/administra/Gestion/nueva_accion_crm_2.php?access_token='.$_SESSION['access_token']['access_token'];// poner ruta correcta    
  
  
  	try
	{										
		
		$service = new Google_Service_Calendar($client); // llamar a la api de calendar		
				
		$event = new Google_Service_Calendar_Event;
		//$event->setId(IdCliente);
		$event->setSummary('prueba 123 titulo');		
		//$event->setStatus($Asignado);

		$start = new Google_Service_Calendar_EventDateTime();
		$start->setDateTime('2017-05-13T' . '9:00' . ':00.000+01:00');
		$start->setTimeZone('Europe/Madrid');
		$event->setStart($start);

		$end = new Google_Service_Calendar_EventDateTime();
		$end->setDateTime('2017-05-13T' . '9:15' . ':00.000+01:00');
		$end->setTimeZone('Europe/Madrid');
		$event->setEnd($end);

		$new_event = null;
		$new_event_id = "";

		//$new_event = $service->events->insert($CALENDAR_ID, $event);  // necesitamos id del calendario o sino probar lo de la siguiente linea
		$new_event = $service->events->insert('victor.estrada@semillaproyectos.com',$event);
		
		if($new_event!=null)
		{
			$new_event_id= $new_event->getId();
			//$event = $service->events->get($CALENDAR_ID, $new_event_id); //necesitamos id de calendario o sino probar lo de la siguiente linea
			$event = $service->events->get('victor.estrada@semillaproyectos.com',$new_event_id); 
			
			if ($event != null) 
			{
				echo "<br/>Inserted:";
				echo "<br/>EventID=".$event->getId();
				echo "<br/>Summary=".$event->getSummary();
				echo "<br/>Status=".$event->getStatus();
				//header("Location: nueva_accion_crm.php");
				exit();
			}
			else
			{
			   echo "No se ha podido obtener la información del evento";
			}			   
		}
		else
		{
			echo "No se ha podido insertar el evento";
		}
		
		header("Location: nueva_accion_crm.php");
	}
	catch (Google_ClientException $e) 
	{
		echo "Caught Google_ClientException:";		
	}
	catch (Google_ServiceException $e) 
	{
        echo "Caught Google_ServiceException:";
		echo "<pre>".print_r($e,true)."</pre>";
	}
	catch (Google_Auth_Exception $e)
	{
		echo "Caught Google_Auth_Exception:";
		unset($_SESSION['token']); //unset the session token
		echo "Token now invalid, please revalidate. <br>";	
	}
	catch (Google_Service_Exception $e)
	{
		echo "Caught Google_Service_Exception:";		
		echo "<pre>".print_r($e,true)."</pre>";
	}

  
  
  
  
  
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

?>