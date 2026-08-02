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

$query = "SELECT * FROM `new_station_list` ORDER BY `id` ASC";

$result = mysqli_query($link, $query);
    
if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    	$id = $row['id'];
    	$yandex_code = $row['yandex_code'];
    	$queryOld = "SELECT * FROM `station_list` WHERE `yandex_code` = '".$yandex_code."'";
    //	echo $queryOld."<br>";
		$resultOld = mysqli_query($link, $queryOld);
    	if($resultOld){
        	while ($rowOld = mysqli_fetch_array($resultOld, MYSQLI_ASSOC)) {
            	$code = $rowOld['code'];
            	$gmt = $rowOld['gmt'];
            	$qe = "UPDATE `new_station_list` SET `code`='".$code."', `gmt`='".$gmt."' WHERE `id`= '".$id."'";
            	mysqli_query($link, $qe);
            }
        }
    
    }
}
?>