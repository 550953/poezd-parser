<?php
require_once __DIR__ . '/../parse/api/BsLogger.php';
$_bs_req_start = microtime(true);

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// header('Content-type: application/json');

require_once '../parse/api/connection.php'; // подключаем скрипт
require './yookassa-sdk-php-master/lib/autoload.php'; 

use YooKassa\Client;


$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");



$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$payment_token = $data['token'] ?? null;
$uid = $data['uid'] ?? null;
$update = $data['update'] ?? null;
$delete = $data['delete'] ?? null;
$idSber =  $data['idSber'] ?? null;
$payment =  $data['payment'] ?? null;
$refresh =  $data['refresh'] ?? null;
$type =  $data['type'] ?? null;
$get_pay =  $data['get_pay'] ?? null;

$payCount = 100;
$payText = "100 дней";

$query = "SELECT * FROM `settings` WHERE `id` = 2";
$result = mysqli_query($link, $query);
	if($result){
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$payCount =  $row['value'];
        	$payText = $row['period'];
		}
	}

if(isset($uid) && isset($update)){
	$sub = updateUserSub($uid, $link);
	$res = updateOrderPayInfo($uid, $link);
 	
	BsLogger::event('info','mine_yookassa','payment_confirmed',['uid_hash'=>substr(md5($uid),0,8)]);
BsLogger::request('/api/poezd/yookassa/mine_yookassa.php',200,round((microtime(true)-$_bs_req_start)*1000,2));
echo json_encode(array('record' => $res, 'sub' => $sub));
}

if(isset($uid) && isset($refresh)){
	//$res = refreshPayment($uid, $link);
}
if(isset($uid) && isset($payment)){
	$res = savePayment($uid, $payment, $link);
 
	echo json_encode(array('record' => $res));
}


if(isset($get_pay) ){

	echo json_encode(array('pay_count' => $payCount, 'pay_period' => $payText));
}

if(isset($uid) && isset($delete)){
	$res = deleteOrderPayInfo($uid, $link);
	echo json_encode(array('record' => $res));
}
if(isset($idSber)){
	statusPay($idSber);
}
function statusPay($idSber){
$client = new Client();
  $client->setAuth('477830','live_lWtNFGAbPNZVs2-Az1CXGUHXazHpNqwKMCyPYT-ej9s');
  $client->setConfig(["timeout" => 20, "connect_timeout" => 10]); // timeout fix
  	$payment = $client->getPaymentInfo($idSber);
	echo json_encode(array('payment' => $payment));
}
function refreshPayment($uid, $link){
	$query = "SELECT * FROM `pay_yookassa` WHERE `uid_user` = '".$uid."' AND  `status` = 'CREATE'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $payment = $row['payment'];
        	statusPay($payment);
        	
        }
    	
    }
}

function savePayment($uid, $payment, $link){
	$query = "SELECT * FROM `pay_yookassa` WHERE `uid_user` = '".$uid."' AND  `status` = 'CREATE'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        	$pay = "UPDATE `pay_yookassa` SET `payment`= '".$payment."' WHERE `id` = '".$id."'";
        	$resultPay = mysqli_query($link, $pay);
        	
        }
    	return true;
    }else{
		return false;
    }
}
function deleteOrderPayInfo($uid, $link){
$query = "SELECT * FROM `pay_yookassa` WHERE `uid_user` = '".$uid."' AND  `status` = 'CREATE'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        	$pay = "DELETE FROM `pay_yookassa` WHERE `id` = '".$id."'";
        	$resultPay = mysqli_query($link, $pay);
        	
        }
    	return true;
    }else{
		return false;
    }
	
}

if(isset($payment_token)){
	$date_sub_user = getDateSub($uid, $link);
	$string = $uid." ".$date_sub_user;
  $client = new Client();
  $client->setAuth('477830','live_lWtNFGAbPNZVs2-Az1CXGUHXazHpNqwKMCyPYT-ej9s');
  $client->setConfig(["timeout" => 20, "connect_timeout" => 10]); // timeout fix
	$response = null;
$_bs_pay_t = microtime(true);
 try {
 	if($type == "SBP"){
    $response = $client-> createPayment(
        array(
        //'payment_token' => $payment_token,
         'payment_method_data' => array(
                'type' => 'sbp',
            ),
            'amount' => array(
                'value' => $payCount,
                'currency' => 'RUB',
            ),
         'confirmation' => array(
                'type' => 'redirect',
                'return_url' => 'https://provodnik.xyz/api/poezd/yookassa/success.php',
            ),
            'capture' => true,
            'description' => $string,
        ),
        uniqid('', true)
    );
    }else{
    $response = $client-> createPayment(
        array(
        'payment_token' => $payment_token,
        
            'amount' => array(
                'value' => $payCount,
                'currency' => 'RUB',
            ),
         'confirmation' => array(
                'type' => 'redirect',
                'return_url' => 'https://provodnik.xyz/api/poezd/yookassa/success.php',
            ),
            'capture' => true,
            'description' => $string,
        ),
        uniqid('', true)
    );
    }
  
 } catch (Exception $e) {
     echo 'Caught exception: ',  $e->getMessage(), "\n";
}


if(isset($response)){
	$res = false;
	if(isset($uid)){
    	$res = createOrderPayInfo($uid, $link);
    }
	$confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
	echo json_encode(array('record' => $res, 'status' => true, 'confirmation_url' => $confirmationUrl));
}else{
	echo json_encode(array('status' => false, 'pay' => $payment_token));
}


//print_r($response);
}
function setSubUser($date_end, $time, $id, $link){
    $now = microtime(true) * 1000;
    if($date_end > $now){
    	$times = $date_end + $time;
    }else{
    	$times = $now + $time;
    }
    $query = "UPDATE `subscription` SET `date_end` = ".$times." WHERE `id` = ".$id;
   // echo $query;
    $result = mysqli_query($link, $query);
	return $result;
   // echo $result;

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
               		return $res;
                }
            }else{
            	return false;
            }
        	
        
       }
    
    }
}



function createOrderPayInfo($uid, $link){
	$pay = "INSERT INTO `pay_yookassa` (`id`, `uid_user`, `status`, `date`)
            VALUES (NULL,'".$uid."','CREATE' ,CURRENT_TIMESTAMP)";
        	$resultPay = mysqli_query($link, $pay);
	return $resultPay;
}


function updateOrderPayInfo($uid, $link){
$dateInt = getDateSubInt($uid, $link);
$query = "SELECT * FROM `pay_yookassa` WHERE `uid_user` = '".$uid."' AND  `status` = 'CREATE'";
	$result = mysqli_query($link, $query);
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
        	$pay = "UPDATE `pay_yookassa` SET `status`='PAID',`date_sub`='".$dateInt."',`date`= CURRENT_TIMESTAMP WHERE `id` = '".$id."'";
        	$resultPay = mysqli_query($link, $pay);
        	
        }
    	return true;
    }else{
		return false;
    }
	
}


function getDateSubInt($uid, $link){
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
    				//$res = date('Y-m-d H:i:s', $times / 1000);
               		return $times;
                }
            }else{
            	return false;
            }
        	
        
       }
    
    }
}


function updateUserSub($uid, $link){
	deleteOrderPayInfo($uid, $link);
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
					$res = setSub($date_end, $day, $id_sub, $link);
               		return $res;
                }
            }else{
            	return false;
            }
        	
        
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
   // echo $query;
    $result = mysqli_query($link, $query);
	return $result;
   // echo $result;

}


// 'receipt': {
//           'customer': {
//             'email': 'user@example.com'
//           },
          
//           'items': [
//             {
//               'description': 'Ложка',
//               'quantity': 10,
//               'amount': {
//                 'value': '50.00',
//                 'currency': 'RUB'
//               },
//               'vat_code': 1
//             },
//            ],
        
?>