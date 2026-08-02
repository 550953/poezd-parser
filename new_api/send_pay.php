<?php


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$messege = "Сообщение:\n";
$messege .= $data['message'];
//$messege .= $_GET['message'];
//,sam4488831@mail.ru
//,sam4488831@mail.ru
//$email = "provodnik@provodnik.xyz";
$email = "send@provodnik.xyz"; // от кого
$to = "sam4488831@mail.ru,sam4488831@yandex.ru"; // кому
//$to = "baksys@ya.ru"; // кому
$sub = "Оплата в приложения \"Проводник вагона\""; // тема письма
$subject = "=?utf-8?B?" . base64_encode($sub) . "?=";

if(mail($to, $subject, $messege, "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit")){
    $all_mass = array("messege" => true);
}else{
    $all_mass = array("messege" => false);
}

echo json_encode($all_mass);
?>