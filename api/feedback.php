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







function saveEmail ($data,$link){
    $name = $data['name'];
    $email = $data['mail'];
    $message = $data['message'];
    $vagon = $data['vagon'];
    $train = $data['train'];
    $date = $data['date'];
    
     $query = "INSERT INTO email VALUES (NULL,'".$name."','".$email."','".$message."','".$vagon."','".$train."','".$date."')";
    $result = mysqli_query($link, $query);
}

// закрываем подключение

if($data['mail'] != null || $data['phone'] != null || $data['message'] != null){

	saveEmail($data,$link);

	$messege = "Поезд: ".$data['train']."\n";
	$messege .= "Имя: ".$data['name']."\n";
	$messege .= "Дата отправления: ".$data['date']."\n";
	$messege .= "Вагон: ".$data['vagon']."\n";
	$messege .= "Сообщение:\n";
	$messege .= $data['message'];
	$mail = $data['mail'];
	$phone = $data['phone'];

	$str = "\n Контакты:\n E-mail:".$mail;
	$messege .= $str;
//,sam4488831@mail.ru
//
//$email = "provodnik@provodnik.xyz";
	$email = "send@provodnik.xyz"; // от кого
	$to = "sam4488831@mail.ru,sam4488831@yandex.ru" ; // кому
	$sub = "Вопрос с обратной связи приложения \"Проводник вагона\""; // тема письма
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