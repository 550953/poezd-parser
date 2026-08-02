<?php
header("Content-type: application/json");

$postData = file_get_contents("php://input");
$data = json_decode($postData, true);
$settings = $data["settings"] ?? null;

if ($settings != null) {
    $cache_file = __DIR__ . "/settings_cache.json";
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 60) {
        header("Cache-Control: public, max-age=60");
        readfile($cache_file);
    } else {
        require_once "../api/connection.php";
        $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
        mysqli_set_charset($link, "utf8");

        $text = "";
        $result = mysqli_query($link, "SELECT * FROM `settings`");
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $text = $row["value"];
            }
        }
        mysqli_close($link);

        $json = json_encode(["settings" => $text]);
        file_put_contents($cache_file, $json);
        header("Cache-Control: public, max-age=60");
        echo $json;
    }
}
