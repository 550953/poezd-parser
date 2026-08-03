<?php
require_once __DIR__ . '/BsLogger.php';
$_bs_t = microtime(true);
header("Content-type: application/json");
header("Cache-Control: public, max-age=600");

$cache_file = __DIR__ . "/trains_list_cache.json";

if (file_exists($cache_file)) {
    BsLogger::event('info', 'cache', 'cache_hit', [
        'file'    => 'trains_list_cache.json',
        'size_kb' => round(filesize($cache_file) / 1024, 1),
    ]);
    BsLogger::request('/api/poezd/parse/api/load_train.php', 200, round((microtime(true) - $_bs_t) * 1000, 2));
    readfile($cache_file);
} else {
    // fallback: generate on the fly
    require_once "connection.php";
    $link = mysqli_connect($host, $user, $password, $database);
    if (!$link) {
        BsLogger::mysqlError('load_train.connect_failed', false);
        echo json_encode(["message" => false]);
        exit;
    }
    mysqli_set_charset($link, "utf8");
    $postData = file_get_contents("php://input");
    $data     = json_decode($postData, true);
    $train    = isset($data["train"]) ? $data["train"] : null;
    if (isset($train) && $train != null) {
        $array  = [];
        $result = mysqli_query($link, "SELECT `name_train` FROM `trains_list` ORDER BY `name_train` ASC");
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $array[] = $row;
            }
        } else {
            BsLogger::mysqlError('load_train.query_failed', $link);
        }
        $out = $array ? ["result" => $array, "message" => "true"] : ["message" => false];
        BsLogger::event('info', 'cache', 'cache_miss', [
            'file' => 'trains_list_cache.json',
            'rows' => count($array),
        ]);
        BsLogger::request('/api/poezd/parse/api/load_train.php', 200, round((microtime(true) - $_bs_t) * 1000, 2));
        echo json_encode($out);
        mysqli_close($link);
    }
}
