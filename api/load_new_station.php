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
$shipments = json_decode(file_get_contents("station_json_file.json"), true);

   // saveTrain($train, $link);
echo json_encode($shipments);
}



function saveTrain ($train, $link){
    $return_arr = array();


    $query = "SELECT `station_list`.`id`,`station_list`.`name`, `station_list`.`code`, `station_list`.`gmt`,`station_list`.`country`,`rx_station_info`.`trush`,`rx_station_info`.`water`,`rx_station_info`.`market`,`rx_station_info`.`coal`,`rx_station_info`.`width`,`rx_station_info`.`sept`,`rx_station_info`.`slag`  FROM `station_list` LEFT JOIN `rx_station_info` ON station_list.code=rx_station_info.code";
    $query = "SELECT `station_list`.`id`,`station_list`.`name`, `station_list`.`code`, `station_list`.`gmt`,`station_list`.`country` FROM `station_list` ORDER BY `name` ASC";
$result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;
    }
    
  

    if($array != null){
        $return_arr = array(  // Формируем массив
            "result" => $array,
            'message' => 'true'
        );

    }else{
        $return_arr = array("message" => false);
    }
}else{
   
    $return_arr = array("message" => false);
}



}
// закрываем подключение
mysqli_close($link);
?>