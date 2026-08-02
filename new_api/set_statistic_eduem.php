<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once '../api/connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$user = $data['user'];

if($user != null){
$dateNow = date("Y-m-d");
$countShow = 0;
$query = "SELECT * FROM `statistic_eduem`  WHERE date = '".$dateNow."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id = $row['id'];
            	$countShow = $row['count'];
            	$countShow = $countShow + 1;
                $querySave = "UPDATE `statistic_eduem` SET `count` = '".$countShow."' WHERE `id` = '".$id."'";
            
    			$result = mysqli_query($link, $querySave);
            }
        }
    }else{
    
    	$querySave = "INSERT INTO `statistic_eduem`(`date`,`count`) VALUES ('".$dateNow."', 1)";
    	$countShow = $countShow + 1;
    	$result = mysqli_query($link, $querySave);
    	
    }
    $result_arr['messege'] = true;
	$result_arr['countShow'] = $countShow;
	echo json_encode($result_arr);
}


// закрываем подключение
mysqli_close($link);
?>