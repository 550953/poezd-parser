<?php
/**
 * Firebase Secure Token Proxy
 * Обходит блокировку securetoken.googleapis.com на российских сетях
 * (нужен для обновления Firebase ID токенов)
 *
 * Путь на сервере: /home/provodnik/public_html/api/poezd/parse/api/firebase_token.php
 *
 * Использование из iOS:
 *   POST https://poezd.androiddev.xyz/parse/api/firebase_token.php
 *   Body (form-encoded): grant_type=refresh_token&refresh_token=<TOKEN>
 */
header('Content-Type: application/json; charset=utf-8');

define('FIREBASE_API_KEY', 'AIzaSyCAX4fPukDcDa54QoZF27u732k-M2Vbg_U');
define('SECURETOKEN_URL', 'https://securetoken.googleapis.com/v1/token');

$body = file_get_contents('php://input');

$url = SECURETOKEN_URL . '?key=' . FIREBASE_API_KEY;

// Определяем Content-Type: JSON или form-encoded
$contentType = (!empty($body) && $body[0] === '{')
    ? 'application/json'
    : 'application/x-www-form-urlencoded';

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        "Content-Type: {$contentType}",
        'Accept: application/json',
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(503);
    echo json_encode(['error' => 'PROXY_CURL_ERROR: ' . $curlError]);
    exit;
}

http_response_code($httpCode ?: 200);
echo $response;
