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

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$word = $data['search'];

//$word = $_GET['w'];
if(isset($word)){
	search($word, $link);
}

function search($qwery, $link){
$query = "SELECT * FROM `new_tech_station` WHERE name LIKE '".$qwery."%'";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;

    }
   // print_r($array);
if(isset($array)){
    $return_arr = array(  // Формируем массив
            "result" => $array,
            'message' => 'true'
        );
}else{
	$return_arr = array(  // Формируем массив
           
            'message' => 'false'
        );
}

echo json_encode($return_arr);
}


function hasCode($code, $link){
$count = 0;
	$query = "SELECT * FROM `new_station_list` WHERE yandex_code='".$code."'";

	$result = mysqli_query($link, $query);
	$count = mysqli_num_rows($result);
    
if($count > 0){
    return true;
}else{
return false;
}


}


//$rt = json_decode($shipments, true);
//print_r($shipments['countries']);



?>