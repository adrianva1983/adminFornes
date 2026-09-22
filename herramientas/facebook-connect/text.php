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
print_r($params_login);
$callback = $_SERVER['PHP_SELF']; // call back url ie http://www.example.com/abc.php/

$facebook = new Facebook($config);
$user_id = $facebook->getUser();
$access_token = $facebook->getAccessToken();
$facebook->setAccessToken($access_tokent);
?>
<html><head></head><body>
<?php
print $user_id."<br/>";
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
/*
//$session = $facebook->getSession();
$me = null;
if($session)
{
	try
	{
		$uid = $facebook->getUser();
		$me = $facebook->api('/me');
	}
	catch(FacebookApiException $e)
	{
		error_log($e);
	}
}
if($me)
{
	//DEVOLVEMOS INFO
	if($_REQUEST["fbs_".$this->ApplicationID]=="") print "Permission Disallow!";
	$PermissionCheck = split(",",$permission);
	$a = str_ireplace("\"","",$_REQUEST["fbs_".$config['appId']]);
	if(!$a) print "Permission Disallow!";	
	$user = json_decode(file_get_contents('https://graph.facebook.com/me?'.$a));
	$Result["UserID"]      = $user->id;
	$Result["Name"]        = $user->name;
	$Result["FirstName"]   = $user->first_name;
	$Result["LastName"]    = $user->last_name;
	$Result["ProfileLink"] = $user->link;
	$Result["ImageLink"] = "<img src='https://graph.facebook.com/".$user->id."/picture' />";
	$Result["About"]       = $user->about;
	$Result["Quotes"]      = $user->quotes;
	$Result["Gender"]      = $user->gender;
	$Result["TimeZone"]    = $user->timezone;
	if(in_array("email",$PermissionCheck))
	{
              $Result["Email"]       = $user->email;
	}
	if(in_array("user_birthday",$PermissionCheck))
	{
              $Result["Birthday"]    = $user->birthday;
	}
	if(in_array("user_location",$PermissionCheck))
	{
              $Result["PermanentAddress"]    = $user->location->name;
              $Result["CurrentAddress"]    = $user->hometown->name;
	}
	print_r($Result);
}
else
{
	//DEVOLVEMOS LOGIN
	$session = $facebook->getSession();
	$me = null;
	if($session)
	{
		try
		{
			$uid = $facebook->getUser();
			$me = $facebook->api('/me');
		}
		catch(FacebookApiException $e)
		{
			error_log($e);
		}
	}
          return "<!doctype html>
          <html xmlns:fb=\"http://www.facebook.com/2008/fbml\">
          <head>
          </head>
          <body>
          <div id=\"fb-root\"></div>
          <script>
            window.fbAsyncInit = function()
            {
                FB.init
                ({
                    appId   : '".$facebook->getAppId()."',
                    session : ".json_encode($session).",
                    status  : true, // check login status
                    cookie  : true, // enable cookies to allow the server to access the session
                    xfbml   : true // parse XFBML
                });
                FB.Event.subscribe('auth.login', function()
                {
                    window.location.reload();
                });
            };
          
          (function()
          {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
            }());
            </script>
            
            <fb:login-button perms=\"".$permission."\" onlogin='window.location=\"".$callBack."\";'>Connect</fb:login-button> 
          </body>
          </html>";
}
/*
  include "facebook.class.php";
  // Create our Application instance (replace this with your appId and secret).
  $id = "588413647871646"; // facebook applicationh ID.
  $sec = "8035aa872db95675e0526b03c382137c"; // facebook application secrate.
  $callback = $_SERVER['PHP_SELF']; // call back url ie http://www.example.com/abc.php/
  //$permission = "email, publish_stream, user_birthday,usear_about_me,user_location,read_friendlists,manage_pages"; // 0 for all class custome permission or explain ur permission like offline_status, email,sms etc http://developers.facebook.com/docs/authentication/permissions.
  $permission = "email, publish_stream,user_birthday,user_location,read_friendlists,manage_pages"; //usear_about_me
  $facebook = new facebook_login($id,$sec,$callback,$permission);

  if(is_array($facebook->FBLogin()))
  {
	print "HOLA1";
      print_r($facebook->FBLogin());      
  }
  else
  {
	print "HOLA2";
      echo $facebook->FBLogin();
  }
   print_r($facebook->InformationInfo());
*/
?>
</body>
</html>