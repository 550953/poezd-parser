<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);


require_once '../api/connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

// $json = file_get_contents('https://api.rasp.yandex.net/v3.0/schedule/?apikey=d7b4a17b-32ba-4e77-9599-c29528ca1510&station=2064147&lang=ru_RU&format=json&transport_types=train&system=express&show_systems=esr');
// $obj = json_decode($json,true);
// $station = $obj['station']['code'];
// print_r($station);
// //echo $obj->station;
// // if(true){
	

	$query = "SELECT * FROM `station_list`  ORDER BY `station_list`.`id` ASC LIMIT 16000, 500";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_way = 0;
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$id = $row['id'];
            	$code = $row['code'];
            	$json = file_get_contents('https://api.rasp.yandex.net/v3.0/schedule/?apikey=d7b4a17b-32ba-4e77-9599-c29528ca1510&station='.$code.'&lang=ru_RU&format=json&transport_types=train&system=express&show_systems=esr');
				$obj = json_decode($json,true);
				$station = $obj['station']['code'];
            	$qw = "UPDATE `station_list` SET `yandex_code`='".$station."' WHERE `id` = '".$id."'";	
                mysqli_query($link, $qw);
            
            	//echo $from." ".$to;
            }
        }
    }

	

   
// 	$result_arr['messege'] = true;

// 	echo json_encode($result_arr);
// }

// закрываем подключение
mysqli_close($link);
?>