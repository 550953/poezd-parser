<?php
require_once 'connection.php'; // подключаем скрипт


// выполняем операции с базой данных

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);





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
$email = "info@poezd.androiddev.xyz"; // от кого
$to = "sam4488831@mail.ru"; // кому
$sub = "Сообщение с чата \"Проводник вагона\""; // тема письма
$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";

if(mail($to, $subject, $messege, "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit")){
    $all_mass = array("messege" => true);
}else{
    $all_mass = array("messege" => false);
}

echo json_encode($all_mass);
?>