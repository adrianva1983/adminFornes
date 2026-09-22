<?php
require_once($_SERVER['DOCUMENT_ROOT']."/herramientas/facebook-connect/src/facebook.php");
$config = array();
$config['appId'] = '588413647871646';
$config['secret'] = '8035aa872db95675e0526b03c382137c';
$config['cookie'] = true;
$params_login = array(
  scope => 'email, publish_stream,user_birthday,user_location,read_friendlists,user_likes',
  redirect_uri => "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
);
$callback = $_SERVER['PHP_SELF']; // call back url ie http://www.example.com/abc.php/
$facebook = new Facebook($config);
$user_id = $facebook->getUser();
$access_token = $facebook->getAccessToken();
$facebook->setAccessToken($access_tokent);
if ($user_id)
{	
	try
	{
		$fql = 'SELECT name from user where uid = ' . $user_id;
		$ret_obj = $facebook->api(array(
			'method' => 'fql.query',
			'query' => $fql,
		));
		$FacebookNombre = $ret_obj[0]['name'];
		$fql = 'SELECT email from user where uid = ' . $user_id;
		$ret_obj = $facebook->api(array(
			'method' => 'fql.query',
			'query' => $fql,
		));
		$FacebookEmail = $ret_obj[0]['email'];
		$fql= 'SELECT created_time,profile_section,type,page_id from page_fan where uid = '.$user_id.' and page_id=204369173062037'; //Miramos si es fan
		$ret_obj = $facebook->api(array(
			'method' => 'fql.query',
			'query' => $fql,
		));		
		$FacebookFanPage = $ret_obj[0]['page_id'];
		if ($FacebookFanPage!="204369173062037")
		{
			//Tiene que hacer click en botón me gusta
			print "Tienes que hacer antes en Me gusta";
			print "<div id=\"fb-root\"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = \"//connect.facebook.net/es_ES/all.js#xfbml=1&appId=588413647871646\";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));";
			print "FB.Event.subscribe('edge.create',function(response) {alert('You liked the URL: ' + response);});</script>";
			print "<div class=\"fb-like-box\" data-href=\"https://www.facebook.com/es.guestathome\" data-width=\"292\" data-show-faces=\"false\" data-header=\"false\" data-stream=\"false\" data-show-border=\"false\"></div>";
		}
		// FQL queries return the results in an array, so we have
		//  to get the user's name from the first element in the array.
		echo '<pre>Name: ' . $FacebookNombre . '</pre>';
		echo '<pre>Email: ' . $FacebookEmail . '</pre>';
		echo '<pre>FAN: ' .$FacebookFanPage . ' </pre>';
		//echo '<pre>Activities: ' . $ret_obj3[0]['user_activities'] . '</pre>';
		//$facebook->destroySession();
	}
	catch (FacebookApiException $e)
	{
		// If the user is logged out, you can have a 
		// user ID even though the access token is invalid.
		// In this case, we'll get an exception, so we'll
		// just ask the user to login again here.
		$login_url = $facebook->getLoginUrl(); 
		echo 'Please <a href="' . $login_url . '">login.</a>';
		error_log($e->getType());
		error_log($e->getMessage());
	}
}
else
{
	// No user, so print a link for the user to login
	$login_url = $facebook->getLoginUrl($params_login);
	echo 'Please <a href="' . $login_url . '">login.</a>';
}
?>