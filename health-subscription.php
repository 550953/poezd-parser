<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$started = microtime(true);
$response = array(
    'ok' => false,
    'service' => 'mysql-subscription'
);

try {
    require_once __DIR__ . '/api/connection.php';

    mysqli_report(MYSQLI_REPORT_OFF);
    $link = mysqli_init();
    mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 5);

    if (!mysqli_real_connect($link, $host, $user, $password, $database)) {
        throw new Exception('connection_failed');
    }

    mysqli_set_charset($link, 'utf8');
    $users = mysqli_query($link, 'SELECT 1 FROM users LIMIT 1');
    if ($users === false) {
        throw new Exception('users_query_failed');
    }
    mysqli_free_result($users);

    $subscriptions = mysqli_query($link, 'SELECT 1 FROM subscription LIMIT 1');
    if ($subscriptions === false) {
        throw new Exception('subscription_query_failed');
    }
    mysqli_free_result($subscriptions);
    mysqli_close($link);

    $response['ok'] = true;
} catch (Throwable $exception) {
    http_response_code(503);
    $response['stage'] = 'subscription';
}

$response['duration_ms'] = round((microtime(true) - $started) * 1000, 2);
echo json_encode($response);
