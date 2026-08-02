<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include '../simple_html_dom.php';

require_once '../api/connection.php'; // подключаем скрипт

require '../../php_mailer/Exception.php';
require '../../php_mailer/PHPMailer.php';
require '../../php_mailer/SMTP.php';


header('Content-type: application/json');

// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$train = $data['id_train'];
$train_name = $data['train_name'];
$full_name = $data['full_name'];
$name_station = $data['name'];
$code_station =  $data['code'];
$side_none = $data['side_none'];
$side_left = $data['side_left'];
$side_right = $data['side_right'];
$side_over = $data['side_over'];

$number_none = $data['number_none'];
$number_g = $data['number_g'];
$number_h = $data['number_h'];

$trush = $data['trush'];
$water = $data['water'];
$market = $data['market'];
$coal= $data['coal'];
$width =  $data['width'];
$sept =  $data['sept'];
$slag =  $data['slag'];
$pharmacy =  $data['pharmacy'];
$comment =  $data['comment'];
//sendMails("", "");
if($data['sept'] == null){
	$sept = 0;
	// $slag = 0;
}
if($full_name == null){
	$full_name = "";
	// $slag = 0;
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
$answer = "";
if($name_station != null && $code_station != "000000"){
	$comm = $comment;

	$query = "SELECT * FROM `rx_station_info`  WHERE `code` = '".$code_station."'";
    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

    if($count > 0){
     	if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            	$old_buffer = $row['buffer'];
            	$old_comment = $row['comment'];
            
            if(strlen($old_buffer) > 0){
            	$comment = $old_buffer." | ".$comment;
            $buf = $old_buffer." | ".$comment;
            }
            $new_buff = str_replace(" | ", "", $buf);
           
            $buf_arr = explode("\n", $new_buff);
            $com_arr = explode("\n", $old_comment);
            $clear = true;
            $record = false;
            foreach ($buf_arr as  $buf_sword) {
            	$buf_sword = preg_replace('/\W+/u', '', $buf_sword);
            	$record = false;
    			foreach ($com_arr as $com_sword) {
                	$com_sword = preg_replace('/\W+/u', '', $com_sword);
                	if ($com_sword==$buf_sword){
                    //	echo "CLEANqq";
                    	$record = true;
                    	break;
                    }
				}
            	if(!$record){
                	$clear = false;
            	}
			}
            if($clear && $old_comment != ""){
            	$comment = "";
            	//echo "CLEAN";
            }
            
            
            
            
            
            
            	$query = "UPDATE `rx_station_info` SET `trush` = '".$trush."', `water` = '".$water."', `market` = '".$market."', `coal` = 
                '".$coal."', `width` = '".$width."', `sept` = '".$sept."', `slag` = '".$slag."', `pharmacy` = '".$pharmacy."', `buffer` = '".$comment."',
                `comment_info` = '".$full_name."' WHERE `id` = '".$row['id']."'";
				$result = mysqli_query($link, $query);
            }
        
        }
    
    }else{
    	$query = "INSERT INTO `rx_station_info` (`id`, `name`, `code`, `trush`, `water`, `market`, `coal`, `width`, `sept`, `slag`, `pharmacy`, `buffer`, `comment_info`) VALUES 
        (NULL,'".$name_station."','".$code_station."','".$trush."','".$water."','".$market."','".$coal."','".$width."','".$sept."','".$slag."','".$pharmacy."','".$comment."','".$full_name."')";
    	mysqli_query($link, $query);
    }
	// if(strlen($comm) > 0){
	// 	sendMails($full_name, $comm);
	// }
$query = "SELECT `way_station`.`id` FROM `way_trains` LEFT JOIN `way_station` ON `way_trains`.`id`= `way_station`.`id_way` WHERE `number` = '".$train_name."' AND `code` = '".$code_station."'";
$result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);
if($count > 0){
     	if ($result) {
         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
         	$query = "UPDATE `way_station` SET `side_none`='".$side_none."',`side_left`='".$side_left."',`side_right`='".$side_right."',`side_over`='".$side_over."',`number_none`='".$number_none."',`number_h`='".$number_h."',`number_g`='".$number_g."' WHERE `id` = '".$row['id']."'";
							$test = $query;
                        	mysqli_query($link, $query);
         	$answer="upadate side";
         }
        }
}else if(isset($code_station)){
		$query = "SELECT `way_trains`.`id` FROM `way_trains` WHERE `number` = '".$train_name."' LIMIT 1";
		$result = mysqli_query($link, $query);
if ($result) {
         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
         	$query = "INSERT INTO `way_station`(`id`, `id_way`, `code`, `side_none`, `side_left`, `side_right`, `side_over`,`number_none`,`number_h`,`number_g`)
                    VALUES (NULL,'".$row['id']."','".$code_station."','".$side_none."','".$side_left."','".$side_right."','".$side_over."','".$number_none."','".$number_h."','".$number_g."')";
                	mysqli_query($link, $query);
         $answer="add side";
         }
        }

                	
                }



// 	$query = "SELECT * FROM `way_trains`  WHERE `number` = '".$train_name."'";
//     $result = mysqli_query($link, $query);
//     $count = mysqli_num_rows($result);

// 	if($count > 0){
//      	if ($result) {
//             while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//             	$id_train = $row['id'];
//             	$query = "SELECT * FROM `way_station`  WHERE `id_way` = '".$id_train."' AND `code` = '".$code_station."'";
//             	$test2 = $query;
// 				$resultSide = mysqli_query($link, $query);
//             	$count = mysqli_num_rows($resultSide);
//             	if($count > 0){
//                 	if ($resultSide) {
//            				while ($row = mysqli_fetch_array($resultSide, MYSQLI_ASSOC)) {
//             				$query = "UPDATE `way_station` SET `side_none`='".$side_none."',`side_left`='".$side_left."',`side_right`='".$side_right."',`side_over`='".$side_over."',`number_none`='".$number_none."',`number_h`='".$number_h."',`number_g`='".$number_g."' WHERE `id` = '".$row['id']."'";
// 							$test = $query;
//                         	mysqli_query($link, $query);
//             			}
//         			}
                	
//                 }else if(isset($code_station)){
//                 	$query = "INSERT INTO `way_station`(`id`, `id_way`, `code`, `side_none`, `side_left`, `side_right`, `side_over`,`number_none`,`number_h`,`number_g`) 
//                     VALUES (NULL,'".$id_train."','".$code_station."','".$side_none."','".$side_left."','".$side_right."','".$side_over."','".$number_none."','".$number_h."','".$number_g."')";
//                 	mysqli_query($link, $query);
//                 }
//             }
//         }
//     }
 $result_arr = array(  
            "messege" => true,
            "answer" => $answer
            
        );
   
//	$result_arr['messege'] = true;

	echo json_encode($result_arr);
}else{
	$result_arr['messege'] = true;
  
	echo json_encode($result_arr);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendMails($info, $text){

$emailSender = 'info@poezd.androiddev.xyz';
//$emailUser = 'baksys@yandex.ru';
$emailUser = 'sam4488831@yandex.ru';
$mail = new PHPMailer;
$mail->setFrom($emailSender, $title);
$mail->addAddress($emailUser,$emailUser);
$mail->Subject  = "Поезд: ".$info."\n";;
$mail->Body     = "Комментарий :".$text."\n";
$mail->send();

//$mail = new PHPMailer();
// try{
// //info@poezd.androiddev.xyz
//  //From email address and name 


// $mail->From = 'info@poezd.androiddev.xyz'; 
// $mail->Subject = "Добавление комментария"; 
// $mail->addAddress('sam4488831@yandex.ru,'); 
// $messege = "Поезд: ".$info."\n";
// $messege .= "Комментарий :".$text."\n";
// $mail->Body = $messege;
// $mail->AltBody = "This is the plain text version of the email content"; 
// $mail->send();
// } catch (Exception $e) {
//     //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }
	
      

}
// закрываем подключение
mysqli_close($link);
?>


