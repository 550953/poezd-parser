<?php
require_once __DIR__ . '/../api/BsLogger.php';
$_bs_t = microtime(true);
header('Content-type: application/json');

$postData = file_get_contents('php://input');
$data     = json_decode($postData, true);
$time     = isset($data['time']) ? $data['time'] : null;

if ($time !== null) {
    $timestamp = time() - 3 * 60 * 60;
    $timer     = date('d.m.Y H:i:s', $timestamp);
    $all_mass  = [
        'messege' => 'true',
        'time'    => $timer,
    ];
    BsLogger::request('/api/poezd/parse/new_api/get_time.php', 200, round((microtime(true) - $_bs_t) * 1000, 2));
    echo json_encode($all_mass);
} else {
    BsLogger::warn('get_time', 'missing_time_param', ['input_bytes' => strlen((string)$postData)]);
    echo json_encode(['messege' => 'false', 'error' => 'time_param_missing']);
}
