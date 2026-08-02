<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

include '../simple_html_dom.php';

require_once '../api/connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
if(isset($data['name']))
	$train = $data['name'];
if(isset($data['start']))
	$start = $data['start'];
if(isset($data['start_code']))
	$start_code = $data['start_code'];
if(isset($data['startTime']))
	$startTime = $data['startTime'];
if(isset($data['end']))
	$end =  $data['end'];
if(isset($data['end_code']))
	$end_code =  $data['end_code'];
if(isset($data['endTime']))
	$endTime = $data['endTime'];
if(isset($data['nameTech']))
	$nameTech = $data['nameTech'];
if(isset($data['prib']))
	$prib = $data['prib'];
if(isset($data['otpr']))
	$otpr = $data['otpr'];
if(isset($data['wait']))
	$wait = $data['wait'];
if(isset($data['day']))
	$day = $data['day'];
if(isset($data['getInfo']))
	$getInfo = $data['getInfo'];
if(isset($data['del']))
	$del = $data['del'];

// $train = $_GET['name'];
// $start = $_GET['start'];
// $start_code = $_GET['start_code'];
// $startTime = $_GET['startTime'];
// $end =  $_GET['end'];
// $end_code =  $_GET['end_code'];
// $endTime = $_GET['endTime'];
// $getInfo = $_GET['getInfo'];
// $prib = $_GET['prib'];
// $otpr = $_GET['otpr'];
// $wait = $_GET['wait'];
// $del = $_GET['del'];
// $nameTech = $_GET['nameTech'];

if(isset($train) && isset($nameTech) && isset($del)){
	$query = "SELECT * FROM `tech_ways` WHERE name = '".$train."'  AND start_code = '".$start_code."' AND startTime = '".$startTime."' AND end_code = '".$end_code."' AND endTime = '".$endTime."'";
  	$result_arr = array(
                    	"messege" => false
                    	
                	);
	$result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
           		$query1 = "SELECT * FROM `tech_stations` WHERE id_train = '".$id_train."' AND name_tech = '".$nameTech."' AND prib = '".$prib."' AND otpr = '".$otpr."' AND wait = '".$wait."'";
            	
				$result1 = mysqli_query($link, $query1);
            	$count = mysqli_num_rows($result1);
            	if($count > 0){
					if ($result1) {
                     	while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                        	$qwu = "DELETE FROM `tech_stations` WHERE id = ".$row['id']."";
                        	//$res = mysqli_query($link, $qwu);
            			}
                    	$result_arr = array(
                        	"messege" => true
                    	);
                    	
    				}
                	
            	}
            }
        }
    }
	echo json_encode($result_arr);

}


if(isset($train) && isset($getInfo)){


    $arr_st = addWayTrains($train, $start, $end, $start_code, $end_code,  $link);
    $query = "SELECT * FROM `tech_ways` WHERE `name` = '".$train."'  AND `startTime` = '".$startTime."' AND `endTime` = '".$endTime."' AND `start_code` = '".$start_code."' AND `end_code` = '".$end_code."'";
	//$query = "SELECT * FROM `tech_ways` WHERE `name` = '".$train."'  AND `start` = '".$start."' AND `startTime` = '".$startTime."' AND `end` = '".$end."' AND `endTime` = '".$endTime."'";
    //echo $query;

$result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
            	if(isset($start_code) && isset($end_code)){
                	$update = "UPDATE `tech_ways` SET `start_code`='".$start_code."',`end_code`='".$end_code."' WHERE `id` = '".$id_train."'";
                	//mysqli_query($link, $update);
                }
            
            
            
           		
            }
        $arr = getTechStation($id_train, $link);
            	$result_arr = array(
    				"messege" => true,
                	"data" => $arr,
                	"side" => $arr_st
                    //"update"=> $update
    			);
    			echo json_encode($result_arr);
        }
    }else{
    			$result_arr = array(
    				"messege" => true,
                	"side" => $arr_st
                	
    			);
    			echo json_encode($result_arr);
    	
    }
}
function addWayTrains($train, $start, $end, $start_code, $end_code, $link){
	$resultArr = array();
    $ful_name = $start." - ".$end;
	$query = "SELECT * FROM `way_trains` WHERE `number` = '".$train."' AND `code_from` = '".$start_code."'  AND `code_to` = '".$end_code."'";

    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	$id_train = 0;
	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id_train = $row['id'];
           		addStatistic($id_train, $link);
            	$resultArr = getSideStation($id_train, $link);
            }
        }
    	return $resultArr;
    }else if(isset($train) && isset($start_code)){
    	$query = "INSERT INTO `way_trains` (`id`, `number`, `full_name`, `code_from`, `code_to`) VALUES (NULL,'".$train."','".$ful_name."','".$start_code."','".$end_code."')";
		//mysqli_query($link, $query);
    	$id_train = mysqli_insert_id($link);
    	addStatistic($id_train, $link);
    	return $resultArr;
    }
}

function getSideStation($id_train, $link){
    $resultArr = array();
	$query = "SELECT * FROM `way_station` WHERE `id_way` = '".$id_train."'";
	//return $query;
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
   
           		$resultArr[] = $row;
            }
        return $resultArr;
        }
    
    }else{
    	return $resultArr;
    }

}


function addStatistic($id_train, $link){
	$query = "SELECT * FROM `way_statistic` WHERE `id_way` = '".$id_train."' AND `date` = CURRENT_DATE()";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$today = date("Y-m-d");
            	$date = $row['date'];
                $id = $row['id'];
            	$count = $row['count'];
                $count++;
            	$query = "UPDATE `way_statistic` SET `count`='".$count."' WHERE `id`='".$id."'";
                mysqli_query($link, $query);
            	// if($today == $date){
            	// $query = "UPDATE `way_statistic` SET `count`='".$count."' WHERE `id`='".$id."'";
            	// mysqli_query($link, $query);
            	// }
					// else{
					// $query = "INSERT INTO `way_statistic`(`id`, `id_way`, `date`, `count`) VALUES (NULL,'".$id_train."','".$today."',1)";
					// mysqli_query($link, $query);
					// }
                
       
            	
            }
        }
    }else{
    	$today = date("Y-m-d");
    	$query = "INSERT INTO `way_statistic`(`id`, `id_way`, `date`, `count`) VALUES (NULL,'".$id_train."','".$today."',1)";
		mysqli_query($link, $query);
    }

}

function getTechStation($id_train, $link){
	$query = "SELECT * FROM `tech_stations` WHERE `id_train` = '".$id_train."'";
	$result = mysqli_query($link, $query);
	$arrayRes = array();
	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $arrayRes[] = $row;
            }
    }
	return $arrayRes;
}

if(isset($train) && isset($nameTech) && !isset($getInfo) && !isset($del)){
	

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
    				"messege" => true
                	
    			);
    			echo json_encode($result_arr);
        }
    }else{
    	$id_train = addNewTrain($train, $start, $startTime, $end, $endTime, $start_code, $end_code,  $link);
    	addNewStation($id_train, $nameTech, $prib, $otpr, $wait, $day, $link);
    	$result_arr = array(
    		"messege" => true
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
    	//mysqli_query($link, $query);
	}

}

function addNewTrain($train, $start, $startTime, $end, $endTime, $start_code, $end_code, $link){



	$query = "INSERT INTO `tech_ways`(`id`, `name`, `start`, `start_code`, `startTime`, `end`, `end_code`, `endTime`) VALUES (NULL,'".$train."','".$start."','".$start_code."','".$startTime."','".$end."','".$end_code."','".$endTime."')";
	if(isset($start_code)){
		//mysqli_query($link, $query);
    $id_train = 0;
		$id_train = mysqli_insert_id($link);
    	return $id_train;
    }else{
    	return 0;
    }
	

}


// закрываем подключение
mysqli_close($link);
?>