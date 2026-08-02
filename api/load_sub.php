<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');

// === LOCK: защита от параллельного запуска ===
$lockFile = '/tmp/load_sub.lock';
if (file_exists($lockFile) && (time() - filemtime($lockFile)) < 300) {
    exit("Already running\n");
}
file_put_contents($lockFile, getmypid());
register_shutdown_function(function() { @unlink('/tmp/load_sub.lock'); });
// === END LOCK ===

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

refreshSubUser($link);


function refreshSubUser($link){
	$client = new Client();
  	$client->setAuth('477830','live_lWtNFGAbPNZVs2-Az1CXGUHXazHpNqwKMCyPYT-ej9s');

  	// ФИКС: только платежи за последние 24 часа, не все за всё время
  	$receipts = $client->getPayments([
	    'created_at.gte' => date('Y-m-d\TH:i:s\Z', strtotime('-24 hours')),
	    'status' => 'succeeded',
	    'limit' => 100
	]);
	$receipts = $receipts['items'];
	foreach ($receipts as $check) {
		$status = $check['status'];
		$description = $check['description'];
        if($status == "succeeded" && isset($description) && strlen($description) > 0){
			$array = explode(" ", $description);

			// ФИКС: проверяем что в описании есть все нужные части
			if (count($array) < 3) { continue; }

    		$uid = $array[0];
    		$date_sub = $array[1]." ".$array[2];
    		
        	$date_sub_table = getDateSubOrig($uid, $link);
    		$now = microtime(true) * 1000;
    		
    		// ФИКС: проверяем что DateTime не вернул false
    		$dtObj = DateTime::createFromFormat('Y.m.d H:i:s', $date_sub);
    		if ($dtObj === false) { continue; } // пропускаем платёж с неверным форматом даты
    		$d = $dtObj->format('d.m.Y H:i:s');

    		$milsec = strtotime($d)*1000;
    		echo $uid." ".$date_sub_table." ".$milsec."\n\n";

    		if($milsec > $date_sub_table){
           		updateUserSub($uid, $link, getDateSub($uid, $link));
        	}
        }
	}
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


function getDateSubOrig($uid, $link){
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
               		return $date_end;
                }
            }else{
            	return false;
            }
        	
        
       }
    
    }
}

// закрываем подключение
mysqli_close($link);
?>
