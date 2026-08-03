<?php
require_once __DIR__ . '/BsLogger.php';
if (PHP_SAPI !== 'cli') { header('Content-Type: application/json; charset=utf-8'); http_response_code(403); echo json_encode(array('error' => 'cli_only')); exit; }
@include_once '/home/provodnik/logs/poezd-diagnostics.php';
if (!function_exists('poezd_diag_log')) { function poezd_diag_log($event, $fields = array()) {} }
if (!function_exists('poezd_diag_hash')) { function poezd_diag_hash($value) { return 'unknown'; } }
if (!function_exists('poezd_diag_request_id')) { function poezd_diag_request_id() { return 'unknown'; } }
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
$poezd_diag_id = poezd_diag_request_id();
$poezd_diag_started = microtime(true);
poezd_diag_log('load_sub.start', array('request_id' => $poezd_diag_id, 'sapi' => PHP_SAPI));
register_shutdown_function(function () use ($poezd_diag_id, $poezd_diag_started) {
    $last = error_get_last();
    poezd_diag_log('load_sub.finish', array('request_id' => $poezd_diag_id, 'duration_ms' => round((microtime(true) - $poezd_diag_started) * 1000, 2), 'fatal_type' => $last ? $last['type'] : null));
    if(isset($_bs_cron_start)) BsLogger::cron('load_sub','finished',round(microtime(true)-$_bs_cron_start,3));
});

// === LOCK: защита от параллельного запуска ===
$lockFile = '/tmp/load_sub.lock';
if (file_exists($lockFile) && (time() - filemtime($lockFile)) < 300) {
    poezd_diag_log('load_sub.lock_busy', array('request_id' => $poezd_diag_id));
    BsLogger::cron('load_sub','skipped_lock',0);
    BsLogger::flush();
    exit("Already running\n");
}
@file_put_contents($lockFile, getmypid());
poezd_diag_log('load_sub.lock_acquired', array('request_id' => $poezd_diag_id));
BsLogger::cron('load_sub','started',0);
$_bs_cron_start = microtime(true);
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
poezd_diag_log('load_sub.mysql_connected', array('request_id' => $poezd_diag_id));
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
poezd_diag_log('load_sub.yookassa_start', array('request_id' => $poezd_diag_id));
  	$client->setAuth('477830','live_lWtNFGAbPNZVs2-Az1CXGUHXazHpNqwKMCyPYT-ej9s');
  $client->setConfig(["timeout" => 20, "connect_timeout" => 10]);

  	// ФИКС: только платежи за последние 24 часа, не все за всё время
  	$receipts = $client->getPayments([
	    'created_at.gte' => date('Y-m-d\TH:i:s\Z', strtotime('-24 hours')),
	    'status' => 'succeeded',
	    'limit' => 100
	]);
	$receipts = $receipts['items'];
  BsLogger::event('info','load_sub','yookassa_finish',['duration_ms'=>round((microtime(true)-$_bs_yk_t)*1000,2),'count'=>count($receipts)]);
poezd_diag_log('load_sub.yookassa_finish', array('request_id' => $poezd_diag_id, 'payments_count' => count($receipts)));
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
global $poezd_diag_id;
poezd_diag_log('load_sub.subscription_update_start', array('request_id' => $poezd_diag_id, 'uid_hash' => poezd_diag_hash($uid)));
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
                    $update_result = mysqli_query($link, $query);
                    if ($update_result === false) {
                        poezd_diag_log('load_sub.subscription_update_error', array('request_id' => $poezd_diag_id, 'mysql_errno' => mysqli_errno($link), 'mysql_state' => mysqli_sqlstate($link)));
                    }
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
