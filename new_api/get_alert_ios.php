<?php
require_once __DIR__ . '/../api/BsLogger.php';
$_bs_t = microtime(true);
header("Content-type: application/json");

$postData = file_get_contents("php://input");
$data = json_decode($postData, true);
$alert = $data["alert"] ?? null;

if ($alert != null) {
    $cache_file = __DIR__ . "/alert_ios_cache.json";
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 60) {
        BsLogger::event('info','cache','cache_hit',['file'=>'alert_ios_cache.json']);
        BsLogger::request('/api/poezd/parse/new_api/get_alert_ios.php',200,round((microtime(true)-$_bs_t)*1000,2));
        header("Cache-Control: public, max-age=60");
        readfile($cache_file);
    } else {
        require_once "../api/connection.php";
        $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
        mysqli_set_charset($link, "utf8");

        $text = ""; $on_off = "";
        $result = mysqli_query($link, "SELECT * FROM `message_ios`");
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $text = $row["text"];
                $on_off = $row["on_off"];
            }
        }
        mysqli_close($link);

        $json = json_encode(["alert" => $text, "settings" => $on_off]);
        file_put_contents($cache_file, $json);
        BsLogger::event('info','cache','cache_miss',['file'=>'alert_ios_cache.json']);
        BsLogger::request('/api/poezd/parse/new_api/get_alert_ios.php',200,round((microtime(true)-$_bs_t)*1000,2));
        header("Cache-Control: public, max-age=60");
        echo $json;
    }
}
