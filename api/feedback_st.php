<?php
require_once 'connection.php'; // подключаем скрипт
header('Content-type: application/json');
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);








// закрываем подключение

if(isset($data['station1'])){

	$messege = "Поезд: ".$data['name']."\n";
	$messege .= "Станция 1: ".$data['station1_name']." код:".$data['station1']."\n";
	$messege .= "Станция 2: ".$data['station2_name']." код:".$data['station2']."\n";
	$messege .= "Дата отправления: ".$data['date']."\n";
	$messege .= "".$data['pereg']."\n";

	$messege .= $str;
//,sam4488831@mail.ru
//
//$email = "provodnik@provodnik.xyz";
	$email = "send@provodnik.xyz"; // от кого
//	$to = "sam4488831@mail.ru,sam4488831@yandex.ru" ; // кому
	//$to = "baksys@ya.ru" ; 
	$sub = "Обнаружени отрицательный перегон \"Проводник вагона\""; // тема письма
	$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";

	if(mail($to, $subject, $messege, "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit")){
   	 $all_mass = array("messege" => true);
	}else{
    	$all_mass = array("messege" => false);
	}

       echo json_encode($all_mass);
}else{
 echo "ERROR!!!";
}
mysqli_close($link);
?>