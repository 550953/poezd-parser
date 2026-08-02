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
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
ini_set('memory_limit', '256M');





$count = 0;
$query = "SELECT * FROM `new_station_list` WHERE `yandex_code`  LIKE 's%' ORDER BY `new_station_list`.`id` ASC LIMIT 11388, 500";
$result = mysqli_query($link, $query);
$count = mysqli_num_rows($result);
$k=0;
if($count > 0){
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    	$id = $row['id'];
    	$yandex_code = $row['yandex_code'];
    	$json = file_get_contents("https://api.rasp.yandex.net/v3.0/schedule/?apikey=d7b4a17b-32ba-4e77-9599-c29528ca1510&station=".$yandex_code."&transport_types=train,suburban&limit=1");
		$obj = json_decode($json);
		if(isset($obj)){
        	//print_r($obj);
        }else{
        	$k++;
        	$q = "DELETE FROM `new_station_list` WHERE `id`='".$id."'";
        	mysqli_query($link, $q);	
        	echo "<br> Delete ".$id." <br>";
        
        }
		
    }
    
}
echo "COUNT ".(500-$k);

// function hasCode($code, $link){
// $count = 0;
// 	$query = "SELECT * FROM `new_station_list` WHERE yandex_code='".$code."'";
// 	$result = mysqli_query($link, $query);
// 	$count = mysqli_num_rows($result);
    
// if($count > 0){
//     return true;
// }else{
// return false;
// }


// }


//$rt = json_decode($shipments, true);
//print_r($shipments['countries']);



?>