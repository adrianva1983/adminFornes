<?php
//$token = 'c6NLX3u9bwU:APA91bHWsyXXM5h7pNH7B5Z9s9VFeQhA-IWu_YGsswov8vPy3u5WvFfByHp6O0YnE9E17kdPiZky7LMA8OMaw9l9z4fWeEYgf6FTKywnW10zcfBBTnM15pnRGqGjnF5doFIwwTtHT4vf';
//Christian
//$token = 'fStyAtW4JOo:APA91bH3S1nM7behleOh8jsTxsF0iFJupXgBitXSQS57-DqPgtlvgPESVcVzPdaVzkeGXFpOiKopF0bau9lFAqiBuVlxrVn9TSYy6uNhEIVAX-EJUlw2KciGprzPQvGu1Au_YexY9OXI';
//ios
//$token = 'diGWADwhfLA:APA91bGgZbunq9Rvo5pCUrA55xAegMpfidt21dyJ6aJA6pNMuMSkXuGfGJZS5B7GkjukUQiPTdBmOEggnldieUIxTKRDTXrWR4HrtoTa2UMrhrYTiaBR5FJM9wZAz4ZT3-dFmcbyHNHN';
//$plataforma = 'ios';
//$plataforma_push
//$token_push 
//$titulo_push
//$body_push
//$destino_push
/*
$token = "eek6vzvezVc:APA91bGhXOjbtM9w8g_Y9yfN99lSaoxZ50Y-gq0fm7B1QRY05STlMb_tsrjE4HrAuID4vvmAi0X7aDxZ4L8vqA-6HjiYKCaAzTI8p4XcIikYztGRBHRwH5SW3WW-_q0YNqbPH6vSS2B8";
$plataforma = 'android';
$titulo = "abcd";
$body = "efg";
$destino = "pagina-home";
*/
function enviar_notificacion($token,$plataforma,$titulo,$body,$destino)
{
	//print "<hr>TOKEN:".$token."||PLATAFORMA:".$plataforma."||TITULO:".$titulo."||DESTINO:".$destino."<hr>";
	if ($plataforma=='ios') define( 'API_ACCESS_KEY', 'AAAAIjFJj5k:APA91bGtBoV1XLguNZhas2xzYWCx_GrBTD8Ukui0pRQKBTpzuEDPzHx1EgVPEFgEuK0SW8ZkC-tGLBs4zXnZASQETQSkQCIWzS_EPxNpKGAcp35tYwNFkR0bN1KQI8We-oDptyrR-UPf');
	else define( 'API_ACCESS_KEY', 'AAAAIjFJj5k:APA91bGtBoV1XLguNZhas2xzYWCx_GrBTD8Ukui0pRQKBTpzuEDPzHx1EgVPEFgEuK0SW8ZkC-tGLBs4zXnZASQETQSkQCIWzS_EPxNpKGAcp35tYwNFkR0bN1KQI8We-oDptyrR-UPf');
	// API access key from Google API's Console
	// prep the bundle
	$msg = array
	(	
		'title'		=> $titulo,
		'body'	=> $body,		
		'content_available' => true,		
		'vibrate'	=> true,
		'sound' => 'default',	
		'lights' => true	
	);	
	$data = array
	(
		'destino' => $destino,
		'title'		=> $titulo,
		'body'	=> $body
	);
	$fields = array
	(
		'notification'			=> $msg,
		'data' => $data,
		'to' 	=> $token
	); 
	$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);

	$ch = curl_init();
	//curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	return $result;
}
//print enviar_notificacion($token,$plataforma,$titulo,$body,$destino);
?>