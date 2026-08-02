<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$host = '127.0.0.1'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'K2ClMv77SQT3gF3k'; // пароль
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '256M');

require '/home/provodnik/public_html/api/poezd/yookassa/yookassa-sdk-php-master/lib/autoload.php'; 

use YooKassa\Client;

// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//if(isset($_GET['train']))
saveTrain($link);
refreshTrainsCache($link);
clearComment($link);
//refreshSubUser($link);


function refreshSubUser($link){



	
   
	$client = new Client();
  	$client->setAuth('477830','live_lWtNFGAbPNZVs2-Az1CXGUHXazHpNqwKMCyPYT-ej9s');
  	$receipts = $client->getPayments();
	$receipts = $receipts['items'];
	foreach ($receipts as $check) {
		$status = $check['status'];
    	
		$description = $check['description'];
    if($status == "succeeded" && isset($description) && strlen($description) > 0){
			$array = explode(" ", $description);
    		$uid = $array[0];
   
    		$date_sub =  $array[1];
    	
    		
    		
        	$date_sub_table = getDateSub($uid, $link);
    		$now = microtime(true) * 1000;
    		
    
//    echo $date_sub."</br>";
    
//     $today  = date('d.m.Y', $date_sub_table/1000);
//     $today1  = date('Y.m.d', $date_sub);
//     $d = strtotime($today);
//     $dd = strtotime($date_sub);
//     $result=($today<$date_sub);
//   echo $d." ".$today1." ".$dd;
    		if($now > $date_sub_table){
        		//echo "Update"."</br>";
           		updateUserSub($uid, $link, getDateSub($uid, $link));
            	//updateUserSub($uid, $link, $date_sub);
        	}
        }
	}
	
//	echo json_encode(array('receipts' => $receipts));





// $query = "SELECT * FROM `pay_yookassa` WHERE `status` = 'PAID' ORDER BY `pay_yookassa`.`id` DESC";
//  $result = mysqli_query($link, $query);
    
//     if($result){
//     	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//         	$uid = $row['uid_user'];
//         	$date_sub = $row['date_sub'];
//         	$date_sub_table = getDateSub($uid, $link);
//         //	echo $date_sub."==".$date_sub_table;
//         	if($date_sub > $date_sub_table){
//             	updateUserSub($uid, $link, $date_sub);
//             }else{
//             	deleteOrderPayInfo($uid, $link);
//             }
//         }
//     }
 }



function updateUserSub($uid, $link, $date_sub){
	$query = "SELECT * FROM `users` WHERE `uid` = '".$uid."'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        	$find = "SELECT * FROM `subscription` WHERE `id_user`=".$id_user;
            $result_find = mysqli_query($link, $find);

            if ($result_find) {
                while ($row_find = mysqli_fetch_array($result_find, MYSQLI_ASSOC)) {
					
                    $id_sub = $row_find['id'];
                    $query = "UPDATE `subscription` SET `date_end` = ".$date_sub." WHERE `id` = ".$id_sub;
    				$result = mysqli_query($link, $query);
                }
            }
        	
        
       }
    
    }
}

function deleteOrderPayInfo($uid, $link){
$query = "SELECT * FROM `pay_yookassa` WHERE `uid_user` = '".$uid."'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        	$pay = "DELETE FROM `pay_yookassa` WHERE `id` = '".$id."' AND `status` = 'PAID'";
        echo $pay;
        	$resultPay = mysqli_query($link, $pay);   	
        }
    	
    }
	
}
function getDateSub($uid, $link){
	$query = "SELECT * FROM `users` WHERE `uid` = '".$uid."'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_user = $row['id'];
        	$find = "SELECT * FROM `subscription` WHERE `id_user`=".$id_user;
            $result_find = mysqli_query($link, $find);

            if ($result_find) {
                while ($row_find = mysqli_fetch_array($result_find, MYSQLI_ASSOC)) {
					
                    $id_sub = $row_find['id'];
                    $date_end = $row_find['date_end'];
                	$day = 100 * 24 * 60 * 60 * 1000;
					$now = microtime(true) * 1000;
    				if($date_end > $now){
    					$times = $date_end + $day;
    				}else{
    					$times = $now + $day;
                    }
    				$res = date('Y.m.d H:i:s', $times / 1000);
               		return $times;
                }
            }else{
            	return false;
            }
        	
        
       }
    
    }
}




function refreshTrainsCache($link){
    $array = [];
    $result = mysqli_query($link, "SELECT name_train FROM trains_list ORDER BY name_train ASC");
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array[] = $row;
        }
    }
    $json = json_encode(['result' => $array, 'message' => 'true']);
    $tmpTrains = __DIR__ . '/trains_list_cache.json.tmp';
    file_put_contents($tmpTrains, $json, LOCK_EX);
    rename($tmpTrains, __DIR__ . '/trains_list_cache.json');
}

function clearComment($link){

	$query = "SELECT * FROM `rx_station_info`";
     echo $query;
    $result = mysqli_query($link, $query);
    
    if($result){
   // echo $query;
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $buff = str_replace('\n', '', $row['buffer']);
            $buff = explode(" | ", $row['buffer']);
      //  print_r($buff);
    		$buff = array_unique($buff);
        //  print_r($buff);
    		$buff = implode(" | ",$buff);
        //  print_r($buff);
    		$go = "UPDATE `rx_station_info` SET `buffer` = '".$buff."' WHERE `rx_station_info`.`id` = '".$row['id']."'";
       // echo $go;
    		mysqli_query($link, $go);
    	}
    }

$query = "SELECT * FROM `rx_station_info`";
   
    $result = mysqli_query($link, $query);
    
    if($result){
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
     //$comment = str_replace(' | ', '', $row['comment']);
    // $buffer = str_replace(' | ', '', $row['buffer']);
    		$buff = explode(" | ", $row['buffer']);
    $buff_new = array();
    foreach ($buff as $buffer){
    		 if(strcmp($row['comment'],$buffer) == 0){
           	//	echo $row['comment']."___".$buffer;
           		// if (strlen($buffer) > 0){
           		// $go = "UPDATE `rx_station_info` SET `buffer` = '' WHERE `rx_station_info`.`id` = '".$row['id']."'";
           		// //echo $go;
           		// 	mysqli_query($link, $go);
           		// }
               // 
           }else{
             	if (strlen($buffer) > 0){
             		$buff_new[] = $buffer;
             	}
            }
    
    
    	
    }
    $buff = implode(" | ",$buff_new);
          $go = "UPDATE `rx_station_info` SET `buffer` = '".$buff."' WHERE `rx_station_info`.`id` = '".$row['id']."'";
          //echo $go;
          mysqli_query($link, $go);
    }
    }
}


// function clearComment($link){

// 	$query = "SELECT * FROM `rx_station_info`";
   
//     $result = mysqli_query($link, $query);
    
//     if($result){
//     	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    
//             $buff = explode(" | ", $row['buffer']);
//     		$buff = array_unique($buff);
//     		$buff = implode(" | ",$buff);
//     		$go = "UPDATE `rx_station_info` SET `buffer` = '".$buff."' WHERE `rx_station_info`.`id` = '".$row['id']."'";
//     		mysqli_query($link, $go);
//     	}
//     }


// 	$query = "SELECT * FROM `rx_station_info`";
   
//     $result = mysqli_query($link, $query);
    
//     if($result){
//     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//     // $comment = str_replace(' | ', '', $row['comment']);
//     // $buffer = str_replace(' | ', '', $row['buffer']);
//            if(strcmp($row['comment'],$row['buffer']) == 0){
//            if (strlen($row['buffer']) > 0){
//                 $go = "UPDATE `rx_station_info` SET `buffer` = '' WHERE `rx_station_info`.`id` = '".$row['id']."'";
//            		mysqli_query($link, $go);
//            }
//                // 
//            }
//     }
//     }
	
// }

function saveTrain ($link){
    

	$array =  array();
    $query = "SET STATEMENT max_statement_time=30 FOR SELECT `new_station_list`.`id`, `new_station_list`.`name`, `new_station_list`.`code`, `new_station_list`.`yandex_code`, `new_station_list`.`gmt`,
    `new_station_list`.`country`, `new_station_list`.`contry_name`, `new_station_list`.`region`, `new_station_list`.`sity`,`new_station_list`.`direction`,`new_station_list`.`lng`,
    `new_station_list`.`lat`,`new_station_list`.`nodeId`,`new_station_list`.`codeUrban`, `rx_station_info`.`trush`,`rx_station_info`.`water`,`rx_station_info`.`market`,`rx_station_info`.`coal`,`rx_station_info`.`width`,`rx_station_info`.`sept`,
    `rx_station_info`.`slag`,`rx_station_info`.`pharmacy`,`rx_station_info`.`comment`  FROM `new_station_list` LEFT JOIN `rx_station_info`
    ON new_station_list.code=rx_station_info.code ORDER BY `name` ASC ";
    $result = mysqli_query($link, $query);
    
    if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           $array[] = $row;
    	}
   
    
   

   // print_r($array);
    $return_arr = array(  // Формируем массив
            "result" => $array,
            'message' => 'true'
        );
  
	$json_data = json_encode($return_arr);
	$tmpStation = __DIR__ . DIRECTORY_SEPARATOR . 'station_new_json_file.json.tmp';
file_put_contents($tmpStation, $json_data, LOCK_EX);
rename($tmpStation, __DIR__ . DIRECTORY_SEPARATOR . 'station_new_json_file.json');
 //   echo $json_data;
//     if($array != null){
//         $return_arr = array(  // Формируем массив
//             "result" => $array,
//             'message' => 'true'
//         );

//     }else{
//         $return_arr = array("message" => false);
//     }
}else{
   
    $return_arr = array("message" => false);
    
   // return $return_arr;
}



}
// закрываем подключение
mysqli_close($link);
?>