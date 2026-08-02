<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$host = '127.0.0.1'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'K2ClMv77SQT3gF3k'; // пароль
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//if(isset($_GET['train']))
saveTrain($link);

function saveTrain ($link){
    

$array =  array();
    $query = "SELECT `station_list`.`id`,`station_list`.`name`, `station_list`.`code`, `station_list`.`gmt`,`station_list`.`country`,`rx_station_info`.`trush`,`rx_station_info`.`water`,`rx_station_info`.`market`,`rx_station_info`.`coal`,`rx_station_info`.`width`,`rx_station_info`.`sept`,`rx_station_info`.`slag`,`rx_station_info`.`pharmacy`,`rx_station_info`.`comment`  FROM `station_list` LEFT JOIN `rx_station_info` ON station_list.code=rx_station_info.code ORDER BY `name` ASC";
    echo $query;
//$query = "SELECT `station_list`.`id`,`station_list`.`name`, `station_list`.`code`, `station_list`.`gmt`,`station_list`.`country`  FROM `station_list` ORDER BY `name` ASC";
    $result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;
    }
   // print_r($array);
    $return_arr = array(  // Формируем массив
            "result" => $array,
            'message' => 'true'
        );
  
	$json_data = json_encode($return_arr);
	file_put_contents(__DIR__ . DIRECTORY_SEPARATOR .'station_json_file.json', $json_data);
 //   echo $json_data;
//     if($array != null){
//         $return_arr = array(  // Формируем массив
//             "result" => $array,
//             'message' => 'true'
//         );

//     }else{
//         $return_arr = array("message" => false);
//     }
}else{
   
    $return_arr = array("message" => false);
    
   // return $return_arr;
}



}
// закрываем подключение
mysqli_close($link);
?>