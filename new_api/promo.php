<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header('Content-type: application/json');
require_once '../api/connection.php';  // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$myPromo = $data['promo'];
$enterCode = $data['enter_code'];

if(isset($myPromo) && isset($enterCode)){
	hasPromo($myPromo, $enterCode, $link);
}
function hasPromo($myPromo, $enterCode, $link){
	$result_arr = array();
	$queryMe =  "SELECT * FROM `users` WHERE `promo` = '".$myPromo."'";
	$resultMe = mysqli_query($link, $queryMe);
	if($resultMe){
    	while ($rowMe = mysqli_fetch_array($resultMe, MYSQLI_ASSOC)) {
            $id_my = $rowMe['id'];
        }
	}
	$queryEnter =  "SELECT * FROM `users` WHERE `promo` = '".$enterCode."'";
	$resultEnter = mysqli_query($link, $queryEnter);
	$countEnter = mysqli_num_rows($resultEnter);
	if($countEnter > 0){
	if($resultEnter){
    	while ($rowEnter = mysqli_fetch_array($resultEnter, MYSQLI_ASSOC)) {
            $id_enter = $rowEnter['id'];
        }
	}
	$queryPromo = "SELECT * FROM `promo` WHERE `my_id` = '".$id_my."' AND `enter_id` = '".$id_enter."'";
	$resultPromo = mysqli_query($link, $queryPromo);
	$count = mysqli_num_rows($resultPromo);
	if($count > 0){
    	$result_arr = array(  // Формируем массив
            'message' => false,
        	'text' => "Данный код уже был введен ранее."
        );
    }else{
    	$queryPromo = "SELECT * FROM `promo` WHERE `my_id` = '".$id_my."' ";
		$resultPromo = mysqli_query($link, $queryPromo);
		$countR = mysqli_num_rows($resultPromo);
    	if($countR > 0){
    		$result_arr = array(  // Формируем массив
            	'message' => false,
        		'text' => "Вы уже вводили промокод ранее."
        	);
    	}else{
        	$query = "INSERT INTO `promo`(`id`, `my_id`, `enter_id`, `promo`) VALUES (NULL,'".$id_my."','".$id_enter."','".$enterCode."')";
    		mysqli_query($link, $query);
    		updateUserSub($id_my, 1, $link);
    		updateUserSub($id_enter, 10, $link);
    		$result_arr = array(  // Формируем массив
            	'message' => true,
        		'text' => "Промо код успешно активирован!"
        	);
        }
    
    
    
    	
    }
    }else{
    	$result_arr = array(  // Формируем массив
            	'message' => false,
        		'text' => "Промокод не существует."
        	);
    }
 	echo json_encode($result_arr);
}

function updateUserSub($id_user, $dayAdd, $link){
	$find = "SELECT * FROM `subscription` WHERE `id_user`=".$id_user;
    $result_find = mysqli_query($link, $find);
    if ($result_find) {
    	while ($row_find = mysqli_fetch_array($result_find, MYSQLI_ASSOC)) {
			$id_sub = $row_find['id'];
            $date_end = $row_find['date_end'];
            $day = $dayAdd * 24 * 60 * 60 * 1000;
			setSub($date_end, $day, $id_sub, $link);
       }
    }
}


function setSub($date_end, $time, $id, $link){
    $now = microtime(true) * 1000;
    if($date_end > $now){
    	$times = $date_end + $time;
    }else{
    	$times = $now + $time;
    }
    $query = "UPDATE `subscription` SET `date_end` = ".$times." WHERE `id` = ".$id;
    //echo $query;
    $result = mysqli_query($link, $query);
    //echo $result;

}


// закрываем подключение
mysqli_close($link);
?>


