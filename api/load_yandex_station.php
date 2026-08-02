<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$train = $data['station'];
// $train = $_GET['station'];
if($train != null){
$shipments = json_decode(file_get_contents("station_new_json_file.json"), true);

   // saveTrain($train, $link);
echo json_encode($shipments);
}
?>