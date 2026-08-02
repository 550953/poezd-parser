<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

include '../simple_html_dom.php';

require_once '../api/connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");


$train = $_GET['name'];
$start = $_GET['start'];
$start_code = $_GET['startCode'];
$startTime = $_GET['startTime'];
$end =  $_GET['end'];
$end_code =  $_GET['endCode'];
$endTime = $_GET['endTime'];
$prib = $_GET['prib'];
$otpr = $_GET['otpr'];
$wait = $_GET['wait'];
$day = $_GET['day'];
$nameTech = $_GET['nameTech'];


function getCode($code, $link){
	$query = "SELECT * FROM `rx_station_info` WHERE `yandex_code`= '".$code."'";
	$result = mysqli_query($link, $query);
	$arrayRes = array();
	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $res = $row['code'];
          //  echo "CODE YES";
            }
    }
	return $res;
}


if(isset($train) && isset($start) && isset($startTime) && isset($start_code) && isset($end) && isset($endTime) && isset($end_code) && !isset($nameTech)){
$start_code_old = $start_code;
	$start_code = getCode($start_code, $link);
	if($start_code == ""){
    	echo "CODE NOT FOUND ".$start_code_old."\n";
	}

//echo "CODE NO ".$start_code;
	$end_code_old = $end_code;
	$end_code = getCode($end_code, $link);
	if($end_code == ""){
    	echo "CODE NOT FOUND ".$end_code_old."\n";
	}

	$query = "SELECT * FROM `tech_ways` WHERE `name` = '".$train."'  AND `start_code` = '".$start_code."' AND `startTime` = '".$startTime."' AND `end_code` = '".$end_code."' AND `endTime` = '".$endTime."'";
 // echo $query;

	$result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
            	$query = "DELETE  FROM `tech_stations` WHERE `id_train` = '".$id_train."'";
    			$result = mysqli_query($link, $query);
            	$result_arr = array(
    				"messege" => $result
                	
    			);
    			echo json_encode($result_arr);
           		
            }
       	 		
        }
    }else{
    	echo "WAY NOT FOUND ".$train."\n";
    }
    
    
}


if(isset($train) && isset($nameTech) && isset($prib) && isset($otpr)){
	$start_code = getCode($start_code, $link);
	$end_code = getCode($end_code, $link);
	//echo $start_code." ".$end_code;
	getTech($train, $start, $startTime, $end, $endTime, $start_code, $end_code, $nameTech, $prib, $otpr, $wait, $day,$link);
}

function getTech($train, $start, $startTime, $end, $endTime, $start_code, $end_code, $nameTech, $prib, $otpr, $wait, $day, $link){
	

	$query = "SELECT * FROM `tech_ways` WHERE `name` = '".$train."'  AND `start_code` = '".$start_code."' AND `startTime` = '".$startTime."' AND `end_code` = '".$end_code."' AND `endTime` = '".$endTime."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
           		
            }
       	 		addNewStation($id_train, $nameTech, $prib, $otpr, $wait, $day, $link);
            	$result_arr = array(
    				"messege" => "Add station"
                	
    			);
    			echo json_encode($result_arr);
        }
    }else{
    	$id_train = addNewTrain($train, $start, $startTime, $end, $endTime, $start_code, $end_code,  $link);
    	addNewStation($id_train, $nameTech, $prib, $otpr, $wait, $day, $link);
    	$result_arr = array(
    		"messege" => "Add station and train"
    	);
    	echo json_encode($result_arr);
    }

}


function addNewStation($id_train, $nameTech, $prib, $otpr, $wait, $day, $link){

	$query = "SELECT * FROM `tech_stations` WHERE `id_train` = '".$id_train."'  AND `name_tech` = '".$nameTech."' AND `prib` = '".$prib."' AND `otpr` = '".$otpr."' AND `wait` = '".$wait."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

	if($count == 0){
    	$query = "INSERT INTO `tech_stations`(`id`, `id_train`, `name_tech`, `prib`, `otpr`, `wait`,`day`) VALUES (NULL,'".$id_train."','".$nameTech."','".$prib."','".$otpr."','".$wait."','".$day."')";
    	mysqli_query($link, $query);
	}

}

function addNewTrain($train, $start, $startTime, $end, $endTime, $start_code, $end_code, $link){



	$query = "INSERT INTO `tech_ways`(`id`, `name`, `start`, `start_code`, `startTime`, `end`, `end_code`, `endTime`) VALUES (NULL,'".$train."','".$start."','".$start_code."','".$startTime."','".$end."','".$end_code."','".$endTime."')";
	if(isset($start_code)){
		mysqli_query($link, $query);
		$id_train = mysqli_insert_id($link);
    	return $id_train;
    }else{
    	return 0;
    }
	

}


// закрываем подключение
mysqli_close($link);
?>