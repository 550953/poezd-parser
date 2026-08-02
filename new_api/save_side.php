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
$train = $data['id_train'];
$train_name = $data['train_name'];
$name_station = $data['name'];
$code_station =  $data['code'];
$side_none = $data['side_none'];
$side_left = $data['side_left'];
$side_right = $data['side_right'];
$side_over = $data['side_over'];
$trush = $data['trush'];
$water = $data['water'];
$market = $data['market'];
$coal= $data['coal'];
$width =  $data['width'];
$sept =  $data['sept'];
$slag =  $data['slag'];
if($data['sept'] == null){
	$sept = 0;
	$slag = 0;
}

$test = "";
$test2 = "";
// $train = $_GET['id_train'];
// $train_name = $_GET['train_name'];
// $name_station = $_GET['name'];
// $code_station =  $_GET['code'];
// $side_none = $_GET['side_none'];
// $side_left = $_GET['side_left'];
// $side_right = $_GET['side_right'];
// $side_over = $_GET['side_over'];
// $trush = $_GET['trush'];
// $water = $_GET['water'];
// $market = $_GET['market'];
// $coal= $_GET['coal'];
// $width =  $_GET['width'];

if($name_station != null){
	

	$query = "SELECT * FROM `rx_station_info`  WHERE `code` = '".$code_station."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$query = "UPDATE `rx_station_info` SET `trush` = '".$trush."', `water` = '".$water."', `market` = '".$market."', `coal` = '".$coal."', `width` = '".$width."', `sept` = '".$sept."', `slag` = '".$slag."' WHERE `id` = '".$row['id']."'";
				$result = mysqli_query($link, $query);
            }
        }
    }else{
    	$query = "INSERT INTO `rx_station_info` (`id`, `name`, `code`, `trush`, `water`, `market`, `coal`, `width`, `sept`, `slag`) VALUES 
        (NULL,'".$name_station."','".$code_station."','".$trush."','".$water."','".$market."','".$coal."','".$width."','".$sept."','".$slag."')";
    }

	$query = "SELECT * FROM `way_trains`  WHERE `number` = '".$train_name."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

	if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$id_train = $row['id'];
            	$query = "SELECT * FROM `way_station`  WHERE `id_way` = '".$id_train."' AND `code` = '".$code_station."'";
            	$test2 = $query;
				$resultSide = mysqli_query($link, $query);
            	$count = mysqli_num_rows($resultSide);
            	if($count > 0){
                	if ($resultSide) {
           				while ($row = mysqli_fetch_array($resultSide, MYSQLI_ASSOC)) {
            				$query = "UPDATE `way_station` SET `side_none`='".$side_none."',`side_left`='".$side_left."',`side_right`='".$side_right."',`side_over`='".$side_over."' WHERE `id` = '".$row['id']."'";
							$test = $query;
                        	mysqli_query($link, $query);
            			}
        			}
                	
                }else{
                	$query = "INSERT INTO `way_station`(`id`, `id_way`, `code`, `side_none`, `side_left`, `side_right`, `side_over`) 
                    VALUES (NULL,'".$id_train."','".$code_station."','".$side_none."','".$side_left."','".$side_right."','".$side_over."')";
                	mysqli_query($link, $query);
                }
            }
        }
    }

   
	$result_arr['messege'] = true;

	echo json_encode($result_arr);
}else{
	$result_arr['messege'] = true;
  
	echo json_encode($result_arr);
}

// закрываем подключение
mysqli_close($link);
?>