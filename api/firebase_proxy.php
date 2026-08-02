<?php
/**
 * Firebase Authentication REST API Proxy
 * Обходит блокировку identitytoolkit.googleapis.com на российских сетях
 *
 * Путь на сервере: /home/provodnik/public_html/api/poezd/parse/api/firebase_proxy.php
 *
 * Использование из iOS:
 *   POST https://poezd.androiddev.xyz/parse/api/firebase_proxy.php?action=signInWithPassword
 *   POST https://poezd.androiddev.xyz/parse/api/firebase_proxy.php?action=signUp
 *   POST https://poezd.androiddev.xyz/parse/api/firebase_proxy.php?action=signInWithIdp
 */
header('Content-Type: application/json; charset=utf-8');

define('FIREBASE_API_KEY', 'AIzaSyCAX4fPukDcDa54QoZF27u732k-M2Vbg_U');
define('FIREBASE_BASE_URL', 'https://identitytoolkit.googleapis.com/v1/accounts');

$ALLOWED_ACTIONS = [
    'signInWithPassword',    // вход email + пароль
    'signUp',                // регистрация email + пароль
    'lookup',                // получить данные пользователя по idToken
    'sendOobCode',           // сброс пароля / верификация email
    'resetPassword',         // применить код сброса пароля
    'update',                // обновить имя/email/пароль
    'signInWithIdp',         // Google/Apple через Firebase (exchange OAuth token -> UID)
    'signInWithCustomToken', // кастомный токен
];

$action = isset($_GET['action']) ? trim($_GET['action']) : '';

if (!in_array($action, $ALLOWED_ACTIONS, true)) {
    http_response_code(400);
    echo json_encode(['error' => ['code' => 400, 'message' => 'INVALID_ACTION']]);
    exit;
}

$body = file_get_contents('php://input');
if (empty($body)) {
    http_response_code(400);
    echo json_encode(['error' => ['code' => 400, 'message' => 'EMPTY_BODY']]);
    exit;
}

$url = FIREBASE_BASE_URL . ':' . $action . '?key=' . FIREBASE_API_KEY;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_ENCODING       => 'gzip',
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(503);
    echo json_encode(['error' => ['code' => 503, 'message' => 'PROXY_CURL_ERROR', 'details' => $curlError]]);
    exit;
}

if ($response === false || $response === '') {
    http_response_code(502);
    echo json_encode(['error' => ['code' => 502, 'message' => 'EMPTY_RESPONSE_FROM_FIREBASE']]);
    exit;
}

http_response_code($httpCode ?: 200);
echo $response;
