<?php
//$json = file_get_contents('https://api.rasp.yandex.net/v3.0/stations_list/?apikey=d7b4a17b-32ba-4e77-9599-c29528ca1510&lang=ru_RU&format=json');
//$json_data = json_encode($json);
//print_r($json);
//file_put_contents(__DIR__ . DIRECTORY_SEPARATOR .'yandex_file.json', $json);
require_once 'connection.php'; // подключаем скрипт
//header('Content-type: application/json');
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '256M');

$shipments = json_decode(file_get_contents("id_st.json"), true);


$countries = $shipments['Routes'];
//print_r($countries);
 $k = 0;
foreach($countries as $country){
	$title = $country['name'];
	$expressCode = $country['expressCode'];
	$nodeId = $country['nodeId'];
	$suburbanCode = $country['suburbanCode'];
	// echo $k.". ".$title." - ".$expressCode." - ".$nodeId." - ".$suburbanCode."<br>";
	$k++;

	$query = "SELECT `code`, `id` FROM `new_station_list` WHERE `code` = $expressCode";
   
    $result = mysqli_query($link, $query);
    
    if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    
            $id = $row['id'];
        	$code = $row['code'];
    		$go = "UPDATE `new_station_list` SET `nodeId` = '".$nodeId."', `codeUrban` = '".$suburbanCode."' WHERE `new_station_list`.`id` = '".$id."'";
        	//echo $k.". ".$go."<br>";
    		mysqli_query($link, $go);
    	}
    }else{
    	echo "NOT HAVE"."<br>";
    }

}

//$rt = json_decode($shipments, true);
//print_r($shipments['countries']);



?>