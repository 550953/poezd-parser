<?php
header("Content-type: application/json");
header("Cache-Control: public, max-age=600");

$cache_file = __DIR__ . "/trains_list_cache.json";

if (file_exists($cache_file)) {
    readfile($cache_file);
} else {
    // fallback: генерируем на лету
    require_once "connection.php";
    $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
    mysqli_set_charset($link, "utf8");
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);
    $train = $data["train"];
    if (isset($train) && $train != null) {
        $array = [];
        $result = mysqli_query($link, "SELECT `name_train` FROM `trains_list` ORDER BY `name_train` ASC");
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $array[] = $row;
            }
        }
        echo json_encode($array ? ["result" => $array, "message" => "true"] : ["message" => false]);
        mysqli_close($link);
    }
}
