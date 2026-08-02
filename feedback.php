<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$messege = "Сообщение:\n";
$messege .= $data['message'];
$mail = $data['mail'];
$phone = $data['phone'];

if($data['mail'] != null || $data['phone'] != null){ 
	$str = "\n Контакты:\n E-mail:".$mail."\n Телефон:".$phone;
	$messege .= $str;
//,sam4488831@mail.ru
	$email = "send@poezd.androiddev.xyz"; // от кого
	$to = "sam4488831@mail.ru,sam4488831@gmail.com"; // кому
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
?>