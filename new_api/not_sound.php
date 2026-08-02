<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

if($_GET['start'] != null && $_GET['end'] != null){

	$query = "SELECT `code`,`name` FROM `rx_station_info` LIMIT ".$_GET['start'].",".$_GET['end']."";
    $result = mysqli_query($link,$query);
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		hasSoundCode($row['code'], $row['name'],$link);
	}
}




function hasSoundCode($code, $name,$link){
	
    $filename = '../sounds/otpr5min_mp3/'.$code.'.mp3';
	if(!file_exists($filename)){
    	$query = "SELECT * FROM `not_sound` WHERE `code` = '".$code."' ";
    	$result = mysqli_query($link,$query);
		$count = mysqli_num_rows($result);
        if($count == 0){
        	$query_sound = "INSERT INTO `not_sound` (`id`, `code`, `name`) VALUES (NULL,'".$code."','".$name."')";
        	mysqli_query($link, $query_sound);
        }
    }else{
    	echo "has file = ".$code."<br>";
    }
    
}


?>