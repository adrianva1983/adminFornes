<?php
function getAccessToken($serviceAccountKeyFile) {
    $serviceAccount = json_decode(file_get_contents($serviceAccountKeyFile), true);

    $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $jwtPayload = base64_encode(json_encode([
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => time() + 3600,
        'iat' => time()
    ]));

    $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
    $jwtSignature = '';
    openssl_sign("$jwtHeader.$jwtPayload", $jwtSignature, $privateKey, 'sha256');
    $jwt = "$jwtHeader.$jwtPayload." . base64_encode($jwtSignature);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

function enviar_notificacion($token, $plataforma='', $titulo, $body, $destino) 
{
	$serviceAccountKeyFile = '/var/www/vhosts/fornescom.semillaproyectos.com/httpdocs/administra/Boletin/masymas-fornes-firebase-adminsdk-sco5g-8dd78dfe01.json';
    $accessToken = getAccessToken($serviceAccountKeyFile);
    if (!$accessToken) {
        return 'Error al obtener el token de acceso.';
    }

    $url = 'https://fcm.googleapis.com/v1/projects/masymas-fornes/messages:send';

    $body = str_replace('#br#','',$body);
    $body = str_replace('#b#','',$body);
    $body = str_replace('#/b#','',$body);
    $body = str_replace('#\/b#','',$body);

    // Estructura del mensaje para la API HTTP v1
    $message = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title'		=> html_entity_decode(utf8_encode($titulo),ENT_SUBSTITUTE,'UTF-8'),
		        'body'	=> html_entity_decode(utf8_encode($body),ENT_SUBSTITUTE,'UTF-8'),	
            ],
            'data' => [
                'destino' => $destino
            ]
        ]
    ];

    // Verificar la estructura del mensaje antes de enviarlo
    echo "Payload enviado:\n";
    print_r(json_encode($message, JSON_PRETTY_PRINT));

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) {
        return "Error HTTP $httpCode: $response";
    }

    return "Notificación enviada con éxito: $response";
}

// Ejemplo de uso
$serviceAccountKeyFile = 'masymas-fornes-firebase-adminsdk-sco5g-8dd78dfe01.json';
$token = "cgVTgBpZSHa0TH_8aJKQ1a:APA91bGvARbrksWybKfWEhPB2vqehgziZ6DrW58CMGOW6yt5eLf6m1XFwznCkgMzHYNST_2AgnyzV7Nk8saBpxOZ4gTAZyoGDJTity1ngCrfHnDs_NFKvaA";
$titulo = "Notificación de prueba";
$body = "EN SU PROXIMA COMPRA
Válido hasta el 14/11/2024#/b#";
$destino = "pagina-home";

$result = 	enviar_notificacion($token,$plataforma,$titulo,$body,$destino);
echo "Respuesta: " . $result;
?>
